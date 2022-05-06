document.addEventListener('DOMContentLoaded', function (e) {

    const typeValidators = {
        validators: {
            notEmpty: {
                message: 'The type is required'
            }
        }
    };
    const ruleValidators = {
        validators: {
            notEmpty: {
                message: 'The rule is required'
            },
        }
    };
    const valueValidators = {
        validators: {
            notEmpty: {
                message: 'The value is required'
            },
            numeric: {
                message: 'The value must be a numeric number'
            }
        }
    };

    const demoForm = document.getElementById('threshold_form');
    const fv = FormValidation.formValidation(demoForm, {
        fields: {
            'kpi_id[0]': typeValidators,
            'threshold_range[0]': ruleValidators,
            'threshold_value[0]': valueValidators,
        },
        plugins: {
            submitButton: new FormValidation.plugins.SubmitButton(),
            trigger: new FormValidation.plugins.Trigger(),
            tachyons: new FormValidation.plugins.Tachyons(),
            icon: new FormValidation.plugins.Icon({
                valid: 'fa fa-check',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            }),
        },
    }).on('core.form.valid', function () {
        $('#threshold_form_result').html("");
        //ajax call
        $.ajax({
            url: base_url + "/threshold/store",
            type: 'POST',
            data: $('#threshold_form').serialize(),
            dataType: "json",
            cache: false,
            success: function (data) {
                if (data.errors) {
                    var form_errors = '<div class="alert alert-danger">' +
                        '<p>' + data.errors + '</p>' +
                        '</div>';
                    $('#threshold_form_result').html(form_errors);
                }
                if (data.success) {
                    $('#threshold_form_result').html("");
                    $('#thresholdModal').modal('hide');
                    Swal.fire({
                        title: "Done",
                        text: data.success,
                        type: "success",
                        allowOutsideClick: false,
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });

                    var filter_vendor = $('#sales_filter_vendor').val();
                    var filter_range = $('#sales_filter_range').val();
                    var filter_date_range = $('#sales_filter_date_range').val();

                    $('#filter_vendor').val(filter_vendor);
                    $('#filter_range').val(filter_range);

                    callSalesVisualization(filter_vendor, filter_range, filter_date_range);
                }
            }
        });
    });

    var removedFields = [];

    const removeRow = function (rowIndex) {
        const row = demoForm.querySelector('[data-row-index="' + rowIndex + '"]');
        removedFields.push("" + rowIndex);

        // Remove field
        fv.removeField('kpi_id[' + rowIndex + ']')
            .removeField('threshold_range[' + rowIndex + ']')
            .removeField('threshold_value[' + rowIndex + ']');

        // Remove row
        row.parentNode.removeChild(row);
    };

    const template = document.getElementById('template');
    let rowIndex = 0;
    document.getElementById('addButton').addEventListener('click', function () {
        rowIndex++;

        const clone = template.cloneNode(true);
        clone.removeAttribute('id');

        // Show the row
        clone.style.display = 'block';

        clone.setAttribute('data-row-index', rowIndex);

        // Insert before the template
        template.before(clone);

        clone.querySelector('[data-name="kpi_id"]').setAttribute('name', 'kpi_id[' + rowIndex + ']');
        clone.querySelector('[data-name="threshold_range"]').setAttribute('name', 'threshold_range[' + rowIndex + ']');
        clone.querySelector('[data-name="threshold_value"]').setAttribute('name', 'threshold_value[' + rowIndex + ']');

        // Add new fields
        // Note that we also pass the validator rules for new field as the third parameter
        fv.addField('kpi_id[' + rowIndex + ']', typeValidators)
            .addField('threshold_range[' + rowIndex + ']', ruleValidators)
            .addField('threshold_value[' + rowIndex + ']', valueValidators);

        // Handle the click event of removeButton
        const removeBtn = clone.querySelector('.js-remove-button');
        removeBtn.setAttribute('data-row-index', rowIndex);
        removeBtn.setAttribute('data-row-id', rowIndex);
        removeBtn.addEventListener('click', function (e) {
            // Get the row index
            const index = e.target.getAttribute('data-row-index');
            removeRow(index);
        });
    });

    //get user data for updating
    $(document).on('click', '.threshold-value', function () {

        var report_name = 'sale';
        var sub_kpi_value = $(this).attr('id');
        var sub_kpi_name = 'asin'
        if (sub_kpi_value == '0') {
            sub_kpi_name = 'None';
        }
        var report_graph = $(this).val();
        var report_range = $('#filter_range').val();
        var report_vendor = $('#filter_vendor').val();

        $('#sub_kpi_value').val(sub_kpi_value);
        $('#sub_kpi_name').val(sub_kpi_name);
        $('#report_graph').val(report_graph);

        showThreshold(report_name, report_range, report_vendor, "show");
    });

    //get user data for updating
    $(document).on('click', '.deleteThreshold', function () {

        var report_name = 'sale';
        var report_range = $('#filter_range').val();
        var report_vendor = $('#filter_vendor').val();

        var threshold_id = $(this).attr('id');

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: base_url + "/threshold/remove/" + threshold_id,
            type: "DELETE",
            success: function (response) {
                if (response.errors) {
                    Swal.fire({
                        title: "Error",
                        text: response.errors,
                        allowOutsideClick: false,
                        type: "danger",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                } else {

                    //show report threshold
                    showThreshold(report_name, report_range, report_vendor, 'delete');

                    //reload report
                    var filter_vendor = $('#sales_filter_vendor').val();
                    var filter_range = $('#sales_filter_range').val();
                    var filter_date_range = $('#sales_filter_date_range').val();

                    $('#filter_vendor').val(filter_vendor);
                    $('#filter_range').val(filter_range);

                    callSalesVisualization(filter_vendor, filter_range, filter_date_range);
                }
            }
        });

    });

    //get user data for updating
    $(document).on('click', '.threshold', function () {

        var report_name = 'sale';
        var sub_kpi_value = $(this).attr('id');
        var sub_kpi_name = 'asin'
        if (sub_kpi_value == '0') {
            sub_kpi_name = 'None';
        }
        var report_graph = $(this).val();
        var report_range = $('#filter_range').val();
        var report_vendor = $('#filter_vendor').val();

        var threshold_data = 'sub_kpi_name=' + sub_kpi_name + '&sub_kpi_value=' + sub_kpi_value + '&report_name=' + report_name + '&report_graph=' + report_graph + '&report_range=' + report_range + '&report_vendor=' + report_vendor

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: base_url + "/threshold",
            type: "GET",
            data: threshold_data,
            dataType: "json",
            cache: false,
            success: function (response) {
                if (response.errors) {
                    Swal.fire({
                        title: "Information",
                        text: response.errors,
                        allowOutsideClick: false,
                        type: "info",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                } else {
                    for (var i = 1; i <= rowIndex; i++) {
                        if (removedFields.includes("" + i) === false) {
                            removeRow(i);
                        }
                    }
                    $('#template-kpi-id').empty();
                    $('#kpi_id').empty();

                    for (var count = 0; count < response.kpi.length; count++) {
                        switch (response.kpi[count].kpi_name) {
                            case 'shipped_cogs':
                                $('#kpi_id').append('<option value="' + response.kpi[count].kpi_id + '">Shipped COGS</option>');
                                $('#template-kpi-id').append('<option value="' + response.kpi[count].kpi_id + '">Shipped COGS</option>');
                                break;
                            case 'shipped_units':
                                $('#kpi_id').append('<option value="' + response.kpi[count].kpi_id + '">Shipped Unit</option>');
                                $('#template-kpi-id').append('<option value="' + response.kpi[count].kpi_id + '">Shipped Unit</option>');
                                break;
                            case 'shipped_unit':
                                $('#kpi_id').append('<option value="' + response.kpi[count].kpi_id + '">Shipped Unit</option>');
                                $('#template-kpi-id').append('<option value="' + response.kpi[count].kpi_id + '">Shipped Unit</option>');
                                break;
                            default:
                                $('#kpi_id').append('<option value="' + response.kpi[count].kpi_id + '">' + response.kpi[count].kpi_name + '</option>');
                                $('#template-kpi-id').append('<option value="' + response.kpi[count].kpi_id + '">' + response.kpi[count].kpi_name + '</option>');
                                break;
                        }
                    }

                    $('#threshold_form')[0].reset();
                    fv.resetForm(true);

                    $('#fk_vendor_id').val(response.vendor);
                    $('#thresholdModal').modal({ backdrop: 'static', keyboard: true });
                }
            }
        });
    });

    function showThreshold(report_name, report_range, report_vendor, callType) {

        var sub_kpi_value = $('#sub_kpi_value').val();
        var sub_kpi_name = $('#sub_kpi_name').val();
        var report_graph = $('#report_graph').val();
        var threshold_data = 'sub_kpi_name=' + sub_kpi_name + '&sub_kpi_value=' + sub_kpi_value + '&report_name=' + report_name + '&report_graph=' + report_graph + '&report_range=' + report_range + '&report_vendor=' + report_vendor

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: base_url + "/threshold",
            type: "GET",
            data: threshold_data,
            dataType: "json",
            cache: false,
            success: function (response) {
                $('#threshold_result_fields').html("");
                if (response.errors) {
                    $('#viewthresholdModal').modal('hide');
                    Swal.fire({
                        title: "Message",
                        text: response.errors,
                        allowOutsideClick: false,
                        type: "info",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                } else {
                    if (response.threshold.length == 0) {
                        $('#viewthresholdModal').modal('hide');
                        if (callType == "show") {
                            Swal.fire({
                                title: "Rules not found",
                                text: "Please add new rules",
                                allowOutsideClick: false,
                                type: "info",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            });
                        }
                    } else {
                        var html = "";
                        html += "<table class='table'><tr><th style='white-space: nowrap;'>Type</th><th style='white-space: nowrap;'>Rule</th><th style='white-space: nowrap;'>Value</th><th></th></tr>";
                        for (var count = 0; count < response.threshold.length; count++) {
                            html += "<tr>";
                            switch (response.threshold[count].kpi_name) {
                                case 'shipped_cogs':
                                    html += "<td style='white-space: nowrap;'>Shipped Cogs</td>";
                                    break;
                                case 'shipped_units':
                                    html += "<td style='white-space: nowrap;'>Shipped Unit</td>";
                                    break;
                                case 'shipped_unit':
                                    html += "<td style='white-space: nowrap;'>Shipped Unit</td>";
                                    break;
                                default:
                                    html += "<td style='white-space: nowrap;'>" + response.threshold[count].kpi_name + "</td>";
                                    break;
                            }
                            html += "<td style='white-space: nowrap;'>" + response.threshold[count].threshold_type + "</td>";
                            html += "<td style='white-space: nowrap;'>" + response.threshold[count].threshold_value + "</td>";
                            html += "<td style='white-space: nowrap;'><button type='button'  name='deleteThreshold' id='" + response.threshold[count].threshold_id + "' title='Delete Rule' class='deleteThreshold btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light'><i class='feather icon-trash-2'></i> </button></td>";
                            html += "</tr>";
                        }
                        html += "</table>";
                        $('#threshold_result_fields').html(html);
                        $('#viewthresholdModal').modal({ backdrop: 'static', keyboard: true });
                    }
                }
            }
        });
    }
});
