<?php

namespace Core\App\Template;

use Core\App\Template;

class Header extends Template
{
    public function load($values)
    {
        $this->collectStyle(__DIR__);
        $this->collectScript(__DIR__);

        $headerMenu = parent::load(['name' => 'HeaderMenu']);
        return <<<EOS
        <header>
            <div class="headermargin">
                <h1>Henkilötietojärjestelmä</h1>
                $headerMenu
            </div>
        </header>
        EOS;
    }
}
