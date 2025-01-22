<?php 

class disabledController extends Controller {
    public function disabled() {
        plugin::load('view');
        $view = new Core\App\View();
        $view->index($this->view);
    }
}

?>