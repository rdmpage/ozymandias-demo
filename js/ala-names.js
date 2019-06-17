// list of works about a taxon
function works_for_taxon_from_name(name, element_id) {
	
		
	var query = `SELECT *
WHERE
{
 ?taxon <http://schema.org/name> "` + name + `" .
 ?taxon  <http://taxref.mnhn.fr/lod/property/hasReferenceName>|<http://taxref.mnhn.fr/lod/property/hasSynonym> ?taxonName .
  ?taxonName <http://schema.org/name> ?tname .
  
OPTIONAL {
 ?taxonName <http://rs.tdwg.org/dwc/terms/taxonomicStatus> ?taxonomicStatus .
}
OPTIONAL {
 ?taxonName <http://rs.tdwg.org/dwc/terms/nomenclaturalStatus> ?nomenclaturalStatus .
}  

OPTIONAL {
?taxonName <http://rs.tdwg.org/ontology/voc/TaxonName#year> ?year .
}

OPTIONAL {
?taxonName <http://rs.tdwg.org/dwc/terms/taxonRemarks> ?remarks .
}
  
  OPTIONAL {
?taxonName <http://rs.tdwg.org/ontology/voc/Common#publishedInCitation> ?work .

?work <http://schema.org/name> ?name .
OPTIONAL {
?work <http://schema.org/datePublished> ?datePublished . 

OPTIONAL {
?work <http://schema.org/identifier> ?identifier .
?identifier <http://schema.org/propertyID> "doi" .
?identifier <http://schema.org/value> ?doi .
}    

OPTIONAL {
?work <http://schema.org/identifier> ?identifierb .
?identifierb <http://schema.org/propertyID> "biostor" .
?identifierb <http://schema.org/value> ?biostor .
} 

OPTIONAL {
?work <http://schema.org/identifier> ?identifierh .
?identifierh <http://schema.org/propertyID> "handle" .
?identifierh <http://schema.org/value> ?handle .
} 


}
  }
}
ORDER BY (?datePublished)`;

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
			//alert(JSON.stringify(data ));
			
			console.log(JSON.stringify(data, null, 2));
						
			if (data.results.bindings.length > 0) {
				var html = '';
			
				for (var i in data.results.bindings) {
					html += '<div style="font-weight:lighter;color:rgb(45,45,45);border-top:2px solid rgb(135,135,135);padding:5px;">';
						
					html += '<div style="padding:5px">';
					/*	
					html += '<li class="';
				
					var css_class = 'unknown';
					if (data.results.bindings[i].taxonomicStatus) {
						css_class = data.results.bindings[i].taxonomicStatus.value; 
					}				
					if (data.results.bindings[i].nomenclaturalStatus) {
						css_class += ' ' + data.results.bindings[i].nomenclaturalStatus.value; 
					}								
					html += css_class + '">';	
					*/
								
					html += data.results.bindings[i].tname.value;
				
					
					if (data.results.bindings[i].taxonomicStatus) {
						html += ' [' + data.results.bindings[i].taxonomicStatus.value + '] '; 
					}
				
					/*
					if (data.results.bindings[i].nomenclaturalStatus) {
						html += ' [' + data.results.bindings[i].nomenclaturalStatus.value + '] '; 
					}
					*/
					
				
					html += '</div>';
				
					// html += '<li class="guid">' + data.results.bindings[i].taxonName.value.replace(/urn:uuid:/, '') + '</li>';
			
					if (data.results.bindings[i].work) {
					  html += '<div style="padding:5px">';
				
						/*
					  html += '<a href="?uri='
						+ data.results.bindings[i].work.value 
						+ '">';
						*/

					  if (data.results.bindings[i].name) {
						html += data.results.bindings[i].name.value 
					  }

					 // html += '</a>';
					  html += '</div>';	
					  
					  if (data.results.bindings[i].doi) {
					  	html += '<div style="padding:5px">';
					  	html += '<a href="https://doi.org/' + data.results.bindings[i].doi.value + '" target="_new">';
						html += 'https://doi.org/' + data.results.bindings[i].doi.value; 
						html += '</a>';
						html += '</div>';
					  }

					  if (data.results.bindings[i].biostor) {
					  	html += '<div style="padding:5px">';
					  	html += '<a href="https://biostor.org/reference/' + data.results.bindings[i].biostor.value + '" target="_new">';
						html += 'https://biostor.org/reference/' + data.results.bindings[i].biostor.value; 
						html += '</a>';
						html += '</div>';
					  }

					  if (data.results.bindings[i].handle) {
					  	html += '<div style="padding:5px">';
					  	html += '<a href="https://hdl.handle.net/' + data.results.bindings[i].handle.value + '" target="_new">';
						html += 'https://hdl.handle.net/' + data.results.bindings[i].handle.value; 
						html += '</a>';
						html += '</div>';
					  }

		      
					}
				
					/*
					if (data.results.bindings[i].remarks) {
					  html += '<li class="remarks">';
					  html += data.results.bindings[i].remarks.value 
					  html += '</li>';			      
					}
					*/
				
					html += '</div>'; 
				}
			
				$('#' + element_id).html(html);
			}			
		}
	);	
}	