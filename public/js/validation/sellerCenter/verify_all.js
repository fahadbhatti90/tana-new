document.addEventListener('DOMContentLoaded', function (e) {
    //View all vendors in datatabale
    $('#sellerCenter_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/sellerCenter/verifyAll",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'vendor_id', name: 'vendor_id' },
            { data: 'vendor_name', name: 'vendor_name' },
            { data: 'no_of_days', name: 'no_of_days' },
            { data: 'max_sale_date', name: 'max_sale_date' },
            { data: 'rows_count', name: 'rows_count' },
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
            url: base_url + "/sellerCenter/destroy/" + id + "",
            type: "PUT",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    $('#sellerCenter_table').DataTable().ajax.reload();
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
