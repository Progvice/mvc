<?php

class profiiliController extends Controller {
    public function profiili() {
        if(!isset($_SESSION['login'])) {
            header('Location: /login');
            return;
        }
        plugin::load('view, models');
        $view = new Core\App\View();
        $models = new Core\App\Models\MainModel();
        $models->CallModel('users');

        if(!isset($_SESSION['login']['uuid'])) {
            $view->variables = [
                'error' => 'Error: Profile not found'
            ];
            $view->index($this->view);
            return;
        }
        $userdata = $models->Select([
            'values' => [
                'normal' => [
                    'uuid' => $_SESSION['login']['uuid']
                ]
            ],
            'columns' => "firstname, lastname, email, phonenumber"
        ]);
        $view->variables = [
            'userdata' => $userdata
        ];
        $view->index($this->view);
    }
}

?>