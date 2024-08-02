<?php

require_once (dirname(__FILE__) . '/config.inc.php');

//----------------------------------------------------------------------------------------
function b2_authorize_account() {

	global $config;	

	$url = 'https://api.backblazeb2.com/b2api/v2/b2_authorize_account';

	$ch = curl_init( $url );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	
	curl_setopt( $ch, CURLOPT_HTTPHEADER, 
			array(
				"Authorization: Basic " . base64_encode($config['b2_key_id'] . ':' . $config['b2_key'])
			)
		);

	$output = curl_exec( $ch );
	curl_close( $ch );

	$result = json_decode( $output, true );
	
	return $result;
}

//----------------------------------------------------------------------------------------
function b2_get_upload_url($authorization)
{
	$data = new stdclass;
	$data->bucketId = $authorization['allowed']['bucketId'];
	
	$url = $authorization['apiUrl'] . '/b2api/v2/b2_get_upload_url';
	
	$ch = curl_init( $url );
	curl_setopt( $ch, CURLOPT_POST, true );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );	
	curl_setopt( $ch, CURLOPT_HTTPHEADER, 
			array(
				"Authorization: " . $authorization['authorizationToken']
			)
		);

	$output = curl_exec( $ch );
	curl_close( $ch );

	$result = json_decode( $output, true );
	
	return $result;
}


//----------------------------------------------------------------------------------------
function b2_upload_file($upload, $filepath, $b2_filename)
{
	$sha1 = sha1_file($filepath);
	$size = filesize($filepath);
	$mimetype = mime_content_type($filepath);
	
	$headers = array(
				"Authorization: " . $upload['authorizationToken'],
				"X-Bz-File-Name: " . rawurlencode($b2_filename),
				"Content-Type:  " . $mimetype,
				"Content-Length: " . $size,
				"X-Bz-Content-Sha1: " . $sha1
			);			

	$ch = curl_init();

	curl_setopt( $ch, CURLOPT_URL, $upload['uploadUrl'] );
	curl_setopt( $ch, CURLOPT_POST, true );
	
	curl_setopt( $ch, CURLOPT_POSTFIELDS, file_get_contents($filepath) );
	
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);

	$output = curl_exec( $ch );
	curl_close( $ch );
	
	$result = json_decode( $output, true );
	
	return $result;
}

?>
