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
// thumbnailUrl should be a string in schema.org but in some datasets it is treated as
// a URI, so use this function to get the string value so we can use it to get the image.
function get_thumbnail_url($value)
{
	$thumbnailUrl = '';
	
	if (is_object($value)) {
		$thumbnailUrl = $value->{'@id'};		
	}
	else
	{
		$thumbnailUrl = $value;	
	}
	
	// hack to handle CDN issues
	$thumbnailUrl = preg_replace('/https:\/\/cdn.rawgit.com\//', 'https://raw.githubusercontent.com/', $thumbnailUrl);
	
	return $thumbnailUrl;
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
		echo '<img src="' . get_thumbnail_url($entity->thumbnailUrl) . '" />';
	}	
	else
	{
		echo '<img src="images/no-icon.svg" />';
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
		echo '<img src="' . get_thumbnail_url($entity->thumbnailUrl) . '" />';
	}
	else
	{
		echo '<img src="images/no-icon.svg" />';
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
					
	if (isset($entity->url))
	{
		echo '<ul class="identifier-list"><li><a class="external" href="' . $entity->url . '" target="_new">' . $entity->url . '</a></li></ul>';					
	}
								
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
		<script>cited_by("' . $entity->{'@id'} . '", "cited_by"); </script>
		<script>cites("' . $entity->{'@id'} . '", "cites"); </script>
		<script>taxa_in_work("' . $entity->{'@id'} . '", "taxa"); </script> 		
		<script>pdf_viewer("' . $entity->{'@id'} . '", "viewer"); </script>
		<!-- <script>biostor_viewer("' . $entity->{'@id'} . '", "viewer"); </script> -->
	';
}

//----------------------------------------------------------------------------------------
// Website 
function display_website($entity)
{
	echo '
			<div class="heading-block clearfix">
				<div class="heading-thumbnail">';
				
	if (isset($entity->thumbnailUrl))
	{
		echo '<img src="' . get_thumbnail_url($entity->thumbnailUrl) . '" />';
	}
	else
	{
		echo '<img src="images/no-icon.svg" />';
	}

	echo '
				</div>
				<div class="heading-body">
					<div class="heading-title">';
					
	echo  get_literal_display($entity->name);
	echo '
					</div>';
					
	echo '					
					<div class="heading-description">
                    </div>';	
											
	// Display authors (SPARQL)		
	echo '
					<div id="creator"></div>';	
					
	if (isset($entity->url))
	{
		echo '<ul class="identifier-list"><li><a class="external" href="' . $entity->url . '" target="_new">' . $entity->url . '</a></li></ul>';					
	}
								
	echo '
					<div id="identifiers"></div>';										
	echo '									
				</div>
			</div>';
			
	echo '
		<script>creators_for_entity("' . $entity->{'@id'} . '", "creator"); </script>
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
		echo '<img src="' . get_thumbnail_url($entity->thumbnailUrl) . '" />';
	}	
	else
	{
		echo '<img src="images/no-icon.svg" />';
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
		<script>creator_cocreators("' . $entity->{'@id'} . '", "creator_cocreators");</script>
		<script>creator_containers("' . $entity->{'@id'} . '", "creator_containers");</script>
		<script>creator_taxa("' . $entity->{'@id'} . '", "creator_taxa");</script>
		<script>match_orcid("' . $entity->{'@id'} . '", "match_orcid");</script>
		<script>match_wikispecies("' . $entity->{'@id'} . '", "match_wikispecies");</script>
	';
}

