<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="referrer" content="origin">
        <title><?= $title . ' | ' . CONFIG['server']['name'] ?></title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <?php 
            plugin::load('core:javascript:1.0, core:css:1.0');
            new Core\App\Css();
            new Core\App\Js();
        ?>
    </head>
    <body>
        <header>
            <div class="logo_text">
                <h2>MVC APPLICATION</h2>
            </div>
            <div class="menu">
                <ul class="menu_ul">
                    <li><a href="/pages">Pages</a></li>
                    <li><a href="/configuration">Configuration</a></li>
                    <li><a href="/controllers">Controllers</a></li>
                    <li><a href="/plugins">Plugins</a></li>
                </ul>
            </div>
        </header>