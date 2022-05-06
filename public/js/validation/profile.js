document.addEventListener('DOMContentLoaded', function (e) {

    var change_password_form = document.getElementById('change_password_form');
    var changePasswordForm = FormValidation.formValidation(
        change_password_form,
        {
            fields: {
                current_password: {
                    validators: {
                        notEmpty: {
                            message: 'The current Password is required'
                        },
                    }
                },
                new_password: {
                    validators: {
                        notEmpty: {
                            message: 'The new password is required'
                        },
                        different: {
                            compare: function () {
                                return change_password_form.querySelector('[name="current_password"]').value;
                            },
                            message: 'New password must be different from current password'
                        },
                        stringLength: {
                            min: 6,
                            max: 19,
                            message: 'The new password must be more than 5 and less than 20 characters long'
                        },
                        regexp: {
                            regexp: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#^])[A-Za-z\d@$!%*?&#^]{6,19}$/,
                            message: 'Password must contain at least one uppercase letter, one lowercase letter, one number and one special character'
                        },
                    }
                },
                new_confirm_password: {
                    validators: {
                        notEmpty: {
                            message: 'The confirm password is required'
                        },
                        identical: {
                            compare: function () {
                                return change_password_form.querySelector('[name="new_confirm_password"]').value;
                            },
                            message: 'New password and confirm password do not match'
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

        $('#change_password_result').html('');
        //ajax call
        $.ajax({
            url: base_url + "/profile/changePassword",
            type: "POST",
            data: $('#change_password_form').serialize(),
            dataType: "json",
            cache: false,
            success: function (data) {

                if (data.errors) {
                    var html = '';
                    $('#change_password_result').html('');
                    html += '<div class="alert alert-danger">';
                    for (var count = 0; count < data.errors.length; count++) {
                        html += '<p>' + data.errors[count] + '</p>';
                    }
                    html += '</div>';
                    $('#change_password_result').html(html);
                }
                if (data.success) {
                    changePasswordForm.resetForm(true);
                    $('#changePassword').modal('hide');
                    Swal.fire({
                        title: "Done",
                        text: data.success,
                        allowOutsideClick: false,
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
            },
        });
    });

    // Revalidate the confirmation password when changing the password
    change_password_form.querySelector('[name="new_password"]').addEventListener('input', function () {
        changePasswordForm.revalidateField('new_confirm_password');
    });

    change_password_form.querySelector('[name="current_password"]').addEventListener('input', function () {
        changePasswordForm.revalidateField('new_password');
    });

    $(document).on('click', '.changePassword_option', function () {
        $('#change_password_result').html("");
        changePasswordForm.resetForm(true);
        $('#changePassword').modal({ backdrop: 'static', keyboard: false });
    });

    var switch_brand_form = document.getElementById('switch_brand_form');
    var switchBrandForm = FormValidation.formValidation(
        switch_brand_form,
        {
            fields: {
                switch_brand_info: {
                    validators: {
                        notEmpty: {
                            message: 'Please select brand'
                        },
                        choice: {
                            min: 1,
                            max: 1,
                            message: 'Only 1 User is selected at a time'
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

        $('#switch_brand_result').html('');
        //ajax call
        $.ajax({
            url: base_url + "/profile/switchBrand",
            type: "POST",
            data: $('#switch_brand_form').serialize(),
            dataType: "json",
            cache: false,
            success: function (data) {
                location.reload();
            },
        });
    });

    $(document).on('click', '.switchBrand', function () {
        $('#switch_brand_result').html("");
        //switchBrandForm.resetForm(true);
        $.ajax({
            url: base_url + "/profile/getBrands",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.result && data.result.length > 0) {
                    var html = '<option value="">-- Select Brand --</option>';
                    for (var count = 0; count < data.result.length; count++) {
                        html += '<option value=' + data.result[count].brand_id + '>' + data.result[count].brand_name + '</option>';
                    }
                    $('#switch_brand_info').html(html);
                    $('#switchBrand').modal({ backdrop: 'static', keyboard: false });
                } else {
                    Swal.fire({
                        title: "Brand not found",
                        text: "Please contact support team for help",
                        type: "info",
                        allowOutsideClick: false,
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
            }
        })
    });


    $(document).on('click', '.changeMode_option', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/profile/changeMode",
            type: "PUT",
            dataType: "json",
            cache: false,
            success: function (data) {
                if (data.success) {
                    location.reload();
                }
            },
        });
    });

});
// image icone change on menu expand and collapse
$("#img1").hide();
$("#img3").hide();
function hideImage1() {
    $("#img1").show();
    $("#img2").hide();
    $("#img3").show();
    $("#img4").hide();
}
function hideImage2() {
    $("#img1").hide();
    $("#img2").show();
    $("#img3").hide();
    $("#img4").show();
}


