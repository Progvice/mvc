<?php
    class indexController extends Controller {
        public function index() {
            plugin::load('view, templateloader');
            $template = new \Core\App\Template();
            $data = $template->LoadData('/etusivu');
            $view = new Core\App\View;
            $view->variables = [
                'templatedata' => $data,
                'uri' => $this->base_uri
            ];
            $view->index($this->view);
        }
    }