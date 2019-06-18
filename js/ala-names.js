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
				
				var unpaywall_counter = 1;
			
				for (var i in data.results.bindings) {									
					
					html += '<table width="100%" cellspacing="0" cellpadding="4">';
					
					html += '<tbody style="font-weight:lighter;color:rgb(45,45,45);">';
					
					html += '<tr>';
					html += '<th align="left" style="border-bottom:2px solid rgb(135,135,135);" width="50%"><br>' + data.results.bindings[i].taxonomicStatus.value + '</th>';
					html += '<th align="left" style="border-bottom:2px solid rgb(135,135,135);"><br>Source</th>';
					html += '</tr>';
						
							
					html += '<tr>';	
					html += '<td>';
					//html += '<a href="' + data.results.bindings[i].taxon.value + '">';
					html += data.results.bindings[i].tname.value;
					//html += '</a>';
					html += '</td>';					
					html += '<td><ul><li><a href="https://collections.ala.org.au/public/show/dr2699">AFD</a></li></ul></td>';					
					html += '</tr>';
				
					if (data.results.bindings[i].work) {

					  if (data.results.bindings[i].name) {					  
						html += '<tr>';	
						html += '<td>';
						html += 'Published in: ';
						//html += '<a href="' + data.results.bindings[i].work.value + '">';
						html += data.results.bindings[i].name.value;
						//html += '</a>';
						html += '</td>';
						//html += '<td><ul><li><a href="https://collections.ala.org.au/public/show/dr2699">AFD</a></li></ul></td>';
						html += '<td></td>';
						html += '</tr>';
					  }
					  
					  if (data.results.bindings[i].doi) {
						html += '<tr>';	
						html += '<td>';
					  	html += '<a href="https://doi.org/' + data.results.bindings[i].doi.value + '" target="_new">';
						html += 'https://doi.org/' + data.results.bindings[i].doi.value; 
						html += '</a>';
						
						html += ' <span id="unpaywall' + unpaywall_counter++ + '" class="unpaywall" data="' + data.results.bindings[i].doi.value + '"></span>';
						
						
						html += '</td>';
						html += '<td><ul><li><a href="https://ozymandias-demo.herokuapp.com">Ozymandias</a></li></ul></td>';
						html += '</tr>';
					  }

					  if (data.results.bindings[i].biostor) {
						html += '<tr>';	
						html += '<td>';
					  	html += '<a href="https://biostor.org/reference/' + data.results.bindings[i].biostor.value + '" target="_new">';
						html += 'https://biostor.org/reference/' + data.results.bindings[i].biostor.value; 
						html += '</a>';
						html += '</td>';
						html += '<td><ul><li><a href="https://ozymandias-demo.herokuapp.com">Ozymandias</a></li></ul></td>';
						html += '</tr>';
					  }

					  if (data.results.bindings[i].handle) {
						html += '<tr>';	
						html += '<td>';
					  	html += '<a href="https://hdl.handle.net/' + data.results.bindings[i].handle.value + '" target="_new">';
						html += 'https://hdl.handle.net/' + data.results.bindings[i].handle.value; 
						html += '</a>';
						html += '</td>';
						html += '<td><ul><li><a href="https://ozymandias-demo.herokuapp.com">Ozymandias</a></li></ul></td>';
						html += '</tr>';
					  }
		      
					}
					html += '</tbody>'; 
					html += '</table>'; 
				}
				
			
				$('#' + element_id).html(html);
				
				// code to call unpaywall
				$( "span" ).each(function( index ) {
  					console.log( index + ": " + $( this ).text() );
  					
  					var id = $(this).attr('id');
  					var doi = $(this).attr('data');
  					
  					var url = 'https://api.oadoi.org/v2/' + encodeURIComponent(doi) 
  						+ '?email=unpaywall@impactstory.org' ;
  					
  					$.getJSON(url,
						function(data){
							//console.log(data);
							if (data.is_oa) {
								$('#' + id).html('<a style="background:orange;color:white;" href="' + data.oa_locations[0].url_for_pdf + '"> Read for free </a>');
							}
						}
					);

  					
				});
				
			}			
		}
	);	
}	