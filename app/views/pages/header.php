<!DOCTYPE HTML>
<html>
<head>
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-ND34T5Q');
    </script>
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
        <a class="logo" href="/">
            <img src="/img/logo_yellow.png" alt="Logo" />
        </a>
        <?php echo $template->Load(['name' => 'header_menu']); ?>
        <button class="menubutton"><i class="fas fa-bars"></i></button>
    </header>
    <?php if(empty($_COOKIE['consent'])) { ?>
        <div class="cn_bg"></div>
        <div class="cookienotification">
            <h1><?php echo LANG['usescookies']; ?></h1>
            <p><?php echo LANG['cookiesinfo']; ?></p>
            <a class="button yellowborder" id="cacceptall" href="/cookies/action/acceptall"><?php echo LANG['acceptall'] ?></a>
            <a class="button yellowborder" id="cacceptsome" href="/cookies/action/acceptnecessary"><?php echo LANG['acceptnecessary']; ?></a>
        </div>
    <?php } ?>
    <div class="main height-100 row wrap">