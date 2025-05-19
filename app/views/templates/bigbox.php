<?php
use Core\App\Template;

class bigbox extends Template {
    static protected $template;
    public function load($values) {
        self::$template = <<<EOT
            <h1>bigbox works!</h1>
        EOT;
        return self::$template;
    }
}
?>