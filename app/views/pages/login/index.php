<a href="/" class="login_logo"><img src="/img/logo_yellow.png" alt="Logo" /></a>
<div class="height-50 column formbox">
    <h2><?php echo LANG['login']; ?></h2>
    <form action="/post/login" method="post" id="loginform">
        <input type="text" name="email" placeholder="<?php echo LANG['email']; ?>" />
        <input type="password" name="password" placeholder="<?php echo LANG['password']; ?>" />
        <button type="submit" id="loginbutton" class="g-recaptcha"
            data-sitekey="6LfngqseAAAAABp3f1htpLMhEsBlpxNqmcKe-xOq"><?php echo LANG['login']; ?></button>
    </form>
    <p><?php echo LANG['forgotpassword']; ?><br><a href="/restorepassword">Palauta salasana</a></p>
    <hr>
    <p>
        <?php echo LANG['donthaveaccount']; ?><br>
        <a href="/register"><?php echo LANG['register'] . ' ' . LANG['now']; ?></a>
    </p>
</div>
<script>
$(document).ready(() => {
    $("#loginbutton").click((e) => {
        let sitekey = e.target.dataset.sitekey;
        grecaptcha.ready(() => {
            grecaptcha.execute(sitekey, {
                action: 'submit'
            }).then((token) => {
                let inputs = $("#loginform").children("input");
                let email = inputs[0].value;
                let password = inputs[1].value;
                let data = JSON.stringify({
                    email: email,
                    password: password,
                    token: token
                });
                $.ajax({
                    method: 'POST',
                    url: '/post/login',
                    data: data
                }).done((res) => {
                    switch (res.status) {
                        case true:
                            toastr.success(res.msg);
                            setTimeout(() => {
                                window.location.href = '/';
                            }, 2000);
                            break;
                        case false:
                            toastr.warning(res.msg);
                            break;
                    }
                });
            });
        });
    });
});
</script>