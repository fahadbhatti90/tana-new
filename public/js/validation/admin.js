document.addEventListener('DOMContentLoaded', function(e) {

    var validationForm = FormValidation.formValidation(
        document.getElementById('user_form'),
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
                            message: 'The username can only consist of alphabets and spaces'
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
    ).on('core.form.valid', function() {

        var action_url = '';
        var action_method = '';

        $('#user_form_result').html("");
        $("#action_button").attr("disabled", true);
        //set route and method for adding user
        if($('#form_action').val() == 'Add') {
            action_url = base_url+"/admin";
            action_method = "POST";
            $('#action_button').val('Creating & sending Email...');
        }
        //set route and method for updating role specific
        if($('#form_action').val() == 'Edit') {
            action_url = base_url+"/admin/"+$('#hidden_id').val();
            action_method = "PUT";
        }
        //ajax call
        $.ajax({
            url:action_url,
            type:action_method,
            data: $('#user_form').serialize(),
            dataType:"json",
            cache: false,
            success:function(data){
                $("#action_button").attr("disabled", false);
                if(data.errors)
                {
                    var html = '';
                    html += '<div class="alert alert-danger">';
                    for(var count = 0; count < data.errors.length; count++){
                        html += '<p>' + data.errors[count] + '</p>';
                    }
                    html += '</div>';
                    if($('#form_action').val() == 'Add') {
                        $('#action_button').val('Add Admin');
                    }
                    if($('#form_action').val() == 'Edit') {
                        $('#action_button').val('Edit Admin');
                    }
                    $('#user_form_result').html(html);
                }
                if(data.success)
                {
                    $('#user_form')[0].reset();
                    //Refresh datatable
                    $('#users_table').DataTable().ajax.reload(null,false);
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

    //View all admin in datatabale
    $('#users_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url+"/admin",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { "data": "DT_RowIndex", "name": "DT_RowIndex", orderable: false, searchable: false},
            { data: 'username',name: 'username' },
            { data: 'email',name: 'email' },
            { data: 'is_active',name: 'is_active', orderable: false  },
            { data: 'action',name: 'action', orderable: false },
        ],
        order: [ [1, 'asc'] ],
    }).columns.adjust().draw();

    //Set Model for adding new user
    $('#create_record').click(function(){
        $('#username').val("");
        $('#email').val("");
        validationForm.resetForm(true);
        $('#user_form_result').html('');
        $('#admin_modal_title').text('Add New Admin Information');
        $('#action_button').val('Add Admin');
        $('#form_action').val('Add');
        $('#userModal').modal({backdrop: 'static', keyboard: false});
    });

    //get user data for updating
    $(document).on('click', '.edit', function(){
        var id = $(this).attr('id');
        $('#user_form_result').html('');
        $.ajax({
            url :base_url+"/admin/"+id+"/edit",
            dataType:"json",
            success:function(data)
            {
                //set user data in model and show model
                validationForm.resetForm(true);
                $('#username').val(data.result.username);
                $('#email').val(data.result.email);
                $('#hidden_id').val(id);
                $('#admin_modal_title').text('Edit Admin Information');
                $('#action_button').val('Edit Admin');
                $('#form_action').val('Edit');
                $('#userModal').modal({backdrop: 'static', keyboard: true});
            }
        })
    });
    //change status in on checking or unchecking Checkbox
    $(document).on('click', '.status', function(){

        //get user and Status ID
        var id = $(this).attr('id');
        var status = $(this).val();
        //set CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var data = 'is_active='+status;
        $('#user_form_result').html('');
        $.ajax({
            url :base_url+"/admin/status/"+id,
            data:data,
            type: "PUT",
            dataType:"json",
            success:function(data){
                //Reload datatable
                $('#users_table').DataTable().ajax.reload(null,false);
            }
        })
    });

    $(document).on('click', '.deleteUser', function () {
        //get user and Status ID
        var id = $(this).attr('id');
        var status = $(this).val();

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this admin!",
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
                var data = 'is_active='+status;
                $.ajax({
                    url : base_url+"/user/status/"+id,
                    data: data,
                    type: "PUT",
                    dataType:"json",
                    success: function (data) {
                        //Reload datatable
                        $('#users_table').DataTable().ajax.reload(null,false);
                        Swal.fire({
                            type: "success",
                            title: 'Deleted!',
                            allowOutsideClick: false,
                            text: "Admin is deleted",
                            confirmButtonClass: 'btn btn-success',
                        })
                    }
                });
            }
        });
    });
});
