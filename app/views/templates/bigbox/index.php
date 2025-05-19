<?php
use Core\App\Template;
class BigBox extends Template {
    static protected $template;
    public function load($values) {
        $html = '';
        switch($values->type) {
            case 'basic': 
                $html .= $this->basic($values->data);
            break;
            case 'img':
                $html .= $this->img($values->data);
            break;
        }
        self::$template = $html;
        return self::$template;
    }
    private function basic($data) {
        $img = '';
        $title = '';
        $desc = '';
        if (isset($data->img->data, $data->img->src)) {
            $img .= <<<EOS
                <img src="{$data->img->src}" alt="{$data->img->data}" class="logo"/> 
            EOS;
        }
        if (isset($data->title->data)) {
            $title .= <<<EOS
                <h1 class="bb_title">{$data->title->data}</h1>
            EOS;
        }
        if (isset($data->desc->data)) {
            $desc .= <<<EOS
                <p class="bb_desc">{$data->desc->data}</p>
            EOS;
        }
        $html = <<<EOS
            <div class="section-12 bgimg">
                <div class="bgimg_content darkbg column">
                    {$img}
                    {$title}
                    {$desc}
                </div>
            </div>
        EOS;
        return $html;
    }
    private function img($data) {
        $bgimg = '';
        $img = '';
        $title = '';
        $desc = '';
        $stringarr = explode('___', $data->title->data);
        $final_string = '';
        if (count($stringarr) > 1) {
            for($counter = 0; $counter < count($stringarr); $counter++) {
                if ($counter % 2 !== 0) {
                    $final_string .= '<span class="yb_highlight">' . $stringarr[$counter] . '</span>';
                    continue;
                }
                $final_string .= $stringarr[$counter];
            }
        }
        else {
            $final_string = $data->title->data;
        }


        if (isset($data->img->alt, $data->img->href)) {
            $img .= <<<EOS
                <img src="{$data->img->href}" alt="{$data->img->alt}" class="logo"/> 
            EOS;
        }
        if (isset($data->title->data)) {
            $title .= <<<EOS
                <h1 class="">{$final_string}</h1>
            EOS;
        }
        if (isset($data->bgimg->href, $data->bgimg->alt)) {
            $bgimg .= <<<EOS
                <img src="{$data->bgimg->href}" alt="{$data->bgimg->alt}" class="bgimg_img"/>
            EOS;
        }
        if (isset($data->desc->data)) {
            $desc .= <<<EOS
                <p class="bb_desc">{$data->desc->data}</p>
            EOS;
        }
        $html = <<<EOS
            <div class="section-12 bgimg">
                <div class="bgimg_images">
                    {$bgimg}
                </div>
                <div class="bgimg_content column">
                    {$img}
                    {$title}
                    {$desc}
                </div>
            </div>
        EOS;
        return $html;
    }
}
?>