//----------------------------------------------------------------------------------------
// Image (such as a Zenodo figure)
function display_image($entity)
{
	global $config;
	
	echo '
			<div class="heading-block clearfix">
				<div class="heading-thumbnail">';
				
	
	if (isset($entity->thumbnailUrl))
	{
		echo '<img src="' . $config['thumbnail_cdn'] . get_thumbnail_url($entity->thumbnailUrl) . '" />';		
	}	
	else
	{
		echo '<img src="images/no-icon.svg" />';
	}
		
	echo '
				</div>
				<div class="heading-body">
					<div class="heading-title">';
					
	echo  get_literal_display($entity->name);
	echo '
					</div>
					<div class="heading-description">';
					
	if (isset($entity->description))
	{
		echo '<div class="caption">' . get_literal_display($entity->description) . '</div>';
	}			
	
	echo '<div id="identifiers">';
	echo '<ul class="identifier-list">';
	echo '<li><a class="external" href="' . $entity->{'@id'} . '" target="_new">' . $entity->{'@id'} . '</a></li>';
	echo '</ul>';
	echo '</div>';			
											
	echo '
					</div>
				</div>
			</div>';
			
	// Display image
	echo '<div>';	
	echo '<img class="image" src="' .$entity->contentUrl->{'@id'} . '" />';	
	echo '</div>';



	echo '
		<script>figure_is_part_of("' . $entity->{'@id'} . '", "cited_by");</script> 
	';
}

//----------------------------------------------------------------------------------------
// Taxon
function display_taxon($entity)
{
	global $config;
	
	echo '
			<div class="heading-block clearfix">
				<div class="heading-thumbnail">					
					<img id="taxon-thumbnail" src="images/no-icon.svg" />';		
	echo '
				</div>
				<div class="heading-body">
					<div class="heading-title">';
					
	echo  get_literal_display($entity->name);
	echo '
					</div>
					<div class="heading-description">';
	
	echo '<div id="identifiers">';
	echo '<ul class="identifier-list">';
	echo '<li><a class="external" href="' . $entity->{'@id'} . '" target="_new">' . $entity->{'@id'} . '</a></li>';
	echo '</ul>';
	echo '</div>';			
											
	echo '
						<div id="lineage" class="lineage"></div>
					</div>
				</div>
			</div>';
			
	echo '<div id="children"></div>';

	echo '
		<!-- <script>figure_is_part_of("' . $entity->{'@id'} . '", "cited_by");</script> -->
		<script>taxon_children("' . $entity->{'@id'} . '", "children"); </script> 
		<script>taxon_lineage("' . $entity->{'@id'} . '", "lineage"); </script>
		<script>taxon_thumbnail("' . $entity->{'@id'} . '", "taxon-thumbnail"); </script>
		<script>works_for_taxon("' . $entity->{'@id'} . '", "taxon-works"); </script>
		
		<script>taxon_figures("' . $entity->{'@id'} . '", "taxon_figures"); </script>
		
		<script>occurrence_images("' . $entity->{'@id'} . '", "occurrence_figures"); </script>
		<script>occurrence_lag("' . $entity->{'@id'} . '", "occurrence_lag"); </script>
				
		<script>name_in_gbif("' . $entity->name . '", "gbif"); </script>
	';
}

//----------------------------------------------------------------------------------------
// Occurrence
function display_occurrence($entity)
{
	echo '
			<div class="heading-block clearfix">
				<div class="heading-thumbnail">';
				
	if (isset($entity->thumbnailUrl))
	{
		echo '<img src="' . get_thumbnail_url($entity->thumbnailUrl) . '" />';
	}	
	else
	{
		echo '<img src="images/no-icon.svg" />';
	}

	echo '
				</div>
				<div class="heading-body">
					<div class="heading-title">';
					
					if (isset($entity->name))
					{
						echo  $entity->name;
					}
					else
					{
						echo  $entity->{'@id'};
					}
					
	
	echo '
					</div>';
					
	echo '
	            	<div class="heading-description">
	              </div>
	          </div>
	      </div>
	';	            

	
	echo '<div id="occurrence_evidence"></div>';
	echo '<div id="occurrence_image"></div>';
	
	echo '
		<script>occurrence_event("' . $entity->{'@id'} . '", "occurrence_event"); </script>
		<script>occurrence_location("' . $entity->{'@id'} . '", "occurrence_location"); </script>
		<script>occurrence_evidence("' . $entity->{'@id'} . '", "occurrence_evidence"); </script>
		<script>occurrence_image("' . $entity->{'@id'} . '", "occurrence_image"); </script>
		<script>occurrence_identification("' . $entity->{'@id'} . '", "occurrence_identification"); </script>
		<script>occurrence_sequence("' . $entity->{'@id'} . '", "occurrence_sequence"); </script>
	';	
	
}

