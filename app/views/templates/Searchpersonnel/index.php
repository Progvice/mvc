<?php

namespace Core\App\Template;

use Core\App\Template;

class Searchpersonnel extends Template
{
    public function load($values)
    {
        $this->collectStyle(__DIR__);
        $this->collectScript(__DIR__);
        return <<<EOT
            <input id="searchbar" class="searchbar" type="text" placeholder="Etsi sähköpostilla..." />
        EOT;
    }
}
