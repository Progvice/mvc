<?php

class configurationController extends Controller {
    public function configuration() {
        plugin::load('core:config:1.0, core:view:1.0');
        $view = new Core\App\View;
        $view->customelements = ['header' => 'admin'];
        $view->index($this->title, $this->view);
        $config = new Core\App\Config;
        $config->list_settings();
        $config->save();
    }
}

?>