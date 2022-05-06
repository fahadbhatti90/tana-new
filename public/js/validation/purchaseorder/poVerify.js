document.addEventListener('DOMContentLoaded', function (e) {

    //To Remove record from database
    $(document).on('click', '.removeVendor', function () {

        var id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var data = 'vendor_info=' + vendor_id;
        $.ajax({
            url: base_url + "/purchaseVerify/destroyVendor/" + id + "",
            type: "PUT",
            data: data,
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    $('#users_table2').DataTable().ajax.reload();
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
    //View all vendors in datatabale
    $('#users_table2').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/purchaseVerify",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'vendor_name', name: 'vendor_name' },
            { data: 'No. of day(s)', name: 'No. of day(s)' },
            { data: 'Max OrderOn Date', name: 'Max OrderOn Date' },
            { data: 'Row(s) Count', name: 'Row(s) Count' },
            { data: 'Duplicate', name: 'Duplicate' },
            { data: 'action', name: 'action', orderable: false },
        ]
    });

    $('#move').click(function (e) {

        var rowCount = $('#users_table1 tbody tr').html().length;

        if (rowCount == 85) {
            // alert('yes');
            e.preventDefault();
        }
        var a = $('#anchor').attr("disabled");
        if (a == 'disabled') {
            e.preventDefault();
        } else {
            return true;
        }
    });
    // to remove flash msg
    setTimeout(function () {
        $('#success').fadeOut('fast');
    }, 5000);
});
// url: base_url + "/purchaseVerify/verify",
