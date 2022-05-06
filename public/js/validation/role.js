document.addEventListener('DOMContentLoaded', function(e) {

    var validationForm = FormValidation.formValidation(
        document.getElementById('addRole_form'),
        {
            fields: {
                role_name: {
                    validators: {
                        notEmpty: {
                            message: 'The role name is required'
                        },
                        stringLength: {
                            min: 4,
                            max: 29,
                            message: 'The role name must be more than 3 and less than 30 characters long'
                        },
                        regexp: {
                            regexp: /^([a-zA-Z]+\s?)*$/,
                            message: 'The role name can only consist of alphabetical and spaces'
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
    ).on('core.form.valid', function() {
        var action_url = '';
        var action_method = '';

        $('#form_result').html("");
        $("#action_button").attr("disabled", true);

        //set route and method for adding user
        if($('#action').val() == 'Add') {
            action_url = base_url+"/role";
            action_method = "POST";
        }
        //set route and method for updating role specific
        if($('#action').val() == 'Edit') {
            action_url = base_url+"/role/"+$('#hidden_id').val();
            action_method = "PUT";
        }
        //ajax call
        $.ajax({
            url:action_url,
            type:action_method,
            data: $('#addRole_form').serialize(),
            dataType:"json",
            cache: false,
            success:function(data){
                $("#action_button").attr("disabled", false);
                var html = '';
                if(data.errors)
                {
                    html += '<div class="alert alert-danger">';
                    for(var count = 0; count < data.errors.length; count++){
                        html += '<p>' + data.errors[count] + '</p>';
                    }
                    html += '</div>';
                    $('#form_result').html(html);
                }
                if(data.success)
                {
                    $('#addRole_form')[0].reset();
                    //Refresh datatable
                    $('#role_table').DataTable().ajax.reload(null,false);
                    $('#addRoleModal').modal('hide');
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
        });
    });

    //View all manager in datatabale
    $('#role_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url+"/role",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { data: 'sno',name: 'sno' },
            { data: 'role_name',name: 'role_name' },
            { data: 'action',name: 'action', orderable: false },
        ]
    }).columns.adjust().draw();

    //Set Model for adding new user
    $('#create_record').click(function(){
        validationForm.resetForm(true);
        $('#role_name').val("");
        $('#form_result').html("");
        $('#role_model_title').text('Add Role Information');
        $('#action_button').val('Add Role');
        $('#action').val('Add');
        $('#addRoleModal').modal({backdrop: 'static', keyboard: false});
    });


    //get user data for updating
    $(document).on('click', '.edit', function(){
        var id = $(this).attr('id');
        $('#form_result').html('');
        $.ajax({
            url :base_url+"/role/"+id+"/edit",
            dataType:"json",
            success:function(data)
            {
                //set user data in model and show model
                validationForm.resetForm(true);
                $('#role_name').val(data.result.role_name);
                $('#hidden_id').val(id);
                $('#form_result').html("");
                $('#role_model_title').text('Edit Role Information');
                $('#action_button').val('Edit Role');
                $('#action').val('Edit');
                $('#addRoleModal').modal({backdrop: 'static', keyboard: false});
            }
        })
    });
});
