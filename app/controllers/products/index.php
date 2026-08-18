<?php

use Core\App\Template;
use Core\App\View;
class ProductsController extends Controller {
    public function products() {
        Plugin::load('view, templateloader');
        $view = new View();
        $template = new Template();

        $page_content = $template->getPageData($this->base_uri);
        
        $view->variables = [
            'page_content' => $page_content
        ];

    
        $view->index($this->view);
    }
}
?>