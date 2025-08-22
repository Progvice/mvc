$(".removePersonnel").on('click', function(e) {
    e.preventDefault();

    console.log('asdasdasd');

    const id = $(this).data('id');

    $.ajax({
        url: "/personel/delete/" + id,
        method: 'DELETE'
    }).done((res) => {
        if (res.status) {
            toastr.success("Henkilö poistettu onnistuneesti");
        }
    })
});