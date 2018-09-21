
function occurrence_images(uri, element_id) {

			var query = `SELECT *
WHERE
{
  <` + uri + `> <http://schema.org/sameAs> ?o .
  
  BIND (IRI(?o) as ?gbif) .
  
  ?identification <http://rs.tdwg.org/dwc/iri/toTaxon> ?gbif  .
  ?identification <http://purl.org/dsw/identifies> ?organism .
  ?image <http://schema.org/about> ?organism .
  ?image <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://schema.org/ImageObject> .
} `;

//alert(query);
		
			$.getJSON('query.php?query=' + encodeURIComponent(query)
					+ '&callback=?',
				function(data){
  					//alert(JSON.stringify(data ));
  					
  					console.log(JSON.stringify(data, null, 2));
  					
  					//$('#main').html('<div style="background:rgb(242,242,242);white-space:pre;font-size:10px;padding:10px;">' + JSON.stringify(data, null, 2) + '</style>');
  					
  					if (data.results.bindings.length > 0) {
					
						var html = '<h4>Images of occurrences from GBIF</h4>';
						
						
					
						html += '<div class="taxa-grid clearfix">';
					
						for (var i in data.results.bindings) {  					
							html += '<div class="taxa-thumbnail">';
							
							if (data.results.bindings[i].image) {
								html += '<img src="' 
									+ 'http://exeg5le.cloudimg.io/crop/100x100/n/'
									+ data.results.bindings[i].image.value 
									+ '" />';
							}
															
							/*			
							html += '<a href="?uri='
								+ data.results.bindings[i].part.value 
								+ '">';
								//+ '<span>' + data.results.bindings[i].child_name.value + '</span>';
							
							if (data.results.bindings[i].thumbnailUrl) {
								html += '<img src="' 
									+ 'http://exeg5le.cloudimg.io/crop/100x100/n/'
									+ data.results.bindings[i].thumbnailUrl.value 
									+ '" />';
							} 
							html += '</a>';  
							*/
													
							html += '</div>';  						
						}
						html += '</div>';  
					
						$('#' + element_id).html(html);
					}  					
  
  				}
  			);
  			
  		
		
		}	
		
		
//----------------------------------------------------------------------------------------
// experimental function to get gap in time between species decsription and occurrece collection
function occurrence_lag(uri, element_id) {

			var query = `PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
SELECT (MAX(xsd:integer(?date) - xsd:integer(?year)) as ?d) 

WHERE
{
  # Taxon publication date
   <` + uri + `>
   <http://taxref.mnhn.fr/lod/property/hasReferenceName>|<http://taxref.mnhn.fr/lod/property/hasSynonym> ?taxonName .
  ?taxonName <http://schema.org/name> ?tname .
  ?taxonName <http://rs.tdwg.org/ontology/voc/Common#publishedInCitation> ?work .
  ?work <http://schema.org/datePublished> ?date .
 
    
  # GBIF occurrence date
  # Get GBIF taxon
  <` + uri + `> <http://schema.org/sameAs> ?o .  
  BIND (IRI(?o) as ?gbif) .
  
  # Bound through nodes to get to event
  ?identification <http://rs.tdwg.org/dwc/iri/toTaxon> ?gbif  .
  ?identification  <http://purl.org/dsw/identifies> ?organism .
  ?occurrence <http://purl.org/dsw/occurrenceOf> ?organism .
  ?occurrence <http://purl.org/dsw/atEvent> ?event .
  ?event <http://rs.tdwg.org/dwc/terms/year> ?year .
}
LIMIT 1`;

//alert(query);
		
			$.getJSON('query.php?query=' + encodeURIComponent(query)
					+ '&callback=?',
				function(data){
  					//alert(JSON.stringify(data ));
  					
  					console.log(JSON.stringify(data, null, 2));
  					
  					//$('#main').html('<div style="background:rgb(242,242,242);white-space:pre;font-size:10px;padding:10px;">' + JSON.stringify(data, null, 2) + '</style>');
  					
  					if (data.results.bindings.length > 0) {
					
						var html = '<h4>Lag in description</h4>';
						html += '<span class="explain">Length in time between oldest collection date and taxon description:</span>';
					
						for (var i in data.results.bindings) {  					
							html += '<span>' + data.results.bindings[i].d.value + ' years' + '</span>';
						}
					
						$('#' + element_id).html(html);
					}  					
  
  				}
  			);
  			
  		
		
		}			
