<?php
use Core\App\Template;
class employees extends Template {
    static protected $template;
    public function load($values) {
        $html = '';
        switch($values->type) {
            case 'employee':
                $html .= $this->employee($values->data);
            break;
            default: 
                $html .= '<h1>' . $values->type . ' is not valid template type.</h1>';
            break;
        }
        self::$template = $html;
        return self::$template;
    }
    public function employee($data) {
        $html = <<<EOS
            <div class="employee">
                <div class="employee_img">
                    <img src="{$data->img->href}" alt="{$data->img->alt}" />
                </div>
                <h2>{$data->name->data}</h2>
                <p>{$data->role->data}</p>
            </div>
        EOS;
        return $html;
    }
}
?>