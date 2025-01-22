<?php 

class tilaakyytiController extends Controller {
    public function tilaakyyti() {
        plugin::load('view, models');
        $view = new Core\App\View();
        if(isset($_SESSION['login'])) {
            $models = new Core\App\Models\MainModel();
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