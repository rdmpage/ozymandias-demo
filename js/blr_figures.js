// Figures from BLR
function figures(uri, element_id) {

	$('#' + element_id).html();
	
	var query = `PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
SELECT *
WHERE
{
<` + uri + `> <http://schema.org/sameAs> ?doistring .
# schema.org valdator requires sameAs to be literal not URI, so we need to cast to URI here :(
BIND(IRI(?doistring) AS ?doi) . 
{?doi <http://schema.org/hasPart> ?part } UNION { ?part <http://schema.org/isPartOf> ?doi } .
?part rdf:type <http://schema.org/ImageObject> .
?part <http://schema.org/thumbnailUrl> ?thumbnailUrl .
?part <http://schema.org/description> ?description .
}
ORDER BY (?part)`;

query = `PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
SELECT *
WHERE
{
<` + uri + `> <http://schema.org/identifier> ?identifier .
?identifier <http://schema.org/propertyID> "zenodo" .
?identifier <http://schema.org/value> ?identifier_value .

BIND(IRI(CONCAT("https://zenodo.org/record/", STR(?identifier_value))) AS ?zenodo) . 

{?zenodo <http://schema.org/hasPart> ?part } UNION { ?part <http://schema.org/isPartOf> ?zenodo } .
?part rdf:type <http://schema.org/ImageObject> .
?part <http://schema.org/thumbnailUrl> ?thumbnailUrl .
?part <http://schema.org/description> ?description .
  
}
ORDER BY (?part)`;

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
			console.log(JSON.stringify(data, null, 2));
			
			if (data.results.bindings.length > 0) {
			
				var html = '<div class="explain">Figures from Biodiversity Literature Repository.</div>';
				
				html += '<section>';
				
				for (var i in data.results.bindings ) {
				   html += '<div class="figure">';
				   
				   html += '<a href="?uri=' + data.results.bindings[i].part.value + '">';

				   html += '<img class="figure" src="' 						   
					+ 'http://exeg5le.cloudimg.io/height/100/n/' 
					+ data.results.bindings[i].thumbnailUrl.value 						   
					+ '" />';
					
					html += '</a>';
					
					html += '</div>';
				}
				
			
				
				html += '</section>';
			
				$('#' + element_id).html(html);
			}
			

		}
	);
	


}	


// Work that contains this figure
function figure_is_part_of(uri, element_id) {

	$('#' + element_id).html();
	
	var query = `PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
SELECT * 
WHERE 
{
<` + uri + `> <http://schema.org/isPartOf> ?zenodo .
  
BIND(REPLACE(STR(?zenodo), "https://zenodo.org/record/", "", "i") AS ?identifier_value).
  
?identifier <http://schema.org/value> ?identifier_value .  
?identifier <http://schema.org/propertyID> "zenodo" .
?work <http://schema.org/identifier> ?identifier .
?work <http://schema.org/name> ?name .
}`;

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
			console.log(JSON.stringify(data, null, 2));
			
			if (data.results.bindings.length > 0) {
				var html = '<h4>Part of</h4>';	
			
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


