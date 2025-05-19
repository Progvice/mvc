<?php

use Core\App\Auth;
use Core\App\View;
use Core\App\Models\MainModel;
use Core\App\Response;

class galleriaController extends Controller {
    public function galleria() {
        Plugin::load('view, gallery, auth');
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $gallery = new Core\App\Gallery();
        $gallery_test = $gallery->ListGalleries();
        $view = new View();
        $view->customelements = [
            'header' => 'admin',
            'footer' => 'admin'
        ];
        $view->variables = [
            'gallery' => $gallery_test
        ];
        $view->index($this->view);
    }
    public function viewGallery() {
        Plugin::load('view, models, auth');
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $view = new View;
        $view->customelements = [
            'header' => 'admin',
            'footer' => 'admin'
        ];
        $gallery_name = urldecode($this->params[0]);
        $models = new MainModel();
        // Check if gallery exists
        $models->CallModel('gallery');
        $gallerycheck = $models->Select([
            'values' => [
                'normal' => [
                    'gname' => $gallery_name
                ]
            ]
        ]);
        if (count($gallerycheck) < 1) {
            $view->variables = [
                'status' => false,
                'gexists' => false,
                'error' => 'Galleriaa ei ole olemassa'
            ];
            $view->index($this->view);
            return;
        }
        $models->CallModel('galleryimg');
        $images = $models->Select([
            'values' => [
                'normal' => [
                    'gname' => $gallery_name
                ]
            ]
        ]);
        if (count($images) < 1) {
            $view->variables = [
                'status' => false,
                'gexists' => true,
                'guuid' => $this->params[0],
                'error' => 'Galleria on tyhjä'
            ];
            $view->index($this->view);
            return;
        }
        $view->variables = [
            'status' => true,
            'gexists' => true,
            'guuid' => $this->params[0],
            'images' => $images
        ];
        $view->index($this->view);
    }
    public function removeImage() {
        Plugin::load('models, response, auth');
        $response = new Response();
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['invalidmethod']
            ]);
            return;
        }
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $models = new MainModel();
        $models->CallModel('galleryimg');
        $imgdata = $models->Select([
            'values' => [
                'normal' => [
                    'uuid' => $this->params[0]
                ]
            ]
        ]);
        if (count($imgdata) < 1) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['gallery']['imgnotexist']
            ]);
            return;
        }
        if (file_exists(APP_PATH . '/../public' . $imgdata[0]['imgpath'])) {
            unlink(APP_PATH . '/../public' . $imgdata[0]['imgpath']);
        }
        $deleteImage = $models->Delete([
            'where' => [
                'uuid' => $this->params[0]
            ]
        ]);
        $response->Send('json', [
            'status' => true,
            'msg' => LANG['gallery']['imageremoved']
        ]);

    }
    public function addImage() {
        Plugin::load('view, auth');
        if (!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $view = new View();
        $view->customelements = [
            'header' => 'admin',
            'footer' => 'admin'
        ];
        $view->variables = [
            'params' => $this->params
        ];
        $view->index($this->view);
    }
    public function createGallery() {

    }
    public function handleCreateGallery() {
        
    }
}

?>