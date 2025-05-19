<?php
use Core\App\Template;

class infobox extends Template {
    static protected $template;
    public function load($values) {
        $html = '';
        switch($values->type) {
            case 'basic': 
                $html .= $this->basic($values->data);
            break;
        }
        self::$template = $html;
        return self::$template;
    }
    public function basic($data) {
        $title = '';
        $text = '';
        $list = '';
        $classes = isset($data->classes->data) ? $data->classes->data : '';
        if (isset($data->title->data)) {
            $title = <<<EOS
                <h1>{$data->title->data}</h1>
            EOS;
        }
        if (isset($data->text->data)) {
            $text = <<<EOS
                <p>{$data->text->data}</p>
            EOS;
        }
        if (isset($data->list)) {
            if (is_array($data->list)) {
                $list .= '<ul>';
                foreach ($data->list as $value) {
                    $list .= <<<EOS
                        <li><span>{$value->title}</span><br> {$value->value}</li>
                    EOS;
                }
                $list .= '</ul>';
            }
        }
        $html = <<<EOS
            <div class="section-6 column infobox {$classes}">
                {$title}
                {$list}
                {$text}
            </div>
        EOS;
        return $html;
    }
}

?>