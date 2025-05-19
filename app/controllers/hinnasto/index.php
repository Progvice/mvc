<?php 

use Core\App\View;
use Core\App\Template;

class hinnastoController extends Controller {
    public function hinnasto() {
        Plugin::load('view, templateloader');
        $template = new Template();
        $template->loadData($this->base_uri);

        $view = new View();
        $view->variables = [
            'template' => $template
        ];
        $view->index($this->view);
    }
}

?>