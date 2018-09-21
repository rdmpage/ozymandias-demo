       //--------------------------------------------------------------------------------
		function name_in_gbif(namestring, element_id) {
	
			$.getJSON('https://api.gbif.org/v1/species/match?name=' + encodeURIComponent(namestring) + '&kingdom=Animalia&verbose=true',
				function(data){
				  if (data.usageKey) {
				    var html = '';
				    if (data.confidence > 90 && data.usageKey != 1) {
            			html = 'Taxon in GBIF <a class="external gbif" href="https://gbif.org/species/' + data.usageKey + '" target="_new">' + data.usageKey + '</a>';
            			
            			gbif_types(data.usageKey, 'gbif_occurrences');
            			
				  	} else {
				     		html = 'No close match in GBIF';         
				  	}
				  	document.getElementById(element_id).innerHTML = html;
				  }
				  
			});			

		}	
		
		
		
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
						
									html += '<a class="external gbif" href="https://gbif.org/occurrence/' + data.results[i].key + '">' +  data.results[i].key + '</a>';
						
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
