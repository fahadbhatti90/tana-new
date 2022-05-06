$('#load_daily_traffic_range').daterangepicker();
$('#load_weekly_traffic_range').daterangepicker();
$('#load_monthly_traffic_range').daterangepicker();

//on Submitting form call SP For Daily Inventory
$('#load_daily_traffic_form').on('submit', function (event) {
    event.preventDefault();
    $("#load_daily_traffic").attr("hidden", true);
    $("#load_daily_traffic_loader").attr("hidden", false);
    //ajax call
    $.ajax({
        url: base_url + "/traffic/load/daily",
        type: 'POST',
        data: $('#load_daily_traffic_range').serialize(),
        dataType: "json",
        cache: false,
        success: function (data) {
            $("#load_daily_traffic").attr("hidden", false);
            $("#load_daily_traffic_loader").attr("hidden", true);
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
            $("#load_daily_traffic").attr("hidden", false);
            $("#load_daily_traffic_loader").attr("hidden", true);
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
//on Submitting form call SP For Weekly inventory
$('#load_weekly_traffic_form').on('submit', function (event) {
    event.preventDefault();
    $("#load_weekly_traffic").attr("hidden", true);
    $("#load_weekly_traffic_loader").attr("hidden", false);
    //ajax call
    $.ajax({
        url: base_url + "/traffic/load/weekly",
        type: 'POST',
        data: $('#load_weekly_traffic_form').serialize(),
        dataType: "json",
        cache: false,
        success: function (data) {
            $("#load_weekly_traffic").attr("hidden", false);
            $("#load_weekly_traffic_loader").attr("hidden", true);
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
            $("#load_weekly_traffic").attr("hidden", false);
            $("#load_weekly_traffic_loader").attr("hidden", true);
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
//on Submitting form call SP For monthly inventory
$('#load_monthly_traffic_form').on('submit', function (event) {
    event.preventDefault();
    $("#load_monthly_traffic").attr("hidden", true);
    $("#load_monthly_traffic_loader").attr("hidden", false);
    //ajax call
    $.ajax({
        url: base_url + "/traffic/load/monthly",
        type: 'POST',
        data: $('#load_monthly_traffic_form').serialize(),
        dataType: "json",
        cache: false,
        success: function (data) {
            $("#load_monthly_traffic").attr("hidden", false);
            $("#load_monthly_traffic_loader").attr("hidden", true);
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
            $("#load_monthly_traffic").attr("hidden", false);
            $("#load_monthly_traffic_loader").attr("hidden", true);
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

