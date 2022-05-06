$("#filter_range_picker").datetimepicker({
    format: 'MM/DD/YYYY',
    minDate: '09/09/1999',
    maxDate: new Date(),
});

$('#custom_data_value').daterangepicker({
    "minDate": '09/09/1999',
    "maxDate": new Date()
});

$("#sales_filter_vendor").select2({
    dropdownParent: $("#sales_filter_form"),
    language: {
        noResults: function (e) {
            return "No vendor found";
        },
    }
});

$("#sales_filter_range").select2({
    dropdownParent: $("#sales_filter_form"),
    language: {
        noResults: function (e) {
            return "No reporting range found";
        },
    }
});

$("#sales_filter_date_range").val($("#custom_data_value").val());

$('#custom_data_value').on('apply.daterangepicker', function (ev, picker) {
    $("#sales_filter_date_range").val($("#custom_data_value").val());
});

document.addEventListener('DOMContentLoaded', function (e) {

    $('#threshold_form').on('submit', function (event) {
        event.preventDefault();
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

    $('#sales_filter_range').on('change', function (e) {

        var type = $('#sales_filter_range').val();

        switch (type) {
            case '1':
                document.getElementById('custom_data_value').type = 'text';
                document.getElementById('filter_range_picker').type = 'hidden';
                $("#sales_filter_date_range").val($("#custom_data_value").val());
                break;
            case '2':
                document.getElementById('custom_data_value').type = 'hidden';
                document.getElementById('filter_range_picker').type = 'text';
                $('#filter_range_picker')
                    .data("DateTimePicker")
                    .options({
                        viewMode: 'months',
                        format: 'MM/DD/YYYY',
                    });
                var value = $("#filter_range_picker").val();
                var firstDate = moment(value, "MM/DD/YYYY").day(0).format("MM/DD/YYYY");
                var lastDate = moment(value, "MM/DD/YYYY").day(6).format("MM/DD/YYYY");
                $("#sales_filter_date_range").val(firstDate + " - " + lastDate);
                break;
            case '3':
                document.getElementById('custom_data_value').type = 'hidden';
                document.getElementById('filter_range_picker').type = 'text';
                $('#filter_range_picker')
                    .data("DateTimePicker")
                    .options({
                        viewMode: 'months',
                        format: 'MMM-YYYY',
                    });
                var value = $("#filter_range_picker").val();
                var firstDate = moment(value, "MMM-YYYY").startOf('month').format("MM/DD/YYYY");
                var lastDate = moment(value, "MMM-YYYY").endOf('month').format("MM/DD/YYYY");
                $("#sales_filter_date_range").val(firstDate + " - " + lastDate);
                break;
            case '4':
                document.getElementById('custom_data_value').type = 'hidden';
                document.getElementById('filter_range_picker').type = 'text';
                $('#filter_range_picker')
                    .data("DateTimePicker")
                    .options({
                        viewMode: 'years',
                        format: 'YYYY',
                    });
                var value = $("#filter_range_picker").val();
                var firstDate = moment(value, "YYYY").startOf('year').format("MM/DD/YYYY");
                var lastDate = moment(value, "YYYY").endOf('year').format("MM/DD/YYYY");
                $("#sales_filter_date_range").val(firstDate + " - " + lastDate);
                break;
        }
    });
});

var label = [];
var date_range = [];
var record1 = [];
var record2 = [];
var record1_alert = [];
var record2_alert = [];

var category_label = [];
var category_record1 = [];
var category_alert = [];

//on loading call Generate Charts Structure
var chart = c3.generate({
    bindto: d3.select('#chart'),
    size: {
        height: 400,
    },
    data: {
        x: 'x',
        json: {
            'x': label,
            'Shipped COGS': record1,
            'Shipped Units': record2,
        },
        colors: {
            'Shipped COGS': '#E15829',
            'Shipped Units': '#00A5B5',
        },
        types: {
            'Shipped COGS': 'area-spline',
            'Shipped Units': 'area-spline',
        },
        axes: {
            'Shipped Units': 'y2'
        },
        labels: {
            format: {
                'Shipped COGS': function (v, id, i, j) {
                    if (record1_alert[i] == "yes") {
                        return "\uf024";
                    }
                },
                'Shipped Units': function (v, id, i, j) {
                    if (record2_alert[i] == "yes") {
                        return "\uf024";
                    }
                },
            },
        }
    },
    grid: {
        y: { show: true }
    },
    zoom: {
        enabled: true
    },
    legend: {
        show: true,
        position: 'inset',
        inset: {
            anchor: 'top-right',
            x: undefined,
            y: undefined,
            step: undefined
        }
    },
    axis: {
        x: {
            type: 'category',
            tick: {
                rotate: -80,
                multiline: false,
            },
            height: 100,
        },
        y: {
            label: {
                text: 'Shipped COGS',
                position: 'outer-middle'
            },
            tick: {
                format: d3.format('$,')
            }
        },
        y2: {
            show: true,
            label: {
                text: 'Shipped Units',
                position: 'outer-middle'
            },
            tick: {
                format: d3.format(',')
            }
        }
    },
    tooltip: {
        format: {
            title: function (d) {
                return date_range[d];
            },
        }
    }
});

var category_chart = c3.generate({
    bindto: d3.select('#category_chart'),
    size: {
        height: 500,
    },
    data: {
        x: 'x',
        json: {
            'x': category_label,
            'Shipped COGS': category_record1,
        },
        colors: {
            'Shipped COGS': '#E15829',
        },
        types: {
            'Shipped COGS': 'bar',
        },
        labels: {
            format: {
                'Shipped COGS': function (v, id, i, j) {
                    if (category_alert[i] == "yes") {
                        return "\uf024";
                    }
                },
            },
        }
    },
    grid: {
        y: { show: true }
    },
    legend: {
        show: true,
        position: 'inset',
        inset: {
            anchor: 'top-right',
            x: undefined,
            y: undefined,
            step: undefined
        }
    },
    zoom: {
        enabled: true
    },
    axis: {
        x: {
            type: 'category',
            tick: {
                rotate: -80,
                multiline: false
            },
            height: 150,
        },
        y: {
            label: {
                text: 'Value',
                position: 'outer-middle'
            },
            tick: {
                format: d3.format("$,")
            }
        }
    }
});

if (access != 'yes') {
    Swal.fire({
        title: "Error",
        text: "No vendor is associated with this brand",
        allowOutsideClick: false,
        type: "info",
        confirmButtonClass: 'btn btn-primary',
        buttonsStyling: false,
    }).then(function (result) {
        window.location.replace(base_url + "/brand");
    });
}

//on loading call SP with all Vendor (filter_vendor = 0 )
$("#sales_filter_date_range").val($("#custom_data_value").val());
callSalesVisualization('0', '1', $("#sales_filter_date_range").val());

//on Submitting form call SP with Filter Values
$('#sales_filter_form').on('submit', function (event) {
    event.preventDefault();

    var type = $('#sales_filter_range').val();

    switch (type) {
        case '1':
            $("#sales_filter_date_range").val($("#custom_data_value").val());
            break;
        case '2':
            var value = $("#filter_range_picker").val();
            var firstDate = moment(value, "MM/DD/YYYY").day(0).format("MM/DD/YYYY");
            var lastDate = moment(value, "MM/DD/YYYY").day(6).format("MM/DD/YYYY");
            $("#sales_filter_date_range").val(firstDate + " - " + lastDate);
            break;
        case '3':
            var value = $("#filter_range_picker").val();
            var firstDate = moment(value, "MMM-YYYY").startOf('month').format("MM/DD/YYYY");
            var lastDate = moment(value, "MMM-YYYY").endOf('month').format("MM/DD/YYYY");
            $("#sales_filter_date_range").val(firstDate + " - " + lastDate);
            break;
        case '4':
            var value = $("#filter_range_picker").val();
            var firstDate = moment(value, "YYYY").startOf('year').format("MM/DD/YYYY");
            var lastDate = moment(value, "YYYY").endOf('year').format("MM/DD/YYYY");
            $("#sales_filter_date_range").val(firstDate + " - " + lastDate);
            break;
    }
    var filter_vendor = $('#sales_filter_vendor').val();
    var filter_range = $('#sales_filter_range').val();
    var filter_date_range = $('#sales_filter_date_range').val();

    $('#filter_vendor').val(filter_vendor);
    $('#filter_range').val(filter_range);

    callSalesVisualization(filter_vendor, filter_range, filter_date_range);
});

//Ajax Call Function
function callSalesVisualization(filter_vendor, filter_range, filter_date_range) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/sales/visual/graph",
        type: "POST",
        data: {
            vendor: filter_vendor,
            range: filter_range,
            date_range: filter_date_range,
        },
        cache: false,
        success: function (response) {
            if (response.error) {
                Swal.fire({
                    title: "Error",
                    text: response.error,
                    allowOutsideClick: false,
                    type: "info",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            } else {
                changeCardHeaders(filter_range);
                if (response.saleSummary[0]) {
                    setSaleSummary(response.saleSummary[0], response.saleSummaryAlerts);
                } else {
                    $('#shipped_cogs_summary').html('-');
                    $('#shipped_cogs_prior_period').html('-');
                    $('#shipped_units_prior_period').html('-');
                    $('#shipped_cogs_ptp').html('-');
                    $('#shipped_units_ptp').html('-');
                    $('#shipped_units_summary').html('-');
                    $('#acu_summary').html('-');
                }
                if (response.saleGraph) {
                    generateSaleGraph(response.saleGraph, response.saleGraphAlerts);
                }
                if (response.saleTopAsinDecrease) {
                    generateSaleTopAsinDecrease(response.saleTopAsinDecrease, response.saleTopAsinDecreaseAlerts);
                }
                if (response.saleTopAsinIncrease) {
                    generateSaleTopAsinIncrease(response.saleTopAsinIncrease, response.saleTopAsinIncreaseAlerts);
                }
                if (response.saleTopAsinShippedCogs) {
                    generateSaleTopAsinShippedCogs(response.saleTopAsinShippedCogs, response.saleTopAsinShippedCogsAlerts);
                }
                if (response.saleCategory) {
                    generateSaleCategory(response.saleCategory, response.saleCategoryAlerts);
                }
            }
        },
    });
}

function generateSaleGraph(saleGraph, Alerts) {
    record1 = [];
    record2 = [];
    label = [];
    date_range = [];
    record1_alert = [];
    record2_alert = [];
    for (var count = 0; count < saleGraph.length; count++) {
        record1[count] = parseInt(Math.round(saleGraph[count].shipped_cogs));
        record2[count] = parseInt(Math.round(saleGraph[count].shipped_units));
        label[count] = saleGraph[count].sale_date;
        date_range[count] = saleGraph[count].date_range;

        let check_shipped_cogs_alert = "no"
        let check_shipped_units_alert = "no"

        for (var i = 0; i < Alerts.length; i++) {
            let shipped_cogs = parseInt(Math.round(saleGraph[count].shipped_cogs));
            let shipped_units = parseInt(Math.round(saleGraph[count].shipped_cogs));
            let reported_value = parseInt(Alerts[i].reported_value);
            if (shipped_cogs == reported_value && Alerts[i].reported_attribute == 'shipped_cogs') {
                check_shipped_cogs_alert = 'yes';
            }
            if (shipped_units == reported_value && Alerts[i].reported_attribute == 'shipped_unit') {
                check_shipped_units_alert = 'yes';
            }
        }
        record1_alert[count] = check_shipped_cogs_alert;
        record2_alert[count] = check_shipped_units_alert;

    }
    setTimeout(function () {
        chart.load({
            json: {
                'x': label,
                'Shipped COGS': record1,
                'Shipped Units': record2,
            },
        });
    }, 1000);
}

function changeCardHeaders(filter_range) {
    switch (filter_range) {
        case '1':
            $('#chart_heading').html("Daily Shipped COGS");
            $('#top_asin_shipped_cogs_heading').html("Top 10 ASIN by Shipped COGS");
            $('#top_asin_decrease_heading').html("Top 5 ASIN Decrease");
            $('#top_asin_increase_heading').html("Top 5 ASIN Increase");
            $('#category_chart_heading').html("Sales by Category");

            $('#shipped_cogs_prior_period_heading').html("Prior Period");
            $('#shipped_units_prior_period_heading').html("Prior Period");
            break;
        case '2':
            $('#chart_heading').html("Daily Shipped COGS");
            $('#top_asin_shipped_cogs_heading').html("Top 10 ASIN of the Week");
            $('#top_asin_decrease_heading').html("Top 5 ASIN Decrease");
            $('#top_asin_increase_heading').html("Top 5 ASIN Increase");
            $('#category_chart_heading').html("Sales by Category");

            $('#shipped_cogs_prior_period_heading').html("WOW");
            $('#shipped_units_prior_period_heading').html("WOW");
            break;
        case '3':
            $('#chart_heading').html("Weekly Shipped COGS");
            $('#top_asin_shipped_cogs_heading').html("Top 10 ASIN of the Month");
            $('#top_asin_decrease_heading').html("Top 5 ASIN Decrease");
            $('#top_asin_increase_heading').html("Top 5 ASIN Increase");
            $('#category_chart_heading').html("Sales By Category");

            $('#shipped_cogs_prior_period_heading').html("MOM");
            $('#shipped_units_prior_period_heading').html("MOM");
            break;
        case '4':
            $('#chart_heading').html("Monthly Shipped COGS");
            $('#top_asin_shipped_cogs_heading').html("Top 10 ASIN of the Year");
            $('#top_asin_decrease_heading').html("Top 5 ASIN Decrease");
            $('#top_asin_increase_heading').html("Top 5 ASIN Increase");
            $('#category_chart_heading').html("Sales By Category");

            $('#shipped_cogs_prior_period_heading').html("YOY");
            $('#shipped_units_prior_period_heading').html("YOY");
            break;
    }
}

function generateSaleCategory(saleCategory, Alerts) {
    category_label = [];
    category_record1 = [];
    category_alert = [];
    for (var count = 0; count < saleCategory.length; count++) {
        category_record1[count] = Math.round(saleCategory[count].shipped_cogs);
        category_label[count] = saleCategory[count].subcategory;
        let check_category_alert = "no"
        for (var i = 0; i < Alerts.length; i++) {
            let shipped_cogs = parseInt(Math.round(saleCategory[count].shipped_cogs));
            let reported_value = parseInt(Alerts[i].reported_value);
            if (shipped_cogs == reported_value && Alerts[i].reported_attribute == 'shipped_cogs') {
                check_category_alert = 'yes';
                break
            }
        }
        category_alert[count] = check_category_alert;
    }
    setTimeout(function () {
        category_chart.load({
            json: {
                'x': category_label,
                'Shipped COGS': category_record1,
            },
        });
    }, 1000);
}

function setSaleSummary(saleSummary, Alerts) {

    //check for shipped cogs
    var shipped_cogs_text = "";
    var shipped_cogs_icon = "success";
    let shipped_cogs_alert_trigger = "no";

    for (var i = 0; i < Alerts.length; i++) {
        let shipped_cogs = parseInt((saleSummary.shipped_cogs).replace(new RegExp("\\s|,|\\$", "gm"), ""))
        let reported_value = parseInt((Alerts[i].reported_value).replace(new RegExp("\\s|,|\\$", "gm"), ""))
        if (shipped_cogs == reported_value && Alerts[i].reported_attribute == 'shipped_cogs') {
            shipped_cogs_alert_trigger = 'yes';
            break;
        }
    }
    if (shipped_cogs_alert_trigger === "yes") {
        shipped_cogs_text = "danger";
        shipped_cogs_icon = "danger";
    }
    var shipped_cogs_card = '<div>\n' +
        '<h2 class="' + shipped_cogs_text + ' text-bold-700 mb-0" id="shipped_cogs_summary"> - </h2>\n' +
        '<p class="' + shipped_cogs_text + '">Shipped COGS</p>\n' +
        '</div>\n' +
        '<div class="avatar bg-rgba-' + shipped_cogs_icon + ' p-50 m-0">\n' +
        '<div class="avatar-content">\n' +
        '<i class="feather icon-trending-up text-' + shipped_cogs_icon + ' font-medium-5"></i>\n' +
        '</div>\n' +
        '</div>';
    $('#shipped_cogs_summary_herder').html(shipped_cogs_card)

    //check for shipped units
    var shipped_units_text = "";
    var shipped_units_icon = "success";
    let shipped_units_alert_trigger = "no";
    for (var i = 0; i < Alerts.length; i++) {
        let shipped_units = parseInt((saleSummary.shipped_units).replace(new RegExp("\\s|,|\\$", "gm"), ""))
        let reported_value = parseInt((Alerts[i].reported_value).replace(new RegExp("\\s|,|\\$", "gm"), ""))
        if (shipped_units == reported_value && Alerts[i].reported_attribute == 'shipped_unit') {
            shipped_units_alert_trigger = 'yes';
            break;
        }
    }
    if (shipped_units_alert_trigger === "yes") {
        shipped_units_text = "danger";
        shipped_units_icon = "danger";
    }
    var shipped_units_card = '<div>\n' +
        '<h2 class="' + shipped_units_text + ' text-bold-700 mb-0" id="shipped_units_summary"> - </h2>\n' +
        '<p class="' + shipped_units_text + '">Shipped Units</p>\n' +
        '</div>\n' +
        '<div class="avatar bg-rgba-' + shipped_units_icon + ' p-50 m-0">\n' +
        '<div class="avatar-content">\n' +
        '<i class="feather icon-bar-chart text-' + shipped_units_icon + ' font-medium-5"></i>\n' +
        '</div>\n' +
        '</div>';
    $('#shipped_units_summary_herder').html(shipped_units_card)

    saleSummary.shipped_cogs != 'null' ? $('#shipped_cogs_summary').html(saleSummary.shipped_cogs) : $('#shipped_cogs_summary').html('-');
    saleSummary.shipped_cogs_prior_period != 'null' ? $('#shipped_cogs_prior_period').html(saleSummary.shipped_cogs_prior_period) : $('#shipped_cogs_prior_period').html('-');
    saleSummary.shipped_cogs_ptp != 'null' ? $('#shipped_cogs_ptp').html(saleSummary.shipped_cogs_ptp) : $('#shipped_cogs_ptp').html('-');

    saleSummary.shipped_units != 'null' ? $('#shipped_units_summary').html(saleSummary.shipped_units) : $('#shipped_units_summary').html('-');
    saleSummary.shipped_units_prior_period != 'null' ? $('#shipped_units_prior_period').html(saleSummary.shipped_units_prior_period) : $('#shipped_units_prior_period').html('-');
    saleSummary.shipped_units_ptp != 'null' ? $('#shipped_units_ptp').html(saleSummary.shipped_units_ptp) : $('#shipped_units_ptp').html('-');

    saleSummary.acu != 'null' ? $('#acu_summary').html(saleSummary.acu) : $('#acu_summary').html('-');

}

function generateSaleTopAsinDecrease(saleTopAsinDecrease, Alerts) {
    var html = "";
    html += "<table class='table mb-0'>" +
        "<thead>" +
        "<tr>" +
        "<th style='white-space: nowrap;'>ASIN</th>" +
        "<th style='white-space: nowrap;' title='Product Title'>Product</th>" +
        "<th style='white-space: nowrap;' title='Model No.'>Model</th>" +
        "<th style='white-space: nowrap;' title='Shipped COGS'>SC</th>" +
        "<th style='white-space: nowrap;' title='Shipped COGS Prior Period'>SC-PP</th>" +
        "<th></th>" +
        "</tr>" +
        "</thead>" +
        "<tbody>";

    if (saleTopAsinDecrease.length == 0) {
        html += "<tr>\n" +
            "    <td style='padding: 10px; white-space: nowrap;' align='center' colspan='7'>No data found</td>\n" +
            "</tr>";
    }
    for (var count = 0; count < saleTopAsinDecrease.length; count++) {
        let alert_trigger = "no";
        for (var i = 0; i < Alerts.length; i++) {
            let shipped_cogs = parseInt((saleTopAsinDecrease[count].shipped_cogs).replace(new RegExp("\\s|,|\\$", "gm"), ""))
            let reported_value = parseInt((Alerts[i].reported_value).replace(new RegExp("\\s|,|\\$", "gm"), ""))
            if (saleTopAsinDecrease[count].asin == Alerts[i].sub_reported_value && shipped_cogs == reported_value && Alerts[i].reported_attribute == 'shipped_cogs') {
                alert_trigger = 'yes';
                break;
            }
        }

        let tr_style = "";
        if (alert_trigger == "yes") {
            tr_style = "table-danger";
        }
        html += "<tr style='" + tr_style + "'>";
        html += "<td style='padding: 10px; white-space: nowrap;'>" + saleTopAsinDecrease[count].asin + "</td>";
        html += "<td title='" + (saleTopAsinDecrease[count].product_title).replace(new RegExp("\\'", "gm"), "`") + "' style='padding: 10px; white-space: nowrap;'>" + (saleTopAsinDecrease[count].product_title).substring(0, 10) + "...</td>";
        html += "<td title='" + (saleTopAsinDecrease[count].model_no).replace(new RegExp("\\'", "gm"), "`") + "' style='padding: 10px; white-space: nowrap;'>" + (saleTopAsinDecrease[count].model_no).substring(0, 5) + "</td>";
        html += "<td style='padding: 10px; white-space: nowrap;'>" + saleTopAsinDecrease[count].shipped_cogs + "</td>";
        html += "<td style='padding: 10px; white-space: nowrap;'>" + saleTopAsinDecrease[count].shipped_cogs_prior_period + "</td>";
        html += "<td style='padding: 10px; white-space: nowrap;'>" +
            " <button type='button' id='" + saleTopAsinDecrease[count].asin + "' title='Show Rules' value='saleTopAsinDecrease' class='threshold-value btn btn-sm waves-effect waves-light'><i class='feather icon-eye text-warning font-medium-5'></i></button>" +
            " <button type='button' id='" + saleTopAsinDecrease[count].asin + "' title='Set Rule' value='saleTopAsinDecrease' class='threshold btn-icon btn btn-sm waves-effect waves-light'><i class='feather icon-bell text-warning font-medium-5'></i></button>" +
            "</td>";
        html += "</tr>";
    }
    html += "</tbody></table>";
    $('#top_asin_decrease').html(html);
}

function generateSaleTopAsinIncrease(saleTopAsinIncrease, Alerts) {
    var html = "";
    html += "<table class='table mb-0'>" +
        "<thead>" +
        "<tr>" +
        "<th style='white-space: nowrap;'>ASIN</th>" +
        "<th style='white-space: nowrap;' title='Product Title'>Product</th>" +
        "<th style='white-space: nowrap;' title='Model No.'>Model</th>" +
        "<th style='white-space: nowrap;' title='Shipped COGS'>SC</th>" +
        "<th style='white-space: nowrap;' title='Shipped COGS Prior Period'>SC-PP</th>" +
        "<th></th>" +
        "</tr>" +
        "</thead>" +
        "<tbody>";
    if (saleTopAsinIncrease.length == 0) {
        html += "<tr>\n" +
            "    <td style='padding: 10px; white-space: nowrap;' align='center' colspan='7'>No data found</td>\n" +
            "</tr>";
    }
    for (var count = 0; count < saleTopAsinIncrease.length; count++) {
        let alert_trigger = "no";
        for (var i = 0; i < Alerts.length; i++) {
            let shipped_cogs = parseInt((saleTopAsinIncrease[count].shipped_cogs).replace(new RegExp("\\s|,|\\$", "gm"), ""))
            let reported_value = parseInt((Alerts[i].reported_value).replace(new RegExp("\\s|,|\\$", "gm"), ""))
            if (saleTopAsinIncrease[count].asin == Alerts[i].sub_reported_value && shipped_cogs == reported_value && Alerts[i].reported_attribute == 'shipped_cogs') {
                alert_trigger = 'yes';
                break;
            }
        }
        let tr_style = "";
        if (alert_trigger == "yes") {
            tr_style = "table-danger";
        }
        html += "<tr class='" + tr_style + "'>";
        html += "<td style='padding: 10px; white-space: nowrap;'>" + saleTopAsinIncrease[count].asin + "</td>";
        html += "<td title='" + (saleTopAsinIncrease[count].product_title).replace(new RegExp("\\'", "gm"), "`") + "' style='padding: 10px; white-space: nowrap;'>" + (saleTopAsinIncrease[count].product_title).substring(0, 10) + "...</td>";
        html += "<td title='" + (saleTopAsinIncrease[count].model_no).replace(new RegExp("\\'", "gm"), "`") + "' style='padding: 10px; white-space: nowrap;'>" + (saleTopAsinIncrease[count].model_no).substring(0, 5) + "</td>";
        html += "<td style='padding: 10px; white-space: nowrap;'>" + saleTopAsinIncrease[count].shipped_cogs + "</td>";
        html += "<td style='padding: 10px; white-space: nowrap;'>" + saleTopAsinIncrease[count].shipped_cogs_prior_period + "</td>";
        html += "<td style='padding: 10px; white-space: nowrap;'>" +
            " <button type='button' id='" + saleTopAsinIncrease[count].asin + "' title='Show Rules' value='saleTopAsinIncrease' class='threshold-value btn btn-sm waves-effect waves-light'><i class='feather icon-eye text-warning font-medium-5'></i></button>" +
            " <button type='button' id='" + saleTopAsinIncrease[count].asin + "' title='Set Rule' value='saleTopAsinIncrease' class='threshold btn-icon btn btn-sm waves-effect waves-light'><i class='feather icon-bell text-warning font-medium-5'></i></button>" +
            "</td>";
        html += "</tr>";
    }
    html += "</tbody></table>";
    $('#top_asin_increase').html(html);
}

function generateSaleTopAsinShippedCogs(saleTopAsinShippedCogs, Alerts) {
    var html = "";
    html += "<table class='table mb-0'>" +
        "<thead>" +
        "<tr>" +
        "<th style='white-space: nowrap;'>ASIN</th>" +
        "<th style='white-space: nowrap;' title='Product Title'>Product</th>" +
        "<th style='white-space: nowrap;' title='Model No.'>Model</th>" +
        "<th style='white-space: nowrap;' title='Shipped COGS'>SC</th>" +
        "<th style='white-space: nowrap;' title='Shipped COGS %age Total'>SC-PT</th>" +
        "<th></th>" +
        "</tr>" +
        "</thead>" +
        "<tbody>";
    if (saleTopAsinShippedCogs.length == 0) {
        html += "<tr>\n" +
            "    <td style='padding: 10px; white-space: nowrap;' align='center' colspan='7'>No data found</td>\n" +
            "</tr>";
    }
    for (var count = 0; count < saleTopAsinShippedCogs.length; count++) {
        let alert_trigger = "no";
        for (var i = 0; i < Alerts.length; i++) {
            let shipped_cogs = parseInt((saleTopAsinShippedCogs[count].shipped_cogs).replace(new RegExp("\\s|,|\\$", "gm"), ""))
            let reported_value = parseInt((Alerts[i].reported_value).replace(new RegExp("\\s|,|\\$", "gm"), ""))
            if (saleTopAsinShippedCogs[count].asin == Alerts[i].sub_reported_value && shipped_cogs == reported_value && Alerts[i].reported_attribute == 'shipped_cogs') {
                alert_trigger = 'yes';
                break;
            }
        }
        let tr_style = "";
        if (alert_trigger == "yes") {
            tr_style = "table-danger";
        }
        html += "<tr class='" + tr_style + "'>";
        html += "<td style='padding: 10px; white-space: nowrap;'>" + saleTopAsinShippedCogs[count].asin + "</td>";
        html += "<td title='" + (saleTopAsinShippedCogs[count].product_title).replace(new RegExp("\\'", "gm"), "`") + "' style='padding: 10px; white-space: nowrap;' >" + (saleTopAsinShippedCogs[count].product_title).substring(0, 10) + "...</td>";
        html += "<td title='" + (saleTopAsinShippedCogs[count].model_no).replace(new RegExp("\\'", "gm"), "`") + "' style='padding: 10px; white-space: nowrap;'>" + (saleTopAsinShippedCogs[count].model_no).substring(0, 5) + "</td>";
        html += "<td style='padding: 10px; white-space: nowrap;'>" + saleTopAsinShippedCogs[count].shipped_cogs + "</td>";
        html += "<td style='padding: 10px; white-space: nowrap;'>" + saleTopAsinShippedCogs[count].shipped_cogs_percentage_of_total + "</td>";
        html += "<td style='padding: 10px; white-space: nowrap;'>" +
            " <button type='button' id='" + saleTopAsinShippedCogs[count].asin + "' title='Show Rules' value='saleTopAsinShippedCogs' class='threshold-value btn btn-sm waves-effect waves-light'><i class='feather icon-eye text-warning font-medium-5'></i></button>" +
            " <button type='button' id='" + saleTopAsinShippedCogs[count].asin + "' title='Set Rule' value='saleTopAsinShippedCogs' class='threshold btn-icon btn btn-sm waves-effect waves-light'><i class='feather icon-bell text-warning font-medium-5'></i></button>" +
            "</td>";
        html += "</tr>";
    }
    html += "</tbody></table>";
    $('#top_asin_shipped_cogs').html(html);
}
