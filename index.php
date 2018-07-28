<?php

error_reporting(E_ALL);

require_once (dirname(__FILE__) . '/config.inc.php');
require_once (dirname(__FILE__) . '/elastic/elastic.php');
require_once (dirname(__FILE__) . '/sparql.php');


//----------------------------------------------------------------------------------------
function get_entity_types($entity)
{
	$types = array();
	
	if (is_array($entity->{'@type'}))
	{
		$types = $entity->{'@type'};
	}
	else
	{
		$types[] = $entity->{'@type'};
	}
	
	$n = count($types);
	for ($i = 0; $i < $n; $i++)
	{
		$types[$i] = str_replace('http://schema.org/', '', $types[$i]);
	}
	return $types;
}

//----------------------------------------------------------------------------------------
// Get a literal value as a string, may be a string, an array, or a language map
function get_literal_display($value)
{
	$literal = '';
	
	if ($literal == '')
	{
		if (is_string($value))
		{
			$literal = $value;
		}
	}
	
	if ($literal == '')
	{
		if (is_array($value))
		{
			$strings = array();
			foreach ($value as $k => $v)
			{
				$strings[] = $v;
			}
			$literal = join('/', $strings);
		}
	}	
	
	return $literal;
}

//----------------------------------------------------------------------------------------
// Container of works
function display_container($entity)
{
	echo '
			<div class="heading-block clearfix">
				<div class="heading-thumbnail">';
				
	if (isset($entity->thumbnailUrl))
	{
		echo '<img src="' . $entity->thumbnailUrl . '" />';
	}	

	echo '
				</div>
				<div class="heading-body">
					<div class="heading-title">';
					
	echo  get_literal_display($entity->name);
	echo '
					</div>';
					
	echo '
	            <div class="heading-description">';

	$issn = array();
	if (isset($entity->issn))
	{
		if (is_array($entity->issn))
		{
			$issn = $entity->issn;
		}
		else
		{
			$issn[] = $entity->issn;
		}
	}
	
	if (count($issn) > 0)
	{
		echo '<span>ISSN: ' . $issn[0] . '</span>';
	
		echo '<script>issn_in_wikidata("' . $issn[0] . '", "wikidata"); </script>';
	}
	
	echo '
	             </div>
	        </div>
	    </div>
	';	            

	
	echo '<div id="works"></div>';
	
	echo '
		<script>container_parts("' . $entity->{'@id'} . '", "works"); </script>
	';	
	
}

//----------------------------------------------------------------------------------------
// Published work
function display_work($entity)
{

	echo '
			<div class="heading-block clearfix">
				<div class="heading-thumbnail">';
				
	if (isset($entity->thumbnailUrl))
	{
		echo '<img src="' . $entity->thumbnailUrl . '" />';
	}	

	echo '
				</div>
				<div class="heading-body">
					<div class="heading-title">';
					
	echo  get_literal_display($entity->name);
	echo '
					</div>';
					
	// Display bibliographic details (SPARQL)		
	echo '					
					<div class="heading-description">
						<span id="container"></span>';
	
	$terms = array();
	$terms[] = ' ';
	if (isset($entity->datePublished))
	{
		$terms[] = $entity->datePublished;
	}
	$terms[] = '; ';
	if (isset($entity->volume))
	{
		$terms[] = $entity->volume;
	}
	if (isset($entity->issueNumber))
	{
		$terms[] = '(';
		$terms[] = $entity->issueNumber;
		$terms[] = ')';
	}
	if (isset($entity->pagination))
	{
		$terms[] = ': ';
		$terms[] = $entity->pagination;
	}
	
	echo '<span id="details">' . join('', $terms) . '</span>';
	echo '</div>';	
											
	// Display authors (SPARQL)		
	echo '
					<div id="creator"></div>';					
	echo '
					<div id="identifiers"></div>';										
	echo '									
				</div>
			</div>';
			
			
	echo '<div id="figures" class="figures"></div>';
			
			
	echo '
			<div id="viewer">				
			</div>';
			
			
	echo '
		<script>container("' . $entity->{'@id'} . '", "container"); </script>	
		<script>creators_for_entity("' . $entity->{'@id'} . '", "creator"); </script>
		<script>identifiers_for_entity("' . $entity->{'@id'} . '", "identifiers"); </script>
		<script>figures("' . $entity->{'@id'} . '", "figures"); </script>		
		<script>pdf_viewer("' . $entity->{'@id'} . '", "viewer"); </script>
	';
}

