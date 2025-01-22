<?php

use Core\App\Auth;

class templatesController extends Controller {
    public function templates() {
        plugin::load('response, auth');
        $response = new Core\App\Response;
        if(!Auth::CheckPerm('pages_update')) {
            $response->Send('json', [
                'status' => 'error',
                'msg' => 'Ei oikeuksia päivittää sivua'
            ]);
            return;
        }
        $data = file_get_contents("php://input");
        $data_json = json_decode($data);
        $final_data = json_encode($data_json->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents(DATA_PATH . $data_json->url . '/index.json', $final_data);
        $response = new Core\App\Response;
        $response->Send('json', [
            'status' => 'success',
            'msg' => 'Template was updated!'
        ]);
    }
}
?>