//----------------------------------------------------------------------------------------
// Google Scholar tags for work
function meta_work($entity)
{
	global $config;
	
	$meta = '';
	
	$query = 'SELECT ?citation_title ?citation_date ?citation_volume ?citation_firstpage 
	?citation_lastpage ?citation_abstract_html_url ?citation_doi ?citation_pdf_url
	?citation_biostor ?citation_handle
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
    
  # rdmp hacks, maybe rethink to store as URLs
  # BioStor
  OPTIONAL {
  <URI> <http://schema.org/identifier> ?biostor_identifier .
?biostor_identifier <http://schema.org/propertyID> "biostor" .
?biostor_identifier <http://schema.org/value> ?citation_biostor .
    } 
    
  # Handle
  OPTIONAL {
  <URI> <http://schema.org/identifier> ?handle_identifier .
?handle_identifier <http://schema.org/propertyID> "handle" .
?handle_identifier <http://schema.org/value> ?citation_handle .
    }      
         
 }
';
	
	$query = str_replace('<URI>', '<' . $entity->{'@id'} . '>', $query);	
	$json = sparql_query($config['sparql_endpoint'], $query);
	
	$result = json_decode($json);
	
	//echo '<pre>' . $result . '</pre>';
	
	$meta_list = array();
	
	if (isset($result->results->bindings))
	{
		if (isset($result->results->bindings[0]))
		{
			foreach ($result->results->bindings[0] as $k => $v)
			{				
				$key = '';
				
				switch ($k) 
				{
					case 'citation_biostor':
						$key = 'citation_abstract_html_url';
						$value = 'https://biostor.org/reference/' . $v->value;
						break;

					case 'citation_handle':
						$key = 'citation_abstract_html_url';
						$value = 'https://hdl.handle.net/' . $v->value;
						break;
										
					default:
						$key = $k;
						$value = $v->value;
						break;
				}
			
				if (!isset($meta_list[$key]))
				{
					$meta_list[$key] = array();
				}
			
				$meta_list[$key][] = $value;
			}		
		}	
	}
	
	
	return $meta_list;
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
			// Not everything has a name so need a better test
			//$ok = isset($entity->name);
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
	
	$meta_list = array();
	
	// What meta tags do we display?	
	$displayed = false;	
	$n = count($types);
	$i = 0;
	while (($i < $n) && !$displayed)
	{
		switch ($types[$i])
		{
			case 'ScholarlyArticle':
				$meta_list = meta_work($entity);
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
				//$meta = '';
				$displayed = true;	
				break;
		
		}
	
	}	
	
	$meta_tags = array();
	foreach ($meta_list as $k => $values)
	{
		foreach ($values as $value)
		{
			$meta_tags[] = 				
				'<meta name="' . $k . '" content="' . htmlentities($value, ENT_COMPAT | ENT_HTML5, 'UTF-8') . '" />';
		}
	}
	
	$meta = join("\n", $meta_tags);
	
	
    // get preferred external identifier for use in trying to link to page
    $identifier = '';
    $namespace = '';
    
    // DOI preferred
    if ($identifier == '')
    {
    	if (isset($meta_list['citation_doi']))
    	{
    		$identifier = $meta_list['citation_doi'][0];
    		$namespace = 'doi';
    	}
    }

    if ($identifier == '')
    {    	
    	if (isset($meta_list['citation_abstract_html_url']))
    	{
    		$n = count($meta_list['citation_abstract_html_url']);
    		$i = 0;
    		while ($i < $n)
    		{
    			if ($identifier == '')
    			{
					if (preg_match('/https:\/\/hdl.handle.net\/(?<id>.*)/', $meta_list['citation_abstract_html_url'][$i], $m))
					{
						$identifier = $m['id'];
						$namespace = 'handle';
					}
				}

    			if ($identifier == '')
    			{
					if (preg_match('/https:\/\/biostor.org\/reference\/(?<id>.*)/', $meta_list['citation_abstract_html_url'][$i], $m))
					{
						$identifier = $m['id'];
						$namespace = 'biostor';
					}
				}
	
				if ($identifier == '')
    			{
					if (preg_match('/https:\/\/www.jstor.org\/stable\/(?<id>.*)/', $meta_list['citation_abstract_html_url'][$i], $m))
					{
						$identifier = $m['id'];
						$namespace = 'jstor';
					}
				}
		    	
		    	$i++;
		    }
		    
			if ($identifier == '')
			{
				$identifier = $meta_list['citation_abstract_html_url'][0];
				$namespace = 'url';
			}		    
		}
    }
	
		
	// JSON-LD for structured data in HTML
	$script = '<script type="application/ld+json">' . "\n"
		. json_encode($entity, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    	. "\n" . '</script>';
    	
    	
    	
    $script .= '<script type="text/javascript">
window.addEventListener("message", receiveMessage, false);

function receiveMessage(event)
{
	console.log("receiveMessage" + JSON.stringify(event.data));
	console.log("receiveMessage" + JSON.stringify(event));
	if (typeof event.data === "number") {
	   //alert(event.data);
	   $("#page_change").html("Page " + event.data);
	   // dummy 

		';
		
	  if (1) // $identifier != "")
	  {
	  	$script .= 'bionames_page_names(
	   		"' . $identifier . '",
	   		"' . $namespace . '",
	   		event.data,
	   		"page_names"	   		
	   		);';
	   }
$script .= '	   	
	}
}  
  </script>';

 	display_html_start($title, $meta, $script, '$(window).resize();');
	
	echo '
	<div class="header">
		<a href=".">' . $config['site_name'] . '</a></b> <a class="menuitem" href="?sparql">SPARQL</a> <a class="menuitem" href="?tree">Tree</a>
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
				
			case 'CreativeWork':
				display_work($entity);
				$displayed = true;	
				break;

			case 'WebSite':
				display_website($entity);
				$displayed = true;	
				break;
								
			// taxon
			case 'http://rs.tdwg.org/ontology/voc/TaxonConcept#TaxonConcept':	
				display_taxon($entity);
				$displayed = true;									
				break;
				
			// occurrence
			case 'http://rs.tdwg.org/dwc/terms/Occurrence':
				display_occurrence($entity);
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
				
		<div class="side localkg">
			<div class="explain">Connections within this knowledge graph.</div>
			
			<!-- works -->
			<div id="cited_by"></div>
			<div id="cites"></div>
			
			<div id="taxon-works"></div>
			<div id="taxa"></div>
			
			<!-- figures -->
			<div id="taxon_figures"></div>
			
			<!-- creator -->
			<div id="creator_cocreators"></div>
			<div id="creator_containers"></div>	
			<div id="creator_taxa"></div>	
			
			<!-- occurrences -->		
			<div id="occurrence_figures"></div>
			<div id="occurrence_lag"></div>
			<div id="occurrence_event"></div>
			<div id="occurrence_location"></div>
			
			<div id="occurrence_identification"></div>
			<div id="occurrence_sequence"></div>
			
		</div>

		<div class="side externalkg">
			<div class="explain">External knowledge graphs.</div>
			<div id="wikidata"></div>
			<div id="orcid"></div>
			<div id="gbif"></div>
			<div id="match_orcid"></div>
			<div id="match_wikispecies"></div>
			<div id="match_wikidata"></div>
			
			<!-- can we track user scrolling within the document viewer so 
			     we can display page-specific info, such as taxonomic names? -->
			<div id="page_change"></div>
			<div id="page_names"></div>
			
			<!-- gbif experiments -->
			<div id="gbif_occurrences"></div>
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

	echo "<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src=\"https://www.googletagmanager.com/gtag/js?id=UA-125506785-1\"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-125506785-1');
