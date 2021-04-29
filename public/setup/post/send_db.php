<?php

if(!isset($_POST['dbname'], $_POST['host'], $_POST['username'])) {
    echo 'All required fields are not set.';
    return;
}
$config = json_decode(file_get_contents(__DIR__ . '/../../../app/config.json'), true);

$config[$config['environment']]['database']['host'] = $_POST['host'];
$config[$config['environment']]['database']['dbname'] = $_POST['dbname'];
$config[$config['environment']]['database']['username'] = $_POST['username'];
$config[$config['environment']]['database']['password'] = $_POST['password'];
$sfile = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
file_put_contents(__DIR__ . '/../../../app/config.json', $sfile);

$progress = json_decode(file_get_contents(__DIR__ . '/../progress.json'));
$progress->database = true;
file_put_contents(__DIR__ . '/../progress.json', json_encode($progress, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
header ('Location: /setup/index.php');
?>