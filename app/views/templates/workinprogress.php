<?php 

use Core\App\Template;

class workinprogress extends Template {
    static protected $template;
    public function load($values) {
        self::$template = <<<EOS
            <div class="section-12 column height-50 infobox">
                <h1>Tilauskalenteri</h1>
                <p>Tilauskalenteri on työn alla. Voit varata ajan soittamalla meille!</p>
                <a href="tel:" class="button">Soita nyt</a>
            </div>
        EOS;

        return self::$template;
    }
}

?>