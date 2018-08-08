// list of works about a taxon
function works_for_taxon(uri, element_id) {
	
		
	var query = `SELECT *
WHERE
{
<` + uri + `>  <http://taxref.mnhn.fr/lod/property/hasReferenceName>|<http://taxref.mnhn.fr/lod/property/hasSynonym> ?taxonName .
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
				var html = '<h4>Names for this taxon.</h4>';
			
				for (var i in data.results.bindings) {
					html += '<ul class="name">';
							
					html += '<li class="';
				
					var css_class = 'unknown';
					if (data.results.bindings[i].taxonomicStatus) {
						css_class = data.results.bindings[i].taxonomicStatus.value; 
					}				
					if (data.results.bindings[i].nomenclaturalStatus) {
						css_class += ' ' + data.results.bindings[i].nomenclaturalStatus.value; 
					}								
					html += css_class + '">';				
					html += data.results.bindings[i].tname.value + ' ';
				
					/*
					if (data.results.bindings[i].taxonomicStatus) {
						html += ' [' + data.results.bindings[i].taxonomicStatus.value + '] '; 
					}
				
					if (data.results.bindings[i].nomenclaturalStatus) {
						html += ' [' + data.results.bindings[i].nomenclaturalStatus.value + '] '; 
					}
					*/
				
					html += '</li>';
				
					html += '<ul class="name-details">';
				
					// html += '<li class="guid">' + data.results.bindings[i].taxonName.value.replace(/urn:uuid:/, '') + '</li>';
			
					if (data.results.bindings[i].work) {
					  html += '<li class="work">';
				
					  html += '<a href="?uri='
						+ data.results.bindings[i].work.value 
						+ '">';

					  if (data.results.bindings[i].name) {
						html += data.results.bindings[i].name.value 
					  }

					  html += '</a>';
					  html += '</li>';			      
					}
				
					if (data.results.bindings[i].remarks) {
					  html += '<li class="remarks">';
					  html += data.results.bindings[i].remarks.value 
					  html += '</li>';			      
					}
				
				
					html += '</ul>'; // name-details
				
					html += '</ul>'; // name
				}
			
				$('#' + element_id).html(html);
			}			
		}
	);	
}	


//--------------------------------------------------------------------------------
// path to root of tree
function taxon_lineage(uri, element_id) {

	//$('#').html('xx');
	
	var query = 
`PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX tc: <http://rs.tdwg.org/ontology/voc/TaxonConcept#>

SELECT ?node_name ?node ?parent ?parent_name WHERE
{   
VALUES ?root { <` + uri + `> }
?root rdfs:subClassOf+ ?node .
?node tc:nameString ?node_name .
  OPTIONAL { 
    ?node rdfs:subClassOf ?parent .
    ?parent tc:nameString ?parent_name
  }
}`;

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
			//alert(JSON.stringify(data ));
			
			console.log(JSON.stringify(data, null, 2));
			
			// Convert to linked list ordered by subClassOf
			var head = null;
			var nodes = {};

			for (var i in data.results.bindings) {
			   var node = null;
			   if (!nodes[data.results.bindings[i].node_name.value]) {
				 node = {};
				 node.name = data.results.bindings[i].node_name.value;
				 node.uri = data.results.bindings[i].node.value;
				 node.next = null;
	 
				 nodes[data.results.bindings[i].node_name.value] = node;
			   } 
			   node = nodes[data.results.bindings[i].node_name.value];


			   var parent = null;
   
			   if (data.results.bindings[i].parent_name) {
				   if (!nodes[data.results.bindings[i].parent_name.value]) {
					 parent = {};
					 parent.name = data.results.bindings[i].parent_name.value;
					 parent.uri = data.results.bindings[i].parent.value;
					 parent.next = null;
	 
					 nodes[data.results.bindings[i].parent_name.value] = parent;
				   } 
				   parent = nodes[data.results.bindings[i].parent_name.value];
			   }

			   if (parent) {
				  parent.next = node;
			   } else {
				 head = node;
			   }

			}			
						
			var html = '';
			
			html += '<ol style="background-size: contain;background-position: center; background-image: url(images/triangle.svg);background-repeat: no-repeat;list-style-type: none;margin:0px;padding:0px;">';
			
			while (head) {
				//console.log(head.name);
				
				html += '<li style="border-bottom:2px solid white;">' 
				html += '<a style="text-decoration: none;" href="?uri=' + head.uri + '">';
				html += head.name;
				html += '</a>';
				html += '</li>';
				
				head = head.next;
				
			}			
			html += '</ol>';
			
			$('#' + element_id).html(html);			

		}
	);
	
}		
		//--------------------------------------------------------------------------------
		// display children of uri in main window
		function taxon_children(uri, element_id) {
		
			
			var query = `PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX tc: <http://rs.tdwg.org/ontology/voc/TaxonConcept#>
SELECT ?child ?child_name ?thumbnailUrl WHERE
{   
  VALUES ?root { <` + uri + `> }

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
  					
  					//$('#main').html('<div style="background:rgb(242,242,242);white-space:pre;font-size:10px;padding:10px;">' + JSON.stringify(data, null, 2) + '</style>');
  					
  					if (data.results.bindings.length > 0) {
					
						var html = '<h4>Children</h4>';
					
						html += '<div class="taxa-grid clearfix">';
					
						for (var i in data.results.bindings) {  					
							html += '<div class="taxa-thumbnail">';
						
							html += '<a href="?uri='
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
					
						$('#' + element_id).html(html);
					}  					
  
  				}
  			);
  			
  		
		
		}	


