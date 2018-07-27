<?php

require_once (dirname(__FILE__) . '/config.inc.php');


$elastic = new ElasticSearch($config['elastic_options']);

//--------------------------------------------------------------------------------------------------
class ElasticSearch
{
	//----------------------------------------------------------------------------------------------
     function ElasticSearch($options)
     {
         foreach($options AS $key => $value) {
             $this->$key = $value;
         }
     }

	//----------------------------------------------------------------------------------------------
     function send($method, $action_url = '', $post_data = NULL)
     {
		$ch = curl_init(); 
		
		$url = $this->protocol . '://' . $this->host . ':' . $this->port . '/' . $this->index;
		
		if ($action_url != '')
		{
			$url .= '/' . $action_url;
		}
		
		//echo $url . "\n";
		
		curl_setopt ($ch, CURLOPT_URL, $url); 
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		if (isset($this->user))
		{
			curl_setopt($ch, CURLOPT_USERPWD, $this->user . ":" . $this->password); 
		}

		// Set HTTP headers
		$headers = array();
		$headers[] = 'Content-type: application/json'; // we are sending JSON
		
		// Override Expect: 100-continue header (may cause problems with HTTP proxies
		// http://the-stickman.com/web-development/php-and-curl-disabling-100-continue-header/
		$headers[] = 'Expect:'; 
    	curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
		
		if (isset($this->proxy))
		{
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
		}
		switch ($method) {
		  case 'POST':
			curl_setopt($ch, CURLOPT_POST, TRUE);
			if (!empty($post_data)) {
			  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			}
			break;
		  case 'PUT':
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			if (!empty($post_data)) {
			  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			}
			break;
		  case 'DELETE':
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			break;
		}
   		$response = curl_exec($ch);
    	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    	
    	//echo $response;
    	
		if (curl_errno ($ch) != 0 )
		{
			echo "CURL error: ", curl_errno ($ch), " ", curl_error($ch);
		}
    	
    	//echo $http_code . "\n";
   		
   		return $response;
     }

 }


?>
