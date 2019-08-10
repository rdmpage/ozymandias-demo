<?php

// Harvest ResearchGate profile image and metadata

error_reporting(E_ALL);



//----------------------------------------------------------------------------------------
function get($url)
{
	$data = null;
	
	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE,
	  CURLOPT_HTTPHEADER =>  array(
	  	"User-agent: Mozilla/5.0 (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Mobile/7B405" )
	);
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);
	
	return $data;
}

//----------------------------------------------------------------------------------------




$id = 'Julia_Caceres-Chamizo';
$id = 'Roger_De_Keyzer';
$id = 'Ingi_Agnarsson';
$id = 'Jeff_Webb2'; // no image, seems to have no linked data
$id = 'Michael_Batley'; 
$id = 'Ingi_Agnarsson';

$id = 'Alexandra_Stupnikova';

if (isset($_GET['id']))
{
	$id = $_GET['id'];
}

$image_dir = dirname(__FILE__) . '/images/rg';

$filename = $image_dir . '/' . $id . '.jpg';

if (!file_exists($filename))
{
	$url = 'https://www.researchgate.net/profile/' . $id;
	$html = get($url);
	
	//echo $html;

	$image_url = '';

	if (preg_match('/<meta property="og:image" content="(?<url>.*)"/Uu', $html, $m))
	{
		$image_url = $m['url'];
		
		$img = get($image_url);
		file_put_contents($filename, $img);
	}
}

if (file_exists($filename))
{
	$img = file_get_contents($filename);
	header("Content-type: image/jpeg");
	echo $img;
}
else
{
	$img = file_get_contents($image_dir . '/80x80.png');
	header("Content-type: image/png");
	echo $img;
}

?>
