<?php

error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/config.inc.php');
require_once(dirname(__FILE__) . '/sparql.php');

$query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX tc: <http://rs.tdwg.org/ontology/voc/TaxonConcept#>

SELECT ?parent ?parent_name WHERE
{   
VALUES ?root_name {"AGAMIDAE"}
?root tc:nameString ?root_name .
?root rdfs:subClassOf+ ?parent .
?parent tc:nameString ?parent_name .
}';

if (isset($_REQUEST['query']))
{
	$query = $_REQUEST['query'];
}

$callback = '';
if (isset($_GET['callback']))
{
	$callback = $_GET['callback'];
}

if ($callback != '')
{
	echo $callback . '(';
}
echo sparql_query($config['sparql_endpoint'], $query);
if ($callback != '')
{
	echo ')';
}

?>
