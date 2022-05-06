document.addEventListener('DOMContentLoaded', function (e) {

    $("#filter_range_picker").datetimepicker({
        format: 'MM/DD/YYYY',
        maxDate: new Date(),
        minDate: '09/09/1999',
        viewMode: 'months',
        date: new Date(),
    });

    let value = $("#filter_range_picker").val();
    let firstDate = moment(value, "MM/DD/YYYY").day(3).format("MM/DD/YYYY");
    let lastDate = moment(value, "MM/DD/YYYY").day(9).format("MM/DD/YYYY");
    var date_filter_range_value = moment(value, "MM/DD/YYYY").day(3).format("MMM DD, YYYY")+" - "+moment(value, "MM/DD/YYYY").day(9).format("MMM DD, YYYY");
    if(moment(value, "MM/DD/YYYY").day() <= 2){
        lastDate = moment(value, "MM/DD/YYYY").day(2).format("MM/DD/YYYY");
        firstDate = moment(value, "MM/DD/YYYY").add('-1', 'week').day(3).format("MM/DD/YYYY");
        date_filter_range_value = moment(value, "MM/DD/YYYY").add('-1', 'week').day(3).format("MMM DD, YYYY")+" - "+moment(value, "MM/DD/YYYY").day(2).format("MMM DD, YYYY");
    }
    $("#ed_filter_date_range").val(firstDate + " - " + lastDate);
    $("#selected_date_text").html(date_filter_range_value);
    let filter_date_range = $('#ed_filter_date_range').val();
    let checkbox = document.getElementById("dollar-unit-switch").checked;
    let report_type = 0; //for dollar
    if (checkbox === false){
        report_type = 1; //for unit
    }
    generateEDReport(report_type, filter_date_range);

    //on Changing Dollar/Unit Toggle
    $('#dollar-unit-switch').on('click', function (ev, picker) {
        let filter_date_range = $('#ed_filter_date_range').val();
        let checkbox = document.getElementById("dollar-unit-switch").checked;
        let report_type = 0; //for dollar
        if (checkbox === false){
            report_type = 1; //for unit
        }
        generateEDReport(report_type, filter_date_range);
    });

    //on Changing Dollar/Unit Toggle
    $('#green_color_formatting_switch').on('click', function (ev, picker) {
        let checkbox = document.getElementById("green_color_formatting_switch").checked;
        $(".green_conditional_formatting").addClass("text-success");
        if (checkbox === false){
            $(".green_conditional_formatting").removeClass("text-success");
        }
    });

    //on Changing Dollar/Unit Toggle
    $('#orange_color_formatting_switch').on('click', function (ev, picker) {
        let checkbox = document.getElementById("orange_color_formatting_switch").checked;
        $(".orange_conditional_formatting").addClass("text-warning");
        if (checkbox === false){
            $(".orange_conditional_formatting").removeClass("text-warning");
        }
    });

    //on Changing Dollar/Unit Toggle
    $('#red_color_formatting_switch').on('click', function (ev, picker) {
        let checkbox = document.getElementById("red_color_formatting_switch").checked;
        $(".red_conditional_formatting").addClass("text-danger");
        if (checkbox === false){
            $(".red_conditional_formatting").removeClass("text-danger");
        }
    });

    //on Submitting Vendor
    $('#filter_form').on('submit', function (event) {
        event.preventDefault();

        let value = $("#filter_range_picker").val();
        var firstDate = moment(value, "MM/DD/YYYY").day(3).format("MM/DD/YYYY");
        var lastDate = moment(value, "MM/DD/YYYY").day(9).format("MM/DD/YYYY");
        var date_filter_range_value = moment(value, "MM/DD/YYYY").day(3).format("MMM DD, YYYY")+" - "+moment(value, "MM/DD/YYYY").day(9).format("MMM DD, YYYY");
        if(moment(value, "MM/DD/YYYY").day() <= 2){
            lastDate = moment(value, "MM/DD/YYYY").day(2).format("MM/DD/YYYY");
            firstDate = moment(value, "MM/DD/YYYY").add('-1', 'week').day(3).format("MM/DD/YYYY");
            date_filter_range_value = moment(value, "MM/DD/YYYY").add('-1', 'week').day(3).format("MMM DD, YYYY")+" - "+moment(value, "MM/DD/YYYY").day(2).format("MMM DD, YYYY");
        }
        $("#ed_filter_date_range").val(firstDate + " - " + lastDate);
        $("#selected_date_text").html(date_filter_range_value);
        let filter_date_range = $('#ed_filter_date_range').val();
        let checkbox = document.getElementById("dollar-unit-switch").checked;
        let report_type = 0; //for dollar
        if (checkbox === false){
            report_type = 1; //for unit
        }
        generateEDReport(report_type, filter_date_range);
    });

    $(document).on('click', '.graph_status', function(){
        let vendor_id = $(this).val();
        var status = document.getElementById("vendor"+vendor_id+"_graph_switch").checked;

        let filter_date_range = $('#ed_filter_date_range').val();
        $('#vendorGraphConfirmedPO').html('');
        let checkbox = document.getElementById("dollar-unit-switch").checked;
        let report_type = 0; //for dollar
        if (checkbox === false){
            report_type = 1; //for unit
        }
        if(status === true){
            generateVendorEDReport(vendor_id, report_type, filter_date_range);
        }else {
            removeVendorEDReport(vendor_id);
        }

    });

    function generateEDReport(report_type, filter_date_range){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ed/confirmPO/report",
            type: "POST",
            data: {
                type: report_type,
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
                    if (response.po_report[0]) {
                        confirmedPOYTD(response.po_report[0], report_type);
                    }else{
                        setTimeout(function () {
                            WeeklyConfirmedPO.internal.config.gauge_max = 100;
                            WeeklyConfirmedPO.internal.config.gauge_min = 0;
                            WeeklyConfirmedPO.load({
                                json: {
                                    'Plan Reached': 0,
                                },
                            });
                        }, 1000);
                    }
                    if (response.po_report_all_vendor) {
                        allVendorsWeeklyConfirmedPO(response.po_report_all_vendor, report_type);
                    }
                    if (response.po_confirmed_rate_all_vendor) {
                        allVendorsConfirmedPOPieChart(response.po_confirmed_rate_all_vendor);
                    }
                }
            },
        });
    }
    function generateVendorEDReport(vendor_id, report_type, filter_date_range) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ed/confirmPO/report/vendor",
            type: "POST",
            data: {
                date_range: filter_date_range,
                vendor_id: vendor_id,
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
                    $('#vendorGraphConfirmedPO').html('');
                    let vendor_confirmation_rate = response.vendor_confirmation_rate;
                    if (vendor_confirmation_rate.length != 0) {
                        vendorPOConfirmedRate(vendor_confirmation_rate, vendor_id);
                    }else{
                        Swal.fire({
                            title: "Record not found",
                            text: 'Try again, with different week or vendor',
                            type: "info",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        });
                    }
                }
            },
        });
    }
    function removeVendorEDReport(vendor_id) {
        let cardID = '#vendor'+vendor_id+'_card';
        let cardBody = '#vendor'+vendor_id+'_graph';
        $(cardBody).html('');
        $(cardID).attr("hidden", true);
    }

    let confirmation_rate_label = [];
    let confirmation_rate_value = [];
    var WeeklyConfirmedPO = c3.generate({
        bindto: d3.select('#po-confirmed'),
        data: {
            columns: [
                ['Plan Reached', 0]
            ],
            type: 'gauge',
        },
        gauge: {
            label: {
                show: true // to turn off the min/max labels.
            },
            min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
            max: 100, // 100 is default
            width: 70 // for adjusting arc thickness
        },
        color: {
            pattern: ['#00A5B5', '#E15829']
        },
        size: {
            height: 200
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
                y: undefined,
                step: undefined
            }
        },
        tooltip: {
            show: true,
            format: {
                value: function (value, ratio, id) {
                    return value+"%";
                }
            }
        },
    });
    var allVendorConfirmedPO = c3.generate({
        bindto: d3.select('#all_vendor_PO_confirmed_rate'),
        data: {
            x: 'x',
            json: {
                'x': confirmation_rate_label,
                'Confirmation Rate': confirmation_rate_value,
            },
            colors: {
                'Confirmation Rate': '#E15829',
            },
            types: {
                'Confirmation Rate': 'bar',
            },
            labels: {
                format: function (v, id, i, j) {
                    return v + '%';
                }
            }
        },
        axis: {
            x: {
                type: 'category',
                tick: {
                    rotate: -80,
                    multiline: false,
                },
                height: 130,
            },
            y: {
                show: false,
                tick: {
                    format: function (v, id, i, j) {
                        return v + '%';
                    }
                }
            },
        },
        size: {
            height: 250
        },
        zoom: {
            enabled: true
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
            show: true,
            position: 'inset',
            inset: {
                anchor: 'top-right',
                x: undefined,
                y: undefined,
                step: undefined
            }
        },
        tooltip: {
            show: true,
        },
    });

    function confirmedPOYTD(po_report, report_type) {
        let value1 = 0;

        let gauge_min = 0;
        let gauge_max = 100;
        let gauge_value1 = 0;
        switch (report_type) {
            case 0:
                po_report.po_ytd_value != null ? $('#confirm_po_ytd').html(po_report.po_ytd_value)  : $('#confirm_po_ytd').html('-');
                po_report.po_percent != null ? value1 = po_report.po_percent : value1 = 0;
                break;
            case 1:
                po_report.po_ytd_units != null ? $('#confirm_po_ytd').html(po_report.po_ytd_units) : $('#confirm_po_ytd').html('-');
                po_report.po_percent != null ? value1 = po_report.po_percent : value1 = 0;
                break;
            default:
                break;
        }


        gauge_value1 = parseInt(value1);

        if(gauge_value1 < 0 ){
            gauge_min = ((parseInt(gauge_value1/100))*100) - 100;
        }
        if(gauge_value1 > 100 ){
            gauge_max = ((parseInt(gauge_value1/100))*100) + 100;
        }

        setTimeout(function () {
            WeeklyConfirmedPO.internal.config.gauge_max = gauge_max;
            WeeklyConfirmedPO.internal.config.gauge_min = gauge_min;
            WeeklyConfirmedPO.load({
                json: {
                    'Plan Reached': value1,
                },
            });
        }, 1000);
    }
    function vendorPOConfirmedRate(vendor_data, vendor_id) {
        let cardID = '#vendor'+vendor_id+'_card';
        let cardTitle = '#vendor'+vendor_id+'_name';
        let cardBody = '#vendor'+vendor_id+'_graph';

        let record1 = [];
        let record2 = [];
        let label = [];
        let date_range = [];
        var vendor1_PO_confirmed_rate = c3.generate({
            bindto: d3.select(cardBody),
            data: {
                x: 'x',
                json: {
                    'x': label,
                    'Total Cases': record1,
                    'Confirmation Rate': record2,
                },
                types: {
                    'Total Cases': 'bar',
                    'Confirmation Rate': 'bar',
                },
                axes: {
                    'Confirmation Rate': 'y2'
                },
            },
            axis: {
                x: {
                    type: 'category',
                    tick: {
                        rotate: -80,
                        multiline: false,
                    },
                    height: 50,
                },
                y: {
                    show: true,
                    label: {
                        text: 'Total Cases',
                        position: 'outer-middle'
                    },
                    tick: {
                        format: d3.format(',')
                    }
                },
                y2: {
                    show: true,
                    label: {
                        text: 'Confirmation Rate',
                        position: 'outer-middle'
                    },
                    tick: {
                        format: function (d) { return d+"%"; }
                    }
                },
            },
            bar: {
                width: {
                    ratio: 0.8 // this makes bar width 50% of length between ticks
                }
            },
            color: {
                pattern: ['#00A5B5', '#E15829']
            },
            size: {
                height: 250
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
                    y: undefined,
                    step: undefined
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

        for (var count = 0; count < vendor_data.length; count++) {
            if(vendor_data[count].vendor_name != null){
                $(cardTitle).html(vendor_data[count].vendor_name);
                record1[count] = vendor_data[count].total_cases;
                record2[count] = vendor_data[count].confirmation_rate;
                label[count] = "Week "+vendor_data[count].week;
                date_range[count] = vendor_data[count].range;
            }
        }

        setTimeout(function () {
            $(cardID).attr("hidden", false);
        }, 500);

        setTimeout(function () {
            vendor1_PO_confirmed_rate.load({
                json: {
                    'x': label,
                    'Total Cases': record1,
                    'Confirmation Rate': record2,
                },
            });
        }, 1000);

    }
    function allVendorsWeeklyConfirmedPO(po_report_all_vendor, report_type) {
        let html = '';
        $('#vendor_graphs').html(html);
        if(po_report_all_vendor.length == 0){
            html =  "<tr>\n" +
                "    <td style='padding: 10px; white-space: nowrap;' align='center' colspan='10'>No data found</td>\n" +
                "</tr>";

            $('#current_week').html("Current Week");
            $('#week_1').html("Week #");
            $('#week_2').html("Week #");
            $('#week_3').html("Week #");

            (report_type == 0)? $('.dollar_unit_label').html("Dollar"): $('.dollar_unit_label').html("Unit");

            $('#all_vendors_weekly_confirmed_PO').html(html);
            return;
        }

        let current_week_info = "-";
        let week_1_info = "-";
        let week_2_info = "-";
        let week_3_info = "-";

        for (let i = 0; i < po_report_all_vendor.length; i++) {
            if(po_report_all_vendor[i].current_range != null){
                current_week_info = "Current Week<br/>" + po_report_all_vendor[i].current_range;
            }
            if(po_report_all_vendor[i].week_1_range != null){
                week_1_info = "Week " +po_report_all_vendor[i].week_1 + "<br/>" + po_report_all_vendor[i].week_1_range;
            }
            if(po_report_all_vendor[i].week_2_range != null){
                week_2_info = "Week " +po_report_all_vendor[i].week_2 + "<br/>" + po_report_all_vendor[i].week_2_range;
            }
            if(po_report_all_vendor[i].week_3_range != null){
                week_3_info = "Week " +po_report_all_vendor[i].week_3 + "<br/>" + po_report_all_vendor[i].week_3_range;
            }
        }

        $('#current_week').html(current_week_info);
        $('#week_1').html(week_1_info);
        $('#week_2').html(week_2_info);
        $('#week_3').html(week_3_info);

        let cardData = "";

        switch (report_type) {
            case 0:
                $('.dollar_unit_label').html("Dollar");
                for (var count = 0; count < po_report_all_vendor.length; count++) {

                    //get conditional class style for all weeks
                    let current_week_style = getConditionalClassStyle(po_report_all_vendor[count].current_confirmation_rate);
                    let week_1_style = getConditionalClassStyle(po_report_all_vendor[count].week_1_confirmation_rate);
                    let week_2_style = getConditionalClassStyle(po_report_all_vendor[count].week_2_confirmation_rate);
                    let week_3_style = getConditionalClassStyle(po_report_all_vendor[count].week_3_confirmation_rate);

                    html += "<tr>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].vendor_name+"</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].current_total_cost+"</td>\n"+
                        "        <td class='text-center "+current_week_style+"' style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].current_confirmation_rate+"</td>\n"+
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].week_1_total_cost+"</td>\n" +
                        "        <td class='text-center "+week_1_style+"' style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].week_1_confirmation_rate+"</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].week_2_total_cost+"</td>\n" +
                        "        <td class='text-center "+week_2_style+"' style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].week_2_confirmation_rate+"</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].week_3_total_cost+"</td>\n" +
                        "        <td class='text-center "+week_3_style+"' style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].week_3_confirmation_rate+"</td>\n" +
                        "        <td class='text-center'  style='padding: 10px; white-space: nowrap;'>" +
                        "               <div class='custom-control custom-switch custom-control-inline'>\n" +
                        "                   <input type='checkbox' class='graph_status custom-control-input' name='graph_status' id='vendor"+po_report_all_vendor[count].vendor_id+"_graph_switch' value='"+po_report_all_vendor[count].vendor_id+"'>\n" +
                        "                   <label class='custom-control-label' for='vendor"+po_report_all_vendor[count].vendor_id+"_graph_switch'>\n" +
                        "                   </label>\n" +
                        "               </div>"+
                        "       </td>\n"+
                        "</tr>";

                    cardData +="<div class='col-xl-4 col-md-6 col-sm-12' id='vendor"+po_report_all_vendor[count].vendor_id+"_card' hidden>" +
                        "         <div class='card' >\n" +
                        "            <div class='card-header d-flex justify-content-center align-items-center pb-0'>\n" +
                        "                <h4 class='card-title' id='vendor"+po_report_all_vendor[count].vendor_id+"_name'>"+po_report_all_vendor[count].vendor_name+"</h4>\n" +
                        "            </div>\n" +
                        "            <div class='card-content collapse show'>\n" +
                        "            <div class='card-body'>\n" +
                        "                <div id='vendor"+po_report_all_vendor[count].vendor_id+"_graph' width='100%'></div>\n" +
                        "                </div>\n" +
                        "            </div>\n" +
                        "         </div>" +
                        "      </div>";

                }
                break;
            case 1:
                $('.dollar_unit_label').html("Unit");
                for (var count = 0; count < po_report_all_vendor.length; count++) {

                    //get conditional class style for all weeks
                    let current_week_style = getConditionalClassStyle(po_report_all_vendor[count].current_confirmation_rate);
                    let week_1_style = getConditionalClassStyle(po_report_all_vendor[count].week_1_confirmation_rate);
                    let week_2_style = getConditionalClassStyle(po_report_all_vendor[count].week_2_confirmation_rate);
                    let week_3_style = getConditionalClassStyle(po_report_all_vendor[count].week_3_confirmation_rate);

                    html += "<tr>\n" +
                        "        <td style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].vendor_name+"</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].current_cases+"</td>\n" +
                        "        <td class='text-center "+current_week_style+"' style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].current_confirmation_rate+"</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].week_1_cases+"</td>\n" +
                        "        <td class='text-center "+week_1_style+"' style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].week_1_confirmation_rate+"</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].week_2_cases+"</td>\n" +
                        "        <td class='text-center "+week_2_style+"' style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].week_2_confirmation_rate+"</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].week_3_cases+"</td>\n" +
                        "        <td class='text-center "+week_3_style+"' style='padding: 10px; white-space: nowrap;'>"+po_report_all_vendor[count].week_3_confirmation_rate+"</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" +
                        "               <div class='custom-control custom-switch custom-control-inline'>\n" +
                        "                   <input type='checkbox' class='graph_status custom-control-input' name='graph_status' id='vendor"+po_report_all_vendor[count].vendor_id+"_graph_switch' value='"+po_report_all_vendor[count].vendor_id+"'>\n" +
                        "                   <label class='custom-control-label' for='vendor"+po_report_all_vendor[count].vendor_id+"_graph_switch'>\n" +
                        "                   </label>\n" +
                        "               </div>"+
                        "       </td>\n"+
                        "</tr>";

                    cardData +="<div class='col-xl-4 col-md-6 col-sm-12' id='vendor"+po_report_all_vendor[count].vendor_id+"_card' hidden>" +
                        "         <div class='card'>\n" +
                        "            <div class='card-header d-flex justify-content-center align-items-center pb-0'>\n" +
                        "                <h4 class='card-title' id='vendor"+po_report_all_vendor[count].vendor_id+"_name'>"+po_report_all_vendor[count].vendor_name+"</h4>\n" +
                        "            </div>\n" +
                        "            <div class='card-content collapse show'>\n" +
                        "            <div class='card-body'>\n" +
                        "                <div id='vendor"+po_report_all_vendor[count].vendor_id+"_graph' width='100%'></div>\n" +
                        "                </div>\n" +
                        "            </div>\n" +
                        "        </div>" +
                        "      </div>";
                }
                break;
            default:
                break;
        }

        // add table rows
        $('#all_vendors_weekly_confirmed_PO').html(html);
        // add vendor graph cards
        $('#all_vendors_cards').html(cardData);

        // change percentage values class accordingly after adding table rows
        $(".green_conditional_formatting").addClass("text-success");
        if (document.getElementById("green_color_formatting_switch").checked === false){
            $(".green_conditional_formatting").removeClass("text-success");
        }

        $(".orange_conditional_formatting").addClass("text-warning");
        if (document.getElementById("orange_color_formatting_switch").checked === false){
            $(".orange_conditional_formatting").removeClass("text-warning");
        }

        $(".red_conditional_formatting").addClass("text-danger");
        if (document.getElementById("red_color_formatting_switch").checked === false){
            $(".red_conditional_formatting").removeClass("text-danger");
        }

    }
    function getConditionalClassStyle(percentage) {
        let style = "red_conditional_formatting";
        let value = (percentage).replace(new RegExp("\\s|,|\\%", "gm"), "");
        if (value > 80 && value <= 100){
            style = "green_conditional_formatting";
        }else if (value > 75 && value <= 80){
            style = "orange_conditional_formatting";
        }
        return style;
    }
    function allVendorsConfirmedPOPieChart(po_confirmed_rate_all_vendor) {
        confirmation_rate_label = [];
        confirmation_rate_value = [];
        let average_confirmation_rate = "-";
        let total = po_confirmed_rate_all_vendor.length;
        let value = 0;
        var i = 0;
        for (var count = 0; count < po_confirmed_rate_all_vendor.length; count++) {
            confirmation_rate_value[i] = parseInt(po_confirmed_rate_all_vendor[count].confirmation_rate);
            confirmation_rate_label[i] = po_confirmed_rate_all_vendor[count].vendor_name;
            i++;
            value += parseFloat(po_confirmed_rate_all_vendor[count].confirmation_rate);
            if(po_confirmed_rate_all_vendor[count].confirmation_rate == "0.00"){
                total -= 1;
            }
        }
        if(total != 0){
            average_confirmation_rate = (value/total).toFixed(2);
        }
        $('#average_confirmation_rate').html(average_confirmation_rate+" %");
        setTimeout(function () {
            allVendorConfirmedPO.load({
                json: {
                    'x': confirmation_rate_label,
                    'Confirmation Rate': confirmation_rate_value,
                },
            });
        }, 500);
    }
});
