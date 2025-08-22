<?php

namespace Core\App;

class View
{
    /*
    *
    *   @var $variables (array)
    *    
    *   @example
    *    
    *   [
    *       "variable_name" => "variable_value",
    *       "another_variable_name" => "variable_value"
    *   ]
    *
    *
    *   @desc   Set variables for view. DO NOT USE THIS FOR USER INPUT WITHOUT FILTERING THE DATA FIRST!
    *
    */
    public $variables;
    /*
    *   @var $customelements (array)
    *
    *   @example
    *
    *   ['header' => 'admin', 'footer' => 'admin'];
    *   
    *   @desc   You are able to load custom headers and footers in view with this.
    *           Just create folder "admin" to "/app/views/custom" and add header.php
    *           and footer.php to this folder. You are not required to set both of them at the same time.
    *           You can load header or footer or both at the same time.
    *
    */

    public $customelements;
    /*
    *   @name   index    Set view for user
    *   
    *   @desc   With this method you can output HTML to browser.
    *
    *   @param1 View    HTML content of page
    *
    *   @return void
    */
    public function index($view)
    {
        if (!file_exists($view)) {
            echo '<h1>JJMVC Error</h1><p>No view set. Contact server administrator if you see this message.</p>';
            return;
        }
        if (!isset($this->variables['title'])) {
            $this->variables['title'] = CONFIG['server']['name'];
        }
        extract($this->variables);

        $header = VIEW_PATH . '/header.php';
        $footer = VIEW_PATH . '/footer.php';
        if ($this->customelements !== null) {
            if (isset($this->customelements['header']) && file_exists(VIEW_PATH . '/../custom/' . $this->customelements['header'] . '/header.php')) {
                $header = VIEW_PATH . '/../custom/' . $this->customelements['header'] . '/header.php';
            }
            if (isset($this->customelements['footer']) && file_exists(VIEW_PATH . '/../custom/' . $this->customelements['footer'] . '/footer.php')) {
                $footer = VIEW_PATH . '/../custom/' . $this->customelements['footer'] . '/footer.php';
            }
        }
        ob_start();
        require $header;
        require $view;
        require $footer;
        $content = ob_get_contents();
        $styles = Template::$styles;
        $scripts = Template::$scripts;

        $headDetails = <<<EOS
        <style>{$styles}</style>
        <script>
        $(document).ready(function() {
        {$scripts}
        });
        </script>
        </head>
        EOS;

        $content = str_replace('</head>', $headDetails, $content);
        ob_end_clean();
        header('Cache-Control: max-age=86400');
        header("Content-Type: text/html");
        echo $content;
    }
    /*
    *   @name   menu    fetch menu for view
    *   
    *   @desc   With this class you can output HTML to browser.
    *           Developer is able to give variables to page and use those variables in view.
    *
    *   @param1 name   name of component.
    *
    *   @return string
    */
}
