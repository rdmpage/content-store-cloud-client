<?php

require_once (dirname(__FILE__) . '/b2.php');
require_once (dirname(__FILE__) . '/utils.php');

//----------------------------------------------------------------------------------------
// https://gist.github.com/alexcorvi/df8faecb59e86bee93411f6a7967df2c?permalink_comment_id=2722664#gistcomment-2722664
function mime2ext($mime) {
        $mime_map = [
            'video/3gpp2'                                                               => '3g2',
            'video/3gp'                                                                 => '3gp',
            'video/3gpp'                                                                => '3gp',
            'application/x-compressed'                                                  => '7zip',
            'audio/x-acc'                                                               => 'aac',
            'audio/ac3'                                                                 => 'ac3',
            'application/postscript'                                                    => 'ai',
            'audio/x-aiff'                                                              => 'aif',
            'audio/aiff'                                                                => 'aif',
            'audio/x-au'                                                                => 'au',
            'video/x-msvideo'                                                           => 'avi',
            'video/msvideo'                                                             => 'avi',
            'video/avi'                                                                 => 'avi',
            'application/x-troff-msvideo'                                               => 'avi',
            'application/macbinary'                                                     => 'bin',
            'application/mac-binary'                                                    => 'bin',
            'application/x-binary'                                                      => 'bin',
            'application/x-macbinary'                                                   => 'bin',
            'image/bmp'                                                                 => 'bmp',
            'image/x-bmp'                                                               => 'bmp',
            'image/x-bitmap'                                                            => 'bmp',
            'image/x-xbitmap'                                                           => 'bmp',
            'image/x-win-bitmap'                                                        => 'bmp',
            'image/x-windows-bmp'                                                       => 'bmp',
            'image/ms-bmp'                                                              => 'bmp',
            'image/x-ms-bmp'                                                            => 'bmp',
            'application/bmp'                                                           => 'bmp',
            'application/x-bmp'                                                         => 'bmp',
            'application/x-win-bitmap'                                                  => 'bmp',
            'application/cdr'                                                           => 'cdr',
            'application/coreldraw'                                                     => 'cdr',
            'application/x-cdr'                                                         => 'cdr',
            'application/x-coreldraw'                                                   => 'cdr',
            'image/cdr'                                                                 => 'cdr',
            'image/x-cdr'                                                               => 'cdr',
            'zz-application/zz-winassoc-cdr'                                            => 'cdr',
            'application/mac-compactpro'                                                => 'cpt',
            'application/pkix-crl'                                                      => 'crl',
            'application/pkcs-crl'                                                      => 'crl',
            'application/x-x509-ca-cert'                                                => 'crt',
            'application/pkix-cert'                                                     => 'crt',
            'text/css'                                                                  => 'css',
            'text/x-comma-separated-values'                                             => 'csv',
            'text/comma-separated-values'                                               => 'csv',
            'application/vnd.msexcel'                                                   => 'csv',
            'application/x-director'                                                    => 'dcr',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
            'application/x-dvi'                                                         => 'dvi',
            'message/rfc822'                                                            => 'eml',
            'application/x-msdownload'                                                  => 'exe',
            'video/x-f4v'                                                               => 'f4v',
            'audio/x-flac'                                                              => 'flac',
            'video/x-flv'                                                               => 'flv',
            'image/gif'                                                                 => 'gif',
            'application/gpg-keys'                                                      => 'gpg',
            'application/x-gtar'                                                        => 'gtar',
            'application/x-gzip'                                                        => 'gzip',
            'application/mac-binhex40'                                                  => 'hqx',
            'application/mac-binhex'                                                    => 'hqx',
            'application/x-binhex40'                                                    => 'hqx',
            'application/x-mac-binhex40'                                                => 'hqx',
            'text/html'                                                                 => 'html',
            'image/x-icon'                                                              => 'ico',
            'image/x-ico'                                                               => 'ico',
            'image/vnd.microsoft.icon'                                                  => 'ico',
            'text/calendar'                                                             => 'ics',
            'application/java-archive'                                                  => 'jar',
            'application/x-java-application'                                            => 'jar',
            'application/x-jar'                                                         => 'jar',
            'image/jp2'                                                                 => 'jp2',
            'video/mj2'                                                                 => 'jp2',
            'image/jpx'                                                                 => 'jp2',
            'image/jpm'                                                                 => 'jp2',
            'image/jpeg'                                                                => 'jpeg',
            'image/pjpeg'                                                               => 'jpeg',
            'application/x-javascript'                                                  => 'js',
            'application/json'                                                          => 'json',
            'text/json'                                                                 => 'json',
            'application/vnd.google-earth.kml+xml'                                      => 'kml',
            'application/vnd.google-earth.kmz'                                          => 'kmz',
            'text/x-log'                                                                => 'log',
            'audio/x-m4a'                                                               => 'm4a',
            'application/vnd.mpegurl'                                                   => 'm4u',
            'audio/midi'                                                                => 'mid',
            'application/vnd.mif'                                                       => 'mif',
            'video/quicktime'                                                           => 'mov',
            'video/x-sgi-movie'                                                         => 'movie',
            'audio/mpeg'                                                                => 'mp3',
            'audio/mpg'                                                                 => 'mp3',
            'audio/mpeg3'                                                               => 'mp3',
            'audio/mp3'                                                                 => 'mp3',
            'video/mp4'                                                                 => 'mp4',
            'video/mpeg'                                                                => 'mpeg',
            'application/oda'                                                           => 'oda',
            'application/vnd.oasis.opendocument.text'                                   => 'odt',
            'application/vnd.oasis.opendocument.spreadsheet'                            => 'ods',
            'application/vnd.oasis.opendocument.presentation'                           => 'odp',
            'audio/ogg'                                                                 => 'ogg',
            'video/ogg'                                                                 => 'ogg',
            'application/ogg'                                                           => 'ogg',
            'application/x-pkcs10'                                                      => 'p10',
            'application/pkcs10'                                                        => 'p10',
            'application/x-pkcs12'                                                      => 'p12',
            'application/x-pkcs7-signature'                                             => 'p7a',
            'application/pkcs7-mime'                                                    => 'p7c',
            'application/x-pkcs7-mime'                                                  => 'p7c',
            'application/x-pkcs7-certreqresp'                                           => 'p7r',
            'application/pkcs7-signature'                                               => 'p7s',
            'application/pdf'                                                           => 'pdf',
            'application/octet-stream'                                                  => 'pdf',
            'application/x-x509-user-cert'                                              => 'pem',
            'application/x-pem-file'                                                    => 'pem',
            'application/pgp'                                                           => 'pgp',
            'application/x-httpd-php'                                                   => 'php',
            'application/php'                                                           => 'php',
            'application/x-php'                                                         => 'php',
            'text/php'                                                                  => 'php',
            'text/x-php'                                                                => 'php',
            'application/x-httpd-php-source'                                            => 'php',
            'image/png'                                                                 => 'png',
            'image/x-png'                                                               => 'png',
            'application/powerpoint'                                                    => 'ppt',
            'application/vnd.ms-powerpoint'                                             => 'ppt',
            'application/vnd.ms-office'                                                 => 'ppt',
            'application/msword'                                                        => 'doc',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'application/x-photoshop'                                                   => 'psd',
            'image/vnd.adobe.photoshop'                                                 => 'psd',
            'audio/x-realaudio'                                                         => 'ra',
            'audio/x-pn-realaudio'                                                      => 'ram',
            'application/x-rar'                                                         => 'rar',
            'application/rar'                                                           => 'rar',
            'application/x-rar-compressed'                                              => 'rar',
            'audio/x-pn-realaudio-plugin'                                               => 'rpm',
            'application/x-pkcs7'                                                       => 'rsa',
            'text/rtf'                                                                  => 'rtf',
            'text/richtext'                                                             => 'rtx',
            'video/vnd.rn-realvideo'                                                    => 'rv',
            'application/x-stuffit'                                                     => 'sit',
            'application/smil'                                                          => 'smil',
            'text/srt'                                                                  => 'srt',
            'image/svg+xml'                                                             => 'svg',
            'application/x-shockwave-flash'                                             => 'swf',
            'application/x-tar'                                                         => 'tar',
            'application/x-gzip-compressed'                                             => 'tgz',
            'image/tiff'                                                                => 'tiff',
            'text/plain'                                                                => 'txt',
            'text/x-vcard'                                                              => 'vcf',
            'application/videolan'                                                      => 'vlc',
            'text/vtt'                                                                  => 'vtt',
            'audio/x-wav'                                                               => 'wav',
            'audio/wave'                                                                => 'wav',
            'audio/wav'                                                                 => 'wav',
            'application/wbxml'                                                         => 'wbxml',
            'video/webm'                                                                => 'webm',
            'audio/x-ms-wma'                                                            => 'wma',
            'application/wmlc'                                                          => 'wmlc',
            'video/x-ms-wmv'                                                            => 'wmv',
            'video/x-ms-asf'                                                            => 'wmv',
            'application/xhtml+xml'                                                     => 'xhtml',
            'application/excel'                                                         => 'xl',
            'application/msexcel'                                                       => 'xls',
            'application/x-msexcel'                                                     => 'xls',
            'application/x-ms-excel'                                                    => 'xls',
            'application/x-excel'                                                       => 'xls',
            'application/x-dos_ms_excel'                                                => 'xls',
            'application/xls'                                                           => 'xls',
            'application/x-xls'                                                         => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
            'application/vnd.ms-excel'                                                  => 'xlsx',
            'application/xml'                                                           => 'xml',
            'text/xml'                                                                  => 'xml',
            'text/xsl'                                                                  => 'xsl',
            'application/xspf+xml'                                                      => 'xspf',
            'application/x-compress'                                                    => 'z',
            'application/x-zip'                                                         => 'zip',
            'application/zip'                                                           => 'zip',
            'application/x-zip-compressed'                                              => 'zip',
            'application/s-compressed'                                                  => 'zip',
            'multipart/x-zip'                                                           => 'zip',
            'text/x-scriptzsh'                                                          => 'zsh',
        ];

        return isset($mime_map[$mime]) === true ? $mime_map[$mime] : false;
    }


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
					echo 'Error 404: Server could parse the ?url= that you were looking for, error it got: '.$curl_result[1];
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
<p>Hash-based content store. Currently stores PDFs and makes them addressable using the
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


</body>
</html>


<?php
		$handled = true;
	}	
}

main ();

?>