</script>
";

	echo '<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">';
 

	echo '<meta charset="utf-8">'
	
   
    . $meta . 
    
    '<!-- base -->
    <base href="' . $config['web_root'] . '" /><!--[if IE]></base><![endif]-->

    <script src="external/jquery.js"></script>
    
	<link href="external/fontawesome/css/all.css" rel="stylesheet">     
    
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script> -->
    	
	<!-- tree display -->
	<script src="js/tree.js"></script>	  

	<!-- fuzzy search -->
	<script src="external/fuzzyset.js"></script>	  
       
    <!-- SPARQL queries -->
    <!-- <script src="js/biostor.js"></script> -->
    <script src="js/counts.js"></script>
    <script src="js/creators.js"></script>
    <script src="js/blr_figures.js"></script>
    <script src="js/works_by_creator.js"></script>
    <script src="js/container.js"></script>
    <script src="js/citation.js"></script>
    <script src="js/identifiers.js"></script>
    <script src="js/match_creator.js"></script>
    <script src="js/occurrence.js"></script>
    <script src="js/taxon.js"></script>
    <script src="js/work.js"></script>
    <script src="js/gbif.js"></script>

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
					var html = \'<iframe id="pdf" width="100%" height="800" src="external/pdfjs/web/viewer.html?file=\' + encodeURIComponent(\'' . $config['web_server'] . $config['web_root'] . 'pdf_proxy.php?url=\' + encodeURIComponent(data.results.bindings[0].contentUrl.value)) + \'" />\';				
					$(\'#\' + element_id).html(html);					
					$(window).resize();
				} else {
					// biostor_viewer(uri, element_id);
					
					var html = \'<iframe id="scan" width="100%" height="800" src="viewer.php?uri=\' + encodeURIComponent(uri) + \'" />\';				
					
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
function display_html_start_d3($title = '')
{
	global $config;
	
	echo '<!DOCTYPE html>
<html lang="en">
<head>';

	echo '<meta charset="utf-8">
	<!-- base -->
    <base href="' . $config['web_root'] . '" /><!--[if IE]></base><![endif]-->

    <script src="external/d3/d3.v3.min.js"></script>
    <script src="external/d3sparql.js"></script>
    <script>
    function exec() {
      var endpoint = d3.select("#endpoint").property("value")
      var sparql = d3.select("#sparql").property("value")
      d3sparql.query(endpoint, sparql, render)
    }
    function render(json) {
      var config = {
        // for d3sparql.tree()
        "root": "root_name",
        "parent": "parent_name",
        "child": "child_name",
        // for d3sparql.roundtree()
        "diameter": 800,
        "angle": 360,
        "depth": 200,
        "radius": 5,
        "selector": "#result"
      }
      d3sparql.roundtree(json, config)
    }

    function exec_offline() {
      d3.json("cache/taxonomy/hypsibiidae.json", render)
    }
    function toggle() {
      d3sparql.toggle()
    }
    </script>
	<title>' . $title . '</title>
	
	<link href="css/main.css" rel="stylesheet"> 
	
	</head>';
	
	echo '<body>';
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
	<div>
		<form class="search_container" action="' . $config['web_root'] . '"> 
	 		<input class="search_input" id="search" placeholder="Search" name="q" value="' . $q . '"/>
	 		<button class="search_button" type="submit"><i class="fa fa-search"></i></button>
		</form>
	</div>';
	
}

//----------------------------------------------------------------------------------------
function display_search($q)
{
	global $config;
	global $elastic;
	
	$rows_per_page = 20;	

	$json = '{
"size":20,
    "query": {
       "multi_match" : {
      "query": "",
      "fields":["search_data.fulltext", "search_data.fulltext_boosted^4"] 
    }
},

"aggs": {
"type" :{
    "terms": { "field" : "search_data.type.keyword" }
  },
  "year" :{
    "terms": { "field" : "search_data.year" }
  },
  "container" :{
    "terms": { "field" : "search_data.container.keyword" }
  },
  "author" :{
    "terms": { "field" : "search_data.creator.keyword" }
  },
  "classification" :{
    "terms": { "field" : "search_data.classification.keyword" }
  }  

}

    
}';

	$obj = json_decode($json);	
	$obj->query->multi_match->query = $q;
	$response = $elastic->send('POST', '_search?pretty', json_encode($obj));
	
	$response_obj = json_decode($response);
	
	$title = $q;
	
	$meta = '';
	$script = '';
	
	display_html_start($title, $meta, $script, '$(window).resize();');
	
	echo '
	<div class="header">
		<a href=".">' . $config['site_name'] . '</a></b> <a class="menuitem" href="?sparql">SPARQL</a> <a class="menuitem" href="?tree">Tree</a>
	</div>
	
	<div class="content">	
		<div  class="main">
			<div class="main_header">';
			
	display_search_bar($q);
	
	echo '
			</div>
			
			<div id="main" class="main_content">';
			
			echo '<h4>Search results</h4>';
			
    foreach ($response_obj->hits->hits as $hit)
 	{
 		$entity = $hit->_source->search_result_data;
 	
 		echo '<div class="list-item">';
 		echo '  <a href="?uri=' . $entity->id .'">';
		echo '    <div class="list-item-thumbnail">';
		
		if (isset($entity->thumbnailUrl))
		{
			echo '<img src="' . $entity->thumbnailUrl . '" />';
		}
		else
		{
			echo '<img src="images/no-icon.svg" />';
		}
		echo '    </div>';
		echo '    <div class="list-item-body">';
		echo '       <div class="list-item-title">';
		echo $entity->name;
		echo '       </div>';
		echo '    </div>';
		echo '  </a>';
		echo '</div>';
 	}

	
	echo '	</div>
			
		</div>
				
		<div class="side">
			<div class="explain"></div>
		</div>

		<div class="side">
			<div class="explain"></div>
		</div>
	</div>';


	display_html_end();	
}


