<?php 

error_reporting(0);

// header('Content-type: text/html');
$symbol=$_GET['symbol'];

$command = escapeshellcmd('python3 ./pythonscrape/scrape-street-insider.py ' . $symbol);

echo "before running the shell command<br><br>"; 

$output = shell_exec($command);

echo "before echoing the output"; 

echo "*" . $output . "*";


?>