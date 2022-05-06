$("#filter_range_picker").datetimepicker({
    format: 'MM/DD/YYYY',
    minDate: '09/09/1999',
    maxDate: new Date(),
});

$('#custom_data_value').daterangepicker({
    "minDate": '09/09/1999',
    "maxDate": new Date()
});

$("#flywheel_filter_vendor").select2({
    dropdownParent: $("#flywheel_filter_form"),
    language: {
        noResults: function (e) {
            return "No vendor found";
        },
    }
});

$("#flywheel_filter_range").select2({
    dropdownParent: $("#flywheel_filter_form"),
    language: {
        noResults: function (e) {
            return "No reporting range found";
        },
    }
});

$("#flywheel_filter_date_range").val($("#custom_data_value").val());

$('#custom_data_value').on('apply.daterangepicker', function (ev, picker) {
    $("#flywheel_filter_date_range").val($("#custom_data_value").val());
});

var value = ($("#flywheel_filter_date_range").val()).split(" - ");
var start_date_text = value[0];
var end_date_text = value[1];
var date_filter_range_value = moment(start_date_text, "MM/DD/YYYY").format("MMM DD, YYYY") + " - " + moment(end_date_text, "MM/DD/YYYY").format("MMM DD, YYYY");
$("#selected_date_text").html(date_filter_range_value);

document.addEventListener('DOMContentLoaded', function (e) {

    //on Submitting form call SP with Filter Values with in the page.
    $('#flywheel_inpage_filter_form').on('submit', function (event) {
        event.preventDefault();

        $("#flywheel_filter_date_range").val($("#custom_data_value").val());
        var value = ($("#flywheel_filter_date_range").val()).split(" - ");
        var start_date_text = value[0];
        var end_date_text = value[1];
        var date_filter_range_value = moment(start_date_text, "MM/DD/YYYY").format("MMM DD, YYYY") + " - " + moment(end_date_text, "MM/DD/YYYY").format("MMM DD, YYYY");
        $("#selected_date_text").html(date_filter_range_value);

        var filter_vendor = $('#flywheel_filter_vendor').val();
        var filter_range = $('#flywheel_filter_range').val();
        var filter_date_range = $('#flywheel_filter_date_range').val();

        var product_info = $('#product_info').val();
        var asin_info = $('#asin_info').val();
        var category_info = $('#category_info').val();

        $('#product_info').val(product_info);
        $('#asin_info').val(asin_info);
        $('#category_info').val(category_info);

        $('#filter_vendor').val(filter_vendor);
        $('#filter_range').val(filter_range);

        getOrderedRevenueSpAdSalesData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info);
        getConversionsAspData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info);
        getSpImpressionsGlanceViewData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info);
        getInventoryOrderedUnitData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info);
        getSpendByAdTypeData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info);
        getSalesByAdTypeData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info);
        getCategoryDetailData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info);

    });
    //on Submitting form call SP with Filter Values
    $('#flywheel_filter_form').on('submit', function (event) {
        event.preventDefault();
        $('#product_info').select2('open').select2('close');
        $('#asin_info').select2('open').select2('close');
        $('#category_info').select2('open').select2('close');

        $("#flywheel_filter_date_range").val($("#custom_data_value").val());
        var value = ($("#flywheel_filter_date_range").val()).split(" - ");
        var start_date_text = value[0];
        var end_date_text = value[1];
        var date_filter_range_value = moment(start_date_text, "MM/DD/YYYY").format("MMM DD, YYYY") + " - " + moment(end_date_text, "MM/DD/YYYY").format("MMM DD, YYYY");
        $("#selected_date_text").html(date_filter_range_value);

        var filter_vendor = $('#flywheel_filter_vendor').val();
        var filter_range = $('#flywheel_filter_range').val();
        var filter_date_range = $('#flywheel_filter_date_range').val();

        $('#filter_vendor').val(filter_vendor);
        $('#filter_range').val(filter_range);
        var product_info = $('#product_info').val();
        var asin_info = $('#asin_info').val();
        var category_info = $('#category_info').val();

        $('#product_info').val(product_info);
        $('#asin_info').val(asin_info);
        $('#category_info').val(category_info);

        getOrderedRevenueSpAdSalesData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info);
        getConversionsAspData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info);
        getSpImpressionsGlanceViewData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info);
        getInventoryOrderedUnitData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info);
        getSpendByAdTypeData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info);
        getSalesByAdTypeData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info);
        getCategoryDetailData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info);

    });
    $('#flywheel_filter_vendor').on('change', function () {
        var filter_vendor = $('#flywheel_filter_vendor').val();
    });

    $('#flywheel_filter_range').on('change', function (e) {
        var type = $('#flywheel_filter_range').val();
        document.getElementById('custom_data_value').type = 'text';
        document.getElementById('filter_range_picker').type = 'hidden';
        $("#flywheel_filter_date_range").val($("#custom_data_value").val());
    });
});
//below is the graphs structures for all graphs in flywheel
var Ordered_rev_adsales_label = [];
var total_ordered_revenue_value = 0;
var total_ad_sales_value = 0;

