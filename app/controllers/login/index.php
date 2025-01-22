<?php

class loginController extends Controller {
    public function login() {
        if(isset($_SESSION['login'])) {
            header('Location: /profiili');
            return;
        }
        plugin::load('view');
        $view = new Core\App\View();
        $view->customelements = [
            'header' => 'empty',
            'footer' => 'empty'
        ];
        $view->index($this->view);
    }
}

?>