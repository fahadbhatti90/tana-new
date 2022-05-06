document.addEventListener('DOMContentLoaded', function(e) {

    var validationForm = FormValidation.formValidation(
        document.getElementById('user_form'),
        {
            fields: {
                brand_name: {
                    validators: {
                        notEmpty: {
                            message: 'The Brand Name is required'
                        },
                        stringLength: {
                            min: 2,
                            max: 64,
                            message: 'The Brand Name must be more than 1 and less than 65 characters long'
                        },
                        regexp: {
                            regexp: /^([a-zA-Z0-9!@#$%^&*().,<>{}[\]<>?_=+\-|;:\'\"\/]+\s?)*$/,
                            message: 'Extra space are not allowed in the brand name'
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
        if($('#form_action').val() == 'Add') {
            action_url = base_url+"/brand";
            action_method = "POST";
            $("#action_button").button('loading');
            document.getElementById("action_button").value = "Creating Brand...";
        }
        //set route and method for updating role specific
        if($('#form_action').val() == 'Edit') {
            action_url = base_url+"/brand/"+$('#hidden_id').val();
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
                //set route and method for adding user
                if($('#form_action').val() == 'Add') {
                    $('#action_button').val('Add Brand');
                }
                //set route and method for updating role specific
                if($('#form_action').val() == 'Edit') {
                    $('#action_button').val('Edit Brand');
                }
                $("#action_button").attr("disabled", false);
                if(data.errors)
                {
                    var html = '';
                    $('#form_result').html('');
                    html += '<div class="alert alert-danger">';
                    for(var count = 0; count < data.errors.length; count++){
                        html += '<p>' + data.errors[count] + '</p>';
                    }
                    html += '</div>';
                    $('#form_result').html(html);
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

    //View all brand in datatabale
    $('#users_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url+"/brand",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { "data": "DT_RowIndex", "name": "DT_RowIndex", orderable: false, searchable: false},
            { data: 'brand_name',name: 'brand_name' },
            { data: 'is_active',name: 'is_active', orderable: false  },
            { data: 'action',name: 'action', orderable: false },
        ],
        order: [ [1, 'asc'] ],
    }).columns.adjust().draw();

    //Set Model for adding new user
    $('#create_record').click(function(){
        validationForm.resetForm(true);
        $('#form_result').html('');
        $('#brand_name').val("");
        $('#brand_model_title').text('Add New Brand Information');
        $('#action_button').val('Add Brand');
        $('#form_action').val('Add');
        $('#userModal').modal({backdrop: 'static', keyboard: false});
    });


    //get user data for updating
    $(document).on('click', '.edit', function(){
        var id = $(this).attr('id');
        $('#form_result').html('');
        $.ajax({
            url :base_url+"/brand/"+id+"/edit",
            dataType:"json",
            success:function(data)
            {
                //set user data in model and show model
                validationForm.resetForm(true);
                $('#brand_name').val(data.result.brand_name);
                $('#hidden_id').val(id);
                $('#form_result').html('');
                $('#brand_model_title').text('Edit Brand Information');
                $('#action_button').val('Edit Brand');
                $('#form_action').val('Edit');
                $('#userModal').modal({backdrop: 'static', keyboard: false});
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
        $('#form_result').html('');
        $.ajax({
            url :base_url+"/brand/status/"+id,
            data:data,
            type: "PUT",
            dataType:"json",
            success:function(data){
                //Reload datatable
                $('#users_table').DataTable().ajax.reload(null,false);
            }
        })
    });

    $(document).on('click', '.deleteBrand', function () {
        //get user and Status ID
        var id = $(this).attr('id');
        var status = $(this).val();

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this brand!",
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
                    url :base_url+"/brand/status/"+id,
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
                            text: "Brand is deleted",
                            confirmButtonClass: 'btn btn-success',
                        })
                    }
                });
            }
        });
    });

});
