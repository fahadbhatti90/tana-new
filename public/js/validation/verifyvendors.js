document.addEventListener('DOMContentLoaded', function (e) {
    //Set Model for adding new user
    $(document).on('click', '.removeVendor', function () {
        var id = $(this).attr('id');
        var date = $(this).attr('name');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var data = 'vendor_info=' + vendor_id;
        $.ajax({
            url: base_url + "/verify/destroy/" + id + "/" + date + "",
            type: "PUT",
            data: data,
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    // $('#vendors_table1').DataTable().ajax.reload();
                    Swal.fire({
                        title: "Done",
                        text: data.success,
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    }).then(function () {
                        location.reload();
                    }
                    );
                }

            }
        })
    });

    //View all verify in datatabale
    $('#vendors_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/verify/vendors/" + vendor_id,
        },
        columns: [
            { data: 'Vendor_Name', name: 'Vendor_Name' },
            { data: 'SaleDate', name: 'SaleDate' },
            { data: 'Rows_Count', name: 'Rows_Count' },
            { data: 'Duplicate', name: 'Duplicate' },
            // { data: 'is_active', name: 'is_active', orderable: false },
            { data: 'action', name: 'action', orderable: false },
        ]
    });
});
