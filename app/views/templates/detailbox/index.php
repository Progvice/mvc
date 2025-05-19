<?php
use Core\App\Template;
class Detailbox extends Template {
    static protected $template;
    public function load($values) {
        if (!isset($values->type)) {
            return '';
        }
        $html = '';
        switch($values->type) {
            case 'prices':
                $html .= $this->prices($values->data);
            break;
            case 'basic':
                $html .= $this->basic($values->data);
            break;
            case 'basic_w_img':
                $html .= $this->basic_w_img($values->data);
            break;
        }
        self::$template = $html;
        return self::$template;
    }
    public function prices($data) {
        $prices = '<div class="section-12 fpage_price_root wrap row">';
        $icon = '';
        $button = '';
        $classes = isset($data->classes->data) ? $data->classes->data : 'detailbox';
        if (isset($data->prices)) {
            foreach ($data->prices as $price) {
                if (isset($price->title, $price->price)) {
                    $prices .= <<<EOS
                    <div class="column fpage_prices">
                        <h5 class="fpage_prices_title">{$price->title}</h5>
                        <p class="fpage_prices_price">{$price->price}</p>
                    </div>
                    EOS;
                }
            }
            $prices .= '</div>';
        }
        if (isset($data->icon)) {
            if (isset($data->icon->data)) {
                $icon = <<<EOS
                <div class="dbox_icon">
                    <i class="{$data->icon->data}"></i>
                </div>
                EOS;
            }
        }
        if (isset($data->button)) {
            if (isset($data->button->data, $data->button->href)) {
                $button = <<<EOS
                    <a href="{$data->button->href}" class="button yellowborder">{$data->button->data}</a>
                EOS;
            }
        }
        $html = <<<EOS
            <div class="{$classes}">
                {$icon}
                {$prices}
                {$button}
            </div>
        EOS;
        return $html;
    }
    public function basic($data) {
        $icon = '';
        $title = '';
        $content = '';
        $button = '';
        $classes = isset($data->classes->data) ? $data->classes->data : 'detailbox';

        if (isset($data->icon->data)) {
            $icon = <<<EOS
            <div class="dbox_icon">
                <i class="{$data->icon->data}"></i>
            </div>
            EOS;
        }
        if (isset($data->title->data)) {
            $title = '<h2 class="yellow_hl">' . $data->title->data . '</h2>';
        }
        if (isset($data->content->data)) {
            $content = '<p>' . $data->content->data .'</p>';
        }
        if (isset($data->button->data, $data->button->href)) {
            $button = '<a href="' . $data->button->href . '" class="button yellowborder">' . $data->button->data . '</a>';
        }
        $html = <<<EOS
        <div class="{$classes}">
            {$icon}
            {$title}
            {$content}
            {$button}
        </div>
        EOS;
        return $html;
    }
    public function basic_w_img($data) {
        $title = '';
        $content = '';
        $button = '';
        $bgimgs = '';
        $classes = isset($data->classes->data) ? $data->classes->data : 'detailbox';

        if (isset($data->title->data)) {
            $title = '<h2 class="yellow_hl">' . $data->title->data . '</h2>';
        }
        if (isset($data->content->data)) {
            $content = '<p>' . $data->content->data . '</p>';
        }
        if (isset($data->button->data)) {
            $button = '<a href="' . $data->button->href . '" class="button yellowborder">' . $data->button->data . '</a>';
        }
        $bgcontent = <<<EOS
        <div class="bgimg_content column">
            {$title}
            {$content}
            {$button}
        </div>
        EOS;
        if (isset($data->img->href, $data->img->alt)) {
            $bgimgs = <<<EOS
            <div class="bgimg_images">
                <img class="bgimg_img" src="{$data->img->href}" alt="{$data->img->alt}"/>
            </div>
            EOS;
        }
        $html = <<<EOS
        <div class="{$classes}">
            {$bgcontent}
            {$bgimgs}
        </div>
        EOS;
        return $html;
    }
}
?>