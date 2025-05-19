<?php
    class indexController extends Controller {
        public function index() {
            plugin::load('view, templateloader');
            $template = new \Core\App\Template();
            $template->loadData('/etusivu');
            $view = new Core\App\View;
            $view->variables = [
                'templateData' => $template,
                'uri' => $this->base_uri
            ];
            $view->index($this->view);
        }
    }