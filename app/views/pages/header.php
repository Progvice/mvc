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
    <?php if (empty($_COOKIE['consent'])) { ?>
        <div class="cn_bg"></div>
        <div class="cookienotification">
            <h1><?php echo LANG['usescookies']; ?></h1>
            <p><?php echo LANG['cookiesinfo']; ?></p>
            <a class="button yellowborder" id="cacceptall" href="/cookies/action/acceptall"><?php echo LANG['acceptall'] ?></a>
            <a class="button yellowborder" id="cacceptsome" href="/cookies/action/acceptnecessary"><?php echo LANG['acceptnecessary']; ?></a>
        </div>
    <?php } ?>
    <div class="main height-100 row wrap">