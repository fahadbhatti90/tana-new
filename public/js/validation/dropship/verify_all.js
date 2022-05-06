document.addEventListener('DOMContentLoaded', function (e) {
    //View all vendors in datatabale
    $('#dropship_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/dropship/verifyAll",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'vendor_id', name: 'vendor_id' },
            { data: 'vendor_name', name: 'vendor_name' },
            { data: 'no_of_days', name: 'no_of_days' },
            { data: 'max_shipped_date', name: 'max_shipped_date' },
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
            url: base_url + "/dropship/destroy/" + id + "",
            type: "PUT",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    $('#dropship_table').DataTable().ajax.reload();
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

    //To Remove duplicate record from database
    $(document).on('click', '.removeDuplication', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/dropship/removeDuplication",
            type: "PUT",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    $('#dropship_table').DataTable().ajax.reload();
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
