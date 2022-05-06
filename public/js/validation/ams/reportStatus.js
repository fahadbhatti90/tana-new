document.addEventListener('DOMContentLoaded', function(e) {

    $("#report").select2({
        dropdownParent: $("#recovery_form"),
        language: {
            noResults: function (e) {
                return "No report found";
            },
        }
    });

    $("#currentReport").select2({
        dropdownParent: $("#report_status_form"),
        language: {
            noResults: function (e) {
                return "No report found";
            },
        }
    });

    $("#recovery_range_report").select2({
        dropdownParent: $("#recovery_range_from"),
        language: {
            noResults: function (e) {
                return "No report found";
            },
        }
    });

    $('#recovery_range_value').daterangepicker({
        "minDate": moment().add(-60, 'day'),
        "maxDate": moment().add(-1, 'day'),
        "startDate": moment().add(-1, 'day'),
        "endDate": moment().add(-1, 'day'),
        drops: 'up',
    });

    $("#currentReportDate").datetimepicker({
        format: 'MM/DD/YYYY',
        maxDate: new Date(),
        minDate: '01/01/2019',
        viewMode: 'days',
        date: new Date(),
    });

    $("#recovery_form").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);

        $.ajax({
            url: base_url+"/ams/report/recover",
            type: 'POST',
            dataType: "json",
            cache: false,
            data: form.serialize(), // serializes the form's elements.
            success: function(data)
            {
                if(data.error)
                {
                    Swal.fire({
                        title: "Error",
                        text: data.error,
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
                if(data.info)
                {
                    Swal.fire({
                        title: "Message",
                        text: data.info,
                        type: "info",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
                if(data.success){
                    let element = document.getElementById("rightDrawer");
                    element.classList.remove("open");
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
            error: function (xhr, httpStatusMessage, customErrorMessage) {
                Swal.fire({
                    title: xhr.status + " Error",
                    text: customErrorMessage,
                    type: "info",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            }
        });
    });

    $("#recovery_range_from").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);

        $.ajax({
            url: base_url+"/ams/report/recover/range",
            type: 'POST',
            dataType: "json",
            cache: false,
            data: form.serialize(), // serializes the form's elements.
            success: function(data)
            {
                if(data.error)
                {
                    Swal.fire({
                        title: "Error",
                        text: data.error,
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
                if(data.info)
                {
                    Swal.fire({
                        title: "Message",
                        text: data.info,
                        type: "info",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
                if(data.success){
                    let element = document.getElementById("rightDrawer");
                    element.classList.remove("open");
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
            error: function (xhr, httpStatusMessage, customErrorMessage) {
                Swal.fire({
                    title: xhr.status + " Error",
                    text: customErrorMessage,
                    type: "info",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            }
        });
    });

    $("#report_status_form").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);

        $.ajax({
            url: base_url+"/ams/report/status",
            type: 'POST',
            dataType: "json",
            cache: false,
            data: form.serialize(), // serializes the form's elements.
            success: function(data)
            {
                if(data.error)
                {
                    alert(data.error)
                } else {
                    tableData = data.profile;
                    if (tableData.length == 0) {
                        html = "<tr>\n" +
                            "    <td style='padding: 10px; white-space: nowrap;' align='center' colspan='8'>No data found</td>\n" +
                            "</tr>";
                        $('#complete_report').html(html);
                    }else{
                        html = "";
                        for (var count = 0; count < tableData.length; count++) {
                            html += "<tr>\n" +
                                "        <td style='padding: 10px;'>" + tableData[count].profile_id + "</td>\n" +
                                "        <td style='padding: 10px;'>" + tableData[count].name + "</td>\n" +
                                "        <td style='padding: 10px;'>" + tableData[count].type + "</td>\n" +
                                "        <td style='padding: 10px;'>" + tableData[count].country_code + "</td>\n" +
                                "        <td style='padding: 10px;'>" + tableData[count].is_active + "</td>\n" +
                                "        <td style='padding: 10px;'>" + tableData[count].is_active + "</td>\n" +
                                "        <td style='padding: 10px;'>" + tableData[count].is_active + "</td>\n" +
                                "        <td style='padding: 10px;'>" + tableData[count].is_active + "</td>\n" +
                                "</tr>";
                        }
                        $('#complete_report').html(html);
                        Swal.fire({
                            title: "Information",
                            text: data.status,
                            allowOutsideClick: false,
                            type: "warning",
                            confirmButtonClass: 'btn btn-warning',
                            buttonsStyling: false,
                        });
                    }
                }
            },
            error: function (xhr, httpStatusMessage, customErrorMessage) {
                Swal.fire({
                    title: xhr.status + " Error",
                    text: customErrorMessage,
                    type: "info",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            }
        });
    });

});
