//--------------------------------------------------------------------------------
// Match creator to ORCID using works and named graphs
function match_orcid(uri, element_id) {
	
	var query = `SELECT DISTINCT ?name ?orcid_creator ?orcid_name
WHERE
{
  GRAPH <https://biodiversity.org.au/afd/publication> {
  <` + uri + `> <http://schema.org/name> ?name .
?role <http://schema.org/creator> <` + uri + `>  .
?role <http://schema.org/roleName> ?roleName  .

?work <http://schema.org/creator> ?role  .

?work <http://schema.org/identifier> ?identifier .
?identifier <http://schema.org/propertyID> "doi" .
?identifier <http://schema.org/value> ?doi .
}
  
  GRAPH <https://orcid.org>
  {
    ?orcid_identifier <http://schema.org/value> ?doi .
    ?orcid_work <http://schema.org/identifier> ?orcid_identifier .
    
	?orcid_work <http://schema.org/creator> ?orcid_role  . 
    ?orcid_role <http://schema.org/roleName> ?orcid_roleName  .
    
    ?orcid_role <http://schema.org/creator> ?orcid_creator  .
    
    ?orcid_creator <http://schema.org/name> ?orcid_name .
  } 
  
  FILTER(?roleName = ?orcid_roleName)
}`;


	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
		
			console.log(JSON.stringify(data, null, 2));			
			
			if (data.results.bindings.length > 0) {
			
				// First check that we have a good match
				var matches = [];
				
				// dictionary is name
				var fs = FuzzySet([data.results.bindings[0].name.value], false);
				for (var i in data.results.bindings) {
					// test for match
					var s = fs.get(data.results.bindings[i].orcid_name.value);
					
					if (s) {
              			if (s[0][0] > 0.6) {
              				console.log(data.results.bindings[i].orcid_name.value + ' ' + s[0][0] + ' ' + i);
              				matches.push(i);
						}
					}
				}	
				
				if (matches.length > 0) {
					var html = '<h4>ORCID match.</h4>';
					html += '<div>';
					//html += '<ul>';
			
					for (var i in matches) {
						html += '<div>';
						html += '<a class="external orcid" href="' + data.results.bindings[matches[i]].orcid_creator.value + '">';
						html += data.results.bindings[matches[i]].orcid_name.value;
						html += '</a>';
						html += '</div>';
					}
					//html += '</ul>';
					html += '</div>';
			
					$('#' + element_id).html(html);
										
				}
						
			}
		}
	);
}	

//--------------------------------------------------------------------------------
// Match creator to wikispecies using works and named graphs
// match on nay shared identifier such as DOI or SICI
function match_wikispecies(uri, element_id) {
	
	var query = `SELECT DISTINCT ?name ?external_creator ?external_name
WHERE
{
  GRAPH <https://biodiversity.org.au/afd/publication> {
  <` + uri + `> <http://schema.org/name> ?name .
?role <http://schema.org/creator> <` + uri + `>  .
?role <http://schema.org/roleName> ?roleName  .

?work <http://schema.org/creator> ?role  .

?work <http://schema.org/identifier> ?identifier .
?identifier <http://schema.org/value> ?identifier_value .
}
  
GRAPH <https://species.wikimedia.org>
  {
    ?external_identifier <http://schema.org/value> ?identifier_value .
    ?external_work <http://schema.org/identifier> ?external_identifier .
    
	?external_work <http://schema.org/creator> ?external_role  . 
    ?external_role <http://schema.org/roleName> ?external_roleName  .
    
    ?external_role <http://schema.org/creator> ?external_creator  .
    
    ?external_creator <http://schema.org/name> ?external_name .
  }   
  
  
  FILTER(?roleName = ?external_roleName)
}`;

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
		
			console.log(JSON.stringify(data, null, 2));			
			
			if (data.results.bindings.length > 0) {
			
				// First check that we have a good match
				var matches = [];
				
				// dictionary is name
				var fs = FuzzySet([data.results.bindings[0].name.value], false);
				for (var i in data.results.bindings) {
					// test for match
					
					var ename = data.results.bindings[i].external_name.value;
					// commas?
					var m = ename.split(',');
					if (m.length == 2) {
						ename = m[1] + ' ' + m[0];
					}
					var s = fs.get(ename);
					
					if (s) {
              			if (s[0][0] > 0.6) {
              				console.log(data.results.bindings[i].external_name.value + ' ' + s[0][0] + ' ' + i);
              				matches.push(i);
						}
					}
				}	
				
				if (matches.length > 0) {
					var html = '<h4>Wikispecies match.</h4>';
					html += '<div>';
					//html += '<ul>';
			
					for (var i in matches) {
						html += '<div>';
						html += '<a class="external" href="' + data.results.bindings[matches[i]].external_creator.value + '">';
						html += data.results.bindings[matches[i]].external_name.value;
						html += '</a>';
						html += '</div>';
					}
					//html += '</ul>';
					html += '</div>';
			
					$('#' + element_id).html(html);
										
				}
						
			}
		}
	);
}		