var Conversion_asp_label = [];
var total_conversion_value = 0;
var total_asp_value = 0;

var glance_view_impressions_label = [];
var total_glance_view_value = 0;
var total_sp_impression_value = 0;

var inventory_ordered_unit_label = [];
var total_inventory_unit_value = 0;
var total_ordered_value = 0;

var total_ad_sale_sp_value = 0;
var total_ad_sale_sb_value = 0;
var total_ad_sale_sbv_value = 0;
var total_ad_sale_sd_value = 0;
var ad_sales_graph_tooltip = [];

var total_campaign_spend_sp_value = 0;
var total_campaign_spend_sb_value = 0;
var total_campaign_spend_sbv_value = 0;
var total_campaign_spend_sd_value = 0;
var total_campaign_graph_tooltip = [];
var ordered_revenue_sp_adsales = c3.generate({
    bindto: d3.select('#ordered_revenue_sp_adsales'),
    size: {
        height: 320,
    },
    data: {
        x: 'x',
        json: {
            'x': Ordered_rev_adsales_label,
            'ordered_revenue': total_ordered_revenue_value,
            'sp_ad_sales': total_ad_sales_value,
        },
        colors: {
            'ordered_revenue': '#4DD0EA',
            'sp_ad_sales': '#FFB371',
        },
        types: {
            'ordered_revenue': 'area',
            'sp_ad_sales': 'area',
        },

        axes: {
            'ordered_revenue': 'y',
            'sp_ad_sales': 'y2',
        },
        names: {
            ordered_revenue: 'TOTAL ORDERED REVENUE',
            sp_ad_sales: 'SP AD SALES'
        }
    },
    axis: {
        x: {
            type: 'category',
            tick: {
                rotate: -80,
                multiline: false
            },
            height: 60,
            show: true
        },
        y: {
            show: true,
            label: {
                text: 'TOTAL ORDERED REVENUE',
                position: 'outer-middle'
            },
            tick: {
                format: d3.format('$,.2f')
            }
        },
        y2: {
            show: true,
            label: {
                text: 'SP AD SALES',
                position: 'outer-middle'
            },
            tick: {
                format: d3.format('$,.2f')
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
            show: true,
        }
    },
    legend: {
        show: true,
        position: "inset",
        inset: {
            anchor: 'top-right',
            x: undefined,
            y: -65,
            step: 2
        }

    },
    padding: {
        top: 60
    },
    tooltip: {
        show: true,
        // format: {
        //     title: function (d) {
        //         return yoy_growth_tooltip[d];
        //     },
        // }
    }
});

var conversion_asp_chart = c3.generate({
    bindto: d3.select('#conversion_asp_chart'),
    size: {
        height: 320,
    },
    data: {
        x: 'x',
        json: {
            'x': Conversion_asp_label,
            'total_conversions': total_conversion_value,
            'asp_value': total_asp_value,
        },
        colors: {
            'total_conversions': '#4DD0EA',
            'asp_value': '#FFB371',
        },
        types: {
            'total_conversions': 'area',
            'asp_value': 'area',
        },

        axes: {
            'total_conversions': 'y',
            'asp_value': 'y2',
        },
        names: {
            total_conversions: 'TOTAL CONVERSION',
            asp_value: 'ASP'
        }
    },
    axis: {
        x: {
            type: 'category',
            tick: {
                rotate: -80,
                multiline: false
            },
            height: 60,
            show: true
        },
        y: {
            show: true,
            label: {
                text: 'TOTAL CONVERSION',
                position: 'outer-middle'
            },
            tick: {
                format: function (v, id, i, j) { return v + '%'; }
            }
        },
        y2: {
            show: true,
            label: {
                text: 'ASP',
                position: 'outer-middle'
            },
            tick: {
                format: d3.format('$,.2f')
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
            show: true,
        }
    },
    legend: {
        show: true,
        position: "inset",
        inset: {
            anchor: 'top-right',
            x: undefined,
            y: -65,
            step: 2
        }

    },
    padding: {
        top: 60
    },
    tooltip: {
        show: true,
    }
});

var glance_view_sp_impression_chart = c3.generate({
    bindto: d3.select('#glance_view_sp_impression_chart'),
    size: {
        height: 320,
    },
    data: {
        x: 'x',
        json: {
            'x': glance_view_impressions_label,
            'glance_view': total_glance_view_value,
            'sp_impression': total_sp_impression_value,
        },
        colors: {
            'glance_view': '#4DD0EA',
            'sp_impression': '#FFB371',
        },
        types: {
            'glance_view': 'area',
            'sp_impression': 'area',
        },

        axes: {
            'glance_view': 'y',
            'sp_impression': 'y2',
        },
        names: {
            glance_view: 'TOTAL GLANCE VIEW',
            sp_impression: 'SP IMPRESSION'
        }
    },
    axis: {
        x: {
            type: 'category',
            tick: {
                rotate: -80,
                multiline: false
            },
            height: 60,
            show: true
        },
        y: {
            show: true,
            label: {
                text: 'TOTAL GLANCE VIEW',
                position: 'outer-middle'
            },
            tick: {
                format: d3.format(',.2f')
            }
        },
        y2: {
            show: true,
            label: {
                text: 'SP IMPRESSION',
                position: 'outer-middle'
            },
            tick: {
                format: d3.format(',.2f')
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
            show: true,
        }
    },
    legend: {
        show: true,
        position: "inset",
        inset: {
            anchor: 'top-right',
            x: undefined,
            y: -65,
            step: 2
        }

    },
    padding: {
        top: 60
    },
    tooltip: {
        show: true,
    }
});

var inventory_ordered_unit_chart = c3.generate({
    bindto: d3.select('#inventory_ordered_unit_chart'),
    size: {
        height: 320,
    },
    data: {
        x: 'x',
        json: {
            'x': inventory_ordered_unit_label,
            'inventory_unit': total_inventory_unit_value,
            'ordered_unit': total_ordered_value,
        },
        colors: {
            'inventory_unit': '#4DD0EA',
            'ordered_unit': '#FFB371',
        },
        types: {
            'inventory_unit': 'area',
            'ordered_unit': 'area',
        },

        axes: {
            'inventory_unit': 'y',
            'ordered_unit': 'y2',
        },
        names: {
            inventory_unit: 'INVENTORY UNIT',
            ordered_unit: 'ORDERED UNIT'
        }
    },
    axis: {
        x: {
            type: 'category',
            tick: {
                rotate: -80,
                multiline: false
            },
            height: 60,
            show: true
        },
        y: {
            show: true,
            label: {
                text: 'INVENTORY UNIT',
                position: 'outer-middle'
            },
            tick: {
                format: d3.format(',.2f')
            }
        },
        y2: {
            show: true,
            label: {
                text: 'ORDERED UNIT',
                position: 'outer-middle'
            },
            tick: {
                format: d3.format(',.2f')
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
            show: true,
        }
    },
    legend: {
        show: true,
        position: "inset",
        inset: {
            anchor: 'top-right',
            x: undefined,
            y: -65,
            step: 2
        }

    },
    padding: {
        top: 60
    },
    tooltip: {
        show: true,
    }
});

var sales_ad_type_chart = c3.generate({
    bindto: d3.select('#sales_ad_type_chart'),
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
                return '$' + numberWithCommas(Number(ad_sales_graph_tooltip[index]));
            },
        }
    }
});

var spend_ad_type_chart = c3.generate({
    bindto: d3.select('#spend_ad_type_chart'),
    data: {
        columns: [
            ['SP', total_campaign_spend_sp_value],
            ['SBV', total_campaign_spend_sbv_value],
            ['SB', total_campaign_spend_sb_value],
            ['SD', total_campaign_spend_sd_value],
        ],
        type: 'donut',
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
                return '$' + numberWithCommas(Number(total_campaign_graph_tooltip[index]));
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
$("#flywheel_filter_date_range").val($("#custom_data_value").val());
getOrderedRevenueSpAdSalesData('0', '0', $("#flywheel_filter_date_range").val(), $("#product_info").val(), $("#asin_info").val(), $("#category_info").val());
getConversionsAspData('0', '0', $("#flywheel_filter_date_range").val(), $("#product_info").val(), $("#asin_info").val(), $("#category_info").val());
getSpImpressionsGlanceViewData('0', '0', $("#flywheel_filter_date_range").val(), $("#product_info").val(), $("#asin_info").val(), $("#category_info").val());
getInventoryOrderedUnitData('0', '0', $("#flywheel_filter_date_range").val(), $("#product_info").val(), $("#asin_info").val(), $("#category_info").val());
getSpendByAdTypeData('0', '0', $("#flywheel_filter_date_range").val(), $("#product_info").val(), $("#asin_info").val(), $("#category_info").val());
getSalesByAdTypeData('0', '0', $("#flywheel_filter_date_range").val(), $("#product_info").val(), $("#asin_info").val(), $("#category_info").val());
getCategoryDetailData('0', '0', $("#flywheel_filter_date_range").val(), $("#product_info").val(), $("#asin_info").val(), $("#category_info").val());

function getOrderedRevenueSpAdSalesData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/flywheel/visual/OrderedRevAdSales",
        type: "POST",
        data: {
            vendor: filter_vendor,
            range: filter_range,
            date_range: filter_date_range,
            product_info: product_info,
            asin_info: asin_info,
            category_info: category_info,
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
                if (typeof (response.orderedRevenueSpAdSales[0]) !== 'undefined') {
                    orderedRevenueSpAdSales(response.orderedRevenueSpAdSales);
                } else {
                    setTimeout(function () {
                        Ordered_rev_adsales_label = [];
                        total_ordered_revenue_value = 0;
                        total_sp_ad_sales_value = 0;
                        ordered_revenue_sp_adsales.load({
                            columns: [
                                ['x', Ordered_rev_adsales_label],
                                ['ordered_revenue', total_ordered_revenue_value],
                                ['sp_ad_sales', total_sp_ad_sales_value],
                            ],
                        });
                    }, 1000);
                }
            }
        },
    });
}
function getConversionsAspData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/flywheel/visual/ConversionAsp",
        type: "POST",
        data: {
            vendor: filter_vendor,
            range: filter_range,
            date_range: filter_date_range,
            product_info: product_info,
            asin_info: asin_info,
            category_info: category_info,
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
                //Total conversions and asp by date line graph
                if (typeof (response.conversionsAsptotal[0]) !== 'undefined') {
                    generateConversionsAsptotal(response.conversionsAsptotal);
                } else {
                    setTimeout(function () {
                        Conversion_asp_label = [];
                        total_conversion_value = 0;
                        total_asp_value = 0;
                        conversion_asp_chart.load({
                            columns: [
                                ['x', Conversion_asp_label],
                                ['total_conversions', total_conversion_value],
                                ['asp_value', total_asp_value],
                            ],
                        });
                    }, 1000);
                }
            }
        },
    });
}
function getSpImpressionsGlanceViewData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/flywheel/visual/spImpressionsGlanceView",
        type: "POST",
        data: {
            vendor: filter_vendor,
            range: filter_range,
            date_range: filter_date_range,
            product_info: product_info,
            asin_info: asin_info,
            category_info: category_info,
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
                //Total Glance view and sp impression graph
                if (typeof (response.glanceViewSpImpression[0]) !== 'undefined') {
                    generateGlanceViewSpImpressiontotal(response.glanceViewSpImpression);
                } else {
                    setTimeout(function () {
                        glance_view_impressions_label = [];
                        total_glance_view_value = 0;
                        total_sp_impression_value = 0;
                        glance_view_sp_impression_chart.load({
                            columns: [
                                ['x', glance_view_impressions_label],
                                ['glance_view', total_glance_view_value],
                                ['sp_impression', total_sp_impression_value],
                            ],
                        });
                    },
                        1000);
                }
            }
        },
    });
}
function getInventoryOrderedUnitData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/flywheel/visual/inventoryOrderedUnit",
        type: "POST",
        data: {
            vendor: filter_vendor,
            range: filter_range,
            date_range: filter_date_range,
            product_info: product_info,
            asin_info: asin_info,
            category_info: category_info,
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
                //Total Inventory and ordered unit graph
                if (typeof (response.inventoryOrderedUnitTotal[0]) !== 'undefined') {
                    generateInventoryOrderedUnit(response.inventoryOrderedUnitTotal);
                } else {
                    setTimeout(function () {
                        inventory_ordered_unit_label = [];
                        total_inventory_unit_value = 0;
                        total_ordered_value = 0;
                        inventory_ordered_unit_chart.load({
                            columns: [
                                ['x', inventory_ordered_unit_label],
                                ['inventory_unit', total_inventory_unit_value],
                                ['ordered_unit', total_ordered_value],
                            ],
                        });
                    }, 1000);
                }
            }
        },
    });
}
function getSpendByAdTypeData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/flywheel/visual/spendByAdType",
        type: "POST",
        data: {
            vendor: filter_vendor,
            range: filter_range,
            date_range: filter_date_range,
            product_info: product_info,
            asin_info: asin_info,
            category_info: category_info,
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
                //spend ad type donut graph
                if (typeof (response.campaignSpendByType[0]) !== 'undefined') {
                    changeCampaignSpendByTypeValues(response.campaignSpendByType);
                } else {
                    total_campaign_spend_sp_value = 0;
                    total_campaign_spend_sb_value = 0;
                    total_campaign_spend_sbv_value = 0;
                    total_campaign_spend_sd_value = 0;
                    setTimeout(function () {
                        spend_ad_type_chart.load({
                            json: {
                                'SP': total_campaign_spend_sp_value,
                                'SBV': total_campaign_spend_sbv_value,
                                'SB': total_campaign_spend_sb_value,
                                'SD': total_campaign_spend_sd_value,
                            },
                        });
                    }, 1000);
                }
            }
        },
    });
}
function getSalesByAdTypeData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/flywheel/visual/salesByAdType",
        type: "POST",
        data: {
            vendor: filter_vendor,
            range: filter_range,
            date_range: filter_date_range,
            product_info: product_info,
            asin_info: asin_info,
            category_info: category_info,
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
                //sales ad type graph donut
                if (typeof (response.totalAdSalesByType[0]) !== 'undefined') {
                    changeTotalAdSalesByTypeValues(response.totalAdSalesByType);
                } else {
                    total_ad_sale_sp_value = 0;
                    total_ad_sale_sb_value = 0;
                    total_ad_sale_sbv_value = 0;
                    total_ad_sale_sd_value = 0;
                    setTimeout(function () {
                        spend_ad_type_chart.load({
                            json: {
                                'SP': total_ad_sale_sp_value,
                                'SBV': total_ad_sale_sbv_value,
                                'SB': total_ad_sale_sb_value,
                                'SD': total_ad_sale_sd_value,
                            },
                        });
                    }, 1000);
                }
            }
        },
    });
}
function getCategoryDetailData(filter_vendor, filter_range, filter_date_range, product_info, asin_info, category_info) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: base_url + "/flywheel/visual/categoryDetail",
        type: "POST",
        data: {
            vendor: filter_vendor,
            range: filter_range,
            date_range: filter_date_range,
            product_info: product_info,
            asin_info: asin_info,
            category_info: category_info,
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
                generateCategoryDetailDataTable(response.categoryDetailDataTable);
            }
        },
    });
}
function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}

