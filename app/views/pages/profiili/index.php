<div class="profile_menu">
    <ul>
        <li><a href="/profiili"><i class="far fa-user"></i>Omat tiedot</a></li>
        <li><a href="/profiili/ajanvaraukset"><i class="fas fa-calendar-alt"></i>Ajanvaraukset</a></li>
        <li><a href="/profiili/kyytitilaukset"><i class="fas fa-taxi"></i>Kyytitilaukset</a></li>
    </ul>
    <a href="/logout" class="profile_logout"><?php echo LANG['logout'];?></a>
</div>
<div class="profile_content row">
    <div class="section-6 profile_c_block column">
        <h1><?php echo LANG['mydetails']; ?></h1>
        <form id="profile_update" class="profile_c_form" action="/user/update" method="POST">
            <?php if(isset($userdata)) {
                foreach($userdata[0] as $name => $value) {
                    $label_field = LANG[$name];
                    echo <<<EOS
                        <label for="{$name}_ifield">{$label_field}</label>
                        <input type="text" id="{$name}_ifield" name="{$name}" value="{$value}" data-origval={$value} placeholder="{$label_field}"/>
                    EOS;
                }
            }
            ?>
            <button type="submit" data-sitekey="6LfngqseAAAAABp3f1htpLMhEsBlpxNqmcKe-xOq"
                id="update_button"><?php echo LANG['updateinfo']; ?></button>
        </form>
    </div>
    <div class="section-6 profile_c_block column">
        <h1><?php echo LANG['changepassword']; ?></h1>
            <form class="profile_c_form" action="/user/update/field/password">
                <label for="curpassword"><?php echo LANG['currentpassword']; ?></label>
                <input type="password" id="curpassword" name="curpass" placeholder="<?php echo LANG['currentpassword']; ?>"/>
                <label for="newpass"><?php echo LANG['newpassword']; ?></label>
                <input type="password" id="newpass" name="newpass" placeholder="<?php echo LANG['newpassword']; ?>"/>
                <label for="newpassagain"><?php echo LANG['newpasswordagain']; ?></label>
                <input type="password" id="newpassagain" name="newpassagain" placeholder="<?php echo LANG['newpasswordagain']; ?>"/>
                <button type="submit" data-sitekey="6LfngqseAAAAABp3f1htpLMhEsBlpxNqmcKe-xOq" id="updatepass_button"><?php echo LANG['changepassword']; ?></button>
            </form>

    </div>
    <script>
    $(document).ready(() => {
        $("#update_button").click((e) => {
            e.preventDefault();
            let sitekey = e.target.dataset.sitekey;
            grecaptcha.ready(() => {
                grecaptcha.execute(sitekey, {
                    action: 'submit'
                }).then((token) => {
                    let data = {
                        token: token,
                        data: {}
                    }
                    let input_fields = $("#profile_update").children("input");
                    for (let counter = 0; counter < input_fields.length; counter++) {
                        let origval = input_fields[counter].dataset.origval;
                        let value = input_fields[counter].value;
                        let field = input_fields[counter].name;
                        if (origval !== value) {
                            data.data[field] = value;
                        }

                    }
                    if (Object.keys(data.data).length < 1) {
                        toastr.warning(
                            'Mitään tietoja ei ole muutettu. Tietoja ei päivitetty.'
                        );
                        return;
                    }
                    let final_data = JSON.stringify(data);
                    $.ajax({
                        type: "POST",
                        url: "/user/update",
                        data: final_data
                    }).done((res) => {
                        console.log(res);
                        switch (res.status) {
                            case false:
                                toastr.warning(res.msg);
                                break;
                            case true:
                                toastr.success(res.msg);
                                break;
                        }
                    });
                });
            });
        });
        $("#updatepass_button").click((e) => {
            e.preventDefault();
            const currentPass = $("#curpassword").val();
            const newPass = $("#newpass").val();
            const newPassAgain = $("#newpassagain").val();
            
            /*if(newPass !== newPassAgain) {
                toastr.warning('Salasanat eivät täsmää!');
                return;
            }*/
            const data = {
                password: currentPass,
                newpassword: newPass,
                newpasswordagain: newPassAgain,
            };
            $.ajax({
                type: 'POST',
                data: JSON.stringify(data),
                url: '/user/update/field/password'
            }).done((res) => {
                console.log(res);
                switch(res.status) {
                    case true: 
                        toastr.success(res.msg);
                    break;
                    case false:
                        toastr.warning(res.msg);
                    break;
                    default: 
                        toastr.error('Something went wrong. Contact webmaster.');
                    break;
                }
            });
        });
    });
    </script>
</div>