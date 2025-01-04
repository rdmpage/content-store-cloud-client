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
			echo 'Error 410: Server could not parse the ?url= that you were looking for "' . $path . '", because the hostname of the origin is unresolvable (DNS) or blocked by policy.';
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
			
			$mime_type = '';
			
			// Get details on file
				
			// URL to JSON file with details of content
			$content_url = $downloadUrl . '/file/' . $config['bucket'] . '/' . $content_filepath . '_info.json';				
			
			$json = get($content_url);
			
			$content_info = json_decode($json);
			if (!$content_info)
			{
				// badness
				header('HTTP/1.1 404 Not Found');
				exit();
			}
			
			// We just want metadata as is
			if (isset($_GET['info']))
			{
				// simple redirect to B2
				header("Location: $content_url");
			}
			else
			{
				// URL to content itself

				$content_url = $downloadUrl . '/file/' . $config['bucket'] . '/' . $content_filepath . '.' . mime2ext($content_info->mimetype);

				// fetch content and stream it so that Cloudflare will cache it and
				// hypothes.is can use it
				$path = $content_url;
				$path = str_replace(' ','%20',$path);
				$fname = tempnam(sys_get_temp_dir(), 'content_');
				$curl_result = download_file($path,$fname);
				if($curl_result[0] === false){
					header("HTTP/1.0 404 Not Found");
					echo 'Error 404: Server could not parse the ?url= that you were looking for, error it got: '.$curl_result[1];
					die;
				}
			
				header('Content-Type: ' . $content_info->mimetype);	
				header('Content-Length: ' . filesize($fname));
			
				ob_start();
				readfile($fname);
				ob_end_flush();
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
<p>Hash-based content store. Currently stores PDFs and images and makes them addressable using the
<a href="https://en.wikipedia.org/wiki/SHA-1">SHA-1</a> hash of the PDF file.</p>

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

<h3>View and annotate in Hypothes.is</h3>

<p><a href="https://web.hypothes.is">Hypothesis</a> provide a way to <a href="https://github.com/hypothesis/pdf.js-hypothes.is">view and annotate PDFs using PDF.js</a>.
The links below use a local copy of PDF.js + Hypothesis. You can also use Hypothesis by appending the URL for a PDF to <a href="https://via.hypothes.is">Via</a>, 
for example <a href="https://via.hypothes.is/https://content.bionames.org/sha1/b99afa9a11a75ef3d019d635e5c004ebf6852050">https://via.hypothes.is/https://content.bionames.org/sha1/b99afa9a11a75ef3d019d635e5c004ebf6852050</a>.
</p>

<ul>
<li><a href="pdf.js-hypothes.is/viewer/web/viewer.html?file=../../../sha1/b99afa9a11a75ef3d019d635e5c004ebf6852050">sha1/b99afa9a11a75ef3d019d635e5c004ebf6852050</a></li>
<li><a href="pdf.js-hypothes.is/viewer/web/viewer.html?file=../../../sha1/a555bd961c5651133e4cbed4392cd9103028804e">sha1/a555bd961c5651133e4cbed4392cd9103028804e</a></li>
<li><a href="pdf.js-hypothes.is/viewer/web/viewer.html?file=../../../sha1/1867f8bcb8a9e39974e1206de5cec638f71df78e">sha1/1867f8bcb8a9e39974e1206de5cec638f71df78e</a></li>
<li><a href="pdf.js-hypothes.is/viewer/web/viewer.html?file=../../../sha1/308befb39be8b30d0e22fa4e7c3b6ca07583c326">sha1/308befb39be8b30d0e22fa4e7c3b6ca07583c326</a></li>
</ul>


<h3>Hypothes.is annotations on PDFs are (mostly) independent of where PDF is stored</h3>

<p>Annotations on PDF at original location, and same PDF in content store.</p>


<ul>
<li><a href="https://dialnet.unirioja.es/descarga/articulo/9138278.pdf" target="_new">PDF at original location https://via.hypothes.is/https://dialnet.unirioja.es/descarga/articulo/9138278.pdf</li>
<li><a href="https://via.hypothes.is/https://dialnet.unirioja.es/descarga/articulo/9138278.pdf" target="_new">PDF at original location viewed using via.hypothes.is</li>
<li><a href="pdf.js-hypothes.is/viewer/web/viewer.html?file=../../../sha1/f91760d398ca2ddf49e92b06f7fa911c281e8400" target="_new">PDF in content store viewed using PDF.js + Hypothesis</a></li>
</ul>


<h3>Images</h3>

<dl>
  <dt><a href="sha1/3135c0bc7c9c3e689d20c02fac582a05e52e82a3">3135c0bc7c9c3e689d20c02fac582a05e52e82a3</a></dt>
  <dd><img style="border:1px solid #EEE" height="200" src="sha1/3135c0bc7c9c3e689d20c02fac582a05e52e82a3"></dd>
</dl>

</body>
</html>


<?php
		$handled = true;
	}	
}

main ();

?>
