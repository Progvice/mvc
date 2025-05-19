<?php
use Core\App\Template;
class title extends Template {
    protected $template;
    public function load($values) {
        $this->template = <<<EOT
            <h1></h1>
        EOT;
        return $this->template;
    }
}
?>