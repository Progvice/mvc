<?php

use Core\App\Auth;
use Core\App\Template;
use Core\App\View;

class adminController extends Controller {
    public function admin() {
        Plugin::load('view, templateloader, auth');
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $view = new View;
        $view->customelements = [
            'header' => 'admin',
            'footer' => 'admin'
        ];
        $view->index($this->view);
        
    }
}

?>