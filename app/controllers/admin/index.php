<?php

class adminController extends Controller {
    public function admin() {
        plugin::load('view');
        $view = new Core\App\View;
        $view->customelements = ['header' => 'admin'];
        $view->index($this->title, $this->view);
    }
}

?>