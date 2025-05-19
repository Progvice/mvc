<?php
use Core\App\Template;
class header_title extends Template {
    protected $template;
    public function load($values) {
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