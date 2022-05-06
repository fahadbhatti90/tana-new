document.addEventListener('DOMContentLoaded', function (e) {
    //View all verify in datatabale
    $('#verify_campaign_sp_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/ams/DetailverifyCampaign/" + vendor_id,
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'Vendor_Name', name: 'Vendor_Name' },
            { data: 'Domian', name: 'Domian' },
            { data: 'Reprted_Date', name: 'Reprted_Date' },
            { data: 'COUNT(*)', name: 'COUNT(*)' },
            { data: 'Duplicate', name: 'Duplicate' },
            { data: 'action', name: 'action', orderable: false },
        ]
    }).columns.adjust().draw();;
    //View all verify in campaign sb datatabale
    $('#verify_campaign_sb_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/ams/DetailverifyCampaignSb/" + vendor_id,
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'Vendor_Name', name: 'Vendor_Name' },
            { data: 'Domian', name: 'Domian' },
            { data: 'Reprted_Date', name: 'Reprted_Date' },
            { data: 'COUNT(*)', name: 'COUNT(*)' },
            { data: 'Duplicate', name: 'Duplicate' },
            { data: 'action', name: 'action', orderable: false },
        ]
    }).columns.adjust().draw();;
    //View all verify in campaign sd datatabale
    $('#verify_campaign_sd_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/ams/DetailverifyCampaignSd/" + vendor_id,
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'Vendor_Name', name: 'Vendor_Name' },
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
            url: base_url + "/ams/destroyCampaignSp/" + vendor_id + "",
            type: "PUT",
            data: data,
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.errors) {
                    $('#verify_campaign_sp_table').DataTable().ajax.reload();
                    Swal.fire({
                        title: "Invalid Record Found",
                        text: data.success,
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
                if (data.success) {
                    $('#verify_campaign_sp_table').DataTable().ajax.reload();
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
            url: base_url + "/ams/destroyCampaignSb/" + vendor_id + "",
            type: "PUT",
            data: data,
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.errors) {
                    $('#verify_campaign_sb_table').DataTable().ajax.reload();
                    Swal.fire({
                        title: "Invalid Record Found",
                        text: data.success,
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
                if (data.success) {
                    $('#verify_campaign_sb_table').DataTable().ajax.reload();
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
            url: base_url + "/ams/destroyCampaignSd/" + vendor_id + "",
            type: "PUT",
            data: data,
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.errors) {
                    $('#verify_campaign_sd_table').DataTable().ajax.reload();
                    Swal.fire({
                        title: "Invalid Record Found",
                        text: data.success,
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
                if (data.success) {
                    $('#verify_campaign_sd_table').DataTable().ajax.reload();
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
