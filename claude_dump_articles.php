<?php
// Background fetch for the Claude dump: the TEXT of the NEW news articles.
// Called by mainscript.js after the SEC panel call (asynchronously), with a POST list of links.

header('Content-Type: application/json');

$urls = (isset($_POST['urls']) && is_array($_POST['urls'])) ? $_POST['urls'] : array();
$clean = array();
foreach ($urls as $url)
{
    $url = trim((string) $url);
    if (preg_match('#^https?://#i', $url) && strlen($url) < 2000)
    {
        $clean[] = $url;
    }
    if (count($clean) >= 15)
    {
        break;
    }
}

if (count($clean) == 0)
{
    echo json_encode(array('ok' => true, 'error' => null, 'articles' => array()));
    exit;
}

$venv_python = '/var/www/html/newslookup/venv/bin/python3';
$output = shell_exec($venv_python . ' ./pythonscrape/fetch-articles.py ' . escapeshellarg(json_encode($clean)));

if ($output === null || trim($output) === '')
{
    echo json_encode(array('ok' => false, 'error' => 'no response from fetch-articles.py'));
}
else
{
    echo trim($output);
}
?>