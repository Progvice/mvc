<div class="profile_menu">
    <ul>
        <li><a href="/profiili"><i class="far fa-user"></i>Omat tiedot</a></li>
        <li><a href="/profiili/ajanvaraukset"><i class="fas fa-calendar-alt"></i>Ajanvaraukset</a></li>
        <li><a href="/profiili/kyytitilaukset"><i class="fas fa-taxi"></i>Kyytitilaukset</a></li>
    </ul>
    <a href="/logout" class="profile_logout"><?php echo LANG['logout'];?></a>
</div>
<div class="profile_content column">
    <h1>Tilaustiedot</h1>
    <?php
        if(isset($appointments)) {
            if(count($appointments) > 0) {
                $html = <<<EOS
                    <table>
                        <tr>
                            <th>Päivänmäärä</th>
                            <th>Aika</th>
                            <th>Otsikko</th>
                            <th>Lisätietoja</th>
                        </tr>
                EOS;
                foreach($appointments as $appointment) {
                    $uuid = $appointment['uuid'];
                    $time_from = $appointment['slot_from'];
                    $time_to = $appointment['slot_to'];
                    $date = date('d.m.Y', strtotime($appointment['appointment_day']));
                    $title = LANG[$appointment['title']] ?? $appointment['title'];
                    $show = LANG['show'];
                    $html .= <<<EOS
                        <tr>
                            <td>{$date}</td>
                            <td>{$time_from}</td>
                            <td>{$title}</td>
                            <td><a href="/profiili/ajanvaraukset/id/{$uuid}">{$show}</a></td>
                        </tr>
                    EOS;
                }
                $html .= '</table>';
                echo $html;
            }
            else {
                echo '<h3>' . LANG['noappointments'] .'</h3>';
            }
        }
    ?>

</div>