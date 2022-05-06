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

$("#shipped_cogs_subcategory_filter").select2({
    dropdownParent: $("#sales_filter_form"),
    placeholder: "Select Subcategories (optional)",
    maximumSelectionLength: 15,
    language: {
        noResults: function (e) {
            return "No subcategory found";
        },
        maximumSelected: function (e) {
            return "Max " + e.maximum + " subcategories can be selected";
        },
    }
});

$("#sales_filter_date_range").val($("#custom_data_value").val());

$('#custom_data_value').on('apply.daterangepicker', function (ev, picker) {
    $("#sales_filter_date_range").val($("#custom_data_value").val());
});

var value = ($("#sales_filter_date_range").val()).split(" - ");
var start_date_text = value[0];
var end_date_text = value[1];
var date_filter_range_value = moment(start_date_text, "MM/DD/YYYY").format("MMM DD, YYYY") + " - " + moment(end_date_text, "MM/DD/YYYY").format("MMM DD, YYYY");
$("#selected_date_text").html(date_filter_range_value);

document.addEventListener('DOMContentLoaded', function (e) {

    //on Submitting form call SP with Filter Values
    $('#sales_filter_form').on('submit', function (event) {
        event.preventDefault();

        var type = $('#sales_filter_range').val();

        switch (type) {
            case '1':
                $("#sales_filter_date_range").val($("#custom_data_value").val());
                var value = ($("#sales_filter_date_range").val()).split(" - ");
                var start_date_text = value[0];
                var end_date_text = value[1];
                var date_filter_range_value = moment(start_date_text, "MM/DD/YYYY").format("MMM DD, YYYY") + " - " + moment(end_date_text, "MM/DD/YYYY").format("MMM DD, YYYY");
                $("#selected_date_text").html(date_filter_range_value);
                break;
            case '2':
                var value = $("#filter_range_picker").val();
                var firstDate = moment(value, "MM/DD/YYYY").day(0).format("MM/DD/YYYY");
                var lastDate = moment(value, "MM/DD/YYYY").day(6).format("MM/DD/YYYY");
                $("#sales_filter_date_range").val(firstDate + " - " + lastDate);
                var date_filter_range_value = moment(value, "MM/DD/YYYY").day(0).format("MMM DD, YYYY") + " - " + moment(value, "MM/DD/YYYY").day(6).format("MMM DD, YYYY");
                $("#selected_date_text").html(date_filter_range_value);
                break;
            case '3':
                var value = $("#filter_range_picker").val();
                var firstDate = moment(value, "MMM-YYYY").startOf('month').format("MM/DD/YYYY");
                var lastDate = moment(value, "MMM-YYYY").endOf('month').format("MM/DD/YYYY");
                $("#sales_filter_date_range").val(firstDate + " - " + lastDate);
                $("#selected_date_text").html(moment(firstDate).startOf('month').format("MMMM YYYY"));
                break;
            case '4':
                var value = $("#filter_range_picker").val();
                var firstDate = moment(value, "YYYY").startOf('year').format("MM/DD/YYYY");
                var lastDate = moment(value, "YYYY").endOf('year').format("MM/DD/YYYY");
                $("#sales_filter_date_range").val(firstDate + " - " + lastDate);
                $("#selected_date_text").html(value);
                break;
        }
        var filter_vendor = $('#sales_filter_vendor').val();
        var filter_range = $('#sales_filter_range').val();
        var filter_date_range = $('#sales_filter_date_range').val();
        let subcategory = $('#shipped_cogs_subcategory_filter').val();

        $('#filter_vendor').val(filter_vendor);
        $('#filter_range').val(filter_range);

        callSalesVisualization(filter_vendor, filter_range, filter_date_range);
        callSubCategorySalesVisualization(filter_vendor, filter_range, filter_date_range, subcategory)
    });
    $('#sales_filter_vendor').on('change', function () {
        var filter_vendor = $('#sales_filter_vendor').val();
        getVendorSubCategory(filter_vendor);
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

var shipped_cogs_gauge1 = 0;
var shipped_cogs_gauge2 = 0;
var shipped_cogs_gauge_value = "-";
var shipped_cogs_gauge_trailing_value = [];
var shipped_cogs_gauge_trailing_label = [];

var net_receipts_gauge1 = 0;
var net_receipts_gauge2 = 0;
var net_receipts_gauge_value = "-";
var net_receipts_gauge_trailing_value = [];
var net_receipts_gauge_trailing_label = [];

var po_plan_gauge1 = 0;
var po_plan_gauge_value = "-";
var po_plan_gauge_trailing_value = [];
var po_plan_gauge_trailing_label = [];

var yoy_growth_yoy = [];
var yoy_growth_label = [];
var yoy_growth_tooltip = [];
var yoy_growth_yoy_alerts = [];

var shipped_cogs_by_granularity_value = [];
var shipped_cogs_by_granularity_label = [];
var shipped_cogs_by_granularity_tooltip = [];
var shipped_cogs_by_granularity_value_alerts = [];

var shipped_cogs_by_subcategory_value = [];
var shipped_cogs_by_subcategory_label = [];
var shipped_cogs_by_subcategory_tooltip = [];

var net_receipts_by_subcategory_value = [];
var net_receipts_by_subcategory_label = [];
var net_receipts_by_subcategory_tooltip = [];

var po_confirmed_rate_by_subcategory_value = [];
var po_confirmed_rate_by_subcategory_label = [];
var po_confirmed_rate_by_subcategory_tooltip = [];

var shipped_cogs_gauge = c3.generate({
    bindto: d3.select('#shipped_cogs_gauge'),
    data: {
        columns: [
            ['PTP', shipped_cogs_gauge1],
            ['YOY', shipped_cogs_gauge2],
        ],
        type: 'gauge',
    },
    gauge: {
        label: {
            show: true // to turn off the min/max labels.
        },
        min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
        max: 100, // 100 is default
        width: 40 // for adjusting arc thickness
    },
    color: {
        pattern: ['#4DD0EA', '#FFB371']
    },
    size: {
        height: 100
    },
    transition: {
        duration: 100
    },
    legend: {
        show: false,
    },
    tooltip: {
        show: true,
        format: {
            value: function (value, ratio, id) {
                return value + "%";
            }
        }
    },
});
var shipped_cogs_gauge_trailing = c3.generate({
    bindto: d3.select('#shipped_cogs_gauge_trailing'),
    data: {
        x: 'x',
        json: {
            'x': shipped_cogs_gauge_trailing_label,
            'YOY': shipped_cogs_gauge_trailing_value,
        },
        colors: {
            'YOY': function (d) {
                return (d.value <= 0) ? '#FF0000' : '#4DD0EA';
            }
        },
        types: {
            'YOY': 'bar',
        },
    },
    axis: {
        x: {
            type: 'category',
            show: false
        },
        y: {
            show: false,
            tick: {
                format: function (v, id, i, j) { return v + '%'; }
            }
        },
    },
    size: {
        height: 60,
    },
    bar: {
        width: {
            ratio: 0.8 // this makes bar width 50% of length between ticks
        },
    },
    transition: {
        duration: 100
    },
    legend: {
        show: false,
    },
});

var net_receipts_gauge = c3.generate({
    bindto: d3.select('#net_receipts_gauge'),
    data: {
        columns: [
            ['PTP', net_receipts_gauge1],
            ['YOY', net_receipts_gauge2],
        ],
        type: 'gauge',
    },
    gauge: {
        label: {
            show: true // to turn off the min/max labels.
        },
        min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
        max: 100, // 100 is default
        width: 40 // for adjusting arc thickness
    },
    color: {
        pattern: ['#4DD0EA', '#FFB371']
    },
    size: {
        height: 100
    },
    transition: {
        duration: 100
    },
    legend: {
        show: false,
    },
    tooltip: {
        show: true,
        format: {
            value: function (value, ratio, id) {
                return value + "%";
            }
        }
    },
});
var net_receipts_gauge_trailing = c3.generate({
    bindto: d3.select('#net_receipts_gauge_trailing'),
    data: {
        x: 'x',
        json: {
            'x': net_receipts_gauge_trailing_label,
            'YOY': net_receipts_gauge_trailing_value,
        },
        colors: {
            'YOY': function (d) {
                return (d.value <= 0) ? '#FF0000' : '#4DD0EA';
            }
        },
        types: {
            'YOY': 'bar',
        },
    },
    axis: {
        x: {
            type: 'category',
            show: false
        },
        y: {
            show: false,
            tick: {
                format: function (v, id, i, j) { return v + '%'; }
            }
        },
    },
    size: {
        height: 60,
    },
    bar: {
        width: {
            ratio: 0.8 // this makes bar width 50% of length between ticks
        },
    },
    transition: {
        duration: 100
    },
    legend: {
        show: false,
    },
});

var po_plan_gauge = c3.generate({
    bindto: d3.select('#po_plan_gauge'),
    data: {
        columns: [
            ['YOY', po_plan_gauge1],
        ],
        type: 'gauge',
    },
    gauge: {
        label: {
            show: true // to turn off the min/max labels.
        },
        min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
        max: 100, // 100 is default
        width: 40 // for adjusting arc thickness
    },
    color: {
        pattern: ['#4DD0EA', '#FFB371']
    },
    size: {
        height: 100
    },
    transition: {
        duration: 100
    },
    legend: {
        show: false,
    },
    tooltip: {
        show: true,
        format: {
            value: function (value, ratio, id) {
                return value + "%";
            }
        }
    },
});
var po_plan_gauge_trailing = c3.generate({
    bindto: d3.select('#po_plan_gauge_trailing'),
    data: {
        x: 'x',
        json: {
            'x': po_plan_gauge_trailing_label,
            'Confirmation': po_plan_gauge_trailing_value,
        },
        colors: {
            'Confirmation': function (d) {
                return (d.value <= 0) ? '#FF0000' : '#4DD0EA';
            }
        },
        types: {
            'Confirmation': 'bar',
        },
    },
    axis: {
        x: {
            type: 'category',
            show: false
        },
        y: {
            show: false,
            tick: {
                format: function (v, id, i, j) { return v + '%'; }
            }
        },
    },
    size: {
        height: 60,
    },
    bar: {
        width: {
            ratio: 0.8 // this makes bar width 50% of length between ticks
        },
    },
    transition: {
        duration: 100
    },
    legend: {
        show: false,
    },
});

var yoy_growth_chart = c3.generate({
    bindto: d3.select('#yoy_growth_chart'),
    size: {
        height: 320,
    },
    data: {
        x: 'x',
        json: {
            'x': yoy_growth_label,
            'YOY': yoy_growth_yoy,
        },
        colors: {
            'YOY': '#FFB371',
        },
        types: {
            'YOY': 'spline',
        },
        labels: {
            format: {
                'Shipped COGS': function (v, id, i, j) {
                    if (yoy_growth_yoy_alerts[i] == "yes") {
                        return "\uf024";
                    }
                },
            },
        }
    },
    axis: {
        x: {
            type: 'category',
            tick: {
                rotate: -80,
                multiline: false
            },
            height: 70,
            show: true
        },
        y: {
            show: true,
            tick: {
                format: function (v, id, i, j) { return v + '%'; }
            }
        },
    },
    bar: {
        width: {
            ratio: 0.6 // this makes bar width 50% of length between ticks
        },
    },
    transition: {
        duration: 100
    },
    grid: {
        y: {
            show: true
        }
    },
    legend: {
        show: false,
    },
    tooltip: {
        format: {
            title: function (d) {
                return yoy_growth_tooltip[d];
            },
        }
    }
});
var po_confirmed_rate_by_subcategory_chart = c3.generate({
    bindto: d3.select('#po_confirmed_rate_by_subcategory_chart'),
    size: {
        height: 320,
    },
    data: {
        x: 'x',
        json: {
            'x': po_confirmed_rate_by_subcategory_label,
            'PO Confirmed Rate': po_confirmed_rate_by_subcategory_value,
        },
        colors: {
            'PO Confirmed Rate': '#FFB371',
        },
        types: {
            'PO Confirmed Rate': 'bar',
        },
        labels: {
            format: {
                'PO Confirmed Rate': function (v, id, i, j) {
                    return v + '%';
                },
            },
        }
    },
    axis: {
        x: {
            type: 'category',
            tick: {
                rotate: -80,
                multiline: false
            },
            height: 70,
            show: true
        },
        y: {
            show: true,
            tick: {
                format: function (v, id, i, j) { return v + '%'; }
            }
        },
    },
    bar: {
        width: {
            ratio: 0.6 // this makes bar width 50% of length between ticks
        },
    },
    transition: {
        duration: 100
    },
    legend: {
        show: false,
    },
    grid: {
        y: {
            show: true
        }
    },
    tooltip: {
        format: {
            title: function (d) {
                return po_confirmed_rate_by_subcategory_tooltip[d];
            },
        }
    }
});

var shipped_cogs_by_granularity_chart = c3.generate({
    bindto: d3.select('#shipped_cogs_by_granularity_chart'),
    size: {
        height: 370,
    },
    data: {
        x: 'x',
        json: {
            'x': shipped_cogs_by_granularity_label,
            'Shipped COGS': shipped_cogs_by_granularity_value,
        },
        colors: {
            'Shipped COGS': '#4DD0EA',
        },
        types: {
            'Shipped COGS': 'bar',
        },
        labels: {
            format: {
                'Shipped COGS': function (v, id, i, j) {
                    if (shipped_cogs_by_granularity_value_alerts[i] == "yes") {
                        return "\uf024";
                    }
                },
            },
        }
    },
    grid: {
        y: { show: false },
        x: { show: false }
    },
    legend: {
        show: false,
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
            height: 70,
            show: true,
        },
        y: {
            show: true,
            tick: {
                format: d3.format("$,")
            }
        }
    },
    tooltip: {
        format: {
            title: function (d) {
                return shipped_cogs_by_granularity_tooltip[d];
            },
        }
    }
});

//on loading call Generate Charts Structure
var shipped_cogs_by_subcategory_chart = c3.generate({
    bindto: d3.select('#shipped_cogs_by_subcategory_chart'),
    size: {
        height: 330,
    },
    data: {
        x: 'x',
        json: {
        },
        type: 'spline'
    },
    axis: {
        x: {
            type: 'category',
            tick: {
                rotate: -80,
                multiline: false
            },
            height: 70,
            show: true,
        },
        y: {
            show: true,
            tick: {
                format: d3.format('$,')
            }
        },
    },
    transition: {
        duration: 100
    },
    legend: {
        show: true,
    },
    grid: {
        y: {
            show: true
        }
    },
    tooltip: {
        format: {
            title: function (d) {
                return shipped_cogs_by_subcategory_tooltip[d];
            },
        }
    }
});
var net_receipts_by_subcategory_chart = c3.generate({
    bindto: d3.select('#net_receipts_by_subcategory_chart'),
    size: {
        height: 330,
    },
    data: {
        x: "x",
        json: {
        },
        type: 'spline'
    },
    axis: {
        x: {
            type: 'category',
            tick: {
                rotate: -80,
                multiline: false
            },
            height: 70,
            show: true,
        },
        y: {
            show: true,
            tick: {
                format: d3.format('$,')
            }
        },
    },
    transition: {
        duration: 100
    },
    legend: {
        show: true,
    },
    grid: {
        y: {
            show: true
        }
    },
    tooltip: {
        format: {
            title: function (d) {
                return net_receipts_by_subcategory_tooltip[d];
            },
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
getVendorSubCategory('0');

//Ajax Call Function
function callSalesVisualization(filter_vendor, filter_range, filter_date_range) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/sales/visual/new/sale",
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

                //shipped COGS Gauge and trailing
                if (typeof (response.shippedCogsGauge[0]) !== 'undefined') {
                    generateShippedCOGSGauge(response.shippedCogsGauge[0], response.shippedCogsAlerts);
                } else {
                    setTimeout(function () {
                        shipped_cogs_gauge.internal.config.gauge_max = 100;
                        shipped_cogs_gauge.internal.config.gauge_min = 0;
                        shipped_cogs_gauge.load({
                            columns: [
                                ['PTP', 0],
                                ['YOY', 0],
                            ],
                        });
                    }, 1000);
                }
                if (response.shippedCogsGaugeTrailing) {
                    generateShippedCOGSTrailing(response.shippedCogsGaugeTrailing);
                } else {
                    shipped_cogs_gauge_trailing_label = [];
                    shipped_cogs_gauge_trailing_value = [];
                    setTimeout(function () {
                        shipped_cogs_gauge_trailing.unload({
                            ids: ['YOY']
                        });
                    }, 1000);
                }

                //Net Receipts Gauge and trailing
                if (typeof (response.netReceiptsGauge[0]) !== 'undefined') {
                    generateNetReceiptsGauge(response.netReceiptsGauge[0], response.netReceiptsAlerts);
                } else {
                    setTimeout(function () {
                        net_receipts_gauge.internal.config.gauge_max = 100;
                        net_receipts_gauge.internal.config.gauge_min = 0;
                        net_receipts_gauge.load({
                            columns: [
                                ['PTP', 0],
                                ['YOY', 0],
                            ],
                        });
                    }, 1000);
                }

                if (response.netReceiptsGaugeTrailing) {
                    generateNetReceiptsTrailing(response.netReceiptsGaugeTrailing);
                } else {
                    net_receipts_gauge_trailing_label = [];
                    net_receipts_gauge_trailing_value = [];
                    setTimeout(function () {
                        net_receipts_gauge_trailing.unload({
                            ids: ['YOY']
                        });
                    }, 1000);
                }

                //PO Plan Gauge and trailing
                if (typeof (response.poPlanGauge[0]) !== 'undefined') {
                    generatePoPlanGauge(response.poPlanGauge[0], response.poPlanAlerts);
                } else {
                    setTimeout(function () {
                        po_plan_gauge.internal.config.gauge_max = 100;
                        po_plan_gauge.internal.config.gauge_min = 0;
                        po_plan_gauge.load({
                            columns: [
                                ['YOY', 0],
                            ],
                        });
                    }, 1000);
                }
                if (response.poPlanGaugeTrailing) {
                    generatePoPlanTrailing(response.poPlanGaugeTrailing);
                } else {
                    po_plan_gauge_trailing_label = [];
                    po_plan_gauge_trailing_value = [];
                    setTimeout(function () {
                        po_plan_gauge_trailing.unload({
                            ids: ['Confirmation']
                        });
                    }, 1000);
                }

                //YOY Growth
                if (response.yoyGrowthChart) {
                    generateYOYGrowth(response.yoyGrowthChart, response.yoyAlerts);
                }

                //shipped COGS Chart
                if (response.shippedCogsByGranularityChart) {
                    generateShippedCOGSGranularity(response.shippedCogsByGranularityChart, response.shippedCogsGraphAlerts);
                }

                //TOP ASIN Decrease
                generateSaleTopAsinDecrease(response.saleTopAsinDecrease, response.saleTopAsinDecreaseAlerts);
                //TOP ASIN Increase
                generateSaleTopAsinIncrease(response.saleTopAsinIncrease, response.saleTopAsinIncreaseAlerts);
                //TOP ASIN Shipped COGS
                generateSaleTopAsinShippedCogs(response.saleTopAsinShippedCogs, response.saleTopAsinShippedCogsAlerts);
            }
        },
    });
}
function getVendorSubCategory(filter_vendor) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/sales/visual/new/getSubcategory",
        type: "POST",
        data: {
            vendor: filter_vendor,
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
                if (response.subcategory) {
                    updateSubcategory(response.subcategory);
                } else {
                    var html = "<option disabled>-- No subcategory found --</option>";
                    $('#shipped_cogs_subcategory_filter').html(html);
                }
            }
        },
    });
}
function callSubCategorySalesVisualization(filter_vendor, filter_range, filter_date_range, subcategory) {
    //callSubCategorySIP(filter_vendor, filter_range, filter_date_range, subcategory);
    callSubCategoryShippedCOGS(filter_vendor, filter_range, filter_date_range, subcategory);
    callSubCategoryNetReceipts(filter_vendor, filter_range, filter_date_range, subcategory);
    callSubCategoryPoConfirmedRate(filter_vendor, filter_range, filter_date_range, subcategory)
}
function callSubCategorySIP(filter_vendor, filter_range, filter_date_range, subcategory) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/sales/visual/new/subcategory_sip",
        type: "POST",
        data: {
            vendor: filter_vendor,
            range: filter_range,
            date_range: filter_date_range,
            subcategory: subcategory,
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
                //Sales Inventory PO Facts for Subcategory Value
                if (typeof (response.sipSubcategoryValue[0]) !== 'undefined') {
                    sip_data = response.sipSubcategoryValue[0]

                    sip_data.shipped_cogs != 'null' ? $('#shipped_cogs_by_subcategory_total').html(sip_data.shipped_cogs) : $('#shipped_cogs_by_subcategory_total').html('-');
                    sip_data.net_received != 'null' ? $('#net_receipts_by_subcategory_total').html(sip_data.net_received) : $('#net_receipts_by_subcategory_total').html('-');
                    sip_data.confirmation_rate != 'null' ? $('#po_confirmed_rate_by_subcategory_total').html(sip_data.confirmation_rate) : $('#po_confirmed_rate_by_subcategory_total').html('-');
                } else {
                    $('#shipped_cogs_by_subcategory_total').html('-');
                    $('#net_receipts_by_subcategory_total').html('-');
                    $('#po_confirmed_rate_by_subcategory_total').html("-");
                }
            }
        },
    });
}
function callSubCategoryShippedCOGS(filter_vendor, filter_range, filter_date_range, subcategory) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/sales/visual/new/subcategory_shipped_cogs",
        type: "POST",
        data: {
            vendor: filter_vendor,
            range: filter_range,
            date_range: filter_date_range,
            subcategory: subcategory,
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
                //Shipped COGS By Subcategory Chart
                if (response) {
                    generateShippedCOGSSubcategory(response);
                }
            }
        },
    });
}
function callSubCategoryNetReceipts(filter_vendor, filter_range, filter_date_range, subcategory) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/sales/visual/new/subcategory_net_receipts",
        type: "POST",
        data: {
            vendor: filter_vendor,
            range: filter_range,
            date_range: filter_date_range,
            subcategory: subcategory,
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
                //Net Receipts By Subcategory Chart
                if (response) {
                    generateNetReceiptsSubcategory(response);
                }
            }
        },
    });
}
function callSubCategoryPoConfirmedRate(filter_vendor, filter_range, filter_date_range, subcategory) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/sales/visual/new/subcategory_po_confirmed_rate",
        type: "POST",
        data: {
            vendor: filter_vendor,
            range: filter_range,
            date_range: filter_date_range,
            subcategory: subcategory,
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
                //PO Confirmed By Subcategory Chart
                if (response.poConfirmedRateBySubcategoryChart) {
                    generatePoConfirmedRateSubcategory(response.poConfirmedRateBySubcategoryChart);
                }
            }
        },
    });
}

