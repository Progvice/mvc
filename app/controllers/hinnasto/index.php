<?php 

class hinnastoController extends Controller {
    public function hinnasto() {
        plugin::load('view, templateloader');
        $template = new Core\App\Template();
        $data = $template->LoadData($this->base_uri);
        $view = new Core\App\View();
        $view->variables = [
            'templatedata' => $data
        ];
        $view->index($this->view);
    }
}

?>