//----------------------------------------------------------------------------------------
// Creator (usually a person)
function display_creator($entity)
{

	echo '
			<div class="heading-block clearfix">
				<div class="heading-thumbnail">';
				
	if (isset($entity->thumbnailUrl))
	{
		echo '<img src="' . $entity->thumbnailUrl . '" />';
	}	

	echo '
				</div>
				<div class="heading-body">
					<div class="heading-title">';
					
	echo  get_literal_display($entity->name);
	echo '
					</div>							
				</div>
			</div>';
			
	echo '<div class="explain">List of works by this author that are in the knowledge graph.</div>';
	echo '<div id="works"></div>';					


	echo '
		<script>works_by_creator("' . $entity->{'@id'} . '", "works");</script>
	';
}

//----------------------------------------------------------------------------------------
// Google Scholar tags for work
function meta_work($entity)
{
	global $config;
	
	$meta = '';
	
	$query = 'SELECT ?citation_title ?citation_date ?citation_volume ?citation_firstpage ?citation_lastpage ?citation_abstract_html_url ?citation_doi ?citation_pdf_url
WHERE {
  
  # title
   <URI> <http://schema.org/name> ?citation_title .

    # date
 OPTIONAL {
  <URI> <http://schema.org/datePublished> ?citation_date .
    } 
  
# authors  

  # journal
  
  OPTIONAL {
  <URI> <http://schema.org/volume> ?citation_volume .
    }           

 OPTIONAL {
  <URI> <http://schema.org/pageStart> ?citation_firstpage .
    }           
           
 OPTIONAL {
  <URI> <http://schema.org/pageEnd> ?citation_lastpage .
    } 
           
 OPTIONAL {
  <URI> <http://schema.org/url> ?citation_abstract_html_url .
    }            
  
  # DOI
  OPTIONAL {
  <URI> <http://schema.org/identifier> ?identifier .
?identifier <http://schema.org/propertyID> "doi" .
?identifier <http://schema.org/value> ?citation_doi .
    }
  
  # PDF
   OPTIONAL {
  <URI> <http://schema.org/encoding> ?encoding .
?encoding <http://schema.org/fileFormat> "application/pdf" .
?encoding <http://schema.org/contentUrl> ?citation_pdf_url .
    }
 }
';
	
	$query = str_replace('<URI>', '<' . $entity->{'@id'} . '>', $query);	
	$json = sparql_query($config['sparql_endpoint'], $query);
	
	$result = json_decode($json);
	
	$meta_list = array();
	
	if (isset($result->results->bindings))
	{
		if (isset($result->results->bindings[0]))
		{
			foreach ($result->results->bindings[0] as $k => $v)
			{
				$meta_list[] = '<meta name="' . $k . '" value="' . htmlentities($v->value, ENT_COMPAT | ENT_HTML5, 'UTF-8') . '" />';
			}		
		}	
	}
	$meta = join("\n", $meta_list);
	
	return $meta;
}
		