function changeCardHeaders(filter_range) {
    switch (filter_range) {
        case '1':
            $('#growth_title').html("Purchase Order YOY Growth");
            $('#shipped_cogs_by_granularity_title').html("Shipped COGS by Days");
            $('#shipped_cogs_by_subcategory_chart_title').html("Last 12 Days");
            $('#net_receipts_by_subcategory_chart_title').html("Last 12 Days");
            $('#po_confirmed_rate_by_subcategory_chart_title').html("Last 12 Days");

            $('#top_asin_shipped_cogs_heading').html("Top 10 ASIN by Shipped COGS");
            $('#top_asin_decrease_heading').html("Top 5 ASIN Decrease");
            $('#top_asin_increase_heading').html("Top 5 ASIN Increase");
            break;
        case '2':
            $('#growth_title').html("Purchase Order YOY Growth");
            $('#shipped_cogs_by_granularity_title').html("Shipped COGS by Week");
            $('#shipped_cogs_by_subcategory_chart_title').html("Last 12 Days");
            $('#net_receipts_by_subcategory_chart_title').html("Last 12 Days");
            $('#po_confirmed_rate_by_subcategory_chart_title').html("Last 12 Days");

            $('#top_asin_shipped_cogs_heading').html("Purchase Order YOY Growth");
            $('#top_asin_decrease_heading').html("Top 5 ASIN Decrease");
            $('#top_asin_increase_heading').html("Top 5 ASIN Increase");
            break;
        case '3':
            $('#growth_title').html("Purchase Order YOY Growth");
            $('#shipped_cogs_by_granularity_title').html("Shipped COGS by Month");
            $('#shipped_cogs_by_subcategory_chart_title').html("Last 12 Weeks");
            $('#net_receipts_by_subcategory_chart_title').html("Last 12 Weeks");
            $('#po_confirmed_rate_by_subcategory_chart_title').html("Last 12 Weeks");

            $('#top_asin_shipped_cogs_heading').html("Top 10 ASIN of the Month");
            $('#top_asin_decrease_heading').html("Top 5 ASIN Decrease");
            $('#top_asin_increase_heading').html("Top 5 ASIN Increase");
            break;
        case '4':
            $('#growth_title').html("Purchase Order YOY Growth");
            $('#shipped_cogs_by_granularity_title').html("Shipped COGS by Year");
            $('#shipped_cogs_by_subcategory_chart_title').html("Last 12 Months");
            $('#net_receipts_by_subcategory_chart_title').html("Last 12 Months");
            $('#po_confirmed_rate_by_subcategory_chart_title').html("Last 12 Months");

            $('#top_asin_shipped_cogs_heading').html("Top 10 ASIN of the Year");
            $('#top_asin_decrease_heading').html("Top 5 ASIN Decrease");
            $('#top_asin_increase_heading').html("Top 5 ASIN Increase");
            break;
    }
}

