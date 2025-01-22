<div class="profile_menu">
    <ul>
        <li><a href="/profiili"><i class="far fa-user"></i>Omat tiedot</a></li>
        <li><a href="/profiili/ajanvaraukset"><i class="fas fa-calendar-alt"></i>Ajanvaraukset</a></li>
        <li><a href="/profiili/kyytitilaukset"><i class="fas fa-taxi"></i>Kyytitilaukset</a></li>
    </ul>
    <a href="/logout" class="profile_logout"><?php echo LANG['logout'];?></a>
</div>
<div class="profile_content column">
    <h1>Kyytitilaukset</h1>
    <?php
        if(isset($rides)) {
            if(count($rides) > 0) {
                
                $html = <<<EOS
                    <table>
                        <tr>
                            <th>Päivänmäärä</th>
                            <th class="hideinmobile">Kellonaika</th>
                            <th>Status</th>
                            <th>Lisätietoja</th>
                        <tr>
                EOS;
                foreach($rides as $ride) {
                    $date = date('d.m.Y', strtotime($ride['order_date']));
                    $time = $ride['order_time'];
                    $from = $ride['address_from'];
                    $desc = $ride['order_desc'];
                    $status = LANG[$ride['order_status']];
                    $class = '';
                    switch($ride['order_status']) {
                        case 'unconfirmed':
                            $class = 'yellow';
                        break;
                        case 'confirmed':
                            $class = 'green';
                        break;
                        case 'cancelled': 
                            $class = 'red';
                        break;
                    }
                    $uuid = $ride['uuid'];
                    $html .= <<<EOS
                        <tr>
                            <td>{$date}</td>
                            <td class="hideinmobile">{$time}</td>
                            <td><span class="{$class}">{$status}</span></td>
                            <td><a href="/profiili/kyytitilaukset/id/{$uuid}">Näytä</a></td>
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