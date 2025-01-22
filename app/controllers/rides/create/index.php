<?php
    class ridesController extends Controller {
        public function rides() {
            plugin::load('response');
            $json = json_decode(file_get_contents('php://input'));
            $response = new Core\App\Response;
            $response->Send('json', $json);
        }
    }
?>