document.addEventListener('DOMContentLoaded', function (e) {
    //View all verify in datatabale
    $('#verify_ams_sp_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/ams/DetailAmsVerify/" + vendor_id + "/" + type + "/" + start + "/" + end,
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'Profile_name', name: 'Profile_name' },
            { data: 'Domian', name: 'Domian' },
            { data: 'Reprted_Date', name: 'Reprted_Date' },
            { data: 'COUNT(*)', name: 'COUNT(*)' },
            { data: 'Duplicate', name: 'Duplicate' },
            { data: 'action', name: 'action', orderable: false },
        ]
    }).columns.adjust().draw();;
    //View all verify in campaign sb datatabale
    $('#verify_ams_sb_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/ams/DetailAmsVerifySb/" + vendor_id + "/" + type + "/" + start + "/" + end,
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'Profile_name', name: 'Profile_name' },
            { data: 'Domian', name: 'Domian' },
            { data: 'Reprted_Date', name: 'Reprted_Date' },
            { data: 'COUNT(*)', name: 'COUNT(*)' },
            { data: 'Duplicate', name: 'Duplicate' },
            { data: 'action', name: 'action', orderable: false },
        ]
    }).columns.adjust().draw();;
    //View all verify in campaign sd datatabale
    $('#verify_ams_sd_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/ams/DetailAmsVerifySd/" + vendor_id + "/" + type + "/" + start + "/" + end,
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'Profile_name', name: 'Profile_name' },
            { data: 'Domian', name: 'Domian' },
            { data: 'Reprted_Date', name: 'Reprted_Date' },
            { data: 'COUNT(*)', name: 'COUNT(*)' },
            { data: 'Duplicate', name: 'Duplicate' },
            { data: 'action', name: 'action', orderable: false },
        ]
    }).columns.adjust().draw();;

    $(document).on('click', '.removeVendorSp', function () {
        let id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let data = 'received_date=' + id;
        $.ajax({
            url: base_url + "/ams/destroyAmsSp/" + vendor_id + "/" + type + "",
            type: "PUT",
            data: data,
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.errors) {
                    $('#verify_ams_sp_table').DataTable().ajax.reload();
                    Swal.fire({
                        title: "Invalid Record Found",
                        text: data.success,
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
                if (data.success) {
                    $('#verify_ams_sp_table').DataTable().ajax.reload();
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
    $(document).on('click', '.removeVendorSb', function () {
        let id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let data = 'received_date=' + id;
        $.ajax({
            url: base_url + "/ams/destroyAmsSb/" + vendor_id + "/" + type + "",
            type: "PUT",
            data: data,
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.errors) {
                    $('#verify_ams_sb_table').DataTable().ajax.reload();
                    Swal.fire({
                        title: "Invalid Record Found",
                        text: data.success,
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
                if (data.success) {
                    $('#verify_ams_sb_table').DataTable().ajax.reload();
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
    $(document).on('click', '.removeVendorSd', function () {
        let id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let data = 'received_date=' + id;
        $.ajax({
            url: base_url + "/ams/destroyAmsSd/" + vendor_id + "/" + type + "",
            type: "PUT",
            data: data,
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.errors) {
                    $('#verify_ams_sd_table').DataTable().ajax.reload();
                    Swal.fire({
                        title: "Invalid Record Found",
                        text: data.success,
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
                if (data.success) {
                    $('#verify_ams_sd_table').DataTable().ajax.reload();
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
