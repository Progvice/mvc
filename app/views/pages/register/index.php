<a href="/" class="login_logo"><img src="/img/logo_yellow.png" alt="Logo" /></a>
<div class="formbox">
    <h2><?php echo LANG['register']; ?></h2>
    <form action="/post/register" method="post" id="registerform">
        <input type="text" id="firstname" placeholder="<?php echo LANG['firstname']; ?> *" required />
        <input type="text" id="lastname" placeholder="<?php echo LANG['lastname']; ?> *" required />
        <input type="text" id="email" placeholder="<?php echo LANG['email']; ?> *" required />
        <input type="password" id="password" placeholder="<?php echo LANG['password']; ?> *" required />
        <input type="password" id="passwordagain" placeholder="<?php echo LANG['passwordagain']; ?> *" required />
        <input type="text" id="phonenumber" placeholder="<?php echo LANG['phonenumber']; ?>*" required />
        <div class="row"><input type="checkbox" id="termsandconditions" / required><label for="termsandconditions"><?php echo LANG['termsaccept'] ?></label></div>
        <div class="row"><input type="checkbox" id="privacystatement" / required><label for="privacystatement"><?php echo LANG['privacystatementaccept'] ?></label></div>
        <p><a class="modalopen" href="/docs/terms.txt">Käyttöehdot</a></p>
        <p><a class="modalopen" href="/docs/tietosuojaseloste.txt">Tietosuojaseloste</a></p>
        <div class="g-recaptcha" data-sitekey="6LdiorseAAAAAJuDwns8fB7Ib7PM76166hz3b_zc"></div>
        <button type="submit"><?php echo LANG['register']; ?></button>
    </form>
    <p><?php echo LANG['forgotpassword']; ?><br><a href="/restorepassword">Palauta salasana</a></p>
    <hr>
    <p>
        <?php echo LANG['doyouhaveaccount']; ?><br>
        <a href="/login"><?php echo LANG['login']; ?></a>
    </p>
</div>
<div class="modal">
    <div class="modal_close">Sulje</div>
    <div class="modal_content"></div>
</div>
<script>
    $(document).ready(() => {
        $("#registerform").submit((e) => {
            e.preventDefault();
            let token = grecaptcha.getResponse();
            if (token.length < 1) {
                toastr.warning("You need to complete ReCaptcha.");
                return;
            }
            let data = {
                token: token,
                data: {}
            }
            data.data['firstname'] = $("#firstname").val();
            data.data['lastname'] = $("#lastname").val();
            data.data['email'] = $("#email").val();
            data.data['password'] = $("#password").val();
            data.data['passwordagain'] = $("#passwordagain").val();
            data.data['phonenumber'] = $("#phonenumber").val();

            if (data.data['password'] !== data.data['passwordagain']) {
                toastr.warning("<?php echo LANG['errorlist']['passwordnotmatch']; ?>");
                return;
            }

            if (!data.data['password'].match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/)) {
                toastr.warning("<?php echo LANG['errorlist']['passwordtooweak']; ?>");
                return;
            }

            $.ajax({
                type: 'POST',
                data: JSON.stringify(data),
                url: '/post/register'
            }).done((res) => {
                console.log(res);
                grecaptcha.reset();
                if (res.status) {
                    toastr.success(res.msg);
                    $("#firstname").val("");
                    $("#lastname").val("");
                    $("#email").val("");
                    $("#password").val("");
                    $("#passwordagain").val("");
                    $("#phonenumber").val("");
                } else {
                    toastr.warning(res.msg);
                }
            });
        });
        let privacystatementdoc;
        $(".modalopen").click((e) => {
            e.preventDefault();
            $.ajax({
                    type: 'GET',
                    url: e.target.getAttribute('href')
                })
                .done((res) => {
                    if (privacystatement !== res) {
                        privacystatementdoc = res;
                        $(".modal_content").text(res);
                    }
                });
            $("body").css('overflow', 'hidden');
            $(".modal_content").text(privacystatementdoc);
            $(".modal").css('display', 'flex');
        });
        $(".modal").click((e) => {
            if ($(e.target).is("div.modal_content")) {
                return;
            }
            $("body").attr('style', '');
            $(".modal").css('display', 'none');
        });
        $(".modal_close").click((e) => {
            e.preventDefault();
            $("body").attr('style', '');
            $(".modal").css('display', 'none');
        });
    });
</script>