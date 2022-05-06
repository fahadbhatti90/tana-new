document.addEventListener('DOMContentLoaded', function (e) {
    //View all verify in datatabale
    $('#verify_vendors_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/sellerCenter/verify/" + vendor_id,
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'vendor_name', name: 'vendor_name' },
            { data: 'sale_date', name: 'sale_date' },
            { data: 'rows_count', name: 'rows_count' },
            { data: 'Duplicate', name: 'Duplicate' },
            { data: 'action', name: 'action', orderable: false },
        ]
    }).columns.adjust().draw();;

    $(document).on('click', '.removeVendor', function () {
        let id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let data = 'received_date=' + id;
        $.ajax({
            url: base_url + "/sellerCenter/destroyDate/" + vendor_id + "",
            type: "PUT",
            data: data,
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.errors) {
                    $('#verify_vendors_table').DataTable().ajax.reload();
                    Swal.fire({
                        title: "Invalid Record Found",
                        text: data.success,
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
                if (data.success) {
                    $('#verify_vendors_table').DataTable().ajax.reload();
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
});
