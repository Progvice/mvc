<div class="main section-12 column height-100">
    <div class="admin_infobox">
        <div class="ordersearch">
            <form id="searchform" method="POST" action="/admin/kyytitilaukset/etsi/action/search">
                <input type="text" name="firstname" id="firstname" placeholder="<?php echo LANG['firstname']; ?>" />
                <input type="text" name="lastname" id="lastname" placeholder="<?php echo LANG['lastname']; ?>"/>
                <input type="text" name="email" id="email" placeholder="<?php echo LANG['email']; ?>"/>
                <input type="text" name="phonenumber" id="phonenumber" placeholder="<?php echo LANG['phonenumber']; ?>"/>
                <input type="text" name="date" id="date" placeholder="<?php echo LANG['date']; ?>"/>
                <input type="text" name="ordernumber" id="ordernumber" placeholder="<?php echo LANG['ordernumber']; ?>"/>
                <select id="status" name="status">
                    <option disabled selected><?php echo LANG['orderstatus']; ?></option>
                    <option value="unconfirmed"><?php echo LANG['unconfirmed']; ?></option>
                    <option value="cancelled"><?php echo LANG['cancelled']; ?></option>
                    <option value="confirmed"><?php echo LANG['confirmed']; ?></option>
                </select>
                <button type="submit">Etsi tietoja</button>
            </form>
        </div>
        <div class="results">

        </div>
    </div>
</div>
<script>

$(document).ready(() => {
    $("#searchform").on('submit', (e) => {
        const firstname = $("#firstname").val();
        const lastname = $("#lastname").val();
        const email = $("#email").val();
        const phonenumber = $("#phonenumber").val();

        const dateArray = $("#date").val().split('.');
        const dateArrReversed = dateArray.reverse();
        const finalDate = dateArrReversed.join('-');
        const ordernumber = $("#ordernumber").val();
        const status = $("#status").val();
        let data = {
            firstname: firstname,
            lastname: lastname,
            email: email,
            phonenumber: phonenumber,
            order_date: finalDate,
            order_status: status,
            uuid: ordernumber
        };
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/admin/kyytitilaukset/etsi/action/search',
            data: JSON.stringify(data)
        }).done((res) => {
            switch(res.status) {
                case true: 
                    toastr.success(res.msg);
                break;
                case false:
                    toastr.warning(res.msg);
                break;
            }
            console.log(res);
            if (res.status) {
                $(".results").empty();
                if (res === undefined) {
                    toastr.warning('Kyytejä ei löytynyt');
                    return;
                }
                res.data.foreach (ride => {

                    const dateArray = ride.order_date.split('-');
                    const dateArrReversed = dateArray.reverse();
                    const finalDate = dateArrReversed.join('.');
                    
                    const html = `
                        <div class="result">
                            <div class="result_col">
                                <div class="rc_box">ID</div>
                                <div class="rc_box">${ride.uuid}</div>
                            </div>
                            <div class="result_col">
                                <div class="rc_box">Status</div>
                                <div class="rc_box">${ride.order_status}</div>
                            </div>
                            <div class="result_col">
                                <div class="rc_box">Päivänmäärä</div>
                                <div class="rc_box">${finalDate}</div>
                            </div>
                            <div class="result_col">
                                <div class="rc_box">Kellonaika</div>
                                <div class="rc_box">${ride.order_time}</div>
                            </div>
                            <div class="result_col">
                                <div class="rc_box">Toimenpiteet</div>
                                <div class="rc_box"><a href="/admin/kyytitilaukset/id/${ride.uuid}">Muokkaa</a></div>
                            </div>
                        </div>                    
                    `;
                    $(".results").append(html);
                });
            }
        });
    })
});
</script>