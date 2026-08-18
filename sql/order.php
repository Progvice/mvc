<?php
define('APP_PATH', __DIR__ . '/../app');
define('PLUGIN_PATH', APP_PATH . '/plugins');
define('MODEL_PATH', APP_PATH . '/models/');

require_once __DIR__ . '/../app/controllers/loader.php';


function loadModels() {
    Plugin::load('models');
    $models = scandir(MODEL_PATH);

    foreach ($models as $model) {
        if (str_ends_with($model, '.php')) {
            require_once MODEL_PATH . $model;
        }
    }
}

loadModels();

use Core\App\Models\Streetaddress;
use Core\App\Models\Personel;
use Core\App\Models\Jobs;
use Core\App\Models\Page;
use Core\App\Models\PageContent;
use Core\App\Models\Rides;

return [
    Jobs::class,
    Personel::class,
    Streetaddress::class,
    Rides::class,
    Page::class,
    PageContent::class
];
