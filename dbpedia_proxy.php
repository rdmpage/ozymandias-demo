<?php

// proxy to ensure PDF is handled as an attachment and is accessible from the same IP
// address as the server

error_reporting(E_ALL);

$query = '';
if (isset($_GET['query']))
{
	$query = $_GET['query'];
}

$url = 'http://dbpedia.org/sparql?default-graph-uri=http://dbpedia.org&query=' . urlencode($query) . '&format=application/json-ld';


$opts = array(
  CURLOPT_URL =>$url,
  CURLOPT_FOLLOWLOCATION => TRUE,
  CURLOPT_RETURNTRANSFER => TRUE,
);

$ch = curl_init();
curl_setopt_array($ch, $opts);
$data = curl_exec($ch);
$info = curl_getinfo($ch); 
curl_close($ch);

header("Content-type: " . $info['content_type']);
echo $data;

?>
