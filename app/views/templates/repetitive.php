<?php
namespace Core\App\Template;
class repetitive extends \Core\App\Template {
    static protected $template;
    public function Load($values) {
        self::$template = <<<EOT
            <div class="section-4 height-50">
                <h3>{$values['title']}</h3>
                <p>{$values['desc']}</p>
            </div>
        EOT;
        return self::$template;
    }
}
?>