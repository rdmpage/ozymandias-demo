
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
		
		
		
//----------------------------------------------------------------------------------------
function occurrence_event(uri, element_id) {

			var query = `SELECT * 
WHERE 
{
  # event 
  <` + uri + `> <http://purl.org/dsw/atEvent> ?event .
  # date elements
  OPTIONAL {
  	?event <http://rs.tdwg.org/dwc/terms/year> ?year .
  }
  OPTIONAL {
	  ?event <http://rs.tdwg.org/dwc/terms/month> ?month .
  }
  OPTIONAL {  
  	?event <http://rs.tdwg.org/dwc/terms/day> ?day .
  }
}`;

//alert(query);
		
			$.getJSON('query.php?query=' + encodeURIComponent(query)
					+ '&callback=?',
				function(data){
  					
  					console.log(JSON.stringify(data, null, 2));
  					
  					if (data.results.bindings.length > 0) {
					
						var html = '<h4>Event</h4>';
					
						var date_terms = [];
						
						for (var i in data.results.bindings) {
							if (data.results.bindings[i].year) {
								date_terms.push(data.results.bindings[i].year.value);
							}
							if (data.results.bindings[i].month) {
								date_terms.push(data.results.bindings[i].month.value);
							}
							if (data.results.bindings[i].day) {
								date_terms.push(data.results.bindings[i].day.value);
							}							
							
						}
						
						if (date_terms.length > 0) {
							html += date_terms.join('-') + '<br />';
						}	
					
						$('#' + element_id).html(html);
					}  					
  
  				}
  			);
  			
  		
		
		}		
//----------------------------------------------------------------------------------------
function occurrence_location(uri, element_id) {

			var query = `SELECT * 
WHERE 
{
  # event 
  <` + uri + `> <http://purl.org/dsw/atEvent> ?event .
  # location
  ?event <http://purl.org/dsw/locatedAt> ?location .
  # coordinates
  ?location <http://rs.tdwg.org/dwc/terms/decimalLongitude> ?lon .
  ?location <http://rs.tdwg.org/dwc/terms/decimalLatitude> ?lat .
  # description
  OPTIONAL {
  ?location <http://rs.tdwg.org/dwc/terms/country> ?country .
  }
  OPTIONAL {
  ?location <http://rs.tdwg.org/dwc/terms/stateProvince> ?stateProvince .
  }
  OPTIONAL {
  ?location <http://rs.tdwg.org/dwc/terms/locality> ?locality .
 }
}`;

//alert(query);
		
			$.getJSON('query.php?query=' + encodeURIComponent(query)
					+ '&callback=?',
				function(data){
  					
  					console.log(JSON.stringify(data, null, 2));
  					
  					if (data.results.bindings.length > 0) {
					
						var html = '<h4>Location</h4>';
						//html += '<span class="explain">Length in time between oldest collection date and taxon description:</span>';
					
						var coordinates = [];
						
						var location_terms = [];
						
						for (var i in data.results.bindings) {
							if (data.results.bindings[i].lon) {
								coordinates.push(data.results.bindings[i].lon.value);
							}
							if (data.results.bindings[i].lat) {
								coordinates.push(data.results.bindings[i].lat.value);
							}
							
							if (data.results.bindings[i].country) {
								location_terms.push(data.results.bindings[i].country.value);
							}
							if (data.results.bindings[i].stateProvince) {
								location_terms.push(data.results.bindings[i].stateProvince.value);
							}
							if (data.results.bindings[i].locality) {
								location_terms.push(data.results.bindings[i].locality.value);
							}
							
							
						}
						
						if (location_terms.length > 0) {
							html += '<span class="explain">' + location_terms.join(', ') + '</span>' + '<br />';
						}	

						
						
						if (coordinates.length == 2) {
							var url = 'https://api.mapbox.com/v4/mapbox.streets';
							var marker = '/pin-s-circle+285A98(' + coordinates.join(',') + ')';
							var pt = '/' + coordinates.join(',');
							var zoom = ',6';
							var size = '/200x200@2x.png';
							var token='?access_token=pk.eyJ1IjoicmRtcGFnZSIsImEiOiJjajJrdmJzbW8wMDAxMnduejJvcmEza2k4In0.bpLlN9O6DylOJyACE8IteA';
			
							html += '<img src="' + url + marker + pt + zoom + size + token + '" width="200"/>';
						
						}
					
						$('#' + element_id).html(html);
					}  					
  
  				}
  			);
  			
  		
		
		}			

