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
	<meta charset="utf-8">
    <script src="external/jquery.js"></script>
    
    <!-- SPARQL queries -->
    <script src="js/ala-names.js"></script> 
    
	<style>
		body {
			padding-left:40px;
			padding-right:40px;
			font-family: sans-serif;
		}
		
		.h1 {
			font-size:36px;
			color: rgb(56,64,71);
		}
	</style>
</head>
<body>
	<div>
		<div style="display:block;float:left;"><img src="images/ala-logo-2016-inline.png"></div>
		<div>
			<form action="alademo.php">
				<input style="font-weight:lighter;margin-left:30px;color:rgb(56,64,71);font-size:20px;height:36px;display:block;float:left;border:1px solid rgb(135,135,135);padding-left:4px;" id="search" placeholder="Search" name="q" value="<?php echo $query; ?>"/>
			</form>
			
		</div>
	<div>
	<div style="clear:both;"></div>
		
	
	<div>
		<h1 id="header"></h1>
	</div>
	
	<div>
		<!-- tabs -->
		<div>
		<ul style="color:rgb(190,85,61);display:block;list-style-position:outside;list-style-type:none;">
			<li style="padding:10px;display:block;float:left;">Overview</li>
			<li style="padding:10px;display:block;float:left;">Gallery</li>
			<li style="padding:10px;border-bottom:1px solid white;border-top:1px solid rgb(135,135,135);border-left:1px solid rgb(135,135,135);border-right:1px solid rgb(135,135,135);display:block;float:left;color: rgb(56,64,71);">Names</li>
			<li style="padding:10px;display:block;float:left;">Classification</li>
			<li style="padding:10px;display:block;float:left;">Records</li>
			<li style="padding:10px;display:block;float:left;">Literature</li>
			<li style="padding:10px;display:block;float:left;">Sequences</li>
			<li style="padding:10px;display:block;float:left;">Data Partners</li>
		</ul>
		</div>
		<div style="clear:both;"></div>
		
		<!-- results box -->		
		<div style="margin-top:-1px;border:1px solid rgb(135,135,135);width:100%;min-height:100px;">
			<div style="padding:10px;" id="names"></div>
		</div>
		
	</div>
	
	<div style="color:rgb(56,64,71);">
		<h4>Proof of concept for displaying references in ALA</h4>
		
		<p>This demo shows one way to enhance the display of taxonomic names and associated 
		literature in the "Names" tab for a taxon in ALA. The demo is "live" in that all results are generated
		by querying <a href="https://ozymandias-demo.herokuapp.com">Ozymandias</a>. Here are some examples to try:</p>
		
		<ul>
			<li><a href="alademo.php?q=Calomantispa+picta+Stitz%2C+1913">Calomantispa picta Stitz, 1913</a></li>
			<li><a href="alademo.php?q=Repomucenus+russelli+%28Johnson%2C+1976%29">Repomucenus russelli (Johnson, 1976)</a></li>
			<li><a href="alademo.php?q=Maricoccus+brucei+Poore%2C+1994">Maricoccus brucei Poore, 1994</a></li>
		</ul>
		
		<p>Feel free to try other examples, but remember that the taxon name must be for an animal in AFD,
		and the name must be <bold>exactly</bold> as in the corresponding ALA page.</p>
	
	
	</div>
	
<?php

if ($query != "")
{
?>
	<script>
	
	var name = '<?php echo $query; ?>';
	var element_id = 'names';
	$('#' + element_id).html('');
	
	$('#header').html(name);
	
	works_for_taxon_from_name(name, element_id);
	
	</script>
<?php
}	
?>

	
</body>
</html>

