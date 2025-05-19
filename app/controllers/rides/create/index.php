<?php

use Core\App\Response;

class ridesController extends Controller {
    public function rides() {
        Plugin::load('response');
        $json = json_decode(file_get_contents('php://input'));
        $response = new Response;
        $response->Send('json', $json);
    }
}
?>