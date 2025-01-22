<?php

namespace Core\App;

class verifyaccount
{
    public function Init($data)
    {
        $host = CONFIG['server']['host'];
        return <<<EOS
        <html>
            <head>

            </head>
            <body>
                <div style="width:calc(100% - 40px); padding: 20px; background-color: #191919; font-family: Arial; text-align: center;">
                    <img src="https://localhost/img/logo_yellow.png" style="width: 300px; max-width: 100%; height: auto;" alt="localhost logo"/>
                    <h1 style="color: #ffea00;">{$data['subject']}</h1>
                    <p style="color: white;">Hei {$data['firstname']}! Olet nyt onnistuneesti rekisteröitynyt localhostn nettipalveluun. Klikkaamalla alla olevaa "Vahvista käyttäjätunnus" -nappia pääset aktivoimaan tunnuksesi ja käyttämään tunnusta localhostn nettipalvelussa.</p>
                    <a style="display: inline-flex; text-decoration: none; justify-content: center; align-items: center; width: 150px; padding: 10px; height: 40px; background-color: #ffea00; color: black; text-align: center;" href="http://{$host}/verifyaccount/id/{$data['verification_code']}" target="_blank">Vahvista käyttäjätunnus</a>
                    <p style="color: white;">Jos et kuitenkaan näe linkkiä, niin kopioi osoitekenttään tämän tekstin alla oleva teksti</p>
                    <p style="color: #ffea00;">https://{$host}/verifyaccount/id/{$data['verification_code']}</p>
                    <p style="color: white;">Jos et kuitenkaan ole rekisteröitynyt palveluumme, niin voit jättää tämän sähköpostiviestin huomiotta ja poistaa sen. </p>
                </div>
            </body>
        </html>
        EOS;
    }
}
