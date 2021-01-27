<?php
class notfoundController extends Controller {
    public function index() {
        plugin::load('core:view:1.0');
        $view = new Core\App\View;
        $view->index($this->view);
        if(!headers_sent()) {
            header("HTTP/1.0 404 Not Found");
        }
    }
}

