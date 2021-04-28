<?php
namespace Core\App\Template;
class header_logo extends \Core\App\Template {
    protected $template;
    public function Load($values) {
        $this->template = <<<EOT
            <div class="hlogo">
                <img src="/img/logo.png" alt="#"/>
            </div>
        EOT;
        return $this->template;
    }
}

?>