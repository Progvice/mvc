let debounceTimer;

$(".searchbar").on('input', function(e) {   // use input instead of change for live typing
    const val = $(this).val();

    clearTimeout(debounceTimer); // reset previous timer

    debounceTimer = setTimeout(() => {
        $.ajax({
            url: '/personel/read/q',
            method: 'POST',
            data: JSON.stringify({
                email: val
            }),
            contentType: 'application/json',
        }).done((res) => {
            console.log(res);
        });
    }, 500); // 500ms debounce delay
});
