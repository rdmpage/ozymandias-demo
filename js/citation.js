	//--------------------------------------------------------------------------------
	function cited_by (uri, element_id) {
		var query = `SELECT ?work ?name
WHERE
{
<` + uri + `> <http://schema.org/identifier> ?identifier .
# Identifier (e.g., DOI) for work we are displaying 
?identifier <http://schema.org/value> ?identifier_value .

# For CrossRef records, we will have another object with this DOI 
?cited_identifier <http://schema.org/value> ?identifier_value .
?cited <http://schema.org/identifier> ?cited_identifier .

# Work citing this work (typically from CrossRef data)
?cited_by <http://schema.org/citation> ?cited .

# Translate the citing work's DOI (or other identifier) into AFD identifier
# Get identifier (typically a DOI) for citing work
?cited_by <http://schema.org/identifier> ?cited_by_identifier .
?cited_by_identifier <http://schema.org/value> ?cited_by_identifier_value .

# Get work(s) with this identifer (may have > 1 if we have CrossRef record in our triple store
?work_identifier <http://schema.org/value> ?cited_by_identifier_value .
?work <http://schema.org/identifier> ?work_identifier .
?work <http://schema.org/name> ?name .

# Just include citing records that are also in ALA
FILTER regex(str(?work),'biodiversity.org.au') .
}`;
	
		$.getJSON('query.php?query=' + encodeURIComponent(query)
				+ '&callback=?',
			function(data){
				//alert(JSON.stringify(data ));
				
				console.log(JSON.stringify(data, null, 2));  
				
				if (data.results.bindings.length > 0) {
					
				
					var html = '<h4>Cited by</h4>';	
				
					html += '<ul class="work-list">';			
				
					for (var i in data.results.bindings) {
						html += '<li>';	
					
						html += '<a href="?uri=' + data.results.bindings[i].work.value + '">';
						html += data.results.bindings[i].name.value;
						html += '</a>';

						html += '</li>';	
					} 
					
					html += '</ul>';	
					
					$('#' + element_id).html(html);  							
				}
			}
		);
				
		}
		
	//--------------------------------------------------------------------------------
	function cites (uri, element_id) {
		var query = `SELECT ?work ?name
WHERE
{
<` + uri + `> <http://schema.org/identifier> ?identifier .
# Identifier (e.g., DOI) for work we are displaying 
?identifier <http://schema.org/value> ?identifier_value .

# For CrossRef records, we will have another object with this DOI 
?citing_identifier <http://schema.org/value> ?identifier_value .
?citing <http://schema.org/identifier> ?citing_identifier .

# What does this work cite (typically from CrossRef data)
?citing <http://schema.org/citation> ?cited .

# Translate the citing work's DOI (or other identifier) into AFD identifier
# Get identifier (typically a DOI) for citing work
?cited <http://schema.org/identifier> ?cited_identifier .
?cited_identifier <http://schema.org/value> ?cited_identifier_value .

# Get work(s) with this identifer (may have > 1 if we have CrossRef record in our triple store
?work_identifier <http://schema.org/value> ?cited_identifier_value .
?work <http://schema.org/identifier> ?work_identifier .
?work <http://schema.org/name> ?name .

# Just include citing records that are also in ALA
FILTER regex(str(?work),'biodiversity.org.au') .
}`;
	
		$.getJSON('query.php?query=' + encodeURIComponent(query)
				+ '&callback=?',
			function(data){
				//alert(JSON.stringify(data ));
				
				console.log(JSON.stringify(data, null, 2));  
				
				if (data.results.bindings.length > 0) {
				
					var html = '<h4>Cites</h4>';	
				
					html += '<ul class="work-list">';			
				
					for (var i in data.results.bindings) {
						html += '<li>';	
					
						html += '<a href="?uri=' + data.results.bindings[i].work.value + '">';
						html += data.results.bindings[i].name.value;
						html += '</a>';

						html += '</li>';	
					} 
					
					html += '</ul>';	
					
					$('#' + element_id).html(html);  							
				}
			}
		);
				
		}
				
