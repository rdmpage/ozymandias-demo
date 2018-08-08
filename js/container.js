function container(uri, element_id) {
	var query = `SELECT *
WHERE
{
<` + uri + `> <http://schema.org/isPartOf> ?container . 
?container <http://schema.org/name> ?name .
}`;

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
			//alert(JSON.stringify(data ));
			
			console.log(JSON.stringify(data, null, 2));  	
			
			var html = '';	
			
			for (var i in data.results.bindings) {
				html += '<a href="?uri=' + data.results.bindings[i].container.value + '">';
				html += data.results.bindings[i].name.value;
				html += '</a>';
			}
				
			$('#' + element_id).html(html);  							

		}
	);
			
}

//--------------------------------------------------------------------------------
// list of things in a container
function container_parts(uri, element_id) {
	
	var query = `SELECT *
WHERE
{
?work <http://schema.org/isPartOf> <` + uri + `> .
?work <http://schema.org/name> ?name .
OPTIONAL {
?work <http://schema.org/datePublished> ?datePublished . 

OPTIONAL {
?work <http://schema.org/identifier> ?identifier .
?identifier <http://schema.org/propertyID> "doi" .
?identifier <http://schema.org/value> ?doi .
}  


}
OPTIONAL {
?work <http://schema.org/image> ?image .
?image <http://schema.org/thumbnailUrl> ?thumbnailUrl .
}    
}`;

query = `SELECT *
WHERE
{
?work <http://schema.org/isPartOf> <` + uri + `> .
?work <http://schema.org/name> ?name .

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
`

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
			//alert(JSON.stringify(data ));
			
			console.log(JSON.stringify(data, null, 2));
			
			
			// group by decades
			var decades = {};
			
			for (var i in data.results.bindings) {
			
				var year = data.results.bindings[i].datePublished.value;
				//year = year.replace(/\b/g, '');
				//year = year.replace(/\w/g, '');
			
			   year = parseInt(year);
			   
			   var decade = Math.floor(year/10);
			   
			   if (!decades[decade]) {
			    decades[decade] = [];
			   }


			   if (!decades[decade][year]) {
			    decades[decade][year] = [];
			   }
			   
			   var work = {
			   	id: data.results.bindings[i].work.value,
			   	name: data.results.bindings[i].name.value,			   	
			   	year: year			   
			   };
			   
				if (data.results.bindings[i].doi) {
					work.doi = data.results.bindings[i].doi.value;
				}			   

				if (data.results.bindings[i].thumbnailUrl) {
					work.thumbnailUrl = data.results.bindings[i].thumbnailUrl.value;
				}			   
			   
			   decades[decade][year].push(work);
			}
			
			console.log(JSON.stringify(decades, null, 2));
			
			
			var html = '';
			
			for (var decade in decades) {
			
				html += '<h2>' + parseInt(decade) * 10 + '</h2>';
								
				for (var year in decades[decade]) {
					for (j in decades[decade][year]) {
					
						html += '<div class="list-item">';
				
						html += '<a href="?uri=' + decades[decade][year][j].id + '">';
				
						html += '<div class="list-item-thumbnail">';
				
						if (decades[decade][year][j].thumbnailUrl) {
							html += '<img src="' 
								+ decades[decade][year][j].thumbnailUrl 
								+ '" />';
						}
						html += '</div>';
				
						html += '<div class="list-item-body">';
						html += '   <div class="list-item-title">';
						html += decades[decade][year][j].name;
						html += '   </div>';
						html += '   <div class="list-item-description">';
						
						if (decades[decade][year][j].doi) {
							html += '<a class="external" href="' 
								+ 'https://doi.org/' + decades[decade][year][j].doi 
								+ '" target="_new">'
								+ 'https://doi.org/' + decades[decade][year][j].doi 
								+ '</a>';
						}
						
						html += '   </div>';
						html += '</div>';
				
						html += '</a>';
				
						html += '</div>';
					}
				}			
			}
			
			$('#' + element_id).html(html);
			


		}
	);
	

			
	
	
	
}		