function updateSubcategory(vendorSubcategory) {
    var html = "";
    if (vendorSubcategory.length == 0) {
        //remove chart
        $('#po_confirmed_rate_by_subcategory_total').html('-');
        $('#net_receipts_by_subcategory_total').html('-');
        $('#shipped_cogs_by_subcategory_total').html("-");
        generateShippedCOGSSubcategory([]);
        generateNetReceiptsSubcategory([]);
        generatePoConfirmedRateSubcategory([]);
    }
    for (var count = 0; count < vendorSubcategory.length; count++) {
        if (vendorSubcategory[count].subcategory != null) {
            html += "<option value='" + vendorSubcategory[count].subcategory + "'>" + vendorSubcategory[count].subcategory + "</option>";
        }
    }
    $('#shipped_cogs_subcategory_filter').html(html);
}

function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}

function generateShippedCOGSGauge(ShippedCOGSGauge, Alerts) {
    ShippedCOGSGauge.shipped_cogs != null ? shipped_cogs_gauge_value = ShippedCOGSGauge.shipped_cogs : shipped_cogs_gauge_value = '-';
    ShippedCOGSGauge.ptp != null ? shipped_cogs_gauge1 = parseInt(Math.round(ShippedCOGSGauge.ptp)) : shipped_cogs_gauge1 = 0;
    ShippedCOGSGauge.yoy != null ? shipped_cogs_gauge2 = parseInt(Math.round(ShippedCOGSGauge.yoy)) : shipped_cogs_gauge2 = 0;

    let shipped_cogs_alert_trigger = "no";

    for (var i = 0; i < Alerts.length; i++) {
        let shipped_cogs = parseInt((ShippedCOGSGauge.shipped_cogs).replace(new RegExp("\\s|,|\\$", "gm"), ""))
        let reported_value = parseInt((Alerts[i].reported_value).replace(new RegExp("\\s|,|\\$", "gm"), ""))
        if (shipped_cogs == reported_value && Alerts[i].reported_attribute == 'shipped_cogs') {
            shipped_cogs_alert_trigger = 'yes';
            break;
        }
    }
    $("#shipped_cogs_gauge_value").removeClass("danger");
    if (shipped_cogs_alert_trigger === "yes") {
        $("#shipped_cogs_gauge_value").addClass("danger");
    }
    $('#shipped_cogs_gauge_value').html(shipped_cogs_gauge_value);

    let gauge_min = 0;
    let gauge_max = 100;
    let gauge_value1 = parseInt(shipped_cogs_gauge1);
    let gauge_value2 = parseInt(shipped_cogs_gauge2);

    if (gauge_value1 < 0 || gauge_value2 < 0) {
        gauge_min = ((parseInt(gauge_value2 / 100)) * 100) - 100;
        if (gauge_value1 < gauge_value2) {
            gauge_min = ((parseInt(gauge_value1 / 100)) * 100) - 100;
        }
    }
    if (gauge_value1 > 100 || gauge_value2 > 100) {
        gauge_max = ((parseInt(gauge_value2 / 100)) * 100) + 100;
        if (gauge_value1 > gauge_value2) {
            gauge_max = ((parseInt(gauge_value1 / 100)) * 100) + 100;
        }
    }


    setTimeout(function () {
        shipped_cogs_gauge.internal.config.gauge_max = gauge_max;
        shipped_cogs_gauge.internal.config.gauge_min = gauge_min;
        shipped_cogs_gauge.load({
            columns: [
                ['PTP', shipped_cogs_gauge1],
                ['YOY', shipped_cogs_gauge2],
            ],
        });
    }, 1000);
}
function generateShippedCOGSTrailing(ShippedCOGSTrailing) {
    shipped_cogs_gauge_trailing_label = [];
    shipped_cogs_gauge_trailing_value = [];
    for (var count = 0; count < ShippedCOGSTrailing.length; count++) {
        shipped_cogs_gauge_trailing_label[count] = ShippedCOGSTrailing[count].date_range;
        shipped_cogs_gauge_trailing_value[count] = parseInt(ShippedCOGSTrailing[count].yoy);
    }
    setTimeout(function () {
        shipped_cogs_gauge_trailing.load({
            json: {
                'x': shipped_cogs_gauge_trailing_label,
                'YOY': shipped_cogs_gauge_trailing_value,
            },
        });
    }, 1000);
}

