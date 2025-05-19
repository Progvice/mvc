<?php 

use Core\App\View;

class passwordresetController extends Controller {
    public function passwordreset() {
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