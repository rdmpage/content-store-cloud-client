<?php

require_once (dirname(__FILE__) . '/b2.php');
require_once (dirname(__FILE__) . '/utils.php');


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
			}		
			else
			{
				// URL to content itself
				$content_url = $downloadUrl . '/file/' . $config['bucket'] . '/' . $content_filepath . '.pdf';		
			}
			
			$handled = true;
	
			// simple redirect to B2
			header("Location: $content_url");
			
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
