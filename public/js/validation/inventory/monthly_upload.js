document.addEventListener('DOMContentLoaded', function (e) {

    var validationForm = FormValidation.formValidation(
        document.getElementById('vendor_inventory_form'),
        {
            fields: {
                vendor: {
                    validators: {
                        notEmpty: {
                            message: 'The Vendor  is required'
                        },
                    }
                },
                vendor_monthly_inventory: {
                    validators: {
                        file: {
                            extension: 'xlsx,csv,xls',
                            maxSize: 2097152,   // 2048 * 1024
                            message: 'The selected file is not valid'
                        },
                        notEmpty: {
                            message: 'The Excel File is required'
                        },
                    }
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap(),
                submitButton: new FormValidation.plugins.SubmitButton(),
                icon: new FormValidation.plugins.Icon({
                    valid: 'fa fa-check',
                    invalid: 'fa fa-times',
                    validating: 'fa fa-refresh',
                }),
            },
        }
    ).on('core.form.valid', function () {
        if ($('#vendor_action_button').val() == 'Upload in inventory') {
            $("#vendor_action_button").attr("hidden", true);
            $("#vendor_action_button_loader").attr("hidden", false);
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var formData = new FormData($("#vendor_inventory_form").get(0));
        $.noConflict();
        $.ajax({
            url: base_url + "/inventory-monthly/store",
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                $("#vendor_action_button").attr("hidden", false);
                $("#vendor_action_button_loader").attr("hidden", true);
                if (data.error) {
                    window.setTimeout(function () { location.reload() }, 5000)
                    var html = '';
                    $('#vendor_form_result').html('');
                    html += '<div class="alert alert-danger">';
                    for (var count = 0; count < data.error.length; count++) {
                        html += '<p>' + data.error[count] + '</p>';
                    }
                    html += '</div>';
                    $("#vendor_monthly_inventory").val(null);
                    $('#vendor_form_result').html(html);
                }
                if (data.success) {
                    $("#vendor_monthly_inventory").val(null);
                    Swal.fire({
                        title: "Done",
                        text: data.success,
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    }).then(function () {
                        location.reload();
                    });
                    validationForm.resetForm(true);
                }
            },
            error: function (xhr, httpStatusMessage, customErrorMessage) {
                $("#vendor_action_button").attr("hidden", false);
                $("#vendor_action_button_loader").attr("hidden", true);
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
    $(function () {
        // choose target dropdown
        var select = $('vendor_select');
        select.html(select.find('vendor_option').sort(function (x, y) {
            // to change to descending order switch "<" for ">"
            return $(x).text() > $(y).text() ? 1 : -1;
        }));
    });
    //to display value in input type file
    $(".custom-file-input").on("change", function () {

        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
});


