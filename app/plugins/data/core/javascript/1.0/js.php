<?php
namespace Core\App;
class Js {
    public $js;
    public $test;
    function __construct() {
        $json_object = file_get_contents(JSON_PATH . '/js.json');
        $json_object = json_decode($json_object);
        $this->js = $json_object;
        $js_scripts = '';
        $uri_bar = $_SERVER['REQUEST_URI'];
        foreach ($this->js->js as $js) {
            foreach ($js->global as $js_file) {
                if (isset($js_file->normal)) {
                    foreach ($js_file->normal as $file) {
                        if (filter_var($file, FILTER_VALIDATE_URL)) {
                            $js_scripts .= '        <script src="' . $file . '" type="text/javascript"></script>' . "\n";
                        }
                        else {
                            $js_scripts .= '        <script src="/js/' . $file . '" type="text/javascript"></script>' . "\n";
                        }
                    }
                }
                else if (isset($js_file->async)) {
                    foreach ($js_file->async as $file) {
                        if (filter_var($file, FILTER_VALIDATE_URL)) {
                            $js_scripts .= '        <script src="' . $file . '" type="text/javascript" async></script>' . "\n";
                        }
                        else {
                            $js_scripts .= '        <script src="/js/' . $file . '" type="text/javascript" async></script>' . "\n";
                        }
                    }
                }
                else if (isset($js_file->defer)) {
                    foreach ($js_file->defer as $file) {
                        if (filter_var($file, FILTER_VALIDATE_URL)) {
                            $js_scripts .= '        <script src="' . $file . '" type="text/javascript"></script>' . "\n";
                        }
                        else {
                            $js_scripts .= '        <script src="/js/' . $file . '" type="text/javascript"></script>' . "\n";
                        }
                    }
                }
                else if (isset($js_file->asfer)) {
                    foreach ($js_file->asfer as $file) {
                        if (filter_var($file, FILTER_VALIDATE_URL)) {
                            $js_scripts .= '        <script src="' . $file . '" type="text/javascript" async defer></script>' . "\n";
                        }
                        else {
                            $js_scripts .= '        <script src="/js/' . $file . '" type="text/javascript" async defer></script>' . "\n";
                        }
                    }
                }
            }
            foreach ($js->custom as $uri) {
                if (!empty($uri->$uri_bar)) {
                    foreach ($uri->$uri_bar as $js_file) {
                        if (isset($js_file->normal)) {
                            foreach ($js_file->normal as $file) {
                                if (filter_var($file, FILTER_VALIDATE_URL)) {
                                    $js_scripts .= '        <script src="' . $file . '" type="text/javascript"></script>' . "\n";
                                }
                                else {
                                    $js_scripts .= '        <script src="/js/' . $file . '" type="text/javascript"></script>' . "\n";
                                }
                            }
                        }
                        else if (isset($js_file->async)) {
                            foreach ($js_file->async as $file) {
                                if (filter_var($file, FILTER_VALIDATE_URL)) {
                                    $js_scripts .= '        <script src="' . $file . '" type="text/javascript" async></script>' . "\n";
                                }
                                else {
                                    $js_scripts .= '        <script src="/js/' . $file . '" type="text/javascript" async></script>' . "\n";
                                }
                            }
                        }
                        else if (isset($js_file->defer)) {
                            foreach ($js_file->defer as $file) {
                                if (filter_var($file, FILTER_VALIDATE_URL)) {
                                    $js_scripts .= '        <script src="' . $file . '" type="text/javascript"></script>' . "\n";
                                }
                                else {
                                    $js_scripts .= '        <script src="/js/' . $file . '" type="text/javascript"></script>' . "\n";
                                }
                            }
                        }
                        else if (isset($js_file->asfer)) {
                            foreach ($js_file->asfer as $file) {
                                if (filter_var($file, FILTER_VALIDATE_URL)) {
                                    $js_scripts .= '        <script src="' . $file . '" type="text/javascript" async defer></script>' . "\n";
                                }
                                else {
                                    $js_scripts .= '        <script src="/js/' . $file . '" type="text/javascript" async defer></script>' . "\n";
                                }
                            }
                        }
                    }
                }
            }
        }
        echo $js_scripts;
    }
}

?>