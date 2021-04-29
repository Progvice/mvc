<?php
session_start();
define('APP_PATH', __DIR__ . '/../app');
define('CONTROLLER_PATH', APP_PATH . '/controllers/');
define('MODEL_PATH', APP_PATH . '/models');
define('REQUEST_PATH', APP_PATH . '/requests');
define('VIEW_PATH', APP_PATH . '/views/pages');
define('PLUGIN_PATH', APP_PATH . '/plugins');
define('JSON_PATH', APP_PATH . '/plugins/json');
/*
 *  If you feel like using composer instead of JJMVC plugins you can include composer in
 *  controller. You can simply load composer like this: "require COMPOSER;"
 *
 */
define('COMPOSER', PLUGIN_PATH . '/composer/vendor/autoload.php');
if(file_exists(__DIR__ . '/setup')) {
    header('Location: /setup/index.php');
    return;
}

$config = json_decode(file_get_contents(APP_PATH . '/config.json'), true);

/*
 *  APP_MODE is for changing environment.
 *
 *  Default modes are
 *  - "development"
 *  - "production"
 * 
 *  You can change development mode in /app/config.json
 *       
 */

if($config['environment'] === 'development' || $config['environment'] === 'production'){
    define('CONFIG', $config[$config['environment']]);
}
else {
    echo 'Invalid configuration mode set. Set proper configuration mode at /app/config.json';
    die;
}




$lang = json_decode(file_get_contents(APP_PATH . '/lang.json'), true);
if(isset($_SESSION['logses']['lang'])) {
    define('LANG', $lang[$_SESSION['logses']['lang']]);
}
else {
    define('LANG', $lang['en-en']);
}
$menu = json_decode(file_get_contents(APP_PATH . '/menu.json'), true);
define('MENU', $menu);
/*
 * LANG is for setting translations.
 *
 * LANG['fi-fi']
 *
 *
 */
$parse_url = parse_url($_SERVER['REQUEST_URI']);
$uri = explode("/", $parse_url['path']);
$json_url = $uri;

if(end($json_url) === '') {
    array_pop($json_url);
    array_pop($uri);
    $json_url = implode("/", $json_url);
}
else {
    $json_url = implode("/", $json_url);
}
switch($parse_url['path']) {
    case '/':
        $requests = file_get_contents(REQUEST_PATH . '/index.json');
        $requests = json_decode($requests);
        $view_path = '/index';
        $method = 'index';
    break;
    default:
        $requests = file_get_contents(REQUEST_PATH . $json_url . '/index.json');
        if(!$requests) {
            header('Location: /404');
        die();
        }
        $requests = json_decode($requests);
        $view_path = $parse_url['path'];
    break;
}

$url = $requests->page->url;
$url_second = $requests->page->url . '/';
if($requests->page->url === $parse_url['path'] || $url_second === $parse_url['path']) {
    $page_found = TRUE;
    $count = count($uri) - 1;
    $method = $requests->page->actions;
    $class_name = $requests->page->name . 'Controller';
    require_once CONTROLLER_PATH . '/Controller.php';
    $controller = new Controller();
    $controller->method = $method;
    $controller->uri = $uri;
    $controller->controller = $requests->page->controller;
    $controller->view = VIEW_PATH . $view_path . '/index.php';
    $controller->classname = $class_name;
    $controller->title = $requests->page->title;
    $controller->cindex();
}

if(!$page_found) {
    header('Location: /404');
    die();
}
