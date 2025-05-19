<div class="main section-12 column height-100">
    <div class="gallery column">
        <?php
        if ($gexists) {
            echo <<<EOS
            <a class="admin_info_btn" href="/admin/galleria/addimg/{$guuid}">Lisää kuva</a>
            EOS;
        }
        ?>
        <div class="section-12 row gimgs">
        <?php
            if ($status) {
                if (count($images) >= 1) {
                    foreach ($images as $img) {
                        echo $template->load([
                            'name' => 'GalleryAdmin',
                            'data' => $img
                        ]);
                    } 
                }
                else {
                    echo <<<EOS
                    <h1>Galleria on tyhjä</h1>
                    EOS;
                }
            }
        ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
        $(".deleteimg").click((e) => {
            e.preventDefault();
            const confirmation = confirm('Oletko varma, että haluat poistaa kuvan?');
            if (confirmation) {
                $.ajax({
                    type: 'DELETE',
                    url: e.target.href
                }).done((res) => { 
                    switch(res.status) {
                        case true:
                            window.location.reload();
                            toastr.success(res.msg);
                        break;
                        case false: 
                            toastr.warning(res.msg);
                        break;
                        default: 
                            toastr.error(res.msg);
                        break;
                    }
                });
            }
        });
    });
</script>