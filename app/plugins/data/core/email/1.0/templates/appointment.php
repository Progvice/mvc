<?php

namespace Core\App;

class appointment
{
    public function Init($data)
    {
        $licenseplate = $this->licensePlate($data['licenseplate']);
        $protocol = CONFIG['server']['protocol'];
        $host = CONFIG['server']['host'];
        return <<<EOS
        <html>
            <head></head>
            <body>
            <div style="width:calc(100% - 40px); padding: 20px; background-color: #191919; font-family: Arial; text-align: center;">
            <img src="https://localhost/img/logo_yellow.png" style="width: 300px; max-width: 100%; height: auto;" alt="localhost logo"/>
                <h1 style="color: #ffea00;">{$data['subject']}</h1>
                <p style="color: white;"></p>
                <h2 style="color: #ffea00;">Tilauksen tiedot</h2>
                <p style="color: white;margin-top: 10px;"><span style="color: #ffea00;">Aihe</span><br>{$data['title']}</p>
                <p style="color: white;margin-top: 10px;"><span style="color: #ffea00;">Päivänmäärä</span><br>{$data['date']} </p>
                <p style="color: white;margin-top: 10px;"><span style="color: #ffea00;">Aika</span><br>{$data['time']} </p>
                <p style="color: white;margin-top: 10px;"><span style="color: #ffea00;">Koko nimi</span><br>{$data['fullname']} </p>
                <p style="color: white;margin-top: 10px;"><span style="color: #ffea00;">Puhelinnumero</span><br>{$data['phonenumber']} </p>
                {$licenseplate}
                <h2 style="color: #ffea00">Lisätietoja</h2>
                <p style="color: white;">{$data['description']}</p>
            </div>
            </body>
        </html>
        EOS;
    }
    private function licensePlate($licenseplate)
    {
        if (is_null($licenseplate)) {
            return '';
        }
        return '<p style="color: white; margin-top: 10px;"><span style="color: #ffea00;">Rekisterinumero</span><br>' . $licenseplate . '</p>';
    }
}
