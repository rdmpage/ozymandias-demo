<?php

// Journal rankings over time

//----------------------------------------------------------------------------------------
// get
function get($url, $format = "application/json")
{
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: " . $format));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

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


$data = array();

$start = 1820;
$end = 2020;

// decades
for ($year = $start; $year < $end; $year += 10)
{

		
		$sparql = "PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX tc: <http://rs.tdwg.org/ontology/voc/TaxonConcept#>
SELECT   ?journal ?issn  (COUNT(?journal) AS ?count) WHERE
{   
 ?work  <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://schema.org/ScholarlyArticle> .
 ?work <http://schema.org/isPartOf> ?container .
  ?container <http://schema.org/name> ?journal .
 ?work <http://schema.org/datePublished> ?year .
  
  OPTIONAL {
  ?container <http://schema.org/issn> ?issn . 
  }
  FILTER ((xsd:integer(?year) >= " . $year . ") && (xsd:integer(?year) < " . ($year + 9) . "))
} 
GROUP BY ?journal ?issn 
ORDER BY DESC(?count)
LIMIT 10";


		$url = 'http://130.209.46.63/blazegraph/sparql?query=' . urlencode($sparql);

		//echo $url;

		$json = get($url);

		//echo $json;

		$obj = json_decode($json);

		$extra = array('', '');
		
		$count = 1;

		foreach ($obj->results as $results)
		{
			foreach ($results as $binding)
			{
				print_r($binding);
				
				$journal = '';
		
				if (isset($binding->journal))
				{
					$journal = $binding->journal->value;
					if (!isset($data[$journal]))
					{
						$data[$journal] = array();
					}
					
					// rank	
					$data[$journal][$year] = $count++;	
				}
				
				if (isset($binding->count))
				{
					$data[$journal][$year] = $binding->count->value;		
				}

						
			}
		}

	
	
	print_r($data);
	

}

$row = array();
$row[]  = 'Decade';
foreach ($data as $journal => $years)
{
	$row[] = $journal;
}
echo join("\t", $row) . "\n";

for ($year = $start; $year < $end; $year += 10)
{
	echo $year;
	
	foreach ($data as $journal => $years)
	{
		$value = 0;
		if (isset($years[$year]))
		{
			$value = $years[$year];
		}
		echo "\t$value";
	}
	
	echo "\n";



}

?>

