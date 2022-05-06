document.addEventListener('DOMContentLoaded', function (e) {
    $("#filter_range_picker").datetimepicker({
        format: 'MM/DD/YYYY',
        minDate: '09/09/1999',
        maxDate: new Date(),
        viewMode: 'months',
        date: new Date(),
    });
    $('#custom_data_value').daterangepicker({
        "minDate": '09/09/1999',
        "maxDate": new Date()
    });
    var custom_data_value1 = $('#custom_data_value').val();
    let filter_date_range = "";
    var reported_type = "";
    if (typeof (type) != "undefined" && type !== null) {
        var reported_type = $('#po_filter_range').val(type);
        var custom_data_value = $('#custom_data_value').val(date);
        $("#ed_filter_date_range").val($("#custom_data_value").val());
        var value = ($("#ed_filter_date_range").val()).split(" - ");
        var start_date_text = value[0];
        var end_date_text = value[1];
        var date_filter_range_value = moment(start_date_text, "MM/DD/YYYY").format("MMM DD, YYYY") + " - " + moment(end_date_text, "MM/DD/YYYY").format("MMM DD, YYYY");
        let filter_date_range = $("#ed_filter_date_range").val();
    }
    //on Submitting Vendor
    $('#filter_form').on('submit', function (event) {
        event.preventDefault();

        var reported_type = $('#po_filter_range').val();
        $("#ed_filter_date_range").val($("#custom_data_value").val());
        var value = ($("#ed_filter_date_range").val()).split(" - ");
        var start_date_text = value[0];
        var end_date_text = value[1];
        var date_filter_range_value = moment(start_date_text, "MM/DD/YYYY").format("MMM DD, YYYY") + " - " + moment(end_date_text, "MM/DD/YYYY").format("MMM DD, YYYY");
        let filter_date_range = $('#ed_filter_date_range').val();
        generateEDReportbytype(filter_date_range, reported_type);
    });

    function generateEDReportbytype(filter_date_range, reported_type) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ams/amsVerify/report",
            type: "POST",
            data: {
                date_range: filter_date_range,
                reported_type: reported_type,
            },
            cache: false,
            success: function (response) {
                if (response.error) {
                    Swal.fire({
                        title: "Error",
                        text: response.error,
                        allowOutsideClick: false,
                        type: "info",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                } else {
                    $('#ams_error_table').DataTable({
                        processing: true,
                        serverSide: true,
                        paging: true,
                        destroy: true,
                        searching: true,
                        stateSave: true,
                        ajax: {
                            url: base_url + "/ams/amsVerify/report",
                            type: "POST",
                            data: {
                                date_range: $('#custom_data_value').val(),
                                reported_type: $('#po_filter_range').val(),
                                checkDate: custom_data_value1,

                            },
                            cache: false,
                        },
                        language: {
                            "emptyTable": "No data found"
                        },
                        columns: [
                            { data: 'Profile_id', name: 'Profile_id' },
                            { data: 'Profile_name', name: 'Profile_name' },
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
                        paging: true,
                        destroy: true,
                        searching: true,
                        stateSave: true,
                        ajax: {
                            url: base_url + "/ams/amsVerify/verifySB",
                            type: "POST",
                            data: {
                                date_range: $('#custom_data_value').val(),
                                reported_type: $('#po_filter_range').val(),
                                checkDate: custom_data_value1,
                            },
                            cache: false,
                        },
                        language: {
                            "emptyTable": "No data found"
                        },
                        columns: [
                            { data: 'Profile_id', name: 'Profile_id' },
                            { data: 'Profile_name', name: 'Profile_name' },
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
                        paging: true,
                        destroy: true,
                        searching: true,
                        stateSave: true,
                        ajax: {
                            url: base_url + "/ams/amsVerify/verifySD",
                            type: "POST",
                            data: {
                                date_range: $('#custom_data_value').val(),
                                reported_type: $('#po_filter_range').val(),
                                checkDate: custom_data_value1,
                            },
                            cache: false,
                        },
                        language: {
                            "emptyTable": "No data found"
                        },
                        columns: [
                            { data: 'Profile_id', name: 'Profile_id' },
                            { data: 'Profile_name', name: 'Profile_name' },
                            { data: 'Domian', name: 'Domian' },
                            { data: 'NO._of_Days', name: 'NO._of_Days' },
                            { data: 'max_date', name: 'max_date' },
                            { data: 'COUNT(*)', name: 'COUNT(*)' },
                            { data: 'Duplicate', name: 'Duplicate' },
                            { data: 'action', name: 'action', orderable: false },
                        ]
                    });
                    $('#ams_error_table3').DataTable({
                        processing: true,
                        serverSide: true,
                        paging: true,
                        destroy: true,
                        searching: true,
                        stateSave: true,
                        ajax: {
                            url: base_url + "/ams/amsVerify/amsDashboard",
                            type: "Get",
                            data: {
                                date_range: $('#custom_data_value').val(),
                                reported_type: $('#po_filter_range').val(),
                                checkDate: custom_data_value1,
                            },
                            cache: false,
                        },
                        language: {
                            "emptyTable": "No data found"
                        },
                        columns: [
                            { data: 'profile_id', name: 'profile_id' },
                            { data: 'profile_name', name: 'profile_name' },
                            { data: 'report_type', name: 'report_type' },
                            { data: 'max_reported_date', name: 'max_reported_date' },
                            { data: 'inserted_at', name: 'inserted_at' },
                        ]

                    });
                }
            },
        });
    }

    $('#ams_error_table').DataTable({
        processing: true,
        serverSide: true,
        paging: true,
        destroy: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: base_url + "/ams/amsVerify/report",
            type: "POST",
            data: {
                date_range: $('#custom_data_value').val(),
                reported_type: $('#po_filter_range').val(),
                checkDate: custom_data_value1,
            },
            cache: false,
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'Profile_id', name: 'Profile_id' },
            { data: 'Profile_name', name: 'Profile_name' },
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
        paging: true,
        destroy: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: base_url + "/ams/amsVerify/verifySB",
            type: "POST",
            data: {
                date_range: $('#custom_data_value').val(),
                reported_type: $('#po_filter_range').val(),
                checkDate: custom_data_value1,
            },
            cache: false,
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'Profile_id', name: 'Profile_id' },
            { data: 'Profile_name', name: 'Profile_name' },
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
        paging: true,
        destroy: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: base_url + "/ams/amsVerify/verifySD",
            type: "POST",
            data: {
                date_range: $('#custom_data_value').val(),
                reported_type: $('#po_filter_range').val(),
                checkDate: custom_data_value1,
            },
            cache: false,
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'Profile_id', name: 'Profile_id' },
            { data: 'Profile_name', name: 'Profile_name' },
            { data: 'Domian', name: 'Domian' },
            { data: 'NO._of_Days', name: 'NO._of_Days' },
            { data: 'max_date', name: 'max_date' },
            { data: 'COUNT(*)', name: 'COUNT(*)' },
            { data: 'Duplicate', name: 'Duplicate' },
            { data: 'action', name: 'action', orderable: false },
        ]
    });
    $('#ams_error_table3').DataTable({
        processing: true,
        serverSide: true,
        paging: true,
        destroy: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: base_url + "/ams/amsVerify/amsDashboard",
            type: "Get",
            data: {
                date_range: $('#custom_data_value').val(),
                reported_type: $('#po_filter_range').val(),
                checkDate: custom_data_value1,
            },
            cache: false,
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'profile_id', name: 'profile_id' },
            { data: 'profile_name', name: 'profile_name' },
            { data: 'report_type', name: 'report_type' },
            { data: 'max_reported_date', name: 'max_reported_date' },
            { data: 'inserted_at', name: 'inserted_at' },
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
            data: {
                date_range: $('#custom_data_value').val(),
                reported_type: $('#po_filter_range').val(),
            },
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
            data: {
                date_range: $('#custom_data_value').val(),
                reported_type: $('#po_filter_range').val(),
            },
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
            data: {
                date_range: $('#custom_data_value').val(),
                reported_type: $('#po_filter_range').val(),
            },
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
    //To Move data record
    $(document).on('click', '.moveToCore', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ams/amsVerify/moveAllToCore",
            type: "Get",
            data: {
                date_range: $('#custom_data_value').val(),
                reported_type: $('#po_filter_range').val(),
            },
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    $('#ams_error_table3').DataTable({
                        processing: true,
                        serverSide: true,
                        paging: true,
                        destroy: true,
                        searching: true,
                        ajax: {
                            url: base_url + "/ams/amsVerify/amsDashboard",
                            type: "Get",
                            data: {
                                date_range: $('#custom_data_value').val(),
                                reported_type: $('#po_filter_range').val(),
                            },
                            cache: false,
                        },
                        language: {
                            "emptyTable": "No data found"
                        },
                        columns: [
                            { data: 'profile_id', name: 'profile_id' },
                            { data: 'profile_name', name: 'profile_name' },
                            { data: 'report_type', name: 'report_type' },
                            { data: 'max_reported_date', name: 'max_reported_date' },
                            { data: 'inserted_at', name: 'inserted_at' },
                        ]
                    });
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

    //To Move data record
    $('#generate_log_form').on('submit', function (event) {
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ams/amsVerify/generateLogTable",
            type: "GET",
            data: $('#generate_log_form').serialize(),
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    $('#ams_error_table').DataTable().ajax.reload();
                    $('#ams_error_table1').DataTable().ajax.reload();
                    $('#ams_error_table2').DataTable().ajax.reload();
                    $('#ams_error_table3').DataTable().ajax.reload();
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
    $(document).on('click', '.removeAmsSp', function () {
        var id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ams/deleteAmsSp/" + id + "",
            type: "Get",
            data: {
                date_range: $('#custom_data_value').val(),
                reported_type: $('#po_filter_range').val(),
            },
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
    $(document).on('click', '.removeAmsSbVendor', function () {
        var id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ams/deleteAmsSb/" + id + "",
            type: "Get",
            data: {
                date_range: $('#custom_data_value').val(),
                reported_type: $('#po_filter_range').val(),
            },
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
    $(document).on('click', '.removeAmsSdVendor', function () {
        var id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ams/deleteAmsSd/" + id + "",
            type: "Get",
            data: {
                date_range: $('#custom_data_value').val(),
                reported_type: $('#po_filter_range').val(),
            },
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
    $("#vendor-tab-fill").click(function () {
        $('#removeDublication').show();
        $("#removeDublication").attr("class", "removeDublication btn-icon btn btn-danger");
    });
    $("#vendorID-tab-fill").click(function () {
        $('#removeDublication').show();
        $("#removeDublication").attr("class", "removeDublicationSb btn-icon btn btn-danger");
    });
    $("#vendorID1-tab-fill").click(function () {
        $('#removeDublication').show();
        $("#removeDublication").attr("class", "removeDublicationSd btn-icon btn btn-danger");
    });
    $("#vendorID2-tab-fill").click(function () {
        $('#removeDublication').hide();
    });
    // to remove flash msg
    setTimeout(function () {
        $('#success').fadeOut('fast');
    }, 5000);
});
