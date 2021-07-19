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
        public function getUserByID() {
            var_dump($this->params);
            exit;
            plugin::load('response');
            $response = new Core\App\Response;
            $response->Send('json', [
                'status' => true,
                'message' => 'Parameters are working'
            ]);
        }
    }
?>