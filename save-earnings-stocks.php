<?php 

error_reporting(1);

ini_set('display_errors', 1);

	$symbol=$_GET['symbol'];

	file_put_contents('./earnings.txt', $symbol . "\n", FILE_APPEND);

	$arr = array('result' => 'ok');

	echo json_encode($arr); 

?>
