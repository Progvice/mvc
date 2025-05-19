<div class="main section-12 column height-100">
    <div class="admin_infobox profile_content column">
    <a href="javascript:history.go(-1)" class="admin_info_btn">Edellinen sivu</a>
        <h1>Appointments</h1>
        <p>Tältä sivulta löydät kaikki asiakkaiden tekemät uudet varaukset.</p>
        <div class="listcontainer section-12 column">
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
            foreach ($appointments as $appointment) {
                $appointment_date = date('d.m.Y', strtotime($appointment['appointment_day']));
                $from_clock = $appointment['slot_from'];
                $to_clock = $appointment['slot_to'];
                $title = LANG['appointmenttypes'][$appointment['title']];
                $status = LANG[$appointment['status']];
                echo <<<EOS
                <div class="row section-12">
                    <div class="lc_item column section-20">
                        <div class="lci_sub">Varauspäivä</div>
                        <div class="lci_sub"><p>{$appointment_date}</p></div>
                    </div>
                    <div class="lc_item column section-20">
                        <div class="lci_sub">Mistä</div>
                        <div class="lci_sub"><p>{$from_clock}</p></div>
                    </div>
                    <div class="lc_item column section-20">
                        <div class="lci_sub">Mihin</div>
                        <div class="lci_sub"><p>{$to_clock}</p></div>
                    </div>
                    <div class="lc_item column section-20">
                        <div class="lci_sub">Aihe</div>
                        <div class="lci_sub"><p>{$title}</p></div>
                    </div>
                    <div class="lc_item column section-20">
                        <div class="lci_sub">Toimenpide</div>
                        <div class="lci_sub"><a href="/admin/appointments/id/{$appointment['uuid']}">Näytä</a></div>
                    </div>
                </div>
                EOS;
            }

        ?>
        </div>
    </div>
</div>