// list of works by a creator
function works_by_creator(uri, element_id) {
	
	var query = `SELECT *
WHERE
{
?role <http://schema.org/creator> <` + uri + `>  .
?work <http://schema.org/creator> ?role  .
?work <http://schema.org/name> ?name .
OPTIONAL {
?work <http://schema.org/datePublished> ?datePublished . 

OPTIONAL {
?work <http://schema.org/identifier> ?identifier .
?identifier <http://schema.org/propertyID> "doi" .
?identifier <http://schema.org/value> ?doi .
}    

OPTIONAL {
?work <http://schema.org/thumbnailUrl> ?thumbnailUrl .
}    

}
}`;

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
			
			var html = '';
			
			for (var i in data.results.bindings) {
				html += '<div class="list-item">';
				
				html += '<a href="?uri=' + data.results.bindings[i].work.value + '">';
				
				html += '<div class="list-item-thumbnail">';
				
				if (data.results.bindings[i].thumbnailUrl) {
					html += '<img src="' 
						+ data.results.bindings[i].thumbnailUrl.value 
						+ '" />';
				}
				html += '</div>';
				
				html += '<div class="list-item-body">';
				html += '   <div class="list-item-title">';
				html += data.results.bindings[i].name.value;
				html += '   </div>';
				html += '   <div class="list-item-description">';
				html += '   </div>';
				html += '</div>';
				
				html += '</a>';
				
				html += '</div>';
			}
			$('#' + element_id).html(html);
		}
	);
}		