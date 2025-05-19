<?php

use Core\App\Template;
use Core\App\View;

class indexController extends Controller {
    public function index() {
        Plugin::load('view, templateloader');
        $template = new Template();
        $template->loadData('/etusivu');
        $view = new View();
        $view->variables = [
            'template' => $template,
            'uri' => $this->base_uri
        ];
        $view->index($this->view);
    }
}