function generateConversionsAsptotal(ConversionsAsptotalLineGraph) {
    Conversion_asp_label = [];
    total_conversion_value = [];
    total_asp_value = [];
    for (var count = 0; count < ConversionsAsptotalLineGraph.length; count++) {
        Conversion_asp_label[count] = ConversionsAsptotalLineGraph[count].by_date;
        total_conversion_value[count] = parseInt(ConversionsAsptotalLineGraph[count].Conversion);
        total_asp_value[count] = parseInt(ConversionsAsptotalLineGraph[count].ave_sale_price);
    }
    setTimeout(function () {
        conversion_asp_chart.load({
            json: {
                'x': Conversion_asp_label,
                'total_conversions': total_conversion_value,
                'asp_value': total_asp_value,
            },
        });
    }, 1000);
}
function generateGlanceViewSpImpressiontotal(GlanceViewSpImpressionLineGraph) {
    glance_view_impressions_label = [];
    total_glance_view_value = [];
    total_sp_impression_value = [];
    for (var count = 0; count < GlanceViewSpImpressionLineGraph.length; count++) {
        glance_view_impressions_label[count] = GlanceViewSpImpressionLineGraph[count].by_date;
        total_glance_view_value[count] = parseInt(GlanceViewSpImpressionLineGraph[count].glance_views);
        total_sp_impression_value[count] = parseInt(GlanceViewSpImpressionLineGraph[count].sp_impressions);
    }
    setTimeout(function () {
        glance_view_sp_impression_chart.load({
            json: {
                'x': glance_view_impressions_label,
                'glance_view': total_glance_view_value,
                'sp_impression': total_sp_impression_value,
            },
        });
    }, 1000);
}
function generateInventoryOrderedUnit(InventoryOrderedUnitLineGraph) {
    inventory_ordered_unit_label = [];
    total_inventory_unit_value = [];
    total_ordered_value = [];
    for (var count = 0; count < InventoryOrderedUnitLineGraph.length; count++) {
        inventory_ordered_unit_label[count] = InventoryOrderedUnitLineGraph[count].by_date;
        total_inventory_unit_value[count] = parseInt(InventoryOrderedUnitLineGraph[count].inventory_units);
        total_ordered_value[count] = parseInt(InventoryOrderedUnitLineGraph[count].ordered_units);
    }
    setTimeout(function () {
        inventory_ordered_unit_chart.load({
            json: {
                'x': inventory_ordered_unit_label,
                'inventory_unit': total_inventory_unit_value,
                'ordered_unit': total_ordered_value,
            },
        });
    }, 1000);
}
function changeCampaignSpendByTypeValues(percentage) {
    total_campaign_graph_tooltip = [];
    for (var i = 0; i < percentage.length; i++) {
        if (percentage[i].product_report_type == 'sp') {
            total_campaign_spend_sp_value = percentage[i].spend_percentage;
            total_campaign_graph_tooltip[0] = percentage[i].spend;
        }
        if (percentage[i].product_report_type == 'sbv') {
            total_campaign_spend_sbv_value = percentage[i].spend_percentage;
            total_campaign_graph_tooltip[1] = percentage[i].spend;
        }
        if (percentage[i].product_report_type == 'sb') {
            total_campaign_spend_sb_value = percentage[i].spend_percentage;
            total_campaign_graph_tooltip[2] = percentage[i].spend;
        }
        if (percentage[i].product_report_type == 'sd') {
            total_campaign_spend_sd_value = percentage[i].spend_percentage;
            total_campaign_graph_tooltip[3] = percentage[i].spend;
        }
    }

    setTimeout(function () {
        spend_ad_type_chart.load({
            json: {
                'SP': total_campaign_spend_sp_value,
                'SBV': total_campaign_spend_sbv_value,
                'SB': total_campaign_spend_sb_value,
                'SD': total_campaign_spend_sd_value,
            },
        });
    }, 1000);
} // end function
function changeTotalAdSalesByTypeValues(percentage) {
    ad_sales_graph_tooltip = [];
    for (var i = 0; i < percentage.length; i++) {
        if (percentage[i].product_report_type == 'sp') {
            total_ad_sale_sp_value = percentage[i].sales_percentage;
            ad_sales_graph_tooltip[0] = percentage[i].sales;
        }
        if (percentage[i].product_report_type == 'sbv') {
            total_ad_sale_sbv_value = percentage[i].sales_percentage;
            ad_sales_graph_tooltip[1] = percentage[i].sales;
        }
        if (percentage[i].product_report_type == 'sb') {
            total_ad_sale_sb_value = percentage[i].sales_percentage;
            if (percentage[i].sales !== 'undefined')
                ad_sales_graph_tooltip[2] = percentage[i].sales;
        }
        if (percentage[i].product_report_type == 'sd') {
            total_ad_sale_sd_value = percentage[i].sales_percentage;
            ad_sales_graph_tooltip[3] = percentage[i].sales;
        }
    }
    setTimeout(function () {
        sales_ad_type_chart.load({
            json: {
                'SP': total_ad_sale_sp_value,
                'SB': total_ad_sale_sb_value,
                'SBV': total_ad_sale_sbv_value,
                'SD': total_ad_sale_sd_value,
            },
        });
    }, 1000);
} // end function

