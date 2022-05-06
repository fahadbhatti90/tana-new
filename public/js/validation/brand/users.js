document.addEventListener('DOMContentLoaded', function (e) {

    var validationForm = FormValidation.formValidation(
        document.getElementById('users_form'),
        {
            fields: {
                username: {
                    validators: {
                        notEmpty: {
                            message: 'The username is required'
                        },
                        stringLength: {
                            min: 4,
                            max: 64,
                            message: 'The username must be more than 3 and less than 65 characters long'
                        },
                        regexp: {
                            regexp: /^([a-zA-Z]+\s?)*$/,
                            message: 'The username can only consist of alphabetical and spaces'
                        },
                    }
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: 'The email address is required'
                        },
                        emailAddress: {
                            message: 'The input is not a valid email address'
                        },
                        regexp: {
                            regexp: /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/,
                            message: 'The email can only consist of small alphabetical, number, dot and address sign'
                        }
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

        $('#form_result').html("");
        $("#action_button").attr("disabled", true);

        //ajax call
        $.ajax({
            url: base_url + "/user/" + $('#hidden_id').val(),
            type: "PUT",
            data: $('#users_form').serialize(),
            dataType: "json",
            cache: false,
            success: function (data) {
                $("#action_button").attr("disabled", false);
                if (data.errors) {
                    var html = '';
                    html += '<div class="alert alert-danger">';
                    for (var count = 0; count < data.errors.length; count++) {
                        html += '<p>' + data.errors[count] + '</p>';
                    }
                    html += '</div>';

                    if ($('#form_action').val() == 'Edit') {
                        $('#action_button').val('Edit User');
                    }
                    $('#user_form_result').html(html);
                }
                if (data.success) {
                    $('#users_form')[0].reset();
                    //Refresh datatable
                    $('#users_table').DataTable().ajax.reload();
                    $('#userModal').modal('hide');
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

    var validationUserForm = FormValidation.formValidation(
        document.getElementById('assign_user_form'),
        {
            fields: {
                // user_info: {
                //     validators: {
                //         notEmpty: {
                //             message: 'Please select user'
                //         },
                //         choice: {
                //             min: 1,
                //             max: 1,
                //             message: 'Only 1 user is selected at a time'
                //         },
                //     }
                // },
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
        $("#assign_user_button").attr("disabled", true);
        //ajax call
        $.ajax({
            url: base_url + "/brand/assign/" + $('#add_brand_id').val(),
            type: "PUT",
            data: $('#assign_user_form').serialize(),
            dataType: "json",
            cache: false,
            success: function (data) {
                $("#assign_user_button").attr("disabled", false);
                if (data.success) {
                    $('#assign_user_form')[0].reset();
                    $('#users_table').DataTable().ajax.reload(null, false);
                    $('#addUserModal').modal('hide');
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

    //Set Model for adding new user
    $(document).on('click', '.removeUser', function () {
        var id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var data = 'brand_info=' + brand_id;
        $.ajax({
            url: base_url + "/brand/unassign/" + id + "",
            type: "PUT",
            data: data,
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                if (data.success) {
                    $('#users_table').DataTable().ajax.reload(null, false);
                    Swal.fire({
                        title: "Done",
                        text: data.success,
                        allowOutsideClick: false,
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }

            }
        })
    });

    //View all brand in datatabale
    $('#users_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/brand/users/" + brand_id,
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { "data": "DT_RowIndex", "name": "DT_RowIndex", orderable: false, searchable: false },
            { data: 'username', name: 'username' },
            { data: 'email', name: 'email' },
            { data: 'is_active', name: 'is_active', orderable: false },
            { data: 'action', name: 'action', orderable: false },
        ],
        order: [[1, 'asc']],
    }).columns.adjust().draw();


    //Set Model for adding new user
    $('#create_record').click(function () {
        $('#user_info').html("");
        var id = brand_id;
        validationUserForm.resetForm(true);
        $.ajax({
            url: base_url + "/brand/unassignedUsers/" + id + "",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                $('#add_brand_id').val(id);
                if (data.result && data.result.length > 0) {
                    var html = '<option value="" disabled>-- select Users --</option>';
                    for (var count = 0; count < data.result.length; count++) {
                        html += '<option value=' + data.result[count].user_id + '>' + data.result[count].username + '</option>';
                    }
                    $('#user_info').html(html);
                    $('#addUserModal').modal({ backdrop: 'static', keyboard: false });
                } else {
                    Swal.fire({
                        title: "User not found",
                        text: "Please add new users",
                        allowOutsideClick: false,
                        type: "info",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
            }
        })
    });


    //get user data for updating
    $(document).on('click', '.edit', function () {
        var id = $(this).attr('id');
        $('#form_result').html('');
        $.ajax({
            url: base_url + "/user/" + id + "/edit",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                validationForm.resetForm(true);
                $('#username').val(data.result.username);
                $('#email').val(data.result.email);
                $('#user_form_result').html("");
                $('#hidden_id').val(id);
                $('#user_modal_title').text('Edit User Information');
                $('#action_button').val('Edit User');
                $('#form_action').val('Edit');
                $('#userModal').modal({ backdrop: 'static', keyboard: false });
            }
        })
    });
    //change status in on checking or unchecking Checkbox

    $(document).on('click', '.status', function () {

        //get user and Status ID
        var id = $(this).attr('id');
        var status = $(this).val();
        //set CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var data = 'is_active=' + status;
        $.ajax({
            url: base_url + "/user/status/" + id,
            data: data,
            type: "PUT",
            dataType: "json",
            success: function (data) {
                //Reload datatable
                $('#users_table').DataTable().ajax.reload(null, false);
            }
        });
    });

    $(document).on('click', '.deleteUser', function () {
        //get user and Status ID
        var id = $(this).attr('id');
        var status = $(this).val();

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this user!",
            type: 'warning',
            showCancelButton: true,
            allowOutsideClick: false,
            confirmButtonText: 'OK',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {
                //set CSRF Token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var data = 'is_active=' + status;
                $.ajax({
                    url: base_url + "/user/status/" + id,
                    data: data,
                    type: "PUT",
                    dataType: "json",
                    success: function (data) {
                        //Reload datatable
                        $('#users_table').DataTable().ajax.reload(null, false);
                        Swal.fire({
                            type: "success",
                            title: 'Deleted!',
                            allowOutsideClick: false,
                            text: "User is deleted",
                            confirmButtonClass: 'btn btn-success',
                        })
                    }
                });
            }
        });
    });
    $('#assign_user_button').click(function () {
        var a = document.getElementById('user_info').value;
        if (a == "" || a == null) {
            Swal.fire({
                title: 'Cancelled',
                allowOutsideClick: false,
                text: 'Sorry! No User selected',
                type: 'error',
                confirmButtonClass: 'btn btn-danger',
            }).then(function () {
                location.reload();
            });

        } else {
            return true;
        }

    });
});
$(document).ready(function () {
    $("#user_info").select2({
        dropdownParent: $("#assign_user_form")
    });
});
