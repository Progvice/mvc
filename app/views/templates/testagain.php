<?php
namespace Core\App\Template;
class testagain extends \Core\App\Template {
    static protected $template;
    public function Load($values) {
        self::$template = <<<EOT
            <h1>testagain works!</h1>
        EOT;
        return self::$template;
    }
}
?>