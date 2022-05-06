$("#filter_range_picker").datetimepicker({
    format: 'MM/DD/YYYY',
    minDate: '09/09/1999',
    maxDate: new Date(),
});

$('#custom_data_value').daterangepicker({
    "minDate": '09/09/1999',
    "maxDate": new Date()
});
$('#sales_filter_vendor').val(1).trigger('change.select2');
$("#sales_filter_vendor").select2({
    dropdownParent: $("#filter_form"),
    language: {
        noResults: function (e) {
            return "No vendor found";
        },
    }
});

$("#sales_filter_range").select2({
    dropdownParent: $("#filter_form"),
    language: {
        noResults: function (e) {
            return "No reporting range found";
        },
    }
});

$("#filter_date_range").val($("#custom_data_value").val());

$('#custom_data_value').on('apply.daterangepicker', function (ev, picker) {
    $("#filter_date_range").val($("#custom_data_value").val());
});
var value = ($("#filter_date_range").val()).split(" - ");
var start_date_text = value[0];
var end_date_text = value[1];
var date_filter_range_value = moment(start_date_text, "MM/DD/YYYY").format("MMM DD, YYYY") + " - " + moment(end_date_text, "MM/DD/YYYY").format("MMM DD, YYYY");
$("#selected_date_text").html(date_filter_range_value);

var value = ($("#filter_date_range").val()).split(" - ");
var start_date_text = value[0];
var end_date_text = value[1];
var date_filter_range_value = moment(start_date_text, "MM/DD/YYYY").format("MMM DD, YYYY") + " - " + moment(end_date_text, "MM/DD/YYYY").format("MMM DD, YYYY");
$("#selected_date_text").html(date_filter_range_value);