function generateNetReceiptsGauge(NetReceiptsGauge, Alerts) {
    NetReceiptsGauge.net_received != null ? net_receipts_gauge_value = NetReceiptsGauge.net_received : net_receipts_gauge_value = '-';
    NetReceiptsGauge.ptp != null ? net_receipts_gauge1 = parseInt(Math.round(NetReceiptsGauge.ptp)) : net_receipts_gauge1 = 0;
    NetReceiptsGauge.yoy != null ? net_receipts_gauge2 = parseInt(Math.round(NetReceiptsGauge.yoy)) : net_receipts_gauge2 = 0;

    let net_recipts_alert_trigger = "no";

    for (var i = 0; i < Alerts.length; i++) {
        let net_recipts = parseInt((NetReceiptsGauge.net_received).replace(new RegExp("\\s|,|\\$", "gm"), ""))
        let reported_value = parseInt((Alerts[i].reported_value).replace(new RegExp("\\s|,|\\$", "gm"), ""))
        if (net_recipts == reported_value && Alerts[i].reported_attribute == 'net_received') {
            net_recipts_alert_trigger = 'yes';
            break;
        }
    }
    $("#net_receipts_gauge_value").removeClass("danger");
    if (net_recipts_alert_trigger === "yes") {
        $("#net_receipts_gauge_value").addClass("danger");
    }
    $('#net_receipts_gauge_value').html(net_receipts_gauge_value);
    let gauge_min = 0;
    let gauge_max = 100;
    let gauge_value1 = parseInt(net_receipts_gauge1);
    let gauge_value2 = parseInt(net_receipts_gauge2);

    if (gauge_value1 < 0 || gauge_value2 < 0) {
        gauge_min = ((parseInt(gauge_value2 / 100)) * 100) - 100;
        if (gauge_value1 < gauge_value2) {
            gauge_min = ((parseInt(gauge_value1 / 100)) * 100) - 100;
        }
    }
    if (gauge_value1 > 100 || gauge_value2 > 100) {
        gauge_max = ((parseInt(gauge_value2 / 100)) * 100) + 100;
        if (gauge_value1 > gauge_value2) {
            gauge_max = ((parseInt(gauge_value1 / 100)) * 100) + 100;
        }
    }


    setTimeout(function () {
        net_receipts_gauge.internal.config.gauge_max = gauge_max;
        net_receipts_gauge.internal.config.gauge_min = gauge_min;
        net_receipts_gauge.load({
            columns: [
                ['PTP', net_receipts_gauge1],
                ['YOY', net_receipts_gauge2],
            ],
        });
    }, 1000);
}
function generateNetReceiptsTrailing(NetReceiptsTrailing) {
    net_receipts_gauge_trailing_label = [];
    net_receipts_gauge_trailing_value = [];
    for (var count = 0; count < NetReceiptsTrailing.length; count++) {
        net_receipts_gauge_trailing_label[count] = NetReceiptsTrailing[count].date_range;
        net_receipts_gauge_trailing_value[count] = parseInt(NetReceiptsTrailing[count].yoy);
    }
    setTimeout(function () {
        net_receipts_gauge_trailing.load({
            json: {
                'x': net_receipts_gauge_trailing_label,
                'YOY': net_receipts_gauge_trailing_value,
            },
        });
    }, 1000);
}

