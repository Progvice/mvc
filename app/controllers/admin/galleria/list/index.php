<?php

use Core\App\Auth;

class listController extends Controller {
    public function list() {
        plugin::load('models, response, auth');
        if(!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $models = new Core\App\Models\MainModel();
        $models->CallModel('Galleryimg');
        $images = $models->Select();
        $response = new Core\App\Response();
        $response->Send('json', [
            'status' => true,
            'data' => $images
        ]);
    }
}

?>