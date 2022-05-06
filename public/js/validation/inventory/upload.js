var vendors = [];
document.addEventListener('DOMContentLoaded', function (e) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/user-vendors",
        type: "GET",
        dataType: "json",
        success: function (response) {
            //Reload datatable
            for (let count = 0; count < response.data.length; count++) {
                vendors[count] = parseInt(response.data[count].vendor_id);
            }
        }
    });

    var validationForm = FormValidation.formValidation(
        document.getElementById('inventory_form'),
        {
            fields: {
                daily_inventory: {
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
        if ($('#action_button').val() == 'Upload in inventory') {
            namefile = $("#file_values").val();
            if (namefile != "") {
                var elements = document.getElementsByClassName("thumb");
                var names = '';
                for (var i = 0; i < elements.length; i++) {
                    names += elements[i].title + ',';
                }
                var strArray1 = names.split(",");
                var strArray = namefile.split(",");
                for (var i = 0; i < strArray.length; i++) {
                    let vendor_id = strArray[i].split("_");
                    if (strArray[i].includes("Inventory") && vendors.includes(parseInt(vendor_id[0])) && vendor_id.length >= 3) {
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
            $("#action_button").attr("hidden", true);
            $("#action_button_loader").attr("hidden", false);

        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var formData = new FormData($("#inventory_form").get(0));
        $.noConflict();
        $.ajax({
            url: base_url + "/inventory/store",
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                $("#action_button").attr("hidden", false);
                $("#action_button_loader").attr("hidden", true);
                if (data.error) {
                    window.setTimeout(function () { location.reload() }, 5000)
                    var html = '';
                    $('#form_result').html('');
                    html += '<div class="alert alert-danger">';
                    for (var count = 0; count < data.error.length; count++) {
                        html += '<p>' + data.error[count] + '</p>';
                    }
                    html += '</div>';
                    $("#daily_inventory").val(null);
                    $('#form_result').html(html);
                }
                if (data.success) {
                    $("#daily_inventory").val(null);
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
                $("#action_button").attr("hidden", false);
                $("#action_button_loader").attr("hidden", true);
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
        var select = $('select');
        select.html(select.find('option').sort(function (x, y) {
            // to change to descending order switch "<" for ">"
            return $(x).text() > $(y).text() ? 1 : -1;
        }));
    });
    $("input:file").change(function () { //when choose file change get values in hidden comma seperated
        var filenames = $.map(this.files, function (file) {
            return file.name;
        });
        var b = filenames.toString();
        if ($("#file_values").val() == ""){
            $("#file_values").val($("#file_values").val() + b);
        }else{
            $("#file_values").val($("#file_values").val() + "," + b);
        }
    });
    $('output').on('click', '.del', function () { //to remove from front end and id as well
        if (count1 == "" && cal == "")
            count1 = document.getElementById("count").value;
        if (cal != "")
            cal--;
        if (count1 != "")
            count1--;
        var id = $(this).prev().attr('id');
        var title = $(this).prev().attr('title');
        var hidden = $("#file_values").val();
        hidden = hidden.split(",");
        for (var i = 0; i < hidden.length; i++) {
            if (hidden[i] == title) {
                hidden.splice(i, 1);
            }
        }
        $("#file_values").val(hidden); //to set input type hidden with new value
        id = id.split("_");
        id = id[1];
        var x = document.getElementById("daily_inventory").files;
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

                    let vendor_id = theFile.name.split("_");
                    if (theFile.name.includes("Inventory") && vendors.includes(parseInt(vendor_id[0])) && vendor_id.length >= 3) {
                        var span = document.createElement('span');
                        span.innerHTML = ['<div class="view"><a id="img_' + num + ' " class="thumb" src="', e.target.result,
                        '" title="' + theFile.name + '">' + theFile.name + '</a><div class="del" style="float: right;  margin-left: 20px;"><a onmouseover="changecolor(this)" onmouseout="changeback(this)">x</a></div></div>'
                        ].join('');
                        document.getElementById('list').insertBefore(span, null);
                    } else {
                        var span = document.createElement('span');
                        span.innerHTML = ['<div class="view" style="color:red;"><a id="img_' + num + ' " class="thumb" src="', e.target.result,
                        '" title="' + theFile.name + '">' + theFile.name + '</a><div class="del" style="float: right;  margin-left: 20px;"><a onmouseover="changecolor(this)" onmouseout="changeback(this)">x</a></div></div>'
                        ].join('');
                        document.getElementById('list').insertBefore(span, null);
                    }
                };
            })(f, i);
            reader.readAsDataURL(f);
        }
        console.dir(files);
    }
    document.getElementById('daily_inventory').addEventListener('change', handleFileSelect, false);
});
var cal = "";
var count1 = "";
function hide(x) {
    x.style.display = 'none';
    $('#target_div').append(
        $('<input/>').attr('type', "file").attr('name', "daily_inventory[]").attr('accept', ".csv,.xlsx").attr('id', "daily_inventory").attr('class', "custom-file-input").attr('multiple', "multiple").attr('required', "required").attr('onchange', "hide(this)")
    );
    var result = $(x)[0].files;
    for (var x = 0; x < result.length; x++) {
        var file = result[x];
        // here are the files
        let vendor_id = file.name.split("_");
        if (file.name.includes("Inventory") && vendors.includes(parseInt(vendor_id[0])) && vendor_id.length >= 3) {
                $("#list").append("<span><div class='view'><a id='img_" + x + "' class='thumb' src=',x.target.result,' title='" + file.name + "'>" + file.name + "</a><div class='del' style='float: right;  margin-left: 20px;'><a onmouseover='changecolor(this)' onmouseout='changeback(this)'>x</a></div></div></span>");
        } else {
            $("#list").append("<span><div class='view' style='color:red'><a id='img_" + x + "' class='thumb' src=',x.target.result,' title='" + file.name + "'>" + file.name + "</a><div class='del' style='float: right;  margin-left: 20px;'><a onmouseover='changecolor(this)' onmouseout='changeback(this)'>x</a></div></div></span>");
        }
        var filenames = $.map(x.files, function (file) {
            return file.name;
        });
        var b = file.name;
        if ($("#file_values").val() == ""){
            $("#file_values").val($("#file_values").val() + b);
        }else{
            $("#file_values").val($("#file_values").val() + "," + b);
        }
    }
    var count = document.getElementById("count").value;
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
$('#daily_inventory').change(function () {
    var i = this.files.length;
    document.getElementById("count").value = i;

    document.getElementById("daily_inventory").style.visibility = "hidden";
    var myClock = document.getElementById('daily_inventory');
    myClock.style.display = 'none';
    $('#target_div').append(
        $('<input/>').attr('type', "file").attr('name', "daily_inventory[]").attr('accept', ".csv,.xlsx").attr('id', "daily_inventory").attr('class', "custom-file-input").attr('multiple', "multiple").attr('required', "required").attr('onchange', "hide(this)")
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


