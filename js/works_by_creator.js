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
?work <http://schema.org/url> ?url .
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

				if (data.results.bindings[i].url) {
					work.url = data.results.bindings[i].url.value;
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
						} else {
							if (decades[decade][year][j].url) {
								html += '<a class="external" href="' 
									+ decades[decade][year][j].url 
									+ '" target="_new">'
									+ decades[decade][year][j].url 
									+ '</a>';
							}
						}
						
						html += '   </div>';
						html += '</div>';
				
						html += '</a>';
				
						html += '</div>';
					}
				}			
			}			
			
			/*
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
			*/
			}
			$('#' + element_id).html(html);
		}
	);
}		