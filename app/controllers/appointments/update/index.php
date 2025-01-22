<?php
    class appointmentsController extends Controller {
        public function appointments() {
            plugin::load('response');
            $response = new Core\App\Response;
            $response->Send('json', [
                'status' => true,
                'message' => 'appointments is working!'
            ]);
        }
    }
?>