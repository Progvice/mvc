<?php

use Core\App\Response;

class kyyditController extends Controller {
    public function kyydit() {
        Plugin::load('response');
        $response = new Response;
        $response->Send('json', [
            'status' => true,
            'message' => 'kyydit is working!'
        ]);
    }
}

?>