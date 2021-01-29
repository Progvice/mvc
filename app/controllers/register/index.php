<?php
class registerController extends Controller {
    public function register() {
        plugin::load('view');
        $view = new Core\App\View();
        $view->variables = [
            'title' => $this->title
        ];
        $view->index($this->view);
    }
}
?>