function generatePoPlanGauge(PoPlanGauge, Alerts) {
    PoPlanGauge.confirmation_rate != null ? po_plan_gauge_value = PoPlanGauge.confirmation_rate : po_plan_gauge_value = '-';
    PoPlanGauge.yoy != null ? po_plan_gauge1 = parseInt(Math.round(PoPlanGauge.yoy)) : po_plan_gauge1 = 0;

    let confirmation_rate_alert_trigger = "no";

    for (var i = 0; i < Alerts.length; i++) {
        let confirmation_rate = parseInt((PoPlanGauge.confirmation_rate).replace(new RegExp("\\s|,|\\%|\\$", "gm"), ""))
        let reported_value = parseInt((Alerts[i].reported_value).replace(new RegExp("\\s|,|\\$", "gm"), ""))
        if (confirmation_rate == reported_value && Alerts[i].reported_attribute == 'confirmation_rate') {
            confirmation_rate_alert_trigger = 'yes';
            break;
        }
    }
    $("#po_plan_gauge_value").removeClass("danger");
    if (confirmation_rate_alert_trigger === "yes") {
        $("#po_plan_gauge_value").addClass("danger");
    }
    $('#po_plan_gauge_value').html(po_plan_gauge_value);

    let gauge_min = 0;
    let gauge_max = 100;
    let gauge_value1 = parseInt(po_plan_gauge1);

    if (gauge_value1 < 0) {
        gauge_min = ((parseInt(gauge_value1 / 100)) * 100) - 100;
    }
    if (gauge_value1 > 100) {
        gauge_max = ((parseInt(gauge_value1 / 100)) * 100) + 100;
    }

    setTimeout(function () {
        po_plan_gauge.internal.config.gauge_max = gauge_max;
        po_plan_gauge.internal.config.gauge_min = gauge_min;
        po_plan_gauge.load({
            columns: [
                ['YOY', po_plan_gauge1],
            ],
        });
    }, 1000);
}
function generatePoPlanTrailing(PoPlanTrailing) {
    po_plan_gauge_trailing_label = [];
    po_plan_gauge_trailing_value = [];
    for (var count = 0; count < PoPlanTrailing.length; count++) {
        po_plan_gauge_trailing_label[count] = PoPlanTrailing[count].date_range;
        po_plan_gauge_trailing_value[count] = parseInt(PoPlanTrailing[count].yoy);
    }
    setTimeout(function () {
        po_plan_gauge_trailing.load({
            json: {
                'x': po_plan_gauge_trailing_label,
                'Confirmation': po_plan_gauge_trailing_value,
            },
        });
    }, 1000);
}

