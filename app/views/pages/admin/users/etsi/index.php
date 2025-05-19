<div class="main section-12 column height-100">
    <div class="admin_infobox">
    <a href="javascript:history.go(-1)" class="admin_info_btn">Edellinen sivu</a>
        <div class="ordersearch">
            <form id="searchform" method="POST" action="/admin/users/etsi/action/search">
                <input type="text" name="firstname" id="firstname" placeholder="<?php echo LANG['firstname']; ?>" />
                <input type="text" name="lastname" id="lastname" placeholder="<?php echo LANG['lastname']; ?>""/>
                <input type="text" name="email" id="email" placeholder="<?php echo LANG['email']; ?>""/>
                <input type="text" name="phonenumber" id="phonenumber" placeholder="<?php echo LANG['phonenumber']; ?>""/>
                <button type="submit">Etsi käyttäjiä</button>
            </form>
        </div>
        <div class="results listcontainer"></div>
    </div>
</div>
<script>

$(document).ready(() => {
    $("#searchform").on('submit', (e) => {
        const firstname = $("#firstname").val();
        const lastname = $("#lastname").val();
        const email = $("#email").val();
        const phonenumber = $("#phonenumber").val();

        let data = {
            firstname: firstname,
            lastname: lastname,
            email: email,
            phonenumber: phonenumber
        };
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/admin/users/etsi/action/search',
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
            console.log(res.data);
            if (res.status) {
                $(".results").empty();
                if (res === undefined) {
                    toastr.warning('Kyytejä ei löytynyt');
                    return;
                }
                res.data.foreach (user => {
                    const html = `
                        <div class="row wrap section-12">
                            <div class="lc_item column section-20">
                                <div class="lci_sub">Rooli</div>
                                <div class="lci_sub">${user.permgroup}</div>
                            </div>
                            <div class="lc_item column section-20">
                                <div class="lci_sub">Etunimi</div>
                                <div class="lci_sub">${user.firstname}</div>
                            </div>
                            <div class="lc_item column section-20">
                                <div class="lci_sub">Sukunimi</div>
                                <div class="lci_sub">${user.lastname}</div>
                            </div>
                            <div class="lc_item column section-20">
                                <div class="lci_sub">Sähköposti</div>
                                <div class="lci_sub">${user.email}</div>
                            </div>
                            <div class="lc_item column section-20">
                                <div class="lci_sub">Toimenpiteet</div>
                                <div class="lci_sub"><a href="/admin/users/id/${user.uuid}">Muokkaa</a></div>
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