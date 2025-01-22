<?php

use Core\App\Auth;

class galleriaController extends Controller {
    public function galleria() {
        plugin::load('view, gallery, auth');
        if(!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $gallery = new Core\App\Gallery();
        $gallery_test = $gallery->ListGalleries();
        $view = new Core\App\View();
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
        plugin::load('view, models, auth');
        if(!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $view = new Core\App\View;
        $view->customelements = [
            'header' => 'admin',
            'footer' => 'admin'
        ];
        $gallery_name = urldecode($this->params[0]);
        $models = new Core\App\Models\MainModel();
        // Check if gallery exists
        $models->CallModel('gallery');
        $gallerycheck = $models->Select([
            'values' => [
                'normal' => [
                    'gname' => $gallery_name
                ]
            ]
        ]);
        if(count($gallerycheck) < 1) {
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
        if(count($images) < 1) {
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
        plugin::load('models, response, auth');
        $response = new Core\App\Response();
        if($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['invalidmethod']
            ]);
            return;
        }
        if(!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $models = new Core\App\Models\MainModel();
        $models->CallModel('galleryimg');
        $imgdata = $models->Select([
            'values' => [
                'normal' => [
                    'uuid' => $this->params[0]
                ]
            ]
        ]);
        if(count($imgdata) < 1) {
            $response->Send('json', [
                'status' => false,
                'msg' => LANG['gallery']['imgnotexist']
            ]);
            return;
        }
        if(file_exists(APP_PATH . '/../public' . $imgdata[0]['imgpath'])) {
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
        plugin::load('view, auth');
        if(!Auth::CheckPerm('admin_access')) {
            header('Location: /404');
            return;
        }
        $view = new Core\App\View();
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