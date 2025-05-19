<?php 

use Core\App\Template;

class Header extends Template {
    public function load($values) {
        $this->collectStyle(__DIR__);
        $headerMenu = parent::load(['name' => 'HeaderMenu']);
        return <<<EOS
        <header>
            <a class="logo" href="/">
                <img src="/img/jjmvc_white.png" alt="Logo" />
            </a>
            $headerMenu
            <button class="menubutton"><i class="fas fa-bars"></i></button>
        </header>
        EOS;
    }
}

?>