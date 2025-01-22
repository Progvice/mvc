<?php 

namespace Core\App;
class forgotpassword {
    public function Init($data) {
	$protocol = CONFIG['server']['protocol'];
	$host = CONFIG['server']['host'];
        return <<<EOS
        <html>
            <head></head>
            <body>
                <div style="width:calc(100% - 40px); padding: 20px; background-color: #191919;">
                    <h1 style="color: #ffea00;">{$data['subject']}</h1>
                    <p style="color: white;">Hei {$data['firstname']} {$data['lastname']},<br><br> Sinun tilille on pyydetty salasanan palautuslinkkiä. Jos sinä et pyytänyt salasanan palautusta, niin ole hyvä ja jätä tämä viesti huomiotta. Jos kuitenkin pyysit palautusta, niin klikkaa linkkiä tunnin sisällä, niin pääset vaihtamaan tilisi salasanan palvelussamme. Kun olet klikannut linkkiä, niin varmista yläpalkista se, että olet oikeassa verkko-osoitteessa. </p>
                    <a style="background-color: #ffea00; color: black;" href="{$protocol}://{$host}/user/update/passwordchange/{$data['uuid']}">Palauta salasana</a>
                </div>
            </body>
        </html>
        EOS;
    }
}

?>
