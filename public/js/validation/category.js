
document.addEventListener('DOMContentLoaded', function (e) {

    var validationForm = FormValidation.formValidation(
        document.getElementById('category_form'),
        {
            fields: {

                categoryFile: {
                    validators: {
                        file: {
                            extension: 'xlsx,csv,xls',
                            //type: 'application/vnd.ms-excel',
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

        var action_url = '';
        var action_method = '';

        //set route and method for adding user
        if ($('#action_button').val() == 'Upload in category') {
            // $('#action_button').val() == "Please Wait";
            document.getElementById("action_button").value = "Please wait Loading.....";
            $('#divid').load('page.php #divid'),
                $("#action_button").attr("disabled", true);
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
        var formData = new FormData($("#category_form").get(0));
        $.noConflict();
        $.ajax({
            url: base_url + "/category",
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,

            success: function (data) {

                if (data.error) {
                    window.setTimeout(function () { location.reload() }, 5000)
                    var html = '';
                    $('#form_result').html('');
                    html += '<div class="alert alert-danger">';
                    for (var count = 0; count < data.error.length; count++) {
                        html += '<p>' + data.error[count] + '</p>';
                    }
                    html += '</div>';
                    $('#form_result').html(html);
                    $('#custom-file-label').text("Choose");
                    $("#categoryFile").val(null);
                    // $('#categoryFile').removeClass('custom-file-input is-valid').addClass('custom-file-input');
                    $("#action_button").attr("disabled", false);
                    document.getElementById("action_button").value = "Upload in category";
                    validationForm.resetForm(true);
                }
                if (data.success) {
                    $('#custom-file-label').text("Choose");
                    //  $('#categoryFile').removeClass('custom-file-input is-valid').addClass('custom-file-input');
                    $("#action_button").attr("disabled", false);
                    $("#categoryFile").attr("disabled", false);
                    $("#categoryFile").val(null);
                    document.getElementById("action_button").value = "Upload in category";

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

        });

    });
    //to display value in input type file
    $(".custom-file-input").on("change", function () {

        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
});

