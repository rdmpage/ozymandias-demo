<?php

$query = '';

// Show search
if (isset($_GET['q']))
{	
	$query = $_GET['q'];
}

?>
<html>
<head>

<style>
body {
	padding-left:40px;
	padding-right:40px;
	font-family: sans-serif;
}
</style>		

	<meta charset="utf-8">
    <script src="external/jquery.js"></script>
 
<script>

       //--------------------------------------------------------------------------------
		function checkall() {
			
			var check = true;
			
			if ($('#checkAll').val() == 'Check All') {
				check = true;
				$('#checkAll').val('Uncheck All');
			} else {
				check = false;
				$('#checkAll').val('Check All');			
			}
		
			$. each($('input[data-entity="work"]'), function(){
				
				$(this).prop('checked', check);
				
			/*
				if ($(this).val() == 'Check All') {
				  $('.button input').prop('checked', true);
				  $(this).val('Uncheck All');
				} else {
				  $('.button input').prop('checked', false);
				  $(this).val('Check All');
				}	*/		
			
			});
			
			


		}

       //--------------------------------------------------------------------------------
		function process() {
		
			// document.getElementById('author').innerHTML = html;
			
			var data = {};
			
			var author_qid = '';
			
			
			$. each($("input:checked"), function(){
			    var qid = $(this).attr('id');
			    data[qid] = {};
			    
			    data[qid].type =  $(this).attr('data-entity');
			    if (data[qid].type == 'author') {
			    	author_qid = qid;
			    }
			    
			    if ($(this).attr('data-name')) {
			    	data[qid].name = $(this).attr('data-name');
			    }

			    if ($(this).attr('data-position')) {
			    	data[qid].position = $(this).attr('data-position');
			    }

			    if ($(this).attr('data-affiliation')) {
			    	data[qid].affiliation = JSON.parse(decodeURIComponent($(this).attr('data-affiliation')));
			    }

			});
			
			//alert(JSON.stringify(data));			
			
			if (author_qid != '') {
				
				var qs = [];
			
				for (var i in data) {
					if (data[i].type == 'work') {
					
						// add
						var s = [];
						
						s.push(i);
						s.push('P50');
						s.push(author_qid);
						s.push('P1545');
						s.push('"' + data[i].position + '"');
						s.push('P1932');
						s.push('"' + data[i].name + '"');
						
						// Add affiliation string to author so we preserve thus info
						if (data[i].affiliation && data[i].affiliation.length > 0) {
							for (var j in data[i].affiliation) {
								s.push('P6424');
								s.push('"' + data[i].affiliation + '"');
							}
						}

						qs.push(s.join("\t"));
	
						// delete
						s = [];						
						s.push('-' + i);
						s.push('P2093');
						s.push('"' + data[i].name + '"');
						
						qs.push(s.join("\t"));
					}
				}
				
				document.getElementById('qs').innerHTML = qs.join("\n");
				
				/*
				// URL commands (doesn't work)
				var commands = qs.join("\n");
				var url = 'https://tools.wmflabs.org/quickstatements/index_old.html';
				url += '#v1=' + encodeURIComponent(commands);
				
				document.getElementById('qs1').setAttribute('href', url);
				*/
			
			}
		}


       //--------------------------------------------------------------------------------
		function wikidata_author_name(name, element_id) {
		   
		   //alert(name);
		   
			var sparql = `SELECT 
?work ?title ?container_label ?author_order ?author_affiliation
?author_name
{ 
 ?statement ps:P2093 "` + name + `" .
 ?work p:P2093 ?statement.
 ?statement pq:P1545 ?author_order. 
  OPTIONAL 
  {
    ?statement pq:P6424 ?author_affiliation. 
  }

 ?work wdt:P2093 ?author_name. 
  
 ?work wdt:P1476 ?title .
 FILTER (lang(?title) = 'en') .
 ?work wdt:P1433 ?container .
 ?container rdfs:label ?container_label .
 FILTER (lang(?container_label) ='en').
}
#ORDER BY (xsd:integer(?author_order))
ORDER BY ?container_label`;

var sparql = `SELECT 
?work ?workLabel ?containerLabel ?author_order ?author_affiliation
?author_name
{ 
 ?statement ps:P2093 "` + name + `" .
 ?work p:P2093 ?statement.
 ?statement pq:P1545 ?author_order. 
  OPTIONAL 
  {
    ?statement pq:P6424 ?author_affiliation. 
  }

 ?work wdt:P2093 ?author_name. 
 ?work wdt:P1433 ?container .

 SERVICE wikibase:label {
    bd:serviceParam wikibase:language "en" .
   }  
}

ORDER BY ?containerLabel`;
			
			console.log(sparql);
	
			$.getJSON('https://query.wikidata.org/bigdata/namespace/wdq/sparql?query=' + encodeURIComponent(sparql),
				function(data){
				
				console.log(JSON.stringify(data));
				
				  if (data.results.bindings.length > 0) {
				      var rows = {};
				      
				      for (var i in data.results.bindings) {
				         var work = data.results.bindings[i].work.value;
				         work = work.replace("http://www.wikidata.org/entity/", "");
						 if (!rows[work]) {
							rows[work] = {};
							rows[work].authors = [];
							rows[work].affiliation = [];
						 }				      
					  
						  if (data.results.bindings[i].workLabel) {
							rows[work].title = data.results.bindings[i].workLabel.value;
						  }
						  
			  			 if (data.results.bindings[i].containerLabel) {
							rows[work].container_label = data.results.bindings[i].containerLabel.value;
						  }

			  			 if (data.results.bindings[i].author_order) {
			  			    var k = data.results.bindings[i].author_order.value;
			  			    var v = data.results.bindings[i].author_name.value;
			  			    
			  			    // Store all names in a list just to help give clues about author
			  			    if (rows[work].authors.indexOf(v) === -1) {
				  			 	rows[work].authors.push(v);
				  			 }
			  			 	
			  			 	if (v === name) {
			  			 		rows[work].position = k;
			  			 	}
			  			 	
			  			 	
			  			 	
						  }
						  
			  			 if (data.results.bindings[i].author_affiliation) {
			  			    var k = data.results.bindings[i].author_affiliation.value;
			  			    var v = data.results.bindings[i].author_name.value;
			  			 	
			  			 	if (v === name) {
			  			 		rows[work].affiliation.push(k);
			  			 	}
						  }
						  
									  
						}
										  
				     var html = '';
				     
				     html += '<h2>Works</h2>';
				     				     
				     //alert(JSON.stringify(rows));
				     
				     html += '<input style="font-size:18px;" id="checkAll" type="button" value="Check All" onclick="checkall()">';
				     
				     html += '<table>';
				     html += '<tr><th>Match?&nbsp;Y/N</th><th>Authors</th><th>Work</th></tr>';
				     for (var i in rows)
				     {
				     	html += '<tr>';
				     							     	
				     	
				     	html += '<td align="center">';
				     	
				     	// Store data as attributes so we can use these to generate quickstatements
				     	html += '<input id="' + i + '"'
				     		+ ' data-entity="work" data-name="' + name + '"'
				     		+ ' data-position="' + rows[i].position + '"';
				     		
				     	if (rows[i].affiliation.length > 0) {
				     		html += ' data-affiliation="' + encodeURIComponent(JSON.stringify(rows[i].affiliation)) + '"';				     	
				     	}
				     	
				     	html += ' type="checkbox">';
				     	html += '</td>';

						/*
				     	html += '<td>';
				     	html += rows[i].cmd;
				     	html += '</td>';
				     	*/
				     	
				     	html += '<td>';
				     	for (var j in rows[i].authors) {
				     		if (rows[i].authors[j] === name) {
				     			html += '<b>';
				     		} else {
				     			html += '<span style="border:1px solid rgb(192,192,192);margin-right:3px;">';
				     		}
				     		html += rows[i].authors[j].replace(' ', '&nbsp;') + '  ';
				     		if (rows[i].authors[j] === name) {
				     			html += '</b>';
				     		} else {
				     			html += '</span>';
				     		}
				     		
				     		
				     	
				     	}
				     	html += '</td>';
				     	
				     	html += '<td width="50%">' 
				     	+ '<a href="https://www.wikidata.org/wiki/' + i + '" target="_new">'
				     	+ rows[i].title + ' ' 
				     	+ '</a>'
				     	+  '<b>' + rows[i].container_label + '</b>' 
				     	+ '</td>';
				     	
				     	
				     	html += '</tr>';
				     
				     }
					html += '</table>';
					
					
					
					html += '<h2>Author</h2><div id="author"></div>';
					
					
					document.getElementById(element_id).innerHTML = html;
					
					// get candidate authors
					var url = 'https://www.wikidata.org/w/api.php?action=wbsearchentities&format=json&search=' + encodeURIComponent(name) + '&type=item&language=en&limit=1';
					
					$.getJSON(url + '&callback=?',
						function(data){
							//alert(JSON.stringify(data));	  
							
							if (data.success && data.search.length == 1) {
								var html = '';
								
								html += '<input id="' + data.search[0].id + '" data-entity="author" type="checkbox"> ';
								
								html += 
								'<a href="' + data.search[0].concepturi + '" target="_new">'
								+ data.search[0].label
								+ '</a>';
								
								if (data.search[0].match.type == 'alias') {
									html += ' ("' +  data.search[0].match.text + '")';
								}
								
								// Need to do something here...
								html += '<h2>Quickstatements</h2>'
								
								html += '<p/><button id="go" onclick="process();" style="font-size:24px;">Create</button>';
								
								//html += '<p/>' + '<a id="qs1" href="" target="_new">Quickstatements 1.0</a>';
								
								html += '<p/>' + '<a href="https://tools.wmflabs.org/quickstatements/#/batchhref="" target="_new">Quickstatements 2.0</a>';
								
								html += '<div id="qs" style="width:100%;white-space:pre;background-color:#333;color:white;overflow:auto;">[quickstatements appear here]</div>';
								
								document.getElementById('author').innerHTML = html;
								
								
							
							}
						}
					);
					
					
					
						 
						 
				  } else {
				     html = '';         
				  }
				  
				  
			});			

		}  
		
</script>			

</head>
<body>
<h1>Match authors</h1>

<div>
	<form action="wikidata-match.php">
		<input style="font-size:20px;height:36px;" id="search" placeholder="R. B. Halliday" name="q" value="<?php echo $query; ?>"/>
	</form>
	
</div>

<div>

	<div id="candidates"></div>
	
<?php

if ($query != "")
{
?>
	<script>
	
	var name = '<?php echo $query; ?>';

	wikidata_author_name(name,"candidates");
	
	</script>
	
<?php
}	
?>
	
</body>
</html>