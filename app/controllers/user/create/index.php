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
    }
?>