function generateYOYGrowth(YOYGrowth, Alerts) {
    yoy_growth_label = [];
    yoy_growth_yoy = [];
    yoy_growth_tooltip = [];
    yoy_growth_yoy_alerts = [];
    for (var count = 0; count < YOYGrowth.length; count++) {
        yoy_growth_label[count] = YOYGrowth[count].ordered_on;
        yoy_growth_yoy[count] = parseInt(YOYGrowth[count].yoy);
        yoy_growth_tooltip[count] = YOYGrowth[count].date_range;

        let check_yoy_alert = "no"
        for (var i = 0; i < Alerts.length; i++) {
            let yoy = parseInt(Math.round(YOYGrowth[count].yoy));
            let reported_value = parseInt(Alerts[i].reported_value);
            if (yoy == reported_value && Alerts[i].reported_attribute == 'yoy') {
                check_yoy_alert = 'yes';
            }
        }
        yoy_growth_yoy_alerts[count] = check_yoy_alert;
    }
    setTimeout(function () {
        yoy_growth_chart.load({
            json: {
                'x': yoy_growth_label,
                'YOY': yoy_growth_yoy,
            },
        });
    }, 1000);
}
function generatePoConfirmedRateSubcategory(PoConfirmedRateSubcategory) {
    po_confirmed_rate_by_subcategory_label = [];
    po_confirmed_rate_by_subcategory_value = [];
    po_confirmed_rate_by_subcategory_tooltip = [];
    for (var count = 0; count < PoConfirmedRateSubcategory.length; count++) {
        po_confirmed_rate_by_subcategory_label[count] = PoConfirmedRateSubcategory[count].ordered_on;
        po_confirmed_rate_by_subcategory_value[count] = parseInt(PoConfirmedRateSubcategory[count].confirmation_rate);
        po_confirmed_rate_by_subcategory_tooltip[count] = PoConfirmedRateSubcategory[count].date_range;
    }
    setTimeout(function () {
        po_confirmed_rate_by_subcategory_chart.load({
            json: {
                'x': po_confirmed_rate_by_subcategory_label,
                'PO Confirmed Rate': po_confirmed_rate_by_subcategory_value,
            },
        });
    }, 1000);
}

