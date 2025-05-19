<?php
use Core\App\Template;
class uusitesti extends Template {
    static protected $template;
    public function load($values) {
        self::$template = <<<EOT
            <h1>uusitesti works!</h1>
        EOT;
        return self::$template;
    }
}
?>