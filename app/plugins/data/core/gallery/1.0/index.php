<?php 

namespace Core\App;
use Core\App\Models\MainModel;

class Gallery {
    public function LoadGallery($gallery) {
        \Plugin::load('models');
        $models = new MainModel();
        $models->CallModel('galleryimg');
        $gallery = $models->Select([
            'values' => [
                'normal' => [
                    'gname' => $gallery
                ]
            ]
        ]);
        return $gallery;
    }
    public function ListGalleries() {
        \Plugin::load('models');
        $models = new MainModel();
        $models->CallModel('gallery');
        $galleries = $models->Select();
        return $galleries;
    }
    public function AddImg($gallery, $img) {}
    public function RemoveImg($gallery, $img) {}
}

?>