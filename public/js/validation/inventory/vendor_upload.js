document.addEventListener('DOMContentLoaded', function (e) {

    var validationForm = FormValidation.formValidation(
        document.getElementById('vendor_inventory_form'),
        {
            fields: {
                vendor: {
                    validators: {
                        notEmpty: {
                            message: 'The Vendor  is required'
                        },
                    }
                },
                vendor_daily_inventory: {
                    validators: {
                        file: {
                            extension: 'xlsx,csv,xls',
                            //type: 'application/vnd.ms-excel',
                            maxSize: 2097152,   // 2048 * 1024
                            message: 'The selected file is not valid'
                        },
                        notEmpty: {
                            message: 'The Excel File is required'
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
        if ($('#vendor_action_button').val() == 'Upload in inventory') {
            namefile = $("#vendor_file_values").val();
            if (namefile != "") {
                var elements = document.getElementsByClassName("thumb");
                var names = '';
                for (var i = 0; i < elements.length; i++) {
                    names += elements[i].title + ',';
                }
                var strArray1 = names.split(",");
                var strArray = namefile.split(",");
                for (var i = 0; i < strArray.length; i++) {
                    if (strArray[i].includes("Inventory")) {
                        continue;
                    } else {
                        var count2 = strArray[i];
                        for (var q = 0; q < strArray1.length; q++) {
                            if (strArray1[q] == count2) {
                                Swal.fire({
                                    title: 'Cancelled',
                                    allowOutsideClick: false,
                                    text: 'Sorry! Remove incorrect files first',
                                    type: 'error',
                                    confirmButtonClass: 'btn btn-danger',
                                });
                                return false;
                            }
                        }
                    }
                }
            }
            if (cal > 62) {
                Swal.fire({
                    title: 'Cancelled',
                    allowOutsideClick: false,
                    text: 'Sorry! More than 62 files can not be selected',
                    type: 'error',
                    confirmButtonClass: 'btn btn-danger',
                })
                return false;
            }
            //document.getElementById("action_button").value = "Please wait Loading.....";
            $("#vendor_action_button").attr("hidden", true);
            $("#vendor_action_button_loader").attr("hidden", false);
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var formData = new FormData($("#vendor_inventory_form").get(0));
        $.noConflict();
        $.ajax({
            url: base_url + "/inventory/store/vendor",
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                $("#vendor_action_button").attr("hidden", false);
                $("#vendor_action_button_loader").attr("hidden", true);
                if (data.error) {
                    window.setTimeout(function () { location.reload() }, 5000)
                    var html = '';
                    $('#vendor_form_result').html('');
                    html += '<div class="alert alert-danger">';
                    for (var count = 0; count < data.error.length; count++) {
                        html += '<p>' + data.error[count] + '</p>';
                    }
                    html += '</div>';
                    $("#vendor_daily_inventory").val(null);
                    $('#vendor_form_result').html(html);
                }
                if (data.success) {
                    $("#vendor_daily_inventory").val(null);
                    Swal.fire({
                        title: "Done",
                        text: data.success,
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    }).then(function () {
                        location.reload();
                    });
                    validationForm.resetForm(true);
                }
            },
            error: function (xhr, httpStatusMessage, customErrorMessage) {
                $("#vendor_action_button").attr("hidden", false);
                $("#vendor_action_button_loader").attr("hidden", true);
                Swal.fire({
                    title: xhr.status+" Error",
                    text: customErrorMessage,
                    type: "info",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                }).then(function () {
                    location.reload();
                });
            }
        });
    });
    $(function () {
        // choose target dropdown
        var select = $('vendor_select');
        select.html(select.find('vendor_option').sort(function (x, y) {
            // to change to descending order switch "<" for ">"
            return $(x).text() > $(y).text() ? 1 : -1;
        }));
    });
    $("input:file").change(function () { //when choose file change get values in hidden comma seperated
        var filenames = $.map(this.files, function (file) {
            return file.name;
        });
        var b = filenames.toString();
        if ($("#vendor_file_values").val() == ""){
            $("#vendor_file_values").val($("#vendor_file_values").val() + b);
        }else{
            $("#vendor_file_values").val($("#vendor_file_values").val() + "," + b);
        }
    });
    $('vendor_output').on('click', '.del', function () { //to remove from front end and id as well
        if (count1 == "" && cal == "")
            count1 = document.getElementById("vendor_count").value;
        if (cal != "")
            cal--;
        if (count1 != "")
            count1--;
        var id = $(this).prev().attr('id');
        var title = $(this).prev().attr('title');
        var hidden = $("#vendor_file_values").val();
        hidden = hidden.split(",");
        for (var i = 0; i < hidden.length; i++) {
            if (hidden[i] == title) {
                hidden.splice(i, 1);
            }
        }
        $("#vendor_file_values").val(hidden); //to set input type hidden with new value
        id = id.split("_");
        id = id[1];
        var x = document.getElementById("vendor_daily_inventory").files;
        if (id in x) { //to remove id from input
            delete x[' + id + ']; //This line is a problem.
        }
        $(this).parent().replaceWith();
    });

    function handleFileSelect(evt) {
        var files = evt.target.files
        for (var i = 0, f; f = files[i]; i++) {
            var reader = new FileReader();
            reader.onload = (function (theFile, num) {
                return function (e) {
                    if (theFile.name.includes("Inventory") ) {
                        var span = document.createElement('span');
                        span.innerHTML = ['<div class="view"><a id="img_' + num + ' " class="thumb" src="', e.target.result,
                        '" title="' + theFile.name + '">' + theFile.name + '</a><div class="del" style="float: right;  margin-left: 20px;"><a onmouseover="changecolor(this)" onmouseout="changeback(this)">x</a></div></div>'
                        ].join('');
                        document.getElementById('vendor_list').insertBefore(span, null);
                    } else {
                        var span = document.createElement('span');
                        span.innerHTML = ['<div class="view" style="color:red;"><a id="img_' + num + ' " class="thumb" src="', e.target.result,
                        '" title="' + theFile.name + '">' + theFile.name + '</a><div class="del" style="float: right;  margin-left: 20px;"><a onmouseover="changecolor(this)" onmouseout="changeback(this)">x</a></div></div>'
                        ].join('');
                        document.getElementById('vendor_list').insertBefore(span, null);
                    }
                };
            })(f, i);
            reader.readAsDataURL(f);
        }
        console.dir(files);
    }
    document.getElementById('vendor_daily_inventory').addEventListener('change', handleFileSelect, false);
});
var cal = "";
var count1 = "";
function hide(x) {
    x.style.display = 'none';
    $('#vendor_target_div').append(
        $('<input/>').attr('type', "file").attr('name', "vendor_daily_inventory[]").attr('accept', ".csv,.xlsx").attr('id', "vendor_daily_inventory").attr('class', "custom-file-input").attr('multiple', "multiple").attr('required', "required").attr('onchange', "hide(this)")
    );
    var result = $(x)[0].files;
    for (var x = 0; x < result.length; x++) {
        var file = result[x];
        // here are the files
        if (file.name.includes("Inventory") ) {
                $("#vendor_list").append("<span><div class='view'><a id='img_" + x + "' class='thumb' src=',x.target.result,' title='" + file.name + "'>" + file.name + "</a><div class='del' style='float: right;  margin-left: 20px;'><a onmouseover='changecolor(this)' onmouseout='changeback(this)'>x</a></div></div></span>");
        } else {
            $("#vendor_list").append("<span><div class='view' style='color:red'><a id='img_" + x + "' class='thumb' src=',x.target.result,' title='" + file.name + "'>" + file.name + "</a><div class='del' style='float: right;  margin-left: 20px;'><a onmouseover='changecolor(this)' onmouseout='changeback(this)'>x</a></div></div></span>");
        }
        var filenames = $.map(x.files, function (file) {
            return file.name;
        });
        var b = file.name;
        if ($("#vendor_file_values").val() == ""){
            $("#vendor_file_values").val($("#vendor_file_values").val() + b);
        }else{
            $("#vendor_file_values").val($("#vendor_file_values").val() + "," + b);
        }
    }
    var count = document.getElementById("vendor_count").value;
    var r = result.length;
    if (cal == "") {
        cal = Number(count) + Number(r);
    }else {
        cal += r;
    }
    if (cal > 62) {
        Swal.fire({
            title: 'Cancelled',
            allowOutsideClick: false,
            text: 'Sorry! More than 62 files can not be selected',
            type: 'error',
            confirmButtonClass: 'btn btn-danger',
        });
    }

}
$('#vendor_daily_inventory').change(function () {
    var i = this.files.length;
    document.getElementById("vendor_count").value = i;

    document.getElementById("vendor_daily_inventory").style.visibility = "hidden";
    var myClock = document.getElementById('vendor_daily_inventory');
    myClock.style.display = 'none';
    $('#vendor_target_div').append(
        $('<input/>').attr('type', "file").attr('name', "vendor_daily_inventory[]").attr('accept', ".csv,.xlsx").attr('id', "vendor_daily_inventory").attr('class', "custom-file-input").attr('multiple', "multiple").attr('required', "required").attr('onchange', "hide(this)")
    );
    if (this.files.length > 62) {
        Swal.fire({
            title: 'Cancelled',
            allowOutsideClick: false,
            text: 'Sorry! More than 62 files can not be selected',
            type: 'error',
            confirmButtonClass: 'btn btn-danger',
        });
    }
});

function changecolor(x) {
    x.style.color = 'red';
}
function changeback(x) {
    x.style.color = 'inherit';
}


