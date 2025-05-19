<div class="main section-12 column height-100">
    <div class="admin_infobox">
        <h1>Käyttöoikeudet <?php echo isset($perm[0]['perm_name']) ? '- ' . $perm[0]['perm_name'] : '' ?></h1>
        <p>Tällä sivulla voit hallinnoida eri käyttöoikeuksia.</p>
        <a href="javascript:history.go(-1)" class="admin_info_btn">Edellinen sivu</a>
    <?php if (isset($error)) { ?>
        <h1>Käyttöoikeutta ei löydy</h1>
    <?php } ?>

    <?php if (isset($perm)) { ?>
            <form class="changeperms" id="changeperms" action="/admin/permissions/modify/send" method="post">
                <?php
                    foreach ($perm[0] as $key => $value) {
                        if ($key !== 'perm_name' && $key !== 'perm_priority') { 
                            $checked = $value ? 'checked' : '';
                            $name = LANG['perms'][$key];
                            echo <<<EOS
                            <div class="perms">
                                <label for="{$key}">{$name}</label>
                                <input id="{$key}" type="checkbox" value="{$value}" {$checked}/>
                            </div>
                            EOS;
                        }
                        if ($key === 'perm_name') {
                            echo <<<EOS
                                <input type="hidden" value="{$value}" id="{$key}"/>
                            EOS;
                        }
                        if ($key === 'perm_priority') {
                            $name = LANG['perms'][$key];
                            echo <<<EOS
                                <div class="perms">
                                    <label for="{$key}">{$name}</label>
                                    <input type="text" value="{$value}" id="{$key}"/>
                                </div>
                            EOS;
                        }
                        
                    }
                ?>
                <button class="admin_info_btn" type="submit">Muokkaa oikeuksia</button>
                <button href="/admin/permissions/remove/send" data-name="<?php echo $perm[0]['perm_name'] ?>" id="deleteperm" class="admin_info_btn">Poista ryhmä</button>
            </form>
    <?php } ?>
    </div>
</div>
<script>

    $(document).ready(() => {
        $("#deleteperm").click((e) => {
            e.preventDefault();
            const confirmation = confirm('Oletko varma, että haluat poistaa ryhmän?');
            if (confirmation) {
                const name = $("#deleteperm").attr('data-name');
                $.ajax({
                    type: 'DELETE',
                    url: e.target.attributes.href.value,
                    data: JSON.stringify({
                        name: name
                    })
                }).done((res) => {
                    console.log(res);
                    switch(res.status) {
                        case true:
                            toastr.success(res.msg);
                            setTimeout(() => {
                                window.location.href = '/admin/permissions';
                            }, 1000);
                        break; 
                        case false: 
                            toastr.warning(res.msg);
                        break;
                    }
                });
            }
        });
        $("#changeperms").submit((e) => {
            e.preventDefault();
            let json = {};
            json.admin_access = $("#admin_access").is(":checked") ? 1 : 0;
            json.perm_priority = parseInt($("#perm_priority").val());
            json.pages_create = $("#pages_create").is(":checked") ? 1 : 0;
            json.pages_remove = $("#pages_remove").is(":checked") ? 1 : 0;
            json.pages_update = $("#pages_update").is(":checked") ? 1 : 0;
            json.appointment_read = $("#appointment_read").is(":checked") ? 1 : 0;
            json.appointment_create = $("#appointment_create").is(":checked") ? 1 : 0;
            json.appointment_remove = $("#appointment_remove").is(":checked") ? 1 : 0;
            json.appointment_update = $("#appointment_update").is(":checked") ? 1 : 0;
            json.appointment_other = $("#appointment_other").is(":checked") ? 1 : 0;
            json.user_create = $("#user_create").is(":checked") ? 1 : 0;
            json.user_remove = $("#user_remove").is(":checked") ? 1 : 0;
            json.user_update = $("#user_update").is(":checked") ? 1 : 0;
            json.user_read = $("#user_read").is(":checked") ? 1 : 0;
            json.user_other = $("#user_other").is(":checked") ? 1 : 0;
            json.groups_create = $("#groups_create").is(":checked") ? 1 : 0;
            json.groups_remove = $("#groups_remove").is(":checked") ? 1 : 0;
            json.groups_update = $("#groups_update").is(":checked") ? 1 : 0;
            json.groups_read = $("#groups_read").is(":checked") ? 1 : 0;
            json.perm_name = $("#perm_name").val();
            console.log(json);
            $.ajax({
                url: '/admin/permissions/modify/send',
                method: 'POST',
                data: JSON.stringify(json),
            }).done((res) => {
                switch(res.status) {
                    case true: 
                        toastr.success(res.msg);
                    break;
                    default:
                        toastr.warning(res.msg);
                    break;
                }
            });
        });
    });

</script>