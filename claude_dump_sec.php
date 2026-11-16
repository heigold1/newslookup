<?php
// Background fetch for the Claude dump: press-release text + going-concern lines from sec.gov.
// Called by mainscript.js right after each lookup (asynchronously). Only needs the CIK.

header('Content-Type: application/json');

$cik = isset($_GET['cik']) ? preg_replace('/\D/', '', $_GET['cik']) : '';
if ($cik === '')
{
    echo json_encode(array('ok' => false, 'error' => 'no CIK on file'));
    exit;
}

$venv_python = '/var/www/html/newslookup/venv/bin/python3';
$output = shell_exec($venv_python . ' ./pythonscrape/sec-dump-docs.py ' . escapeshellarg($cik));

if ($output === null || trim($output) === '')
{
    echo json_encode(array('ok' => false, 'error' => 'no response from sec-dump-docs.py'));
}
else
{
    echo trim($output);
}
?>