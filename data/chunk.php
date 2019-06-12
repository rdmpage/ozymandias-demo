<?php

require_once('../config.inc.php');

$config['blazegraph-url'] 	= 'http://kg-blazegraph.sloppy.zone';	
$config['sparql_endpoint']	= $config['blazegraph-url'] . '/blazegraph/sparql'; 

// Break a big triples file into arbitrary sized chunks for easier uplaoding.

$graph_uri = '';



if (1)
{
	// CrossRef
	$triples_filename = 'oz-crossref.nt';
	$basename = 'crossref';
	$graph_uri = 'https://crossref.org';
}


if (1)
{
	// Zenodo
	$triples_filename = 'oz-zenodo.nt';
	$basename = 'zenodo';
	$graph_uri = 'https://zenodo.org';
}


if (1)
{
	// ORCID
	$triples_filename = 'oz-orcid.nt';
	$basename = 'orcid';
	$graph_uri = 'https://orcid.org';
}

if (1)
{
	// Barcodes
	$triples_filename = 'oz-bold.nt';
	$basename = 'bold';
	$graph_uri = 'http://boldsystems.org';
}

if (1)
{
	// GBIF
	$triples_filename = 'oz-gbif.nt';
	$basename = 'gbif';
	$graph_uri = 'https://gbif.org/species';
}

if (1)
{
	// ALA
	$triples_filename = 'oz-ala.nt';
	$basename = 'ala';
	$graph_uri = 'https://bie.ala.org.au';
}

// Wikispecies?



$count = 0;
$total = 0;
$triples = '';

$chunks= 500000;

$delay = 5;

$handle = null;
$output_filename = '';

$chunk_files = array();

$file_handle = fopen($triples_filename, "r");
while (!feof($file_handle)) 
{
	if ($count == 0)
	{
		$output_filename = $basename . '-' . $total . '.nt';
		$chunk_files[] = $output_filename;
		$handle = fopen($output_filename, 'a');
	}

	$line = fgets($file_handle);
	
	fwrite($handle, $line);
	
	if (!(++$count < $chunks))
	{
		fclose($handle);
		
		$total += $count;
		
		echo $total . "\n";
		$count = 0;
		
	}
}

fclose($handle);


echo "--- curl upload.sh ---\n";
$curl = "#!/bin/sh\n\n";
foreach ($chunk_files as $filename)
{
	$curl .= "echo '$filename'\n";
	
	//$url = 'http://130.209.46.63/blazegraph/sparql';
	
	$url = $config['sparql_endpoint'];
	
	if ($graph_uri != '')
	{
		$url .= '?context-uri=' . $graph_uri;
	}
	
	$curl .= "curl $url -H 'Content-Type: text/rdf+n3' --data-binary '@$filename'  --progress-bar | tee /dev/null\n";
	$curl .= "echo ''\n";
	$curl .= "sleep $delay\n";
}

file_put_contents(dirname(__FILE__) . '/upload.sh', $curl);



	
?>	
