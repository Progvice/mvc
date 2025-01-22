<div class="profile_menu">
    <ul>
        <li><a href="/profiili"><i class="far fa-user"></i>Omat tiedot</a></li>
        <li><a href="/profiili/ajanvaraukset"><i class="fas fa-calendar-alt"></i>Ajanvaraukset</a></li>
        <li><a href="/profiili/kyytitilaukset"><i class="fas fa-taxi"></i>Kyytitilaukset</a></li>
    </ul>
    <a href="/logout" class="profile_logout"><?php echo LANG['logout'];?></a>
</div>
<div class="profile_content column">
    <?php
    if(isset($ride)) {
        $orderid = $ride[0]['uuid'];
        $date = date('d.m.Y', strtotime($ride[0]['order_date']));
        $time = $ride[0]['order_time'];
        $from = $ride[0]['address_from'];
        $to = $ride[0]['address_to'];
        $desc = $ride[0]['order_desc'];
        $uuid = $ride[0]['uuid'];
        $colors = ['unconfirmed' => 'yellow', 'confirmed' => 'green', 'cancelled' => 'red'];
        $status = $ride[0]['order_status'];
        $status_lang = LANG[$status];
        $comment = '';
        if($ride[0]['order_status'] === 'cancelled') {
            $comment = <<<EOS
                <hr>
                <h3>Peruutuksen syy</h3>
                <p>{$ride[0]['order_comment']}</p>
            EOS;
        }
        $html = <<<EOS
            <div class="profile_order">
            <h1>Tilauksen tiedot</h1>
            <h3 class="status {$colors[$status]}">{$status_lang}</h3>
                <div class="section-12 row wrap">
                    <div class="section-6 column">
                        <h3><span class="icon"><i class="fas fa-map-marker-alt"></i></span>Mistä</h3>
                        <p>{$from}</p>
                    </div>
                    <div class="section-6 column">
                        <h3><span class="icon"><i class="fas fa-map-marker-alt"></i></span>Mihin</h3>
                        <p>{$to}</p>
                    </div>
                    <div class="section-6 column">
                        <h3><span class="icon"><i class="far fa-calendar-alt"></i></span>Päivänmäärä</h3>
                        <p>{$date}</p>
                    </div>
                    <div class="section-6 column">
                        <h3><span class="icon"><i class="far fa-clock"></i></span>Kellonaika</h3>
                        <p>{$time}</p>
                    </div>
                </div>
                <hr>
                <h3>Lisätietoja</h3>
                <p>{$desc}</p>
                {$comment}
            </div>
        EOS;
        echo $html;
    }

?>
</div>