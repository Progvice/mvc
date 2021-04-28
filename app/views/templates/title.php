<?php
namespace Core\App\Template;
class title extends \Core\App\Template {
    protected $template;
    public function Load($values) {
        $this->template = <<<EOT
            <h1></h1>
        EOT;
        return $this->template;
    }
}
?>