document.addEventListener('DOMContentLoaded', function (e) {

    var validationForm = FormValidation.formValidation(
        document.getElementById('traffic_form'),
        {
            fields: {
                vendor: {
                    validators: {
                        notEmpty: {
                            message: 'The Vendor  is required'
                        },
                    }
                },
                trafficFile: {
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

        var action_url = '';
        var action_method = '';
        var namesfile = '';
        if ($('#action_button').val() == 'Upload in traffic') {
            namefile = $("#file_values").val();
            if (namefile != "") {
                var elements = document.getElementsByClassName("thumb");
                var names = '';
                for (var i = 0; i < elements.length; i++) {
                    names += elements[i].title + ',';
                }
                var strArray1 = names.split(",");
                var strArray = namefile.split(",");
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
        }
        $("#action_button").attr("hidden", true);
        $("#action_button_loader").attr("hidden", false);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var formData = new FormData($("#traffic_form").get(0));
        $.noConflict();
        $.ajax({
            url: base_url + "/traffic/store",
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,

            success: function (data) {
                $("#action_button").attr("disabled", false);
                $("#action_button_loader").attr("disabled", true);
                if (data.error) {
                    window.setTimeout(function () { location.reload() }, 5000)
                    var html = '';
                    $('#form_result').html('');
                    html += '<div class="alert alert-danger">';
                    for (var count = 0; count < data.error.length; count++) {
                        html += '<p>' + data.error[count] + '</p>';
                    }
                    html += '</div>';
                    $("#trafficFile").val(null);
                    $('#form_result').html(html);

                    document.getElementById("action_button").value = "Upload in traffic";
                    validationForm.resetForm(true);

                }
                if (data.success) {
                    document.getElementById("action_button").value = "Upload in traffic";
                    $("#trafficFile").val(null);

                    Swal.fire({
                        title: "Done",
                        text: data.success,
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,

                    }).then(function () {
                            location.reload();
                        }
                    ); validationForm.resetForm(true);

                }
            },
            error: function (xhr, httpStatusMessage, customErrorMessage) {
                $("#action_button").attr("disabled", false);
                $("#action_button_loader").attr("disabled", true);
                Swal.fire({
                    title: xhr.status + " Error",
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

        if ($("#file_values").val() == "")
            $("#file_values").val($("#file_values").val() + b);
        else
            $("#file_values").val($("#file_values").val() + "," + b);
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
        var x = document.getElementById("trafficFile").files;
        if (id in x) { //to remove id from input
            delete x[' + id + ']; //This line is a problem.
        }
        console.dir(x);
        $(this).parent().replaceWith();
    });

    function handleFileSelect(evt) {
        var files = evt.target.files;
        for (var i = 0, f; f = files[i]; i++) {
            var reader = new FileReader();
            reader.onload = (function (theFile, num) {
                return function (e) {
                    if (theFile.name.includes("Traffic")) {
                        var span = document.createElement('span');
                        span.innerHTML = ['<div class="view"><a id="img_' + num + ' " class="thumb" src="', e.target.result,
                            '" title="' + theFile.name + '">' + theFile.name + '</a><div class="del" style="float: right;  margin-left: 20px;"><a onmouseover="changecolor(this)" onmouseout="changeback(this)">x</a></div></div>'
                        ].join('');
                        document.getElementById('list').insertBefore(span, null);
                    }else{
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
    document.getElementById('trafficFile').addEventListener('change', handleFileSelect, false);

});
var cal = "";
var count1 = "";
function hide(x) {
    x.style.display = 'none';
    $('#target_div').append(
        $('<input/>').attr('type', "file").attr('name', "trafficFile[]").attr('accept', ".csv,.xlsx").attr('id', "trafficFile").attr('class', "custom-file-input").attr('multiple', "multiple").attr('required', "required").attr('onchange', "hide(this)")
    );
    var result = $(x)[0].files;
    for (var x = 0; x < result.length; x++) {
        var file = result[x];
        // here are the files
        $("#list").append("<span><div class='view'><a id='img_" + x + "' class='thumb' src=',x.target.result,' title='" + file.name + "'>" + file.name + "</a><div class='del' style='float: right;  margin-left: 20px;'><a onmouseover='changecolor(this)' onmouseout='changeback(this)'>x</a></div></div></span>");
        var filenames = $.map(x.files, function (file) {
            return file.name;
        });
        var b = file.name;
        if ($("#file_values").val() == "")
            $("#file_values").val($("#file_values").val() + b);
        else
            $("#file_values").val($("#file_values").val() + "," + b);

    }
    var count = document.getElementById("count").value;
    var r = result.length;
    if (cal == "") {
        cal = Number(count) + Number(r);
    }
    else {
        cal += r;
    }
    if (cal > 62) {
        Swal.fire({
            title: 'Cancelled',
            allowOutsideClick: false,
            text: 'Sorry! More than 62 files can not be selected',
            type: 'error',
            confirmButtonClass: 'btn btn-danger',
        })
    }

}
$('#trafficFile').change(function () {
    var i = this.files.length;
    document.getElementById("count").value = i;

    document.getElementById("trafficFile").style.visibility = "hidden";
    var myClock = document.getElementById('trafficFile');
    myClock.style.display = 'none';
    $('#target_div').append(
        $('<input/>').attr('type', "file").attr('name', "trafficFile[]").attr('accept', ".csv,.xlsx").attr('id', "trafficFile").attr('class', "custom-file-input").attr('multiple', "multiple").attr('required', "required").attr('onchange', "hide(this)").attr('onmouseout', "getvalue(this)")
    );
    if (this.files.length > 62) {
        Swal.fire({
            title: 'Cancelled',
            allowOutsideClick: false,
            text: 'Sorry! More than 62 files can not be selected',
            type: 'error',
            confirmButtonClass: 'btn btn-danger',
        })
    }
});


