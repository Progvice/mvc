<?php 

use Core\App\Template;
use Core\App\View;

class yhteystiedotController extends Controller {
    public function yhteystiedot() {
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