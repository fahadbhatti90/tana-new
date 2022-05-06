document.addEventListener('DOMContentLoaded', function (e) {
    //Set Model for adding new user
    $('#ptp_filter_form').on('submit', function (event) {
        event.preventDefault();

        //ajax call
        $.ajax({
            url: base_url + "/verifyPtp/store/1",
            type: 'POST',
            data: $('#ptp_filter_form').serialize(),
            dataType: "json",
            cache: false,
            success: function (data) {
                if (data.error) {
                    Swal.fire({
                        title: "Error",
                        text: data.error,
                        type: "info",
                        allowOutsideClick: false,
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
                if (data.success) {
                    Swal.fire({
                        title: "Done",
                        text: data.success,
                        type: "success",
                        allowOutsideClick: false,
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    }).then(function () {
                        location.reload();
                    }
                    );
                }
            }
        });
    });

    //View all verify in datatabale
    $('#ptp_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/verifyPtp",
        },
        columns: [
            { data: 'row_id', name: 'row_id' },
            { data: 'fk_vendor_name', name: 'fk_vendor_name' },
            { data: 'category_name', name: 'category_name' },
            { data: 'shipped_cogs', name: 'shipped_cogs' },
            { data: 'receipt_shipped_units', name: 'receipt_shipped_units' },
            { data: 'receipt_dollar', name: 'receipt_dollar' },
            { data: 'shipped_units', name: 'shipped_units' },
            { data: 'ptp_date', name: 'ptp_date' },
        ]
    });
    $('#moveData').click(function (e) {
        var a = document.getElementById("ptp_filter_vendor").value;
        if (a == null || a == "") {
            e.preventDefault();
        } else {
            return true;
        }
    });
    setTimeout(function () {
        $('#success').fadeOut('fast');
    }, 5000);
});
