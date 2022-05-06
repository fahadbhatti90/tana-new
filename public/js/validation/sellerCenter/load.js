$('#load_daily_seller_center_range').daterangepicker();

//on Submitting form call SP For Daily Seller center sales
$('#load_daily_seller_center_form').on('submit', function (event) {
    event.preventDefault();
    $("#load_daily_seller_center").attr("hidden", true);
    $("#load_daily_seller_center_loader").attr("hidden", false);
    //ajax call
    $.ajax({
        url: base_url + "/sellerCenter/load/daily",
        type: 'POST',
        data: $('#load_daily_seller_center_form').serialize(),
        dataType: "json",
        cache: false,
        success: function (data) {
            $("#load_daily_seller_center").attr("hidden", false);
            $("#load_daily_seller_center_loader").attr("hidden", true);
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
            if(errorData.length !== 0)
            {
                for (let i = 0; i < errorData.length; i++) {
                    toastr.error( errorData[i].Message, errorData[i].Code+" "+errorData[i].Level, {
                        "closeButton": true,
                        "showMethod": "slideDown",
                        "hideMethod": "slideUp",
                        timeOut: 5000
                    });
                }
            }else if (data.success) {
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
            $("#load_daily_seller_center").attr("hidden", false);
            $("#load_daily_seller_center_loader").attr("hidden", true);
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


