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