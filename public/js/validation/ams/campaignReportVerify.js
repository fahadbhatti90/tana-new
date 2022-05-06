document.addEventListener('DOMContentLoaded', function (e) {



    $('#ams_error_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/ams/verifyCampaign",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'Vendor_id', name: 'Vendor_id' },
            { data: 'Vendor_Name', name: 'Vendor_Name' },
            { data: 'Domian', name: 'Domian' },
            { data: 'NO._of_Days', name: 'NO._of_Days' },
            { data: 'max_date', name: 'max_date' },
            { data: 'COUNT(*)', name: 'COUNT(*)' },
            { data: 'Duplicate', name: 'Duplicate' },
            { data: 'action', name: 'action', orderable: false },
        ]
    });
    $('#ams_error_table1').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/ams/verifyCampaign/SB",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'Vendor_id', name: 'Vendor_id' },
            { data: 'Vendor_Name', name: 'Vendor_Name' },
            { data: 'Domian', name: 'Domian' },
            { data: 'NO._of_Days', name: 'NO._of_Days' },
            { data: 'max_date', name: 'max_date' },
            { data: 'COUNT(*)', name: 'COUNT(*)' },
            { data: 'Duplicate', name: 'Duplicate' },
            { data: 'action', name: 'action', orderable: false },
        ]
    });
    $('#ams_error_table2').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/ams/verifyCampaign/SD",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'Vendor_id', name: 'Vendor_id' },
            { data: 'Vendor_Name', name: 'Vendor_Name' },
            { data: 'Domian', name: 'Domian' },
            { data: 'NO._of_Days', name: 'NO._of_Days' },
            { data: 'max_date', name: 'max_date' },
            { data: 'COUNT(*)', name: 'COUNT(*)' },
            { data: 'Duplicate', name: 'Duplicate' },
            { data: 'action', name: 'action', orderable: false },
        ]
    });
    //To Remove duplicate record from database SP
    $(document).on('click', '.removeDublication', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ams/deleteDuplicationSp",
            type: "Get",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    $('#ams_error_table').DataTable().ajax.reload();
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
    //To Remove duplicate record from database SB
    $(document).on('click', '.removeDublicationSb', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ams/deleteDuplicationSb",
            type: "Get",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    $('#ams_error_table').DataTable().ajax.reload();
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
    //To Remove duplicate record from database SD
    $(document).on('click', '.removeDublicationSd', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ams/deleteDuplicationSd",
            type: "Get",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    $('#ams_error_table').DataTable().ajax.reload();
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
    //To Move data record
    $(document).on('click', '.moveToCore', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ams/moveAllToCore",
            type: "Get",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    $('#ams_error_table').DataTable().ajax.reload();
                    $('#ams_error_table1').DataTable().ajax.reload();
                    $('#ams_error_table2').DataTable().ajax.reload();
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
    //To Remove record from database campaign SP table
    $(document).on('click', '.removeCampaignSpVendor', function () {
        var id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ams/deleteCampaignSp/" + id + "",
            type: "Get",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    $('#ams_error_table').DataTable().ajax.reload();
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
    //To Remove record from Campaign Sb table
    $(document).on('click', '.removeCampaignSbVendor', function () {
        var id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ams/deleteCampaignSb/" + id + "",
            type: "Get",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    $('#ams_error_table1').DataTable().ajax.reload();
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

    //To Remove record from Campaign Sb table
    $(document).on('click', '.removeCampaignSdVendor', function () {
        var id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ams/deleteCampaignSd/" + id + "",
            type: "Get",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    $('#ams_error_table2').DataTable().ajax.reload();
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
