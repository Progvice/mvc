<?php
namespace Core\App;
class Template {
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

    /*
     *  @method LoadRepetitive
     *
     *  @param  $templatename   string  -   Template name
     *  @param  $templatedatas  array   -   Data to load in template.
     * 
     *  @desc   This functions purpose is to create multiple elements repetitively but with possibility to output
     *          different data.
     *          
     * 
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

    public function LoadRepetitive($templatename, $templatedatas) {
        $all_data = '';
        if(!file_exists(VIEW_PATH . '/../templates/' . $templatename . '.php')) {
            echo 'Template does not exist.!';
            return;
        }
        $name = '\Core\App\Template\\' . $templatename;
        if(!class_exists($name)) {
            require VIEW_PATH . '/../templates/'. $templatename . '.php';
        }
        $class = new $name;
        foreach($templatedatas as $templatedata) {
            $final_template = $class->Load($templatedata);
            $all_data .= $final_template;
        }
        return $all_data;
    }
}

?>