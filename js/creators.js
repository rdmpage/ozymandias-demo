function creators_for_entity(uri, element_id) {
	var query = `PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
SELECT *
WHERE
{
<` + uri + `> <http://schema.org/creator> ?role .
?role <http://schema.org/creator> ?creator .
?role <http://schema.org/roleName> ?position . 
?creator <http://schema.org/name> ?name .
}
ORDER BY (xsd:integer(?position))`;

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
			//alert(JSON.stringify(data ));
			
			console.log(JSON.stringify(data, null, 2));  	
			
			var html = '<ul class="creator">';	
			
			for (var i in data.results.bindings) {

				html += '<li>';
				
				html += '<a href="?uri='
					+ data.results.bindings[i].creator.value.replace(/#/, '%23')
					+ '">';
				
				html += data.results.bindings[i].name.value.replace(/\s+/, '&nbsp;');
				
				html += '</a>';
				
				html += '</li>';
			} 
			html += '</ul>'
			$('#' + element_id).html(html);  							

		}
	);
			
}
