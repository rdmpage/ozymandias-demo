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
    
	<!-- altmetric -->
	<!-- 
	<script type="text/javascript" src="https://d1bxh8uas1mnw7.cloudfront.net/assets/embed.js"></script>
	-->
    
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
		
		.tab {
			padding:10px;
			display:block;
			float:left;
		}
		
		.tab-active {
			padding:10px;
			border-bottom:1px solid white;
			border-top:1px solid rgb(135,135,135);
			border-left:1px solid rgb(135,135,135);
			border-right:1px solid rgb(135,135,135);
			display:block;
			float:left;
			color: rgb(56,64,71);
		}
		
		a {
			text-decoration:none;
			color:rgb(190,85,61);
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
			<li class="tab">Overview</li>
			<li class="tab">Gallery</li>
			<li class="tab-active">Names</li>
			<li class="tab">Classification</li>
			<li class="tab">Records</li>
			<li class="tab">Literature</li>
			<li class="tab">Sequences</li>
			<li class="tab">Data Partners</li>
		</ul>
		</div>
		<div style="clear:both;"></div>
		
		<!-- results box -->		
		<div style="margin-top:-1px;border:1px solid rgb(135,135,135);width:100%;min-height:100px;">
			<div style="padding:10px;" id="names"></div>
		</div>
		
	</div>
	
	<div style="color:rgb(56,64,71);">
		<h3>Proof of concept for displaying references in ALA</h3>
		
		<p>This demo shows one way to enhance the display of taxonomic names and associated 
		literature in the "Names" tab for a taxon in ALA. The demo is "live" in that all results are generated
		by querying <a href="https://ozymandias-demo.herokuapp.com">Ozymandias</a>. Here are some examples to try. 
		Note that the categories "paywall" and "free to read" need not apply to all the papers listed, just some.</p>
		
		
		<h4>Paywall</h4>
		<ul>
			<li><a href="alademo.php?q=Calomantispa+picta+Stitz%2C+1913">Calomantispa picta Stitz, 1913</a></li>
			<li><a href="alademo.php?q=Repomucenus+russelli+%28Johnson%2C+1976%29">Repomucenus russelli (Johnson, 1976)</a></li>
		</ul>
		</h4>
		
		<h4>Free to read</h4>
		<ul>
			<li><a href="alademo.php?q=Bellatorias+obiri+%28Wells+%26+Wellington%2C+1985%29">Bellatorias obiri (Wells & Wellington, 1985)</a></li>
		</ul>
		</h4>		
		
		<h4>Museums Victoria</h4>
		<ul>
			<li><a href="alademo.php?q=Maricoccus+brucei+Poore%2C+1994">Maricoccus brucei Poore, 1994</a></li>
		</ul>
		</h4>

		
		<h4>Records of the Australian Museum</h4>
		<ul>		
			<li><a href="alademo.php?q=Pauropsalta+herveyensis+Owen+%26+Moulds%2C+2016">Pauropsalta herveyensis Owen & Moulds, 2016</a></li>
		</ul>
		</h4>
		
		
		<h4>Iconic animals</h4>
		<ul>
			<li><a href="alademo.php?q=Thylacinus cynocephalus (Harris, 1808)">Thylacinus cynocephalus (Harris, 1808)</a></li>
			<li><a href="alademo.php?q=Dactylopsila trivirgata Gray, 1858">Dactylopsila trivirgata Gray, 1858</a></li>
		</ul>
		
		<p>Feel free to try other examples, but remember that (for the purposes of this demo) the taxon name <strong>must be for an animal in AFD</strong>,
		and the name <strong>must be exactly</strong> as in the corresponding ALA page. This is because all the data comes from 
		<a href="https://ozymandias-demo.herokuapp.com/">Ozymandias</a> (which uses data from AFD), and 
		the demo doesn't do approximate search matching like the ALA website itself, it simply takes the taxon name as given and searches on that. 
		</p>
	
	
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

