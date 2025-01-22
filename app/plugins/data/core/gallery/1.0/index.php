<?php 

namespace Core\App;

class Gallery {
    public function LoadGallery($gallery) {
        \plugin::load('models');
        $models = new Models\MainModel();
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
        \plugin::load('models');
        $models = new Models\MainModel();
        $models->CallModel('gallery');
        $galleries = $models->Select();
        return $galleries;
    }
    public function AddImg($gallery, $img) {}
    public function RemoveImg($gallery, $img) {}
}

?>