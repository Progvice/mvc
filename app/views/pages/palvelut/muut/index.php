<?php echo $template->getBlock('isolaatikko'); ?>
<div class="section-12 wrap detailbox_container">
    <?php echo $template->getBlock('tietolaatikot'); ?>
</div>
<?php if (isset($_SESSION['login']) && false) { ?>
<div class="datepicker">
    <h1>Ajanvaraus</h1>
    <div class="dp_selectfields">
        <select name="appointment_title" id="appointment_title">
            <option value="maintenance"><?php echo LANG['maintenance']; ?></option>
            <option value="carwash"><?php echo LANG['carwash']; ?></option>
            <option value="tirechange"><?php echo LANG['tirechange']; ?></option>
            <option value="otherservices"><?php echo LANG['otherservices']; ?></option>
        </select>
        <input type="hidden" name="appointment_time" id="appointment_time" value="1"/>
        <input type="text" name="licenseplate" id="licenseplate" placeholder="Rekisterinumero (xxx-xxx)"/>
    </div>
    <textarea id="appointment_desc" placeholder="<?php echo LANG['appointmentdesc'] ?>"></textarea>
    <div class="dp_buttons">
        <input id="datepicker" placeholder="Valitse päivänmäärä" autocomplete="off"/>
    </div>
    <div class="datepicker_results" id="datepicker_results"></div>
    <button id="appointment_submit" type="submit">Varaa aika</button>
</div>
<script>
$(document).ready(function() {
    let date = new Date();
    let dateOutput = date.getDate() + '.' + date.getMonth() + '.' + date.getFullYear();
    let dayCounter = 0;
    let submit = {};
    async function loadTimeTables(dateObj) {
        let currentDate = new Date();
        let month = dateObj.getMonth() + 1;
        let finalMonth = ('0' + month).slice(-2);
        let finalDate = dateObj.getFullYear() + '-' + finalMonth + '-' + ('0' + dateObj.getDate()).slice(-2);
        let final_data = JSON.stringify({
            date: finalDate
        });
        submit.date = finalDate;
        currentDays = parseInt(currentDate.getTime() / 1000 / 86400);
        calendarDays = parseInt(dateObj.getTime() / 1000 / 86400);
        dayCounter = calendarDays - currentDays;
        console.log(dayCounter);
        $.ajax({
            type: "POST",
            url: "/appointments/read",
            data: final_data
        }).done((res) => {
            if (res.status === true) {
                $(".datepicker_results").empty();
                for (const slot in res.data) {

                    const time = new Date((res.data[slot].time * 3600) * 1000).toISOString().substr(11,
                        8);
                    const intSlot = parseInt(slot);
                    if (intSlot !== 43) {
                        const elem = '<div class="datepicker_time" data-slot="' + res.data[slot].slot +
                        '" data-clickable="' + res.data[slot].available + '">' + time + '</div>';
                        $(".datepicker_results").append(elem);
                    }
                };
            } else {
                console.log('Request failed');
            }
        });
    }
    $("#datepicker").datepicker({
        beforeShowDay: function(date) {
            let dayOfWeek = date.getDay();
            // 0 : Sunday, 1 : Monday, ...
            if (dayOfWeek == 0) return [false];
            else return [true];
        },
        onSelect: function() {
            let dateObj = $(this).datepicker('getDate');
            loadTimeTables(dateObj);
        }
    });
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
    $(".datepicker_results").on('click', '.datepicker_time', (e) => {
        if (e.target.attributes[2].value === "true") {
            $(".datepicker_time").attr("style", null);
            //console.log(e.target.attributes[1].value);
            let elemTitle = $("#appointment_title");
            let elemTime = $("#appointment_time");
            let time = parseInt(elemTime[0].value);
            let selectSlot = parseInt(e.target.attributes[1].value);
            let slotCount = time + selectSlot;

            let slots_from = selectSlot;
            let slots_to = slotCount;

            for(selectSlot; selectSlot < slotCount; selectSlot++) {
                let timeElement = $('*[data-slot="' + selectSlot + '"]');
                if (selectSlot >= 43) {
                    return;
                    break;
                }
                if (slotCount >= 44) {
                    toastr.warning('Aika menee yli sulkemisajan!');
                    return;
                    break;
                }
                if (timeElement[0].attributes[2].value === 'false') {
                    toastr.warning("Aika menee toisen ajan päälle! Valitse toinen aika.");
                    $(".datepicker_time").attr("style", null);
                    return;
                    break;
                }
                $('*[data-slot="' + selectSlot + '"]').css('background-color', 'black');
            }
            submit.slotfrom = slots_from;
            submit.slotto = slots_to;
        }
    });
    $("#appointment_submit").click((e) => {
        e.preventDefault();
        submit.title = $("#appointment_title").val();
        submit.description = $("#appointment_desc").val();
        submit.licenseplate = $("#licenseplate").val();
        
        const fsubmit = JSON.stringify(submit);
        $.ajax({
            type: "POST",
            url: "/appointments/create",
            data: fsubmit
        }).done((res) => {
            console.log(res);
            switch(res.status) {
                case true: 
                    toastr.success(res.msg);
                break;
                case false:
                    toastr.warning(res.msg);
                break;
            }
            loadTimeTables($("#datepicker").datepicker("getDate"));
        });
    });
});
</script>
<?php } 
else {
    Plugin::load('templateloader');
}
?>
