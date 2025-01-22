<?php

namespace Core\App;

class confirmorder
{
    public function Init($data)
    {
        $protocol = CONFIG['server']['protocol'];
        $host = CONFIG['server']['host'];
        return <<<EOS
        <html>
            <head></head>
            <body>
            <div style="width:calc(100% - 40px); padding: 20px; background-color: #191919; font-family: Arial; text-align: center;">
            <img src="https://localhost/img/logo_yellow.png" style="width: 300px; max-width: 100%; height: auto;" alt="localhost logo"/>
                <h1 style="color: #ffea00;">{$data['subject']}</h1>
                <p style="color: white;">Hei! Tilauksesi tunnus <span style="color: #ffea00;">{$data['id']}</span> on nyt hyväksytty. Kyyti päivältä <span style="color:#ffea00;">{$data['date']}</span> klo <span style="color:#ffea00;">{$data['time']}</span> on nyt vahvistettu.</p>
                <hr style="border-color:#ffea00">
                <h2 style="color: #ffea00;">Yhteystiedot</h2>
                <ul style="padding:0;list-style:none;color:white">
                    <li style="color: white;margin: 10px 0;"><span class="il">Suorsa</span> <span class="il">taksi</span> &amp; kuljetus Oy</li>
                    <li style="color: white;margin: 19px 0;"><span style="color: #ffea00;">Puhelinnumero</span><br> <a style="color: #ffffff; text-decoration: none; href="tel:+358440123151" target="_blank">+358(0)440123151</a></li>
                    <li style="color: white;margin: 10px 0;"><span style="color: #ffea00;">Sähköposti</span><br><a style="color: #ffffff; text-decoration: none;" href="mailto:tilaus@localhost" target="_blank">tilaus@localhost</a></li>
                    <li style="color: white;margin: 10px 0;"><span style="color: #ffea00;">Y-tunnus</span><br>2626827-5</li>
                </ul>
            </div>
            </body>
        </html>
        EOS;
    }
}
