<?php 

class hinnastoController extends Controller {
    public function hinnasto() {
        plugin::load('view, templateloader');
        $template = new Core\App\Template();
        $template->loadData($this->base_uri);

        $view = new Core\App\View();
        $view->variables = [
            'templateData' => $template
        ];
        $view->index($this->view);
    }
}

?>