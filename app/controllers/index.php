<?php
    class indexController extends Controller {
        public function index() {
            plugin::load('view, models');
            $model = new Core\App\Models\MainModel;
            $model->CallModel('user');
            $data = [
                'otsikko' => 'Insert funktio',
                'kuvaus' => 'Katsotaan että toimiiko tämä insert.'
            ];
            $view = new Core\App\View();
            $view->variables = [
                'title' => $this->title
            ];
            $view->index($this->view);
        }
    }