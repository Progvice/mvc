<?php
namespace Core\App\Template;
class title extends \Core\App\Template {
    static protected $template;
    public function Load($values) {
        self::$template = <<<EOT
            <h1></h1>
        EOT;
        return self::$template;
    }
}
?>