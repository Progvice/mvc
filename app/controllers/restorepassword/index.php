<?php 

class restorepasswordController extends Controller {
    public function restorepassword() {
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