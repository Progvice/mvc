<!DOCTYPE HTML>
<html>
    <head>
        <link rel="stylesheet" href="/setup/css/style.css">
        <title>JJMVC Setup</title>
    </head>
    <body>
        <header>
            <div class="inner_header">
                <img src="/setup/img/jjmvc_white_long.png" alt="#"/>
                <h2>Setup</h2>
            </div>
        </header>
        <div class="main">
            <?php
                $progress = json_decode(file_get_contents(__DIR__ . '/progress.json'));
                if(!$progress->config) {
                    $config = file_get_contents(__DIR__ . '/setup_config.php');
                    echo $config;
                    return;
                }
                else if(!$progress->database) {
                    $db = file_get_contents(__DIR__ . '/setup_db.php');
                    echo $db;
                    return;
                }
                $content = file_get_contents(__DIR__ . '/success.php');
                echo $content;
            ?>
        </div>
    </body>
</html>
<?php
    if($progress->config === true && $progress->database === true) {
        unlink(__DIR__ . '/css/style.css');
        unlink(__DIR__ . '/css/fonts/Opensans.ttf');
        rmdir(__DIR__ . '/css/fonts');
        rmdir(__DIR__ . '/css');
        unlink(__DIR__ . '/img/jjmvc_white_long.png');
        rmdir(__DIR__ . '/img');
        unlink(__DIR__ . '/post/send_config.php');
        unlink(__DIR__ . '/post/send_db.php');
        rmdir(__DIR__ . '/post');
        unlink(__DIR__ . '/setup_config.php');
        unlink(__DIR__ . '/setup_db.php');
        unlink(__DIR__ . '/success.php');
        unlink(__DIR__ . '/progress.json');
        unlink(__FILE__);
        rmdir(__DIR__);
        sleep(2);
        header('Location: /');
    }
?>