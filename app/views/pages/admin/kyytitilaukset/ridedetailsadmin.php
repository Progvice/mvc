<div class="main section-12 column height-100">
    <div class="admin_infobox">
        <?php if (isset($order) && !empty($order)) {
            $order = $order[0];
            $status = LANG[$order['order_status']];
            
            $action = '';
            switch($order['order_status']) {
                case 'unconfirmed': 
                    $action = '<textarea id="reason" placeholder="Kirjoita tähän syy, jos päätät peruuttaa kyydin. Syy lähetetään asiakkaan sähköpostiin."></textarea><a class="green" href="/admin/kyytitilaukset/accept/' . $order['uuid'] . '">Vahvista tilaus</a>';
                    $action = $action . '<a class="red" href="/admin/kyytitilaukset/reject/' . $order['uuid'] . '">Hylkää kyyti</a>';
                break;
                case 'confirmed':
                    $action = '<textarea id="reason" placeholder="Kirjoita tähän syy, jos päätät peruuttaa kyydin..."></textarea><a class="red" href="/admin/kyytitilaukset/reject/' . $order['uuid'] . '">Peruuta tilaus</a>';
                break;
                default:
                    $action = '<p>Tilaukselle ei voi tehdä toimenpiteitä.</p>';
                break;
            }
            $comment = '';
            if ($order['order_status'] === 'cancelled' && isset($order['order_comment'])) {
                $comment = <<<EOS
                <div class="odt_destination">
                    <div class="odtd_box">
                        <h3>Peruutuksen syy</h3>
                        <p>{$order['order_comment']}</p>
                    </div>
                </div>
                EOS;
            }
            $html = <<<EOS
            <div class="orderdetails">
                <div class="od_trip">
                    <h2>Tilaajan tiedot</h2>
                    <div class="odt_destination">
                        <div class="odtd_box">
                            <h3>Etunimi</h3>
                            <p>{$order['firstname']}</p>
                        </div>
                        <div class="odtd_box">
                            <h3>Sukunimi</h3>
                            <p>{$order['lastname']}</p>
                        </div>
                    </div>
                    <div class="odt_destination">
                        <div class="odtd_box">
                            <h3>Puhelinnumero</h3>
                            <p>{$order['phonenumber']}</p>
                        </div>
                        <div class="odtd_box">
                            <h3>Sähköposti</h3>
                            <p>{$order['email']}</p>
                        </div>
                    </div>
                </div>
                <div class="od_trip">
                    <h2>Tilauksen tiedot</h2>
                    <div class="odt_destination">
                        <div class="odtd_box">
                            <h3>Mistä</h3>
                            <p>{$order['address_from']}</p>
                        </div>
                        <div class="odtd_box">
                            <h3>Mihin</h3>
                            <p>{$order['address_to']}</p>
                        </div>
                    </div>
                    <div class="odt_destination">
                        <div class="odtd_box">
                            <h3>Päivänmäärä</h3>
                            <p>{$order['order_date']}</p>
                        </div>
                        <div class="odtd_box">
                            <h3>Kellonaika</h3>
                            <p>{$order['order_time']}</p>
                        </div>
                    </div>
                    <div class="odt_destination">
                        <div class="odtd_box">
                            <h3>Matkustajat</h3>
                            <p>{$order['passengers']}</p>
                        </div>
                        <div class="odtd_box">
                            <h3>Status</h3>
                            <p>{$status}</p>
                        </div>
                    </div>
                    {$comment}
                </div>
                <div class="od_actions">
                    <h2>Toimenpiteet</h2>
                    {$action}
                </div>
            </div>
            EOS;
            echo $html;
        ?>


        <?php } else { ?>
            <h1>Virheellinen tilauksen ID!</h1>
        <?php } ?>

    </div>
</div>
<script>
    $(document).ready(() => {
        $(".red").click((e) => {
            e.preventDefault();
            const reason = $("#reason").val();
            const confirmation = confirm('Oletko varma, että haluat peruuttaa tilauksen?');
            if (confirmation) {
                $.ajax({
                    type: 'POST',
                    url: e.target.getAttribute('href'),
                    data: JSON.stringify({
                        reason: reason
                    })
                }).done((res) => { 
                    console.log(res);
                    switch(res.status) {
                        case true: 
                            toastr.success(res.msg);
                        break;
                        case false: 
                            toastr.warning(res.msg);
                        break;
                    }
                });
            }
        });
    });
</script>