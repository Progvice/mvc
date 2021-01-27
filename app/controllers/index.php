<?php
    class indexController extends Controller {
        public function index() {
            plugin::load('view, models');
            $model = new Core\App\Models\MainModel;
            $model->CallModel('blocks');
            $data = [
                'otsikko' => 'Insert funktio',
                'kuvaus' => 'Katsotaan että toimiiko tämä insert.'
            ];
            $test = $model->Insert($data);
            $view = new Core\App\View();
            $view->variables = [
                'title' => $this->title
            ];
            $view->index($this->view);
        }
    }