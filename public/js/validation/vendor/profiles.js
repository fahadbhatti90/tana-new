document.addEventListener('DOMContentLoaded', function (e) {
    var validationVendorForm = FormValidation.formValidation(
        document.getElementById('assign_profile_form'),
        {
            fields: {
                // vendor_info: {
                //     validators: {
                //         notEmpty: {
                //             message: 'Please select Vendor'
                //         },
                //         choice: {
                //             min: 1,
                //             max: 1,
                //             message: 'Only 1 Vendor is selected at a time'
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
        $("#assign_entity_button").attr("disabled", true);
        //ajax call
        $.ajax({
            url: base_url + "/user-vendors/assignProfile/" + $('#add_vendor_id').val(),
            type: "PUT",
            data: $('#assign_profile_form').serialize(),
            dataType: "json",
            cache: false,
            success: function (data) {
                $("#assign_entity_button").attr("disabled", false);
                if (data.success) {
                    $('#assign_profile_form')[0].reset();
                    $('#profile_table').DataTable().ajax.reload(null, false);
                    $('#addVendorModal').modal('hide');
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
    //View all profiles in datatabale
    $('#profile_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/user-vendors/profiles/" + vendor_id,
        },
        language: {
            "emptyTable": "No data found"
        },
        columns: [
            { "data": "DT_RowIndex", "name": "DT_RowIndex", orderable: false, searchable: false},
            { data: 'name', name: 'name' },
            { data: 'profile_id', name: 'profile_id' },
            { data: 'entity_id', name: 'entity_id' },
            { data: 'country_code', name: 'country_code' },
            { data: 'type', name: 'type' },
            { data: 'status',name: 'status', orderable: false },
            { data: 'action',name: 'action', orderable: false },
        ],
        order: [[1, 'asc']],
    }).columns.adjust().draw();


    //Set Model for adding new user
    $('#create_record').click(function () {
        $('#profile_info').html("");
        var id = vendor_id;
        validationVendorForm.resetForm(true);
        $.ajax({
            url: base_url + "/user-vendors/assignProfile/" + id + "",
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                $('#add_vendor_id').val(id);
                if (data.result && data.result.length > 0) {
                    var html = '<option value="" disabled>-- select entity --</option>';
                    for (var count = 0; count < data.result.length; count++) {
                        html += '<option value=' + data.result[count].id + "-" + data.result[count].entity_id + "-" + id + '>' + data.result[count].entity_id + ' - ' + data.result[count].name + ' - ' + data.result[count].country_code + '</option>';
                    }
                    $('#profile_info').html(html);
                    $('#addVendorModal').modal({ backdrop: 'static', keyboard: false });
                } else {
                    Swal.fire({
                        title: "Entity not found",
                        text: "Please add new vendors",
                        allowOutsideClick: false,
                        type: "info",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
            }
        })
    });
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
                $('#profile_table').DataTable().ajax.reload(null, false);
            }
        })
    });
    $('#assign_entity_button').click(function () {
        var a = document.getElementById('profile_info').value;
        if (a == "" || a == null) {
            Swal.fire({
                title: 'Cancelled',
                allowOutsideClick: false,
                text: 'Sorry! No Profile selected',
                type: 'error',
                confirmButtonClass: 'btn btn-danger',
            }).then(function () {
                location.reload();
            });

        } else {
            return true;
        }

    });

    $(document).on('click', '.unLinkProfile', function () {
        //get user and Status ID
        var id = $(this).attr('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to un-assign this entity!",
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
                data = "";
                $.ajax({
                    url : base_url+"/user-vendors/unAssignProfile/"+id,
                    data: data,
                    type: "PUT",
                    dataType:"json",
                    success: function (data) {
                        //Reload datatable
                        $('#profile_table').DataTable().ajax.reload(null,false);
                        Swal.fire({
                            type: "success",
                            title: 'Done',
                            allowOutsideClick: false,
                            text: "Entity is unassigned",
                            confirmButtonClass: 'btn btn-success',
                        })
                    }
                });
            }
        });
    });

});
$(document).ready(function () {
    $("#profile_info").select2({
        dropdownParent: $("#assign_profile_form")
    });
});
