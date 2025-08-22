<?php

namespace Core\App\Template;

use Core\App\Template;

class Actionmenu extends Template
{
    public function load($values)
    {
        $addPerson = LANG['add'] . ' ' . LANG['person'];
        $title = isset($values['title']) ? $values['title'] : '';
        $searchbar = parent::load(['name' => 'Searchpersonnel']);
        $this->collectStyle(__DIR__);
        $this->collectScript(__DIR__);

        return <<<EOT
            <div class="actionmenu">
                <h2>{$title}</h2>
                {$searchbar}
                <div class="actions">
                    <button id="addPerson">{$addPerson}</button>
                </div>
                <div class="actionmenudesc">
                    <p>Tervetuloa henkilötietojen hallintajärjestelmään! Olen toteuttanut tämän omalla <a href="https://github.com/Progvice/mvc" target="_blank">MVC sovelluskehyksellä</a>. Jos haluat päästä kurkkaamaan, että minkälaisia API endpointteja olen rakentanut, niin löydät ne kansiosta "/www/app/controllers/personel". API endpointeista löydät myös lisää tietoa, kun klikkaat tästä sinisestä kohdasta <a href="/swagger">/swagger</a>. Kun käytät hakupalkkia, niin katso hakutulokset selaimen hakupalkista. Jquery frontendin toteutus on todella työlästä ja priorisoin rajapinnan toteuttamisen. </p>
                </div>
            </div>
        EOT;
    }
}
