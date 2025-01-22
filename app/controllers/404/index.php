<?php
    class notfoundController extends Controller {
        public function notfound (){
            plugin::load('view');
            http_response_code(404);
            $view = new Core\App\View;
            $view->index($this->view);
        }
    }
?>