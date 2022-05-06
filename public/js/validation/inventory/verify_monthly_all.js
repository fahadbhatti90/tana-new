document.addEventListener('DOMContentLoaded', function (e) {

    $('#move').click(function (e) {//to prevent move duplicated data
        var rowCount = $('#inventory_monthly_table tbody tr').html().length;
        if (rowCount == 72) {
            e.preventDefault();
        }
        var a = $('#anchor').attr("disabled");
        if (a == 'disabled') {
            Swal.fire({
                title: 'Cancelled',
                allowOutsideClick: false,
                text: 'Error! Remove Duplication',
                type: 'error',
                confirmButtonClass: 'btn btn-danger',
            })
            e.preventDefault();
        } else {
            return true;
        }
    });
    //View all vendors in datatabale
    $('#inventory_monthly_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/inventory-monthly/verify_all",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'Vendor Name', name: 'Vendor Name' },
            { data: 'No. of month(s)', name: 'No. of month(s)' },
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
            url: base_url + "/inventory-monthly/destroy/" + id + "",
            type: "PUT",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    $('#inventory_monthly_table').DataTable().ajax.reload();
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
