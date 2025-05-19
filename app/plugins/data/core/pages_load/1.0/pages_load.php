<?php
namespace Core\App\Pages;
use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;
use \stdClass;
class Load {
    public function __construct() {
        $path = realpath(REQUEST_PATH);
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
        $json_list = [];
        foreach ($objects as $json_fp){
            if (is_file($json_fp)) {
                $json = file_get_contents($json_fp);
                $json = json_decode($json);
                if (empty($json->page)){
                    continue;   
                }
                if (isset($json->page->hidden) && $json->page->hidden === true) {
                    continue;
                }
                if (isset($json->page->url)) {
                    array_push($json_list, $json->page->url);
                }
            }
        }
        $pageslist = new stdClass();
        $pageslist->pages = $json_list;
        $pageslist = json_encode($pageslist, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
        file_put_contents(JSON_PATH . '/pages.json', $pageslist);
        echo 'Pages refreshed!';
    }
}

