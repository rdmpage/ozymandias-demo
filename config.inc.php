<?php

/**
 * @file config.inc.php
 *
 * Global configuration variables (may be added to by other modules).
 *
 */

global $config;

// Date timezone
date_default_timezone_set('UTC');

$site = 'local';
//$site = 'heroku';

switch ($site)
{
	case 'heroku':
		// Server-------------------------------------------------------------------------
		$config['web_server']	= 'https://ozymandias-demo.herokuapp.com'; 
		$config['site_name']	= 'Ozymandias - a biodiversity knowledge graph';

		// Files--------------------------------------------------------------------------
		$config['web_dir']		= dirname(__FILE__);
		$config['web_root']		= '/';		
		break;

	case 'local':
	default:
		// Server-------------------------------------------------------------------------
		$config['web_server']	= 'http://localhost'; 
		$config['site_name']	= 'Ozymandias - a biodiversity knowledge graph';

		// Files--------------------------------------------------------------------------
		$config['web_dir']		= dirname(__FILE__);
		$config['web_root']		= '/~rpage/ozymandias-demo/';
}


$config['thumbnail_cdn'] = 'http://exeg5le.cloudimg.io/height/100/n/';


$config['sparql_endpoint'] 	= '';
$config['triplestore'] 		= 'blazegraph';

if ($config['triplestore'] == 'blazegraph')
{
	$config['blazegraph-url'] 	= 'http://130.209.46.63';	
	$config['sparql_endpoint']	= $config['blazegraph-url'] . '/blazegraph/sparql'; 
}

?>
