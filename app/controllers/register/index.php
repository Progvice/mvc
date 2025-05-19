<?php

use Core\App\View;

class registerController extends Controller {
    public function register() {
        if (isset($_SESSION['login'])) {
            header('Location: /');
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