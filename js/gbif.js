       //--------------------------------------------------------------------------------
		function name_in_gbif(namestring, element_id) {
	
			$.getJSON('https://api.gbif.org/v1/species/match?name=' + encodeURIComponent(namestring) + '&kingdom=Animalia&verbose=true',
				function(data){
				  if (data.usageKey) {
				    var html = '';
				    if (data.confidence > 90) {
            		html = 'Taxon in GBIF <a class="external gbif" href="https://gbif.org/species/' + data.usageKey + '" target="_new">' + data.usageKey + '</a>';
				  		} else {
				     		html = 'No close match in GBIF';         
				  	}
				  	document.getElementById(element_id).innerHTML = html;
				  }
				  
			});			

		}		     