function generateShippedCOGSGranularity(ShippedCOGSGranularity, Alerts) {
    shipped_cogs_by_granularity_label = [];
    shipped_cogs_by_granularity_tooltip = [];
    shipped_cogs_by_granularity_value = [];
    shipped_cogs_by_granularity_value_alerts = [];
    for (var count = 0; count < ShippedCOGSGranularity.length; count++) {
        shipped_cogs_by_granularity_label[count] = ShippedCOGSGranularity[count].sale_date;
        shipped_cogs_by_granularity_tooltip[count] = ShippedCOGSGranularity[count].date_range;
        shipped_cogs_by_granularity_value[count] = parseInt(ShippedCOGSGranularity[count].shipped_cogs);

        let check_shipped_cogs_alert = "no"
        for (var i = 0; i < Alerts.length; i++) {
            let shipped_cogs = parseInt(Math.round(ShippedCOGSGranularity[count].shipped_cogs));
            let reported_value = parseInt(Alerts[i].reported_value);
            if (shipped_cogs == reported_value && Alerts[i].reported_attribute == 'shipped_cogs') {
                check_shipped_cogs_alert = 'yes';
            }
        }
        shipped_cogs_by_granularity_value_alerts[count] = check_shipped_cogs_alert;
    }
    setTimeout(function () {
        shipped_cogs_by_granularity_chart.load({
            json: {
                'x': shipped_cogs_by_granularity_label,
                'Shipped COGS': shipped_cogs_by_granularity_value,
            },
        });
    }, 1000);
}

