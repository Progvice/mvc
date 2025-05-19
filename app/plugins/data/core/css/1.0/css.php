<?php
namespace Core\App;
class Css {
    public $css;
    function __construct() {
        $this->css = json_decode(file_get_contents(JSON_PATH . '/css.json'));
        $css_links = '';
        $uri_bar = $_SERVER['REQUEST_URI'];
        foreach ($this->css->css as $css) {
            foreach ($css->global as $css_file) {
                if (filter_var($css_file, FILTER_VALIDATE_URL)) {
                    $css_links .= '        <link rel="stylesheet" href="' . $css_file . '">' . "\n";
                }
                else {
                    $css_links .= '        <link rel="stylesheet" href="/css/' . $css_file . '">' . "\n";
                }
            }
            foreach ($css->custom as $uri) {
                if (!empty($uri->$uri_bar)) {
                    foreach ($uri->$uri_bar as $css_file) {
                        if (filter_var($css_file, FILTER_VALIDATE_URL)) {
                            $css_links .= '        <link rel="stylesheet" href="' . $css_file . '">' . "\n";
                        }
                        else {
                            $css_links .= '        <link rel="stylesheet" href="/css/' . $css_file . '">' . "\n";
                        }
                    }
                }
            }
        }
        echo $css_links;
    }
}

?>