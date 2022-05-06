document.addEventListener('DOMContentLoaded', function (e) {

    var validationForm = FormValidation.formValidation(
        document.getElementById('vendors_form'),
        {
            fields: {
                vendor_name: {
                    validators: {
                        notEmpty: {
                            message: 'The vendor name is required'
                        },
                        stringLength: {
                            min: 2,
                            max: 64,
                            message: 'The vendor name must be more than 1 and less than 65 characters long'
                        },
                        regexp: {
                            regexp: /^([a-zA-Z0-9!@#$%^&*().,<>{}[\]<>?_=+\-|;:\'\"\/]+\s?)*$/,
                            message: 'Extra space are not allowed in the vendor name'
                        },
                    }
                },
                vendor_alias: {
                    validators: {
                        notEmpty: {
                            message: 'The vendor alias is required'
                        },
                        stringLength: {
                            min: 2,
                            max: 64,
                            message: 'The vendor alias must be more than 1 and less than 65 characters long'
                        },
                    }
                },
                region: {
                    validators: {
                        notEmpty: {
                            message: 'The vendor region is required'
                        },
                        regexp: {
                            regexp: /^[A-Z]+$/,
                            message: 'The vendor region can only consist of uppercase alphabets'
                        },
                    }
                },
                tier: {
                    validators: {
                        notEmpty: {
                            message: 'The vendor tier is required'
                        },
                        regexp: {
                            regexp: /^([a-zA-Z0-9!@#$%^&*().,<>{}[\]<>?_=+\-|;:\'\"\/]+\s?)*$/,
                            message: 'Extra space are not allowed in the vendor tier'
                        },
                    }
                },
                marketplace: {
                    validators: {
                        notEmpty: {
                            message: 'The vendor marketplace is required'
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

        $('#form_result').html("");
        $("#action_button").attr("disabled", true);

        //set route and method for adding user
        if ($('#form_action').val() == 'Add') {
            action_url = base_url + "/user-vendors";
            action_method = "POST";
        }
        //set route and method for updating role specific
        if ($('#form_action').val() == 'Edit') {
            action_url = base_url + "/user-vendors/" + $('#hidden_id').val();
            action_method = "PUT";
        }
        //ajax call
        $.ajax({
            url: action_url,
            type: action_method,
            data: $('#vendors_form').serialize(),
            dataType: "json",
            cache: false,
            success: function (data) {
                $("#action_button").attr("disabled", false);
                if (data.errors) {
                    var html = '';
                    $('#form_result').html('');
                    html += '<div class="alert alert-danger">';
                    for (var count = 0; count < data.errors.length; count++) {
                        html += '<p>' + data.errors[count] + '</p>';
                    }
                    html += '</div>';
                    $('#form_result').html(html);
                }
                if (data.success) {

                    $('#vendors_form')[0].reset();
                    //Refresh datatable
                    $('#vendors_table').DataTable().ajax.reload(null, false);
                    $('#vendorModal').modal('hide');
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
    $('#vendors_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/user-vendors",
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { "data": "DT_RowIndex", "name": "DT_RowIndex", orderable: false, searchable: false },
            { data: 'vendor_name', name: 'vendor_name' },
            { data: 'vendor_alias', name: 'vendor_alias' },
            { data: 'region', name: 'region' },
            { data: 'marketplace', name: 'marketplace' },
            { data: 'tier', name: 'tier' },
            { data: 'is_active', name: 'is_active', orderable: false },
            { data: 'action', name: 'action', orderable: false },
        ],
        order: [[1, 'asc']],
    }).columns.adjust().draw();

    //Set Model for adding new user
    $('#create_record').click(function () {
        validationForm.resetForm(true);
        $('#vendor_name').val("");
        $('#vendor_alias').val("");
        $('#region').val("");
        $('#tier').val("");
        $('#marketplace').val("");
        $('#form_result').html("");
        $('#vendor_modal_title').text('Add New Vendor Information');
        $('#action_button').val('Add Vendor');
        $('#form_action').val('Add');
        $('#vendorModal').modal({ backdrop: 'static', keyboard: false });

    });

    //get user data for updating
    $(document).on('click', '.edit', function () {
        var id = $(this).attr('id');
        $('#form_result').html('');
        $.ajax({
            url: base_url + "/user-vendors/" + id + "/edit",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                validationForm.resetForm(true);
                $('#vendor_name').val(data.result.vendor_name);
                $('#vendor_alias').val(data.result.vendor_alias);
                $('#region').val(data.result.region);
                $('#tier').val(data.result.tier);
                $('#marketplace').val(data.result.marketplace);
                $('#is_active').val(data.result.is_active);
                $('#hidden_id').val(id);
                $('#form_result').html("");
                $('#vendor_modal_title').text('Edit Vendors Information');
                $('#action_button').val('Edit Vendor');
                $('#form_action').val('Edit');
                $('#vendorModal').modal({ backdrop: 'static', keyboard: false });

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
        $('#form_result').html('');

        $.ajax({
            url: base_url + "/user-vendors/status/" + id,
            data: data,
            type: "PUT",
            dataType: "json",
            success: function (data) {
                //Reload datatable
                $('#vendors_table').DataTable().ajax.reload(null, false);
            }
        })
    });

    $(document).on('click', '.deleteVendor', function () {
        //get user and Status ID
        var id = $(this).attr('id');
        var status = $(this).val();

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this vendor!",
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
                    url: base_url + "/user-vendors/status/" + id,
                    data: data,
                    type: "PUT",
                    dataType: "json",
                    success: function (data) {
                        //Reload datatable
                        $('#vendors_table').DataTable().ajax.reload(null, false);
                        Swal.fire({
                            type: "success",
                            title: 'Deleted!',
                            allowOutsideClick: false,
                            text: "Vendor is deleted",
                            confirmButtonClass: 'btn btn-success',
                        })
                    }
                });
            }
        });
    });
});
