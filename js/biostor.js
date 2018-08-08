
function biostor_viewer (uri, element_id) {
/*	var query = `SELECT *
WHERE
{
<` + uri + `> <http://schema.org/encoding> ?encoding .
?encoding <http://schema.org/hasPart> ?hasPart .
?hasPart <http://schema.org/name> ?name .
?hasPart <http://schema.org/position> ?position .
?hasPart <http://schema.org/contentUrl> ?contentUrl .
?hasPart <http://schema.org/thumbnailUrl> ?thumbnailUrl .
} ORDER BY (xsd:integer(?position))`;
*/

var query = `PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
SELECT *
WHERE
{
<` + uri + `> <http://schema.org/sameAs> ?biostor_string .
BIND(IRI(?biostor_string) AS ?biostor) . 
?biostor rdf:type <http://schema.org/CreativeWork> .
?biostor <http://schema.org/hasPart> ?hasPart .
?hasPart <http://schema.org/name> ?name .
?hasPart <http://schema.org/position> ?position .
?hasPart <http://schema.org/contentUrl> ?contentUrl .
?hasPart <http://schema.org/thumbnailUrl> ?thumbnailUrl .
} ORDER BY (xsd:integer(?position))`;

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
			console.log(JSON.stringify(data, null, 2));  	
			
			if (data.results.bindings.length > 0) {
				
				var html = '';
				html += '<div class="image-viewer">';
				
				for (var i in data.results.bindings ) {
				
					html += '<div>';					
				
				   html += '<img class="lazy" src="' 						   
					+ data.results.bindings[i].contentUrl.value 						   
					+ '" />';
									
					html += '</div>';
					
					/* Spacer between pages, hack for now */
					html += '<div style="height:1em;"></div>';
							
					/*			
					html += '<div style="text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">';
					html += data.results.bindings[i].name.value;
					html += '</div>';
					*/					
				}
				
				html += '</div>';

				$('#' + element_id).html(html);					
				$(window).resize();
			}
		}
	);				
}    
