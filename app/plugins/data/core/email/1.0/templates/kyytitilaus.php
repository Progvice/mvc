<?php 

namespace Core\App;
class kyytitilaus {
    public function Init($data) {
        return <<<EOS
        <html>
            <head></head>
            <body>
                <div style="width:calc(100% - 40px); padding: 20px; background-color: #191919;">
                    <h1 style="color: #ffea00;">{$data['subject']}</h1>
                    <p style="color: white;">Henkilö {$data['firstname']} {$data['lastname']} on tehnyt tilaukset osoitteesta <span style="color: #ffea00;">{$data['address_from']}</span> osoitteeseen <span style="color: #ffea00;">{$data['address_to']}</span><br>
                    Tilaukset noutoajaksi on asetettu seuraavan päivänmäärä ja kellonaika: {$data['order_date']} {$data['order_time']}</p>
                    <h2 style="color: #ffea00;">Tilaajan yhteystiedot</h2>
                    <ul style="list-style: none;">
                        <li style="color: white;"><span style="color: #ffea00;">Nimi: </span> {$data['firstname']} {$data['lastname']}</li>
                        <li style="color: white;"><span style="color: #ffea00;">Puhelinnumero: </span>{$data['phonenumber']}</li>
                        <li style="color: white;"><span style="color: #ffea00;">Sähköposti: </span> {$data['email']}</li>
                        <li style="color: white;"><span style="color: #ffea00;">Henkilömäärä: </span> {$data['passengers']}</li>
                    </ul>
                </div>
            </body>
        </html>
        EOS;
    }
}

?>