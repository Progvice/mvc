<?php 

use Core\App\View;

class restorepasswordController extends Controller {
    public function restorepassword() {
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