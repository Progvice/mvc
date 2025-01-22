        <?php
            class ridesController extends Controller {
                public function rides() {
                    plugin::load('response');
                    $response = new Core\App\Response;
                    $response->Send('json', [
                        'status' => true,
                        'message' => 'rides is working!'
                    ]);
                }
            }
        ?>