document.addEventListener('DOMContentLoaded', function (e) {
    //on Submitting form call SP with Filter Values
    $('#filter_form').on('submit', function (event) {
        event.preventDefault();

        var type = $('#sales_filter_range').val();
        $("#filter_date_range").val($("#custom_data_value").val());
        var value = ($("#filter_date_range").val()).split(" - ");
        var start_date_text = value[0];
        var end_date_text = value[1];
        var date_filter_range_value = moment(start_date_text, "MM/DD/YYYY").format("MMM DD, YYYY") + " - " + moment(end_date_text, "MM/DD/YYYY").format("MMM DD, YYYY");
        $("#selected_date_text").html(date_filter_range_value);
        var filter_vendor = $('#sales_filter_vendor').val();
        var filter_range = $('#sales_filter_range').val();
        var filter_date_range = $('#filter_date_range').val();

        $('#filter_vendor').val(filter_vendor);
        $('#filter_range').val(filter_range);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/businessView/visual/dateCheck",
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
                    var filter_vendor = $('#sales_filter_vendor').val();
                    var filter_range = $('#sales_filter_range').val();
                    var filter_date_range = $('#filter_date_range').val();

                    $('#filter_vendor').val(filter_vendor);
                    $('#filter_range').val(filter_range);

                    getBusinessViewKPI(filter_vendor, filter_range, filter_date_range);
                    getTotalAdSalesByType(filter_vendor, filter_range, filter_date_range);
                    getLineGraphByType(filter_vendor, filter_range, filter_date_range);
                    getCampaignSpendByType(filter_vendor, filter_range, filter_date_range);
                    getSearchTermSPData(filter_vendor, filter_range, filter_date_range);
                    getOrderedSalesSpendData(filter_vendor, filter_range, filter_date_range);
                    getPortfolioKpi(filter_vendor, filter_range, filter_date_range);
                    getTopASINData(filter_vendor, filter_range, filter_date_range);
                }
            },
        });

    });

    $('#custom_data_value').on('change', function (e) {
        var type = $('#sales_filter_range').val();
        document.getElementById('custom_data_value').type = 'text';
        document.getElementById('filter_range_picker').type = 'hidden';

        $("#filter_date_range").val($("#custom_data_value").val());
        var filter_vendor = $('#sales_filter_vendor').val();
        var filter_range = $('#sales_filter_range').val();
        var filter_date_range = $('#filter_date_range').val();

        $('#filter_vendor').val(filter_vendor);
        $('#filter_range').val(filter_range);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/businessView/visual/dateCheck",
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
                }
            },
        });
    });

    $('#exportSpTerm').on('click', function () {
        var filter_vendor = $('#sales_filter_vendor').val();
        var filter_range = $('#sales_filter_range').val();
        var filter_date_range = $('#filter_date_range').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/businessView/visual/exportSearchTermSPData",
            type: "GET",
            data: {
                vendor: filter_vendor,
                range: filter_range,
                date_range: filter_date_range,
            },
            cache: false,
            xhrFields: {
                responseType: 'blob'
            },
            success: function (response, status, xhr) {
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
                    var filename = "";
                    var disposition = xhr.getResponseHeader('Content-Disposition');
                    if (disposition && disposition.indexOf('attachment') !== -1) {
                        var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        var matches = filenameRegex.exec(disposition);
                        if (matches != null && matches[1]) {
                            filename = matches[1].replace(/['"]/g, '');
                        }
                    }

                    let blob = new Blob([response], { type: "application/vnd.ms-excel" });
                    let link = URL.createObjectURL(blob);
                    let a = document.createElement("a");
                    a.download = filename;
                    a.href = link;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                let text = "";
                if (errorThrown == "Not Found") {
                    text = "There is no data to export";
                } else {
                    text = "Your selected date is not valid";
                }
                Swal.fire({
                    title: "Error",
                    text: text,
                    allowOutsideClick: false,
                    type: "info",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            }
        });
    });
    $('#exportPerformance').on('click', function () {
        var filter_vendor = $('#sales_filter_vendor').val();
        var filter_range = $('#sales_filter_range').val();
        var filter_date_range = $('#filter_date_range').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/businessView/visual/exportPerformanceOverTimeData",
            type: "GET",
            data: {
                vendor: filter_vendor,
                range: filter_range,
                date_range: filter_date_range,
            },
            cache: false,
            xhrFields: {
                responseType: 'blob'
            },
            success: function (response, status, xhr) {
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
                    var filename = "";
                    var disposition = xhr.getResponseHeader('Content-Disposition');
                    if (disposition && disposition.indexOf('attachment') !== -1) {
                        var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        var matches = filenameRegex.exec(disposition);
                        if (matches != null && matches[1]) {
                            filename = matches[1].replace(/['"]/g, '');
                        }
                    }

                    let blob = new Blob([response], { type: "application/vnd.ms-excel" });
                    let link = URL.createObjectURL(blob);
                    let a = document.createElement("a");
                    a.download = filename;
                    a.href = link;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                let text = "";
                if (errorThrown == "Not Found") {
                    text = "There is no data to export";
                } else {
                    text = "Your selected date is not valid";
                }
                Swal.fire({
                    title: "Error",
                    text: text,
                    allowOutsideClick: false,
                    type: "info",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            }
        });
    });
    $('#exportPortfolio').on('click', function () {
        var filter_vendor = $('#sales_filter_vendor').val();
        var filter_range = $('#sales_filter_range').val();
        var filter_date_range = $('#filter_date_range').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/businessView/visual/exportPortfolioData",
            type: "GET",
            data: {
                vendor: filter_vendor,
                range: filter_range,
                date_range: filter_date_range,
            },
            cache: false,
            xhrFields: {
                responseType: 'blob'
            },
            success: function (response, status, xhr) {
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
                    var filename = "";
                    var disposition = xhr.getResponseHeader('Content-Disposition');
                    if (disposition && disposition.indexOf('attachment') !== -1) {
                        var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        var matches = filenameRegex.exec(disposition);
                        if (matches != null && matches[1]) {
                            filename = matches[1].replace(/['"]/g, '');
                        }
                    }

                    let blob = new Blob([response], { type: "application/vnd.ms-excel" });
                    let link = URL.createObjectURL(blob);
                    let a = document.createElement("a");
                    a.download = filename;
                    a.href = link;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                let text = "";
                if (errorThrown == "Not Found") {
                    text = "There is no data to export";
                } else {
                    text = "Your selected date is not valid";
                }
                Swal.fire({
                    title: "Error",
                    text: text,
                    allowOutsideClick: false,
                    type: "info",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            }
        });
    });
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
} // end if

//on loading call SP with all Vendor (filter_vendor = 0 )
var vendor_first = $("#sales_filter_vendor").val();
if (vendor_first < 1) {
    vendor_first = 0;
}
$("#filter_date_range").val($("#custom_data_value").val());
getBusinessViewKPI(vendor_first, '0', $("#filter_date_range").val());
getTotalAdSalesByType(vendor_first, '0', $("#filter_date_range").val());
getLineGraphByType(vendor_first, '0', $("#filter_date_range").val());
getCampaignSpendByType(vendor_first, '0', $("#filter_date_range").val());
getSearchTermSPData(vendor_first, '0', $("#filter_date_range").val());
getOrderedSalesSpendData(vendor_first, '0', $("#filter_date_range").val());
getPortfolioKpi(vendor_first, '0', $("#filter_date_range").val());
getTopASINData(vendor_first, '0', $("#filter_date_range").val());

var total_ad_sale_sp_value = 0;
var total_ad_sale_sb_value = 0;
var total_ad_sale_sbv_value = 0;
var total_ad_sale_sd_value = 0;
var ad_sales_graph_tooltip = [];
var totalAdSalesByTypeGraph = c3.generate({
    bindto: d3.select('#total_ad_sales_by_type_graph'),
    data: {
        columns: [
            ['SP', total_ad_sale_sp_value],
            ['SBV', total_ad_sale_sbv_value],
            ['SB', total_ad_sale_sb_value],
            ['SD', total_ad_sale_sd_value],
        ],
        type: 'donut',
    },
    donut: {
        width: 50,
    },
    size: {
        height: 360,
    },
    color: {
        pattern: ['#4DD0EA', '#2693BE', '#FFB371', '#9DE5E6']
    },
    transition: {
        duration: 100
    },
    legend: {
        show: true,
        position: 'inset',
        inset: {
            anchor: 'top-right',
            x: undefined,
            y: -80,
            step: undefined
        }
    },
    padding: {
        top: 80
    },
    tooltip: {
        format: {
            value: function (value, ratio, id, index) {
                return '$' + ad_sales_graph_tooltip[index];
            },
        }
    }
});

var total_campaign_spend_sp_value = 0;
var total_campaign_spend_sb_value = 0;
var total_campaign_spend_sbv_value = 0;
var total_campaign_spend_sd_value = 0;
var total_campaign_graph_tooltip = [];
var totalCampaignSpendByTypeGraph = c3.generate({
    bindto: d3.select('#total_campaign_spend_by_type_graph'),
    data: {
        columns: [
            ['SP', total_campaign_spend_sp_value],
            ['SBV', total_campaign_spend_sbv_value],
            ['SB', total_campaign_spend_sb_value],
            ['SD', total_campaign_spend_sd_value],
        ],
        type: 'donut',
    },
    donut: {
        width: 50,
    },
    size: {
        height: 360,
    },
    color: {
        pattern: ['#4DD0EA', '#2693BE', '#FFB371', '#9DE5E6']
    },
    transition: {
        duration: 100
    },
    legend: {
        show: true,
        position: 'inset',
        inset: {
            anchor: 'top-right',
            x: undefined,
            y: -80,
            step: undefined
        }
    },
    padding: {
        top: 80
    },
    tooltip: {
        format: {
            value: function (value, ratio, id, index) {
                return '$' + total_campaign_graph_tooltip[index];
            },
        }
    }
});
//on loading call Generate Charts Structure for campaign spend and sales mychart
var adSales_value = [];
var adSpends_value = [];
var roas_value = [];
var orderedRevenue_value = [];
var glanceView_value = [];
var clicks_value = [];
var impressions_value = [];
var orders_value = [];
var conversionRate_value = [];
var label_value = [];
var business_report_graph_tooltip = [];
var business_report_graph_dollar = c3.generate({
    bindto: d3.select('.business_report_graph_dollar'),
    size: {
        height: 330,
    },
    data: {
        x: 'x',
        json: {
            'x': label_value,
            'Ad Sales': adSales_value,
            'Ad Spends': adSpends_value,
            'ROAS': roas_value,
            'Ordered Revenue': orderedRevenue_value,
            'Glance View': glanceView_value,
            'Clicks': clicks_value,
            'Impressions': impressions_value,
            'Orders': orders_value,
            'Conversion Rate': [],
        },
        axes: {
            'Ad Sales': 'y',
            'Ad Spends': 'y',
            'ROAS': 'y',
            'Ordered Revenue': 'y',
            'Glance View': 'y2',
            'Clicks': 'y2',
            'Impressions': 'y2',
            'Orders': 'y2',
            'Conversion Rate': 'y3',
        },
        types: {
            'Ad Sales': 'line',
            'Ad Spends': 'area',
            'ROAS': 'spline',
            'Ordered Revenue': 'area-spline',
            'Glance View': 'line',
            'Clicks': 'area',
            'Impressions': 'spline',
            'Orders': 'area-spline',
            'Conversion Rate': 'area',
        },
        colors: {
            'Ad Sales': '#4DD0EA',
            'Ad Spends': '#FFB371',
            'ROAS': '#9DE5E6',
            'Ordered Revenue': '#2693BE',
            'Glance View': '#FFD2A0',
            'Clicks': '#90B9D5',
            'Impressions': '#30DDFF',
            'Orders': '#FC943D',
            'Conversion Rate': '#343438',
        },
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
            label: {
                text: 'Ad Sales, Ad Spends, ROAS, Ordered Revenue',
                position: 'outer-middle'
            },
            tick: {
                format: d3.format('$,.2f')
            }
        },
        y2: {
            show: true,
            label: {
                text: 'Impressions, Clicks, Orders, Glance View',
                position: 'outer-middle'
            },
            tick: {
                format: d3.format(',.0f')
            }
        },
        y3: {
            show: false,
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
                return business_report_graph_tooltip[d];
            },
        }
    }
});
function getBusinessViewKPI(filter_vendor, filter_range, filter_date_range) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/businessView/visual/kpi",
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
                //shipped COGS Gauge and trailing
                if (typeof (response.businessReviewKPI[0]) !== 'undefined') {
                    changeKPIValues(response.businessReviewKPI[0]);
                } else {
                    $('#kpi_spend').html('-');
                    $('#kpi_ad_sale').html('-');
                    $('#kpi_click').html('-');
                    $('#kpi_impression').html('-');
                    $('#kpi_roas').html('-');
                    $('#kpi_order').html('-');
                    $('#kpi_conversion_rate').html('-');
                    $('#kpi_acos').html('-');
                    $('#kpi_ordered_revenue').html('-');
                    $('#kpi_program_value').html('-');

                    $('#kpi_spend_percentage').html('-');
                    $('#kpi_ad_sale_percentage').html('-');
                    $('#kpi_click_percentage').html('-');
                    $('#kpi_impression_percentage').html('-');
                    $('#kpi_roas_percentage').html('-');
                    $('#kpi_order_percentage').html('-');
                    $('#kpi_conversion_rate_percentage').html('-');
                    $('#kpi_acos_percentage').html('-');
                    $('#kpi_ordered_revenue_percentage').html('-');
                    $('#kpi_program_value_percentage').html('-');
                }
            }
        },
    });
} // end function
function getTotalAdSalesByType(filter_vendor, filter_range, filter_date_range) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/businessView/visual/totalAdSales",
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
                if (typeof (response.totalAdSalesByType[0]) !== 'undefined') {
                    changeTotalAdSalesByTypeValues(response.totalAdSalesByType);
                } else {
                    total_ad_sale_sp_value = 0;
                    total_ad_sale_sb_value = 0;
                    total_ad_sale_sbv_value = 0;
                    total_ad_sale_sd_value = 0;
                }
            } // end else
        },
    });
} // end function
function getLineGraphByType(filter_vendor, filter_range, filter_date_range) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/businessView/visual/lineGraphByType",
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
                if (typeof (response.LineGraphByType[0]) !== 'undefined') {
                    changeLineGraphByTypeValues(response.LineGraphByType);
                } else {
                    adSales = 0;
                    adSpends = 0;
                    roas = 0;
                    orderedRevenue = 0;
                    glanceView = 0;
                    clicks = 0;
                    impressions = 0;
                    orders = 0;
                    conversionRate = 0;
                }
            } // end else
        },
    });
} // end function
function getCampaignSpendByType(filter_vendor, filter_range, filter_date_range) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/businessView/visual/campaignSpend",
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
                if (typeof (response.campaignSpendByType[0]) !== 'undefined') {
                    changeCampaignSpendByTypeValues(response.campaignSpendByType);
                } else {
                    total_campaign_spend_sp_value = 0;
                    total_campaign_spend_sb_value = 0;
                    total_campaign_spend_sbv_value = 0;
                    total_campaign_spend_sd_value = 0;
                }
            }
        },
    });
} // end function
function getSearchTermSPData(filter_vendor, filter_range, filter_date_range) {
    $('#search_team_sp_table').DataTable({
        processing: true,
        serverSide: true,
        paging: true,
        destroy: true,
        searching: true,
        stateSave: true,
        ajax: {
            url: base_url + "/businessView/visual/getSearchTermSPData",
            type: "GET",
            data: {
                vendor: filter_vendor,
                range: filter_range,
                date_range: filter_date_range,
            },
            cache: false,
        },
        language: { "emptyTable": "No data found" },
        columns: [
            { data: 'row', name: 'row' },
            { data: 'keyword_text', name: 'keyword_text' },
            { data: 'spend', name: 'spend' },
            { data: 'ad_sales', name: 'ad_sales' },
            { data: 'impressions', name: 'impressions' },
            { data: 'clicks', name: 'clicks' },
            { data: 'CPC', name: 'CPC' },
            { data: 'CTR', name: 'CTR' },
            { data: 'orders', name: 'orders' },
            { data: 'ROAS', name: 'ROAS' },
            { data: 'conversion_rate', name: 'conversion_rate' },
        ],
        order: [[0, 'asc']],
    }).columns.adjust().draw();
} // end function
function getOrderedSalesSpendData(filter_vendor, filter_range, filter_date_range) {
    $('#order_revenue_ad_sales_spend_table').DataTable({
        processing: true,
        serverSide: true,
        paging: true,
        destroy: true,
        searching: false,
        stateSave: true,
        info: false,
        ajax: {
            url: base_url + "/businessView/visual/getOrderedSalesSpendData",
            type: "GET",
            data: {
                vendor: filter_vendor,
                range: filter_range,
                date_range: filter_date_range,
            },
            cache: false,
        },
        language: { "emptyTable": "No data found" },
        columns: [
            { data: 'date', name: 'date' },
            { data: 'spend', name: 'spend' },
            { data: 'pre_spend', name: 'pre_spend' },
            { data: 'ad_sales', name: 'ad_sales' },
            { data: 'pre_ad_sales', name: 'pre_ad_sales' },
            { data: 'order_revenue', name: 'order_revenue' },
            { data: 'glance_views', name: 'glance_views' },
            { data: 'impressions', name: 'impressions' },
            { data: 'clicks', name: 'clicks' },
            { data: 'ROAS', name: 'ROAS' },
            { data: 'orders', name: 'orders' },
            { data: 'conversion_rate', name: 'conversion_rate' },
        ],
        order: [[0, 'asc']],
    }).columns.adjust().draw();
}
function getPortfolioKpi(filter_vendor, filter_range, filter_date_range) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/businessView/visual/getPortfolioData",
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
                //Portfolio Kpi data
                generatePortfolioKpi(response.portfolioKpi);
            }
        },
    });
} // end function
function getTopASINData(filter_vendor, filter_range, filter_date_range) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/businessView/visual/getTopASINData",
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
                //TOP ASIN Sales
                generateSaleTopAsinShippedCogs(response.saleTopAsinSales);
                //TOP ASIN Decrease
                generateSaleTopAsinDecrease(response.saleTopAsinDecrease);
                //TOP ASIN Increase
                generateSaleTopAsinIncrease(response.saleTopAsinIncrease);
            }
        },
    });
} // end function
function changeKPIValues(changeKPIValues) {
    $('#kpi_spend').html(changeKPIValues.spend);
    $('#kpi_ad_sale').html(changeKPIValues.ad_sales);
    $('#kpi_click').html(changeKPIValues.clicks);
    $('#kpi_impression').html(changeKPIValues.impressions);
    $('#kpi_roas').html(changeKPIValues.ROAS);
    $('#kpi_order').html(changeKPIValues.orders);
    $('#kpi_conversion_rate').html(changeKPIValues.conversion_rate);
    $('#kpi_acos').html(changeKPIValues.ACOS);
    $('#kpi_ordered_revenue').html(changeKPIValues.order_revenue);
    $('#kpi_program_value').html(changeKPIValues.program_value);
    if (changeKPIValues.spend == null) {
        $('#kpi_spend').html('-');
    }
    if (changeKPIValues.ad_sales == null) {
        $('#kpi_ad_sale').html('-');
    }
    if (changeKPIValues.clicks == null) {
        $('#kpi_click').html('-');
    }
    if (changeKPIValues.impressions == null) {
        $('#kpi_impression').html('-');
    }
    if (changeKPIValues.ROAS == null) {
        $('#kpi_roas').html('-');
    }
    if (changeKPIValues.orders == null) {
        $('#kpi_order').html('-');
    }
    if (changeKPIValues.conversion_rate == null) {
        $('#kpi_conversion_rate').html('-');
    }
    if (changeKPIValues.ACOS == null) {
        $('#kpi_acos').html('-');
    }
    if (changeKPIValues.order_revenue == null) {
        $('#kpi_ordered_revenue').html('-');
    }
    if (changeKPIValues.program_value == null) {
        $('#kpi_program_value').html('-');
    }
    if (changeKPIValues.pre_spend == null) {
        changeKPIValues.pre_spend = 0;
    }
    if (changeKPIValues.pre_ad_sales == null) {
        changeKPIValues.pre_ad_sales = 0;
    }
    if (changeKPIValues.pre_clicks == null) {
        changeKPIValues.pre_clicks = 0;
    }
    if (changeKPIValues.pre_impressions == null) {
        changeKPIValues.pre_impressions = 0;
    }
    if (changeKPIValues.pre_ROAS == null) {
        changeKPIValues.pre_ROAS = 0;
    }
    if (changeKPIValues.pre_orders == null) {
        changeKPIValues.pre_orders = 0;
    }
    if (changeKPIValues.pre_conversion_rate == null) {
        changeKPIValues.pre_conversion_rate = 0;
    }
    if (changeKPIValues.pre_ACOS == null) {
        changeKPIValues.pre_ACOS = 0;
    }
    if (changeKPIValues.pre_order_revenue == null) {
        changeKPIValues.pre_order_revenue = 0;
    }
    if (changeKPIValues.pre_program_value == null) {
        changeKPIValues.pre_program_value = 0;
    }
    let spend_progress_label = getConditionalClassStyle(changeKPIValues.pre_spend);
    let ad_sale_progress_label = getConditionalClassStyle(changeKPIValues.pre_ad_sales);
    let click_progress_label = getConditionalClassStyle(changeKPIValues.pre_clicks);
    let impression_progress_label = getConditionalClassStyle(changeKPIValues.pre_impressions);
    let roas_progress_label = getConditionalClassStyle(changeKPIValues.pre_ROAS);
    let order_progress_label = getConditionalClassStyle(changeKPIValues.pre_orders);
    let conversion_rate_progress_label = getConditionalClassStyle(changeKPIValues.pre_conversion_rate);
    let acos_progress_label = getConditionalClassStyle(changeKPIValues.pre_ACOS);
    let ordered_revenue_progress_label = getConditionalClassStyle(changeKPIValues.pre_order_revenue);
    let program_value_progress_label = getConditionalClassStyle(changeKPIValues.pre_program_value);

    $('#kpi_spend_percentage').html(spend_progress_label + " " + changeKPIValues.pre_spend);
    $('#kpi_ad_sale_percentage').html(ad_sale_progress_label + " " + changeKPIValues.pre_ad_sales);
    $('#kpi_click_percentage').html(click_progress_label + " " + changeKPIValues.pre_clicks);
    $('#kpi_impression_percentage').html(impression_progress_label + " " + changeKPIValues.pre_impressions);
    $('#kpi_roas_percentage').html(roas_progress_label + " " + changeKPIValues.pre_ROAS);
    $('#kpi_order_percentage').html(order_progress_label + " " + changeKPIValues.pre_orders);
    $('#kpi_conversion_rate_percentage').html(conversion_rate_progress_label + " " + changeKPIValues.pre_conversion_rate);
    $('#kpi_acos_percentage').html(acos_progress_label + " " + changeKPIValues.pre_ACOS);
    $('#kpi_ordered_revenue_percentage').html(ordered_revenue_progress_label + " " + changeKPIValues.pre_order_revenue);
    $('#kpi_program_value_percentage').html(program_value_progress_label + " " + changeKPIValues.pre_program_value);
} // end function
function changeTotalAdSalesByTypeValues(percentage) {
    ad_sales_graph_tooltip = [];
    for (var i = 0; i < percentage.length; i++) {
        if (percentage[i].campaign_type == 'sp') {
            total_ad_sale_sp_value = percentage[i].sales;
            ad_sales_graph_tooltip[0] = percentage[i].campaign_sales;
        }
        if (percentage[i].campaign_type == 'sbv') {
            total_ad_sale_sbv_value = percentage[i].sales;
            ad_sales_graph_tooltip[1] = percentage[i].campaign_sales;
        }
        if (percentage[i].campaign_type == 'sb') {
            total_ad_sale_sb_value = percentage[i].sales;
            if (percentage[i].campaign_sales !== 'undefined')
                ad_sales_graph_tooltip[2] = percentage[i].campaign_sales;
        }
        if (percentage[i].campaign_type == 'sd') {
            total_ad_sale_sd_value = percentage[i].sales;
            ad_sales_graph_tooltip[3] = percentage[i].campaign_sales;
        }
    }
    setTimeout(function () {
        totalAdSalesByTypeGraph.load({
            json: {
                'SP': total_ad_sale_sp_value,
                'SB': total_ad_sale_sb_value,
                'SBV': total_ad_sale_sbv_value,
                'SD': total_ad_sale_sd_value,
            },
        });
    }, 1000);
} // end function
function changeLineGraphByTypeValues(LineGraphByType) {
    adSales_value = [];
    adSpends_value = [];
    roas_value = [];
    orderedRevenue_value = [];
    glanceView_value = [];
    clicks_value = [];
    impressions_value = [];
    orders_value = [];
    conversionRate_value = [];
    label_value = [];

    for (var count = 0; count < LineGraphByType.length; count++) {
        adSales_value[count] = LineGraphByType[count].ad_sales;
        adSpends_value[count] = LineGraphByType[count].spend;
        roas_value[count] = LineGraphByType[count].ROAS;
        orderedRevenue_value[count] = LineGraphByType[count].order_revenue;
        glanceView_value[count] = LineGraphByType[count].glance_views;
        clicks_value[count] = LineGraphByType[count].clicks;
        impressions_value[count] = LineGraphByType[count].impressions;
        orders_value[count] = LineGraphByType[count].orders;
        conversionRate_value[count] = LineGraphByType[count].conversion_rate;
        label_value[count] = LineGraphByType[count].date;
        business_report_graph_tooltip[count] = LineGraphByType[count].tooltip;
    }

    $('#adSales').prop("checked", true);
    $('#adSpend').prop("checked", true);
    $('#roas').prop("checked", true);
    $('#orderedRevenue').prop("checked", true);

    $('#glanceView').prop("checked", true);
    $('#click').prop("checked", true);
    $('#impression').prop("checked", true);
    $('#order').prop("checked", true);

    $('#conversionRtae').prop("checked", false);

    business_report_graph_dollar = c3.generate({
        bindto: d3.select('.business_report_graph_dollar'),
        size: {
            height: 330,
        },
        data: {
            x: 'x',
            json: {
                'x': label_value,
                'Ad Sales': adSales_value,
                'Ad Spends': adSpends_value,
                'ROAS': roas_value,
                'Ordered Revenue': orderedRevenue_value,
                'Glance View': glanceView_value,
                'Clicks': clicks_value,
                'Impressions': impressions_value,
                'Orders': orders_value,
                'Conversion Rate': [],
            },
            axes: {
                'Ad Sales': 'y',
                'Ad Spends': 'y',
                'ROAS': 'y',
                'Ordered Revenue': 'y',
                'Glance View': 'y2',
                'Clicks': 'y2',
                'Impressions': 'y2',
                'Orders': 'y2',
                'Conversion Rate': 'y3',
            },
            types: {
                'Ad Sales': 'line',
                'Ad Spends': 'area',
                'ROAS': 'spline',
                'Ordered Revenue': 'area-spline',
                'Glance View': 'line',
                'Clicks': 'area',
                'Impressions': 'spline',
                'Orders': 'area-spline',
                'Conversion Rate': 'area',
            },
            colors: {
                'Ad Sales': '#4DD0EA',
                'Ad Spends': '#FFB371',
                'ROAS': '#9DE5E6',
                'Ordered Revenue': '#2693BE',
                'Glance View': '#FFD2A0',
                'Clicks': '#90B9D5',
                'Impressions': '#30DDFF',
                'Orders': '#FC943D',
                'Conversion Rate': '#343438',
            },
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
                label: {
                    text: 'Ad Sales, Ad Spends, ROAS, Ordered Revenue',
                    position: 'outer-middle'
                },
                tick: {
                    format: d3.format('$,.2f')
                }
            },
            y2: {
                show: true,
                label: {
                    text: 'Impressions, Clicks, Orders, Glance View',
                    position: 'outer-middle'
                },
                tick: {
                    format: d3.format(',.0f')
                }
            },
            y3: {
                show: false,
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
                    return business_report_graph_tooltip[d];
                },
            }
        }
    });
} // end function
function changeCampaignSpendByTypeValues(percentage) {
    total_campaign_graph_tooltip = [];
    for (var i = 0; i < percentage.length; i++) {
        if (percentage[i].campaign_type == 'sp') {
            total_campaign_spend_sp_value = percentage[i].sales;
            total_campaign_graph_tooltip[0] = percentage[i].campaign_cost;
        }
        if (percentage[i].campaign_type == 'sbv') {
            total_campaign_spend_sbv_value = percentage[i].sales;
            total_campaign_graph_tooltip[1] = percentage[i].campaign_cost;
        }
        if (percentage[i].campaign_type == 'sb') {
            total_campaign_spend_sb_value = percentage[i].sales;
            total_campaign_graph_tooltip[2] = percentage[i].campaign_cost;
        }
        if (percentage[i].campaign_type == 'sd') {
            total_campaign_spend_sd_value = percentage[i].sales;
            total_campaign_graph_tooltip[3] = percentage[i].campaign_cost;
        }
    }

    setTimeout(function () {
        totalCampaignSpendByTypeGraph.load({
            json: {
                'SP': total_campaign_spend_sp_value,
                'SBV': total_campaign_spend_sbv_value,
                'SB': total_campaign_spend_sb_value,
                'SD': total_campaign_spend_sd_value,
            },
        });
    }, 1000);
} // end function
// portfolio kpi's table
function generatePortfolioKpi(portfolioKpi) {
    var html = "";
    html += "<table class='table tablePortfolio table-striped mb-0' id='portfolio_Table' style='text-align: center;'>" +
        "<thead class='thead'>" +
        "<tr class='tr'>" +
        "<th style='white-space: nowrap; font-size: small;'>Portfolio<i class='fa fa-fw fa-sort' onclick='sortTable1(0)' ></i> </th>" +
        "<th style='white-space: nowrap; font-size: small;' title='Spend'>Spend<i class='fa fa-fw fa-sort'  onclick='sortTable1(1)' ></i></th>" +
        "<th style='white-space: nowrap; font-size: small;' title='Spend (%)'>Spend (%)<i class='fa fa-fw fa-sort'  onclick='sortTable1(2)' ></i></th>" +
        "<th style='white-space: nowrap; font-size: small;' title='AD SALES'>ad sales<i class='fa fa-fw fa-sort'  onclick='sortTable1(3)' ></i></th>" +
        "<th style='white-space: nowrap; font-size: small;' title=' Ad sales %'> Ad sales %<i class='fa fa-fw fa-sort'  onclick='sortTable1(4)' ></i></th>" +
        "<th style='white-space: nowrap; font-size: small;' title='impressions'>impressions<i class='fa fa-fw fa-sort'  onclick='sortTable1(5)' ></i></th>" +
        "<th style='white-space: nowrap; font-size: small;' title='Clicks'>clicks<i class='fa fa-fw fa-sort'  onclick='sortTable1(6)' ></i></th>" +
        "<th style='white-space: nowrap; font-size: small;' title='CPC'>cpc<i class='fa fa-fw fa-sort'  onclick='sortTable1(7)' ></i></th>" +
        "<th style='white-space: nowrap; font-size: small;' title='Orders'>Orders<i class='fa fa-fw fa-sort'  onclick='sortTable1(8)' ></i></th>" +
        "<th style='white-space: nowrap; font-size: small;' title='ROAS'>roas<i class='fa fa-fw fa-sort'  onclick='sortTable1(9)' ></i></th>" +
        "<th style='white-space: nowrap; font-size: small;' title='Conversion Rate'>conversion rate<i class='fa fa-fw fa-sort'  onclick='sortTable1(10)' ></i></th>" +
        "</tr>" +
        "</thead>" +
        "<tbody>";
    if (portfolioKpi.length == 0) {
        html += "<tr>\n" +
            "    <td style='padding: 14px; white-space: nowrap;' align='center' colspan='11'>No data found</td>\n" +
            "</tr>";
    }
    //else {// end if
    var background_spend = "";
    var background_sales = "";
    var background_impressions = "";
    var background_clicks = "";
    var cost_sum = 0;
    var percentage_spend_sum = 0;
    var ad_sales_sum = 0;
    var per_ad_sales = 0;
    var impressions_sum = 0;
    var clicks_sum = 0;
    var cpc_sum = 0;
    var orders_sum = 0;
    var roas_sum = 0;
    var conversion_rate_sum = 0;
    let get_spend = [];
    let get_sales = [];
    let get_impressions = [];
    let get_clicks = [];
    var max_spend = 0;
    var max_sales = 0;
    var max_impressions = 0;
    var max_clicks = 0;
    var col_percent_spend = 0;
    var col_percent_spend_left = 0;
    var col_percent_sales = 0;
    var col_percent_sales_left = 0;
    var col_percent_impressions = 0;
    var col_percent_impressions_left = 0;
    var col_percent_clicks = 0;
    var col_percent_clicks_left = 0;
    var cpc_foot = 0;
    var roas_foot = 0
    var conversion_rate_foot = 0;
    for (var count = 0; count < portfolioKpi.length; count++) {
        get_spend[count] = Number(portfolioKpi[count].cost);
        get_sales[count] = Number(portfolioKpi[count].campaign_sales);
        get_impressions[count] = Number(portfolioKpi[count].impressions);
        get_clicks[count] = Number(portfolioKpi[count].clicks);
    }
    max_spend = Math.max.apply(null, get_spend);
    max_sales = Math.max.apply(null, get_sales);
    max_impressions = Math.max.apply(null, get_impressions);
    max_clicks = Math.max.apply(null, get_clicks);
    for (var count = 0; count < portfolioKpi.length; count++) {
        // column color percentage calculation for Spend
        col_percent_spend = Math.ceil(portfolioKpi[count].cost / max_spend * 100);
        col_percent_spend_left = col_percent_spend - 100;
        if (document.body.classList.contains('dark-layout') == true) {
            background_spend = "background: linear-gradient(to right, #FFB371 " + col_percent_spend + "%, #212744 " + col_percent_spend_left + "%);";
        } else {
            background_spend = "background: linear-gradient(to right, #FFB371 " + col_percent_spend + "%, #f8f8f8 " + col_percent_spend_left + "%);";
        }
        // column color percentage calculation for AD Sales
        col_percent_sales = Math.ceil(portfolioKpi[count].campaign_sales / max_sales * 100);
        col_percent_sales_left = col_percent_sales - 100;
        if (document.body.classList.contains('dark-layout') == true) {
            background_sales = "background: linear-gradient(to right, #4DD0EA " + col_percent_sales + "%, #212744 " + col_percent_sales_left + "%);";
        } else {
            background_sales = "background: linear-gradient(to right, #4DD0EA " + col_percent_sales + "%, #f8f8f8 " + col_percent_sales_left + "%);";
        }
        // column color percentage calculation for impressions
        col_percent_impressions = Math.ceil(portfolioKpi[count].impressions / max_impressions * 100);
        col_percent_impressions_left = col_percent_impressions - 100;
        if (document.body.classList.contains('dark-layout') == true) {
            background_impressions = "background: linear-gradient(to right, #2693BE " + col_percent_impressions + "%, #212744 " + col_percent_impressions_left + "%);";
        } else {
            background_impressions = "background: linear-gradient(to right, #2693BE " + col_percent_impressions + "%, #f8f8f8 " + col_percent_impressions_left + "%);";
        }
        // column color percentage calculation for clicks
        col_percent_clicks = Math.ceil(portfolioKpi[count].clicks / max_clicks * 100);
        col_percent_clicks_left = col_percent_clicks - 100;
        if (document.body.classList.contains('dark-layout') == true) {
            background_clicks = "background: linear-gradient(to right, #FFD2A0 " + col_percent_clicks + "%, #212744 " + col_percent_clicks_left + "%);";
        } else {
            background_clicks = "background: linear-gradient(to right, #FFD2A0 " + col_percent_clicks + "%, #f8f8f8 " + col_percent_clicks_left + "%);";
        }
        // check null values
        if (portfolioKpi[count].portfolios_name == null) {
            portfolioKpi[count].portfolios_name = "-";
        }
        if (portfolioKpi[count].cost) {
            cost_sum = Number(cost_sum) + Number(portfolioKpi[count].cost);
        }
        if (portfolioKpi[count].Percentage_of_spend) {
            percentage_spend_sum = 100;
        }
        if (portfolioKpi[count].campaign_sales) {
            ad_sales_sum = Number(ad_sales_sum) + Number(portfolioKpi[count].campaign_sales);
        }
        if (portfolioKpi[count].Percentage_of_sales) {
            per_ad_sales = 100;
        }
        if (portfolioKpi[count].impressions) {
            impressions_sum = Number(impressions_sum) + Number(portfolioKpi[count].impressions);
        }
        if (portfolioKpi[count].clicks) {
            clicks_sum = Number(clicks_sum) + Number(portfolioKpi[count].clicks);
        }
        if (portfolioKpi[count].CPC) {
            cpc_sum = Number(cpc_sum) + Number(portfolioKpi[count].CPC);
        }
        if (portfolioKpi[count].orders) {
            orders_sum = Number(orders_sum) + Number(portfolioKpi[count].orders);
        }
        if (portfolioKpi[count].ROAS) {
            roas_sum = Number(roas_sum) + Number(portfolioKpi[count].ROAS);
        }
        if (portfolioKpi[count].conversion_rate) {
            conversion_rate_sum = Number(conversion_rate_sum) + Number(portfolioKpi[count].conversion_rate);
        }
        cpc_foot = Number(cost_sum / clicks_sum);
        roas_foot = Number(ad_sales_sum / cost_sum);
        conversion_rate_foot = Number(orders_sum / clicks_sum * 100);
        html += "<tr>";
        html += "<td style='padding: 14px; white-space: nowrap;'>" + portfolioKpi[count].portfolios_name + "</td>";
        html += "<td title='" + portfolioKpi[count].cost + "' style='padding: 14px; white-space: nowrap;" + background_spend + "' >$" + numberWithCommas(Number(portfolioKpi[count].cost).toFixed(2)) + "</td>";
        html += "<td title='" + portfolioKpi[count].Percentage_of_spend + "' style='padding: 14px; white-space: nowrap;'>" + numberWithCommas(Number(portfolioKpi[count].Percentage_of_spend).toFixed(2)) + "%</td>";
        html += "<td style='padding: 14px; white-space: nowrap;" + background_sales + "'>$" + numberWithCommas(Number(portfolioKpi[count].campaign_sales).toFixed(2)) + "</td>";
        html += "<td style='padding: 14px; white-space: nowrap;'>" + numberWithCommas(Number(portfolioKpi[count].Percentage_of_sales).toFixed(2)) + "%</td>";
        html += "<td style='padding: 14px; white-space: nowrap;" + background_impressions + "'>" + numberWithCommas(Number(portfolioKpi[count].impressions)) + "</td>";
        html += "<td title='" + numberWithCommas(Number(portfolioKpi[count].clicks)) + "' style='padding: 14px; white-space: nowrap;" + background_clicks + "' >" + numberWithCommas(Number(portfolioKpi[count].clicks)) + "</td>";
        html += "<td title='" + portfolioKpi[count].CPC + "' style='padding: 14px; white-space: nowrap;'>$" + numberWithCommas(Number(portfolioKpi[count].CPC).toFixed(2)) + "</td>";
        html += "<td style='padding: 14px; white-space: nowrap;'>" + numberWithCommas(Number(portfolioKpi[count].orders)) + "</td>";
        html += "<td style='padding: 14px; white-space: nowrap;'>$" + numberWithCommas(Number(portfolioKpi[count].ROAS).toFixed(2)) + "</td>";
        html += "<td style='padding: 14px; white-space: nowrap;'>" + Number(portfolioKpi[count].conversion_rate).toFixed(2) + "%   </td>";
        html += "</tr>";
    } // end for
    html += "</tbody>";
    html +=
        "<tfoot class='tfoot'>" +
        "<tr>" +
        "<td style='white-space: nowrap; font-size: small;'><b>Total</b></td>" +
        "<td style='white-space: nowrap; font-size: small;' >$" + numberWithCommas(cost_sum.toFixed(2)) + "</td>" +
        "<td style='white-space: nowrap; font-size: small;' >" + percentage_spend_sum + "%</td>" +
        "<td style='white-space: nowrap; font-size: small;'>$" + numberWithCommas(ad_sales_sum.toFixed(2)) + "</td>" +
        "<td style='white-space: nowrap; font-size: small;' >" + per_ad_sales + "%</td>" +
        "<td style='white-space: nowrap; font-size: small;' >" + numberWithCommas(Number(impressions_sum.toFixed(2))) + "</td>" +
        "<td style='white-space: nowrap; font-size: small;'>" + numberWithCommas(Number(clicks_sum.toFixed(2))) + "</td>" +
        "<td style='white-space: nowrap; font-size: small;' >$" + numberWithCommas(cpc_foot.toFixed(2)) + "</td>" +
        "<td style='white-space: nowrap; font-size: small;' >" + numberWithCommas(Number(orders_sum.toFixed(2))) + "</td>" +
        "<td style='white-space: nowrap; font-size: small;' >$" + numberWithCommas(roas_foot.toFixed(2)) + "</td>" +
        "<td style='white-space: nowrap; font-size: small;' >" + conversion_rate_foot.toFixed(2) + "%</td>" +
        "</tr>" +
        "</tfoot>" +
        "</table>";
    // }
    $('#portfolio_kpi').html(html);
} // end portfolio kpi's table function
function generateSaleTopAsinDecrease(saleTopAsinDecrease) {
    var html = "";
    html += "<table class='table table-striped mb-0' id='5_asin_decrease_Table' style='text-align: center;'>" +
        "<thead>" +
        "<tr>" +
        "<th style='white-space: nowrap;'>ASIN<i class='fa fa-fw fa-sort'  onclick='sortTable4(0)'></i></th>" +
        "<th style='white-space: nowrap;'>Product<i class='fa fa-fw fa-sort'  onclick='sortTable4(1)'></i></th>" +
        "<th style='white-space: nowrap;' title='AD SALES'>AD SALES<i class='fa fa-fw fa-sort'  onclick='sortTable4(2)'></i></th>" +
        "<th style='white-space: nowrap;' title='SPEND'>SPEND<i class='fa fa-fw fa-sort'  onclick='sortTable4(3)'></i></th>" +
        "<th style='white-space: nowrap;' title='Impressions'>Impressions<i class='fa fa-fw fa-sort'  onclick='sortTable4(4)'></i></th>" +
        "<th style='white-space: nowrap;' title='Clicks'>Clicks<i class='fa fa-fw fa-sort'  onclick='sortTable4(5)'></i></th>" +
        "<th style='white-space: nowrap;' title='ACOS'>ACOS<i class='fa fa-fw fa-sort'  onclick='sortTable4(6)'></i></th>" +
        "</tr>" +
        "</thead>" +
        "<tbody>";

    if (saleTopAsinDecrease.length == 0) {
        html += "<tr>\n" +
            "    <td style='padding: 7px; white-space: nowrap;' align='center' colspan='7'>No data found</td>\n" +
            "</tr>";
    } // end if
    for (var count = 0; count < saleTopAsinDecrease.length; count++) {

        html += "<tr>";
        html += "<td style='padding: 7px; white-space: nowrap;'>" + saleTopAsinDecrease[count].asin + "</td>";
        if (saleTopAsinDecrease[count].product_title == null) {
            saleTopAsinDecrease[count].product_title = '-';
            html += "<td title='" + saleTopAsinDecrease[count].product_title + "' style='padding: 7px; white-space: nowrap;'>" + (saleTopAsinDecrease[count].product_title).substring(0, 20) + "</td>";
        } else {
            html += "<td title='" + saleTopAsinDecrease[count].product_title.replace(/(['.?*&+^$[\]\\(){}|-])/g, "") + "' style='padding: 7px; white-space: nowrap;'>" + (saleTopAsinDecrease[count].product_title).substring(0, 20) + "...</td>";
        }
        html += "<td style='padding: 7px; white-space: nowrap;'>" + saleTopAsinDecrease[count].adSales + "</td>";
        html += "<td style='padding: 7px; white-space: nowrap;'>" + saleTopAsinDecrease[count].spend + "</td>";
        html += "<td style='padding: 7px; white-space: nowrap;'>" + saleTopAsinDecrease[count].impressions + "</td>";
        html += "<td style='padding: 7px; white-space: nowrap;'>" + saleTopAsinDecrease[count].clicks + "</td>";
        html += "<td style='padding: 7px; white-space: nowrap;'>" + saleTopAsinDecrease[count].ACOS + "</td>";
        html += "</tr>";
    } // end for
    html += "</tbody></table>";
    $('#top_asin_decrease').html(html);
} // end function
function generateSaleTopAsinIncrease(saleTopAsinIncrease) {
    var html = "";
    html += "<table class='table table-striped mb-0' id='5_asin_increase_Table' style='text-align: center;'>" +
        "<thead>" +
        "<tr>" +
        "<th style='white-space: nowrap;'>ASIN<i class='fa fa-fw fa-sort'  onclick='sortTable3(0)'></i></th>" +
        "<th style='white-space: nowrap;'>Product<i class='fa fa-fw fa-sort'  onclick='sortTable3(1)'></i></th>" +
        "<th style='white-space: nowrap;' title='AD SALES'>AD SALES<i class='fa fa-fw fa-sort'  onclick='sortTable3(2)'></i></th>" +
        "<th style='white-space: nowrap;' title='SPEND'>SPEND<i class='fa fa-fw fa-sort'  onclick='sortTable3(3)'></i></th>" +
        "<th style='white-space: nowrap;' title='Impressions'>Impressions<i class='fa fa-fw fa-sort'  onclick='sortTable3(4)'></i></th>" +
        "<th style='white-space: nowrap;' title='Clicks'>Clicks<i class='fa fa-fw fa-sort'  onclick='sortTable3(5)'></i></th>" +
        "<th style='white-space: nowrap;' title='ACOS'>ACOS<i class='fa fa-fw fa-sort'  onclick='sortTable3(6)'></i></th>" +
        "</tr>" +
        "</thead>" +
        "<tbody>";
    if (saleTopAsinIncrease.length == 0) {
        html += "<tr>\n" +
            "    <td style='padding: 7px; white-space: nowrap;' align='center' colspan='7'>No data found</td>\n" +
            "</tr>";
    } // end if
    for (var count = 0; count < saleTopAsinIncrease.length; count++) {
        html += "<tr>";
        html += "<td style='padding: 7px; white-space: nowrap; '>" + saleTopAsinIncrease[count].asin + "</td>";
        if (saleTopAsinIncrease[count].product_title == null) {
            saleTopAsinIncrease[count].product_title = '-';
            html += "<td title='" + saleTopAsinIncrease[count].product_title + "' style='padding: 7px; white-space: nowrap;'>" + (saleTopAsinIncrease[count].product_title).substring(0, 20) + "</td>";
        } else {
            html += "<td title='" + saleTopAsinIncrease[count].product_title.replace(/(['.?*&+^$[\]\\(){}|-])/g, "") + "' style='padding: 7px; white-space: nowrap;'>" + (saleTopAsinIncrease[count].product_title).substring(0, 20) + "...</td>";

        }
        html += "<td style='padding: 7px; white-space: nowrap;'>" + saleTopAsinIncrease[count].adSales + "</td>";
        html += "<td style='padding: 7px; white-space: nowrap;'>" + saleTopAsinIncrease[count].spend + "</td>";
        html += "<td style='padding: 7px; white-space: nowrap;'>" + saleTopAsinIncrease[count].impressions + "</td>";
        html += "<td style='padding: 7px; white-space: nowrap;'>" + saleTopAsinIncrease[count].clicks + "</td>";
        html += "<td style='padding: 7px; white-space: nowrap;'>" + saleTopAsinIncrease[count].ACOS + "</td>";
        html += "</tr>";
    } // end for
    html += "</tbody></table>";
    $('#top_asin_increase').html(html);
} // end function
function generateSaleTopAsinShippedCogs(saleTopAsinShippedCogs) {
    var html = "";
    html += "<table class='table table-striped mb-0' id='10_asin_Table' style='text-align: center;'>" +
        "<thead>" +
        "<tr>" +
        "<th style='white-space: nowrap;'>ASIN<i class='fa fa-fw fa-sort'  onclick='sortTable2(0)'></i></th>" +
        "<th style='white-space: nowrap;'>Product<i class='fa fa-fw fa-sort'  onclick='sortTable2(1)'></i></th>" +
        "<th style='white-space: nowrap;' title='AD SALES'>AD SALES<i class='fa fa-fw fa-sort'  onclick='sortTable2(2)'></i></th>" +
        "<th style='white-space: nowrap;' title='SPEND'>SPEND<i class='fa fa-fw fa-sort'  onclick='sortTable2(3)'></i></th>" +
        "<th style='white-space: nowrap;' title='Impressions'>Impressions<i class='fa fa-fw fa-sort'  onclick='sortTable2(4)'></i></th>" +
        "<th style='white-space: nowrap;' title='Clicks'>Clicks<i class='fa fa-fw fa-sort'  onclick='sortTable2(5)'></i></th>" +
        "<th style='white-space: nowrap;' title='ACOS'>ACOS<i class='fa fa-fw fa-sort'  onclick='sortTable2(6)'></i></th>" +
        "</tr>" +
        "</thead>" +
        "<tbody>";
    if (saleTopAsinShippedCogs.length == 0) {
        html += "<tr>\n" +
            "    <td style='padding: 14px; white-space: nowrap;' align='center' colspan='7'>No data found</td>\n" +
            "</tr>";
    } // end if
    for (var count = 0; count < saleTopAsinShippedCogs.length; count++) {
        html += "<tr>";
        html += "<td style='padding: 14px; white-space: nowrap; '>" + saleTopAsinShippedCogs[count].asin + "</td>";
        if (saleTopAsinShippedCogs[count].product_title == null) {
            saleTopAsinShippedCogs[count].product_title = '-';
            html += "<td title='" + saleTopAsinShippedCogs[count].product_title + "' style='padding: 14px; white-space: nowrap;' >" + (saleTopAsinShippedCogs[count].product_title).substring(0, 20) + "</td>";
        } else {
            html += "<td title='" + saleTopAsinShippedCogs[count].product_title.replace(/(['.?*&+^$[\]\\(){}|-])/g, "") + "' style='padding: 14px; white-space: nowrap;' >" + (saleTopAsinShippedCogs[count].product_title).substring(0, 20) + "...</td>";
        }
        html += "<td style='padding: 14px; white-space: nowrap;'>" + saleTopAsinShippedCogs[count].adSales + "</td>";
        html += "<td style='padding: 14px; white-space: nowrap;'>" + saleTopAsinShippedCogs[count].spend + "</td>";
        html += "<td style='padding: 14px; white-space: nowrap;'>" + saleTopAsinShippedCogs[count].impressions + "</td>";
        html += "<td style='padding: 14px; white-space: nowrap;'>" + saleTopAsinShippedCogs[count].clicks + "</td>";
        html += "<td style='padding: 14px; white-space: nowrap;'>" + saleTopAsinShippedCogs[count].ACOS + "</td>";
        html += "</tr>";
    } // end for
    html += "</tbody></table>";
    $('#top_asin_sales').html(html);
} // end function
function getConditionalClassStyle(percentage) {
    let style = "";
    let value = percentage ? (percentage).replace(new RegExp("\\s|,|\\%", "gm"), "") : 0;
    if (value > 0 && value <= 100) {
        style = "<i class='fa fa-arrow-up success' ></i>";
    } // end if
    else {
        style = "<i class='fa fa-arrow-down danger'></i>";
    }
    return style;
} // end function

$('.checkbox').on('click', function () {
    var checkbox_adSale = [];
    var checkbox_adSpend = [];
    var checkbox_roas = [];
    var checkbox_orderRevenue = [];
    var checkbox_glanceView = [];
    var checkbox_click = [];
    var checkbox_impression = [];
    var checkbox_order = [];
    var checkbox_conversionRate = [];
    var dollar = 0;
    var unit = 0;
    var percentage = 0;
    let dollar_names = [];
    let unit_names = [];
    var count_dollar = 0;
    if ($('#adSales').is(':checked')) {
        checkbox_adSale = adSales_value;
        dollar = 1;
        if (unit === 1) {
            unit = 2;
            percentage = 0;
        }
        if (percentage === 1) {
            percentage = 2;
            unit = 0;
        }
        if (dollar_names.length == 0) {
            dollar_names[dollar_names.length] = 'Ad Sales';
        }
    }
    if ($('#adSpend').is(':checked')) {
        checkbox_adSpend = adSpends_value;
        dollar = 1;
        if (unit === 1) {
            unit = 2;
            percentage = 0;
        }
        if (percentage === 1) {
            percentage = 2;
            unit = 0;
        }
        dollar_names[dollar_names.length] = 'Ad Spend';
    }
    if ($('#roas').is(':checked')) {
        checkbox_roas = roas_value;
        dollar = 1;
        if (unit === 1) {
            unit = 2;
            percentage = 0;
        }
        if (percentage === 1) {
            percentage = 2;
            unit = 0;
        }
        dollar_names[dollar_names.length] = 'ROAS';
    }
    if ($('#orderedRevenue').is(':checked')) {
        checkbox_orderRevenue = orderedRevenue_value;
        dollar = 1;
        if (unit === 1) {
            unit = 2;
            percentage = 0;
        }
        if (percentage === 1) {
            percentage = 2;
            unit = 0;
        }
        dollar_names[dollar_names.length] = 'Ordered Revenue';
    }
    if ($('#glanceView').is(':checked')) {
        checkbox_glanceView = glanceView_value;
        unit = 1;
        if (dollar === 1) {
            dollar = 2;
            percentage = 0;
        }
        if (percentage === 1) {
            percentage = 2;
            dollar = 0;
        }
        unit_names[unit_names.length] = "Glance View";
    }
    if ($('#click').is(':checked')) {
        checkbox_click = clicks_value;
        unit = 1;
        if (dollar === 1) {
            dollar = 2;
            percentage = 0;
        }
        if (percentage === 1) {
            percentage = 2;
            dollar = 0;
        }
        unit_names[unit_names.length] = "Clicks";
    }
    if ($('#impression').is(':checked')) {
        checkbox_impression = impressions_value;
        unit = 1;
        if (dollar === 1) {
            dollar = 2;
            percentage = 0;
        }
        if (percentage === 1) {
            percentage = 2;
            dollar = 0;
        }
        unit_names[unit_names.length] = "Impressions";
    }
    if ($('#order').is(':checked')) {
        checkbox_order = orders_value;
        unit = 1;
        if (dollar === 1) {
            dollar = 2;
            percentage = 0;
        }
        if (percentage === 1) {
            percentage = 2;
            dollar = 0;
        }
        unit_names[unit_names.length] = "Orders";
    }
    if ($('#conversionRtae').is(':checked')) {
        checkbox_conversionRate = conversionRate_value;
        percentage = 1;
        if (dollar === 1) {
            dollar = 2;
            unit = 0;
        }
        if (unit === 1) {
            unit = 2;
            dollar = 0;
        }
    }

    if ($(this).val() == "dollar") {
        dollar = 1;
        if (unit === 1) {
            unit = 2;
            percentage = 0;
        }
        if (percentage === 1) {
            percentage = 2;
            unit = 0;
        }
    }
    if ($(this).val() == "unit") {
        unit = 1;
        if (dollar === 1) {
            dollar = 2;
            percentage = 0;
        }
        if (percentage === 1) {
            percentage = 2;
            dollar = 0;
        }
    }
    if ($(this).val() == "percentage") {
        percentage = 1;
        if (unit === 1) {
            unit = 2;
            dollar = 0;
        }
        if (dollar === 1) {
            dollar = 2;
            unit = 0;
        }
    }

    // uncheck by requirements
    if (dollar === 0) {
        $('#adSales').prop("checked", false);
        $('#adSpend').prop("checked", false);
        $('#roas').prop("checked", false);
        $('#orderedRevenue').prop("checked", false);

        dollar_names = [];
        checkbox_adSale = [];
        checkbox_adSpend = [];
        checkbox_roas = [];
        checkbox_orderRevenue = [];
    }
    if (unit === 0) {
        $('#glanceView').prop("checked", false);
        $('#click').prop("checked", false);
        $('#impression').prop("checked", false);
        $('#order').prop("checked", false);

        unit_names = [];
        checkbox_glanceView = [];
        checkbox_click = [];
        checkbox_impression = [];
        checkbox_order = [];
    }
    if (percentage === 0) {
        $('#conversionRtae').prop("checked", false);

        checkbox_conversionRate = [];
    }
    var transform = checkTransformation(dollar, unit, percentage);
    if (transform) {
        transformGraph(transform, dollar_names, unit_names);
    }
    setTimeout(function () {
        business_report_graph_dollar.unload();
    }, 500);
    setTimeout(function () {
        business_report_graph_dollar.load({
            json: {
                'x': label_value,
                'Ad Sales': checkbox_adSale,
                'Ad Spends': checkbox_adSpend,
                'ROAS': checkbox_roas,
                'Ordered Revenue': checkbox_orderRevenue,
                'Glance View': checkbox_glanceView,
                'Clicks': checkbox_click,
                'Impressions': checkbox_impression,
                'Orders': checkbox_order,
                'Conversion Rate': checkbox_conversionRate,
            },
        });
    }, 1000);
});

function transformGraph(type, dollar_names, unit_names) {
    let axis = null;
    let axes = null;
    switch (type) {
        case "dollar-unit":
            axis = {
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
                        text: dollar_names,
                        position: 'outer-middle'
                    },
                    tick: {
                        format: d3.format('$,.2f')
                    }
                },
                y2: {
                    show: true,
                    label: {
                        text: unit_names,
                        position: 'outer-middle'
                    },
                    tick: {
                        format: d3.format(',.0f')
                    }
                }
            };
            axes = {
                'Ad Sales': 'y',
                'Ad Spends': 'y',
                'ROAS': 'y',
                'Ordered Revenue': 'y',
                'Glance View': 'y2',
                'Clicks': 'y2',
                'Impressions': 'y2',
                'Orders': 'y2',
                'Conversion Rate': 'y3',
            };
            break;
        case "dollar-percentage":
            axis = {
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
                        text: dollar_names,
                        position: 'outer-middle'
                    },
                    tick: {
                        format: d3.format('$,.2f')
                    }
                },
                y2: {
                    show: true,
                    label: {
                        text: 'Conversion Rate',
                        position: 'outer-middle'
                    },
                    tick: {
                        format: function (v, id, i, j) { return v.toFixed(2) + '%'; }
                    }

                }
            };
            axes = {
                'Ad Sales': 'y',
                'Ad Spends': 'y',
                'ROAS': 'y',
                'Ordered Revenue': 'y',
                'Glance View': 'y3',
                'Clicks': 'y3',
                'Impressions': 'y3',
                'Orders': 'y3',
                'Conversion Rate': 'y2',
            };
            break;
        case "unit-dollar":
            axis = {
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
                        text: unit_names,
                        position: 'outer-middle'
                    },
                    tick: {
                        format: d3.format(',.0f')
                    }
                },
                y2: {
                    show: true,
                    label: {
                        text: dollar_names,
                        position: 'outer-middle'
                    },
                    tick: {
                        format: d3.format('$,.2f')
                    }
                }
            };
            axes = {
                'Ad Sales': 'y2',
                'Ad Spends': 'y2',
                'ROAS': 'y2',
                'Ordered Revenue': 'y2',
                'Glance View': 'y',
                'Clicks': 'y',
                'Impressions': 'y',
                'Orders': 'y',
                'Conversion Rate': 'y3',
            };
            break;
        case "unit-percentage":
            axis = {
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
                        text: unit_names,
                        position: 'outer-middle'
                    },
                    tick: {
                        format: d3.format(',.0f')
                    }
                },
                y2: {
                    show: true,
                    label: {
                        text: 'Conversion Rate',
                        position: 'outer-middle'
                    },
                    tick: {
                        format: function (v, id, i, j) { return v.toFixed(2) + '%'; }
                    }
                }
            };
            axes = {
                'Ad Sales': 'y3',
                'Ad Spends': 'y3',
                'ROAS': 'y3',
                'Ordered Revenue': 'y3',
                'Glance View': 'y',
                'Clicks': 'y',
                'Impressions': 'y',
                'Orders': 'y',
                'Conversion Rate': 'y2',
            };
            break;
        case "percentage-dollar":
            axis = {
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
                        text: 'Conversion Rate',
                        position: 'outer-middle'
                    },
                    tick: {
                        format: function (v, id, i, j) { return v.toFixed(2) + '%'; }
                    }
                },
                y2: {
                    show: true,
                    label: {
                        text: dollar_names,
                        position: 'outer-middle'
                    },
                    tick: {
                        format: d3.format('$,.2f')
                    }
                }
            };
            axes = {
                'Ad Sales': 'y2',
                'Ad Spends': 'y2',
                'ROAS': 'y2',
                'Ordered Revenue': 'y2',
                'Glance View': 'y3',
                'Clicks': 'y3',
                'Impressions': 'y3',
                'Orders': 'y3',
                'Conversion Rate': 'y',
            };
            break;
        case "percentage-unit":
            axis = {
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
                        text: 'Conversion Rate',
                        position: 'outer-middle'
                    },
                    tick: {
                        format: function (v, id, i, j) { return v.toFixed(2) + '%'; }
                    }
                },
                y2: {
                    show: true,
                    label: {
                        text: unit_names,
                        position: 'outer-middle'
                    },
                    tick: {
                        format: d3.format(',.0f')
                    }
                }
            };
            axes = {
                'Ad Sales': 'y3',
                'Ad Spends': 'y3',
                'ROAS': 'y3',
                'Ordered Revenue': 'y3',
                'Glance View': 'y2',
                'Clicks': 'y2',
                'Impressions': 'y2',
                'Orders': 'y2',
                'Conversion Rate': 'y',
            };
            break;
        case "dollar":
            axis = {
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
                        text: dollar_names,
                        position: 'outer-middle'
                    },
                    tick: {
                        format: d3.format('$,.2f')
                    }
                },
                y2: {
                    show: true,
                    label: {
                        text: unit_names,
                        position: 'outer-middle'
                    },
                    tick: {
                        format: d3.format(',.0f')
                    }
                }
            };
            axes = {
                'Ad Sales': 'y',
                'Ad Spends': 'y',
                'ROAS': 'y',
                'Ordered Revenue': 'y',
                'Glance View': 'y2',
                'Clicks': 'y2',
                'Impressions': 'y2',
                'Orders': 'y2',
                'Conversion Rate': 'y3',
            };
            break;
        case "unit":
            axis = {
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
                        text: unit_names,
                        position: 'outer-middle'
                    },
                    tick: {
                        format: d3.format(',.0f')
                    }
                },
                y2: {
                    show: true,
                    label: {
                        text: dollar_names,
                        position: 'outer-middle'
                    },
                    tick: {
                        format: d3.format('$,.2f')
                    }
                }
            };
            axes = {
                'Ad Sales': 'y2',
                'Ad Spends': 'y2',
                'ROAS': 'y2',
                'Ordered Revenue': 'y2',
                'Glance View': 'y',
                'Clicks': 'y',
                'Impressions': 'y',
                'Orders': 'y',
                'Conversion Rate': 'y3',
            };
            break;
        case "percentage":
            axis = {
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
                        text: 'Conversion Rate',
                        position: 'outer-middle'
                    },
                    tick: {
                        format: function (v, id, i, j) { return v.toFixed(2) + '%'; }
                    }
                },
                y2: {
                    show: false,
                    label: {
                        text: dollar_names,
                        position: 'outer-middle'
                    },
                    tick: {
                        format: d3.format('$,.2f')
                    }
                },
            };
            axes = {
                'Ad Sales': 'y3',
                'Ad Spends': 'y3',
                'ROAS': 'y3',
                'Ordered Revenue': 'y3',
                'Glance View': 'y3',
                'Clicks': 'y3',
                'Impressions': 'y3',
                'Orders': 'y3',
                'Conversion Rate': 'y',
            };
            break;
        default:
            break;
    }
    setTimeout(function () {
        business_report_graph_dollar.unload();
    }, 500);

    $('.business_report_graph_dollar').html("");
    business_report_graph_dollar = c3.generate({
        bindto: d3.select('.business_report_graph_dollar'),
        size: {
            height: 330,
        },
        data: {
            x: 'x',
            json: {
                'x': '',
                'Ad Sales': '',
                'Ad Spends': '',
                'ROAS': '',
                'Ordered Revenue': '',
                'Glance View': '',
                'Clicks': '',
                'Impressions': '',
                'Orders': '',
                'Conversion Rate': '',
            },
            axes: axes,
            types: {
                'Ad Sales': 'line',
                'Ad Spends': 'area',
                'ROAS': 'spline',
                'Ordered Revenue': 'area-spline',
                'Glance View': 'line',
                'Clicks': 'area',
                'Impressions': 'spline',
                'Orders': 'area-spline',
                'Conversion Rate': 'area',
            },
            colors: {
                'Ad Sales': '#4DD0EA',
                'Ad Spends': '#FFB371',
                'ROAS': '#9DE5E6',
                'Ordered Revenue': '#2693BE',
                'Glance View': '#FFD2A0',
                'Clicks': '#90B9D5',
                'Impressions': '#30DDFF',
                'Orders': '#FC943D',
                'Conversion Rate': '#343438',
            },
        },
        axis: axis,
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
                    return business_report_graph_tooltip[d];
                },
            }
        }
    });
}
function checkTransformation(dollar, unit, percentage) {
    if (dollar === 1 && unit === 0 && percentage === 0) {
        return 'dollar';
    } else if (dollar === 0 && unit === 1 && percentage === 0) {
        return 'unit';
    } else if (dollar === 0 && unit === 0 && percentage === 1) {
        return 'percentage';
    } else if (dollar === 1 && unit === 2 && percentage === 0) {
        return 'dollar-unit';
    }
    else if (dollar === 2 && unit === 1 && percentage === 0) {
        return 'unit-dollar';
    }
    else if (dollar === 1 && unit === 0 && percentage === 2) {
        if ($('#adSales').is(':checked') == false) {
            if ($('#adSpend').is(':checked') == false) {
                if ($('#roas').is(':checked') == false) {
                    if ($('#orderedRevenue').is(':checked') == false) {
                        percentage = 1;
                        dollar = 0;
                        return 'percentage';
                    } else {
                        return 'dollar-percentage';
                    }
                } else {
                    return 'dollar-percentage';
                }
            }
            else {
                return 'dollar-percentage';
            }
        } else {
            return 'dollar-percentage';
        }
    }
    else if (dollar === 0 && unit === 1 && percentage === 2) {
        if ($('#glanceView').is(':checked') == false) {
            if ($('#click').is(':checked') == false) {
                if ($('#impression').is(':checked') == false) {
                    if ($('#order').is(':checked') == false) {
                        percentage = 1;
                        unit = 0;
                        return 'percentage';
                    } else {
                        return 'unit-percentage';
                    }
                } else {
                    return 'unit-percentage';
                }
            }
            else {
                return 'unit-percentage';
            }
        } else {
            return 'unit-percentage';
        }
    }
    else if (dollar === 2 && unit === 0 && percentage === 1) {
        return 'percentage-dollar';
    }
    else if (dollar === 0 && unit === 2 && percentage === 1) {
        return 'percentage-unit';
    } else if (dollar === 1 && unit === 1 && percentage === 1) {
        Swal.fire({
            title: "Error",
            text: "Kindly uncheck dollar or unit or percentage first",
            type: "info",
            allowOutsideClick: false,
            confirmButtonClass: 'btn btn-primary',
            buttonsStyling: false,
        });
        $("#conversionRtae").prop("checked", false);
        return false;
    }
}

