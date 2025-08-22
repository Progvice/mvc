<?php

use Core\App\Template;
use Core\App\View;

class indexController extends Controller
{
    public function index()
    {
        Plugin::load('view, templateloader');
        $view = new View();

        $personels = json_decode(file_get_contents('http://localhost/personel/read'), true);

        $view->variables = [
            'uri' => $this->base_uri,
            'personnels' => $personels['data'] ?? []
        ];
        $view->index($this->view);
    }
}
