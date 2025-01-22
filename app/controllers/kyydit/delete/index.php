        <?php
            class kyyditController extends Controller {
                public function kyydit() {
                    plugin::load('response');
                    $response = new Core\App\Response;
                    $response->Send('json', [
                        'status' => true,
                        'message' => 'kyydit is working!'
                    ]);
                }
            }
        ?>