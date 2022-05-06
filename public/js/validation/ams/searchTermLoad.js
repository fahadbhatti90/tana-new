$('#load_daily_search_term_range').daterangepicker();
$('#load_weekly_search_term_range').daterangepicker();
$('#load_monthly_search_term_range').daterangepicker();

//on Submitting form call SP For Daily search term
$('#load_daily_search_term_form').on('submit', function (event) {
    event.preventDefault();
    $("#load_daily_search_term").attr("hidden", true);
    $("#load_daily_search_term_loader").attr("hidden", false);
    //ajax call
    $.ajax({
        url: base_url + "/search_term/load/daily",
        type: 'POST',
        data: $('#load_daily_search_term_form').serialize(),
        dataType: "json",
        cache: false,
        success: function (data) {
            $("#load_daily_search_term").attr("hidden", false);
            $("#load_daily_search_term_loader").attr("hidden", true);
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
            let errorData = data.response;
            if (errorData.length !== 0) {
                for (let i = 0; i < errorData.length; i++) {
                    toastr.error(errorData[i].Message, errorData[i].Code + " " + errorData[i].Level, {
                        "closeButton": true,
                        "showMethod": "slideDown",
                        "hideMethod": "slideUp",
                        timeOut: 5000
                    });
                }
            } else if (data.success) {
                Swal.fire({
                    title: "Done",
                    text: data.success,
                    type: "success",
                    allowOutsideClick: false,
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            }
        },
        error: function (xhr, httpStatusMessage, customErrorMessage) {
            $("#load_daily_search_term").attr("hidden", false);
            $("#load_daily_search_term_loader").attr("hidden", true);
            Swal.fire({
                title: xhr.status + " Error",
                text: customErrorMessage,
                type: "info",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            }).then(function () {
                location.reload();
            });
        }
    });
});
//on Submitting form call SP For Weekly search term
$('#load_weekly_search_term_form').on('submit', function (event) {
    event.preventDefault();
    $("#load_weekly_search_term").attr("hidden", true);
    $("#load_weekly_search_term_loader").attr("hidden", false);
    //ajax call
    $.ajax({
        url: base_url + "/search_term/load/weekly",
        type: 'POST',
        data: $('#load_weekly_search_term_form').serialize(),
        dataType: "json",
        cache: false,
        success: function (data) {
            $("#load_weekly_search_term").attr("hidden", false);
            $("#load_weekly_search_term_loader").attr("hidden", true);
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
            let errorData = data.response;
            if (errorData.length !== 0) {
                for (let i = 0; i < errorData.length; i++) {
                    toastr.error(errorData[i].Message, errorData[i].Code + " " + errorData[i].Level, {
                        "closeButton": true,
                        "showMethod": "slideDown",
                        "hideMethod": "slideUp",
                        timeOut: 5000
                    });
                }
            } else if (data.success) {
                Swal.fire({
                    title: "Done",
                    text: data.success,
                    type: "success",
                    allowOutsideClick: false,
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            }
        },
        error: function (xhr, httpStatusMessage, customErrorMessage) {
            $("#load_weekly_search_term").attr("hidden", false);
            $("#load_weekly_search_term_loader").attr("hidden", true);
            Swal.fire({
                title: xhr.status + " Error",
                text: customErrorMessage,
                type: "info",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            }).then(function () {
                location.reload();
            });
        }
    });
});
//on Submitting form call SP For monthly search term
$('#load_monthly_search_term_form').on('submit', function (event) {
    event.preventDefault();
    $("#load_monthly_search_term").attr("hidden", true);
    $("#load_monthly_search_term_loader").attr("hidden", false);
    //ajax call
    $.ajax({
        url: base_url + "/search_term/load/monthly",
        type: 'POST',
        data: $('#load_monthly_search_term_form').serialize(),
        dataType: "json",
        cache: false,
        success: function (data) {
            $("#load_monthly_search_term").attr("hidden", false);
            $("#load_monthly_search_term_loader").attr("hidden", true);
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
            let errorData = data.response;
            if (errorData.length !== 0) {
                for (let i = 0; i < errorData.length; i++) {
                    toastr.error(errorData[i].Message, errorData[i].Code + " " + errorData[i].Level, {
                        "closeButton": true,
                        "showMethod": "slideDown",
                        "hideMethod": "slideUp",
                        timeOut: 5000
                    });
                }
            } else if (data.success) {
                Swal.fire({
                    title: "Done",
                    text: data.success,
                    type: "success",
                    allowOutsideClick: false,
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            }
        },
        error: function (xhr, httpStatusMessage, customErrorMessage) {
            $("#load_monthly_search_term").attr("hidden", false);
            $("#load_monthly_search_term_loader").attr("hidden", true);
            Swal.fire({
                title: xhr.status + " Error",
                text: customErrorMessage,
                type: "info",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            }).then(function () {
                location.reload();
            });
        }
    });
});
$('#dashboard').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: base_url + "/ams/load/dashboard/search_term",
    },
    language: {
        "emptyTable": "No data found"
    },
    columns: [
        { data: 'profile_id', name: 'profile_id' },
        { data: 'profile_name', name: 'profile_name' },
        { data: 'report_type', name: 'report_type' },
        { data: 'daily_max_date', name: 'daily_max_date' },
        { data: 'weekly_max_date', name: 'weekly_max_date' },
        { data: 'monthly_max_date', name: 'monthly_max_date' },
        { data: 'inserted_at', name: 'inserted_at' },
    ]
});
