<?php

error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/config.inc.php');
require_once(dirname(__FILE__) . '/sparql.php');

$uri = 'https://doi.org/10.1080/09397140.2017.1320840';

if (isset($_GET['uri']))
{
	$uri = $_GET['uri'];
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
echo sparql_construct($config['sparql_endpoint'], $uri);

if ($callback != '')
{
	echo ')';
}

?>
