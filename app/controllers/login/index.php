<?php

class loginController {
    public function login() {
        plugin::load('view');
        $view = new Core\App\View;
        $view->index($this->title, $this->view);
    }
}

?>