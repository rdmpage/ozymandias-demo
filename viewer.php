<?php

error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/config.inc.php');
require_once(dirname(__FILE__) . '/sparql.php');

$uri = 'https://biodiversity.org.au/afd/publication/6e8b2032-b952-4f7e-88e2-ca8fefd6380c';

if (isset($_GET['uri']))
{
	$uri = $_GET['uri'];
}

$query = 'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
SELECT *
WHERE
{
<' . $uri . '> <http://schema.org/sameAs> ?uri_string .
BIND(IRI(?uri_string) AS ?uri) . 
?uri rdf:type <http://schema.org/CreativeWork> .
?uri <http://schema.org/hasPart> ?hasPart .
?hasPart <http://schema.org/name> ?name .
?hasPart <http://schema.org/position> ?position .
?hasPart <http://schema.org/contentUrl> ?contentUrl .
?hasPart <http://schema.org/thumbnailUrl> ?thumbnailUrl .
} ORDER BY (xsd:integer(?position))';

$json = sparql_query($config['sparql_endpoint'], $query);

if ($json != '')
{
	$data = json_decode($json);
	
	echo '<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<!-- base -->
		<base href="' . $config['web_root'] . '" /><!--[if IE]></base><![endif]-->
		<script src="js/jquery.js"></script>
		<!-- lazy loader -->
		<script type="text/javascript" src="external/jquery.lazy-master/jquery.lazy.min.js"></script>

		<style>
		body {
			padding:0px;
			margin:0px;
		}
		.image-viewer {
			background-color:rgb(229,229,229); /* Google Books */
			height:800px;
			overflow-y:auto;
			text-align:center;
	
			border-top: 1px solid rgb(192,192,192);
		}

		.image-viewer img {
			background-color:white;
			width:90%;
		}
		</style>
		
		<script src="//hypothes.is/embed.js" async></script>
		
</head>
<body>';
	
	echo '<div class="image-viewer">';
	
	foreach ($data->results->bindings as $binding)
	{
		echo '<div>';		
		echo '<img class="lazy" src="' . $binding->contentUrl->value . '" />';
		echo '</div>';
		
		/* Spacer between pages, hack for now */
		echo '<div style="height:1em;"></div>';
	}
	
	echo '</div>';
	echo '</body>
</html>';
	
}
else
{
	echo '<html></html>';
}


?>

