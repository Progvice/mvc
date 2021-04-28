<?php
    class notfoundController extends Controller {
        public function notfound (){
            plugin::load('view');
            $view = new Core\App\View;
            $view->index($this->view);
        }
    }
?>