<?php
    class userController extends Controller {
        public function user() {
            plugin::load('response, templateloader');
            $template = new \Core\App\Template;
            $response = new Core\App\Response;
            $response->Send('json', [
                'status' => true,
                'msg' => 'User is working!'
            ]);
        }
        public function getUserByID() {
            plugin::load('response');
            $response = new Core\App\Response;
            $response->Send('json', [
                'status' => true,
                'msg' => 'User is working!'
            ]);
        }
    }
?>