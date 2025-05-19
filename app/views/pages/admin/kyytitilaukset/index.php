<div class="main section-12 column height-100">
    <div class="admin_infobox profile_content column">
        <a class="admin_info_btn" href="/admin/kyytitilaukset/etsi">Etsi tilauksia</a>
        <h1>Kyytitilaukset</h1>
        <p>Tältä sivulta löydät kaikki asiakkaiden tekemät uudet varaukset. Listassa näkyy kaikki tulevat kyydit. Voit kuitenkin etsiä myös aikaisempia kyytejä käyttämällä "Etsi tilauksia" -ominaisuutta. </p>
        <div class="listcontainer section-12 row">
            <div class="section-12 row orderbuttons">
                <a href="#" id="uarides_btn">Hyväksymättömät kyydit <span><?php echo count($uarides); ?></span></a>
                <a href="#" id="accepted_btn">Hyväksytyt kyydit <span><?php echo count($rides); ?></span></a>
                <a href="#" id="cancelled_btn">Perutut tilaukset <span><?php echo count($cancelledrides); ?></span></a>
            </div>
        <div id="unacceptedrides" class="section-12 column">
        <h2>Hyväksymättömät kyydit</h2>
        <?php
        if (isset($uarides)) {
            $html = '';
            if (count($uarides) < 1) {
                echo '<h4>Ei hyväksyttäviä kyytejä</h4>';
            }
            foreach ($uarides as $ride) {
                $date = $ride['order_date'];
                $time = $ride['order_time'];
                $from = $ride['address_from'];
                $to = $ride['address_to'];
                $passengers = $ride['passengers'];
                $desc = $ride['order_desc'];
                $uuid = $ride['uuid'];

                $html .= <<<EOS
                <div class="row wrap section-12">
                    <div class="lc_item column section-2">
                        <div class="lci_sub">Päivänmäärä</div>
                        <div class="lci_sub"><p>{$date}</p></div>
                    </div>
                    <div class="lc_item column section-2">
                        <div class="lci_sub">Kellonaika</div>
                        <div class="lci_sub"><p>{$time}</p></div>
                    </div>
                    <div class="lc_item column section-2">
                        <div class="lci_sub">Matkustajat</div>
                        <div class="lci_sub"><p>{$passengers}</p></div>
                    </div>
                    <div class="lc_item column section-2">
                        <div class="lci_sub">Mistä</div>
                        <div class="lci_sub"><p>{$from}</p></div>
                    </div>
                    <div class="lc_item column section-2">
                        <div class="lci_sub">Mihin</div>
                        <div class="lci_sub"><p>{$to}</p></div>
                    </div>
                    <div class="lc_item column section-2">
                        <div class="lci_sub">Toimenpide</div>
                        <div class="lci_sub"><a href="/admin/kyytitilaukset/id/{$uuid}">Näytä</a></div>
                    </div>
                </div>
                EOS;
            }
        echo $html;
        }
        ?>
        </div>
        <div style="display: none;" id="acceptedrides" class="section-12 column">
        <h2>Hyväksytyt kyydit</h2>
        <?php
        if (isset($rides)) {
            $html = '';
            if (count($rides) < 1) {
                echo '<h4>Ei hyväksyttyjä kyytejä</h4>';
            }
            foreach ($rides as $ride) {
                $date = $ride['order_date'];
                $time = $ride['order_time'];
                $from = $ride['address_from'];
                $to = $ride['address_to'];
                $passengers = $ride['passengers'];
                $desc = $ride['order_desc'];
                $uuid = $ride['uuid'];
                $html .= <<<EOS
                <div class="row wrap section-12">
                    <div class="lc_item column section-2">
                        <div class="lci_sub">Päivänmäärä</div>
                        <div class="lci_sub"><p>{$date}</p></div>
                    </div>
                    <div class="lc_item column section-2">
                        <div class="lci_sub">Kellonaika</div>
                        <div class="lci_sub"><p>{$time}</p></div>
                    </div>
                    <div class="lc_item column section-2">
                        <div class="lci_sub">Matkustajat</div>
                        <div class="lci_sub"><p>{$passengers}</p></div>
                    </div>
                    <div class="lc_item column section-2">
                        <div class="lci_sub">Mistä</div>
                        <div class="lci_sub"><p>{$from}</p></div>
                    </div>
                    <div class="lc_item column section-2">
                        <div class="lci_sub">Mihin</div>
                        <div class="lci_sub"><p>{$to}</p></div>
                    </div>
                    <div class="lc_item column section-2">
                        <div class="lci_sub">Toimenpide</div>
                        <div class="lci_sub"><a href="/admin/kyytitilaukset/id/{$uuid}">Näytä</a></div>
                    </div>
                </div>
                EOS;
            }
        echo $html;
        }
        ?>
        </div>
        <div style="display: none;" id="cancelledrides" class="section-12 column">
        <h2>Perutut tilaukset</h2>
        <?php 
            if (isset($cancelledrides)) {
                $html = '';
                if (count($cancelledrides) < 1) {
                    echo '<h4>Ei peruttuja kyytejä</h4>';
                }
                foreach ($cancelledrides as $ride) {
                    $date = $ride['order_date'];
                    $time = $ride['order_time'];
                    $from = $ride['address_from'];
                    $to = $ride['address_to'];
                    $passengers = $ride['passengers'];
                    $desc = $ride['order_desc'];
                    $uuid = $ride['uuid'];
                    $html .= <<<EOS
                    <div class="row wrap section-12">
                        <div class="lc_item column section-2">
                            <div class="lci_sub">Päivänmäärä</div>
                            <div class="lci_sub"><p>{$date}</p></div>
                        </div>
                        <div class="lc_item column section-2">
                            <div class="lci_sub">Kellonaika</div>
                            <div class="lci_sub"><p>{$time}</p></div>
                        </div>
                        <div class="lc_item column section-2">
                            <div class="lci_sub">Matkustajat</div>
                            <div class="lci_sub"><p>{$passengers}</p></div>
                        </div>
                        <div class="lc_item column section-2">
                            <div class="lci_sub">Mistä</div>
                            <div class="lci_sub"><p>{$from}</p></div>
                        </div>
                        <div class="lc_item column section-2">
                            <div class="lci_sub">Mihin</div>
                            <div class="lci_sub"><p>{$to}</p></div>
                        </div>
                        <div class="lc_item column section-2">
                            <div class="lci_sub">Toimenpide</div>
                            <div class="lci_sub"><a href="/admin/kyytitilaukset/id/{$uuid}">Näytä</a></div>
                        </div>
                    </div>
                    EOS;
                }
            echo $html;
            }
        ?>
        </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
        $("#uarides_btn").click(() => {
            $("#unacceptedrides").css('display', 'flex');
            $("#acceptedrides").hide();
            $("#cancelledrides").hide();
        });
        $("#accepted_btn").click(() => {
            $("#acceptedrides").css('display', 'flex');
            $("#unacceptedrides").hide();
            $("#cancelledrides").hide();
        });
        $("#cancelled_btn").click(() => {
            $("#cancelledrides").css('display', 'flex');
            $("#unacceptedrides").hide();
            $("#acceptedrides").hide();
        });
    });
</script>

