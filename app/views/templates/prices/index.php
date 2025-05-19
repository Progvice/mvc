<?php
use Core\App\Template;

class prices extends Template {
    static protected $template;
    public function load($values) {
        $html = '';
        switch($values->type) {
            case 'price':
                $html .= $this->price($values->data);
            break;
        }
        self::$template = $html;
        return self::$template;
    }
    public function price($data) {
        $title = isset($data->title->data) ? $data->title->data : '';
        $html = <<<EOS
        <div class="price_section detailbox_container">
            <h1>{$title}</h1>
            <table class="pricetable">
            <tr>
                <th>Palvelu</th>
                <th>Hinta</th>
                <th>Lisätieto</th>
            </tr>
        EOS;
        if (isset($data->price) && is_array($data->price)) {
            foreach ($data->price as $price) {
                $name = isset($price->name) && !empty($price->name) ? $price->name : '';
                $desc = isset($price->desc) && !empty($price->desc) ? $price->desc : '';
                $price = isset($price->value) && !empty($price->value) ? '<span class="pricetag">' . $price->value . '</span>' : '';
                $html .= <<<EOS
                    <tr>
                        <td>{$name}</td>
                        <td class="pricecol">{$price}</td>
                        <td>{$desc}</td>
                    </tr>
                EOS;
            }
        }
        $html .= '</table></div>';
        return $html;
    }
}
?>