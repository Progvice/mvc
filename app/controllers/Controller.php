<?php 
class Controller {
    public $uri;
    public $classname;
    public $controller;
    public $params;
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
        
        if(class_exists($this->classname)) {
            $controller = new $this->classname;
            $controller->view = $this->view;
            $controller->title = $this->title;
            if(isset($this->params)) {
                $uri = explode('/', $_SERVER['REQUEST_URI']);
                foreach($uri as $uri_part) {
                    if(isset($this->params->$uri_part)) {
                        $param_method = $this->params->$uri_part->method;
                        $position = array_search($uri_part, $uri);
                        $position++;
                        $controller->params = array_slice($uri, $position);
                        $controller->$param_method();
                        return;
                    }
                }
            }
            $controller->$method();
        }
        else {
            header('Location: /404');
        }
    }
}