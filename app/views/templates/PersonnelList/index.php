<?php

namespace Core\App\Template;

use Core\App\Template;

class PersonnelList extends Template
{

    public function personel($personnel)
    {

        $birthday = date('d.m.Y', strtotime($personnel['birthday']));

        return <<<EOT
            <div class="personnel">
                <div class="personnelCol">
                    <p>{$personnel['firstname']} {$personnel['lastname']}</p>
                </div>
                <div class="personnelCol">
                    <p>{$personnel['email']}</p>
                </div>
                <div class="personnelCol">
                    <p>{$personnel['phonenumber']}</p>
                </div>
                <div class="personnelCol">
                    <p>{$birthday}</p>
                </div>
                <div class="personnelCol">
                    <button data-id="{$personnel['id']}">Toimenpiteet</button>
                    <div class="personnelActionMenu">
                        <button data-id="{$personnel['id']}" class="editPersonnel">Muokkaa henkilöä</button>
                        <button data-id="{$personnel['id']}" class="removePersonnel">Poista henkilö</button>
                    </div>
                </div>
            </div>
        EOT;
    }

    public function load($values)
    {
        $this->collectStyle(__DIR__);
        $this->collectScript(__DIR__);


        $personnels = '';

        foreach ($values as $personnel) {
            $personnels = $personnels . $this->personel($personnel);
        }

        if (count($values) < 1) {
            $personnels = <<<EOS
                <div class="nopersonnels">
                    <h2>Henkilöitä ei löytynyt - Lisää uusia henkilöitä</h2>
                </div>
            EOS;
        }

        return <<<EOT
            <div class="personnels" id="personnels">
            
            <div class="personnel">
                <div class="personnelCol"><p class="bold">Koko nimi</p></div>
                <div class="personnelCol"><p class="bold">Sähköposti</p></div>
                <div class="personnelCol"><p class="bold">Puhelinnumero</p></div>
                <div class="personnelCol"><p class="bold">Syntymäpäivä</p></div>
                <div class="personnelCol"><p class="bold">Toimenpiteet</p></div>
            </div>

            {$personnels}
        
            </div>
        EOT;
    }
}
