<?php

use Core\App\Response;

class templatesController extends Controller {
    public function templates() {
        Plugin::load('response');
        $response = new Response;
        $response->Send('json', [
            'status' => true,
            'message' => 'templates is working!'
        ]);
    }
}
?>