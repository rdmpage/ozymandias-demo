<?php

// Do a big SPARQL query


$queries = array();


$queries['online'] = 'PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
SELECT ?work_date (COUNT(?w) as ?c) (COUNT(?doi) as ?c_doi) (COUNT(?biostor) as ?c_biostor) (COUNT(?jstor) as ?c_jstor) (COUNT(?pdf) as ?c_pdf) 
WHERE
{
  ?w <http://schema.org/datePublished> ?work_date .
  
  # just articles
  ?w <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://schema.org/ScholarlyArticle> .
  
  # DOI?
  OPTIONAL {
  ?w <http://schema.org/identifier> ?doi .
  ?doi <http://schema.org/propertyID> "doi" .
  }
  
  # BioStor?
  OPTIONAL {
  ?w <http://schema.org/identifier> ?biostor .
  ?biostor <http://schema.org/propertyID> "biostor" .
  }  
  
  # JSTOR?
  OPTIONAL {
  ?w <http://schema.org/identifier> ?jstor .
  ?jstor <http://schema.org/propertyID> "jstor" .
  }  
    
  
  # PDF?
  OPTIONAL {
  ?w <http://schema.org/encoding> ?pdf .
  ?pdf <http://schema.org/fileFormat> "application/pdf" .
  }    
  

  FILTER regex(?work_date, "^[0-9]{4}$")

  #FILTER (xsd:integer(?work_date) > 1980)
} 
GROUP BY ?work_date
ORDER BY ?work_date';

