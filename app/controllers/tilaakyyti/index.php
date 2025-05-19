<?php 

use Core\App\Models\MainModel;
use Core\App\Response;
use Core\App\View;

class tilaakyytiController extends Controller {
    public function tilaakyyti() {
        Plugin::load('view, models');
        $view = new View();
        if (isset($_SESSION['login'])) {
            $models = new MainModel();
            $models->CallModel('users');
            $user = $models->Select([
                'values' => [
                    'normal' => [
                        'uuid' => $_SESSION['login']['uuid']
                    ]
                ]
            ]);
            $view->variables = [
                'firstname' => $user[0]['firstname'],
                'lastname' => $user[0]['lastname'],
                'email' => $user[0]['email'],
                'phonenumber' => $user[0]['phonenumber']
            ];
        }
        $view->index($this->view);
    }
}

?>