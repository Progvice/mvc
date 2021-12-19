<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="referrer" content="origin">
        <title><?= $title . ' | ' . CONFIG['server']['name'] ?></title>
        <?php 
            plugin::load('css, js, templateloader');
            new Core\App\Css();
            new Core\App\Js();
            $template = new Core\App\Template();
        ?>
    </head>
    <body>
    <header>
        <div class="inner_header">
            <?php
                echo $template->Load(['name' => 'header_title']);
                echo $template->Load(['name' => 'header_menu']);
            ?>
        </div>
    </header>