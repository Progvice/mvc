<?php
namespace Core\App\Template;
class header_logo extends \Core\App\Template {
    static protected $template;
    public function Load($values) {
        self::$template = <<<EOT
            <div class="hlogo">
                <img src="/img/logo.png" alt="#"/>
            </div>
        EOT;
        return self::$template;
    }
}

?>