<?php

use Core\App\View;

class notfoundController extends Controller {
    public function notfound() {
        Plugin::load('view');
        http_response_code(404);
        $view = new View;
        $view->index($this->view);
    }
}
?>