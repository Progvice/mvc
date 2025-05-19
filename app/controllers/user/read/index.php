<?php

use Core\App\Response;

class userController extends Controller {
    public function user() {
        Plugin::load('response');
        $response = new Response;
        $response->Send('json', [
            'status' => true,
            'message' => 'user is working!'
        ]);
    }
    public function userById() {
        Plugin::load('response');
        $response = new Response();
        $response->Send('json', [
            'status' => false,
            'msg' => '403 - Unauthrozied Access'
        ]);
    }
}
?>