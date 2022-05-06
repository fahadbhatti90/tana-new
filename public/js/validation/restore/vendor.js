document.addEventListener('DOMContentLoaded', function (e) {

    //View all admin in datatabale
    $('#vendors_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/user-vendors/restore",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { "data": "DT_RowIndex", "name": "DT_RowIndex", orderable: false, searchable: false },
            { data: 'vendor_name', name: 'vendor_name' },
            { data: 'region', name: 'region' },
            { data: 'tier', name: 'tier' },
            { data: 'action', name: 'action', orderable: false },
        ],
        order: [[1, 'asc']],
    }).columns.adjust().draw();

    $(document).on('click', '.restoreVendor', function () {
        //get user and Status ID
        var id = $(this).attr('id');
        var status = $(this).val();

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to restore this vendor!",
            type: 'warning',
            showCancelButton: true,
            allowOutsideClick: false,
            confirmButtonText: 'OK',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {
                //set CSRF Token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var data = 'is_active=' + status;
                $.ajax({
                    url: base_url + "/user-vendors/status/" + id,
                    data: data,
                    type: "PUT",
                    dataType: "json",
                    success: function (data) {
                        //Reload datatable
                        $('#vendors_table').DataTable().ajax.reload(null, false);
                        Swal.fire({
                            type: "success",
                            title: 'Restored!',
                            allowOutsideClick: false,
                            text: "Vendor is restored",
                            confirmButtonClass: 'btn btn-success',
                        })
                    }
                });
            }
        });
    });
});