//----------------------------------------------------------------------------------------
function display_sparql()
{
	global $config;

	$title = $config['site_name'] . ' - SPARQL';
	$meta = '';
	$script = '<link href="//cdn.jsdelivr.net/yasgui/2.5.0/yasgui.min.css" rel="stylesheet" type="text/css"/>';
	
	$script .=  '<style>
      /* uncomment this if you\'d like to hide the endpoint selector */
      .yasgui .endpointText {display:none !important;}
      /* */
    </style>';
	
	display_html_start($title, $meta, $script, '$(window).resize();');
	
	echo '
	<div class="header">
		<a href=".">' . $config['site_name'] . '</a></b> <a class="menuitem" href="?sparql">SPARQL</a> <a class="menuitem" href="?tree">Tree</a>
	</div>
	
	<div class="content">	
		<div  class="main">			
			<div id="main" class="main_content">
				<div id="yasgui"></div>
			</div>			
		</div>
	</div>
		
    <script src="//cdn.jsdelivr.net/yasgui/2.5.0/yasgui.min.js"></script>
    <script type="text/javascript">
        //Uncomment below to change the default endpoint
        //Note: If you\'ve already opened the YASGUI page before, you should first clear your
        //local-storage cache before you will see the changes taking effect 
	    var yasgui = YASGUI(document.getElementById("yasgui"), {
         yasqe:{sparql:{endpoint:"' . $config['web_server'] . $config['web_root'] . 'query.php"}}
      });
    </script>';

	display_html_end();	
}

