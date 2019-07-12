function identifiers_for_entity(uri, element_id) {
	var query = `SELECT *
WHERE
{
<` + uri + `> <http://schema.org/identifier> ?identifier .
?identifier <http://schema.org/propertyID> ?namespace .
?identifier <http://schema.org/value> ?value .
}`;

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
			//alert(JSON.stringify(data ));
			
			console.log(JSON.stringify(data, null, 2));  	
			
			var html = '';	
			
			html += '<ul class="identifier-list">';			
			
			for (var i in data.results.bindings) {
				
				switch (data.results.bindings[i].namespace.value) {
				
					case 'biostor':
						html += '<li>';	
						html += '<a class="external" href="https://biostor.org/reference/' + data.results.bindings[i].value.value + '" target="_new">' + 'https://biostor.org/reference/' + data.results.bindings[i].value.value + '</a>';
						html += '</li>';	
						break;  						
				
					case 'doi':
						html += '<li>';	
						html += '<a class="external" href="https://doi.org/' + data.results.bindings[i].value.value + '" target="_new">' + 'https://doi.org/' + data.results.bindings[i].value.value + '</span>';
												
						// Get some hints about what identifiers we could be adding
						doi_in_wikidata(data.results.bindings[i].value.value, 'wikidata');
						doi_in_orcid(data.results.bindings[i].value.value, 'orcid');						
						
						html += '</li>';	
						break;
						
					case 'handle':
						html += '<li>';	
						html += '<a class="external" href="https://hdl.handle.net/' + data.results.bindings[i].value.value + '" target="_new">' + 'https://hdl.handle.net/' + data.results.bindings[i].value.value + '</a>';
						html += '</li>';	
						break;

					case 'jstor':
						html += '<li>';	
						html += '<a class="external" href="https://www.jstor.org/stable/' + data.results.bindings[i].value.value + '" target="_new">' + 'https://www.jstor.org/stable/' + data.results.bindings[i].value.value + '</a>';
						html += '</li>';	
						break;
						
					case 'pmid':
						html += '<li>';	
						html += '<a class="external" href="https://www.ncbi.nlm.nih.gov/pubmed/' + data.results.bindings[i].value.value + '" target="_new">' + 'https://www.ncbi.nlm.nih.gov/pubmed/' + data.results.bindings[i].value.value + '</a>';
						html += '</li>';	
						break;
						
					case 'sici':
						break;						

					case 'zenodo':
						html += '<li>';	
						html += '<a class="external" href="https://zenodo.org/record/' + data.results.bindings[i].value.value + '" target="_new">' + 'https://zenodo.org/record/' + data.results.bindings[i].value.value + '</a>';
						html += '</li>';	
						break;
						
					case 'zoobank':
						html += '<li>';	
						html += '<a class="external" href="http://zoobank.org/' + data.results.bindings[i].value.value.replace('urn:lsid:zoobank.org:pub:', '') + '" target="_new">' + 'http://zoobank.org/' + data.results.bindings[i].value.value.replace('urn:lsid:zoobank.org:pub:', '') + '</a>';
						html += '</li>';	
						break;
						
					default:
						html += data.results.bindings[i].namespace.value + ':' + data.results.bindings[i].value.value;
						break;
				}
			
			
				
			} 
				
			html += '</ul>';	
				
			$('#' + element_id).html(html);  							

		}
	);
			
	}