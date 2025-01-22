<?php 

use Core\App\Auth;
use Core\App\View;

class forbiddenController extends Controller {
    public function forbidden() {
        plugin::load('view, auth');
        if(!Auth::CheckPerm('admin_access')) {
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