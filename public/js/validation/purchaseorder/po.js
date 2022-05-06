document.addEventListener('DOMContentLoaded', function (e) {
    var validationForm = FormValidation.formValidation(
        document.getElementById('po_form'),
        {
            fields: {
                vendor: {
                    validators: {
                        notEmpty: {
                            message: 'The Vendor  is required'
                        },
                    }
                },
                open_agg_file: {
                    validators: {
                        file: {
                            extension: 'xlsx,xls',
                            //type: 'application/vnd.ms-excel',
                            //maxSize: 2097152,   // 2048 * 1024 for 2mb
                            maxSize: 524288000,   // 512000 * 1024 for 500mb
                            message: 'The selected file is not valid'
                        },
                        // notEmpty: {
                        //     message: 'The Open aggrigated file is required'
                        // },

                    }
                },
                open_nonagg_file: {
                    validators: {
                        file: {
                            extension: 'xlsx,xls',
                            //type: 'application/vnd.ms-excel',
                            //maxSize: 2097152,   // 2048 * 1024
                            maxSize: 524288000,   // 512000 * 1024 for 500mb
                            message: 'The selected file is not valid'
                        },
                        // notEmpty: {
                        //     message: 'The Open Non aggrigated file is required'
                        // },

                    }
                },
                close_agg_file: {
                    validators: {
                        file: {
                            extension: 'xlsx,xls',
                            //type: 'application/vnd.ms-excel',
                            //maxSize: 2097152,   // 2048 * 1024
                            maxSize: 524288000,   // 512000 * 1024 for 500mb
                            message: 'The selected file is not valid'
                        },
                        // notEmpty: {
                        //     message: 'The Close Aggrigated file is required'
                        // },

                    }
                },
                close_nonagg_file: {
                    validators: {
                        file: {
                            extension: 'xlsx,xls',
                            // type: 'application/vnd.ms-excel',
                            // maxSize: 2097152,   // 2048 * 1024
                            maxSize: 524288000,   // 512000 * 1024 for 500mb
                            message: 'The selected file is not valid'
                        },
                        // notEmpty: {
                        //     message: 'The Close Non Aggrigated file is required'
                        // },

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

        var action_url = '';
        var action_method = '';

        //set route and method for adding user
        if ($('#action_button').val() == 'Upload') {
            $("#action_button").attr("hidden", true);
            $("#action_button_loader").attr("hidden", false);
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //Diffrent methods to submit data using ajax
        // var formData = $(this).serialize();
        // new FormData($(this)[0])
        // $('#sales_form').serialize()
        var formData = new FormData($("#po_form").get(0));
        $.noConflict();
        $.ajax({
            url: base_url + "/purchase/store/1",
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
                    $('#form_result').html('');
                    html += '<div class="alert alert-danger">';
                    for (var count = 0; count < data.error.length; count++) {
                        html += '<p>' + data.error[count] + '</p>';
                    }
                    html += '</div>';
                    $("#ptpfile").val(null);
                    $('#form_result').html(html);
                    $("#action_button").attr("disabled", false);
                    document.getElementById("action_button").value = "Upload";
                    validationForm.resetForm(true);
                }
                if (data.success) {
                    $("#ptpfile").val(null);
                    Swal.fire({
                        title: "Done",
                        text: data.success,
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,

                    }).then(function () {
                        location.reload();
                    }
                    );
                }
            },
            error: function (xhr, httpStatusMessage, customErrorMessage) {
                $("#action_button").attr("hidden", false);
                $("#action_button_loader").attr("hidden", true);
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
    //to display value in input type file
    $(".custom-file-input").on("change", function () {

        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
});