function sortTable1(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("portfolio_Table");
    switching = true;
    //Set the sorting direction to ascending:
    dir = "asc";
    /*Make a loop that will continue until
    no switching has been done:*/
    while (switching) {
        //start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        /*Loop through all table rows (except the
        first, which contains table headers):*/
        for (i = 1; i < (rows.length - 2); i++) {
            //start by saying there should be no switching:
            shouldSwitch = false;
            /*Get the two elements you want to compare,
            one from current row and one from the next:*/
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            /*check if the two rows should switch place,
            based on the direction, asc or desc:*/
            if (i == 1) {
                if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        //if so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        //if so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                }
            } else {
                if (dir == "asc") {
                    if (parseFloat(x.innerHTML.replace(/\$|,/g, '')) > parseFloat(y.innerHTML.replace(/\$|,/g, ''))) {
                        //if so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (parseFloat(x.innerHTML.replace(/\$|,/g, '')) < parseFloat(y.innerHTML.replace(/\$|,/g, ''))) {
                        //if so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                }
            }
        }
        if (shouldSwitch) {
            /*If a switch has been marked, make the switch
            and mark that a switch has been done:*/
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            //Each time a switch is done, increase this count by 1:
            switchcount++;
        } else {
            /*If no switching has been done AND the direction is "asc",
            set the direction to "desc" and run the while loop again.*/
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}
function sortTable2(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("10_asin_Table");
    switching = true;
    //Set the sorting direction to ascending:
    dir = "asc";
    /*Make a loop that will continue until
    no switching has been done:*/
    while (switching) {
        //start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        /*Loop through all table rows (except the
        first, which contains table headers):*/
        for (i = 1; i < (rows.length - 1); i++) {
            //start by saying there should be no switching:
            shouldSwitch = false;
            /*Get the two elements you want to compare,
            one from current row and one from the next:*/
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            /*check if the two rows should switch place,
            based on the direction, asc or desc:*/
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            /*If a switch has been marked, make the switch
            and mark that a switch has been done:*/
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            //Each time a switch is done, increase this count by 1:
            switchcount++;
        } else {
            /*If no switching has been done AND the direction is "asc",
            set the direction to "desc" and run the while loop again.*/
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}
function sortTable3(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("5_asin_increase_Table");
    switching = true;
    //Set the sorting direction to ascending:
    dir = "asc";
    /*Make a loop that will continue until
    no switching has been done:*/
    while (switching) {
        //start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        /*Loop through all table rows (except the
        first, which contains table headers):*/
        for (i = 1; i < (rows.length - 1); i++) {
            //start by saying there should be no switching:
            shouldSwitch = false;
            /*Get the two elements you want to compare,
            one from current row and one from the next:*/
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            /*check if the two rows should switch place,
            based on the direction, asc or desc:*/
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            /*If a switch has been marked, make the switch
            and mark that a switch has been done:*/
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            //Each time a switch is done, increase this count by 1:
            switchcount++;
        } else {
            /*If no switching has been done AND the direction is "asc",
            set the direction to "desc" and run the while loop again.*/
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}
function sortTable4(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("5_asin_decrease_Table");
    switching = true;
    //Set the sorting direction to ascending:
    dir = "asc";
    /*Make a loop that will continue until
    no switching has been done:*/
    while (switching) {
        //start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        /*Loop through all table rows (except the
        first, which contains table headers):*/
        for (i = 1; i < (rows.length - 1); i++) {
            //start by saying there should be no switching:
            shouldSwitch = false;
            /*Get the two elements you want to compare,
            one from current row and one from the next:*/
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            /*check if the two rows should switch place,
            based on the direction, asc or desc:*/
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            /*If a switch has been marked, make the switch
            and mark that a switch has been done:*/
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            //Each time a switch is done, increase this count by 1:
            switchcount++;
        } else {
            /*If no switching has been done AND the direction is "asc",
            set the direction to "desc" and run the while loop again.*/
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
$(document).ready(function () {
    $("#sales_filter_vendor").select2({
        dropdownParent: $("#filter_form")
    });
});
function changeHeight() {
    var checkheight = document.getElementById('portfolio_kpi').style.maxHeight;
    if (checkheight == '363px') {
        document.getElementById('portfolio_kpi').style.maxHeight = '100%';
        document.getElementById("exportPortfolio").style.marginLeft = "1123px";
    } else {
        document.getElementById('portfolio_kpi').style.maxHeight = '363px';
        document.getElementById("exportPortfolio").style.marginLeft = "378px";
    }
}
