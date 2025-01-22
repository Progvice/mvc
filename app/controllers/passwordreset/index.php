<?php 

class passwordresetController extends Controller {
    public function passwordreset() {
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