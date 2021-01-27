<?php

class pagesController extends Controller {
    public function pages() {
        plugin::load('core:view:1.0');
        $view = new Core\App\View();
        $view->customelements = ['header' => 'admin'];
        $view->index($this->title, $this->view);
    }
}

?>