//----------------------------------------------------------------------------------------
function display_tree()
{
	global $config;

	$title = $config['site_name'] . ' - SPARQL';
	
	display_html_start_d3($title);

	
	//display_html_start($title, $meta, $script, '');
	
	echo '
	<div class="header">
		<a href=".">' . $config['site_name'] . '</a></b> <a class="menuitem" href="?sparql">SPARQL</a> <a class="menuitem" href="?tree">Tree</a>
	</div>
	
	<div class="content">
		<div style="flex: 1 0 0;">	
		<div class="explain">Display a taxonomic classification for a taxon, based on <a href="http://biohackathon.org/d3sparql/">d3sparql.js</a>.</div>
		  <form class="form-inline">
			<!-- <label>SPARQL endpoint:</label> -->
			<div class="input-append">
			  <input style="display:none;" id="endpoint" value="' . $config['web_server'] . $config['web_root'] . '/query.php" type="text" size="40">
			  <button style="font-size:18px;" type="button" onclick="exec()">Query</button>
			  <!-- <button type="button" onclick="exec_offline()">Use cache</button> -->
			  <!-- <button type="button" onclick="toggle()"><i id="button" class="icon-chevron-up"></i></button> -->
			</div>
		  </form>
		  <textarea id="sparql" class="sparqlquery" rows=15>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
SELECT ?root_name ?parent_name ?child_name  WHERE
{   
VALUES ?root_name {"Aedes Meigen, 1818"}
?root <http://schema.org/name> ?root_name .
?child rdfs:subClassOf+ ?root .
?child rdfs:subClassOf ?parent .
?child <http://schema.org/name> ?child_name .
?parent <http://schema.org/name> ?parent_name .
}
		  </textarea>
		</div>
		<div id="result" style="flex: 1 0 0;"></div>	
		</div>		
	</div>
	
 </body>
</html>';	
}

