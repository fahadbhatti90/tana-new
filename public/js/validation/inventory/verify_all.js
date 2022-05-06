document.addEventListener('DOMContentLoaded', function (e) {
    //View all vendors in datatabale
    $('#inventory_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/inventory/verify_all",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'Vendor Name', name: 'Vendor Name' },
            { data: 'No. of day(s)', name: 'No. of day(s)' },
            { data: 'Max Date', name: 'Max Date' },
            { data: 'Row(s) Count', name: 'Row(s) Count' },
            { data: 'Duplicate', name: 'Duplicate' },
            { data: 'action', name: 'action', orderable: false },
        ]
    }).columns.adjust().draw();;

    //To Remove record from database
    $(document).on('click', '.removeVendor', function () {
        var id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/inventory/destroy/" + id + "",
            type: "PUT",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    $('#inventory_table').DataTable().ajax.reload();
                    Swal.fire({
                        title: "Done",
                        text: data.success,
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
            }
        })
    });
    // to remove flash msg
    setTimeout(function () {
        $('#success').fadeOut('fast');
    }, 5000);
});
