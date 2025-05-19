<?php
use Core\App\Template;
class testagain extends Template {
    static protected $template;
    public function load($values) {
        self::$template = <<<EOT
            <h1>testagain works!</h1>
        EOT;
        return self::$template;
    }
}
?>