//----------------------------------------------------------------------------------------
		// get thumbnail for taxon
		function taxon_thumbnail(uri, element_id) {
		
			
			var query = `
SELECT ?thumbnailUrl WHERE
{   
  <` + uri + `>  <http://schema.org/image> ?image .
  ?image <http://schema.org/thumbnailUrl> ?thumbnailUrl .
}`;

//alert(query);
		
			$.getJSON('query.php?query=' + encodeURIComponent(query)
					+ '&callback=?',
				function(data){
  					//alert(JSON.stringify(data ));
  					
  					console.log(JSON.stringify(data, null, 2));
  					
  					
  					if (data.results.bindings.length > 0) {
  						$('#' + element_id).attr('src', 'http://exeg5le.cloudimg.io/crop/100x100/n/' + data.results.bindings[0].thumbnailUrl.value);
  					}
   					
  
  				}
  			);
  			
  		
		
		}	

//----------------------------------------------------------------------------------------

// these queries need to be rethoght, taxa includes all node son subtree
// names records date of original name, not the date of the act (i.e., when combination was published)
// that needs to use work date, but this query seems slow...

// taxonomic progress over time
function taxon_timeline2(uri, element_id, raw_data) {

// accepted taxa

	var query = `PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX tc: <http://rs.tdwg.org/ontology/voc/TaxonConcept#>

SELECT ?year (COUNT(?taxonName) AS ?count) WHERE
{   
  VALUES ?root { <` + uri + `> }
  ?child rdfs:subClassOf+ ?root .
  ?child tc:nameString ?child_name .
  
  ?child  <http://taxref.mnhn.fr/lod/property/hasReferenceName>|<http://taxref.mnhn.fr/lod/property/hasSynonym> ?taxonName .
  ?taxonName <http://schema.org/name> ?tname .
  #?taxonName <http://rs.tdwg.org/ontology/voc/Common#publishedInCitation> ?work .
  
  ?taxonName <http://rs.tdwg.org/dwc/terms/taxonomicStatus> ?status .
  #?work <http://schema.org/datePublished> ?date .
  
  ?taxonName <http://rs.tdwg.org/ontology/voc/TaxonName#year> ?year . 
 
  FILTER (?status = 'accepted')
} 
GROUP BY ?year 
ORDER BY (?year)`;

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
			//alert(JSON.stringify(data ));
			
			var cummulative = {};
			
			var sum = 0;
			
			for (var i in data.results.bindings) {
				sum += parseInt(data.results.bindings[i].count.value);
				cummulative[data.results.bindings[i].year.value] = sum;
			}
			
			var current_sum = 0;
			
			for (var year in raw_data) {
			  if (cummulative[year]) {
			     current_sum = parseInt(cummulative[year]);
			  }
			  raw_data[year][1] = current_sum;
			}
			
			var table = [];
			
			table.push(['Year', 'Names', 'Taxa']);
			
			for (var year in raw_data) {
			  var row = [
			  	new Date(year, 1, 1),
			  	raw_data[year][0],
			  	raw_data[year][1]
			  ];
			  
			  if (cummulative[year]) {
			     current_sum = parseInt(cummulative[year]);
			  }
			  raw_data[year][1] = current_sum;
			}
			
			var html = '<pre>' + JSON.stringify(raw_data, null, 2) + '</pre>';
			
			var chart_data = { labels: [], datasets: [] };
			
			chart_data.datasets[0] = { label: 'names', data: [], backgroundColor: "#3e95cd"};
			chart_data.datasets[1] = { label: 'taxa', data: [], type: 'line', fill: false, borderColor: "#8e5ea2"};
			
			for (var year in raw_data) {
			  chart_data.labels.push(year);
			  
			  chart_data.datasets[0].data.push(raw_data[year][0]);
			  chart_data.datasets[1].data.push(raw_data[year][1]);
			  
			}
			html = '<pre>' + JSON.stringify(chart_data, null, 2) + '</pre>';

			
			//$('#' + element_id).html(html);
			
			
var ctx = document.getElementById("chart");
var myChart = new Chart(ctx, {
    type: 'bar',
    data: chart_data,
    options: {}
});			


		}
	);




}



// taxonomic progress over time
function taxon_timeline(uri, element_id) {
	
	var raw_data = {};
	
	
	// get numbers of names over time 
		
	var query = `PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX tc: <http://rs.tdwg.org/ontology/voc/TaxonConcept#>

SELECT ?year  (COUNT(?taxonName) AS ?count)  WHERE
{   
  VALUES ?root { <` + uri + `> }
  ?child rdfs:subClassOf+ ?root .
  ?child tc:nameString ?child_name .
  
  ?child  <http://taxref.mnhn.fr/lod/property/hasReferenceName>|<http://taxref.mnhn.fr/lod/property/hasSynonym> ?taxonName .
  ?taxonName <http://schema.org/name> ?tname .
  ?taxonName <http://rs.tdwg.org/ontology/voc/Common#publishedInCitation> ?work .
  
  ?taxonName <http://rs.tdwg.org/ontology/voc/TaxonName#year> ?year . 
  
} 
GROUP BY ?year 
ORDER BY (?year)`;

	$.getJSON('query.php?query=' + encodeURIComponent(query)
			+ '&callback=?',
		function(data){
			//alert(JSON.stringify(data ));
			
			console.log(JSON.stringify(data, null, 2));
			
			for (var i in data.results.bindings) {
				raw_data[data.results.bindings[i].year.value] = [];
				raw_data[data.results.bindings[i].year.value][0] = parseInt(data.results.bindings[i].count.value);
			}
			
			taxon_timeline2(uri, element_id, raw_data);


		}
	);
	

			
	
	
	
}	
