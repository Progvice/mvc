<?php
    class indexController extends Controller {
        public function index() {
            plugin::load('view');
            $view = new Core\App\View;
            $view->variables = [
                'products' => [
                    [
                        'title' => 'Welcome',
                        'desc' => 'This is JJMVC installation.',
                        'width' => 'section-6',
                        'height' => 'height-50'
                    ],
                    [
                        'title' => 'Documentation',
                        'desc' => 'You can find our documentation in Github',
                        'width' => 'section-4',
                        'height' => 'height-50',
                        'button' => [
                            'link' => 'https://github.com/Progvice/mvc',
                            'text' => 'Github'
                        ]
                    ]
                ]
            ];
            $view->index($this->view);
        }
    }