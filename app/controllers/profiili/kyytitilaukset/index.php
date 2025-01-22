<?php 

class kyytitilauksetController extends Controller {
    public function kyytitilaukset() {
        if(!isset($_SESSION['login'])) {
            header('Location: /login');
            return;
        }
        plugin::load('view, models');

        $models = new Core\App\Models\MainModel();
        $models->CallModel('guestrides');
        $rides = $models->Select([
            'values' => [
                'normal' => [
                    'userid' => $_SESSION['login']['uuid']
                ]
            ]
        ]);
        $view = new Core\App\View();
        $view->variables = [
            'rides' => $rides
        ];
        $view->index($this->view);
    }
    public function getUsersOrders() {
        plugin::load('view, models');
        $view = new Core\App\View();
        $models = new Core\App\Models\MainModel();
        $models->CallModel('guestrides');
        $ride = $models->Select([
            'values' => [
                'normal' => [
                    'uuid' => $this->params[0]
                ]
            ]
        ]);
        if(!isset($_SESSION['login'])) {
            header('Location: /404');
        }
        if($ride[0]['userid'] !== $_SESSION['login']['uuid']) {
            plugin::load('response');
            $response = new Core\App\Response();
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