function generateShippedCOGSSubcategory(ShippedCOGSSubcategory) {
    shipped_cogs_by_subcategory_label = [];
    shipped_cogs_by_subcategory_value = [];
    shipped_cogs_by_subcategory_tooltip = [];

    shipped_cogs_by_subcategory_label = ShippedCOGSSubcategory.date;
    shipped_cogs_by_subcategory_value = JSON.parse(ShippedCOGSSubcategory.data);
    shipped_cogs_by_subcategory_tooltip = ShippedCOGSSubcategory.range;

    setTimeout(function () {
        shipped_cogs_by_subcategory_chart.unload();
    }, 500);

    setTimeout(function () {
        shipped_cogs_by_subcategory_chart.load({
            json: shipped_cogs_by_subcategory_value,
        });
    }, 1000);
}
function generateNetReceiptsSubcategory(NetReceiptsSubcategory) {
    net_receipts_by_subcategory_label = [];
    net_receipts_by_subcategory_value = [];
    net_receipts_by_subcategory_tooltip = [];

    net_receipts_by_subcategory_label = NetReceiptsSubcategory.date;
    net_receipts_by_subcategory_value = JSON.parse(NetReceiptsSubcategory.data);
    net_receipts_by_subcategory_tooltip = NetReceiptsSubcategory.range;

    setTimeout(function () {
        net_receipts_by_subcategory_chart.unload();
    }, 500);

    setTimeout(function () {
        net_receipts_by_subcategory_chart.load({
            json: net_receipts_by_subcategory_value,
        });
    }, 1000);
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
            "    <td style='padding: 7px; white-space: nowrap;' align='center' colspan='7'>No data found</td>\n" +
            "</tr>";
    }
    for (var count = 0; count < saleTopAsinDecrease.length; count++) {
        let alert_trigger = "no";
        for (var i = 0; i < Alerts.length; i++) {
            let shipped_cogs = parseInt((saleTopAsinDecrease[count].shipped_cogs).replace(new RegExp("\\s|,|\\$", "gm"), ""))
            let reported_value = parseInt((Alerts[i].reported_value).replace(new RegExp("\\s|,|\\$", "gm"), ""))
            // alert(shipped_cogs + "-" + reported_value);
            if (saleTopAsinDecrease[count].asin == Alerts[i].sub_reported_value && shipped_cogs == reported_value && Alerts[i].reported_attribute == 'shipped_cogs') {
                //  alert('ys');
                alert_trigger = 'yes';
                break;
            }
        }

        let tr_style = "";
        if (alert_trigger == "yes") {
            tr_style = "table-danger";
        }
        html += "<tr class='" + tr_style + "'>";
        html += "<td style='padding: 7px; white-space: nowrap;'>" + saleTopAsinDecrease[count].asin + "</td>";
        html += "<td title='" + (saleTopAsinDecrease[count].product_title).replace(new RegExp("\\'", "gm"), "`") + "' style='padding: 7px; white-space: nowrap;'>" + (saleTopAsinDecrease[count].product_title).substring(0, 10) + "...</td>";
        html += "<td title='" + (saleTopAsinDecrease[count].model_no).replace(new RegExp("\\'", "gm"), "`") + "' style='padding: 7px; white-space: nowrap;'>" + (saleTopAsinDecrease[count].model_no).substring(0, 5) + "</td>";
        html += "<td style='padding: 7px; white-space: nowrap;'>" + saleTopAsinDecrease[count].shipped_cogs + "</td>";
        html += "<td style='padding: 7px; white-space: nowrap;'>" + saleTopAsinDecrease[count].shipped_cogs_prior_period + "</td>";
        html += "<td style='padding: 7px; white-space: nowrap;'>" +
            " <button type='button' id='" + saleTopAsinDecrease[count].asin + "' title='Show Rules' value='newSaleTopAsinDecrease' class='threshold-value btn btn-sm waves-effect waves-light'><i class='feather icon-eye text-info font-medium-5'></i></button>" +
            " <button type='button' id='" + saleTopAsinDecrease[count].asin + "' title='Set Rule' value='newSaleTopAsinDecrease' class='threshold btn-icon btn btn-sm waves-effect waves-light'><i class='feather icon-bell text-info font-medium-5'></i></button>" +
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
            "    <td style='padding: 7px; white-space: nowrap;' align='center' colspan='7'>No data found</td>\n" +
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
        html += "<td style='padding: 7px; white-space: nowrap;'>" + saleTopAsinIncrease[count].asin + "</td>";
        html += "<td title='" + (saleTopAsinIncrease[count].product_title).replace(new RegExp("\\'", "gm"), "`") + "' style='padding: 7px; white-space: nowrap;'>" + (saleTopAsinIncrease[count].product_title).substring(0, 10) + "...</td>";
        html += "<td title='" + (saleTopAsinIncrease[count].model_no).replace(new RegExp("\\'", "gm"), "`") + "' style='padding: 7px; white-space: nowrap;'>" + (saleTopAsinIncrease[count].model_no).substring(0, 5) + "</td>";
        html += "<td style='padding: 7px; white-space: nowrap;'>" + saleTopAsinIncrease[count].shipped_cogs + "</td>";
        html += "<td style='padding: 7px; white-space: nowrap;'>" + saleTopAsinIncrease[count].shipped_cogs_prior_period + "</td>";
        html += "<td style='padding: 7px; white-space: nowrap;'>" +
            " <button type='button' id='" + saleTopAsinIncrease[count].asin + "' title='Show Rules' value='newSaleTopAsinIncrease' class='threshold-value btn btn-sm waves-effect waves-light'><i class='feather icon-eye text-info font-medium-5'></i></button>" +
            " <button type='button' id='" + saleTopAsinIncrease[count].asin + "' title='Set Rule' value='newSaleTopAsinIncrease' class='threshold btn-icon btn btn-sm waves-effect waves-light'><i class='feather icon-bell text-info font-medium-5'></i></button>" +
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
            "    <td style='padding: 14px; white-space: nowrap;' align='center' colspan='7'>No data found</td>\n" +
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
        html += "<td style='padding: 14px; white-space: nowrap;'>" + saleTopAsinShippedCogs[count].asin + "</td>";
        html += "<td title='" + (saleTopAsinShippedCogs[count].product_title).replace(new RegExp("\\'", "gm"), "`") + "' style='padding: 14px; white-space: nowrap;' >" + (saleTopAsinShippedCogs[count].product_title).substring(0, 10) + "...</td>";
        html += "<td title='" + (saleTopAsinShippedCogs[count].model_no).replace(new RegExp("\\'", "gm"), "`") + "' style='padding: 14px; white-space: nowrap;'>" + (saleTopAsinShippedCogs[count].model_no).substring(0, 5) + "</td>";
        html += "<td style='padding: 14px; white-space: nowrap;'>" + saleTopAsinShippedCogs[count].shipped_cogs + "</td>";
        html += "<td style='padding: 14px; white-space: nowrap;'>" + saleTopAsinShippedCogs[count].shipped_cogs_percentage_of_total + "</td>";
        html += "<td style='padding: 14px; white-space: nowrap;'>" +
            " <button type='button' id='" + saleTopAsinShippedCogs[count].asin + "' title='Show Rules' value='newSaleTopAsinShippedCogs' class='threshold-value btn btn-sm waves-effect waves-light'><i class='feather icon-eye text-info font-medium-5'></i></button>" +
            " <button type='button' id='" + saleTopAsinShippedCogs[count].asin + "' title='Set Rule' value='newSaleTopAsinShippedCogs' class='threshold btn-icon btn btn-sm waves-effect waves-light'><i class='feather icon-bell text-info font-medium-5'></i></button>" +
            "</td>";
        html += "</tr>";
    }
    html += "</tbody></table>";
    $('#top_asin_shipped_cogs').html(html);
}