//----------------------------------------------------------------------------------------
// evidence is specimen, observation, etc.
function occurrence_evidence(uri, element_id) {

			var query = `SELECT * 
WHERE 
{
  # evidence 
  ?evidence <http://purl.org/dsw/evidenceFor> <` + uri + `> .
  OPTIONAL {
  	?evidence <http://rs.tdwg.org/dwc/terms/institutionCode> ?institutionCode .
  }
  OPTIONAL {
	?evidence <http://rs.tdwg.org/dwc/terms/collectionCode> ?collectionCode .
  }
  OPTIONAL {  
  	?evidence <http://rs.tdwg.org/dwc/terms/catalogNumber> ?catalogNumber .
  }
}`;

//alert(query);
		
			$.getJSON('query.php?query=' + encodeURIComponent(query)
					+ '&callback=?',
				function(data){
  					
  					console.log(JSON.stringify(data, null, 2));
  					
  					if (data.results.bindings.length > 0) {
					
						var html = '<h4>Evidence</h4>';
					
						var specimen_terms = [];
						
						for (var i in data.results.bindings) {
							if (data.results.bindings[i].institutionCode) {
								specimen_terms.push(data.results.bindings[i].institutionCode.value);
							}
							if (data.results.bindings[i].collectionCode) {
								specimen_terms.push(data.results.bindings[i].collectionCode.value);
							}
							if (data.results.bindings[i].catalogNumber) {
								specimen_terms.push(data.results.bindings[i].catalogNumber.value);
							}							
							
						}
						
						if (specimen_terms.length > 0) {
							html += specimen_terms.join(' ') + '<br />';
						}	
					
						$('#' + element_id).html(html);
					}  					
  
  				}
  			);
  			
  		
		
		}
		
//----------------------------------------------------------------------------------------
// image (a kind of token)
function occurrence_image(uri, element_id) {

			var query = `SELECT * 
WHERE 
{
  # organism 
   <` + uri + `> <http://purl.org/dsw/occurrenceOf> ?organism .
   
  # "token"
  ?image  <http://purl.org/dsw/derivedFrom> ?organism .
  
  # we want images
  ?image  <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://schema.org/ImageObject>  .
  ?image  <http://schema.org/contentUrl> ?contentUrl .
  
}`;

//alert(query);
		
			$.getJSON('query.php?query=' + encodeURIComponent(query)
					+ '&callback=?',
				function(data){
  					
  					console.log(JSON.stringify(data, null, 2));
  					
  					if (data.results.bindings.length > 0) {
					
						var html = '';
						
						for (var i in data.results.bindings) {
							if (data.results.bindings[i].contentUrl) {
								html += '<div>';
							
								html += '<img src="' 
									+ 'http://exeg5le.cloudimg.io/crop/100x100/n/'
									+ data.results.bindings[i].contentUrl.value 
									+ '" />';

								html += '</div>';
							}
						}
					
						$('#' + element_id).html(html);
					}  					
  
  				}
  			);
  			
  		
		
		}	
		
//----------------------------------------------------------------------------------------
// identification
function occurrence_identification(uri, element_id) {

			var query = `SELECT * 
WHERE 
{
  #evidence
  ?evidence <http://purl.org/dsw/evidenceFor>  <` + uri + `> .
  
  # identification 
  ?evidence <http://purl.org/dsw/isBasisForId> ?identification .
  
  # taxon
  ?identification <http://rs.tdwg.org/dwc/iri/toTaxon> ?taxon .
  
  # taxon name 
 OPTIONAL {
	  ?identification <http://rs.tdwg.org/dwc/terms/scientificName> ?scientificName   .
  }  
  
  # type status
  OPTIONAL {
	  ?identification <http://rs.tdwg.org/dwc/terms/typeStatus> ?typeStatus  .
  }
}



`;

//alert(query);
		
			$.getJSON('query.php?query=' + encodeURIComponent(query)
					+ '&callback=?',
				function(data){
  					
  					console.log(JSON.stringify(data, null, 2));
  					
  					if (data.results.bindings.length > 0) {
					
						var html = '<h4>Identification(s)</h4>';
						
						html += '<ul>';
					
						for (var i in data.results.bindings) {
								html += '<li>';
																
								if (data.results.bindings[i].taxon) {
									html += data.results.bindings[i].taxon.value + '<br />';
								}
								
								if (data.results.bindings[i].scientificName) {
									html += data.results.bindings[i].scientificName.value + '<br />';
								}
																
								if (data.results.bindings[i].collectionCode) {
									html += data.results.bindings[i].typeStatus.value + '<br />';
								}
								
								html += '</li>';
							
						}
						
						html += '</ul>';	
					
						$('#' + element_id).html(html);
					}  					
  
  				}
  			);
  			
  		
		
		}				