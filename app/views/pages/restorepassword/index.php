<a href="/" class="login_logo"><img src="/img/logo_yellow.png" alt="Logo" /></a>
<div class="height-50 column formbox">
    <h2><?php echo LANG['restorepassword']; ?></h2>
    <form action="/user/update/restore/password" method="post" id="loginform">
        <input type="text" name="email" placeholder="<?php echo LANG['email']; ?>" />
        <div class="g-recaptcha" data-sitekey="6LdiorseAAAAAJuDwns8fB7Ib7PM76166hz3b_zc"></div>
        <button type="submit" id="loginbutton"><?php echo LANG['restorepassword']; ?></button>
    </form>
    <p><?php echo LANG['rememberpassword']; ?><br><a href="/login">Kirjaudu sisään</a></p>
    <hr>
    <p>
        <?php echo LANG['donthaveaccount']; ?><br>
        <a href="/register"><?php echo LANG['register'] . ' ' . LANG['now']; ?></a>
    </p>
</div>
<script>
$(document).ready(() => {
    $("#loginbutton").click((e) => {
        e.preventDefault();
        let token = grecaptcha.getResponse();
        let inputs = $("#loginform").children("input");
        let email = inputs[0].value;
        let data = JSON.stringify({
            email: email,
            token: token
        });
        $.ajax({
            method: 'POST',
            url: '/user/update/restore/password',
            data: data
        }).done((res) => {
            console.log(res);
            switch (res.status) {
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