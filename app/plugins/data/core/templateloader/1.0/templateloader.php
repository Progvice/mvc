<?php
namespace Core\App;
class Template {
    /*
     *  @method LoadData
     * 
     *  @param1 $uri    string
     *  @param2 $template_name  string
     * 
     *  Purpose of this function is to load data for URI. Data is stored 
     *  in its own JSON file. This data gets processed through this function
     *  so that we can inject it to template
     * 
     * 
     */

    public $blocks;
    public static $styles = '';

    public function loadData($uri) {
        $template_link = DATA_PATH . $uri . '/index.json';
        $template = json_decode(file_get_contents($template_link));
        $datalist = [];
        foreach ($template as $block_name => $data) {
            if (!file_exists(TEMPLATE_PATH . $data->template . '/index.php')) {
                continue;
            }
            $name = $data->template;

            if (!class_exists($name)) {
                require TEMPLATE_PATH . $data->template . '/index.php';
            }
            foreach ($data->data as $block) {
                $class = new $name;
                if (!array_key_exists($block_name, $datalist)) {
                    $datalist[$block_name] = $class->load($block);
                    continue;
                }
                $datalist[$block_name] .= $class->load($block);
            }
        }
        $this->blocks = $datalist;
    }

    /*
     *  @method Load
     *
     *  @param  $template   array   -   Template data
     *          
     *          @Array keys
     *              
     *              *** = Required
     *              
     *              name    -   Template name - String ***
     *              data    -   Store data in to this variable from database or from controller and send it to template. - Mixed (array||string)
     *          
     *              
     *  
     */
    public function load($template) {

        $name = $template['name'];

        if (!file_exists(VIEW_PATH . '/../templates/' . $name . '/' . 'index.php')) {
            echo 'Template does not exist.!';
            return;
        }

        if (!class_exists($name)) {
            require VIEW_PATH . '/../templates/'. $name . '/' . 'index.php';
        }
        $class = new $name;
        $final_template = $class->load(isset($template['data']) ? $template['data'] : []);
        return $final_template;
    }

    public function getBlock($blockName) {
        if (isset($this->blocks[$blockName])) return $this->blocks[$blockName];
        return $blockName . ' is not defined';
    }

    public function collectStyle($dir) {
        if (!file_exists($dir . '/style.css')) return;
        $styles = file_get_contents($dir . '/style.css');
        self::$styles = self::$styles . "\n\n" . $styles;
    }
}

?>