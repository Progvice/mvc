<?php

if(!isset($_POST['sname'], $_POST['host'], $_POST['environment'])) {
    echo 'All required fields are not set.';
    return;
}

$cfile = json_decode(file_get_contents(__DIR__ . '/../../../app/config-example.json'), true);
$cfile['environment'] = $_POST['environment'];
$cfile[$_POST['environment']]['server']['host'] = $_POST['host'];
$cfile[$_POST['environment']]['server']['name'] = $_POST['sname'];
$sfile = json_encode($cfile, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
file_put_contents(__DIR__ . '/../../../app/config.json', $sfile);

$progress = json_decode(file_get_contents(__DIR__ . '/../progress.json'));
$progress->config = true;
file_put_contents(__DIR__ . '/../progress.json', json_encode($progress, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
header ('Location: /setup/index.php?configset=true');

?>