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

