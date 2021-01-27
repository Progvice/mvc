<?php

class addPageController extends Controller {
    public function add() {
        plugin::load('core:view:1.0');
        $view = new Core\App\View;
        $view->customelements = ['header' => 'admin'];
        $view->index($this->title, $this->view);
    }
}

?>