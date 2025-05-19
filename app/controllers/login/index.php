<?php

use Core\App\View;

class loginController extends Controller {
    public function login() {
        if (isset($_SESSION['login'])) {
            header('Location: /profiili');
            return;
        }
        Plugin::load('view');
        $view = new View();
        $view->customelements = [
            'header' => 'empty',
            'footer' => 'empty'
        ];
        $view->index($this->view);
    }
}

?>