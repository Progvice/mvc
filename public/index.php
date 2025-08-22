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

if (!file_exists(APP_PATH . '/config.json')) {
    echo 'Configuration has not been set';
    exit;
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

$currentLang = $_SESSION['login']['lang'] ?? 'fi';

$supportedLanguages = ['fi', 'en'];

$loadedLang = null;

if (in_array($currentLang, $supportedLanguages)) {
    $loadedLang = require APP_PATH . "/lang/" . $currentLang . '.php';
} else {
    $loadedLang = require APP_PATH . '/lang/fi.php';
}


define('LANG', $loadedLang);

if (isset(CONFIG['timezone'])) {
    date_default_timezone_set(CONFIG['timezone']);
}

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
    $requests = require REQUEST_PATH . '/index.php';
    $view_path = '/index';
    $method = 'index';
    $right_uri = '/';
} else {
    // Traverse URI from right to left and choose first match that it finds based on that
    for ($counter = 0; $counter <= count($uri); $counter++) {
        $current_uri = implode('/', $count_uri);
        if (file_exists(REQUEST_PATH . $current_uri . '/index.php')) {
            $right_uri = $current_uri;
            break;
        }
        array_pop($count_uri);
    }
    $requests = require REQUEST_PATH . $right_uri . '/index.php';
    $view_path = $right_uri;
}

if (
    isset($requests['params']) === false
    && $right_uri !== $final_url
    && $parse_url['path'] !== '/'
) {
    header('Location: /404');
    exit;
}

$url = $requests['url'];
$url_second = $requests['url'] . '/';

if ($url === $right_uri || $url_second === $right_uri) {
    $page_found = TRUE;
    require_once CONTROLLER_PATH . '/Controller.php';
    $controller = new Controller();
    $controller->method = $requests['actions'];
    $controller->base_uri = $right_uri;
    $controller->params_uri = $final_url;
    $controller->uri_array = $uri;
    $controller->controller = $requests['controller'];
    $controller->params = isset($requests['params']) ? $requests['params'] : null;
    $controller->view = VIEW_PATH . $view_path . '/index.php';
    $controller->classname = $requests['name'] . 'Controller';
    $controller->disabled = isset($requests['disabled']) ? $requests['disabled'] : false;
    $controller->title = $requests['title'];
    $controller->httpMethod = isset($requests['httpMethod']) ? $requests['httpMethod'] : false;
    $controller->cindex();
}

if (isset($page_found)) {
    if (!$page_found) {
        header('Location: /404');
        exit;
    }
}
