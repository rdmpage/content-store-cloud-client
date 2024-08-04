<?php

//----------------------------------------------------------------------------------------
function get($url, $format = '')
{
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	
	if ($format != '')
	{
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: " . $format));	
	}
	
	$response = curl_exec($ch);
	if($response == FALSE) 
	{
		$errorText = curl_error($ch);
		curl_close($ch);
		die($errorText);
	}
	
	$info = curl_getinfo($ch);
	$http_code = $info['http_code'];
	
	curl_close($ch);
	
	return $response;
}

//----------------------------------------------------------------------------------------
// http://stackoverflow.com/questions/247678/how-does-mediawiki-compose-the-image-paths
function hash_to_path_array($hash)
{
	preg_match('/^(..)(..)(..)/', $hash, $matches);
	
	$hash_path_parts = array();
	$hash_path_parts[] = $matches[1];
	$hash_path_parts[] = $matches[2];
	$hash_path_parts[] = $matches[3];

	return $hash_path_parts;
}

//----------------------------------------------------------------------------------------
// Return path for a sha1
function hash_to_path($hash)
{
	$hash_path_parts = hash_to_path_array($hash);
	
	$hash_path = join("/", $hash_path_parts);

	return $hash_path;
}

//----------------------------------------------------------------------------------------
// Create nested folders in folder "root" based on sha1
function create_path_from_hash($hash, $root = '')
{	
	$hash_path_parts 	= hash_to_path_array($hash);
	$hash_path 			= hash_to_path($hash);
	
	$filename = $root;
	if ($root != '')
	{
		$filename .= '/';
	}
	$filename .= $hash_path . '/' . $hash;
				
	return $filename;
}

//----------------------------------------------------------------------------------------
function sanitise_filename($filename)
{
	$filename = strip_tags($filename);

	// https://stackoverflow.com/a/42058764
	$filename = preg_replace(
	'~
	[<>:"/\\\|?*]|           # file system reserved https://en.wikipedia.org/wiki/Filename#Reserved_characters_and_words
	[\x00-\x1F]|             # control characters http://msdn.microsoft.com/en-us/library/windows/desktop/aa365247%28v=vs.85%29.aspx
	[\x7F\xA0\xAD]|          # non-printing characters DEL, NO-BREAK SPACE, SOFT HYPHEN
	[#\[\]@!$&\'()+,;=]|     # URI reserved https://www.rfc-editor.org/rfc/rfc3986#section-2.2
	[{}^\~`]                 # URL unsafe characters https://www.ietf.org/rfc/rfc1738.txt
	~x',
	'-', $filename);

	return $filename;
}

?>
