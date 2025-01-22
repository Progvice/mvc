<?php 

class muutController extends Controller {
    protected function muut() {
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