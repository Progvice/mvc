<?php

namespace Core\App\Template;

use Core\App\Template;

class HeaderMenu extends Template
{
    static protected $template;
    public function load($values)
    {
        $usersText = LANG['users'];

        self::$template = <<<EOS
        <nav>
            <ul class="menu">

            </ul>
        </nav>
        EOS;
        $this->collectStyle(__DIR__);
        return self::$template;
    }
}
