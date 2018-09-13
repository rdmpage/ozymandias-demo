<?php



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


// process file

$filename = 'queryResults-4.csv';		
$file_handle = fopen($filename, "r");

$row_count = 0;

while (!feof($file_handle)) 
{
	$row = fgetcsv(
		$file_handle, 
		0, 
		',',
		'"');

	$go = is_array($row);
	
	if ($go && ($row_count == 0))
	{
		$go = false;
	}
	if ($go)
	{
		print_r($row);
	
		// query 
		$issn = trim($row[1]);
		
		//echo "|" . $row[1] . "|\n";
		
		$sparql = "SELECT *
WHERE
{
  ?item wdt:P236 \"$issn\" .
  
  ?item wdt:P31 wd:Q5633421 .
  
   OPTIONAL {
	 ?item wdt:P495 ?country_of_origin .
	 ?country_of_origin rdfs:label ?country_of_origin_name .
	 FILTER (lang(?country_of_origin_name) = 'en')
	}
  
   OPTIONAL {
     ?item wdt:P123 ?publisher .
     ?publisher rdfs:label ?publisher_name .
	
     OPTIONAL {
	   ?publisher wdt:P625 ?coordinates .
	  }

     OPTIONAL {
	   ?publisher wdt:P17 ?country .
	   ?country rdfs:label ?country_name .
	 }    
 
	FILTER (lang(?publisher_name) = 'en')
	FILTER (lang(?country_name) = 'en')
  }
}";


		$url = 'https://query.wikidata.org/bigdata/namespace/wdq/sparql?query=' . urlencode($sparql);

		//echo $url;

		$json = get($url);

		//echo $json;

		$obj = json_decode($json);

		$extra = array('', '');

		foreach ($obj->results as $results)
		{
			foreach ($results as $binding)
			{
				//print_r($binding);
		
				if (isset($binding->publisher_name))
				{
					//echo $binding->publisher_name->value;
					$extra[0] = $binding->publisher_name->value;
				}
		
				if (isset($binding->country_name))
				{
					//echo $binding->country_name->value;
					$extra[1] = $binding->country_name->value;
				}
				
				if (isset($binding->country_of_origin))
				{
					//echo $binding->country_name->value;
					$extra[1] = $binding->country_of_origin_name->value;
				}		
						
			}
		}

		print_r($extra);
	}
	
	$row_count++;
}

?>

