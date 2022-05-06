document.addEventListener('DOMContentLoaded', function (e) {
    //Set Model for adding new user
    $('#category_filter_form').on('submit', function (event) {
        event.preventDefault();

        //ajax call
        $.ajax({
            url: base_url + "/verifyCategory/store/1",
            type: 'POST',
            data: $('#category_filter_form').serialize(),
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
    $('#category_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/verifyCategory",
        },
        columns: [
            { data: 'row_id', name: 'row_id' },
            { data: 'fk_vendor_name', name: 'fk_vendor_name' },
            { data: 'category', name: 'category' },
            { data: 'asin', name: 'asin' },
            { data: 'inserted_at', name: 'inserted_at' },
            { data: 'captured_at', name: 'captured_at' },
        ]
    });

    $('#moveData').click(function (e) {
        var a = document.getElementById("category_filter_vendor").value;
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
