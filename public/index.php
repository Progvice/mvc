<?php
if (isset($_COOKIE['consent'])) {
    session_start();
}
define('APP_PATH', __DIR__ . '/../app');
define('CONTROLLER_PATH', APP_PATH . '/controllers/');
define('MODEL_PATH', APP_PATH . '/models');
define('REQUEST_PATH', APP_PATH . '/requests');
define('VIEW_PATH', APP_PATH . '/views/pages');
define('PLUGIN_PATH', APP_PATH . '/plugins');
define('JSON_PATH', APP_PATH . '/plugins/json');
define('DATA_PATH', APP_PATH . '/views/data');
define('TEMPLATE_PATH', APP_PATH . '/views/templates/');
/*
 *  If you feel like using composer instead of JJMVC plugins you can include composer in
 *  controller. You can simply load composer like this: "require COMPOSER;"
 *
 */
define('COMPOSER', PLUGIN_PATH . '/composer/vendor/autoload.php');


if (file_exists(__DIR__ . '/setup.zip')) {

    if (!extension_loaded('zip')) {
        echo <<<EOD
            <h1>JJMVC Setup failed</h1>
            <p>PHP Zip extension is not loaded. Zip extension is required for JJMVC setup. You can either: </p>
            <ol>
                <li>Enable Zip extension and make it through setup</li>
                <li>Make manual installation</li>
            </ol>

            <p>If you decided to enable Zip extension just for this setup you can freely disable it afterwards. JJMVC does not use it anywhere else. Sorry for the inconvenience! </p>
        EOD;
        die;
    }

    $zip = new ZipArchive;

    if ($zip->open(__DIR__ . '/setup.zip') === TRUE) {
        $zip->extractTo(__DIR__);
        $zip->close();
    }
    unlink(__DIR__ . '/setup.zip');
    header('Location: /setup/index.php');
    return;
} else if (file_exists(__DIR__ . '/setup')) {
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

if ($config['environment'] === 'development' || $config['environment'] === 'production') {
    define('CONFIG', $config[$config['environment']]);
} else {
    echo 'Invalid configuration mode set. Set proper configuration mode at configuration file.';
    die;
}
$lang = json_decode(file_get_contents(APP_PATH . '/lang.json'), true);

define('LANG', isset($_SESSION['login']['lang']) ? $lang[$_SESSION['login']['lang']] : $lang['fi-fi']);

if (isset(CONFIG['timezone'])) {
    date_default_timezone_set(CONFIG['timezone']);
}

$menu = json_decode(file_get_contents(APP_PATH . '/menu.json'), true);

define('MENU', $menu);



$parse_url = parse_url($_SERVER['REQUEST_URI']);
if ($parse_url === false) {
    echo 'Invalid URL';
    exit;
}

$uri = explode("/", $parse_url['path']);

if (end($uri) === '') {
    array_pop($uri);
}

$count_uri = $uri;

// All possible uris. We need this informatiion so we can load controller even though we set parameters to URI
$final_url = implode('/', $uri);
$right_uri = '';

$url_check = preg_match("/\.\./", $parse_url['path']);

if ($url_check) {
    echo "Path traversal blocked.";
    exit;
}

// If request is just request to index then we want to define these settings
if ($parse_url['path'] === '/') {
    $requests = file_get_contents(REQUEST_PATH . '/index.json');
    $requests = json_decode($requests);
    $view_path = '/index';
    $method = 'index';
    $right_uri = '/';
} else {
    // Traverse URI from right to left and choose first match that it finds based on that
    for ($counter = 0; $counter <= count($uri); $counter++) {
        $current_uri = implode('/', $count_uri);
        if (file_exists(REQUEST_PATH . $current_uri . '/index.json')) {
            $right_uri = $current_uri;
            break;
        }
        array_pop($count_uri);
    }
    $requests = json_decode(file_get_contents(REQUEST_PATH . $right_uri . '/index.json'));
    $view_path = $right_uri;
}
if  (isset($requests->page->params) === false 
        && $right_uri !== $final_url 
        && $parse_url['path'] !== '/'
    ) {
    header('Location: /404');
    exit;
}

$url = $requests->page->url;
$url_second = $requests->page->url . '/';

if ($url === $right_uri || $url_second === $right_uri) {
    $page_found = TRUE;
    require_once CONTROLLER_PATH . '/Controller.php';
    $controller = new Controller();
    $controller->method = $requests->page->actions;
    $controller->base_uri = $right_uri;
    $controller->params_uri = $final_url;
    $controller->uri_array = $uri;
    $controller->controller = $requests->page->controller;
    $controller->params = isset($requests->page->params) ? $requests->page->params : null;
    $controller->view = VIEW_PATH . $view_path . '/index.php';
    $controller->classname = $requests->page->name . 'Controller';
    $controller->disabled = isset($requests->page->disabled) ? $requests->page->disabled : false;
    $controller->title = $requests->page->title;
    $controller->cindex();
}

if (isset($page_found)) {
    if (!$page_found) {
        header('Location: /404');
    }
}