$queries['citation'] = 'PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
SELECT ?cited_identifier_type (xsd:integer(?w_year) as ?from) (xsd:integer(?work_year) as ?to) 
WHERE
{
  
?w <http://schema.org/identifier> ?identifier .
  ?w <http://schema.org/name> ?w_name .
?w <http://schema.org/datePublished> ?w_year .
# Identifier (e.g., DOI) for work we are displaying 
?identifier <http://schema.org/value> ?identifier_value .  
  
?citing_identifier <http://schema.org/value> ?identifier_value .
?citing <http://schema.org/identifier> ?citing_identifier .

# What does this work cite (typically from CrossRef data)
?citing <http://schema.org/citation> ?cited .

# Translate the citing work\'s DOI (or other identifier) into AFD identifier
# Get identifier (typically a DOI) for citing work
?cited <http://schema.org/identifier> ?cited_identifier .
?cited_identifier <http://schema.org/value> ?cited_identifier_value .
?cited_identifier <http://schema.org/propertyID> ?cited_identifier_type .


# Get work(s) with this identifer (may have > 1 if we have CrossRef record in our triple store
?work_identifier <http://schema.org/value> ?cited_identifier_value .
?work <http://schema.org/identifier> ?work_identifier .
?work <http://schema.org/name> ?name .
?work <http://schema.org/datePublished> ?work_year .

# Just include citing records that are also in ALA
FILTER regex(str(?work),\'biodiversity.org.au\') .
FILTER regex(str(?w),\'biodiversity.org.au\') .
  
FILTER regex(?w_year, "^[0-9]{4}$")
FILTER regex(?work_year, "^[0-9]{4}$")
}
';

$queries['cumulative_weevils'] = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
SELECT ?year (COUNT(?taxonName) AS ?count) 
WHERE 
{   
VALUES ?root_name {"CURCULIONOIDEA"}
?root <http://schema.org/name> ?root_name .
?child rdfs:subClassOf+ ?root .
?child rdfs:subClassOf ?parent .
?child <http://schema.org/name> ?child_name .
?parent <http://schema.org/name> ?parent_name .
  
  ?child  <http://taxref.mnhn.fr/lod/property/hasReferenceName> ?taxonName .
  
  ?taxonName <http://rs.tdwg.org/ontology/voc/TaxonName#rankString> "species" .
  ?taxonName <http://rs.tdwg.org/ontology/voc/TaxonName#year> ?year .
  
}
GROUP BY ?year
ORDER BY ?year
';


$queries['weevil_names'] =
'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
SELECT ?year (COUNT(DISTINCT ?name) AS ?c)
WHERE 
{   
VALUES ?root_name {"CURCULIONOIDEA"}
?root <http://schema.org/name> ?root_name .
?child rdfs:subClassOf+ ?root .
?child rdfs:subClassOf ?parent .
?child <http://schema.org/name> ?child_name .
?parent <http://schema.org/name> ?parent_name .
  
  ?child  <http://taxref.mnhn.fr/lod/property/hasReferenceName>|<http://taxref.mnhn.fr/lod/property/hasSynonym> ?taxonName .
  ?taxonName <http://rs.tdwg.org/ontology/voc/TaxonName#rankString> "species" .
  ?taxonName <http://schema.org/name> ?name .
  ?taxonName <http://rs.tdwg.org/ontology/voc/TaxonName#year> ?year .
  
}

GROUP BY ?year
ORDER BY ?year';

$queries['c1'] = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
SELECT ?year (COUNT(?taxonName) AS ?count) 
WHERE 
{   
VALUES ?root_name {"CAMAENIDAE"}
?root <http://schema.org/name> ?root_name .
?child rdfs:subClassOf+ ?root .
?child rdfs:subClassOf ?parent .
?child <http://schema.org/name> ?child_name .
?parent <http://schema.org/name> ?parent_name .
  
  ?child  <http://taxref.mnhn.fr/lod/property/hasReferenceName> ?taxonName .
  
  ?taxonName <http://rs.tdwg.org/ontology/voc/TaxonName#rankString> "species" .
  ?taxonName <http://rs.tdwg.org/ontology/voc/TaxonName#year> ?year .
  
}
GROUP BY ?year
ORDER BY ?year';

$queries['c2'] =
'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
SELECT ?year (COUNT(DISTINCT ?name) AS ?c)
WHERE 
{   
VALUES ?root_name {"CAMAENIDAE"}
?root <http://schema.org/name> ?root_name .
?child rdfs:subClassOf+ ?root .
?child rdfs:subClassOf ?parent .
?child <http://schema.org/name> ?child_name .
?parent <http://schema.org/name> ?parent_name .
  
  ?child  <http://taxref.mnhn.fr/lod/property/hasReferenceName>|<http://taxref.mnhn.fr/lod/property/hasSynonym> ?taxonName .
  ?taxonName <http://rs.tdwg.org/ontology/voc/TaxonName#rankString> "species" .
  ?taxonName <http://schema.org/name> ?name .
  ?taxonName <http://rs.tdwg.org/ontology/voc/TaxonName#year> ?year .
  
}

GROUP BY ?year
ORDER BY ?year';



$queries['works'] = 
"prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
SELECT  ?year (COUNT(?year) AS ?c_year)
WHERE {
  ?work rdf:type <http://schema.org/ScholarlyArticle> .
  
  ?work  <http://schema.org/datePublished> ?year .
  FILTER regex(str(?work),'biodiversity.org.au') .
  FILTER regex(str(?year),'^[0-9]{4}$') .
}
GROUP BY  ?year
ORDER BY ?year";

$queries['works_identifiers'] = 
"prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
SELECT   ?year ?work (COUNT(?year) AS ?c_year)  (group_concat(?id;separator=';') as ?identifiers)
WHERE {
  ?work rdf:type <http://schema.org/ScholarlyArticle> .
  ?work <http://schema.org/identifier> ?identifier .
  ?identifier <http://schema.org/propertyID> ?id .
  ?identifier <http://schema.org/value> ?value .
  
  ?work  <http://schema.org/datePublished> ?year .
  
  FILTER (?id != 'sici')
  FILTER regex(str(?work),'biodiversity.org.au') .
  FILTER regex(str(?year),'^[0-9]{4}$') .
  
  
}
group by ?identifiers ?work ?year
ORDER BY ?year";

$queries['weevil_genera'] = 

'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
SELECT ?year (COUNT(?taxonName) AS ?count) 
WHERE 
{   
VALUES ?root_name {"CURCULIONOIDEA"}
?root <http://schema.org/name> ?root_name .
?child rdfs:subClassOf+ ?root .
?child rdfs:subClassOf ?parent .
?child <http://schema.org/name> ?child_name .
?parent <http://schema.org/name> ?parent_name .
  
  ?child  <http://taxref.mnhn.fr/lod/property/hasReferenceName> ?taxonName .
  
  ?taxonName <http://rs.tdwg.org/ontology/voc/TaxonName#rankString> "genus" .
  ?taxonName <http://rs.tdwg.org/ontology/voc/TaxonName#year> ?year .
  
}
GROUP BY ?year
ORDER BY ?year';


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

$heading = array();
$first = true;

$page = 100;
$offset = 0;

$done = false;

while (!$done)
{



	$sparql = $queries['online'];
	//$sparql = $queries['citation'];
	$sparql = $queries['cumulative_weevils'];
	$sparql = $queries['weevil_names'];
	
	$sparql = $queries['c1'];
	$sparql = $queries['c2'];
	
	$sparql = $queries['works'];
	$sparql = $queries['works_identifiers'];
	$sparql = $queries['weevil_genera'];
	
		
	$sparql .= "\nLIMIT $page";
	$sparql .= "\nOFFSET $offset";

	//echo $sparql . "\n";


		$url = 'http://130.209.46.63/blazegraph/sparql?query=' . urlencode($sparql);

		//echo $url;

		$json = get($url);
		
		//echo $json;

		$obj = json_decode($json);
		
		//print_r($obj);

		foreach ($obj->results as $results)
		{
			foreach ($results as $binding)
			{
				//print_r($binding);
				
				// dump results 
				
				$row = array();
				
				foreach ($binding as $k => $v)
				{
					if (!isset($heading[$k]))
					{
						$heading[] = $k;
					}
					
					$row[] = $v->value;					
					
				
				}
				
				if ($first)
				{
					echo join("\t", $heading) . "\n";
					$first = false;
				}
				echo join("\t", $row) . "\n";
		
						
			}
		}

	if (count($obj->results->bindings) < $page)
	{
		$done = true;
	}
	else
	{
		$offset += $page;
	}
	
	
}

?>

