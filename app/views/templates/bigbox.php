<?php
namespace Core\App\Template;
class bigbox extends \Core\App\Template {
    static protected $template;
    public function Load($values) {
        self::$template = <<<EOT
            <h1>bigbox works!</h1>
        EOT;
        return self::$template;
    }
}
?>