<?php
    class indexController extends Controller {
        public function index() {
            plugin::load('view');
            $view = new Core\App\View;
            $view->index($this->view);
        }
    }