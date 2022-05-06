document.addEventListener('DOMContentLoaded', function(e) {

    var validationForm = FormValidation.formValidation(
        document.getElementById('po_plan_form'),
        {
            fields: {
                po_value: {
                    validators: {
                        notEmpty: {
                            message: 'The PO value is required'
                        },
                        integer: {
                            message: 'The value is not an integer',
                        }
                    }
                },
                po_unit: {
                    validators: {
                        notEmpty: {
                            message: 'The PO Unit is required'
                        },
                        integer: {
                            message: 'The value is not an integer',
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
        $("#po_plan_action_button").attr("hidden", true);
        $("#po_plan_action_button_loader").attr("hidden", false);

        //ajax call
        $.ajax({
            url :base_url+"/po/plan/store",
            type:"POST",
            data: $('#po_plan_form').serialize(),
            dataType:"json",
            cache: false,
            success:function(data){
                $("#po_plan_action_button").attr("hidden", false);
                $("#po_plan_action_button_loader").attr("hidden", true);
                if(data.errors)
                {
                    var html = '';
                    html += '<div class="alert alert-danger">';
                    for(var count = 0; count < data.errors.length; count++){
                        html += '<p>' + data.errors[count] + '</p>';
                    }
                    html += '</div>';
                    $('#form_result').html(html);
                }
                if(data.success)
                {
                    $('#po_plan_form')[0].reset();
                    validationForm.resetForm(true);
                    $('#poPlanModal').modal('hide');
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
    $('#po_plan_record').click(function(){
        $.ajax({
            url :base_url+"/po/plan",
            dataType:"json",
            success:function(data)
            {
                //set PO plan data in model and show model
                validationForm.resetForm(true);
                $('#po_value').val(data.po_value);
                $('#po_unit').val(data.po_unit);
                $('#form_result').html('');
                $("#po_plan_action_button").attr("hidden", false);
                $("#po_plan_action_button_loader").attr("hidden", true);
                $('#poPlanModal').modal({backdrop: 'static', keyboard: true});
            }
        })

    });
});