//----------------------------------------------------------------------------------------
// Home page, or badness happened
function default_display($error_msg = '')
{
	global $config;
	
	$title = $config['site_name'];
	$meta = '';
	$script = '';
	
	display_html_start($title, $meta, $script, '$(window).resize();');
	
	echo '
	<div class="header">
		<a href=".">' . $config['site_name'] . '</a></b> <a class="menuitem" href="?sparql">SPARQL</a> <a class="menuitem" href="?tree">Tree</a>
	</div>
	
	<div class="content">	
		<div  class="main">';
		
	echo '	<div class="main_header">';
			
	display_search_bar('');
	
	echo '
			</div>
			<div id="main" class="main_content">';

	if ($error_msg != '')
	{
		echo '<div><strong>Error!</strong> ' . $error_msg . '</div>';
		echo '				
			</div>	<!-- .main_content -->		
		</div> <!-- .main -->';
	}
	else
	{
		// main content
		
		echo '<div style="padding:20px;"><p>Ozymandias is a proof-of-concept <a href="https://doi.org/10.3897/rio.2.e8767">biodiversity knowledge graph</a> by Rod Page <a href="https://twitter.com/rdmpage">@rdmpage</a>.</p>
		
		<p>The core of this knowledge graph is a classification of animals from the <a href="https://www.ala.org.au">Atlas of Living Australia</a> (ALA)
		combined with data on taxonomic names and publications from the <a href="https://biodiversity.org.au/afd/home">Australian Faunal Directory</a> (AFD). 
		This has been enhanced by adding lots of digital identifiers (such as DOIs) to the publications and, where possible, full text 
		either as PDFs or as page scans from the <a href="http://biodiversitylibrary.org">Biodiversity Heritage Library</a> (BHL) (provided via <a href="http://biostor.org">BioStor</a>). 
		Identifiers enable us to
		further grow the knowledge graph, for example by adding "cites" and "cited by" links between publications (data from <a href="http://crossref.org">CrossRef</a>), and
		displaying figures from the <a href="https://zenodo.org/communities/biosyslit/">Biodiversity Literature Repository</a> (BLR).</p>
		
		<p>
		<img src="images/kg.png" width="500" />
		</p>
		
		
		
		<h4>Technical details</h4>
		<p><b>TL;DR</b> the knowledge graph is implemented as a triple store where the data has been
		represented using a small number of vocabularies (mostly <a href="http://schema.org">schema.org</a> with some terms borrowed from <a href="https://doi.org/10.3897/tdwgproceedings.1.20232">TAXREF-LD</a>
		and the TDWG LSID vocabularies). All results displayed in the first two panels are the result of SPARQL queries, the content in the rightmost panel 
		comes from calls to external APIs. Search is implemented using Elasticsearch. If you are feeling brave you can <a href="?sparql">query the knowledge graph directly in SPARQL</a>.</p>
		
		
		
		';
		
		echo '
				</div>				
			</div>	<!-- .main_content -->		
		</div> <!-- .main -->';

	
		echo '
		<div class="side">
			<div class="explain">Links between entites in the knowledge graph appear here, such as
			citation links between works, lists of taxa in a publication, or where a person publishes and what
			taxa that work on.</div>
			
			<h4>How big is the knowledge graph?</h4>
			<div id="counts"></div>
			
			<h4>Examples</h4>
		
			<div class="list-item">
			  <a href="?uri=https://bie.ala.org.au/species/urn:lsid:biodiversity.org.au:afd.taxon:111fc7e9-0265-453e-8e60-1761e42efc9a">
				<div class="list-item-thumbnail">
				 <img src="http://exeg5le.cloudimg.io/crop/100x100/n/https://images.ala.org.au/image/proxyImageThumbnail?imageId=1decca49-f45e-46da-80cf-baa8cfdf5615" />
				</div>
				<div class="list-item-body">
				  <div class="list-item-title">Acupalpa Kröber, 1912</div>
				</div>
			   </a>
			</div>
		
			<div class="list-item">
			  <a href="?uri=https://biodiversity.org.au/afd/publication/%23creator/r-mesibov">
				<div class="list-item-thumbnail">
				 <img src="images/no-icon.svg" />
				</div>
				<div class="list-item-body">
				  <div class="list-item-title">R. Mesibov</div>
				</div>
			   </a>
			</div>

			<div class="list-item">
			  <a href="?uri=https://biodiversity.org.au/afd/publication/64908f75-456b-4da8-a82b-c569b4806c22">
				<div class="list-item-thumbnail">
				 <img src="http://exeg5le.cloudimg.io/height/100/n/https://zenodo.org/api/iiif/v2/67aaf08c-5b28-40e0-9c8f-905618da39a6:40222f16-d066-40ca-9ebd-79e80bbd29da:oo_16979.jpg/full/250,/0/default.jpg" />
				</div>
				<div class="list-item-body">
				  <div class="list-item-title">Australian Assassins, Part I: A review of the Assassin Spiders (Araneae, Archaeidae) of mid-eastern Australia</div>
				</div>
			   </a>
			</div>

			<div class="list-item">
			  <a href="?uri=https://biodiversity.org.au/afd/publication/3e0c1402-de05-4227-9df3-803e68300623">
				<div class="list-item-thumbnail">
				 <img src="https://cdn.rawgit.com/rdmpage/oz-afd-export/master/thumbnails/3/2/3e0c1402-de05-4227-9df3-803e68300623.png" />
				</div>
				<div class="list-item-body">
				  <div class="list-item-title">Revision of genera of the dragonets (Pisces : Callionymidae)</div>
				</div>
			   </a>
			</div>
		
		
			<div class="list-item">
			  <a href="?uri=https://biodiversity.org.au/afd/publication/8adcca9b-ba23-4332-8764-3137d09e3776">
				<div class="list-item-thumbnail">
				 <img src="images/no-icon.svg" />
				</div>
				<div class="list-item-body">
				  <div class="list-item-title">The Beagle, Records of the Museums and Art Galleries of the Northern Territory</div>
				</div>
			   </a>
			</div>
					
			
		</div>

		<div class="side">
			<div class="explain">This is where links to external knowledge graphs 
			such as Wikidata will appear. There may also be links to other databases such as ORCID.
	        </div>
		</div>';
			
	}

	echo '<script>count_types("counts");</script>';

	echo '		
	</div>';

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
		
	// Show search
	if (isset($_GET['q']))
	{	
		$query = $_GET['q'];
		display_search($query);
		exit(0);
	}
	
	// Show sparql
	if (isset($_GET['sparql']))
	{	
		display_sparql();
		exit(0);
	}	
	
	// Show tree
	if (isset($_GET['tree']))
	{	
		display_tree();
		exit(0);
	}			
	
}


main();

?>