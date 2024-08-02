<?php

error_reporting(E_ALL);

global $config;

// Date timezone--------------------------------------------------------------------------
date_default_timezone_set('UTC');

// Multibyte strings----------------------------------------------------------------------
mb_internal_encoding("UTF-8");

// Environment----------------------------------------------------------------------------
// In development this is a PHP file that is in .gitignore, when deployed these parameters
// will be set on the server
if (file_exists(dirname(__FILE__) . '/env.php'))
{
	include 'env.php';
}

// B2 Cloud Storage-----------------------------------------------------------------------
$config['b2_key_id'] 	= getenv('B2_APPLICATIONKEYID');
$config['b2_key'] 		= getenv('B2_APPLICATIONKEY');

$config['downloadUrl'] 	= 'https://f000.backblazeb2.com';
$config['bucket']		= 'content-store';


// B2 drive mounted locally
$config['content'] = '/Users/rpage/Library/CloudStorage/CloudMounter-content-store';

?>
