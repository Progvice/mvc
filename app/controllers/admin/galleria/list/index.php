<?php

use Core\App\Auth;
use Core\App\Response;
use Core\App\Models\MainModel;

class listController extends Controller {
    public function list() {
        Plugin::load('models, response, auth');
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $models = new MainModel();
        $models->CallModel('Galleryimg');
        $images = $models->Select();
        $response = new Response();
        $response->Send('json', [
            'status' => true,
            'data' => $images
        ]);
    }
}

?>