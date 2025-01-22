<?php if(isset($uuid)) { ?>
<a href="/" class="login_logo"><img src="/img/logo_yellow.png" alt="Logo" /></a>
<div class="height-50 column formbox">
    <h2><?php echo LANG['restorepassword']; ?></h2>
    <form action="/user/update/restore/password" method="post" id="loginform">
        <input type="hidden" name="uuid" id="uuid" value="<?php echo $uuid; ?>"/>
        <input type="password" id="newpass" name="newpassword" placeholder="<?php echo LANG['newpassword']; ?>" />
        <input type="password" id="newpassagain" name="newpasswordagain" placeholder="<?php echo LANG['newpasswordagain']; ?>"/>
        <div class="g-recaptcha" data-sitekey="6LdiorseAAAAAJuDwns8fB7Ib7PM76166hz3b_zc"></div>
        <button type="submit" id="updatepass_button"><?php echo LANG['restorepassword']; ?></button>
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
    $("#updatepass_button").click((e) => {
        e.preventDefault();
        let token = grecaptcha.getResponse();
        if (token.length < 1) {
            toastr.warning("You need to complete ReCaptcha.");
            return;
        }
        const newPass = $("#newpass").val();
        const newPassAgain = $("#newpassagain").val();
        const code = $("#uuid").val();
        
        if(newPass !== newPassAgain) {
            toastr.warning('Salasanat eivät täsmää!');
            return;
        }
        const data = {
            newpassword: newPass,
            newpasswordagain: newPassAgain,
            code: code,
            token: token,
        };
        $.ajax({
            type: 'POST',
            data: JSON.stringify(data),
            url: '/user/update/sendchange/password'
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
<?php } else { ?>
    <a href="/" class="login_logo"><img src="/img/logo_yellow.png" alt="Logo" /></a>
    <div class="height-50 column formbox">
        <h2><?php echo $msg; ?></h2>
    </div>
<?php } ?>
