<?php 

namespace Core\App\Template;

class workinprogress extends \Core\App\Template {
    static protected $template;
    public function Load($values) {
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