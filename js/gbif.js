       //--------------------------------------------------------------------------------
		function name_in_gbif(namestring, element_id) {
	
			$.getJSON('https://api.gbif.org/v1/species/match?name=' + encodeURIComponent(namestring) + '&kingdom=Animalia&verbose=true',
				function(data){
				  if (data.usageKey) {
				    var html = '';
				    if (data.confidence > 90 && data.usageKey != 1) {
            			html = 'Taxonomic databases <a class="external gbif" href="https://gbif.org/species/' + data.usageKey + '" target="_new">' + data.usageKey + '</a>';
            			
            			gbif_types(data.usageKey, 'gbif_occurrences');
            			
            			gbif_to_wikidata(data.usageKey, 'match_taxon_external');
            			
				  	} else {
				     		html = 'No close match in GBIF';         
				  	}
				  	document.getElementById(element_id).innerHTML = html;
				  }				  
			});			
		}			
		
       //--------------------------------------------------------------------------------
		function gbif_to_wikidata(gbif, element_id) {
			var sparql = `PREFIX wdt: <http://www.wikidata.org/prop/direct/>
PREFIX wd: <http://www.wikidata.org/entity/>
SELECT ?ncbi ?wiki ?species
WHERE
{
  # Wikidata
  ?taxon wdt:P846 "GBIF" .
  
  # NCBI
  OPTIONAL {
    ?taxon wdt:P685 ?ncbi .
  }
  
  # English language Wikipedia
   OPTIONAL {
     ?wikipedia_en schema:about ?taxon .
     ?wikipedia_en schema:isPartOf <https://en.wikipedia.org/> .
     BIND( REPLACE( STR(?wikipedia_en),"https://en.wikipedia.org/wiki/","" ) AS ?wiki).
  }
  
 	# Wikispecies
   OPTIONAL {
 	?wikispecies schema:about ?taxon .
	?wikispecies schema:isPartOf <https://species.wikimedia.org/> .
    BIND( REPLACE( STR(?wikispecies),"https://species.wikimedia.org/wiki/","" ) AS ?species).
    }  

}`;

			sparql = sparql.replace(/GBIF/, gbif);
			
			console.log(sparql);
	
			$.getJSON('https://query.wikidata.org/bigdata/namespace/wdq/sparql?query=' + encodeURIComponent(sparql),
				function(data){
				  if (data.results.bindings.length > 0) {
				     var html = '';
						 
						 if (data.results.bindings[0].ncbi) {
						   html += '<a class="external ncbi" href="https://www.ncbi.nlm.nih.gov/Taxonomy/Browser/wwwtax.cgi?mode=Info&id=' + data.results.bindings[0].ncbi.value + '" target="_new">' + data.results.bindings[0].ncbi.value + '</a><br />';
						 }

						 if (data.results.bindings[0].wiki) {
						 	dbpedia_summary(data.results.bindings[0].wiki.value, 'match_dbpedia');
						 }
						 
						 
				  } else {
				     html = '';         
				  }
				  
				  document.getElementById(element_id).innerHTML = html;
			});			

		}   		
		
		
		
      //--------------------------------------------------------------------------------		
		function dbpedia_summary(wikipedia, element_id) {
		
			$.getJSON('http://dbpedia.org/sparql?default-graph-uri=http://dbpedia.org&query=DESCRIBE <http://dbpedia.org/resource/' + wikipedia + '>&format=application/json-ld',
				function(data){
				  if (data) {
				  	var html = '';
				  	for (var i in data) {
				  		if (data[i]['http://www.w3.org/2000/01/rdf-schema#comment']) {	
				  			for (var j in data[i]['http://www.w3.org/2000/01/rdf-schema#comment'])	{	  			
				  				if (data[i]['http://www.w3.org/2000/01/rdf-schema#comment'][j].lang == 'en') {
				  			  		html += '<div style="color: #999;font-size:0.8em;">' 
				  			  		+ data[i]['http://www.w3.org/2000/01/rdf-schema#comment'][j].value 
				  			  		+ ' ' 
				  			  		+ '(from <a href="https://en.wikipedia.org/wiki/' + wikipedia + '" target="_new">Wikipedia</a>)'
				  			  		+ '</div>';
				  			  	}
				  			}
				  		}
				  	}
				  	 document.getElementById(element_id).innerHTML = html;
				  }
				}
			);
		}
		
		
      //--------------------------------------------------------------------------------		
		function gbif_types(id, element_id) {
		
			$.getJSON('https://api.gbif.org/v1/occurrence/search?taxonKey=' + id + '&typestatus=*',
				function(data){
				  if (data.results) {

					if (data.results.length > 0) {
					
						// GBIF will return occurrences for all taxa rooted on this node, so filter
					    var count = 0;
						for (var i in data.results) {
							if (data.results[i].speciesKey == id) {
							   count++;
							}
						}
						if (count > 0) {

							var html = '<h4>Type specimens in GBIF</h4>';
					
							html += '<ul>';
					
							for (var i in data.results) {
								if (data.results[i].speciesKey == id) {
					
									html += '<li>';
						
									html += '<a class="external gbif" href="https://gbif.org/occurrence/' + data.results[i].key + '" target="_new">' +  data.results[i].key + '</a>';
						
									html += ' ' + data.results[i].typeStatus;
						
									var terms = [];
								
									if (data.results[i].year) {
										terms.push(data.results[i].year);
									}

									if (data.results[i].recordedBy) {
										terms.push('recorded by ' + data.results[i].recordedBy);
									}				    	
						
									if (data.results[i].identifiedBy) {
										terms.push('identified by ' + data.results[i].identifiedBy);
									}
								
									html += ' ' + terms.join(', ');
									
									// images...?
									if (data.results[i].media) {
										for (var j in data.results[i].media) {
											if (data.results[i].media[j].type == "StillImage") {
												html += '<div>'
												    + '<img src="' 						   
													+ 'http://exeg5le.cloudimg.io/crop/100x100/n/' 
													+ data.results[i].media[j].identifier 						   
													+ '" />'
													+ '</div>';
											}
										}								
									}
						
									html += '</li>';
								}
							
					
							}
							html += '</ul>';
							document.getElementById(element_id).innerHTML = html;
						 }
					  }
				  }
			});			
		
		
		
		
		
		}
