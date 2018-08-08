//--------------------------------------------------------------------------------
// list of taxa in a work
function taxa_in_work(uri, element_id) {

	var query = `PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
SELECT *
WHERE
{
?taxonName <http://rs.tdwg.org/ontology/voc/Common#publishedInCitation> <` + uri + `> .
?taxon <http://taxref.mnhn.fr/lod/property/hasReferenceName>|<http://taxref.mnhn.fr/lod/property/hasSynonym> ?taxonName .
?taxon <http://schema.org/name> ?name .

OPTIONAL {
?taxon <http://schema.org/image> ?image .
?image <http://schema.org/thumbnailUrl> ?thumbnailUrl .


# name string to search on
#?taxonName <http://rs.tdwg.org/ontology/voc/TaxonName#nameComplete> ?nameComplete .


# figures in work (e.g., Zenodo figures)
#<` + uri + `> <http://schema.org/sameAs> ?doi . 
#?doi <http://schema.org/hasPart> ?part .
#?part rdf:type <http://schema.org/ImageObject> .
#?part <http://schema.org/thumbnailUrl> ?thumbnailUrl .
#?part <http://schema.org/description> ?description .
#FILTER regex(?description, ?nameComplete)  


}
}`;

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
			//alert(JSON.stringify(data ));
			
			console.log(JSON.stringify(data, null, 2));			
			
			if (data.results.bindings.length > 0) {
				var html = '<h4>Taxa in this work.</h4>';
				html += '<div class="taxa-grid clearfix">';
			
				for (var i in data.results.bindings) {
					html += '<div class="taxa-thumbnail">';
				
					html += '<a href="?uri='
						+ data.results.bindings[i].taxon.value 
						+ '">'
						+ '<span>' + data.results.bindings[i].name.value + '</span>';

					if (data.results.bindings[i].thumbnailUrl) {
						html += '<img src="' 
							+ 'http://exeg5le.cloudimg.io/crop/100x100/n/'
							+ data.results.bindings[i].thumbnailUrl.value 
							+ '" />';
					} 
					html += '</a>';  						
					html += '</div>'; 
				}
				html += '</div>';
			
				$('#' + element_id).html(html);
						
			}

		}
	);
	

			
			

}