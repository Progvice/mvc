<?php 
class Controller {
    public $params_uri;
    public $base_uri;
    public $uri_array;
    public $classname;
    public $controller;
    public $params;
    public $method;
    public $view;
    public $title;
    public $disabled;
    public function cindex() {
        if($this->method === NULL || $this->method === FALSE || $this->method === '404' || $this->method === ''){
            $this->method = 'index';
        }
        $method = $this->method;
        require_once __DIR__ . '/loader.php';
        if(!file_exists(__DIR__ . $this->controller)) {
            die('This page is not available. Controller not found.');
        }
        if($this->disabled) {
            plugin::load('response');
            $request = new Core\App\Response();
            $request->Redirect('/disabled');
            return;
        }
        require __DIR__ . $this->controller;
        if(class_exists($this->classname)) {
            if(isset($this->uri_array[1])) {
                if($this->uri_array[1] === 'admin') {
                    plugin::load('auth');
                    Core\App\Auth::RefreshPerms();
                }
            }
            $controller = new $this->classname;
            $controller->params_uri = $this->params_uri;
            $controller->base_uri = $this->base_uri;
            $controller->view = $this->view;
            $controller->title = $this->title;
            if(isset($this->params)) {
                $uri = explode('/', $this->params_uri);
                foreach($uri as $uri_part) {
                    if(isset($this->params->$uri_part)) {
                        $param_method = $this->params->$uri_part->method;
                        $position = array_search($uri_part, $uri);
                        $position++;
                        $controller->params = array_slice($uri, $position);
                        if(isset($this->params->$uri_part->view)) {
                            $controller->view = substr($this->view, 0, -9) . $this->params->$uri_part->view . '.php';
                        }
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