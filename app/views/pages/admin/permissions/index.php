<div class="main section-12 column height-100">
    <div class="admin_infobox">
        <a href="javascript:history.go(-1)" class="admin_info_btn">Edellinen sivu</a>
        <h1>Käyttöoikeudet</h1>
        <p>Tällä sivulla voit hallinnoida eri käyttöoikeuksia.</p>
        <?php if ($perm_create) {
            echo '<a href="#" class="admin_info_btn" id="addRole">Lisää rooli</a>';
         } 
         ?>
            
        
    </div>
    <div class="admin_boxes">
        <?php
            if (isset($perms)) {
                foreach ($perms as $perm) {
                    $urlname = urlencode($perm['perm_name']);
                    $name = $perm['perm_name'];
                    echo <<<EOS
                    <div class="a_box red">
                        <a href="/admin/permissions/id/{$urlname}" class="a_box_link">
                            <i class="far fa-window-restore"></i>
                            <h2>{$name}</h2>
                        </a>
                    </div>
                    EOS;
                }
            }
        ?>
    </div>
    <?php if ($perm_create) { ?>
        <div class="modal center section-12">
            <div class="modal_close">Sulje</div>
            <div class="modal_content section-12 center">
                <form class="createperm" id="createperm" class="section-4 column center">
                    <h2>Lisää ryhmä</h2>
                    <input placeholder="Ryhmän nimi" type="text" name="permname" id="permname"/>
                    <button class="admin_info_btn" id="createperm" type="submit">Luo ryhmä</button>
                </form>
            </div>
        </div>
        <script>
            $(document).ready(() => {
                $("#addRole").click((e) => {
                    e.preventDefault();
                    $(".modal").css('display', 'flex');
                });
                $(".modal_close").click((e) => {
                    e.preventDefault();
                    $(".modal").css('display', 'none');
                });
                $("#createperm").submit((e) => {
                    e.preventDefault();
                    const name = $("#permname").val();
                    $.ajax({
                        url: '/admin/permissions/create/send',
                        method: 'POST',
                        data: JSON.stringify({name: name}),
                    }).done((res) => {
                        switch(res.status) {
                            case true: 
                                location.reload();
                            break;
                            default:
                                toastr.warning(res.msg);
                            break;
                        }
                    });
                });
            });
        </script>

    <?php } ?>