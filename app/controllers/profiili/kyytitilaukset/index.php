<?php 

use Core\App\Models\MainModel;
use Core\App\View;
use Core\App\Response;

class kyytitilauksetController extends Controller {
    public function kyytitilaukset() {
        if (!isset($_SESSION['login'])) {
            header('Location: /login');
            return;
        }
        Plugin::load('view, models');

        $models = new MainModel();
        $models->CallModel('guestrides');
        $rides = $models->Select([
            'values' => [
                'normal' => [
                    'userid' => $_SESSION['login']['uuid']
                ]
            ]
        ]);
        $view = new View();
        $view->variables = [
            'rides' => $rides
        ];
        $view->index($this->view);
    }
    public function getUsersOrders() {
        Plugin::load('view, models');
        $view = new View();
        $models = new MainModel();
        $models->CallModel('guestrides');
        $ride = $models->Select([
            'values' => [
                'normal' => [
                    'uuid' => $this->params[0]
                ]
            ]
        ]);
        if (!isset($_SESSION['login'])) {
            header('Location: /404');
        }
        if ($ride[0]['userid'] !== $_SESSION['login']['uuid']) {
            Plugin::load('response');
            $response = new Response();
            $response->Send('json', [
                'status' => false,
                'msg' => 'Unauthorized access'
            ]);
            return;
        }
        $view->variables = [
            'ride' => $ride
        ];
        $view->index($this->view);
    }
}

?>