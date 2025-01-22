<div class="main section-12 column height-100">
    <div class="admin_infobox">
        <?php
            $times = [
                1 => 7.5,
                2 => 7.75,
                3 => 8,
                4 => 8.25,
                5 => 8.5,
                6 => 8.75,
                7 => 9,
                8 => 9.25,
                9 => 9.5,
                10 => 9.75,
                11 => 10,
                12 => 10.25,
                13 => 10.5,
                14 => 10.75,
                15 => 11,
                16 => 11.25,
                17 => 11.5,
                18 => 11.75,
                19 => 12,
                20 => 12.25,
                21 => 12.5,
                22 => 12.75,
                23 => 13,
                24 => 13.25,
                25 => 13.5,
                26 => 13.75,
                27 => 14,
                28 => 14.25,
                29 => 14.5,
                30 => 14.75,
                31 => 15,
                32 => 15.25,
                33 => 15.5,
                34 => 15.75,
                35 => 16,
                36 => 16.25,
                37 => 16.5,
                38 => 16.75,
                39 => 17,
                40 => 17.25,
                41 => 17.5,
                42 => 17.75,
                43 => 18
            ];


            $title = LANG[$appointment['title']];
            $status = LANG[$appointment['status']];
            $from_clock = $appointment['slot_from'];
            $to_clock = $appointment['slot_to'];

            $date = date_format(date_create($appointment['appointment_day']), 'd.m.Y');

            $html = <<<EOS
            <div class="orderdetails">
                <div class="od_trip">
                    <h2>Tilaajan tiedot</h2>
                    <div class="odt_destination">
                        <div class="odtd_box">
                            <h3>Etunimi</h3>
                            <p>{$user['firstname']}</p>
                        </div>
                        <div class="odtd_box">
                            <h3>Sukunimi</h3>
                            <p>{$user['lastname']}</p>
                        </div>
                    </div>
                    <div class="odt_destination">
                        <div class="odtd_box">
                            <h3>Puhelinnumero</h3>
                            <p>{$user['phonenumber']}</p>
                        </div>
                        <div class="odtd_box">
                            <h3>Sähköposti</h3>
                            <p>{$user['email']}</p>
                        </div>
                    </div>
                </div>
                <div class="od_trip">
                    <h2>Tilauksen tiedot</h2>
                    <div class="odt_destination">
                        <div class="odtd_box">
                            <h3>Mistä</h3>
                            <p>{$from_clock}</p>
                        </div>
                        <div class="odtd_box">
                            <h3>Mihin</h3>
                            <p>{$to_clock}</p>
                        </div>
                    </div>
                    <div class="odt_destination">
                        <div class="odtd_box">
                            <h3>Päivänmäärä</h3>
                            <p>{$date}</p>
                        </div>
                    </div>
                </div>
                <div class="od_actions">
                    <h2>Kuvaus</h2>
                    <p>{$appointment['description']}</p>
                </div>
            </div>
            EOS;
            echo $html;

            
        ?>
    </div>
</div>