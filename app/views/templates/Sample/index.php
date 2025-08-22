<?php

namespace Core\App\Template;

use Core\App\Template;

class Sample extends Template
{
    public function load($values)
    {
        return <<<EOS
            <div>
                <h1>Welcome to JJMVC</h1>
                <p>
                    This text has been loaded by using JJMVC template functionality. 
                    It loads templates from view when set what templates we want to load. 
                    This is static template and it does nothing. However there are also 
                    ways to load dynamic templates. Static template components are easier
                    to build as they do not require additional changes. 
                </p>
            </div>
        EOS;
    }
}
