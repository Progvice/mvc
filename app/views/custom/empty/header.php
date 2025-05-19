<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="origin">
    <title><?php echo $title . ' | ' . CONFIG['server']['name'] ?></title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php 
        Plugin::load('css, js, templateloader');
        new Core\App\Css();
        new Core\App\Js();
        $template = new Template();
    ?>
</head>
<body>
    <div class="section-12 height-100 center column">