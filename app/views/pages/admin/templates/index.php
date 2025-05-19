<div class="main height-100 row wrap">
    <div class="admin_infobox">
        <a href="javascript:history.go(-1)" class="admin_info_btn">Edellinen sivu</a>
        <h1>Sivut</h1>
        <p>Tällä sivulla on listattuna kaikki sivut ja niiden tekstit. </p>
    </div>
    <?php
        $list = '';
        foreach ($sitedata as $sitename => $sdata) {
            $snamearr = explode('/', $sitename);
            $snamefinal = implode('_', $snamearr);
            $list .= <<<EOS
                <div class="a_box">
                    <a class="a_box_link" href="/admin/templates/id{$sitedata[$sitename]['uri']}">
                        <h2>{$sitename}</h2>
                    </a>
                </div>
            EOS;
        }
        echo <<<EOS
        <div class="admin_boxes">
            {$list}
        </div>
        EOS;
    ?> 
</div>