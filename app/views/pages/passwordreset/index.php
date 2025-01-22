<a href="/" class="login_logo"><img src="/img/logo_yellow.png" alt="Logo" /></a>
<div class="height-50 column formbox">
    <h2><?php echo LANG['resetpassword']; ?></h2>
    <form action="/post/login" method="post" id="loginform">
        <input type="text" name="email" placeholder="<?php echo LANG['email']; ?>" />
        <div class="g-recaptcha" data-sitekey="6LdiorseAAAAAJuDwns8fB7Ib7PM76166hz3b_zc"></div>
        <button type="submit" id="loginbutton" class="g-recaptcha"><?php echo LANG['resetpassword']; ?></button>
    </form>
    <p><?php echo LANG['forgotpassword']; ?><br><a href="/restorepassword">Palauta salasana</a></p>
    <hr>
    <p>
        <?php echo LANG['donthaveaccount']; ?><br>
        <a href="/register"><?php echo LANG['register'] . ' ' . LANG['now']; ?></a>
    </p>
</div> 