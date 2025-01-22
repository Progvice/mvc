<?php

namespace Core\App;

class ordersent
{
    public function Init($data)
    {
        $protocol = CONFIG['server']['protocol'];
        $host = CONFIG['server']['host'];
        $yellow = 'style="color: #ffea00;"';
        $white = 'style="color: white;"';

        return <<<EOS
        <html>
        <head></head>
        <body>
        <div style="width:calc(100% - 40px); padding: 20px; background-color: #191919; font-family: Arial;">
            <h1 {$yellow}>{$data['subject']}</h1>
            <p {$white}>Henkilö {$data['firstname']} {$data['lastname']} on tehnyt tilaukset osoitteesta <span {$yellow}>{$data['address_from']}</span> osoitteeseen <span {$yellow}>{$data['address_to']}</span><br>
            Tilaukset noutoajaksi on asetettu seuraavan päivänmäärä ja kellonaika: {$data['order_date']} {$data['order_time']}</p>
            <h2 {$yellow}>Tilaajan yhteystiedot</h2>
            <ul style="list-style: none;">
                <li {$white}><span {$yellow}>Nimi: </span> {$data['firstname']} {$data['lastname']}</li>
                <li {$white}><span {$yellow}>Puhelinnumero: </span>{$data['phonenumber']}</li>
                <li {$white}><span {$yellow}>Sähköposti: </span> {$data['email']}</li>
                <li {$white}><span {$yellow}>Henkilömäärä: </span> {$data['passengers']}</li>
            </ul>
            <h2 {$yellow}>Lisätietoja</h2>
            <p {$white}>{$data['order_desc']}</p>
            <hr style="border-color:#ffea00">
            <h2 {$yellow}>localhost yhteystiedot</h2>
            <ul style="padding:0;list-style:none;color:white">
                <li {$white}><span class="il">Suorsa</span> <span class="il">taksi</span> &amp; kuljetus Oy</li>
                <li {$white}>Puhelinnumero: <a href="tel:+358440123151" target="_blank">+358(0)440123151</a></li>
                <li {$white}>Sähköposti: <a href="mailto:tilaus@localhost" target="_blank">tilaus@localhost</a></li>
                <li {$white}>Y-tunnus: 2626827-5</li>
            </ul>
        </div>
        </body>
        </html>
        EOS;
    }
}
