<?php 
class yhteystiedotController extends Controller {
    public function yhteystiedot() {
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