<?php


$values = array();

for ($year = 1750; $year < 2019; $year++)
{
	$values[$year] = 0;
}

$filename = 'weevils.tsv';
$filename = 'weevil_names.tsv';
$filename = 'c_names.tsv';
$filename = 'c_cum.tsv';
$filename = 'works.tsv';
$filename = 'weevil_g.tsv';

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$line = trim(fgets($file_handle));
	
	$parts = explode("\t", $line);
	
	if (is_numeric($parts[0]))
	{
		$values[$parts[0]] = $parts[1];
	}
}

fclose($file_handle);


//print_r($values);

for ($year = 1758; $year < 2019; $year++)
{
	echo $year . "\t" . $values[$year] . "\n";
}


?>