function orderedRevenueSpAdSales(OrderedRevenueSpAdSalesLineGraph) {
    total_ad_sales_value = [];
    Ordered_rev_adsales_label = [];
    total_ordered_revenue_value = [];
    for (var count = 0; count < OrderedRevenueSpAdSalesLineGraph.length; count++) {
        Ordered_rev_adsales_label[count] = OrderedRevenueSpAdSalesLineGraph[count].by_date;
        total_ordered_revenue_value[count] = parseInt(OrderedRevenueSpAdSalesLineGraph[count].ordered_revenue);
        total_ad_sales_value[count] = parseInt(OrderedRevenueSpAdSalesLineGraph[count].ad_sales);
    }
    setTimeout(function () {
        ordered_revenue_sp_adsales.load({
            json: {
                'x': Ordered_rev_adsales_label,
                'ordered_revenue': total_ordered_revenue_value,
                'sp_ad_sales': total_ad_sales_value,
            },
        });
    }, 1000);
}
function generateCategoryDetailDataTable(categoryData) {
    var html = "";
    html += "<table class='table tableCategory table-striped mb-0' id='tableCategory' style='1px solid #f8f8f8';>" +
        "<thead class='thead'>" +
        "<tr>" +
        "<th style='white-space: nowrap;'>CATEGORY<i class='fa fa-fw fa-sort' onclick='sortTable1(0)' ></i></th>" +
        "<th style='white-space: nowrap;'>TOTAL ORDERED REV<i class='fa fa-fw fa-sort' onclick='sortTable1(1)' ></i></th>" +
        "<th style='white-space: nowrap;'>GLANCE VIEWS<i class='fa fa-fw fa-sort' onclick='sortTable1(2)' ></i></th>" +
        "<th style='white-space: nowrap;'>CONVERSIONS<i class='fa fa-fw fa-sort' onclick='sortTable1(3)' ></i></th>" +
        "<th style='white-space: nowrap;'>SP AD SALES<i class='fa fa-fw fa-sort' onclick='sortTable1(4)' ></i></th>" +
        "<th style='white-space: nowrap;'>SP AD SPEND<i class='fa fa-fw fa-sort' onclick='sortTable1(5)' ></i></th>" +
        "<th style='white-space: nowrap;'>SP IMPRESSIONS<i class='fa fa-fw fa-sort' onclick='sortTable1(6)' ></i></th>" +
        "</tr>" +
        "</thead>" +
        "<tbody class='tbody'>";
    if (categoryData.length == 0) {
        html += "<tr>\n" +
            "    <td style='padding: 14px; white-space: nowrap;' align='center' colspan=7'>No data found</td>\n" +
            "</tr>";
    }
    var background_ordered_rev = "";
    var background_glance_view = "";
    var background_sp_ad_sales = "";
    let get_ordered_rev = [];
    let get_glance_view = [];
    let get_sp_ad_sales = [];
    var max_ordered_rev = 0;
    var max_glance_view = 0;
    var max_sp_ad_sales = 0;
    var col_percent_ordered_rev = 0;
    var col_percent_ordered_rev_left = 0;
    var col_percent_glance_view = 0;
    var col_percent_glance_view_left = 0;
    var col_percent_sp_ad_sales = 0;
    var col_percent_sp_ad_sales_left = 0;
    var ordered_revenue_sum = 0;
    var glance_view_sum = 0;
    var sp_ad_sales_sum = 0;
    var conversion_sum = 0;
    var sp_ad_spend_sum = 0;
    var sp_impression_sum = 0;
    var clicks_sum = 0;
    var orders_sum = 0;
    var conversion_rate_foot = 0;
    for (var count = 0; count < categoryData.length; count++) {
        get_ordered_rev[count] = Number(categoryData[count].total_ordered_rev);
        get_glance_view[count] = Number(categoryData[count].glance_views);
        get_sp_ad_sales[count] = Number(categoryData[count].sp_ad_sales);
    }
    max_ordered_rev = Math.max.apply(null, get_ordered_rev);
    max_glance_view = Math.max.apply(null, get_glance_view);
    max_sp_ad_sales = Math.max.apply(null, get_sp_ad_sales);
    for (var count = 0; count < categoryData.length; count++) {
        // column color percentage calculation for ordered rev
        col_percent_ordered_rev = Math.ceil(categoryData[count].total_ordered_rev / max_ordered_rev * 100);
        col_percent_ordered_rev_left = col_percent_ordered_rev - 100;
        if (document.body.classList.contains('dark-layout') == true) {
            background_ordered_rev = "background: linear-gradient(to right, #4DD0EA " + col_percent_ordered_rev + "%, #212744 " + col_percent_ordered_rev_left + "%);";
        } else {
            background_ordered_rev = "background: linear-gradient(to right, #4DD0EA " + col_percent_ordered_rev + "%, #f8f8f8 " + col_percent_ordered_rev_left + "%);";
        }
        // column color percentage calculation for glance_views
        col_percent_glance_view = Math.ceil(categoryData[count].glance_views / max_glance_view * 100);
        col_percent_glance_view_left = col_percent_glance_view - 100;
        if (document.body.classList.contains('dark-layout') == true) {
            background_glance_view = "background: linear-gradient(to right, #FFB371 " + col_percent_glance_view + "%, #212744 " + col_percent_glance_view_left + "%);";
        } else {
            background_glance_view = "background: linear-gradient(to right, #FFB371 " + col_percent_glance_view + "%, #f8f8f8 " + col_percent_glance_view_left + "%);";
        }
        // column color percentage calculation for sp_ad_sales
        col_percent_sp_ad_sales = Math.ceil(categoryData[count].sp_ad_sales / max_sp_ad_sales * 100);
        col_percent_sp_ad_sales_left = col_percent_sp_ad_sales - 100;
        if (document.body.classList.contains('dark-layout') == true) {
            background_sp_ad_sales = "background: linear-gradient(to right, #2693BE " + col_percent_sp_ad_sales + "%, #212744 " + col_percent_sp_ad_sales_left + "%);";
        } else {
            background_sp_ad_sales = "background: linear-gradient(to right, #2693BE " + col_percent_sp_ad_sales + "%, #f8f8f8 " + col_percent_sp_ad_sales_left + "%);";
        }
        if (categoryData[count].subcategory == null) {
            categoryData[count].subcategory = "-";
        }
        if (categoryData[count].ordered_revenue) {
            ordered_revenue_sum = Number(ordered_revenue_sum) + Number(categoryData[count].ordered_revenue);
        }
        if (categoryData[count].glance_views) {
            glance_view_sum = Number(glance_view_sum) + Number(categoryData[count].glance_views);
        }
        if (categoryData[count].conversion) {
            conversion_sum = Number(conversion_sum) + Number(categoryData[count].conversion);
        }
        if (categoryData[count].sp_ad_sales) {
            sp_ad_sales_sum = Number(sp_ad_sales_sum) + Number(categoryData[count].sp_ad_sales);
        }
        if (categoryData[count].sp_ad_spend) {
            sp_ad_spend_sum = Number(sp_ad_spend_sum) + Number(categoryData[count].sp_ad_spend);
        }
        if (categoryData[count].sp_impressions) {
            sp_impression_sum = Number(sp_impression_sum) + Number(categoryData[count].sp_impressions);
        }
        if (categoryData[count].clicks) {
            clicks_sum = Number(clicks_sum) + Number(categoryData[count].clicks);
        }
        if (categoryData[count].ordered_units) {
            orders_sum = Number(orders_sum) + Number(categoryData[count].ordered_units);
        }
        conversion_rate_foot = Number(orders_sum / clicks_sum * 100);
        html += "<tr>";
        html += "<td style='padding: 14px; white-space: nowrap;'>" + categoryData[count].subcategory + "</td>";
        html += "<td style='padding: 14px; white-space: nowrap;" + background_ordered_rev + "' >$" + numberWithCommas(Number(categoryData[count].ordered_revenue).toFixed(2)) + "</td>";
        html += "<td style='padding: 14px; white-space: nowrap;" + background_glance_view + "'>" + numberWithCommas(Number(categoryData[count].glance_views).toFixed(2)) + "</td>";
        html += "<td style='padding: 14px; white-space: nowrap;'>" + numberWithCommas(Number(categoryData[count].conversion).toFixed(2)) + "%</td>";
        html += "<td style='padding: 14px; white-space: nowrap;" + background_sp_ad_sales + "'>$" + numberWithCommas(Number(categoryData[count].sp_ad_sales).toFixed(2)) + "</td>";
        html += "<td style='padding: 14px; white-space: nowrap;'>$" + numberWithCommas(Number(categoryData[count].sp_ad_spend).toFixed(2)) + "</td>";
        html += "<td style='padding: 14px; white-space: nowrap;'>" + numberWithCommas(Number(categoryData[count].sp_impressions).toFixed(2)) + "</td>";
        html += "</tr>";

    }
    html += "</tbody>";
    html +=
        "<tfoot class='tfoot'>" +
        "<tr>" +
        "<td style='white-space: nowrap; font-size: small; '><b>TOTAL</b></td>" +
        "<td style='white-space: nowrap; font-size: small;' >$" + numberWithCommas(ordered_revenue_sum.toFixed(2)) + "</td>" +
        "<td style='white-space: nowrap; font-size: small;' >" + numberWithCommas(glance_view_sum.toFixed(2)) + "</td>" +
        "<td style='white-space: nowrap; font-size: small;'>" + numberWithCommas(conversion_rate_foot.toFixed(2)) + "%</td>" +
        "<td style='white-space: nowrap; font-size: small;' >$" + numberWithCommas(sp_ad_sales_sum.toFixed(2)) + "</td>" +
        "<td style='white-space: nowrap; font-size: small;' >$" + numberWithCommas(sp_ad_spend_sum.toFixed(2)) + "</td>" +
        "<td style='white-space: nowrap; font-size: small;'>" + numberWithCommas(sp_impression_sum.toFixed(2)) + "</td>" +
        "</tr>" +
        "</tfoot>" +
        "</table>";
    $('#category_detail_table').html(html);
}
function sortTable1(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("tableCategory");
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
(function () {
    var filter_vendor = $('#flywheel_filter_vendor').val();
    $("#product_info").select2({
        placeholder: '--Select Products--',
        // width: '350px',
        allowClear: true,
        minimumInputLength: 0,
        multiple: true,
        ajax: {
            url: base_url + '/flywheel/visual/dataforselect2',
            dataType: 'json',
            //delay: 250,
            data: function (params) {
                return {
                    term: params.term || '',
                    page: params.page || 1,
                    vendor: $('#flywheel_filter_vendor').val(),
                    range: $('#custom_data_value').val(),
                }

            },
            cache: false
        }
    });
    $("#asin_info").select2({
        placeholder: '--Select Asin--',
        // width: '350px',
        allowClear: true,
        minimumInputLength: 0,
        multiple: true,
        ajax: {
            url: base_url + '/flywheel/visual/dataforasinselect2',
            dataType: 'json',
            // delay: 250,
            data: function (params) {
                return {
                    term: params.term || '',
                    page: params.page || 1,
                    vendor: $('#flywheel_filter_vendor').val(),
                    range: $('#custom_data_value').val(),
                }

            },
            cache: false
        }
    });
    $("#category_info").select2({
        placeholder: '--Select Category--',
        // width: '350px',
        allowClear: true,
        minimumInputLength: 0,
        multiple: true,
        ajax: {
            url: base_url + '/flywheel/visual/dataforcategoryselect2',
            dataType: 'json',
            //  delay: 250,
            data: function (params) {
                return {
                    term: params.term || '',
                    page: params.page || 1,
                    vendor: $('#flywheel_filter_vendor').val(),
                    range: $('#custom_data_value').val(),
                }

            },
            cache: false
        }
    });

})();