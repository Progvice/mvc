<?php

use Core\App\Response;

class ridesController extends Controller {
    public function rides() {
        Plugin::load('response');
        $response = new Response;
        $response->Send('json', [
            'status' => true,
            'message' => 'rides is working!'
        ]);
    }
}
?>