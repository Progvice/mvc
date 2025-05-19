<div class="main section-12 column height-100">
    <div class="admin_infobox">
        <h1>Galleriat</h1>
        <p>Tällä sivulla on listattuna kaikki galleriat. </p>
    </div>
    <div class="admin_boxes">
        <?php
        foreach ($gallery as $gal) {
            $udc = $gal['gname'];
            echo <<<EOS
            <div class="a_box">
                <a href="/admin/galleria/id/{$udc}" class="a_box_link">
                    <i class="fas fa-images"></i>
                    <h2>{$gal['gname']}</h2>
                </a>
            </div>
            EOS;
        }
        ?>
    </div>
</div>