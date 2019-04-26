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

// output
if (0)
{
	header("Content-type: text/plain;charset=utf-8");
}
else
{
	header("Content-type: application/json;charset=utf-8");
}
header("Access-Control-Allow-Origin: *");

if ($callback != '')
{
	echo $callback . '(';
}

echo sparql_describe($config['sparql_endpoint'], $uri);
if ($callback != '')
{
	echo ')';
}

?>

