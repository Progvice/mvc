<?php
use Core\App\Template;
class Repetitive extends Template {
    static protected $template;
    private function CreateButton($buttondata) {
        
        if(!$buttondata['link']) {
            $button = '<!-- Button is missing link -->';
        }
        else if(!$buttondata['text']) {
            $button = '<!-- Button is missing text -->';
        }
        else {
            $button = <<<EOT
            <a href="{$buttondata['link']}" target="_blank" class="button green">
                {$buttondata['text']}
            </a>
            EOT;
        }

        return $button;
    }
    public function load($values) {
        $button = '';
        if(isset($values['button'])) {
            $button = $this->CreateButton($values['button']);
        }
        self::$template = <<<EOT
            <div class="{$values['height']} {$values['width']} column">
                <h3>{$values['title']}</h3>
                <p>{$values['desc']}</p>
                {$button}
            </div>
        EOT;
        return self::$template;
    }
}
?>