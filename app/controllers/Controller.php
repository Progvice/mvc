<?php 
class Controller {
    public $uri;
    public $classname;
    public $controller;
    public $method;
    public $view;
    public $title;
    public function cindex() {
        if($this->method === NULL || $this->method === FALSE || $this->method === '404' || $this->method === ''){
            $this->method = 'index';
        }
        $method = $this->method;
        require_once __DIR__ . '/loader.php';
        if(!file_exists(__DIR__ . $this->controller)) {
            die('This page is not available. Controller not found.');
        }
        require __DIR__ . $this->controller;
        plugin::load('csrf', true);
        Core\App\Csrf::Generate();
        if(class_exists($this->classname)) {
            $controller = new $this->classname;
            $controller->view = $this->view;
            $controller->title = $this->title;
            $controller->uri = $this->uri;
            $controller->$method();
        }
        else {
            header('Location: /404');
        }
    }
}