//----------------------------------------------------------------------------------------
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

//----------------------------------------------------------------------------------------
function creator_cocreators(uri, element_id) {
	var query = `SELECT ?coauthor ?coauthor_name (COUNT(?coauthor) AS ?count) 
WHERE
{
  VALUES ?author { <` + uri + `> }
  
  ?author <http://schema.org/name> ?author_name .
  ?author_role <http://schema.org/creator> ?author .
  ?work <http://schema.org/creator> ?author_role . 

  ?coauthor_role <http://schema.org/creator> ?coauthor . 
  ?work <http://schema.org/creator> ?coauthor_role .
  ?coauthor <http://schema.org/name> ?coauthor_name .
  
  FILTER (?coauthor != ?author)
}
GROUP BY ?coauthor ?coauthor_name
ORDER BY DESC(?count)
LIMIT 5 `;

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
			//alert(JSON.stringify(data ));
			
			console.log(JSON.stringify(data, null, 2));  
			
			if (data.results.bindings.length > 0) {
			
				var html = '<h4>Top five coauthors.</h4>';	
				html += '<ul>';	
			
				for (var i in data.results.bindings) {

					html += '<li>';
				
					html += '<a href="?uri='
						+ data.results.bindings[i].coauthor.value.replace(/#/, '%23')
						+ '">';
				
					html += data.results.bindings[i].coauthor_name.value;
				
					html += '</a>';
					
					html += '<span class="badge">' + data.results.bindings[i].count.value + '</span>';					
				
					html += '</li>';
				} 
				html += '</ul>'
				$('#' + element_id).html(html);  							
			}
		}
	);
			
}

//----------------------------------------------------------------------------------------
function creator_containers(uri, element_id) {
	var query = `prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
SELECT ?container ?container_name (COUNT(?container) AS ?count) 
WHERE
{
  VALUES ?author { <` + uri + `> }
  
  ?author <http://schema.org/name> ?author_name .
  ?author_role <http://schema.org/creator> ?author .
  ?work <http://schema.org/creator> ?author_role . 

  ?work <http://schema.org/isPartOf> ?container .
  ?container rdf:type <http://schema.org/Periodical> .
  ?container <http://schema.org/name> ?container_name .
  
 
}
GROUP BY ?container ?container_name
ORDER BY DESC(?count)
LIMIT 10`;

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
			//alert(JSON.stringify(data ));
			
			console.log(JSON.stringify(data, null, 2));  
			
			if (data.results.bindings.length > 0) {
			
				var html = '<h4>Top ten journals.</h4>';	
				html += '<ul>';	
			
				for (var i in data.results.bindings) {

					html += '<li>';
				
					html += '<a href="?uri='
						+ data.results.bindings[i].container.value.replace(/#/, '%23')
						+ '">';
				
					html += data.results.bindings[i].container_name.value;
				
					html += '</a>';
					
					html += '<span class="badge">' + data.results.bindings[i].count.value + '</span>';					
				
					html += '</li>';
				} 
				html += '</ul>'
				$('#' + element_id).html(html);  							
			}
		}
	);
			
}

//----------------------------------------------------------------------------------------
function creator_taxa(uri, element_id) {
	var query = `PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX tc: <http://rs.tdwg.org/ontology/voc/TaxonConcept#>

SELECT ?node ?node_name ?parent ?parent_name (COUNT(?node) AS ?count)
WHERE
{
  VALUES ?author { <` + uri + `> }
  
  ?author <http://schema.org/name> ?author_name .
  ?author_role <http://schema.org/creator> ?author .
  ?work <http://schema.org/creator> ?author_role . 
  
  ?taxonName <http://rs.tdwg.org/ontology/voc/Common#publishedInCitation> ?work .
  
  ?taxon <http://taxref.mnhn.fr/lod/property/hasReferenceName>|<http://taxref.mnhn.fr/lod/property/hasSynonym> ?taxonName .
  ?taxon <http://schema.org/name> ?tname .
  
  ?taxon rdfs:subClassOf+ ?node .
  ?node tc:nameString ?node_name .
  OPTIONAL { 
    ?node rdfs:subClassOf ?parent .
    ?parent tc:nameString ?parent_name
  }  
}
GROUP BY ?node ?node_name ?parent ?parent_name
ORDER BY DESC(?count)
LIMIT 20`;

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
			//alert(JSON.stringify(data ));
			
			console.log(JSON.stringify(data, null, 2));  
			
			if (data.results.bindings.length > 0) {
			
				var html = '<h4>Top 20 taxa.</h4>';
				
				var node_identifier = {};
			
				var ancestor_function = {};
				for (var i in data.results.bindings) {
					var node = data.results.bindings[i].node_name.value;
					
					node_identifier[node] = data.results.bindings[i].node.value;
					
					var ancestor = null;
					if (data.results.bindings[i].parent_name) {
						ancestor = data.results.bindings[i].parent_name.value;
						
						node_identifier[ancestor] = data.results.bindings[i].parent.value;
					}
					ancestor_function[node] = ancestor;
				} 
				
				/*
				var html = '<h4>Taxa studied.</h4>';	
				html += '<ul>';	
			
				for (var i in data.results.bindings) {

					html += '<li>';
								
					html += data.results.bindings[i].node_name.value;
					
					if (data.results.bindings[i].parent_name) {
						html += '-' + data.results.bindings[i].parent_name.value;
					}
									
					html += '<span class="badge">' + data.results.bindings[i].count.value + '</span>';					
				
					html += '</li>';
				} 
				html += '</ul>'*/
				
				//var html = '<pre>' + JSON.stringify(ancestor_function, null, 2) + '</pre>';
				
				var t = new Tree();

				for (var i in ancestor_function) {
					var node_name  = i;
					var parent_name = ancestor_function[i];
					t.AddNode(parent_name, node_name);
				}
				
				html += t.WriteHtml(node_identifier);
				
				//html += '<pre>' + JSON.stringify(node_identifier, null, 2) + '</pre>';
				
				$('#' + element_id).html(html);  							
			}
		}
	);
			
}

