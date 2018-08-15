<?php

// proxy to ensure PDF is handled as an attachment and is accessible from the same IP
// address as the server

error_reporting(E_ALL);

$url = '';
if (isset($_GET['url']))
{
	$url = $_GET['url'];
}

//$url = 'https://gallica.bnf.fr/ark:/12148/bpt6k54425183/f246.highres';

if ($url != '')
{
	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE,
//	  CURLOPT_COOKIEJAR => 'cookie.txt'
CURLOPT_USERAGENT => 'Mozilla/5.0 (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Mobile/7B405'
	);
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);

  	header("Content-type: " . $info['content_type']);
  	echo $data;
}

?>
