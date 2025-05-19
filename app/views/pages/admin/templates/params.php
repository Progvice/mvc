<div class="main height-100 row wrap">
    <div class="admin_infobox">
    <a href="javascript:history.go(-1)" class="admin_info_btn">Edellinen sivu</a>
    <h1>Sivun tiedot</h1>
    <p>Tällä sivulla on listattuna kaikki sivulla olevat tiedot. </p>
        <div class="admin_boxes">
        <?php
            $finalParamsUri = implode('/', $paramsUri);
            
            $site = htmlspecialchars($finalParamsUri);
            /*$regex = preg_match("/(\.|\/)/", $site);
            if ($regex) {
                echo '<h2>Invalid parameter';
                return;
            }*/
            $surl = explode('_', $site);
            $surlfinal = '/';
            $surlfinal .= implode('/', $surl);
            if (!file_exists(DATA_PATH . $surlfinal . '/index.json')) {
                echo '<h2>This site data does not exist.</h2>';
                return;
            }
            $data = json_decode(file_get_contents(DATA_PATH . $surlfinal . '/index.json'));
            $list = '';
            foreach ($data as $dataname => $datablock) {
                $list .= <<<EOS
                    <div class="a_box">
                        <a class="a_box_link" href="/admin/templates/modify/{$site}/data/{$dataname}"><h2>{$dataname}</h2></a>
                    </div>
                EOS;
            }
            echo $list;
        ?>
        </div>
    </div>
</div>
