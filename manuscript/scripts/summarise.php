<?php


$values = array();

for ($year = 1750; $year < 2019; $year++)
{
	$values[$year] = 0;
}

$filename = 'ids.tsv';

$count = 0;


$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$line = trim(fgets($file_handle));
	
	$parts = explode("\t", $line);
	
	if ($count > 0)
	{
		$year = $parts[2];
	
		$values[$year]++;
		
	}
	$count++;
}

fclose($file_handle);


//print_r($values);

for ($year = 1758; $year < 2019; $year++)
{
	echo $year . "\t" . $values[$year] . "\n";
}


?>
