//on Submitting form call SP For Daily Campaign
$('#load_daily_campaign_form').on('submit', function (event) {
    event.preventDefault();
    $("#load_daily_campaign").attr("hidden", true);
    $("#load_daily_campaign_loader").attr("hidden", false);
    //ajax call
    $.ajax({
        url: base_url + "/target/load/daily",
        type: 'POST',
        data: $('#load_daily_campaign_form').serialize(),
        dataType: "json",
        cache: false,
        success: function (data) {
            $("#load_daily_campaign").attr("hidden", false);
            $("#load_daily_campaign_loader").attr("hidden", true);
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
            $("#load_daily_campaign").attr("hidden", false);
            $("#load_daily_campaign_loader").attr("hidden", true);
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
//on Submitting form call SP For Weekly campaign
$('#load_weekly_campaign_form').on('submit', function (event) {
    event.preventDefault();
    $("#load_daily_keyword").attr("hidden", true);
    $("#load_daily_keyword_loader").attr("hidden", false);
    //ajax call
    $.ajax({
        url: base_url + "/keyword/load/daily",
        type: 'POST',
        data: $('#load_weekly_campaign_form').serialize(),
        dataType: "json",
        cache: false,
        success: function (data) {
            $("#load_daily_keyword").attr("hidden", false);
            $("#load_daily_keyword_loader").attr("hidden", true);
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
            $("#load_daily_keyword").attr("hidden", false);
            $("#load_daily_keyword_loader").attr("hidden", true);
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