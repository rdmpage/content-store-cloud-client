<?php

require_once (dirname(__FILE__) . '/b2.php');
require_once (dirname(__FILE__) . '/utils.php');

//----------------------------------------------------------------------------------------
/*
Based on https://github.com/andrieslouw/imagesweserv, also borrows from 
http://stackoverflow.com/questions/16847015/php-stream-remote-pdf-to-client-browser

Make remote PDF's cachable and accessible by pdf.js 

*/

function download_file($path,$fname){
	$options = array(
		CURLOPT_FILE => fopen($fname, 'w'),
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_URL => $path,
		CURLOPT_FAILONERROR => true, // HTTP code > 400 will throw curl error
		CURLOPT_TIMEOUT => 60,
		CURLOPT_CONNECTTIMEOUT => 5,
		CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; ImageFetcher/5.6; +http://images.weserv.nl/)',
	);
	
	//print_r($options);
	
	$ch = curl_init();
	curl_setopt_array($ch, $options);
	$return = curl_exec($ch);
	
	if ($return === false){
		$error = curl_error($ch);
		$errno = curl_errno($ch);
		curl_close($ch);
		unlink($fname);
		$error_code = substr($error,0,3);
		
		if($errno == 6){
			header('HTTP/1.1 410 Gone');
			header('X-Robots-Tag: none');
			header('X-Gone-Reason: Hostname not in DNS or blocked by policy');
			echo 'Error 410: Server could parse the ?url= that you were looking for "' . $path . '", because the hostname of the origin is unresolvable (DNS) or blocked by policy.';
			echo 'Error: $error';
			die;
		}
		
		if(in_array($error_code,array('400','403','404','500','502'))){
			trigger_error('cURL Request error: '.$error.' URL: '.$path,E_USER_WARNING);
		}
		return array(false,$error);
	}else{
		curl_close($ch);
		return array(true,NULL);
	}
}


//----------------------------------------------------------------------------------------
function main()
{
	global $config;
	
	if (!isset($config['downloadUrl']))
	{
		$authorised = b2_authorize_account();
		$config['downloadUrl'] =  $authorised['downloadUrl'];
	}
	
	$downloadUrl = $config['downloadUrl'];	
	
	$handled = false;
	
	if (!$handled)
	{			
		if (isset($_GET['sha1']))
		{
			$sha1 = $_GET['sha1'];
			
			$content_filepath = create_path_from_hash($sha1, '');
	
			if (isset($_GET['info']))
			{
				// URL to JSON file with details of content
				$content_url = $downloadUrl . '/file/' . $config['bucket'] . '/' . $content_filepath . '_info.json';				
				
				// simple redirect to B2
				header("Location: $content_url");
			}		
			else
			{
				// URL to content itself
				$content_url = $downloadUrl . '/file/' . $config['bucket'] . '/' . $content_filepath . '.pdf';		
				
				if (0)
				{
					// simple redirect to B2
					header("Location: $content_url");				
				}
				else
				{
					// fetch PDF
					$path = $content_url;
					$path = str_replace(' ','%20',$path);
					$fname = tempnam(sys_get_temp_dir(), 'pdf_');
					$curl_result = download_file($path,$fname);
					if($curl_result[0] === false){
						header("HTTP/1.0 404 Not Found");
						echo 'Error 404: Server could parse the ?url= that you were looking for, error it got: '.$curl_result[1];
						die;
					}
					
					//header('Expires: '.gmdate("D, d M Y H:i:s", (time()+2678400)).' GMT'); //31 days
					//header('Cache-Control: max-age=2678400'); //31 days
				
					header('Content-Type: application/pdf');	
					header('Content-Length: ' . filesize($fname));
				
					ob_start();
					readfile($fname);
					ob_end_flush();
				}				
			}
			
			$handled = true;
	
			exit();
		}
	}
	
	if (!$handled)
	{		
?>

<html>
<head>
<title>Content Store</title>
<style>
body {
	padding:2em;
	font-family:sans-serif;
}
</style>
</head>
<body>
<h1>Content Store</h1>
<p>Hash-based content store.</p>
<h2>Examples</h2>

<h3>Clean URI</h3>
<ul>
<li><a href="sha1/b99afa9a11a75ef3d019d635e5c004ebf6852050">sha1/b99afa9a11a75ef3d019d635e5c004ebf6852050</a></li>
<li><a href="sha1/a555bd961c5651133e4cbed4392cd9103028804e">sha1/a555bd961c5651133e4cbed4392cd9103028804e</a></li>
<li><a href="sha1/1867f8bcb8a9e39974e1206de5cec638f71df78e">sha1/1867f8bcb8a9e39974e1206de5cec638f71df78e</a></li>
<li><a href="sha1/308befb39be8b30d0e22fa4e7c3b6ca07583c326">sha1/308befb39be8b30d0e22fa4e7c3b6ca07583c326</a></li>
</ul>

<h3>URLs written as hash URIs</h3>

<p>See <a href="https://github.com/hash-uri/hash-uri">Hash URI Specification (Initial Draft)</a> for a definition of hash URIs.</p>

<ul>
<li><a href="./hash://sha1/b99afa9a11a75ef3d019d635e5c004ebf6852050">hash://sha1/b99afa9a11a75ef3d019d635e5c004ebf6852050</a></li>
<li><a href="./hash://sha1/a555bd961c5651133e4cbed4392cd9103028804e">hash://sha1/a555bd961c5651133e4cbed4392cd9103028804e</a></li>
<li><a href="./hash://sha1/1867f8bcb8a9e39974e1206de5cec638f71df78e">hash://sha1/1867f8bcb8a9e39974e1206de5cec638f71df78e</a></li>
<li><a href="./hash://sha1/308befb39be8b30d0e22fa4e7c3b6ca07583c326">hash://sha1/308befb39be8b30d0e22fa4e7c3b6ca07583c326</a></li>
</ul>


</body>
</html>


<?php
		$handled = true;
	}	
}

main ();

?>
