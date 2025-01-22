<div class="order_form">
    <form method="post" action="/rides/create" id="tilausformi">
        <p>Kaikki * -merkillä merkityt kentät ovat pakollisia!</p>
        <div class="section-12 row">
            <input type="text" name="a_start" id="a_start" placeholder="Lähtöosoite*" required />
            <input type="text" name="a_end" id="a_end" placeholder="Kohdeosoite*" required />
        </div>
        <div class="section-12 row">
            <input type="text" name="firstname" id="firstname" value="<?php echo isset($firstname) ? $firstname : '' ?>" placeholder="<?php echo LANG['firstname']; ?>*"
                required />
            <input type="text" name="lastname" id="lastname" value="<?php echo isset($lastname) ? $lastname : '' ?>" placeholder="<?php echo LANG['lastname']; ?>*" required />
        </div>
        <div class="section-12 row">
            <input type="text" name="email" id="email" value="<?php echo isset($email) ? $email : '' ?>" placeholder="<?php echo LANG['email']; ?>*" required />
            <input type="text" name="phonenumber" id="phonenumber" value="<?php echo isset($phonenumber) ? $phonenumber : '' ?>" placeholder="<?php echo LANG['phonenumber']; ?>*"
                required />
        </div>
        <div class="section-12 row">
            <input type="text" id="datepicker" placeholder="<?php echo LANG['date']; ?>*" required autocomplete="off"/>
            <input type="number" name="hlomaara" id="passengers" min="1" max="99" placeholder="Henkilömäärä*" required />
        </div>
        <div class="section-12 row">
            <input type="number" id="hours" placeholder="<?php echo LANG['hours']; ?>* (0 - 23)" name="hours" min="0"
                max="23" required />
            <input type="number" id="minutes" placeholder="<?php echo LANG['minutes']; ?>* (0 - 59)" name="minutes"
                min="0" max="59" required />
        </div>
        <textarea rows="6" id="description" name="info"
            placeholder="Kirjoita tähän lisätietoja tilauksestasi jos esimerkiksi tarvitset invataxia käyttöösi."></textarea>
        <div class="g-recaptcha" data-sitekey="6LdiorseAAAAAJuDwns8fB7Ib7PM76166hz3b_zc"></div>
        <button id="tf_submit" data-sitekey="6LfngqseAAAAABp3f1htpLMhEsBlpxNqmcKe-xOq" type="submit">TILAA
            KYYTI</button>
    </form>
</div>
<script>
$(document).ready(() => {
    $("#datepicker").datepicker();
    $("#datepicker").datepicker("option", "dateFormat", 'dd.mm.yy');
    $("#datepicker").datepicker("option", "minDate", "+2D");
    $("#datepicker").datepicker("option", "defaultDate", "+3d");
    $("#datepicker").datepicker("option", "firstDay", 1);
    $("#datepicker").datepicker("option", "dayNamesMin", ["Su", "Ma", "Ti", "Ke", "To", "Pe", "La"]);
    $("#datepicker").datepicker("option", "monthNames", ["Tammikuu", "Helmikuu", "Maaliskuu", "Huhtikuu",
        "Toukokuu", "Kesäkuu", "Heinäkuu", "Elokuu", "Syyskuu", "Lokakuu", "Marraskuu", "Joulukuu"
    ]);
    $("#datepicker").datepicker("option", "monthNameShort", ["Tammikuu", "Helmikuu", "Maaliskuu", "Huhtikuu",
        "Toukokuu", "Kesäkuu", "Heinäkuu", "Elokuu", "Syyskuu", "Lokakuu", "Marraskuu", "Joulukuu"
    ]);
    $("#tilausformi").submit((e) => {
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
        let minutes = $("#minutes").val();
        let hours = $("#hours").val();
        if (minutes.length === 1) {
            minutes = '0' + minutes;
        }
        if (hours.length === 1) {
            hours = '0' + hours;
        }
        // Here we change format of date so that database can store it properly
        const dateArray = $("#datepicker").val().split('.');
        const dateArrReversed = dateArray.reverse();
        const finalDate = dateArrReversed.join('-');
        data.data['address_from'] = $("#a_start").val();
        data.data['address_to'] = $("#a_end").val();
        data.data['firstname'] = $("#firstname").val();
        data.data['lastname'] = $("#lastname").val();
        data.data['email'] = $("#email").val();
        data.data['phonenumber'] = $("#phonenumber").val();
        data.data['passengers'] = $("#passengers").val();
        data.data['order_desc'] = $("#description").val();
        data.data['order_date'] = finalDate;
        data.data['order_time'] = $("#hours").val() + ':' + $("#minutes").val();
        $.ajax({
            method: "POST",
            url: "/kyydit/create",
            data: JSON.stringify(data)
        }).done((res) => {
            console.log(res);
            if (res.status) {
                grecaptcha.reset();
                $("#a_start").val('');
                $("#a_end").val('');
                $("#firstname").val('');
                $("#lastname").val('');
                $("#email").val('');
                $("#phonenumber").val('');
                $("#passengers").val('');
                $("#description").val('');
                $("#datepicker").val('');
                $("#hours").val('');
                $("#minutes").val('');
                toastr.success(res.msg);
            }
        });
    });
});
</script>