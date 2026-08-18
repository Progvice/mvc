<?php
use Core\App\View;
class TestController extends Controller {
    public function test() {
        Plugin::load('view');
        $view = new View();
        $view->variables = [
            'page_content' => 'Hello World!'
        ];
        $view->index($this->view);
    }
}
?>