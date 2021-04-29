<?php
namespace Core\App\Template;
class header_title extends \Core\App\Template {
    protected $template;
    public function Load($values) {
        $name = CONFIG['server']['name'];
        $this->template = <<<EOT
            <div class="header_title">
                <a href="/"><h2>{$name}</h2></a>
            </div>
        EOT;
        return $this->template;
    }
}

?>