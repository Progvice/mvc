<?php
use Core\App\Template;
class header_logo extends Template {
    protected $template;
    public function load($values) {
        $this->template = <<<EOT
            <div class="hlogo">
                <img src="/img/logo.png" alt="#"/>
            </div>
        EOT;
        return $this->template;
    }
}

?>