//----------------------------------------------------------------------------------------
function display_entity($uri)
{
	global $config;
		
	$ok = false;	
	
	// Handle hash identifiers
	$uri = str_replace('%23', '#', $uri);
	
	
	// By default we assume we have this entity so we can get basic info using DESCRIBE
	$json = sparql_describe($config['sparql_endpoint'], $uri);
	
	if ($json != '')
	{
		$entity = json_decode($json);
		$ok = isset($entity->{'@id'});
		$ok = isset($entity->name);
	}
	
	// This may be an entity that we refer to but which doesn't exist in the triple store,
	// so we use CONSTRUCT
	if (!$ok)
	{
		$json = sparql_construct($config['sparql_endpoint'], $uri);

		if ($json != '')
		{
			$entity = json_decode($json);
			
			$ok = isset($entity->{'@id'});
			$ok = isset($entity->name);
		}
	}
		
	if (!$ok)
	{
		// bounce
		header('Location: ' . $config['web_root'] . '?error=Record not found' . "\n\n");
		exit(0);
	}
	
	// What type of entity is it?
	$types = get_entity_types($entity);
	
	$title = 'Untitled';
	if (isset($entity->name))
	{
		$title = get_literal_display($entity->name);
	}
	
	$meta = '';
	
	// What meta tags do we display?	
	$displayed = false;	
	$n = count($types);
	$i = 0;
	while (($i < $n) && !$displayed)
	{
		switch ($types[$i])
		{
			case 'ScholarlyArticle':
				$meta = meta_work($entity);
				$displayed = true;	
				break;
/*
			case 'http://schema.org/Book':
			case 'Book':
				display_work($entity);
				$displayed = true;	
				break;
				
			case 'http://schema.org/Chapter':
			case 'Chapter':
				display_work($entity);
				$displayed = true;	
				break;
				
			case 'http://schema.org/ImageObject':
			case 'ImageObject':
				display_image($entity);
				$displayed = true;	
				break;

			case 'http://schema.org/Person':
			case 'Person':
				display_creator($entity);
				$displayed = true;	
				break;

			case 'http://schema.org/Periodical':
			case 'Periodical':
				display_container($entity);
				$displayed = true;	
				break;	
				
			case 'http://rs.tdwg.org/ontology/voc/TaxonConcept#TaxonConcept':	
				display_taxon($entity);
				$displayed = true;									
				break;
*/				
			default:
				$meta = '';
				$displayed = true;	
				break;
		
		}
	
	}		
		
	$script = '<script type="application/ld+json">' . "\n"
		. json_encode($entity, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    	. "\n" . '</script>';
	
	display_html_start($title, $meta, $script, '$(window).resize();');
	
	echo '
	<div class="header">
		<b>' . $config['site_name'] . '</b>
	</div>
	
	<div class="content">	
		<div  class="main">
			<div class="main_header">';
			
	display_search_bar('');
	
	echo '
			</div>
			
			<div id="main" class="main_content">';
			

			
	// How do we display it?
	
	$displayed = false;	
	$n = count($types);
	$i = 0;
	while (($i < $n) && !$displayed)
	{
		switch ($types[$i])
		{
			case 'ScholarlyArticle':
				display_work($entity);
				$displayed = true;	
				break;

			case 'Book':
				display_work($entity);
				$displayed = true;	
				break;
				
			case 'Chapter':
				display_work($entity);
				$displayed = true;	
				break;
				
			case 'ImageObject':
				display_image($entity);
				$displayed = true;	
				break;

			case 'Person':
				display_creator($entity);
				$displayed = true;	
				break;

			case 'Periodical':
				display_container($entity);
				$displayed = true;	
				break;	
				
			case 'http://rs.tdwg.org/ontology/voc/TaxonConcept#TaxonConcept':	
				display_taxon($entity);
				$displayed = true;									
				break;
				
			default:
				echo 'Unknown type' . $types[$i];
				exit();
				break;		
		}	
	}
	
	echo '	</div>
			
		</div>
				
		<div class="side">
			<div class="explain">Connections within this knowledge graph.</div>
		</div>

		<div class="side">
			<div class="explain">External knowledge graphs.</div>
			<div id="wikidata"></div>
			<div id="orcid"></div>
		</div>
	</div>';
	
	
	display_html_end();	
}

//----------------------------------------------------------------------------------------
function display_html_start($title = '', $meta = '', $script = '', $onload = '')
{
	global $config;
	
	echo '<!DOCTYPE html>
<html lang="en">
<head>';

	echo '<meta charset="utf-8">'
    
    . $meta . 
    
    '<!-- base -->
    <base href="' . $config['web_root'] . '" /><!--[if IE]></base><![endif]-->

    <script src="js/jquery.js"></script>
    
	<link href="external/fontawesome/css/all.css" rel="stylesheet">     
    
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script> -->
       
    <!-- SPARQL queries -->
    <script src="js/creators.js"></script>
    <script src="js/blr_figures.js"></script>
    <script src="js/works_by_creator.js"></script>
    <script src="js/container.js"></script>
    <script src="js/citation.js"></script>
    <script src="js/identifiers.js"></script>
    <script src="js/taxon.js"></script>
    <script src="js/work.js"></script>

	<!-- hacks to find external identifiers -->
    <script src="js/identifier-queries.js"></script>
    
    <script>
	function pdf_viewer (uri, element_id) {
		var query = `SELECT *
WHERE
{
  <` + uri + `> <http://schema.org/encoding> ?encoding .
  ?encoding <http://schema.org/fileFormat> ?"application/pdf" .
  ?encoding <http://schema.org/contentUrl> ?contentUrl .
  ?encoding <http://id.loc.gov/vocabulary/preservation/cryptographicHashFunctions/sha1> ?sha1 .
}`;
	
		$.getJSON(\'query.php?query=\' + encodeURIComponent(query)
				+ \'&callback=?\',
			function(data){
				console.log(JSON.stringify(data, null, 2));  	
				
				if (data.results.bindings.length == 1) {
					var html = \'<iframe id="pdf" width="100%" height="800" src="external/pdfjs/web/viewer.html?file=\' + encodeURIComponent(\'' . $config['web_server'] . $config['web_root'] . '/pdf_proxy.php?url=\' + encodeURIComponent(data.results.bindings[0].contentUrl.value)) + \'" />\';				
					$(\'#\' + element_id).html(html);					
					$(window).resize();
				}
			}
		);				
}    
	</script>
	
	'	
	. $script . '
	<title>' . $title . '</title>
	
	<link href="css/main.css" rel="stylesheet"> 
	
	<style type="text/css">
	</style>	
	</head>';
	
	if ($onload == '')
	{
		echo '<body>';
	}
	else
	{
		echo '<body onload="' . $onload . '">';
	}
}

//----------------------------------------------------------------------------------------
function display_html_end()
{
	global $config;
	
	echo '<script>
	/* http://stackoverflow.com/questions/6762564/setting-div-width-according-to-the-screen-size-of-user */
	$(window).resize(function() { 
		/* Only resize document window if we have a document cloud viewer */
		var windowHeight =$(window).height() -  30;		
		$("#viewer").css({"height":windowHeight });
		$("#pdf").css({"height":windowHeight });
	});	
</script>
';

	echo '</body>';
	echo '</html>';
}

//----------------------------------------------------------------------------------------
function display_search_bar($q)
{
	global $config;
	
	echo '
	<div class="search_form">
	 <input id="search" class="search_input" placeholder="Search" name="q" value="' . $q . '"/>
	 <button id="search_button" class="search_button" onclick="search()">Search</button>
	</div>';
}

//----------------------------------------------------------------------------------------
// Badness happened
function default_display($error_msg = '')
{
	global $config;
	
	display_html_start('OZ');
	
	if ($error_msg != '')
	{
		echo '<div><strong>Error!</strong> ' . $error_msg . '</div>';
	}
	
	echo 'OZ';

	display_html_end();
}

//----------------------------------------------------------------------------------------
function main()
{
	$query = '';
		
	// If no query parameters 
	if (count($_GET) == 0)
	{
		default_display();
		exit(0);
	}
		
	// Error message
	if (isset($_GET['error']))
	{	
		$error_msg = $_GET['error'];
		
		default_display($error_msg);
		exit(0);			
	}
	
	// Show entity
	if (isset($_GET['uri']))
	{	
		$uri = $_GET['uri'];
						
		display_entity($uri);
		exit(0);
	}
		
	/*
	// Show search (text, author)
	if (isset($_GET['q']))
	{	
		$query = $_GET['q'];
		display_search($query);
		exit(0);
	}
	*/	
	
}


main();

?>