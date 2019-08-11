<?php 

header("Access-Control-Allow-Origin: *");

error_reporting(1);

ini_set('display_errors', 1);

	$fileContents = file_get_contents('./earnings.txt');

	$symbols = explode("\n", $fileContents); 

	$arr = array(); 

	foreach ($symbols as $symbol)
	{
		array_push($arr, $symbol);
	}

	$removed = array_pop($arr);
	echo json_encode($arr); 

?>