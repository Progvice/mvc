<?php

use Core\App;
use Core\App\Response;
use Core\App\Auth;

class Controller
{
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
    public $httpMethod;

    private function methodNotAllowed($method)
    {
        http_response_code(405);
        header('Allow: ' . $method);
        echo json_encode(['error' => "Method not allowed"]);
        exit;
    }

    public function cindex()
    {
        if ($this->method === NULL || $this->method === FALSE || $this->method === '404' || $this->method === '') {
            $this->method = 'index';
        }
        $method = $this->method;
        require_once __DIR__ . '/loader.php';
        Plugin::load('response');
        $request = new Response();

        if (!file_exists(__DIR__ . $this->controller)) {
            die('This page is not available. Controller not found.');
        }
        if ($this->disabled) {
            $request->Redirect('/disabled');
            return;
        }

        require __DIR__ . $this->controller;

        if (!class_exists($this->classname)) {
            $request->notFound();
            return;
        }


        if (isset($this->uri_array[1]) && $this->uri_array[1] === 'admin') {
            Plugin::load('auth');
            Auth::RefreshPerms();
        }

        $controller = new $this->classname;
        $controller->params_uri = $this->params_uri;
        $controller->base_uri = $this->base_uri;
        $controller->view = $this->view;
        $controller->title = $this->title;

        if (isset($this->params)) {
            $uri = explode('/', $this->params_uri);
            $count = count($uri);

            for ($i = $count - 1; $i >= 0; $i--) {
                $uri_part = $uri[$i];

                if (!isset($this->params[$uri_part])) continue;

                $param_method = $this->params[$uri_part]['method'];
                $position = $i + 1;
                $controller->params = array_slice($uri, $position);

                if (isset($this->params[$uri_part]['httpMethod']) && $this->params[$uri_part]['httpMethod'] !== $_SERVER['REQUEST_METHOD']) {
                    $this->methodNotAllowed($this->params[$uri_part]['httpMethod']);
                    return;
                }

                if (count($controller->params) < 1 && !isset($this->params[$uri_part]['allowNoParams'])) break;

                if (isset($this->params[$uri_part]['view'])) {
                    $controller->view = substr($this->view, 0, -9) . $this->params[$uri_part]['view'] . '.php';
                }
                $controller->$param_method();
                return;
            }
        }

        if ($this->httpMethod !== false && $this->httpMethod !== $_SERVER['REQUEST_METHOD']) {
            $this->methodNotAllowed($this->httpMethod);
            exit;
        }

        $controller->$method();
    }
}
