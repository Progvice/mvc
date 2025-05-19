<?php
use Core\App\Template;
class GalleryAdmin extends Template {
    static protected $template;
    public function load($values) {
        $filename = isset($values['fname']) ? ' - ' . $values['fname'] : '';
        $fullnameHTML = isset($values['imgname']) ? '<h5>' . $values['imgname'] . $filename . '</h5>' : '';
        $descriptionHTML = isset($values['description']) ? '<p>' . $values['description'] . '</p>' : ''; 
        $description = isset($values['description']) ? $values['description'] : 'no description';
        self::$template = <<<EOT
            <div class="galleryimg">
                <div class="gi_img"><img src="{$values['imgpath']}" alt="{$description}"></div>
                {$fullnameHTML}
                {$descriptionHTML}
                <div class="gi_actions">
                    <a class="deleteimg" href="/admin/galleria/deleteimg/{$values['uuid']}">Poista kuva</a>
                </div>
            </div>
        EOT;
        return self::$template;
    }
}
?>