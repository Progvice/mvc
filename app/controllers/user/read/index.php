<?php
    class userController extends Controller {
        public function user() {
            plugin::load('response');
            $response = new Core\App\Response;
            $response->Send('json', [
                'status' => true,
                'message' => 'user is working!'
            ]);
        }
        public function userById() {
            plugin::load('response');
            $response = new Core\App\Response();
            $response->Send('json', [
                'status' => false,
                'msg' => '403 - Unauthrozied Access'
            ]);
        }
    }
?>