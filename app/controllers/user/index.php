<?php

use Core\App\Response;

class userController extends Controller {
    public function user() {
        Plugin::load('response, templateloader');
        $template = new \Core\App\Template;
        $response = new Response;
        $response->Send('json', [
            'status' => true,
            'msg' => 'User is working!'
        ]);
    }
    public function getUserByID() {
        Plugin::load('response');
        $response = new Response;
        $response->Send('json', [
            'status' => true,
            'msg' => 'User is working!'
        ]);
    }
}
?>