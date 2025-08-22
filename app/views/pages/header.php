<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="origin">
    <title><?= $title . ' | ' . CONFIG['server']['name'] ?></title>
    <?php
    Plugin::load('css, js, templateloader');
    new Core\App\Css();
    new Core\App\Js();
    $template = $template ?? new Core\App\Template();
    ?>
</head>

<body>
    <?php echo $template->load(['name' => 'Header']) ?>
    <div class="main height-100 row wrap">