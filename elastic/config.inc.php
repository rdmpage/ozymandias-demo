<?php

// $Id: //

/**
 * @file config.php
 *
 * Global configuration variables (may be added to by other modules).
 *
 */

global $config;

// Date timezone
date_default_timezone_set('UTC');

// Database-------------------------------------------------------------------------------


// Proxy settings for connecting to the web-----------------------------------------------
// Set these if you access the web through a proxy server. 
$config['proxy_name'] 	= '';
$config['proxy_port'] 	= '';

//$config['proxy_name'] 	= 'wwwcache.gla.ac.uk';
//$config['proxy_port'] 	= '8080';


// Elastic--------------------------------------------------------------------------------

if (1)
{
	// Bitnami https://google.bitnami.com/vms/bitnami-elasticsearch-dm-c610
	$config['elastic_options'] = array(
			'index' => 'elasticsearch/ala',
			'protocol' => 'http',
			'host' => '35.204.73.93',
			'port' => 80,
			'user' => 'user',
			'password' => '7WbQZedlAvzQ'
			);
}

if (0)
{		
	// Local Docker Elasticsearch version 5.6.4, e.g. http://localhost:32773
	$config['elastic_options'] = array(
			'index' => 'ala',
			'protocol' => 'http',
			'host' => '127.0.0.1',
			'port' => 32773
			);
}

if (0)
{		
	// Windows 10 Docker Elasticsearch version 5.6.10
	$config['elastic_options'] = array(
			'index' => 'ala',
			'protocol' => 'http',
			'host' => '130.209.46.63',
			'port' => 80
			);
}
	
?>