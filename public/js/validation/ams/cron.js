document.addEventListener('DOMContentLoaded', function(e) {

    $("#cron_time").select2({
        dropdownParent: $("#change_cron_form"),
        language: {
            noResults: function (e) {
                return "No reporting range found";
            },
        }
    });

    var validationForm = FormValidation.formValidation(
        document.getElementById('change_cron_form'),
        {
            fields: {
                cron_name: {
                    validators: {
                        notEmpty: {
                            message: 'The schedule time is required'
                        },
                        stringLength: {
                            min: 2,
                            max: 64,
                            message: 'The schedule name must be more than 1 and less than 65 characters long'
                        },
                        regexp: {
                            regexp: /^([a-zA-Z0-9!@#$%^&*().,<>{}[\]<>?_=+\-|;:\'\"\/]+\s?)*$/,
                            message: 'Extra space are not allowed in the schedule name'
                        },
                    }
                },
                cron_time: {
                    validators: {
                        notEmpty: {
                            message: 'The schedule time is required'
                        },
                        choice: {
                            min: 1,
                            max: 1,
                            message: 'The schedule time is required'
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

        //set route and method for updating role specific
        if($('#action').val() == 'Edit') {
            action_url = base_url+"/ams/cron/time/"+$('#hidden_id').val();
            action_method = "PUT";
        }
        //ajax call
        $.ajax({
            url:action_url,
            type:action_method,
            data: $('#change_cron_form').serialize(),
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
                    $('#change_cron_form')[0].reset();
                    //Refresh datatable
                    $('#cron_table').DataTable().ajax.reload(null,false);
                    $('#changeCornModal').modal('hide');
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
    $('#cron_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url+"/ams/cron",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { "data": "DT_RowIndex", "name": "DT_RowIndex", orderable: false, searchable: false},
            { data: 'cron_name',name: 'cron_name' },
            { data: 'cron_type',name: 'cron_type' },
            { data: 'cron_time',name: 'cron_time' },
            { data: 'next_run',name: 'next_run' },
            { data: 'status',name: 'status', orderable: false },
            { data: 'action',name: 'action', orderable: false },
        ],
        "lengthMenu": [[16, 25, 50, -1], [16, 25, 50, "All"]],
        order: [ [2, 'desc'] ],
    }).columns.adjust().draw();

    //get user data for updating
    $(document).on('click', '.edit', function(){
        var id = $(this).attr('id');
        $('#form_result').html('');
        $.ajax({
            url :base_url+"/ams/"+id+"/edit",
            dataType:"json",
            success:function(data)
            {
                //set user data in model and show model
                validationForm.resetForm(true);
                $('#cron_name').val(data.result.cron_name);
                var html =  '<option value="" disabled>-- select schedule time --</option>';
                for(let i=0; i < 24; i++){
                    let value = (i < 10 ? '0' : '') + i + ':00:00';
                    if(data.result.cron_time == value){
                        html += '<option selected value="' +value+ '">' + value + '</option>';
                    }else{
                        html += '<option  value="' +value+ '">' + value + '</option>';
                    }
                }
                $('#cron_time').html(html);
                $('#hidden_id').val(id);
                $('#form_result').html("");
                $('#action_button').val('Edit Schedule');
                $('#action').val('Edit');
                $('#changeCornModal').modal({backdrop: 'static', keyboard: false});
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
        var data = 'cron_status='+status;
        $('#user_form_result').html('');
        $.ajax({
            url :base_url+"/ams/cron/status/"+id,
            data:data,
            type: "PUT",
            dataType:"json",
            success:function(data){
                //Reload datatable
                $('#cron_table').DataTable().ajax.reload(null,false);
            }
        })
    });

});
