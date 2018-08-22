
// https://stackoverflow.com/a/28708770/9684

function numberFormat(_number, _sep) {
    _number = typeof _number != "undefined" && _number > 0 ? _number : "";
    _number = _number.replace(new RegExp("^(\\d{" + (_number.length%3? _number.length%3:0) + "})(\\d{3})", "g"), "$1 $2").replace(/(\d{3})+?/gi, "$1 ").trim();
    if(typeof _sep != "undefined" && _sep != " ") {
        _number = _number.replace(/\s/g, _sep);
    }
    return _number;
}

//--------------------------------------------------------------------------------
// Count types
function count_types(element_id) {

	var query = `PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
select (COUNT(?s) AS ?c) ?type
WHERE
{
 ?s  rdf:type ?type .
}
GROUP BY ?type`;

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){

			console.log(JSON.stringify(data, null, 2));			
			
			if (data.results.bindings.length > 0) {
				var parts = [];			
				for (var i in data.results.bindings) {
				
					switch (data.results.bindings[i].type.value) {

						case 'http://rs.tdwg.org/ontology/voc/TaxonConcept#TaxonConcept':
							parts.push(numberFormat(data.results.bindings[i].c.value, ',') + ' taxa');
							break;

						case 'http://rs.tdwg.org/ontology/voc/TaxonName#TaxonName':
							parts.push(numberFormat(data.results.bindings[i].c.value, ',') + ' taxonomic names');
							break;

						case 'http://schema.org/ScholarlyArticle':
							parts.push(numberFormat(data.results.bindings[i].c.value, ',') + ' articles');
							break;

						case 'http://schema.org/ScholarlyArticle':
							parts.push(numberFormat(data.results.bindings[i].c.value, ',') + ' articles');
							break;

						case 'http://schema.org/Periodical':
							parts.push(numberFormat(data.results.bindings[i].c.value, ',') + ' journals');
							break;

						case 'http://schema.org/Person':
							parts.push(numberFormat(data.results.bindings[i].c.value, ',') + ' people');
							break;
					
						default:
							break;
					}
				}
				
				var html = 'The knowledge graph contains ' + parts.join(', ');
				$('#' + element_id).html(html);
						
			}

		}
	);
	

			
			

}