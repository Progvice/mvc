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
     *  so that we can load 
     * 
     * 
     */
    public function LoadData($uri) {
        $template_link = DATA_PATH . $uri . '/index.json';
        $template = json_decode(file_get_contents($template_link));
        $datalist = [];
        foreach($template as $block_name => $data) {
            if(!file_exists(TEMPLATE_PATH . $data->template . '/index.php')) {
                echo 'Template does not exist';
                continue;
            }
            $name = '\Core\App\Template\\' . $data->template;
            if(!class_exists($name)) {
                require TEMPLATE_PATH . $data->template . '/index.php';
            }
            foreach($data->data as $block) {
                $class = new $name;
                if(!array_key_exists($block_name, $datalist)) {
                    $datalist[$block_name] = $class->Load($block);
                    continue;
                }
                $datalist[$block_name] .= $class->Load($block);
            }
        }
        return $datalist;
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
    public function Load($templatedata) {

        if(!file_exists(VIEW_PATH . '/../templates/' . $templatedata['name'] . '.php')) {
            echo 'Template does not exist.!';
            return;
        }
        if(empty($templatedata['data'])) {
            $templatedata['data'] = [];
        }
        $name = '\Core\App\Template\\' . $templatedata['name'];
        if(!class_exists($name)) {
            require VIEW_PATH . '/../templates/'. $templatedata['name'] . '.php';
        }
        $class = new $name;
        $final_template = $class->Load($templatedata['data']);
        return $final_template;
    }
}

?>