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
if(end($uri) === '') {
    array_pop($uri);
}

$count_uri = $uri;

// All possible uris. We need this informatiion so we can load controller even though we set parameters to URI
$ap_uris = [];
$final_url = implode('/', $uri);
$right_uri = '';

if($parse_url['path'] === '/') {
    $requests = file_get_contents(REQUEST_PATH . '/index.json');
    $requests = json_decode($requests);
    $view_path = '/index';
    $method = 'index';
    $right_uri = '/';
}
else {
    for($counter = 0; $counter <= count($uri); $counter++) {
        $current_uri = implode('/', $count_uri);
        if(file_exists(REQUEST_PATH . $current_uri . '/index.json')) {
            $right_uri = $current_uri;
            break;
        }
        array_pop($count_uri);
    }
    $requests = file_get_contents(REQUEST_PATH . $right_uri . '/index.json');
    $requests = json_decode($requests);
    $view_path = $right_uri;
}
if(isset($requests->page->params) === false) {
    if($right_uri !== $final_url && $parse_url['path'] !== '/') {
        header('Location: /404');
    }
}
$url = $requests->page->url;
$url_second = $requests->page->url . '/';

$params = null;
if(isset($requests->page->params)) {
    $params = $requests->page->params;
}
if($url === $right_uri || $url_second === $right_uri) {
    $page_found = TRUE;
    require_once CONTROLLER_PATH . '/Controller.php';
    $controller = new Controller();
    $controller->method = $requests->page->actions;
    $controller->uri = $final_url;
    $controller->controller = $requests->page->controller;
    $controller->params = $params;
    $controller->view = VIEW_PATH . $view_path . '/index.php';
    $controller->classname = $requests->page->name . 'Controller';
    $controller->title = $requests->page->title;
    $controller->cindex();
}

if(!$page_found) {
    header('Location: /404');
    die();
}
