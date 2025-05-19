<div class="main section-12 column height-100">
    <div class="admin_infobox profile_content column">
        <a class="admin_info_btn" href="/admin/users/etsi">Etsi käyttäjiä</a>
        <h1>Käyttäjä sivu</h1>
        <p>Tällä sivulla voit hallinnoida yksittäistä käyttäjää.</p>

        <?php if (isset($error)){ ?>
            <h1><?php echo $msg; ?></h1>
        <?php } ?>

        <?php if (isset($user)) {?>
            <div class="userform">
                <form id="changeuser" action="/admin/users/modify/send" method="POST">
                    <?php 
                    foreach ($user[0] as $field => $value) {
                        if ($field === 'uuid') {
                            echo <<<EOS
                                <input id="{$field}" type="hidden" placeholder="{$lang}" name="{$field}" value="{$value}"/>
                            EOS;
                            continue;
                        }
                        $lang = LANG[$field];
                        if ($field === 'permgroup') {
                            $html = <<<EOS
                            <div class="inputs">
                                <label for="{$field}">{$lang}</label>
                                <select id="{$field}" name="{$field}">
                            EOS;
                            foreach ($perms as $pvalue) {
                                $val = $pvalue['perm_name'];
                                if ($value === $val) {
                                    $html .= <<<EOS
                                        <option value="{$val}" selected>{$val}</option>
                                    EOS;
                                    continue;
                                }
                                $html .= <<<EOS
                                    <option value="{$val}">{$val}</option>
                                EOS;
                            }
                            $html .= '</select></div>';
                            echo $html;
                            continue;
                        }
                        echo <<<EOS
                            <div class="inputs">
                                <label for="{$field}">{$lang}</label>
                                <input id="{$field}" type="text" placeholder="{$lang}" name="{$field}" value="{$value}"/>
                            </div>
                        EOS;
                    }
                    ?>
                    <button class="admin_info_btn" type="submit">Muuta tietoja</button>
                </form>
            </div>
        <?php } ?>
    </div>
</div>
<script>
    $(document).ready(() => {
        const jsonOrig = {};
        const fields = ['firstname', 'lastname', 'email', 'permgroup', 'phonenumber'];
        jsonOrig.firstname = $("#firstname").val();
        jsonOrig.lastname = $("#lastname").val();
        jsonOrig.email = $("#email").val();
        jsonOrig.permgroup = $("#permgroup").val();
        jsonOrig.phonenumber = $("#phonenumber").val();

        $("#changeuser").submit((e) => {
            e.preventDefault();

            const finalJson = {};
            const json = {};
            json.firstname = $("#firstname").val();
            json.lastname = $("#lastname").val();
            json.email = $("#email").val();
            json.permgroup = $("#permgroup").val();
            json.phonenumber = $("#phonenumber").val();
            const uuid = $("#uuid").val();

            fields.foreach (field => {
                let newVal = json[field];
                let oldVal = jsonOrig[field];
                if (newVal !== oldVal) {
                    finalJson[field] = newVal;
                }
            });
            $.ajax({
                url: '/admin/users/update/send',
                method: 'POST',
                data: JSON.stringify({
                    data: finalJson,
                    uuid: uuid
                })
            }).done((res) => {
                switch(res.status) {
                    case true:
                        toastr.success(res.msg);
                    break;
                    case false:
                        toastr.warning(res.msg);
                    break;
                }
            });
        });
    });
</script>