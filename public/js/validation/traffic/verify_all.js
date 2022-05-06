document.addEventListener('DOMContentLoaded', function (e) {
    //View all vendors in datatabale
    $('#traffic_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/traffic/verify_all",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'Vendor Name', name: 'Vendor Name' },
            { data: 'No. of day(s)', name: 'No. of day(s)' },
            { data: 'Max Sale Date', name: 'Max Sale Date' },
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
            url: base_url + "/traffic/destroy/" + id + "",
            type: "PUT",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    $('#traffic_table').DataTable().ajax.reload();
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
    //if dupication prevent move datqa
    $('#move').click(function (e) {
        var rowCount = $('#traffic_table tbody tr').html().length;
        if (rowCount == 72) {
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
