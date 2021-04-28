<?php
namespace Core\App\Template;
class uusitesti extends \Core\App\Template {
    static protected $template;
    public function Load($values) {
        self::$template = <<<EOT
            <h1>uusitesti works!</h1>
        EOT;
        return self::$template;
    }
}
?>