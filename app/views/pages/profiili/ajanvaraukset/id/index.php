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
    if (isset($appointment)) {
        $orderid = $appointment['uuid'];
        $title = LANG[$appointment['title']] ?? $appointment['title'];
        $desc = $appointment['description'];
        $time_from = $appointment['slot_from'];
        $time_to = $appointment['slot_to'];
        $date = date('d.m.Y', strtotime($appointment['appointment_day']));
        $html = <<<EOS
        <div class="profile_order">
            <h1>Ajanvarauksen tiedot</h1>
            <div class="section-12 row wrap">
                <div class="section-6 column">
                    <h3><span class="icon"><i class="fas fa-pencil-alt"></i></span>Aihe</h3>
                    <p>{$title}</p>
                </div>
                <div class="section-6 column">
                    <h3><span class="icon"><i class="far fa-calendar-alt"></i></span>Päivänmäärä</h3>
                    <p>{$date}</p>
                </div>
                <div class="section-6 column">
                    <h3><span class="icon"><i class="far fa-clock"></i></span>Mistä</h3>
                    <p>{$time_from}</p>
                </div>
                <div class="section-6 column">
                    <h3><span class="icon"><i class="far fa-clock"></i></span>Mihin</h3>
                    <p>{$time_to}</p>
                </div>
            </div>
            <hr>
            <h3>Lisätietoja</h3>
            <p>{$desc}</p>
        </div>
        EOS;
        echo $html;
    }

?>
</div>