       //--------------------------------------------------------------------------------
		function doi_in_wikidata(doi, element_id) {
			var sparql = `SELECT *
WHERE
{
  ?work wdt:P356 "DOI" .
}`;

			sparql = sparql.replace(/DOI/, doi.toUpperCase());
	
			$.getJSON('https://query.wikidata.org/bigdata/namespace/wdq/sparql?query=' + encodeURIComponent(sparql),
				function(data){
				  if (data.results.bindings.length == 1) {
            		html = 'DOI in Wikidata <a class="external" href="' + data.results.bindings[0].work.value + '" target="_new">' + data.results.bindings[0].work.value.replace("http://www.wikidata.org/entity/","") + '</a>';
				  } else {
				     html = 'DOI not in Wikidata';         
				  }
				  document.getElementById(element_id).innerHTML = html;
			});			

		}		     
    
    
       //--------------------------------------------------------------------------------
    function doi_in_orcid(doi, element_id) {   
      $.getJSON('https://enchanting-bongo.glitch.me/search?q=' + encodeURIComponent(doi) + '&callback=?',
            function(data){
				      if (data.orcid) {
                var html = '';
                if (data.orcid.length == 0) {
                  html = 'No ORCIDs linked to this DOI';                  
                } else {
                  for (var i in data.orcid) {
                    html += '<div>';
                    html += ' <a class="external orcid" href="https://orcid.org/' + data.orcid[i] + '" target="_new">orcid.org/' + data.orcid[i] + '</a>';
                    html += '</div>';                 
                  }
                }
                document.getElementById(element_id).innerHTML = html;
				      }  
			    });       
    
    }
    
    
       //--------------------------------------------------------------------------------
		function issn_in_wikidata(issn, element_id) {
			var sparql = `SELECT *
WHERE
{
  ?item wdt:P236 "ISSN" .
  OPTIONAL {
	?item wdt:P2007 ?zoobank .
 }
  
 OPTIONAL {
   ?item wdt:P123 ?publisher .
   ?publisher rdfs:label ?publisher_name .
   
   OPTIONAL {
     ?publisher wdt:P625 ?coordinates .
    }
    
    OPTIONAL {
	   ?publisher wdt:P18 ?image .
		}     
    
    FILTER (lang(?publisher_name) = 'en')
 }  
  
  
}`;

			sparql = sparql.replace(/ISSN/, issn.toUpperCase());
			
			console.log(sparql);
	
			$.getJSON('https://query.wikidata.org/bigdata/namespace/wdq/sparql?query=' + encodeURIComponent(sparql),
				function(data){
				  if (data.results.bindings.length > 0) {
				     var html = '';
				     
						 if (data.results.bindings[0].item) {
						   html += 'Wikidata <a href="' + data.results.bindings[0].item.value + '" target="_new">' + data.results.bindings[0].item.value.replace(/http:\/\/www.wikidata.org\/entity\//, "") + '</a><br />';
						 }
						 
						 if (data.results.bindings[0].zoobank) {
						   html += 'Zoobank <a href="http://zoobank.org/' + data.results.bindings[0].zoobank.value + '" target="_new">' + data.results.bindings[0].zoobank.value + '</a><br />';
						 }
						 
						 if (data.results.bindings[0].publisher_name) {
						   html += 'Publisher ' + data.results.bindings[0].publisher_name.value + '<br />';
						 }
						 
						 if (data.results.bindings[0].image) {
						   html += '<img src="' + data.results.bindings[0].image.value + '" width="200" />';
						 }
						

						 if (data.results.bindings[0].coordinates) {
						    var coordinates = data.results.bindings[0].coordinates.value;
						    coordinates = coordinates.replace(/Point\(/, '');
						    coordinates = coordinates.replace(/\)$/, '');
						    coordinates = coordinates.replace(/\s/, ',');
						    						   
							var url = 'https://api.mapbox.com/v4/mapbox.streets';
							var marker = '/pin-s-circle+285A98(' + coordinates + ')';
							var pt = '/' + coordinates;
							var zoom = ',2';
							var size = '/200x200@2x.png';
							var token='?access_token=pk.eyJ1IjoicmRtcGFnZSIsImEiOiJjajJrdmJzbW8wMDAxMnduejJvcmEza2k4In0.bpLlN9O6DylOJyACE8IteA';
			
							html += '<img src="' + url + marker + pt + zoom + size + token + '" width="200"/>';
						 }
						 
						 
				  } else {
				     html = 'ISSN not in Wikidata';         
				  }
				  
				  document.getElementById(element_id).innerHTML = html;
			});			

		}   
		
       //--------------------------------------------------------------------------------
		function wikispecies_author_wikidata(wikispecies, element_id) {
			var sparql = `SELECT *
WHERE
{
    VALUES ?article {<` + wikispecies +`>}
	?article schema:about ?item .
    ?item wdt:P31 wd:Q5 .
  OPTIONAL {
	   ?item wdt:P213 ?isni .
		}
	  OPTIONAL {
	   ?item wdt:P214 ?viaf .
		}    
	  OPTIONAL {
	   ?item wdt:P18 ?image .
		} 
	  OPTIONAL {
	   ?item wdt:P496 ?orcid .
		} 	
	  OPTIONAL {
	   ?item wdt:P2038 ?researchgate .
		} 					
	  OPTIONAL {
	   ?item wdt:P586 ?ipni .
		} 
	  OPTIONAL {
	   ?item wdt:P2006 ?zoobank .
		} 		
}`;
			
			console.log(sparql);
	
			$.getJSON('https://query.wikidata.org/bigdata/namespace/wdq/sparql?query=' + encodeURIComponent(sparql),
				function(data){
				  if (data.results.bindings.length > 0) {
				     var html = '';
				     
						 if (data.results.bindings[0].item) {
						   html += 'Wikidata <a class="external" href="' + data.results.bindings[0].item.value + '" target="_new">' + data.results.bindings[0].item.value.replace(/http:\/\/www.wikidata.org\/entity\//, "") + '</a><br />';
						 }
						 
						 if (data.results.bindings[0].zoobank) {
						   html += 'Zoobank <a class="external" href="http://zoobank.org/' + data.results.bindings[0].zoobank.value + '" target="_new">' + data.results.bindings[0].zoobank.value + '</a><br />';
						 }

						 if (data.results.bindings[0].viaf) {
						   html += 'VIAF <a class="external" href="https://viaf.org/viaf/' + data.results.bindings[0].viaf.value + '" target="_new">' + data.results.bindings[0].viaf.value + '</a><br />';
						 }

						 if (data.results.bindings[0].orcid) {
						   html += 'ORCID <a class="external orcid" href="https://orcid.org/' + data.results.bindings[0].orcid.value + '" target="_new">' + data.results.bindings[0].orcid.value + '</a><br />';
						 }

						 if (data.results.bindings[0].researchgate) {
						   html += 'ResearchGate <a class="external" href="https://www.researchgate.net/profile/' + data.results.bindings[0].researchgate.value + '" target="_new">' + data.results.bindings[0].researchgate.value + '</a><br />';
						 }
						 
						 if (data.results.bindings[0].image) {
						   html += '<img src="' + data.results.bindings[0].image.value + '" width="100" />';
						 }
						 
						 
				  } else {
				     html = '';         
				  }
				  
				  document.getElementById(element_id).innerHTML = html;
			});			

		}  		 
		
		
       //--------------------------------------------------------------------------------
       // bionames names 
    function bionames_page_names(identifier, name_space, page, element_id) {
       
      $.getJSON('//bionames.org/bionames-api/oz-names-on-page.php?identifier=' 
      	+ encodeURIComponent(identifier) 
      	+ '&namespace=' + name_space
      	+ '&page=' + page      	
      	+ '&callback=?',
            function(data){
				      if (data.names) {
				         	var html = '';
							for (var i in data.names) {
								// need to think about mapping between page numbers in
								// physical and digital documents :()
														
							   if (i == page) {
							   	for (var j in data.names[i]) {
								  html += data.names[i][j].name + '<br />';
								  html += '<a class="external" href="http://www.organismnames.com/details.htm?lsid=' + data.names[i][j].id.replace(/urn:lsid:organismnames.com:name:/, '') + '" target=_new">' +  data.names[i][j].id + '</a><br />';
								}
							   }				         	
				            }
				         document.getElementById(element_id).innerHTML = html;
				     } 
			    });       
    
    }		