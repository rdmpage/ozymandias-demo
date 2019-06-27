// images child taxa
function child_taxa_images_ala(name, element_id) {
	
		
	var query = `PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX tc: <http://rs.tdwg.org/ontology/voc/TaxonConcept#>
SELECT ?child ?child_name ?thumbnailUrl WHERE
{   
  #VALUES ?root { <https://bie.ala.org.au/species/urn:lsid:biodiversity.org.au:afd.taxon:83fd3bf1-6a86-4727-8387-6804ea4c64db> }
  
  ?root <http://schema.org/name> "` + name + `" .

  ?child rdfs:subClassOf ?root .

  ?child tc:nameString ?child_name .
  OPTIONAL {
  
  # child has image
  ?child <http://schema.org/image> ?image .
  ?image <http://schema.org/thumbnailUrl> ?thumbnailUrl .

   
  }
  }
  ORDER BY ?child_name`;
  
  //alert(query);

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
			//alert(JSON.stringify(data ));
			
			console.log(JSON.stringify(data, null, 2));
						
			if (data.results.bindings.length > 0) {
				var html = '';
			
				for (var i in data.results.bindings) {									
					
						var html = '';
					
						html += '<div class="taxa-grid clearfix">';
					
						for (var i in data.results.bindings) {  					
							html += '<div class="taxa-thumbnail">';
						
							html += '<a href="./?uri='
								+ data.results.bindings[i].child.value 
								+ '">'
								+ '<span>' + data.results.bindings[i].child_name.value + '</span>';

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

				}
							
				$('#' + element_id).html(html);
				
			}			
		}
	);	
}	

// images child taxa
function child_taxa_images_blr(name, element_id) {
	
		
	var query = `PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX tc: <http://rs.tdwg.org/ontology/voc/TaxonConcept#>
SELECT ?child ?child_name ?thumbnailUrl WHERE
{   
  #VALUES ?root { <https://bie.ala.org.au/species/urn:lsid:biodiversity.org.au:afd.taxon:83fd3bf1-6a86-4727-8387-6804ea4c64db> }
  
  ?root <http://schema.org/name> "` + name + `" .

  ?child rdfs:subClassOf ?root .

  ?child tc:nameString ?child_name .
  OPTIONAL {
    
  # get work(s) for this taxon
  ?child  <http://taxref.mnhn.fr/lod/property/hasReferenceName>|<http://taxref.mnhn.fr/lod/property/hasSynonym> ?taxonName .
  ?taxonName <http://schema.org/name> ?tname .
 
  ?taxonName <http://rs.tdwg.org/ontology/voc/Common#publishedInCitation> ?work .
    
  # get Zenodo figures (assume isPartOf works for now)
  ?work <http://schema.org/identifier> ?identifier .
  
  
  #?identifier <http://schema.org/propertyID> "doi" .
  #?identifier <http://schema.org/value> ?identifier_value .
 
  #BIND(IRI(CONCAT("https://doi.org/", STR(?identifier_value))) AS ?doi) . 
    
   # a figure
  #?part <http://schema.org/isPartOf> ?doi  .
  
  {
   ?identifier <http://schema.org/propertyID> "doi" .
   ?identifier <http://schema.org/value> ?identifier_value .
   BIND(IRI(CONCAT("https://doi.org/", STR(?identifier_value))) AS ?doi) .
   ?part <http://schema.org/isPartOf> ?doi  .
  }
  UNION {
   ?identifier <http://schema.org/propertyID> "zenodo" .
   ?identifier <http://schema.org/value> ?identifier_value .
   BIND(IRI(CONCAT("https://zenodo.org/record/", STR(?identifier_value))) AS ?zenodo) .
   ?part <http://schema.org/isPartOf> ?zenodo  .
  }  
  
  
  ?part rdf:type <http://schema.org/ImageObject> .
  ?part <http://schema.org/thumbnailUrl> ?thumbnailUrl .
  ?part <http://schema.org/description> ?description .

  # filter those that have the name
  FILTER regex(?description, ?child_name)   
  }
}
ORDER BY ?child_name`;

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
			//alert(JSON.stringify(data ));
			
			console.log(JSON.stringify(data, null, 2));
						
			if (data.results.bindings.length > 0) {
				var html = '';
			
				for (var i in data.results.bindings) {									
					
						var html = '';
					
						html += '<div class="taxa-grid clearfix">';
					
						for (var i in data.results.bindings) {  					
							html += '<div class="taxa-thumbnail">';
						
							html += '<a href="./uri/'
								+ data.results.bindings[i].child.value 
								+ '">'
								+ '<span>' + data.results.bindings[i].child_name.value + '</span>';

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

				}
							
				$('#' + element_id).html(html);
				
			}			
		}
	);	
}	