<?php

use Core\App\Response;

class appointmentsController extends Controller {
    public function appointments() {
        Plugin::load('response');
        $response = new Response;
        $response->Send('json', [
            'status' => true,
            'message' => 'appointments is working!'
        ]);
    }
}

?>