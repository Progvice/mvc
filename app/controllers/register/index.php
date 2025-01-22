<?php

class registerController extends Controller {
    public function register() {
        if(isset($_SESSION['login'])) {
            header('Location: /');
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