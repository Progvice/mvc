        <?php
            class templatesController extends Controller {
                public function templates() {
                    plugin::load('response');
                    $response = new Core\App\Response;
                    $response->Send('json', [
                        'status' => true,
                        'message' => 'templates is working!'
                    ]);
                }
            }
        ?>