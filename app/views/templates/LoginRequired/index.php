<?php

namespace Core\App\Template;

use Core\App\Template;

class LoginRequired extends Template
{
    static protected $template;
    public function load($values)
    {
        $title = LANG['loginrequired'];
        $desc = LANG['loginreqdesc'];
        $loginbtn = LANG['login'];

        self::$template = <<<EOS
            <div class="section-12 column height-50 infobox">
                <h1>{$title}</h1>
                <p>{$desc}</p>
                <a href="/login" class="button">{$loginbtn}</a>
            
            </div>
        EOS;

        return self::$template;
    }
}
