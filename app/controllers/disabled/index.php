<?php 

use Core\App\View;

class disabledController extends Controller {
    public function disabled() {
        Plugin::load('view');
        $view = new View();
        $view->index($this->view);
    }
}

?>