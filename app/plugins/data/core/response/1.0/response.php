<?php

namespace Core\App;

use Core\App\View;
use \Plugin;

class Response
{
    public function Send($type, $data = '')
    {
        switch ($type) {
            case 'json':
                $this->Json($data);
                break;
            case 'text':
                $this->Text($data);
                break;
            case 404:
                $this->notFound();
                break;
            default:
                return 'No proper response type is set';
                break;
        }
    }
    public function SetCode($code)
    {
        http_response_code($code);
    }
    public function Json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data, true);
    }
    public function Text($data)
    {
        header('Content-Type: text/plain');
        if (gettype($data) === 'string') {
            echo $data;
        } else {
            echo 'Invalid text input.';
        }
    }
    public function Html($data)
    {
        header('Content-Type: text/html');
        echo $data;
    }
    public function notFound()
    {
        Plugin::load('view');
        header('HTTP/1.0 404 Not Found');
        header('Content-Type: application/json');
        $view = new View();
        $view->index(VIEW_PATH . '/404/index.php');
    }
    public function Redirect($path)
    {
        header('Location: ' . $path);
    }
}
