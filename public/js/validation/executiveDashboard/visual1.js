document.addEventListener('DOMContentLoaded', function (e) {
    $("#filter_range_picker").datetimepicker({

        minDate: '09/09/1999',
        maxDate: new Date(),
        viewMode: 'months',
        format: 'MMM-YYYY',
        date: new Date(),
    });

    let value = $("#filter_range_picker").val();
    let firstDate = moment(value, "MMM-YYYY").startOf('month').format("MM/DD/YYYY");
    let lastDate = moment(value, "MMM-YYYY").endOf('month').format("MM/DD/YYYY");
    $("#selected_date_text").html(moment(new Date()).startOf('month').format("MMMM YYYY"));
    $("#ed_filter_date_range").val(firstDate + " - " + lastDate);

    let filter_date_range = $('#ed_filter_date_range').val();
    let checkbox = document.getElementById("dollar-unit-switch").checked;
    let report_type = 0; //for dollar
    if (checkbox === false) {
        report_type = 1; //for unit
    }
    let marketplace = $('#marketplace').val();
    let checkboxTable = document.getElementById("mtd-ytd-nc-sc-switch").checked;
    let report_type_table = 0; //for MTD
    if (checkboxTable === false) {
        report_type_table = 1; //for YTD
    }
    //initial load SP call
    generateEDReport(report_type, filter_date_range);
    generateVendorEDReport(report_type, filter_date_range);
    getScNcMYTD(marketplace, report_type_table, report_type, filter_date_range);
    setEDVendor();

    $("#vendor_filter_vendor").select2({
        dropdownParent: $("#ed_vendor_form"),
        language: {
            noResults: function (e) {
                return "No vendor found";
            },
        }
    });

    $(document).on('click', '.merge_table', function () {

        let vendor_id = $('#get_id').val();
        let filter_date_range = $('#ed_filter_date_range').val();
        let checkbox = document.getElementById("dollar-unit-switch").checked;
        let report_type = 0; //for dollar
        if (checkbox === false) {
            report_type = 1; //for unit
        }
        let marketplace = $('#marketplace').val();
        let checkboxTable = document.getElementById("mtd-ytd-nc-sc-switch").checked;
        let report_type_table = 0; //for MTD
        if (checkboxTable === false) {
            report_type_table = 1; //for YTD
        }

        getScNcTrailing('vendor_sc_nc_mtd', vendor_id, report_type);


    });
    // for 3p table
    $(document).on('click', '.merge_table_3p', function () {

        let vendor_id = $('#get_id_3p').val();
        let filter_date_range = $('#ed_filter_date_range').val();
        let checkbox = document.getElementById("dollar-unit-switch").checked;
        let report_type = 0; //for dollar
        if (checkbox === false) {
            report_type = 1; //for unit
        }
        let marketplace = $('#marketplace').val();
        let checkboxTable = document.getElementById("mtd-ytd-sc-switch-3p-merge").checked;
        let report_type_table = 0; //for MTD
        if (checkboxTable === false) {
            report_type_table = 1; //for YTD
        }
        getScNcTrailing('vendor_sc_nc_mtd', vendor_id, report_type);


    });
    //on Changing Dollar/Unit Toggle
    $('#dollar-unit-switch').on('click', function (ev, picker) {
        let filter_date_range = $('#ed_filter_date_range').val();
        let checkbox = document.getElementById("dollar-unit-switch").checked;
        let report_type = 0; //for dollar
        if (checkbox === false) {
            report_type = 1; //for unit
        }
        let marketplace = $('#marketplace').val();
        let checkboxTable = document.getElementById("mtd-ytd-nc-sc-switch").checked;
        let report_type_table = 0; //for MTD
        if (checkboxTable === false) {
            report_type_table = 1; //for YTD
        }
        generateEDReport(report_type, filter_date_range);
        getScNcMYTD(marketplace, report_type_table, report_type, filter_date_range);
        generateVendorEDReport(report_type, filter_date_range);
    });
    //on Changing YTD/MTD Toggle
    $('#mtd-ytd-nc-sc-switch').on('click', function (ev, picker) {
        let filter_date_range = $('#ed_filter_date_range').val();
        let marketplace = $('#marketplace').val();
        let checkbox = document.getElementById("dollar-unit-switch").checked;
        let report_type = 0; //for dollar
        if (checkbox === false) {
            report_type = 1; //for unit
        }
        let checkboxTable = document.getElementById("mtd-ytd-nc-sc-switch").checked;
        let report_type_table = 0; //for MTD
        if (checkboxTable === false) {
            report_type_table = 1; //for YTD
        }
        getScNcMYTD(marketplace, report_type_table, report_type, filter_date_range);
    });
    //on Changing YTD/MTD Toggle
    $('#mtd-ytd-sc-switch-3p-merge').on('click', function (ev, picker) {
        let filter_date_range = $('#ed_filter_date_range').val();
        let marketplace = $('#marketplace').val();
        let checkbox = document.getElementById("dollar-unit-switch").checked;
        let report_type = 0; //for dollar
        if (checkbox === false) {
            report_type = 1; //for unit
        }
        let checkboxTable = document.getElementById("mtd-ytd-sc-switch-3p-merge").checked;
        let report_type_table = 0; //for MTD
        if (checkboxTable === false) {
            report_type_table = 1; //for YTD
        }
        getScNcMYTD(marketplace, report_type_table, report_type, filter_date_range);
    });
    //new function for merge tables
    function getScNcMYTD(marketplace, report_type_table, report_type, filter_date_range) {
        let checkboxTable3p = document.getElementById("mtd-ytd-sc-switch-3p-merge").checked;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ed/table/all",
            type: "POST",
            data: {
                type: report_type,
                date_range: filter_date_range,
                marketplace_value: marketplace,
                tooggleTableSc: report_type_table,
                tooggleTableSc3p: checkboxTable3p,
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
                    if (response.shippedCogsNcTable) {
                        if (response.check == '(3P)') {
                            shippedCogs3pTable(response.shippedCogsNcTable, report_type, filter_date_range);
                            if (response.shippedCogsNCGrandTotal) {
                                shippedCogsNcMergeGrandTotal(response.shippedCogsNCGrandTotal, report_type);
                            }
                        } else {
                            shippedCogsNcMergeTable(response.shippedCogsNcTable, report_type, filter_date_range);
                            if (response.shippedCogsNCGrandTotal) {
                                shippedCogsNcMergeGrandTotal(response.shippedCogsNCGrandTotal, report_type);
                            }
                        }
                    }
                }
            },
        });
    }
    $('#vendor_filter_vendor').on('change', function () {
        setEDVendor();
    });
    function setEDVendor() {
        let filter_vendor = $('#vendor_filter_vendor').val();
        if (filter_vendor == null) {
            filter_vendor = 0;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ed/vendor/store",
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
                    $('#vendor_name_card').html(response.vendor);
                    $('#vendor_name_card_mtd').html(response.vendor);
                    $('#vendor_name_card_3p').html(response.vendor);
                    $('#vendor_name_card_mtd_3p').html(response.vendor);

                    let value = $("#filter_range_picker").val();
                    let firstDate = moment(value, "MMM-YYYY").startOf('month').format("MM/DD/YYYY");
                    let lastDate = moment(value, "MMM-YYYY").endOf('month').format("MM/DD/YYYY");
                    $("#ed_filter_date_range").val(firstDate + " - " + lastDate);

                    let filter_date_range = $('#ed_filter_date_range').val();
                    let checkbox = document.getElementById("dollar-unit-switch").checked;
                    let report_type = 0; //for dollar
                    if (checkbox === false) {
                        report_type = 1; //for unit
                    }
                    let marketplace = $('#marketplace').val();
                    let checkboxTable = document.getElementById("mtd-ytd-nc-sc-switch").checked;
                    let report_type_table = 0; //for MTD
                    if (checkboxTable === false) {
                        report_type_table = 1; //for YTD
                    }
                    generateEDReport(report_type, filter_date_range);
                    generateVendorEDReport(report_type, filter_date_range);
                    getScNcMYTD(marketplace, report_type_table, report_type, filter_date_range);
                }
            },
        });
    }
    $('#marketplace').on('change', function () {
        getEDVendorForMarketplace();// for updating vendor field
    });
    // for updating vendor field
    function getEDVendorForMarketplace() {
        let marketplace_vendor = $('#marketplace').val();
        if (marketplace_vendor == null) {
            marketplace_vendor = 0;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ed/get/vendor",
            type: "POST",
            data: {
                marketplace_value: marketplace_vendor,
            },
            dataType: "json",
            success: function (data) {
                $("#vendor_filter_vendor").empty();
                allVendors = data.allVendors;
                oneP = data.oneP;
                threeP = data.threeP;
                let html_platinum = "<optgroup label='Platinum Vendors'></optgroup>";//total length 46
                let html_silver = "<optgroup label='Silver Vendors'></optgroup>";//total length 44
                let html_gold = "<optgroup label='Gold Vendors'></optgroup>";//total length 42
                let html_threeP = "<optgroup label='3P Vendors'></optgroup>";//total length 40
                if (allVendors.length > 0) {
                    for (let count = 0; count < allVendors.length; count++) {
                        if (allVendors[count].tier == 'Platinum' && allVendors[count].marketplace == '1P') {
                            html_platinum += "<option value='" + allVendors[count].rdm_vendor_id + "'>" + allVendors[count].vendor_alias + "</option>";
                        }
                        if (allVendors[count].tier == 'Silver' && allVendors[count].marketplace == '1P') {
                            html_silver += "<option value='" + allVendors[count].rdm_vendor_id + "'>" + allVendors[count].vendor_alias + "</option>";
                        }
                        if (allVendors[count].tier == 'Gold' && allVendors[count].marketplace == '1P') {
                            html_gold += "<option value='" + allVendors[count].rdm_vendor_id + "'>" + allVendors[count].vendor_alias + "</option>";
                        }
                        if (allVendors[count].tier == 'Platinum' && allVendors[count].marketplace == '3P') {
                            html_platinum += "<option value='" + allVendors[count].rdm_vendor_id + "'>" + allVendors[count].vendor_alias + "</option>";
                        }
                        if (allVendors[count].tier == 'Silver' && allVendors[count].marketplace == '3P') {
                            html_silver += "<option value='" + allVendors[count].rdm_vendor_id + "'>" + allVendors[count].vendor_alias + "</option>";
                        }
                        if (allVendors[count].tier == 'Gold' && allVendors[count].marketplace == '3P') {
                            html_gold += "<option value='" + allVendors[count].rdm_vendor_id + "'>" + allVendors[count].vendor_alias + "</option>";
                        }
                        if (allVendors[count].tier == '(3P)' && allVendors[count].marketplace == '3P') {
                            html_threeP += "<option value='" + allVendors[count].rdm_vendor_id + "'>" + allVendors[count].vendor_alias + "</option>";
                        }
                    }

                }
                if (oneP.length > 0) {
                    for (let count = 0; count < oneP.length; count++) {
                        if (oneP[count].tier == 'Platinum' && oneP[count].marketplace == '1P') {
                            html_platinum += "<option value='" + oneP[count].rdm_vendor_id + "'>" + oneP[count].vendor_alias + "</option>";
                        }
                        if (oneP[count].tier == 'Silver' && oneP[count].marketplace == '1P') {
                            html_silver += "<option value='" + oneP[count].rdm_vendor_id + "'>" + oneP[count].vendor_alias + "</option>";
                        }
                        if (oneP[count].tier == 'Gold' && oneP[count].marketplace == '1P') {
                            html_gold += "<option value='" + oneP[count].rdm_vendor_id + "'>" + oneP[count].vendor_alias + "</option>";
                        }
                    }
                }
                if (threeP.length > 0) {
                    for (let count = 0; count < threeP.length; count++) {
                        if (threeP[count].tier == 'Platinum' && threeP[count].marketplace == '3P') {
                            html_platinum += "<option value='" + threeP[count].rdm_vendor_id + "'>" + threeP[count].vendor_alias + "</option>";
                        }
                        if (threeP[count].tier == 'Silver' && threeP[count].marketplace == '3P') {
                            html_silver += "<option value='" + threeP[count].rdm_vendor_id + "'>" + threeP[count].vendor_alias + "</option>";
                        }
                        if (threeP[count].tier == 'Gold' && threeP[count].marketplace == '3P') {
                            html_gold += "<option value='" + threeP[count].rdm_vendor_id + "'>" + threeP[count].vendor_alias + "</option>";
                        }
                        if (threeP[count].tier == '(3P)' && threeP[count].marketplace == '3P') {
                            html_gold += "<option value='" + threeP[count].rdm_vendor_id + "'>" + threeP[count].vendor_alias + "</option>";
                        }
                    }
                }

                if (html_platinum.length === 46) { //check if only label set then empty variable
                    html_platinum = "";
                }
                if (html_silver.length === 44) { //check if only label set then empty variable
                    html_silver = "";
                }
                if (html_gold.length === 42) { //check if only label set then empty variable
                    html_gold = "";
                }
                if (html_threeP.length === 40) { //check if only label set then empty variable
                    html_threeP = "";
                }
                $('#vendor_filter_vendor').html(html_platinum + html_silver + html_gold + html_threeP);
                setEDVendor();
            },
        });
    }

    //on Submitting Vendor
    $('#ed_vendor_form').on('submit', function (event) {
        event.preventDefault();
        let value = $("#filter_range_picker").val();
        let firstDate = moment(value, "MMM-YYYY").startOf('month').format("MM/DD/YYYY");
        let lastDate = moment(value, "MMM-YYYY").endOf('month').format("MM/DD/YYYY");
        $("#ed_filter_date_range").val(firstDate + " - " + lastDate);
        $("#selected_date_text").html(moment(firstDate).startOf('month').format("MMMM YYYY"));
        let filter_date_range = $('#ed_filter_date_range').val();
        let checkbox = document.getElementById("dollar-unit-switch").checked;
        let report_type = 0; //for dollar
        if (checkbox === false) {
            report_type = 1; //for unit
        }
        let marketplace = $('#marketplace').val();
        let checkboxTable = document.getElementById("mtd-ytd-nc-sc-switch").checked;
        let report_type_table = 0; //for MTD
        if (checkboxTable === false) {
            report_type_table = 1; //for YTD
        }

        getScNcMYTD(marketplace, report_type_table, report_type, filter_date_range);
        generateEDReport(report_type, filter_date_range);
        generateVendorEDReport(report_type, filter_date_range);
    });

    function generateEDReport(report_type, filter_date_range) {
        let marketplace = $('#marketplace').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ed/report",
            type: "POST",
            data: {
                type: report_type,
                date_range: filter_date_range,
                marketplace_value: marketplace,
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
                    if (response.check == '(3P)') {
                        // For 3P Design hide existing ED and show hidden 3p ED
                        $("#ED_3p").attr("hidden", false);
                        $("#ED_1p").attr("hidden", true);

                    } else {
                        $("#ED_3p").attr("hidden", true);
                        $("#ED_1p").attr("hidden", false);
                    }
                    if (response.SC_YTD[0]) {
                        shippedCOGSYTD(response.SC_YTD[0], report_type);
                    }
                    if (response.NR_YTD[0]) {
                        netReceiptsYTD(response.NR_YTD[0], report_type);
                    }
                    if (response.SC_MTD[0]) {
                        shippedCOGSMTD(response.SC_MTD[0], report_type);
                    }
                    if (response.NR_MTD[0]) {
                        netReceiptsMTD(response.NR_MTD[0], report_type);
                    }
                    if (response.orderedProductYtd[0]) {
                        orderedProductYTD(response.orderedProductYtd[0], report_type);
                    }
                    if (response.orderedProductMtd[0]) {
                        orderedProductMTD(response.orderedProductMtd[0], report_type);
                    }
                }
            },
        });
    }
    function generateVendorEDReport(report_type, filter_date_range) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: base_url + "/ed/vendor/report",
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
                    if (response.vendorDetailSC[0]) {
                        generateVendorDetailSC(response.vendorDetailSC[0], report_type, response.vendorAlerts);
                    } else {
                        if (report_type == 0) {
                            setTimeout(function () {
                                vendor_shipped_cogs_ytd.internal.config.gauge_max = 100;
                                vendor_shipped_cogs_ytd.internal.config.gauge_min = 0;
                                vendor_shipped_cogs_ytd.load({
                                    columns: [
                                        ['PTP', 0],
                                        ['Shipped COGS', 0],
                                    ],
                                });
                                vendor_shipped_cogs_ytd.unload({
                                    ids: ['Shipped Units']
                                });
                            }, 1000);
                            $("#vendor_shipped_cog_ytd_card").removeAttr("style");
                            $('#vendor_sc_type').html("Shipped COGS");
                            $('#vendor_sc_value').html("-");
                            $('#vendor_ptp_sc_value').html("-");
                            var col = document.getElementById("vendor_ptp_sc_ytd_percentage");
                            if (document.body.classList.contains('dark-layout') == true) {
                                col.style.color = "#c2c6dc";
                            } else {
                                col.style.color = "#626262";
                            }
                            $('#vendor_ptp_sc_ytd_percentage').html("%");
                        } else {
                            setTimeout(function () {
                                vendor_shipped_cogs_ytd.internal.config.gauge_max = 100;
                                vendor_shipped_cogs_ytd.internal.config.gauge_min = 0;
                                vendor_shipped_cogs_ytd.load({
                                    columns: [
                                        ['PTP', 0],
                                        ['Shipped Units', 0],
                                    ],
                                });
                                vendor_shipped_cogs_ytd.unload({
                                    ids: ['Shipped COGS']
                                });
                            }, 1000);
                            $("#vendor_shipped_cog_ytd_card").removeAttr("style");
                            $('#vendor_sc_type').html("Shipped Units");
                            $('#vendor_sc_value').html("-");
                            $('#vendor_ptp_sc_value').html("-");
                            var col = document.getElementById("vendor_ptp_sc_ytd_percentage");
                            if (document.body.classList.contains('dark-layout') == true) {
                                col.style.color = "#c2c6dc";
                            } else {
                                col.style.color = "#626262";
                            }
                            $('#vendor_ptp_sc_ytd_percentage').html("%");
                        }

                    }
                    if (response.vendorDetailNR[0]) {
                        generateVendorDetailNR(response.vendorDetailNR[0], report_type, response.vendorAlerts);
                    } else {
                        if (report_type == 0) {
                            setTimeout(function () {
                                vendor_net_receipts_ytd.internal.config.gauge_max = 100;
                                vendor_net_receipts_ytd.internal.config.gauge_min = 0;
                                vendor_net_receipts_ytd.load({
                                    columns: [
                                        ['PTP', 0],
                                        ['Net Received', 0],
                                    ],
                                });
                                vendor_net_receipts_ytd.unload({
                                    ids: ['Net Received Units']
                                });
                            }, 1000);
                            $('#vendor_nr_type').html("Net Receipts");
                            $('#vendor_nr_value').html("-");
                            $('#vendor_ptp_nr_value').html("-");
                            var col = document.getElementById("vendor_ptp_nr_ytd_percentage");
                            if (document.body.classList.contains('dark-layout') == true) {
                                col.style.color = "#c2c6dc";
                            } else {
                                col.style.color = "#626262";
                            }
                            $('#vendor_ptp_nr_ytd_percentage').html("%");
                        } else {
                            setTimeout(function () {
                                vendor_net_receipts_ytd.internal.config.gauge_max = 100;
                                vendor_net_receipts_ytd.internal.config.gauge_min = 0;
                                vendor_net_receipts_ytd.load({
                                    columns: [
                                        ['PTP', 0],
                                        ['Net Received Units', 0]
                                    ],
                                });
                                vendor_net_receipts_ytd.unload({
                                    ids: ['Net Received']
                                });
                            }, 1000);
                            $('#vendor_nr_type').html("Net Received Units");
                            $('#vendor_nr_value').html("-");
                            $('#vendor_ptp_nr_value').html("-");
                            var col = document.getElementById("vendor_ptp_nr_ytd_percentage");
                            if (document.body.classList.contains('dark-layout') == true) {
                                col.style.color = "#c2c6dc";
                            } else {
                                col.style.color = "#626262";
                            }
                            $('#vendor_ptp_nr_ytd_percentage').html("%");
                        }

                    }
                    if (response.vendorDetailROAS[0]) {
                        generateVendorDetailROAS(response.vendorDetailROAS[0], report_type, response.vendorAlerts);
                    } else {
                        $('#vendor_roas_type_ytd').html("ROAS");
                        $('#vendor_roas_value').html("-");
                        setTimeout(function () {
                            vendor_roas_ytd.load({
                                json: {
                                    'x': [''],
                                    'Spend': [0],
                                    'Sales': [0],
                                },
                            });
                        }, 1000);
                    }
                    if (response.vendorDetailSCMTD[0]) {
                        generateVendorDetailSCMTD(response.vendorDetailSCMTD[0], report_type, response.vendorAlerts);
                    } else {
                        if (report_type == 0) {
                            setTimeout(function () {
                                vendor_shipped_cogs_mtd.internal.config.gauge_max = 100;
                                vendor_shipped_cogs_mtd.internal.config.gauge_min = 0;
                                vendor_shipped_cogs_mtd.load({
                                    columns: [
                                        ['PTP', 0],
                                        ['Shipped COGS', 0],
                                    ],
                                });
                                vendor_shipped_cogs_mtd.unload({
                                    ids: ['Shipped Units']
                                });
                            }, 1000);
                            $("#vendor_shipped_cog_mtd_card").removeAttr("style");
                            $('#vendor_sc_type_mtd').html("Shipped COGS");
                            $('#vendor_sc_value_mtd').html("-");
                            $('#vendor_ptp_sc_value_mtd').html("-");
                            var col = document.getElementById("vendor_ptp_sc_mtd_percentage");
                            if (document.body.classList.contains('dark-layout') == true) {
                                col.style.color = "#c2c6dc";
                            } else {
                                col.style.color = "#626262";
                            }
                            $('#vendor_ptp_sc_mtd_percentage').html("%");
                        } else {
                            setTimeout(function () {
                                vendor_shipped_cogs_mtd.internal.config.gauge_max = 100;
                                vendor_shipped_cogs_mtd.internal.config.gauge_min = 0;
                                vendor_shipped_cogs_mtd.load({
                                    columns: [
                                        ['PTP', 0],
                                        ['Shipped Units', 0],
                                    ],
                                });
                                vendor_shipped_cogs_mtd.unload({
                                    ids: ['Shipped COGS']
                                });
                            }, 1000);
                            $("#vendor_shipped_cog_mtd_card").removeAttr("style");
                            $('#vendor_sc_type_mtd').html("Shipped Units");
                            $('#vendor_sc_value_mtd').html("-");
                            $('#vendor_ptp_sc_value_mtd').html("-");
                            var col = document.getElementById("vendor_ptp_sc_mtd_percentage");
                            if (document.body.classList.contains('dark-layout') == true) {
                                col.style.color = "#c2c6dc";
                            } else {
                                col.style.color = "#626262";
                            }
                            $('#vendor_ptp_sc_mtd_percentage').html("%");
                        }
                    }
                    if (response.vendorDetailNRMTD[0]) {
                        generateVendorDetailNRMTD(response.vendorDetailNRMTD[0], report_type, response.vendorAlerts);
                    } else {
                        if (report_type == 0) {
                            setTimeout(function () {
                                vendor_net_receipts_mtd.internal.config.gauge_max = 100;
                                vendor_net_receipts_mtd.internal.config.gauge_min = 0;
                                vendor_net_receipts_mtd.load({
                                    columns: [
                                        ['PTP', 0],
                                        ['Net Received', 0],
                                    ],
                                });
                                vendor_net_receipts_mtd.unload({
                                    ids: ['Net Received Units']
                                });
                            }, 1000);
                            $('#vendor_nr_type_mtd').html("Net Receipts");
                            $('#vendor_nr_value_mtd').html("-");
                            $('#vendor_ptp_nr_value_mtd').html("-");
                            var col = document.getElementById("vendor_ptp_nr_mtd_percentage");
                            if (document.body.classList.contains('dark-layout') == true) {
                                col.style.color = "#c2c6dc";
                            } else {
                                col.style.color = "#626262";
                            }
                            $('#vendor_ptp_nr_mtd_percentage').html("%");
                        } else {
                            setTimeout(function () {
                                vendor_net_receipts_mtd.internal.config.gauge_max = 100;
                                vendor_net_receipts_mtd.internal.config.gauge_min = 0;
                                vendor_net_receipts_mtd.load({
                                    columns: [
                                        ['PTP', 0],
                                        ['Net Received Units', 0],
                                    ],
                                });
                                vendor_net_receipts_mtd.unload({
                                    ids: ['Net Received']
                                });
                            }, 1000);
                            $('#vendor_nr_type_mtd').html("Net Received Units");
                            $('#vendor_nr_value_mtd').html("-");
                            $('#vendor_ptp_nr_value_mtd').html("-");
                            var col = document.getElementById("vendor_ptp_nr_mtd_percentage");
                            if (document.body.classList.contains('dark-layout') == true) {
                                col.style.color = "#c2c6dc";
                            } else {
                                col.style.color = "#626262";
                            }
                            $('#vendor_ptp_nr_mtd_percentage').html("%");
                        }
                    }
                    if (response.vendorDetailROASMTD[0]) {
                        generateVendorDetailROASMTD(response.vendorDetailROASMTD[0], report_type, response.vendorAlerts);
                    } else {
                        $('#vendor_roas_type_mtd').html("ROAS");
                        $('#vendor_roas_value_mtd').html("-");
                        setTimeout(function () {
                            vendor_roas_mtd.load({
                                json: {
                                    'x': [],
                                    'Spend': [0],
                                    'Sales': [0],
                                },
                            });
                        }, 1000);
                    }
                    if (response.vendorDetailOrderedProductYtd[0]) {
                        generateVendorDetailOrderedProductYtd(response.vendorDetailOrderedProductYtd[0], report_type);
                    }
                    else {
                        if (response.checkVendorType == '(3P)') {
                            $('#opTitle').html("Ordered Product");
                            $('#opValue').html("-");
                        }
                    }
                    if (response.vendorDetailOrderedProductMtd[0]) {
                        generateVendorDetailOrderedProductMtd(response.vendorDetailOrderedProductMtd[0], report_type);
                    } else {
                        if (response.checkVendorType == '(3P)') {
                            $('#opTitleMtd').html("Ordered Product");
                            $('#opValueMtd').html("-");
                        }
                    }
                    if (response.vendor) {
                        vendorShippedCogsTrailing('vendor_last6_shipped_cogs_ytd', response.vendor, report_type, filter_date_range);
                        vendorNetReceivedTrailing('vendor_last6_net_receipts_ytd', response.vendor, report_type, filter_date_range);
                        vendorShippedCogsTrailing('vendor_last6_shipped_cogs_mtd', response.vendor, report_type, filter_date_range);
                        vendorNetReceivedTrailing('vendor_last6_net_receipts_mtd', response.vendor, report_type, filter_date_range);
                        vendorOrderedProductTrailing('vendor_last6_ordered_product_ytd', response.vendor, report_type, filter_date_range);
                        vendorOrderedProductTrailing('vendor_last6_ordered_product_mtd', response.vendor, report_type, filter_date_range);
                        vendorRoasTrailing('vendor_line_roas_ytd', response.vendor, report_type, filter_date_range);
                        vendorRoasTrailingMtd('vendor_line_roas_mtd', response.vendor, report_type, filter_date_range);
                    }
                }
            },
        });
    }

    var shipped_cogs_ytd = c3.generate({
        bindto: d3.select('#shipped_cogs_ytd'),
        data: {
            columns: [
                ['PTP', 0],
                ['Shipped COGS', 0],
            ],
            type: 'gauge',
        },
        gauge: {
            label: {
                show: true // to turn off the min/max labels.
            },
            min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
            max: 100, // 100 is default
            width: 50 // for adjusting arc thickness
        },
        color: {
            pattern: ['#4DD0EA', '#FFB371', '#FFB371']
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
                anchor: 'top-left',
                x: -30,
                y: 20,
                step: undefined
            }
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
    var net_receipts_ytd = c3.generate({
        bindto: d3.select('#net_receipts_ytd'),
        data: {
            columns: [
                ['PTP', 0],
                ['Net Received', 0],
            ],
            type: 'gauge',
        },
        gauge: {
            min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
            max: 100, // 100 is default
            width: 50 // for adjusting arc thickness
        },
        color: {
            pattern: ['#4DD0EA', '#FFB371', '#FFB371']
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
                anchor: 'top-left',
                x: -30,
                y: 20,
                step: undefined
            }
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

    var shipped_cogs_mtd = c3.generate({
        bindto: d3.select('#shipped_cogs_mtd'),
        data: {
            columns: [
                ['PTP', 0],
                ['Shipped COGS', 0],
            ],
            type: 'gauge',
        },
        gauge: {
            label: {
                show: true // to turn off the min/max labels.
            },
            min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
            max: 100, // 100 is default
            width: 50 // for adjusting arc thickness
        },
        color: {
            pattern: ['#4DD0EA', '#FFB371', '#FFB371']
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
                anchor: 'top-left',
                x: -30,
                y: 20,
                step: undefined
            }
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
    var net_receipts_mtd = c3.generate({
        bindto: d3.select('#net_receipts_mtd'),
        data: {
            columns: [
                ['PTP', 0],
                ['Net Received', 0],
            ],
            type: 'gauge',
        },
        gauge: {
            min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
            max: 100, // 100 is default
            width: 50 // for adjusting arc thickness
        },
        color: {
            pattern: ['#4DD0EA', '#FFB371', '#FFB371']
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
                anchor: 'top-left',
                x: -30,
                y: 20,
                step: undefined
            },
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

    var vendor_shipped_cogs_ytd = c3.generate({
        bindto: d3.select('#vendor_shipped_cogs_ytd'),
        data: {
            columns: [
                ['PTP', 0],
                ['Shipped COGS', 0],
            ],
            type: 'gauge',
        },
        gauge: {
            min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
            max: 100, // 100 is default
            width: 40 // for adjusting arc thickness
        },
        color: {
            pattern: ['#4DD0EA', '#FFB371', '#FFB371']
        },
        size: {
            height: 138
        },
        transition: {
            duration: 100
        },
        legend: {
            show: true,
            position: 'inset',
            inset: {
                anchor: 'top-right',
                x: 25,
                y: undefined,
                step: undefined
            }
        },
        tooltip: {
            format: {
                value: function (value, ratio, id) {
                    return value + "%";
                }
            }
        },
    });
    var vendor_net_receipts_ytd = c3.generate({
        bindto: d3.select('#vendor_net_receipts_ytd'),
        data: {
            columns: [
                ['PTP', 0],
                ['Net Received', 0],
            ],
            type: 'gauge',
        },
        gauge: {
            min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
            max: 100, // 100 is default
            width: 40 // for adjusting arc thickness
        },
        color: {
            pattern: ['#4DD0EA', '#FFB371', '#FFB371']
        },
        size: {
            height: 138
        },
        transition: {
            duration: 100
        },
        legend: {
            show: true,
            position: 'inset',
            inset: {
                anchor: 'top-right',
                x: 18,
                y: undefined,
                step: undefined
            }
        },
        tooltip: {
            format: {
                value: function (value, ratio, id) {
                    return value + "%";
                },
            }
        },
    });
    var vendor_roas_ytd = c3.generate({
        bindto: d3.select('#vendor_trailing_roas_ytd'),
        data: {
            x: 'x',
            json: {
                'x': [],
                'Spend': [],
                'Sales': [],
            },
            colors: {
                'Spend': '#4DD0EA',
                'Sales': '#FFB371',
            },
            types: {
                'Spend': 'bar',
                'Sales': 'bar',
            },
        },
        color: {
            pattern: ['#FFB371', '#4DD0EA']
        },
        axis: {
            rotated: true,
            x: {
                type: 'category',
                show: false,
            },
            y: {
                show: false,
                tick: {
                    format: d3.format('$,')
                }
            },
        },
        size: {
            height: 170,
            width: 150,
        },
        bar: {
            width: {
                ratio: 0.3 // this makes bar width 50% of length between ticks
            },
        },
        transition: {
            duration: 100
        },
        legend: {
            show: true,
            position: 'inset',
            inset: {
                anchor: 'top-left',
                x: undefined,
                y: undefined,
                step: undefined
            }
        },
        tooltip: {
            show: true
        }
    });
    var vendor_shipped_cogs_mtd = c3.generate({
        bindto: d3.select('#vendor_shipped_cogs_mtd'),
        data: {
            columns: [
                ['PTP', 0],
                ['Shipped COGS', 0],
            ],
            type: 'gauge',
        },
        gauge: {
            min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
            max: 100, // 100 is default
            width: 40 // for adjusting arc thickness
        },
        color: {
            pattern: ['#4DD0EA', '#FFB371', '#FFB371']
        },
        size: {
            height: 138
        },
        transition: {
            duration: 100
        },
        legend: {
            show: true,
            position: 'inset',
            inset: {
                anchor: 'top-right',
                x: 25,
                y: undefined,
                step: undefined
            }
        },
        tooltip: {
            format: {
                value: function (value, ratio, id) {
                    return value + "%";
                }
            }
        },
    });
    var vendor_net_receipts_mtd = c3.generate({
        bindto: d3.select('#vendor_net_receipts_mtd'),
        data: {
            columns: [
                ['PTP', 0],
                ['Net Received', 0],
            ],
            type: 'gauge',
        },
        gauge: {
            min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
            max: 100, // 100 is default
            width: 40 // for adjusting arc thickness
        },
        color: {
            pattern: ['#4DD0EA', '#FFB371', '#FFB371']
        },
        size: {
            height: 138
        },
        transition: {
            duration: 100
        },
        legend: {
            show: true,
            position: 'inset',
            inset: {
                anchor: 'top-right',
                x: 25,
                y: undefined,
                step: undefined
            }
        },
        tooltip: {
            format: {
                value: function (value, ratio, id) {
                    return value + "%";
                },
            }
        },
    });
    var vendor_roas_mtd = c3.generate({
        bindto: d3.select('#vendor_trailing_roas_mtd'),
        data: {
            x: 'x',
            json: {
                'x': [],
                'Spend': [],
                'Sales': [],
            },
            colors: {
                'Spend': '#4DD0EA',
                'Sales': '#FFB371',
            },
            types: {
                'Spend': 'bar',
                'Sales': 'bar',
            },
        },
        color: {
            pattern: ['#FFB371', '#4DD0EA']
        },
        axis: {
            rotated: true,
            x: {
                type: 'category',
                show: false,
            },
            y: {
                show: false,
                tick: {
                    format: d3.format('$,')
                }
            },
        },
        size: {
            height: 170,
            width: 150,
        },
        bar: {
            width: {
                ratio: 0.3 // this makes bar width 50% of length between ticks
            },
        },
        transition: {
            duration: 100
        },
        legend: {
            show: true,
            position: 'inset',
            inset: {
                anchor: 'top-left',
                x: undefined,
                y: undefined,
                step: undefined
            }
        },
        tooltip: {
            show: true
        }
    });
    function shippedCOGSYTD(SC_YTD, report_type) {
        let value = 0;
        let value1 = 0;

        var gauge_min = 0;
        var gauge_max = 100;
        var gauge_value1 = 0;
        var gauge_value2 = 0;
        switch (report_type) {
            case 0:
                SC_YTD.shipped_cogs_percent != null ? value = SC_YTD.shipped_cogs_percent : value = 0;
                SC_YTD.ptp_shipped_cogs_percent != null ? value1 = SC_YTD.ptp_shipped_cogs_percent : value1 = 0;
                SC_YTD.shipped_cogs_ytd != null ? $('#sc_ytd').html(SC_YTD.shipped_cogs_ytd) : $('#sc_ytd').html("-");
                $('#sc_ytd_title').html("Shipped COGS YTD");
                gauge_value1 = parseInt(value);
                gauge_value2 = parseInt(value1);
                gauge_min = 0;
                gauge_max = 100;

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
                    shipped_cogs_ytd.internal.config.gauge_max = gauge_max;
                    shipped_cogs_ytd.internal.config.gauge_min = gauge_min;
                    shipped_cogs_ytd.load({
                        columns: [
                            ['Shipped COGS', value],
                            ['PTP', value1],
                        ]
                    });
                    shipped_cogs_ytd.unload({
                        ids: ['Shipped Units']
                    });
                }, 1000);
                break;
            case 1:
                SC_YTD.shipped_units_percent != null ? value = SC_YTD.shipped_units_percent : value = 0;
                SC_YTD.ptp_shipped_units_percent != null ? value1 = SC_YTD.ptp_shipped_units_percent : value1 = 0;
                SC_YTD.shipped_units_ytd != null ? $('#sc_ytd').html(SC_YTD.shipped_units_ytd) : $('#sc_ytd').html("-");
                $('#sc_ytd_title').html("Shipped Units YTD");
                gauge_value1 = parseInt(value);
                gauge_value2 = parseInt(value1);
                gauge_min = 0;
                gauge_max = 100;

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
                    shipped_cogs_ytd.internal.config.gauge_max = gauge_max;
                    shipped_cogs_ytd.internal.config.gauge_min = gauge_min;
                    shipped_cogs_ytd.load({
                        columns: [
                            ['Shipped Units', value],
                            ['PTP', value1],
                        ]
                    });
                    shipped_cogs_ytd.unload({
                        ids: ['Shipped COGS']
                    });
                }, 1000);
                break;
            default:
                break;
        }
    }
    function netReceiptsYTD(NR_YTD, report_type) {
        let value = 0;
        let value1 = 0;

        var gauge_min = 0;
        var gauge_max = 100;
        var gauge_value1 = 0;
        var gauge_value2 = 0;
        switch (report_type) {
            case 0:
                NR_YTD.net_received_percent != null ? value = NR_YTD.net_received_percent : value = 0;
                NR_YTD.ptp_net_received_percent != null ? value1 = NR_YTD.ptp_net_received_percent : value1 = 0;
                NR_YTD.net_received_ytd != null ? $('#nr_ytd').html(NR_YTD.net_received_ytd) : $('#nr_ytd').html("-");
                $('#nr_ytd_title').html("Net Received YTD");
                gauge_value1 = parseInt(value);
                gauge_value2 = parseInt(value1);
                gauge_min = 0;
                gauge_max = 100;

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
                    net_receipts_ytd.internal.config.gauge_max = gauge_max;
                    net_receipts_ytd.internal.config.gauge_min = gauge_min;
                    net_receipts_ytd.load({
                        columns: [
                            ['Net Received', value],
                            ['PTP', value1],
                        ]
                    });
                    net_receipts_ytd.unload({
                        ids: ['Net Received Units']
                    });
                }, 1000);
                break;
            case 1:
                NR_YTD.net_received_units_percent != null ? value = NR_YTD.net_received_units_percent : value = 0;
                NR_YTD.ptp_net_received_units_percent != null ? value1 = NR_YTD.ptp_net_received_units_percent : value1 = 0;
                NR_YTD.net_received_units_ytd != null ? $('#nr_ytd').html(NR_YTD.net_received_units_ytd) : $('#nr_ytd').html("-");
                $('#nr_ytd_title').html("Net Received Units YTD");
                gauge_value1 = parseInt(value);
                gauge_value2 = parseInt(value1);
                gauge_min = 0;
                gauge_max = 100;

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
                    net_receipts_ytd.internal.config.gauge_max = gauge_max;
                    net_receipts_ytd.internal.config.gauge_min = gauge_min;
                    net_receipts_ytd.load({
                        columns: [
                            ['Net Received Units', value],
                            ['PTP', value1],
                        ]
                    });
                    net_receipts_ytd.unload({
                        ids: ['Net Received']
                    });
                }, 1000);
                break;
            default:
                break;
        }
    }

    function shippedCOGSMTD(SC_MTD, report_type) {
        let value = 0;
        let value1 = 0;

        var gauge_min = 0;
        var gauge_max = 100;
        var gauge_value1 = 0;
        var gauge_value2 = 0;
        switch (report_type) {
            case 0:
                SC_MTD.shipped_cogs_percent != null ? value = SC_MTD.shipped_cogs_percent : value = 0;
                SC_MTD.ptp_shipped_cogs_percent != null ? value1 = SC_MTD.ptp_shipped_cogs_percent : value1 = 0;
                SC_MTD.shipped_cogs_mtd != null ? $('#sc_mtd').html(SC_MTD.shipped_cogs_mtd) : $('#sc_mtd').html("-");
                $('#sc_mtd_title').html("Shipped COGS MTD");
                gauge_value1 = parseInt(value);
                gauge_value2 = parseInt(value1);
                gauge_min = 0;
                gauge_max = 100;

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
                    shipped_cogs_mtd.internal.config.gauge_max = gauge_max;
                    shipped_cogs_mtd.internal.config.gauge_min = gauge_min;
                    shipped_cogs_mtd.load({
                        columns: [
                            ['Shipped COGS', value],
                            ['PTP', value1],
                        ]
                    });
                    shipped_cogs_mtd.unload({
                        ids: ['Shipped Units']
                    });
                }, 1000);
                break;
            case 1:
                SC_MTD.shipped_units_percent != null ? value = SC_MTD.shipped_units_percent : value = 0;
                SC_MTD.ptp_shipped_units_percent != null ? value1 = SC_MTD.ptp_shipped_units_percent : value1 = 0;
                SC_MTD.shipped_units_mtd != null ? $('#sc_mtd').html(SC_MTD.shipped_units_mtd) : $('#sc_mtd').html("-");
                $('#sc_mtd_title').html("Shipped Units MTD");
                gauge_value1 = parseInt(value);
                gauge_value2 = parseInt(value1);
                gauge_min = 0;
                gauge_max = 100;

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
                    shipped_cogs_mtd.internal.config.gauge_max = gauge_max;
                    shipped_cogs_mtd.internal.config.gauge_min = gauge_min;
                    shipped_cogs_mtd.load({
                        columns: [
                            ['Shipped Units', value],
                            ['PTP', value1],
                        ]
                    });
                    shipped_cogs_mtd.unload({
                        ids: ['Shipped COGS']
                    });
                }, 1000);
                break;
            default:
                break;
        }
    }
    function netReceiptsMTD(NR_MTD, report_type) {
        let value = 0;
        let value1 = 0;

        var gauge_min = 0;
        var gauge_max = 100;
        var gauge_value1 = 0;
        var gauge_value2 = 0;
        switch (report_type) {
            case 0:
                NR_MTD.net_received_percent != null ? value = NR_MTD.net_received_percent : value = 0;
                NR_MTD.ptp_net_received_percent != null ? value1 = NR_MTD.ptp_net_received_percent : value1 = 0;
                NR_MTD.net_received_mtd != null ? $('#nr_mtd').html(NR_MTD.net_received_mtd) : $('#nr_mtd').html("-");
                $('#nr_mtd_title').html("Net Received MTD");
                gauge_value1 = parseInt(value);
                gauge_value2 = parseInt(value1);
                gauge_min = 0;
                gauge_max = 100;

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
                    net_receipts_mtd.internal.config.gauge_max = gauge_max;
                    net_receipts_mtd.internal.config.gauge_min = gauge_min;
                    net_receipts_mtd.load({
                        columns: [
                            ['Net Received', value],
                            ['PTP', value1],
                        ]
                    });
                    net_receipts_mtd.unload({
                        ids: ['Net Received Units']
                    });
                }, 1000);
                break;
            case 1:
                NR_MTD.net_received_units_percent != null ? value = NR_MTD.net_received_units_percent : value = 0;
                NR_MTD.ptp_net_received_units_percent != null ? value1 = NR_MTD.ptp_net_received_units_percent : value1 = 0;
                NR_MTD.net_received_units_mtd != null ? $('#nr_mtd').html(NR_MTD.net_received_units_mtd) : $('#nr_mtd').html("-");
                $('#nr_mtd_title').html("Net Received Units MTD");
                gauge_value1 = parseInt(value);
                gauge_value2 = parseInt(value1);
                gauge_min = 0;
                gauge_max = 100;

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
                    net_receipts_mtd.internal.config.gauge_max = gauge_max;
                    net_receipts_mtd.internal.config.gauge_min = gauge_min;
                    net_receipts_mtd.load({
                        columns: [
                            ['Net Received Units', value],
                            ['PTP', value1],
                        ]
                    });
                    net_receipts_mtd.unload({
                        ids: ['Net Received']
                    });
                }, 1000);
                break;
            default:
                break;
        }
    }

    function generateVendorDetailSC(data, report_type, vendorAlerts) {
        var sc_type = '-';
        var sc_value = '-';
        var ptp_sc_value = '-';
        var ptp_graph_value1 = 0;
        var ptp_graph_value2 = 0;
        var ptp_alert_value = 0;

        var gauge_min = 0;
        var gauge_max = 100;
        var gauge_value1 = 0;
        var gauge_value2 = 0;
        $("#vendor_shipped_cog_ytd_card").removeAttr("style");
        switch (report_type) {
            case 0:
                for (var i = 0; i < vendorAlerts.length; i++) {
                    let shipped_cogs = parseInt((data.shipped_cogs_ytd).replace(new RegExp("\\s|,|\\$", "gm"), ""))
                    let reported_value = parseInt((vendorAlerts[i].reported_value).replace(new RegExp("\\s|,|\\$", "gm"), ""))
                    if (shipped_cogs == reported_value && vendorAlerts[i].reported_attribute == 'shipped_cogs') {
                        $("#vendor_shipped_cog_ytd_card").attr({
                            "style": "box-shadow: 1px 4px 25px 0px rgb(255 0 0);"
                        });
                        break;
                    }
                }
                sc_type = "Shipped COGS";
                if (data.shipped_cogs_ytd != null) {
                    sc_value = data.shipped_cogs_ytd;
                }
                if (data.ptp_shipped_cogs_ytd != null) {
                    ptp_sc_value = data.ptp_shipped_cogs_ytd;
                }
                if (data.shipped_cogs_percent != null) {
                    ptp_graph_value1 = parseFloat(data.shipped_cogs_percent);
                }
                if (data.ptp_shipped_cogs_percent != null) {
                    ptp_graph_value2 = parseFloat(data.ptp_shipped_cogs_percent);
                }

                gauge_value1 = parseInt(ptp_graph_value1);
                gauge_value2 = parseInt(ptp_graph_value2);
                gauge_min = 0;
                gauge_max = 100;

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
                    vendor_shipped_cogs_ytd.internal.config.gauge_max = gauge_max;
                    vendor_shipped_cogs_ytd.internal.config.gauge_min = gauge_min;
                    vendor_shipped_cogs_ytd.load({
                        columns: [
                            ['Shipped COGS', ptp_graph_value1],
                            ['PTP', ptp_graph_value2],
                        ],
                    });
                    vendor_shipped_cogs_ytd.unload({
                        ids: ['Shipped Units']
                    });
                }, 1000);
                break;
            case 1:
                for (var i = 0; i < vendorAlerts.length; i++) {
                    let shipped_cogs = parseInt((data.shipped_units_ytd).replace(new RegExp("\\s|,|\\$", "gm"), ""))
                    let reported_value = parseInt((vendorAlerts[i].reported_value).replace(new RegExp("\\s|,|\\$", "gm"), ""))
                    if (shipped_cogs == reported_value && vendorAlerts[i].reported_attribute == 'shipped_unit') {
                        $("#vendor_shipped_cog_ytd_card").attr({
                            "style": "box-shadow: 1px 4px 25px 0px rgb(255 0 0);"
                        });
                        break;
                    }
                }
                sc_type = "Shipped Units";
                if (data.shipped_units_ytd != null) {
                    sc_value = data.shipped_units_ytd
                }
                if (data.ptp_shipped_units_ytd != null) {
                    ptp_sc_value = data.ptp_shipped_units_ytd
                }
                if (data.shipped_units_percent != null) {
                    ptp_graph_value1 = parseFloat(data.shipped_units_percent);
                }
                if (data.ptp_shipped_units_percent != null) {
                    ptp_graph_value2 = parseFloat(data.ptp_shipped_units_percent);
                }
                gauge_value1 = parseInt(ptp_graph_value1);
                gauge_value2 = parseInt(ptp_graph_value2);
                gauge_min = 0;
                gauge_max = 100;

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
                    vendor_shipped_cogs_ytd.internal.config.gauge_max = gauge_max;
                    vendor_shipped_cogs_ytd.internal.config.gauge_min = gauge_min;
                    vendor_shipped_cogs_ytd.load({
                        columns: [
                            ['Shipped Units', ptp_graph_value1],
                            ['PTP', ptp_graph_value2],
                        ],
                    });
                    vendor_shipped_cogs_ytd.unload({
                        ids: ['Shipped COGS']
                    });
                }, 1000);
                break;
            default:
                break;
        }
        if (parseFloat(ptp_graph_value1) >= parseFloat(ptp_graph_value2)) {
            ptp_alert_value = ptp_graph_value1 - ptp_graph_value2 + 100;
            var col = document.getElementById("vendor_ptp_sc_ytd_percentage");
            col.style.color = "rgb(15 130 15)";
        } else {
            ptp_alert_value = ptp_graph_value1 - ptp_graph_value2 + 100;
            ptp_alert_value = Math.abs(ptp_alert_value);
            var col = document.getElementById("vendor_ptp_sc_ytd_percentage");
            col.style.color = "#FF0000";
        }
        $('#vendor_sc_type').html(sc_type);
        $('#vendor_sc_value').html(sc_value);
        $('#vendor_ptp_sc_value').html(ptp_sc_value);
        $('#vendor_ptp_sc_ytd_percentage').html(ptp_alert_value.toFixed(2) + "% ");
    }
    function generateVendorDetailNR(data, report_type, vendorAlerts) {
        var nr_type = '-';
        var nr_value = '-';
        var ptp_nr_value = '-';
        var ptp_graph_value1 = 0;
        var ptp_graph_value2 = 0;
        nr_ytd_title_vendor = "-";
        var ptp_alert_value = 0;

        var gauge_min = 0;
        var gauge_max = 100;
        var gauge_value1 = 0;
        var gauge_value2 = 0;

        switch (report_type) {
            case 0:
                nr_type = "Net Received";
                if (data.net_received_ytd != null) {
                    nr_value = data.net_received_ytd
                }
                if (data.ptp_net_received_ytd != null) {
                    ptp_nr_value = data.ptp_net_received_ytd
                }
                if (data.net_received_percent != null) {
                    ptp_graph_value1 = parseFloat(data.net_received_percent);
                }
                if (data.ptp_net_received_percent != null) {
                    ptp_graph_value2 = parseFloat(data.ptp_net_received_percent);
                }
                gauge_value1 = parseInt(ptp_graph_value1);
                gauge_value2 = parseInt(ptp_graph_value2);
                gauge_min = 0;
                gauge_max = 100;

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
                    vendor_net_receipts_ytd.internal.config.gauge_max = gauge_max;
                    vendor_net_receipts_ytd.internal.config.gauge_min = gauge_min;
                    vendor_net_receipts_ytd.load({
                        columns: [
                            ['Net Received', ptp_graph_value1],
                            ['PTP', ptp_graph_value2],
                        ],
                    });
                    vendor_net_receipts_ytd.unload({
                        ids: ['Net Received Units']
                    });
                }, 1000);
                break;
            case 1:
                nr_type = "Net Received Units";
                if (data.net_received_units_ytd != null) {
                    nr_value = data.net_received_units_ytd;
                }
                if (data.ptp_net_received_units_ytd != null) {
                    ptp_nr_value = data.ptp_net_received_units_ytd;
                }
                if (data.net_received_units_percent != null) {
                    ptp_graph_value1 = parseFloat(data.net_received_units_percent);
                }
                if (data.ptp_net_received_units_percent != null) {
                    ptp_graph_value2 = parseFloat(data.ptp_net_received_units_percent);
                }
                gauge_value1 = parseInt(ptp_graph_value1);
                gauge_value2 = parseInt(ptp_graph_value2);
                gauge_min = 0;
                gauge_max = 100;

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
                    vendor_net_receipts_ytd.internal.config.gauge_max = gauge_max;
                    vendor_net_receipts_ytd.internal.config.gauge_min = gauge_min;
                    vendor_net_receipts_ytd.load({
                        columns: [
                            ['Net Received Units', ptp_graph_value1],
                            ['PTP', ptp_graph_value2],
                        ],
                    });
                    vendor_net_receipts_ytd.unload({
                        ids: ['Net Received']
                    });
                }, 1000);
                break;
            default:
                break;
        }
        if (parseFloat(ptp_graph_value1) >= parseFloat(ptp_graph_value2)) {
            ptp_alert_value = ptp_graph_value1 - ptp_graph_value2 + 100;
            var col = document.getElementById("vendor_ptp_nr_ytd_percentage");
            col.style.color = "rgb(15 130 15)";
        } else {
            ptp_alert_value = ptp_graph_value1 - ptp_graph_value2 + 100;
            ptp_alert_value = Math.abs(ptp_alert_value);
            var col = document.getElementById("vendor_ptp_nr_ytd_percentage");
            col.style.color = "#FF0000";
        }
        $('#vendor_nr_type').html(nr_type);
        $('#vendor_nr_value').html(nr_value);
        $('#vendor_ptp_nr_value').html(ptp_nr_value);
        $('#vendor_ptp_nr_ytd_percentage').html(ptp_alert_value.toFixed(2) + "% ");
    }
    function generateVendorDetailROAS(data, report_type, vendorAlerts) {
        var roas_type = '-';
        var roas_value = '-';
        nr_ytd_title_vendor = "-";
        var sales = 0;
        var spend = 0;
        var name = '';
        switch (report_type) {
            case 0:
                roas_type = "ROAS";
                if (data.roas != null) {
                    roas_value = data.roas
                }
                if (data.sales != null) {
                    sales = parseInt((data.sales).replace(new RegExp("\\s|,|\\$", "gm"), ""));
                }
                if (data.cost != null) {
                    spend = parseInt((data.cost).replace(new RegExp("\\s|,|\\$", "gm"), ""));
                }
                if (data.vendor_name != null) {
                    name = data.vendor_name;
                }
                break;
            case 1:
                roas_type = "ROAS";
                if (data.roas != null) {
                    roas_value = data.roas
                }
                if (data.sales != null) {
                    sales = parseInt((data.sales).replace(new RegExp("\\s|,|\\$", "gm"), ""));
                }
                if (data.cost != null) {
                    spend = parseInt((data.cost).replace(new RegExp("\\s|,|\\$", "gm"), ""));
                }
                if (data.vendor_name != null) {
                    name = data.vendor_name;
                }
                break;
            default:
                break;
        }
        setTimeout(function () {
            vendor_roas_ytd.load({
                json: {
                    'x': name,
                    'Spend': spend,
                    'Sales': sales,
                },
            });
        }, 1000);
        $('#vendor_roas_type_ytd').html(roas_type);
        $('#vendor_roas_value').html(roas_value);

    }
    function generateVendorDetailOrderedProductYtd(data, report_type) {
        var ordered_product_title = '-';
        var value = '-';
        switch (report_type) {
            case 0:
                ordered_product_title = "Ordered Product";
                if (data.ordered_product_ytd != null) {
                    value = data.ordered_product_ytd
                }
                break;
            case 1:
                ordered_product_title = "Ordered Product Units";
                if (data.units_ordered_ytd != null) {
                    value = data.units_ordered_ytd
                }
                break;
            default:
                break;
        }
        $('#opTitle').html(ordered_product_title);
        $('#opValue').html(value);
    }
    function generateVendorDetailOrderedProductMtd(data, report_type) {
        var ordered_product_title = '-';
        var value = '-';
        switch (report_type) {
            case 0:
                ordered_product_title = "Ordered Product";
                if (data.ordered_product_mtd != null) {
                    value = data.ordered_product_mtd
                }
                break;
            case 1:
                ordered_product_title = "Ordered Product Units";
                if (data.units_ordered_mtd != null) {
                    value = data.units_ordered_mtd
                }
                break;
            default:
                break;
        }
        $('#opTitleMtd').html(ordered_product_title);
        $('#opValueMtd').html(value);
    }
    function generateVendorDetailSCMTD(data, report_type, vendorAlerts) {
        var sc_type = '-';
        var sc_value = '-';
        var ptp_sc_value = '-';
        var ptp_graph_value = 0;
        var ptp_graph_value1 = 0;
        var ptp_alert_value = 0;

        var gauge_min = 0;
        var gauge_max = 100;
        var gauge_value1 = 0;
        var gauge_value2 = 0;

        $("#vendor_shipped_cog_mtd_card").removeAttr("style");

        switch (report_type) {
            case 0:
                for (var i = 0; i < vendorAlerts.length; i++) {
                    let shipped_cogs = parseInt((data.shipped_cogs_mtd).replace(new RegExp("\\s|,|\\$", "gm"), ""))
                    let reported_value = parseInt((vendorAlerts[i].reported_value).replace(new RegExp("\\s|,|\\$", "gm"), ""))
                    if (shipped_cogs == reported_value && vendorAlerts[i].reported_attribute == 'shipped_cogs') {
                        $("#vendor_shipped_cog_mtd_card").attr({
                            "style": "box-shadow: 1px 4px 25px 0px rgb(255 0 0);"
                        });
                        break
                    }
                }
                sc_type = "Shipped COGS";
                if (data.shipped_cogs_mtd != null) {
                    sc_value = data.shipped_cogs_mtd;
                }
                if (data.shipped_cogs_percent != null) {
                    ptp_graph_value = parseFloat(data.shipped_cogs_percent);
                }
                if (data.ptp_shipped_cogs_percent != null) {
                    ptp_graph_value1 = parseFloat(data.ptp_shipped_cogs_percent);
                }
                if (data.ptp_shipped_cogs_mtd != null) {
                    ptp_sc_value = data.ptp_shipped_cogs_mtd
                }
                gauge_value1 = parseInt(ptp_graph_value);
                gauge_value2 = parseInt(ptp_graph_value1);
                gauge_min = 0;
                gauge_max = 100;

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
                    vendor_shipped_cogs_mtd.internal.config.gauge_max = gauge_max;
                    vendor_shipped_cogs_mtd.internal.config.gauge_min = gauge_min;
                    vendor_shipped_cogs_mtd.load({
                        columns: [
                            ['PTP', ptp_graph_value1],
                            ['Shipped COGS', ptp_graph_value],
                        ],
                    });
                    vendor_shipped_cogs_mtd.unload({
                        ids: ['Shipped Units']
                    });
                }, 1000);
                break;
            case 1:
                for (var i = 0; i < vendorAlerts.length; i++) {
                    let shipped_cogs = parseInt((data.shipped_units_mtd).replace(new RegExp("\\s|,|\\$", "gm"), ""))
                    let reported_value = parseInt((vendorAlerts[i].reported_value).replace(new RegExp("\\s|,|\\$", "gm"), ""))
                    if (shipped_cogs == reported_value && vendorAlerts[i].reported_attribute == 'shipped_unit') {
                        $("#vendor_shipped_cog_mtd_card").attr({
                            "style": "box-shadow: 1px 4px 25px 0px rgb(255 0 0);"
                        });
                        break
                    }
                }
                sc_type = "Shipped Units";
                if (data.shipped_units_mtd != null) {
                    sc_value = data.shipped_units_mtd;
                }
                if (data.shipped_units_percent != null) {
                    ptp_graph_value = parseFloat(data.shipped_units_percent);
                }
                if (data.ptp_shipped_units_percent != null) {
                    ptp_graph_value1 = parseFloat(data.ptp_shipped_units_percent);
                }
                if (data.ptp_shipped_units_mtd != null) {
                    ptp_sc_value = data.ptp_shipped_units_mtd
                }
                gauge_value1 = parseInt(ptp_graph_value);
                gauge_value2 = parseInt(ptp_graph_value1);
                gauge_min = 0;
                gauge_max = 100;
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
                    vendor_shipped_cogs_mtd.internal.config.gauge_max = gauge_max;
                    vendor_shipped_cogs_mtd.internal.config.gauge_min = gauge_min;
                    vendor_shipped_cogs_mtd.load({
                        columns: [
                            ['PTP', ptp_graph_value1],
                            ['Shipped Units', ptp_graph_value],
                        ],
                    });
                    vendor_shipped_cogs_mtd.unload({
                        ids: ['Shipped COGS']
                    });
                }, 1000);
                break;
            default:
                break;
        }
        if (parseFloat(ptp_graph_value) >= parseFloat(ptp_graph_value1)) {
            ptp_alert_value = ptp_graph_value - ptp_graph_value1 + 100;
            var col = document.getElementById("vendor_ptp_sc_mtd_percentage");
            col.style.color = "rgb(15 130 15)";
        } else {
            ptp_alert_value = ptp_graph_value - ptp_graph_value1 + 100;
            ptp_alert_value = Math.abs(ptp_alert_value);
            var col = document.getElementById("vendor_ptp_sc_mtd_percentage");
            col.style.color = "#FF0000";
        }
        $('#vendor_sc_type_mtd').html(sc_type);
        $('#vendor_sc_value_mtd').html(sc_value);
        $('#vendor_ptp_sc_value_mtd').html(ptp_sc_value);
        $('#vendor_ptp_sc_mtd_percentage').html(ptp_alert_value.toFixed(2) + "% ");
    }
    function generateVendorDetailNRMTD(data, report_type, vendorAlerts) {
        var nr_type = '-';
        var nr_value = '-';
        var ptp_nr_value = '-';
        var ptp_graph_value1 = 0;
        var ptp_graph_value2 = 0;
        var ptp_alert_value = 0;

        var gauge_min = 0;
        var gauge_max = 100;
        var gauge_value1 = 0;
        var gauge_value2 = 0;

        switch (report_type) {
            case 0:
                nr_type = "Net Received";
                if (data.net_received_mtd != null) {
                    nr_value = data.net_received_mtd
                }
                if (data.net_received_percent != null) {
                    ptp_graph_value1 = parseFloat(data.net_received_percent);
                }
                if (data.net_received_percent != null) {
                    ptp_graph_value2 = parseFloat(data.ptp_net_received_percent);
                }
                if (data.ptp_net_received_mtd != null) {
                    ptp_nr_value = data.ptp_net_received_mtd
                }
                gauge_value1 = parseInt(ptp_graph_value1);
                gauge_value2 = parseInt(ptp_graph_value2);
                gauge_min = 0;
                gauge_max = 100;
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
                    vendor_net_receipts_mtd.internal.config.gauge_max = gauge_max;
                    vendor_net_receipts_mtd.internal.config.gauge_min = gauge_min;
                    vendor_net_receipts_mtd.load({
                        columns: [
                            ['PTP', ptp_graph_value2],
                            ['Net Received', ptp_graph_value1],
                        ],
                    });
                    vendor_net_receipts_mtd.unload({
                        ids: ['Net Received Units']
                    });
                }, 1000);
                break;
            case 1:
                nr_type = "Net Received Units";
                if (data.net_received_units_mtd != null) {
                    nr_value = data.net_received_units_mtd
                }
                if (data.net_received_units_percent != null) {
                    ptp_graph_value1 = parseFloat(data.net_received_units_percent);
                }
                if (data.net_received_units_percent != null) {
                    ptp_graph_value2 = parseFloat(data.ptp_net_received_units_percent);
                }
                if (data.ptp_net_received_units_mtd != null) {
                    ptp_nr_value = data.ptp_net_received_units_mtd
                }
                gauge_value1 = parseInt(ptp_graph_value1);
                gauge_value2 = parseInt(ptp_graph_value2);
                gauge_min = 0;
                gauge_max = 100;
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
                    vendor_net_receipts_mtd.internal.config.gauge_max = gauge_max;
                    vendor_net_receipts_mtd.internal.config.gauge_min = gauge_min;
                    vendor_net_receipts_mtd.load({
                        columns: [
                            ['PTP', ptp_graph_value2],
                            ['Net Received Units', ptp_graph_value1],
                        ],
                    });
                    vendor_net_receipts_mtd.unload({
                        ids: ['Net Received']
                    });
                }, 1000);
                break;
            default:
                break;
        }
        if (ptp_graph_value1 >= ptp_graph_value2) {
            ptp_alert_value = ptp_graph_value1 - ptp_graph_value2 + 100;
            var col = document.getElementById("vendor_ptp_nr_mtd_percentage");
            col.style.color = "rgb(15 130 15)";
        } else {
            ptp_alert_value = ptp_graph_value1 - ptp_graph_value2 + 100;
            ptp_alert_value = Math.abs(ptp_alert_value);
            var col = document.getElementById("vendor_ptp_nr_mtd_percentage");
            col.style.color = "#FF0000";
        }
        $('#vendor_nr_type_mtd').html(nr_type);
        $('#vendor_nr_value_mtd').html(nr_value);
        $('#vendor_ptp_nr_value_mtd').html(ptp_nr_value);
        $('#vendor_ptp_nr_mtd_percentage').html(ptp_alert_value.toFixed(2) + "% ");
    }
    function generateVendorDetailROASMTD(data, report_type, vendorAlerts) {
        var roas_type = '-';
        var roas_value = '-';
        var sales = 0;
        var spend = 0;
        var name = '';
        switch (report_type) {
            case 0:
                roas_type = "ROAS";
                if (data.roas != null) {
                    roas_value = data.roas
                }
                if (data.sales != null) {
                    sales = parseInt((data.sales).replace(new RegExp("\\s|,|\\$", "gm"), ""));
                }
                if (data.cost != null) {
                    spend = parseInt((data.cost).replace(new RegExp("\\s|,|\\$", "gm"), ""));
                }
                if (data.vendor_name != null) {
                    name = data.vendor_name;
                }
                break;
            case 1:
                roas_type = "ROAS";
                if (data.roas != null) {
                    roas_value = data.roas
                }
                if (data.sales != null) {
                    sales = parseInt((data.sales).replace(new RegExp("\\s|,|\\$", "gm"), ""));
                }
                if (data.cost != null) {
                    spend = parseInt((data.cost).replace(new RegExp("\\s|,|\\$", "gm"), ""));
                }
                if (data.vendor_name != null) {
                    name = data.vendor_name;
                }
                break;
            default:
                break;
        }
        setTimeout(function () {
            vendor_roas_mtd.load({
                json: {
                    'x': name,
                    'Spend': spend,
                    'Sales': sales,
                },
            });
        }, 1000);
        $('#vendor_roas_type_mtd').html(roas_type);
        $('#vendor_roas_value_mtd').html(roas_value);
    }
    function orderedProductYTD(orderedProductYtd, report_type) {
        var ordered_product_type = '-';
        var value = '-';
        switch (report_type) {
            case 0:
                ordered_product_type = "Ordered Product";
                if (orderedProductYtd.ordered_product_ytd != null) {
                    value = orderedProductYtd.ordered_product_ytd
                }
                break;
            case 1:
                ordered_product_type = "Ordered Product Units";
                if (orderedProductYtd.units_ordered_ytd != null) {
                    value = orderedProductYtd.units_ordered_ytd
                }
                break;
            default:
                break;
        }
        $('#sc_ytd_title1').html(ordered_product_type);
        $('#sc_ytd1').html(value);
    }
    function orderedProductMTD(orderedProductMtd, report_type) {
        var ordered_product_type = '-';
        var value = '-';
        switch (report_type) {
            case 0:
                ordered_product_type = "Ordered Product";
                if (orderedProductMtd.ordered_product_mtd != null) {
                    value = orderedProductMtd.ordered_product_mtd
                }
                break;
            case 1:
                ordered_product_type = "Ordered Product Units";
                if (orderedProductMtd.units_ordered_mtd != null) {
                    value = orderedProductMtd.units_ordered_mtd
                }
                break;
            default:
                break;
        }
        $('#ordered_mtd_title').html(ordered_product_type);
        $('#ordered_mtd').html(value);
    }
    function vendorShippedCogsTrailing(DOM_id, vendor_id, report_type) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let value = $("#filter_range_picker").val();
        let firstDate = moment(value, "MMM-YYYY").startOf('month').format("MM/DD/YYYY");
        let lastDate = moment(value, "MMM-YYYY").endOf('month').format("MM/DD/YYYY");
        var filter_date_range = firstDate + " - " + lastDate;
        $.ajax({
            url: base_url + "/ed/vendor/trailing/sc",
            type: "POST",
            data: {
                type: report_type,
                vendor: vendor_id,
                date_range: filter_date_range,
            },
            cache: false,
            success: function (response) {
                if (response.shippedCogsTrailing) {
                    let responsedata = response.shippedCogsTrailing;
                    let month = [];
                    let value = [];
                    if (response.check != '3P') {
                        switch (report_type) {
                            case 0:
                                var chart = c3.generate({
                                    bindto: d3.select('#' + DOM_id),
                                    data: {
                                        x: 'x',
                                        json: {
                                            'x': [],
                                            'Shipped COGS': [],
                                        },
                                        colors: {
                                            'Shipped COGS': '#FFB371',
                                        },

                                        types: {
                                            'Shipped COGS': 'line',
                                        },
                                    },
                                    axis: {
                                        x: {
                                            type: 'category',
                                            show: false,
                                        },
                                        y: {
                                            show: false,
                                            tick: {
                                                format: d3.format('$,')
                                            }
                                        },
                                    },
                                    size: {
                                        height: 40,
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
                                    tooltip: {
                                        show: true
                                    }
                                });
                                for (var count = 0; count < responsedata.length; count++) {
                                    month[count] = responsedata[count].month_name;
                                    if (responsedata[count].month_name == 'January') {
                                        month[count] = 'Jan'
                                    } else if (responsedata[count].month_name == 'February') {
                                        month[count] = 'Feb'
                                    } else if (responsedata[count].month_name == 'March') {
                                        month[count] = 'Mar'
                                    } else if (responsedata[count].month_name == 'April') {
                                        month[count] = 'Apr'
                                    } else if (responsedata[count].month_name == 'May') {
                                        month[count] = 'May'
                                    } else if (responsedata[count].month_name == 'June') {
                                        month[count] = 'June'
                                    } else if (responsedata[count].month_name == 'July') {
                                        month[count] = 'July'
                                    } else if (responsedata[count].month_name == 'August') {
                                        month[count] = 'Aug'
                                    } else if (responsedata[count].month_name == 'September') {
                                        month[count] = 'Sept'
                                    } else if (responsedata[count].month_name == 'October') {
                                        month[count] = 'Oct'
                                    } else if (responsedata[count].month_name == 'November') {
                                        month[count] = 'Nov'
                                    } else if (responsedata[count].month_name == 'December') {
                                        month[count] = 'Dec'
                                    }
                                    month[count] += '-' + responsedata[count].order_year;
                                    value[count] = parseInt(responsedata[count].shipped_cogs);
                                }
                                setTimeout(function () {
                                    chart.load({
                                        json: {
                                            'x': month,
                                            'Shipped COGS': value,
                                        },
                                    });
                                }, 1000);
                                break;
                            case 1:
                                var chart = c3.generate({
                                    bindto: d3.select('#' + DOM_id),
                                    data: {
                                        x: 'x',
                                        json: {
                                            'x': [],
                                            'Shipped Unit': [],
                                        },
                                        colors: {
                                            'Shipped Unit': '#FFB371',
                                        },
                                        types: {
                                            'Shipped Unit': 'line',
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
                                                format: d3.format(',')
                                            }
                                        },
                                    },
                                    size: {
                                        height: 40,
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
                                    tooltip: {
                                        show: true
                                    }
                                });
                                for (var count = 0; count < responsedata.length; count++) {
                                    month[count] = responsedata[count].month_name;
                                    if (responsedata[count].month_name == 'January') {
                                        month[count] = 'Jan'
                                    } else if (responsedata[count].month_name == 'February') {
                                        month[count] = 'Feb'
                                    } else if (responsedata[count].month_name == 'March') {
                                        month[count] = 'Mar'
                                    } else if (responsedata[count].month_name == 'April') {
                                        month[count] = 'Apr'
                                    } else if (responsedata[count].month_name == 'May') {
                                        month[count] = 'May'
                                    } else if (responsedata[count].month_name == 'June') {
                                        month[count] = 'June'
                                    } else if (responsedata[count].month_name == 'July') {
                                        month[count] = 'July'
                                    } else if (responsedata[count].month_name == 'August') {
                                        month[count] = 'Aug'
                                    } else if (responsedata[count].month_name == 'September') {
                                        month[count] = 'Sept'
                                    } else if (responsedata[count].month_name == 'October') {
                                        month[count] = 'Oct'
                                    } else if (responsedata[count].month_name == 'November') {
                                        month[count] = 'Nov'
                                    } else if (responsedata[count].month_name == 'December') {
                                        month[count] = 'Dec'
                                    }
                                    month[count] += '-' + responsedata[count].order_year;
                                    value[count] = parseInt(responsedata[count].shipped_units);
                                }
                                setTimeout(function () {
                                    chart.load({
                                        json: {
                                            'x': month,
                                            'Shipped Unit': value,
                                        },
                                    });
                                }, 1000);
                                break;
                            default:
                                break;
                        }
                    } else {
                        switch (report_type) {
                            case 0:
                                var chart = c3.generate({
                                    bindto: d3.select('#' + DOM_id),
                                    data: {
                                        x: 'x',
                                        json: {
                                            'x': [],
                                            'Ordered Product': [],
                                        },
                                        colors: {
                                            'Ordered Product': '#FFB371',
                                        },

                                        types: {
                                            'Ordered Product': 'line',
                                        },
                                    },
                                    axis: {
                                        x: {
                                            type: 'category',
                                            show: false,
                                        },
                                        y: {
                                            show: false,
                                            tick: {
                                                format: d3.format('$,')
                                            }
                                        },
                                    },
                                    size: {
                                        height: 40,
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
                                    tooltip: {
                                        show: true
                                    }
                                });
                                for (var count = 0; count < responsedata.length; count++) {
                                    month[count] = responsedata[count].month_name;
                                    if (responsedata[count].month_name == 'January') {
                                        month[count] = 'Jan'
                                    } else if (responsedata[count].month_name == 'February') {
                                        month[count] = 'Feb'
                                    } else if (responsedata[count].month_name == 'March') {
                                        month[count] = 'Mar'
                                    } else if (responsedata[count].month_name == 'April') {
                                        month[count] = 'Apr'
                                    } else if (responsedata[count].month_name == 'May') {
                                        month[count] = 'May'
                                    } else if (responsedata[count].month_name == 'June') {
                                        month[count] = 'June'
                                    } else if (responsedata[count].month_name == 'July') {
                                        month[count] = 'July'
                                    } else if (responsedata[count].month_name == 'August') {
                                        month[count] = 'Aug'
                                    } else if (responsedata[count].month_name == 'September') {
                                        month[count] = 'Sept'
                                    } else if (responsedata[count].month_name == 'October') {
                                        month[count] = 'Oct'
                                    } else if (responsedata[count].month_name == 'November') {
                                        month[count] = 'Nov'
                                    } else if (responsedata[count].month_name == 'December') {
                                        month[count] = 'Dec'
                                    }
                                    month[count] += '-' + responsedata[count].order_year;
                                    value[count] = parseInt(responsedata[count].ordered_product_sales);
                                }
                                setTimeout(function () {
                                    chart.load({
                                        json: {
                                            'x': month,
                                            'Ordered Product': value,
                                        },
                                    });
                                }, 1000);
                                break;
                            case 1:
                                var chart = c3.generate({
                                    bindto: d3.select('#' + DOM_id),
                                    data: {
                                        x: 'x',
                                        json: {
                                            'x': [],
                                            'Ordered Product Unit': [],
                                        },
                                        colors: {
                                            'Ordered Product Unit': '#FFB371',
                                        },
                                        types: {
                                            'Ordered Product Unit': 'line',
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
                                                format: d3.format(',')
                                            }
                                        },
                                    },
                                    size: {
                                        height: 40,
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
                                    tooltip: {
                                        show: true
                                    }
                                });
                                for (var count = 0; count < responsedata.length; count++) {
                                    month[count] = responsedata[count].month_name;
                                    if (responsedata[count].month_name == 'January') {
                                        month[count] = 'Jan'
                                    } else if (responsedata[count].month_name == 'February') {
                                        month[count] = 'Feb'
                                    } else if (responsedata[count].month_name == 'March') {
                                        month[count] = 'Mar'
                                    } else if (responsedata[count].month_name == 'April') {
                                        month[count] = 'Apr'
                                    } else if (responsedata[count].month_name == 'May') {
                                        month[count] = 'May'
                                    } else if (responsedata[count].month_name == 'June') {
                                        month[count] = 'June'
                                    } else if (responsedata[count].month_name == 'July') {
                                        month[count] = 'July'
                                    } else if (responsedata[count].month_name == 'August') {
                                        month[count] = 'Aug'
                                    } else if (responsedata[count].month_name == 'September') {
                                        month[count] = 'Sept'
                                    } else if (responsedata[count].month_name == 'October') {
                                        month[count] = 'Oct'
                                    } else if (responsedata[count].month_name == 'November') {
                                        month[count] = 'Nov'
                                    } else if (responsedata[count].month_name == 'December') {
                                        month[count] = 'Dec'
                                    }
                                    month[count] += '-' + responsedata[count].order_year;
                                    value[count] = parseInt(responsedata[count].units_ordered);
                                }
                                setTimeout(function () {
                                    chart.load({
                                        json: {
                                            'x': month,
                                            'Ordered Product Unit': value,
                                        },
                                    });
                                }, 1000);
                                break;
                            default:
                                break;
                        }
                    }
                }
            },
        });
    }
    function vendorNetReceivedTrailing(DOM_id, vendor_id, report_type) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let value = $("#filter_range_picker").val();
        let firstDate = moment(value, "MMM-YYYY").startOf('month').format("MM/DD/YYYY");
        let lastDate = moment(value, "MMM-YYYY").endOf('month').format("MM/DD/YYYY");
        var filter_date_range = firstDate + " - " + lastDate;
        $.ajax({
            url: base_url + "/ed/vendor/trailing/nr",
            type: "POST",
            data: {
                type: report_type,
                vendor: vendor_id,
                date_range: filter_date_range,
            },
            cache: false,
            success: function (response) {
                if (response.netReceivedTrailing) {
                    let responsedata = response.netReceivedTrailing;
                    let month = [];
                    let value = [];
                    switch (report_type) {
                        case 0:
                            var chart = c3.generate({
                                bindto: d3.select('#' + DOM_id),
                                data: {
                                    x: 'x',
                                    json: {
                                        'x': [],
                                        'Net Received': [],
                                    },
                                    colors: {
                                        'Net Received': '#FFB371',
                                    },
                                    types: {
                                        'Net Received': 'line',
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
                                            format: d3.format('$,')
                                        }
                                    },
                                },
                                size: {
                                    height: 40,
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
                                tooltip: {
                                    show: true
                                }
                            });
                            for (var count = 0; count < responsedata.length; count++) {
                                month[count] = responsedata[count].month_name;
                                if (responsedata[count].month_name == 'January') {
                                    month[count] = 'Jan'
                                } else if (responsedata[count].month_name == 'February') {
                                    month[count] = 'Feb'
                                } else if (responsedata[count].month_name == 'March') {
                                    month[count] = 'Mar'
                                } else if (responsedata[count].month_name == 'April') {
                                    month[count] = 'Apr'
                                } else if (responsedata[count].month_name == 'May') {
                                    month[count] = 'May'
                                } else if (responsedata[count].month_name == 'June') {
                                    month[count] = 'June'
                                } else if (responsedata[count].month_name == 'July') {
                                    month[count] = 'July'
                                } else if (responsedata[count].month_name == 'August') {
                                    month[count] = 'Aug'
                                } else if (responsedata[count].month_name == 'September') {
                                    month[count] = 'Sept'
                                } else if (responsedata[count].month_name == 'October') {
                                    month[count] = 'Oct'
                                } else if (responsedata[count].month_name == 'November') {
                                    month[count] = 'Nov'
                                } else if (responsedata[count].month_name == 'December') {
                                    month[count] = 'Dec'
                                }
                                month[count] += '-' + responsedata[count].order_year;
                                value[count] = parseInt(responsedata[count].net_received);
                            }
                            setTimeout(function () {
                                chart.load({
                                    json: {
                                        'x': month,
                                        'Net Received': value,
                                    },
                                });
                            }, 1000);
                            break;
                        case 1:
                            var chart = c3.generate({
                                bindto: d3.select('#' + DOM_id),
                                data: {
                                    x: 'x',
                                    json: {
                                        'x': [],
                                        'Net Received Units': [],
                                    },
                                    colors: {
                                        'Net Received Units': '#FFB371',
                                    },
                                    types: {
                                        'Net Received Units': 'line',
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
                                            format: d3.format(',')
                                        }
                                    },
                                },
                                size: {
                                    height: 40,
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
                                tooltip: {
                                    show: true
                                }
                            });
                            for (var count = 0; count < responsedata.length; count++) {
                                month[count] = responsedata[count].month_name;
                                if (responsedata[count].month_name == 'January') {
                                    month[count] = 'Jan'
                                } else if (responsedata[count].month_name == 'February') {
                                    month[count] = 'Feb'
                                } else if (responsedata[count].month_name == 'March') {
                                    month[count] = 'Mar'
                                } else if (responsedata[count].month_name == 'April') {
                                    month[count] = 'Apr'
                                } else if (responsedata[count].month_name == 'May') {
                                    month[count] = 'May'
                                } else if (responsedata[count].month_name == 'June') {
                                    month[count] = 'June'
                                } else if (responsedata[count].month_name == 'July') {
                                    month[count] = 'July'
                                } else if (responsedata[count].month_name == 'August') {
                                    month[count] = 'Aug'
                                } else if (responsedata[count].month_name == 'September') {
                                    month[count] = 'Sept'
                                } else if (responsedata[count].month_name == 'October') {
                                    month[count] = 'Oct'
                                } else if (responsedata[count].month_name == 'November') {
                                    month[count] = 'Nov'
                                } else if (responsedata[count].month_name == 'December') {
                                    month[count] = 'Dec'
                                }
                                month[count] += '-' + responsedata[count].order_year;
                                value[count] = parseInt(responsedata[count].net_received_units);
                            }
                            setTimeout(function () {
                                chart.load({
                                    json: {
                                        'x': month,
                                        'Net Received Units': value,
                                    },
                                });
                            }, 1000);
                            break;
                        default:
                            break;
                    }
                }
            },
        });
    }
    function vendorOrderedProductTrailing(DOM_id, vendor_id, report_type) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let value = $("#filter_range_picker").val();
        let firstDate = moment(value, "MMM-YYYY").startOf('month').format("MM/DD/YYYY");
        let lastDate = moment(value, "MMM-YYYY").endOf('month').format("MM/DD/YYYY");
        var filter_date_range = firstDate + " - " + lastDate;
        $.ajax({
            url: base_url + "/ed/vendor/trailing/op",
            type: "POST",
            data: {
                type: report_type,
                vendor: vendor_id,
                date_range: filter_date_range,
            },
            cache: false,
            success: function (response) {
                if (response.orderedProductTrailing) {
                    let responsedata = response.orderedProductTrailing;
                    let month = [];
                    let value = [];
                    switch (report_type) {
                        case 0:
                            var chart = c3.generate({
                                bindto: d3.select('#' + DOM_id),
                                data: {
                                    x: 'x',
                                    json: {
                                        'x': [],
                                        'Ordered Product': [],
                                    },
                                    colors: {
                                        'Ordered Product': '#FFB371',
                                    },

                                    types: {
                                        'Ordered Product': 'line',
                                    },
                                },
                                axis: {
                                    x: {
                                        type: 'category',
                                        show: false,
                                    },
                                    y: {
                                        show: false,
                                        tick: {
                                            format: d3.format('$,')
                                        }
                                    },
                                },
                                size: {
                                    height: 40,
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
                                tooltip: {
                                    show: true
                                }
                            });
                            for (var count = 0; count < responsedata.length; count++) {
                                month[count] = responsedata[count].month_name;
                                if (responsedata[count].month_name == 'January') {
                                    month[count] = 'Jan'
                                } else if (responsedata[count].month_name == 'February') {
                                    month[count] = 'Feb'
                                } else if (responsedata[count].month_name == 'March') {
                                    month[count] = 'Mar'
                                } else if (responsedata[count].month_name == 'April') {
                                    month[count] = 'Apr'
                                } else if (responsedata[count].month_name == 'May') {
                                    month[count] = 'May'
                                } else if (responsedata[count].month_name == 'June') {
                                    month[count] = 'June'
                                } else if (responsedata[count].month_name == 'July') {
                                    month[count] = 'July'
                                } else if (responsedata[count].month_name == 'August') {
                                    month[count] = 'Aug'
                                } else if (responsedata[count].month_name == 'September') {
                                    month[count] = 'Sept'
                                } else if (responsedata[count].month_name == 'October') {
                                    month[count] = 'Oct'
                                } else if (responsedata[count].month_name == 'November') {
                                    month[count] = 'Nov'
                                } else if (responsedata[count].month_name == 'December') {
                                    month[count] = 'Dec'
                                }
                                month[count] += '-' + responsedata[count].order_year;
                                value[count] = parseInt(responsedata[count].ordered_product_sales);
                            }
                            setTimeout(function () {
                                chart.load({
                                    json: {
                                        'x': month,
                                        'Ordered Product': value,
                                    },
                                });
                            }, 1000);
                            break;
                        case 1:
                            var chart = c3.generate({
                                bindto: d3.select('#' + DOM_id),
                                data: {
                                    x: 'x',
                                    json: {
                                        'x': [],
                                        'Ordered Product Unit': [],
                                    },
                                    colors: {
                                        'Ordered Product Unit': '#FFB371',
                                    },
                                    types: {
                                        'Ordered Product Unit': 'line',
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
                                            format: d3.format(',')
                                        }
                                    },
                                },
                                size: {
                                    height: 40,
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
                                tooltip: {
                                    show: true
                                }
                            });
                            for (var count = 0; count < responsedata.length; count++) {
                                month[count] = responsedata[count].month_name;
                                if (responsedata[count].month_name == 'January') {
                                    month[count] = 'Jan'
                                } else if (responsedata[count].month_name == 'February') {
                                    month[count] = 'Feb'
                                } else if (responsedata[count].month_name == 'March') {
                                    month[count] = 'Mar'
                                } else if (responsedata[count].month_name == 'April') {
                                    month[count] = 'Apr'
                                } else if (responsedata[count].month_name == 'May') {
                                    month[count] = 'May'
                                } else if (responsedata[count].month_name == 'June') {
                                    month[count] = 'June'
                                } else if (responsedata[count].month_name == 'July') {
                                    month[count] = 'July'
                                } else if (responsedata[count].month_name == 'August') {
                                    month[count] = 'Aug'
                                } else if (responsedata[count].month_name == 'September') {
                                    month[count] = 'Sept'
                                } else if (responsedata[count].month_name == 'October') {
                                    month[count] = 'Oct'
                                } else if (responsedata[count].month_name == 'November') {
                                    month[count] = 'Nov'
                                } else if (responsedata[count].month_name == 'December') {
                                    month[count] = 'Dec'
                                }
                                month[count] += '-' + responsedata[count].order_year;
                                value[count] = parseInt(responsedata[count].units_ordered);
                            }
                            setTimeout(function () {
                                chart.load({
                                    json: {
                                        'x': month,
                                        'Ordered Product Unit': value,
                                    },
                                });
                            }, 1000);
                            break;
                        default:
                            break;
                    }
                }
            },
        });
    }
    function vendorRoasTrailing(DOM_id, vendor_id, report_type) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let value = $("#filter_range_picker").val();
        let firstDate = moment(value, "MMM-YYYY").startOf('month').format("MM/DD/YYYY");
        let lastDate = moment(value, "MMM-YYYY").endOf('month').format("MM/DD/YYYY");
        var filter_date_range = firstDate + " - " + lastDate;
        $.ajax({
            url: base_url + "/ed/vendor/trailing/roas",
            type: "POST",
            data: {
                type: report_type,
                vendor: vendor_id,
                date_range: filter_date_range,
            },
            cache: false,
            success: function (response) {
                if (response.roasTrailing) {
                    let responsedata = response.roasTrailing;
                    let month = [];
                    let value = [];
                    switch (report_type) {
                        case 0:
                            var vendor_roas_ytd_trailling = c3.generate({
                                bindto: d3.select('#' + DOM_id),
                                data: {
                                    x: 'x',
                                    json: {
                                        'x': [],
                                        'Sales': [],
                                    },
                                    colors: {
                                        'Sales': '#FFB371',
                                    },
                                    types: {
                                        'Sales': 'line',
                                    },
                                },
                                color: {
                                    pattern: ['#FFB371']
                                },
                                axis: {
                                    x: {
                                        type: 'category',
                                        show: false,
                                    },
                                    y: {
                                        show: false,
                                        tick: {
                                            format: d3.format('$,')
                                        }
                                    },
                                },
                                size: {
                                    height: 60,
                                    width: 150,
                                },
                                bar: {
                                    width: {
                                        ratio: 0.2 // this makes bar width 50% of length between ticks
                                    },
                                },
                                transition: {
                                    duration: 100
                                },
                                legend: {
                                    show: false,
                                    position: 'inset',
                                    inset: {
                                        anchor: 'top-left',
                                        x: undefined,
                                        y: 5,
                                        step: undefined
                                    }
                                },
                                tooltip: {
                                    show: true
                                }
                            });
                            for (var count = 0; count < responsedata.length; count++) {
                                month[count] = responsedata[count].month_name;
                                if (responsedata[count].month_name == 'January') {
                                    month[count] = 'Jan'
                                } else if (responsedata[count].month_name == 'February') {
                                    month[count] = 'Feb'
                                } else if (responsedata[count].month_name == 'March') {
                                    month[count] = 'Mar'
                                } else if (responsedata[count].month_name == 'April') {
                                    month[count] = 'Apr'
                                } else if (responsedata[count].month_name == 'May') {
                                    month[count] = 'May'
                                } else if (responsedata[count].month_name == 'June') {
                                    month[count] = 'June'
                                } else if (responsedata[count].month_name == 'July') {
                                    month[count] = 'July'
                                } else if (responsedata[count].month_name == 'August') {
                                    month[count] = 'Aug'
                                } else if (responsedata[count].month_name == 'September') {
                                    month[count] = 'Sept'
                                } else if (responsedata[count].month_name == 'October') {
                                    month[count] = 'Oct'
                                } else if (responsedata[count].month_name == 'November') {
                                    month[count] = 'Nov'
                                } else if (responsedata[count].month_name == 'December') {
                                    month[count] = 'Dec'
                                }
                                month[count] += '-' + responsedata[count].order_year;
                                value[count] = parseInt(responsedata[count].sales);
                            }
                            setTimeout(function () {
                                vendor_roas_ytd_trailling.load({
                                    json: {
                                        'x': month,
                                        'Sales': value,
                                    },
                                });
                            }, 1000);
                            break;
                        case 1:
                            var chart = c3.generate({
                                bindto: d3.select('#' + DOM_id),
                                data: {
                                    x: 'x',
                                    json: {
                                        'x': [],
                                        'Sales': [],
                                    },
                                    colors: {
                                        'Sales': '#FFB371',
                                    },
                                    types: {
                                        'Sales': 'line',
                                    },
                                },
                                color: {
                                    pattern: ['#FFB371']
                                },
                                axis: {
                                    x: {
                                        type: 'category',
                                        show: false,
                                    },
                                    y: {
                                        show: false,
                                        tick: {
                                            format: d3.format('$,')
                                        }
                                    },
                                },
                                size: {
                                    height: 60,
                                    width: 150,
                                },
                                bar: {
                                    width: {
                                        ratio: 0.2 // this makes bar width 50% of length between ticks
                                    },
                                },
                                transition: {
                                    duration: 100
                                },
                                legend: {
                                    show: false,
                                    position: 'inset',
                                    inset: {
                                        anchor: 'top-left',
                                        x: undefined,
                                        y: 5,
                                        step: undefined
                                    }
                                },
                                tooltip: {
                                    show: true
                                }
                            });
                            for (var count = 0; count < responsedata.length; count++) {
                                month[count] = responsedata[count].month_name;
                                if (responsedata[count].month_name == 'January') {
                                    month[count] = 'Jan'
                                } else if (responsedata[count].month_name == 'February') {
                                    month[count] = 'Feb'
                                } else if (responsedata[count].month_name == 'March') {
                                    month[count] = 'Mar'
                                } else if (responsedata[count].month_name == 'April') {
                                    month[count] = 'Apr'
                                } else if (responsedata[count].month_name == 'May') {
                                    month[count] = 'May'
                                } else if (responsedata[count].month_name == 'June') {
                                    month[count] = 'June'
                                } else if (responsedata[count].month_name == 'July') {
                                    month[count] = 'July'
                                } else if (responsedata[count].month_name == 'August') {
                                    month[count] = 'Aug'
                                } else if (responsedata[count].month_name == 'September') {
                                    month[count] = 'Sept'
                                } else if (responsedata[count].month_name == 'October') {
                                    month[count] = 'Oct'
                                } else if (responsedata[count].month_name == 'November') {
                                    month[count] = 'Nov'
                                } else if (responsedata[count].month_name == 'December') {
                                    month[count] = 'Dec'
                                }
                                month[count] += '-' + responsedata[count].order_year;
                                value[count] = parseInt(responsedata[count].sales);
                            }
                            setTimeout(function () {
                                chart.load({
                                    json: {
                                        'x': month,
                                        'Sales': value,
                                    },
                                });
                            }, 1000);
                            break;
                        default:
                            break;
                    }
                    $('#line_chart_label_ytd').html('Sales');
                }
            },
        });
    }
    function vendorRoasTrailingMtd(DOM_id, vendor_id, report_type) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let value = $("#filter_range_picker").val();
        let firstDate = moment(value, "MMM-YYYY").startOf('month').format("MM/DD/YYYY");
        let lastDate = moment(value, "MMM-YYYY").endOf('month').format("MM/DD/YYYY");
        var filter_date_range = firstDate + " - " + lastDate;
        $.ajax({
            url: base_url + "/ed/vendor/trailing/roas",
            type: "POST",
            data: {
                type: report_type,
                vendor: vendor_id,
                date_range: filter_date_range,
            },
            cache: false,
            success: function (response) {
                if (response.roasTrailing) {
                    let responsedata = response.roasTrailing;
                    let month = [];
                    let value = [];
                    switch (report_type) {
                        case 0:
                            var vendor_roas_ytd_trailling = c3.generate({
                                bindto: d3.select('#' + DOM_id),
                                data: {
                                    x: 'x',
                                    json: {
                                        'x': [],
                                        'Sales': [],
                                    },
                                    colors: {
                                        'Sales': '#FFB371',
                                    },
                                    types: {
                                        'Sales': 'line',
                                    },
                                },
                                color: {
                                    pattern: ['#FFB371']
                                },
                                axis: {
                                    x: {
                                        type: 'category',
                                        show: false,
                                    },
                                    y: {
                                        show: false,
                                        tick: {
                                            format: d3.format('$,')
                                        }
                                    },
                                },
                                size: {
                                    height: 60,
                                    width: 150,
                                },
                                bar: {
                                    width: {
                                        ratio: 0.2 // this makes bar width 50% of length between ticks
                                    },
                                },
                                transition: {
                                    duration: 100
                                },
                                legend: {
                                    show: false,
                                    position: 'inset',
                                    inset: {
                                        anchor: 'top-left',
                                        x: undefined,
                                        y: 5,
                                        step: undefined
                                    }
                                },
                                tooltip: {
                                    show: true
                                }
                            });
                            for (var count = 0; count < responsedata.length; count++) {
                                month[count] = responsedata[count].month_name;
                                if (responsedata[count].month_name == 'January') {
                                    month[count] = 'Jan'
                                } else if (responsedata[count].month_name == 'February') {
                                    month[count] = 'Feb'
                                } else if (responsedata[count].month_name == 'March') {
                                    month[count] = 'Mar'
                                } else if (responsedata[count].month_name == 'April') {
                                    month[count] = 'Apr'
                                } else if (responsedata[count].month_name == 'May') {
                                    month[count] = 'May'
                                } else if (responsedata[count].month_name == 'June') {
                                    month[count] = 'June'
                                } else if (responsedata[count].month_name == 'July') {
                                    month[count] = 'July'
                                } else if (responsedata[count].month_name == 'August') {
                                    month[count] = 'Aug'
                                } else if (responsedata[count].month_name == 'September') {
                                    month[count] = 'Sept'
                                } else if (responsedata[count].month_name == 'October') {
                                    month[count] = 'Oct'
                                } else if (responsedata[count].month_name == 'November') {
                                    month[count] = 'Nov'
                                } else if (responsedata[count].month_name == 'December') {
                                    month[count] = 'Dec'
                                }
                                month[count] += '-' + responsedata[count].order_year;
                                value[count] = parseInt(responsedata[count].sales);
                            }
                            setTimeout(function () {
                                vendor_roas_ytd_trailling.load({
                                    json: {
                                        'x': month,
                                        'Sales': value,
                                    },
                                });
                            }, 1000);
                            break;
                        case 1:
                            var chart = c3.generate({
                                bindto: d3.select('#' + DOM_id),
                                data: {
                                    x: 'x',
                                    json: {
                                        'x': [],
                                        'Sales': [],
                                    },
                                    colors: {
                                        'Sales': '#FFB371',
                                    },
                                    types: {
                                        'Sales': 'line',
                                    },
                                },
                                color: {
                                    pattern: ['#FFB371']
                                },
                                axis: {
                                    x: {
                                        type: 'category',
                                        show: false,
                                    },
                                    y: {
                                        show: false,
                                        tick: {
                                            format: d3.format('$,')
                                        }
                                    },
                                },
                                size: {
                                    height: 60,
                                    width: 150,
                                },
                                bar: {
                                    width: {
                                        ratio: 0.2 // this makes bar width 50% of length between ticks
                                    },
                                },
                                transition: {
                                    duration: 100
                                },
                                legend: {
                                    show: false,
                                    position: 'inset',
                                    inset: {
                                        anchor: 'top-left',
                                        x: undefined,
                                        y: 5,
                                        step: undefined
                                    }
                                },
                                tooltip: {
                                    show: true
                                }
                            });
                            for (var count = 0; count < responsedata.length; count++) {
                                month[count] = responsedata[count].month_name;
                                if (responsedata[count].month_name == 'January') {
                                    month[count] = 'Jan'
                                } else if (responsedata[count].month_name == 'February') {
                                    month[count] = 'Feb'
                                } else if (responsedata[count].month_name == 'March') {
                                    month[count] = 'Mar'
                                } else if (responsedata[count].month_name == 'April') {
                                    month[count] = 'Apr'
                                } else if (responsedata[count].month_name == 'May') {
                                    month[count] = 'May'
                                } else if (responsedata[count].month_name == 'June') {
                                    month[count] = 'June'
                                } else if (responsedata[count].month_name == 'July') {
                                    month[count] = 'July'
                                } else if (responsedata[count].month_name == 'August') {
                                    month[count] = 'Aug'
                                } else if (responsedata[count].month_name == 'September') {
                                    month[count] = 'Sept'
                                } else if (responsedata[count].month_name == 'October') {
                                    month[count] = 'Oct'
                                } else if (responsedata[count].month_name == 'November') {
                                    month[count] = 'Nov'
                                } else if (responsedata[count].month_name == 'December') {
                                    month[count] = 'Dec'
                                }
                                month[count] += '-' + responsedata[count].order_year;
                                value[count] = parseInt(responsedata[count].sales);
                            }
                            setTimeout(function () {
                                chart.load({
                                    json: {
                                        'x': month,
                                        'Sales': value,
                                    },
                                });
                            }, 1000);
                            break;
                        default:
                            break;
                    }
                    $('#line_chart_label_mtd').html('Sales');
                }
            },
        });
    }
    function shippedCogs3pTable(shippedCogsNcTable, report_type, filter_date_range) {
        switch (report_type) {
            case 0:
                $("#sc_table_cm_merge_3p").attr({
                    "title": "Current Month Shipped COGS"
                });
                $("#nc_table_cm_merge_3p").attr({
                    "title": "Current Month Net Received "
                });
                $("#sc_table_py_merge_3p").attr({
                    "title": "Previous Year Shipped COGS"
                });
                $("#nc_table_py_merge_3p").attr({
                    "title": "Previous Year  Net Received "
                });
                $('#sc_table_merge_3p').html('SHIPPED COGS & NET RECEIPTS');

                break;
            case 1:
                $("#sc_table_cm_merge_3p").attr({
                    "title": "Current Month Shipped Unit"
                });
                $("#nc_table_cm_merge_3p").attr({
                    "title": "Current Month Net Received  Unit"
                });
                $("#sc_table_py_merge_3p").attr({
                    "title": "Previous Year Shipped Unit"
                });
                $("#nc_table_py_merge_3p").attr({
                    "title": "Previous Year Net Received Unit"
                });
                $('#sc_table_merge_3p').html('SHIPPED UNIT & NET RECEIPTS UNIT');
                break;
        }
        let html = '';
        if (shippedCogsNcTable.length == 0) {
            html = "<tr>\n" +
                "    <td style='padding: 10px; white-space: nowrap;' align='center' colspan='10'>No data found</td>\n" +
                "</tr>";
            $('#all_vendor_shipped_cogs_ytd_3p').html(html);
            return;
        }
        switch (report_type) {
            case 0:
                for (var count = 0; count < shippedCogsNcTable.length; count++) {
                    let tr_style = '';
                    let tr_name_style = '';
                    //get conditional class style for ptp and yoy
                    let ptp_style = getConditionalClassStyle(shippedCogsNcTable[count].ptp_shipped_cogs);
                    let yoy_style = getConditionalClassStyle(shippedCogsNcTable[count].yoy_shipped_cogs);
                    let ptp_style_nc = getConditionalClassStyle(shippedCogsNcTable[count].ptp_net_received);
                    let yoy_style_nc = getConditionalClassStyle(shippedCogsNcTable[count].yoy_net_received);
                    if (shippedCogsNcTable[count].alert == "yes") {
                        tr_style = "table-danger";
                        tr_name_style = "background:#f9cfcf;";
                    }
                    html += "<tr class='" + tr_style + "'>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap; " + tr_name_style + "'><span value='" + shippedCogsNcTable[count].fk_vendor_id + "' id='merge_table_3p' name='merge_table_3p'  class='merge_table_3p' onclick=myFunction3p('" + shippedCogsNcTable[count].fk_vendor_id + "')><input type='hidden' id='get_id_3p'><a>" + shippedCogsNcTable[count].fk_vendor_name + "</a></span></td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].shipped_cogs + "</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].net_received + "</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].current_dropship + "</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].previous_shipped_cogs + "</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].previous_net_received + "</td>\n" +
                        "        <td class='text-center  " + ptp_style + "' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].ptp_shipped_cogs + "</td>\n" +
                        "        <td class='text-center  " + ptp_style_nc + "' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].ptp_net_received + "</td>\n" +
                        "        <td class='text-center  " + yoy_style + "' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].yoy_shipped_cogs + "</td>\n" +
                        "        <td class='text-center  " + yoy_style_nc + "' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].yoy_net_received + "</td>\n" +
                        "</tr>";
                }
                break;
            case 1:

                for (var count = 0; count < shippedCogsNcTable.length; count++) {
                    let tr_style = '';
                    let tr_name_style = '';
                    //get conditional class style for ptp and yoy
                    let ptp_style = getConditionalClassStyle(shippedCogsNcTable[count].ptp_shipped_units);
                    let yoy_style = getConditionalClassStyle(shippedCogsNcTable[count].yoy_shipped_units);
                    let ptp_style_nc = getConditionalClassStyle(shippedCogsNcTable[count].ptp_net_received_units);
                    let yoy_style_nc = getConditionalClassStyle(shippedCogsNcTable[count].yoy_net_received_units);
                    if (shippedCogsNcTable[count].alert == "yes") {
                        tr_style = "table-danger";
                        tr_name_style = "background:#f9cfcf;";
                    }
                    html += "<tr class='" + tr_style + "'>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap; " + tr_name_style + "'><span value='" + shippedCogsNcTable[count].fk_vendor_id + "' id='merge_table_3p' name='merge_table_3p'  class='merge_table_3p' onclick=myFunction3p('" + shippedCogsNcTable[count].fk_vendor_id + "')><input type='hidden' id='get_id_3p'><a>" + shippedCogsNcTable[count].fk_vendor_name + "</a></span></td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].shipped_units + "</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].net_received_units + "</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].current_dropship + "</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].previous_shipped_units + "</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].previous_net_received_units + "</td>\n" +
                        "        <td class='text-center  " + ptp_style + "' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].ptp_shipped_units + "</td>\n" +
                        "        <td class='text-center  " + ptp_style_nc + "' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].ptp_net_received_units + "</td>\n" +
                        "        <td class='text-center  " + yoy_style + "' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].yoy_shipped_units + "</td>\n" +
                        "        <td class='text-center  " + yoy_style_nc + "' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].yoy_net_received_units + "</td>\n" +
                        "</tr>";
                }
                break;
            default:
                break;
        }
        $('#all_vendor_shipped_cogs_ytd_3p').html(html);
    }
    function shippedCogsNcMergeTable(shippedCogsNcTable, report_type, filter_date_range) {
        switch (report_type) {
            case 0:
                $("#sc_table_cm_merge").attr({
                    "title": "Current Month Shipped COGS"
                });
                $("#nc_table_cm_merge").attr({
                    "title": "Current Month Net Received"
                });
                $("#sc_table_py_merge").attr({
                    "title": "Previous Year Shipped COGS"
                });
                $("#nc_table_py_merge").attr({
                    "title": "Previous Year  Net Received"
                });
                $('#sc_table_merge').html('SHIPPED COGS & NET RECEIPTS');

                break;
            case 1:
                $("#sc_table_cm_merge").attr({
                    "title": "Current Month Shipped Unit"
                });
                $("#nc_table_cm_merge").attr({
                    "title": "Current Month Net Received Unit"
                });
                $("#sc_table_py_merge").attr({
                    "title": "Previous Year Shipped Unit"
                });
                $("#nc_table_py_merge").attr({
                    "title": "Previous Year Net Received Unit"
                });
                $('#sc_table_merge').html('SHIPPED UNIT & NET RECEIPTS UNIT');
                break;
        }
        let html = '';
        let html1 = '';
        if (shippedCogsNcTable.length == 0) {
            html = "<tr>\n" +
                "    <td style='padding: 10px; white-space: nowrap;' align='center' colspan='10'>No data found</td>\n" +
                "</tr>";
            $('#nc_sc_all_vendor_shipped_cogs_ytd').html(html);
            return;
        }
        switch (report_type) {
            case 0:
                for (var count = 0; count < shippedCogsNcTable.length; count++) {
                    let tr_style = '';
                    let tr_name_style = '';
                    //get conditional class style for ptp and yoy
                    let ptp_style = getConditionalClassStyle(shippedCogsNcTable[count].ptp_shipped_cogs);
                    let yoy_style = getConditionalClassStyle(shippedCogsNcTable[count].yoy_shipped_cogs);
                    let ptp_style_nc = getConditionalClassStyle(shippedCogsNcTable[count].ptp_net_received);
                    let yoy_style_nc = getConditionalClassStyle(shippedCogsNcTable[count].yoy_net_received);
                    if (shippedCogsNcTable[count].alert == "yes") {
                        tr_style = "table-danger";
                        tr_name_style = "background:#f9cfcf;";
                    }
                    html += "<tr class='" + tr_style + "'>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap; " + tr_name_style + "'><span value='" + shippedCogsNcTable[count].fk_vendor_id + "' id='merge_table' name='merge_table'  class='merge_table' onclick=myFunction('" + shippedCogsNcTable[count].fk_vendor_id + "')><input type='hidden' id='get_id'><a>" + shippedCogsNcTable[count].fk_vendor_name + "</a></span></td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].shipped_cogs + "</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].net_received + "</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].current_dropship + "</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].previous_shipped_cogs + "</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].previous_net_received + "</td>\n" +
                        "        <td class='text-center  " + ptp_style + "' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].ptp_shipped_cogs + "</td>\n" +
                        "        <td class='text-center  " + ptp_style_nc + "' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].ptp_net_received + "</td>\n" +
                        "        <td class='text-center  " + yoy_style + "' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].yoy_shipped_cogs + "</td>\n" +
                        "        <td class='text-center  " + yoy_style_nc + "' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].yoy_net_received + "</td>\n" +
                        "</tr>";
                }
                break;
            case 1:

                for (var count = 0; count < shippedCogsNcTable.length; count++) {
                    let tr_style = '';
                    let tr_name_style = '';
                    //get conditional class style for ptp and yoy
                    let ptp_style = getConditionalClassStyle(shippedCogsNcTable[count].ptp_shipped_units);
                    let yoy_style = getConditionalClassStyle(shippedCogsNcTable[count].yoy_shipped_units);
                    let ptp_style_nc = getConditionalClassStyle(shippedCogsNcTable[count].ptp_net_received_units);
                    let yoy_style_nc = getConditionalClassStyle(shippedCogsNcTable[count].yoy_net_received_units);
                    if (shippedCogsNcTable[count].alert == "yes") {
                        tr_style = "table-danger";
                        tr_name_style = "background:#f9cfcf;";
                    }
                    html += "<tr class='" + tr_style + "'>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap; " + tr_name_style + "'><span value='" + shippedCogsNcTable[count].fk_vendor_id + "' id='merge_table' name='merge_table'  class='merge_table' onclick=myFunction('" + shippedCogsNcTable[count].fk_vendor_id + "')><input type='hidden' id='get_id'><a>" + shippedCogsNcTable[count].fk_vendor_name + "</a></span></td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].shipped_units + "</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].net_received_units + "</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].current_dropship + "</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].previous_shipped_units + "</td>\n" +
                        "        <td class='text-center' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].previous_net_received_units + "</td>\n" +
                        "        <td class='text-center  " + ptp_style + "' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].ptp_shipped_units + "</td>\n" +
                        "        <td class='text-center  " + ptp_style_nc + "' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].ptp_net_received_units + "</td>\n" +
                        "        <td class='text-center  " + ptp_style + "' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].yoy_shipped_units + "</td>\n" +
                        "        <td class='text-center  " + yoy_style_nc + "' style='padding: 10px; white-space: nowrap;'>" + shippedCogsNcTable[count].yoy_net_received_units + "</td>\n" +
                        "</tr>";
                }
                break;
            default:
                break;
        }
        $('#nc_sc_all_vendor_shipped_cogs_ytd').html(html);
    }
    function shippedCogsNcMergeGrandTotal(shippedCogsNCGrandTotal, report_type) {

        let shipped_cogs = "-";
        let current_dropship = "-";
        let previous_shipped_cogs = "-";

        let ptp_shipped_cogs = "-";
        let yoy_shipped_cogs = "-";
        let net_received = "-";

        let previous_net_received = "-";
        let ptp_net_received = "-";
        let yoy_net_received = "-";
        if (report_type == 0) {
            for (let i = 0; i < shippedCogsNCGrandTotal.length; i++) {
                if (shippedCogsNCGrandTotal[i].shipped_cogs != null) {
                    shipped_cogs = shippedCogsNCGrandTotal[i].shipped_cogs;
                }
                if (shippedCogsNCGrandTotal[i].current_dropship != null) {
                    current_dropship = shippedCogsNCGrandTotal[i].current_dropship;
                }
                if (shippedCogsNCGrandTotal[i].previous_shipped_cogs != null) {
                    previous_shipped_cogs = shippedCogsNCGrandTotal[i].previous_shipped_cogs;
                }

                if (shippedCogsNCGrandTotal[i].ptp_shipped_cogs != null) {
                    ptp_shipped_cogs = shippedCogsNCGrandTotal[i].ptp_shipped_cogs;
                }

                if (shippedCogsNCGrandTotal[i].yoy_shipped_cogs != null) {
                    yoy_shipped_cogs = shippedCogsNCGrandTotal[i].yoy_shipped_cogs;
                }
                if (shippedCogsNCGrandTotal[i].net_received != null) {
                    net_received = shippedCogsNCGrandTotal[i].net_received;
                }
                if (shippedCogsNCGrandTotal[i].previous_net_received != null) {
                    previous_net_received = shippedCogsNCGrandTotal[i].previous_net_received;
                }

                if (shippedCogsNCGrandTotal[i].ptp_net_received != null) {
                    ptp_net_received = shippedCogsNCGrandTotal[i].ptp_net_received;
                }
                if (shippedCogsNCGrandTotal[i].yoy_net_received != null) {
                    yoy_net_received = shippedCogsNCGrandTotal[i].yoy_net_received;
                }
            }
        } else {
            for (let i = 0; i < shippedCogsNCGrandTotal.length; i++) {
                if (shippedCogsNCGrandTotal[i].shipped_units != null) {
                    shipped_cogs = shippedCogsNCGrandTotal[i].shipped_units;
                }
                if (shippedCogsNCGrandTotal[i].current_dropship != null) {
                    current_dropship = shippedCogsNCGrandTotal[i].current_dropship;
                }
                if (shippedCogsNCGrandTotal[i].previous_shipped_units != null) {
                    previous_shipped_cogs = shippedCogsNCGrandTotal[i].previous_shipped_units;
                }

                if (shippedCogsNCGrandTotal[i].ptp_shipped_units != null) {
                    ptp_shipped_cogs = shippedCogsNCGrandTotal[i].ptp_shipped_units;
                }

                if (shippedCogsNCGrandTotal[i].yoy_shipped_units != null) {
                    yoy_shipped_cogs = shippedCogsNCGrandTotal[i].yoy_shipped_units;
                }
                if (shippedCogsNCGrandTotal[i].net_received_units != null) {
                    net_received = shippedCogsNCGrandTotal[i].net_received_units;
                }
                if (shippedCogsNCGrandTotal[i].previous_net_received_units != null) {
                    previous_net_received = shippedCogsNCGrandTotal[i].previous_net_received_units;
                }

                if (shippedCogsNCGrandTotal[i].ptp_net_received_units != null) {
                    ptp_net_received = shippedCogsNCGrandTotal[i].ptp_net_received_units;
                }
                if (shippedCogsNCGrandTotal[i].yoy_net_received_units != null) {
                    yoy_net_received = shippedCogsNCGrandTotal[i].yoy_net_received_units;
                }
            }
        }

        $('.shipped_cogs_current').html(shipped_cogs);
        $('.net_received').html(net_received);
        $('.current_dropship').html(current_dropship);

        $('.previous_shipped_cogs').html(previous_shipped_cogs);
        $('.previous_net_received').html(previous_net_received);
        $('.ptp_shipped_cogs').html(ptp_shipped_cogs);

        $('.ptp_net_received').html(ptp_net_received);
        $('.yoy_shipped_cogs').html(yoy_shipped_cogs);
        $('.yoy_net_received').html(yoy_net_received);
    }
    function getConditionalClassStyle(percentage) {
        let style = "text-center";
        let value = (percentage).replace(new RegExp("\\s|,|\\%", "gm"), "");
        if (value < 70) {
            style = "text-danger";
        }
        return style;
    }
    function getScNcTrailing(DOM_id, vendor_id, report_type) {
        $('#vendor_sc_nc_mtd').html('');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let value = $("#filter_range_picker").val();
        let firstDate = moment(value, "MMM-YYYY").startOf('month').format("MM/DD/YYYY");
        let lastDate = moment(value, "MMM-YYYY").endOf('month').format("MM/DD/YYYY");
        var filter_date_range = firstDate + " - " + lastDate;
        $.ajax({
            url: base_url + "/ed/vendor/trailing",
            type: "POST",
            data: {
                type: report_type,
                vendor: vendor_id,
                date_range: filter_date_range,
            },
            cache: false,
            success: function (response) {
                if (response.shippedCogsTrailing && response.netReceivedTrailing) {
                    let responsedata = response.shippedCogsTrailing;
                    let responsedata1 = response.netReceivedTrailing;
                    let month = [];
                    let value = [];
                    let month1 = [];
                    let value1 = [];
                    let sc_mon = [];
                    let nc_mon = [];
                    let name = response.name;
                    if (response.check != '3P') {
                        switch (report_type) {
                            case 0:
                                var chart = c3.generate({
                                    bindto: d3.select('#' + DOM_id),
                                    data: {
                                        x: 'x',
                                        json: {
                                            'x': [],
                                            'Shipped COGS': [],
                                            'Net Received': [],
                                        },
                                        colors: {
                                            'Shipped COGS': '#FFB371',
                                            'Net Received': '#4DD0EA',
                                        },

                                        types: {
                                            'Shipped COGS': 'bar',
                                            'Net Received': 'bar',
                                        },
                                        axes: {
                                            'Net Received': 'y2',
                                        },
                                    },
                                    axis: {
                                        x: {
                                            type: 'category',
                                            tick: {
                                                multiline: false,
                                            },
                                            height: 50,
                                        },
                                        y: {
                                            show: true,
                                            label: {
                                                text: 'value',
                                                position: 'outer-middle'
                                            },
                                            tick: {
                                                format: d3.format('$,')
                                            }
                                        },
                                        y2: {
                                            show: false,
                                            tick: {
                                                format: d3.format('$,')
                                            }
                                        },
                                    },
                                    size: {
                                        height: 250,
                                    },
                                    bar: {
                                        width: {
                                            ratio: 0.8 // this makes bar width 50% of length between ticks
                                        }
                                    },
                                    color: {
                                        pattern: ['#4DD0EA', '#FFB371']
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
                                        show: true
                                    }
                                });
                                for (var count = 0; count < responsedata.length; count++) {
                                    month[count] = responsedata[count].month_name;
                                    if (responsedata[count].month_name == 'January') {
                                        month[count] = 'Jan';
                                        value[count] = parseInt(responsedata[count].shipped_cogs);
                                    } else if (responsedata[count].month_name == 'February') {
                                        month[count] = 'Feb';
                                        value[count] = parseInt(responsedata[count].shipped_cogs);
                                    } else if (responsedata[count].month_name == 'March') {
                                        month[count] = 'Mar';
                                        value[count] = parseInt(responsedata[count].shipped_cogs);
                                    } else if (responsedata[count].month_name == 'April') {
                                        month[count] = 'Apr';
                                        value[count] = parseInt(responsedata[count].shipped_cogs);
                                    } else if (responsedata[count].month_name == 'May') {
                                        month[count] = 'May';
                                        value[count] = parseInt(responsedata[count].shipped_cogs);
                                    } else if (responsedata[count].month_name == 'June') {
                                        month[count] = 'Jun';
                                        value[count] = parseInt(responsedata[count].shipped_cogs);
                                    } else if (responsedata[count].month_name == 'July') {
                                        month[count] = 'Jul';
                                        value[count] = parseInt(responsedata[count].shipped_cogs);
                                    } else if (responsedata[count].month_name == 'August') {
                                        month[count] = 'Aug';
                                        value[count] = parseInt(responsedata[count].shipped_cogs);
                                    } else if (responsedata[count].month_name == 'September') {
                                        month[count] = 'Sept';
                                        value[count] = parseInt(responsedata[count].shipped_cogs);
                                    } else if (responsedata[count].month_name == 'October') {
                                        month[count] = 'Oct';
                                        value[count] = parseInt(responsedata[count].shipped_cogs);
                                    } else if (responsedata[count].month_name == 'November') {
                                        month[count] = 'Nov';
                                        value[count] = parseInt(responsedata[count].shipped_cogs);
                                    } else if (responsedata[count].month_name == 'December') {
                                        month[count] = 'Dec';
                                        value[count] = parseInt(responsedata[count].shipped_cogs);
                                    }
                                    month[count] += '-' + responsedata[count].order_year;
                                }
                                for (var count = 0; count < responsedata1.length; count++) {
                                    month1[count] = responsedata1[count].month_name;
                                    if (responsedata1[count].month_name == 'January') {
                                        month1[count] = 'Jan';
                                        value1[count] = parseInt(responsedata1[count].net_received);
                                    } else if (responsedata1[count].month_name == 'February') {
                                        month1[count] = 'Feb';
                                        value1[count] = parseInt(responsedata1[count].net_received);
                                    } else if (responsedata1[count].month_name == 'March') {
                                        month1[count] = 'Mar';
                                        value1[count] = parseInt(responsedata1[count].net_received);
                                    } else if (responsedata1[count].month_name == 'April') {
                                        month1[count] = 'Apr';
                                        value1[count] = parseInt(responsedata1[count].net_received);
                                    } else if (responsedata1[count].month_name == 'May') {
                                        month1[count] = 'May';
                                        value1[count] = parseInt(responsedata1[count].net_received);
                                    } else if (responsedata1[count].month_name == 'June') {
                                        month1[count] = 'Jun';
                                        value1[count] = parseInt(responsedata1[count].net_received);
                                    } else if (responsedata1[count].month_name == 'July') {
                                        month1[count] = 'Jul';
                                        value1[count] = parseInt(responsedata1[count].net_received);
                                    } else if (responsedata1[count].month_name == 'August') {
                                        month1[count] = 'Aug';
                                        value1[count] = parseInt(responsedata1[count].net_received);
                                    } else if (responsedata1[count].month_name == 'September') {
                                        month1[count] = 'Sept';
                                        value1[count] = parseInt(responsedata1[count].net_received);
                                    } else if (responsedata1[count].month_name == 'October') {
                                        month1[count] = 'Oct';
                                        value1[count] = parseInt(responsedata1[count].net_received);
                                    } else if (responsedata1[count].month_name == 'November') {
                                        month1[count] = 'Nov';
                                        value1[count] = parseInt(responsedata1[count].net_received);
                                    } else if (responsedata1[count].month_name == 'December') {
                                        month1[count] = 'Dec';
                                        value1[count] = parseInt(responsedata1[count].net_received);
                                    }
                                    month1[count] += '-' + responsedata1[count].order_year;
                                }
                                var merge_months = month.concat(month1.filter((item) => month.indexOf(item) < 0));
                                var i = 0;
                                for (var count2 = 0; count2 < merge_months.length; count2++) {
                                    var sc_month = "";
                                    var sc_month1 = "";
                                    for (var count3 = 0; count3 < responsedata.length; count3++) {
                                        if (responsedata[count3].month_name == 'January') {
                                            sc_mon[count3] = 'Jan';
                                        } else if (responsedata[count3].month_name == 'February') {
                                            sc_mon[count3] = 'Feb';
                                        } else if (responsedata[count3].month_name == 'March') {
                                            sc_mon[count3] = 'Mar';
                                        } else if (responsedata[count3].month_name == 'April') {
                                            sc_mon[count3] = 'Apr';
                                        } else if (responsedata[count3].month_name == 'May') {
                                            sc_mon[count3] = 'May';
                                        } else if (responsedata[count3].month_name == 'June') {
                                            sc_mon[count3] = 'Jun';
                                        } else if (responsedata[count3].month_name == 'July') {
                                            sc_mon[count3] = 'Jul';
                                        } else if (responsedata[count3].month_name == 'August') {
                                            sc_mon[count3] = 'Aug';
                                        } else if (responsedata[count3].month_name == 'September') {
                                            sc_mon[count3] = 'Sept';
                                        } else if (responsedata[count3].month_name == 'October') {
                                            sc_mon[count3] = 'Oct';
                                        } else if (responsedata[count3].month_name == 'November') {
                                            sc_mon[count3] = 'Nov';
                                        } else if (responsedata[count3].month_name == 'December') {
                                            sc_mon[count3] = 'Dec';
                                        }
                                        sc_month = sc_mon[count3] + "-" + responsedata[count3].order_year;
                                        if (merge_months[count2] === sc_month) {
                                            value[i] = parseInt(responsedata[count3].shipped_cogs);
                                            break;
                                        } else {
                                            value[i] = 0;
                                        }
                                    }
                                    for (var count4 = 0; count4 < responsedata1.length; count4++) {
                                        if (responsedata1[count4].month_name == 'January') {
                                            nc_mon[count4] = 'Jan';
                                        } else if (responsedata1[count4].month_name == 'February') {
                                            nc_mon[count4] = 'Feb';
                                        } else if (responsedata1[count4].month_name == 'March') {
                                            nc_mon[count4] = 'Mar';
                                        } else if (responsedata1[count4].month_name == 'April') {
                                            nc_mon[count4] = 'Apr';
                                        } else if (responsedata1[count4].month_name == 'May') {
                                            nc_mon[count4] = 'May';
                                        } else if (responsedata1[count4].month_name == 'June') {
                                            nc_mon[count4] = 'Jun';
                                        } else if (responsedata1[count4].month_name == 'July') {
                                            nc_mon[count4] = 'Jul';
                                        } else if (responsedata1[count4].month_name == 'August') {
                                            nc_mon[count4] = 'Aug';
                                        } else if (responsedata1[count4].month_name == 'September') {
                                            nc_mon[count4] = 'Sept';
                                        } else if (responsedata1[count4].month_name == 'October') {
                                            nc_mon[count4] = 'Oct';
                                        } else if (responsedata1[count4].month_name == 'November') {
                                            nc_mon[count4] = 'Nov';
                                        } else if (responsedata1[count4].month_name == 'December') {
                                            nc_mon[count4] = 'Dec';
                                        }
                                        sc_month1 = nc_mon[count4] + "-" + responsedata1[count4].order_year;
                                        if (merge_months[count2] === sc_month1) {
                                            value1[i] = parseInt(responsedata1[count4].net_received);
                                            break;
                                        } else {
                                            value1[i] = 0;
                                        }
                                    }
                                    i++;
                                }
                                setTimeout(function () {
                                    $('#model_header').html(name);
                                    $('#myModal').modal({ backdrop: 'static', keyboard: true });
                                }, 1000);
                                setTimeout(function () {
                                    chart.load({
                                        json: {
                                            'x': merge_months,
                                            'Shipped COGS': value,
                                            'Net Received': value1,
                                        },
                                    });
                                }, 2000);


                                break;
                            case 1:
                                var chart = c3.generate({
                                    bindto: d3.select('#' + DOM_id),
                                    data: {
                                        x: 'x',
                                        json: {
                                            'x': [],
                                            'Shipped Unit': [],
                                            'Net Received Unit': [],
                                        },
                                        colors: {
                                            'Shipped Unit': '#FFB371',
                                            'Net Received Unit': '#4DD0EA',
                                        },
                                        types: {
                                            'Shipped Unit': 'bar',
                                            'Net Received Unit': 'bar',
                                        },
                                        axes: {
                                            'Net Received Unit': 'y2',
                                        },
                                    },
                                    axis: {
                                        x: {
                                            type: 'category',
                                            // show: true
                                            tick: {
                                                multiline: false,
                                            },
                                            height: 50,
                                        },
                                        y: {
                                            show: true,
                                            label: {
                                                text: 'value',
                                                position: 'outer-middle'
                                            },
                                            tick: {
                                                format: d3.format(',')
                                            }
                                        },
                                        y2: {
                                            show: false,
                                            tick: {
                                                format: d3.format(',')
                                            }
                                        },
                                    },
                                    size: {
                                        height: 250,
                                    },
                                    bar: {
                                        width: {
                                            ratio: 0.8 // this makes bar width 50% of length between ticks
                                        },
                                    },
                                    color: {
                                        pattern: ['#4DD0EA', '#FFB371']
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
                                    }
                                });
                                for (var count = 0; count < responsedata.length; count++) {
                                    month[count] = responsedata[count].month_name;
                                    if (responsedata[count].month_name == 'January') {
                                        month[count] = 'Jan';
                                        value[count] = parseInt(responsedata[count].shipped_units);
                                    } else if (responsedata[count].month_name == 'February') {
                                        month[count] = 'Feb';
                                        value[count] = parseInt(responsedata[count].shipped_units);
                                    } else if (responsedata[count].month_name == 'March') {
                                        month[count] = 'Mar';
                                        value[count] = parseInt(responsedata[count].shipped_units);
                                    } else if (responsedata[count].month_name == 'April') {
                                        month[count] = 'Apr';
                                        value[count] = parseInt(responsedata[count].shipped_units);
                                    } else if (responsedata[count].month_name == 'May') {
                                        month[count] = 'May';
                                        value[count] = parseInt(responsedata[count].shipped_units);
                                    } else if (responsedata[count].month_name == 'June') {
                                        month[count] = 'Jun';
                                        value[count] = parseInt(responsedata[count].shipped_units);
                                    } else if (responsedata[count].month_name == 'July') {
                                        month[count] = 'Jul';
                                        value[count] = parseInt(responsedata[count].shipped_units);
                                    } else if (responsedata[count].month_name == 'August') {
                                        month[count] = 'Aug';
                                        value[count] = parseInt(responsedata[count].shipped_units);
                                    } else if (responsedata[count].month_name == 'September') {
                                        month[count] = 'Sept';
                                        value[count] = parseInt(responsedata[count].shipped_units);
                                    } else if (responsedata[count].month_name == 'October') {
                                        month[count] = 'Oct';
                                        value[count] = parseInt(responsedata[count].shipped_units);
                                    } else if (responsedata[count].month_name == 'November') {
                                        month[count] = 'Nov';
                                        value[count] = parseInt(responsedata[count].shipped_units);
                                    } else if (responsedata[count].month_name == 'December') {
                                        month[count] = 'Dec';
                                        value[count] = parseInt(responsedata[count].shipped_units);
                                    }
                                    month[count] += '-' + responsedata[count].order_year;
                                }
                                for (var count = 0; count < responsedata1.length; count++) {
                                    month1[count] = responsedata1[count].month_name;
                                    if (responsedata1[count].month_name == 'January') {
                                        month1[count] = 'Jan';
                                        value1[count] = parseInt(responsedata1[count].net_received_units);
                                    } else if (responsedata1[count].month_name == 'February') {
                                        month1[count] = 'Feb';
                                        value1[count] = parseInt(responsedata1[count].net_received_units);
                                    } else if (responsedata1[count].month_name == 'March') {
                                        month1[count] = 'Mar';
                                        value1[count] = parseInt(responsedata1[count].net_received_units);
                                    } else if (responsedata1[count].month_name == 'April') {
                                        month1[count] = 'Apr';
                                        value1[count] = parseInt(responsedata1[count].net_received_units);
                                    } else if (responsedata1[count].month_name == 'May') {
                                        month1[count] = 'May';
                                        value1[count] = parseInt(responsedata1[count].net_received_units);
                                    } else if (responsedata1[count].month_name == 'June') {
                                        month1[count] = 'Jun';
                                        value1[count] = parseInt(responsedata1[count].net_received_units);
                                    } else if (responsedata1[count].month_name == 'July') {
                                        month1[count] = 'Jul';
                                        value1[count] = parseInt(responsedata1[count].net_received_units);
                                    } else if (responsedata1[count].month_name == 'August') {
                                        month1[count] = 'Aug';
                                        value1[count] = parseInt(responsedata1[count].net_received_units);
                                    } else if (responsedata1[count].month_name == 'September') {
                                        month1[count] = 'Sept';
                                        value1[count] = parseInt(responsedata1[count].net_received_units);
                                    } else if (responsedata1[count].month_name == 'October') {
                                        month1[count] = 'Oct';
                                        value1[count] = parseInt(responsedata1[count].net_received_units);
                                    } else if (responsedata1[count].month_name == 'November') {
                                        month1[count] = 'Nov';
                                        value1[count] = parseInt(responsedata1[count].net_received_units);
                                    } else if (responsedata1[count].month_name == 'December') {
                                        month1[count] = 'Dec';
                                        value1[count] = parseInt(responsedata1[count].net_received_units);
                                    }
                                    month1[count] += '-' + responsedata1[count].order_year;
                                }
                                var merge_months = month.concat(month1.filter((item) => month.indexOf(item) < 0));
                                var i = 0;
                                for (var count2 = 0; count2 < merge_months.length; count2++) {
                                    var sc_month = "";
                                    var sc_month1 = "";
                                    for (var count3 = 0; count3 < responsedata.length; count3++) {
                                        if (responsedata[count3].month_name == 'January') {
                                            sc_mon[count3] = 'Jan';
                                        } else if (responsedata[count3].month_name == 'February') {
                                            sc_mon[count3] = 'Feb';
                                        } else if (responsedata[count3].month_name == 'March') {
                                            sc_mon[count3] = 'Mar';
                                        } else if (responsedata[count3].month_name == 'April') {
                                            sc_mon[count3] = 'Apr';
                                        } else if (responsedata[count3].month_name == 'May') {
                                            sc_mon[count3] = 'May';
                                        } else if (responsedata[count3].month_name == 'June') {
                                            sc_mon[count3] = 'Jun';
                                        } else if (responsedata[count3].month_name == 'July') {
                                            sc_mon[count3] = 'Jul';
                                        } else if (responsedata[count3].month_name == 'August') {
                                            sc_mon[count3] = 'Aug';
                                        } else if (responsedata[count3].month_name == 'September') {
                                            sc_mon[count3] = 'Sept';
                                        } else if (responsedata[count3].month_name == 'October') {
                                            sc_mon[count3] = 'Oct';
                                        } else if (responsedata[count3].month_name == 'November') {
                                            sc_mon[count3] = 'Nov';
                                        } else if (responsedata[count3].month_name == 'December') {
                                            sc_mon[count3] = 'Dec';
                                        }
                                        sc_month = sc_mon[count3] + "-" + responsedata[count3].order_year;
                                        if (merge_months[count2] === sc_month) {
                                            value[i] = parseInt(responsedata[count3].shipped_units);
                                            break;
                                        } else {
                                            value[i] = 0;
                                        }
                                    }
                                    for (var count4 = 0; count4 < responsedata1.length; count4++) {
                                        if (responsedata1[count4].month_name == 'January') {
                                            nc_mon[count4] = 'Jan';
                                        } else if (responsedata1[count4].month_name == 'February') {
                                            nc_mon[count4] = 'Feb';
                                        } else if (responsedata1[count4].month_name == 'March') {
                                            nc_mon[count4] = 'Mar';
                                        } else if (responsedata1[count4].month_name == 'April') {
                                            nc_mon[count4] = 'Apr';
                                        } else if (responsedata1[count4].month_name == 'May') {
                                            nc_mon[count4] = 'May';
                                        } else if (responsedata1[count4].month_name == 'June') {
                                            nc_mon[count4] = 'Jun';
                                        } else if (responsedata1[count4].month_name == 'July') {
                                            nc_mon[count4] = 'Jul';
                                        } else if (responsedata1[count4].month_name == 'August') {
                                            nc_mon[count4] = 'Aug';
                                        } else if (responsedata1[count4].month_name == 'September') {
                                            nc_mon[count4] = 'Sept';
                                        } else if (responsedata1[count4].month_name == 'October') {
                                            nc_mon[count4] = 'Oct';
                                        } else if (responsedata1[count4].month_name == 'November') {
                                            nc_mon[count4] = 'Nov';
                                        } else if (responsedata1[count4].month_name == 'December') {
                                            nc_mon[count4] = 'Dec';
                                        }
                                        sc_month1 = nc_mon[count4] + "-" + responsedata1[count4].order_year;
                                        if (merge_months[count2] === sc_month1) {
                                            value1[i] = parseInt(responsedata1[count4].net_received_units);
                                            break;
                                        } else {
                                            value1[i] = 0;
                                        }
                                    }
                                    i++;
                                }
                                setTimeout(function () {
                                    $('#model_header').html(name);
                                    $('#myModal').modal({ backdrop: 'static', keyboard: true });
                                }, 1000);
                                setTimeout(function () {
                                    chart.load({
                                        json: {
                                            'x': merge_months,
                                            'Shipped Unit': value,
                                            'Net Received Unit': value1,
                                        },
                                    });
                                }, 2000);

                                break;
                            default:
                                break;
                        }
                    } else {
                        switch (report_type) {
                            case 0:
                                var chart = c3.generate({
                                    bindto: d3.select('#' + DOM_id),
                                    data: {
                                        x: 'x',
                                        json: {
                                            'x': [],
                                            'Ordered Product': [],
                                        },
                                        colors: {
                                            'Ordered Product': '#FFB371',
                                        },

                                        types: {
                                            'Ordered Product': 'bar',
                                        },
                                    },
                                    axis: {
                                        x: {
                                            type: 'category',
                                            // show: true,
                                            tick: {
                                                multiline: false,
                                            },
                                            height: 50,
                                        },
                                        y: {
                                            show: true,
                                            label: {
                                                text: 'value',
                                                position: 'outer-middle'
                                            },
                                            tick: {
                                                format: d3.format('$,')
                                            }
                                        },
                                        y2: {
                                            show: false,
                                            tick: {
                                                format: d3.format('$,')
                                            }
                                        },

                                    },
                                    size: {
                                        height: 250,
                                    },
                                    bar: {
                                        width: {
                                            ratio: 0.8 // this makes bar width 50% of length between ticks
                                        }
                                    },
                                    color: {
                                        pattern: ['#FFB371']
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
                                        show: true
                                    }
                                });
                                for (var count = 0; count < responsedata.length; count++) {
                                    month[count] = responsedata[count].month_name;
                                    if (responsedata[count].month_name == 'January') {
                                        month[count] = 'Jan'
                                    } else if (responsedata[count].month_name == 'February') {
                                        month[count] = 'Feb'
                                    } else if (responsedata[count].month_name == 'March') {
                                        month[count] = 'Mar'
                                    } else if (responsedata[count].month_name == 'April') {
                                        month[count] = 'Apr'
                                    } else if (responsedata[count].month_name == 'May') {
                                        month[count] = 'May'
                                    } else if (responsedata[count].month_name == 'June') {
                                        month[count] = 'Jun'
                                    } else if (responsedata[count].month_name == 'July') {
                                        month[count] = 'Jul'
                                    } else if (responsedata[count].month_name == 'August') {
                                        month[count] = 'Aug'
                                    } else if (responsedata[count].month_name == 'September') {
                                        month[count] = 'Sept'
                                    } else if (responsedata[count].month_name == 'October') {
                                        month[count] = 'Oct'
                                    } else if (responsedata[count].month_name == 'November') {
                                        month[count] = 'Nov'
                                    } else if (responsedata[count].month_name == 'December') {
                                        month[count] = 'Dec'
                                    }
                                    month[count] += '-' + responsedata[count].order_year;
                                    value[count] = parseInt(responsedata[count].ordered_product_sales);
                                }
                                setTimeout(function () {
                                    $('#model_header').html(name);
                                    $('#myModal').modal({ backdrop: 'static', keyboard: true });
                                }, 1000);
                                setTimeout(function () {
                                    chart.load({
                                        json: {
                                            'x': month,
                                            'Ordered Product': value,
                                        },
                                    });
                                }, 2000);
                                break;
                            case 1:
                                var chart = c3.generate({
                                    bindto: d3.select('#' + DOM_id),
                                    data: {
                                        x: 'x',
                                        json: {
                                            'x': [],
                                            'Ordered Product Unit': [],
                                        },
                                        colors: {
                                            'Ordered Product Unit': '#FFB371',
                                        },
                                        types: {
                                            'Ordered Product Unit': 'bar',
                                        },
                                    },
                                    axis: {
                                        x: {
                                            type: 'category',
                                            tick: {
                                                multiline: false,
                                            },
                                            height: 50,
                                        },
                                        y: {
                                            show: true,
                                            label: {
                                                text: 'value',
                                                position: 'outer-middle'
                                            },
                                            tick: {
                                                format: d3.format(',')
                                            }
                                        },
                                        y2: {
                                            show: false,
                                            tick: {
                                                format: d3.format(',')
                                            }
                                        },

                                    },
                                    size: {
                                        height: 250,
                                    },
                                    bar: {
                                        width: {
                                            ratio: 0.8 // this makes bar width 50% of length between ticks
                                        }
                                    },
                                    color: {
                                        pattern: ['#FFB371']
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
                                        show: true
                                    }
                                });
                                for (var count = 0; count < responsedata.length; count++) {
                                    month[count] = responsedata[count].month_name;
                                    if (responsedata[count].month_name == 'January') {
                                        month[count] = 'Jan'
                                    } else if (responsedata[count].month_name == 'February') {
                                        month[count] = 'Feb'
                                    } else if (responsedata[count].month_name == 'March') {
                                        month[count] = 'Mar'
                                    } else if (responsedata[count].month_name == 'April') {
                                        month[count] = 'Apr'
                                    } else if (responsedata[count].month_name == 'May') {
                                        month[count] = 'May'
                                    } else if (responsedata[count].month_name == 'June') {
                                        month[count] = 'Jun'
                                    } else if (responsedata[count].month_name == 'July') {
                                        month[count] = 'Jul'
                                    } else if (responsedata[count].month_name == 'August') {
                                        month[count] = 'Aug'
                                    } else if (responsedata[count].month_name == 'September') {
                                        month[count] = 'Sept'
                                    } else if (responsedata[count].month_name == 'October') {
                                        month[count] = 'Oct'
                                    } else if (responsedata[count].month_name == 'November') {
                                        month[count] = 'Nov'
                                    } else if (responsedata[count].month_name == 'December') {
                                        month[count] = 'Dec'
                                    }
                                    month[count] += '-' + responsedata[count].order_year;
                                    value[count] = parseInt(responsedata[count].units_ordered);
                                }
                                setTimeout(function () {
                                    $('#model_header').html(name);
                                    $('#myModal').modal({ backdrop: 'static', keyboard: true });
                                }, 1000);
                                setTimeout(function () {
                                    chart.load({
                                        json: {
                                            'x': month,
                                            'Ordered Product Unit': value,
                                        },
                                    });
                                }, 2000);
                                break;
                            default:
                                break;
                        }
                    }
                }
            },
        });
    }
});
function numberWithCommas(x) {
    x = parseFloat(x).toFixed(0)
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}
function sortTable(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("nc_sc_Table");
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
function sortTable1(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("shipCogsTable3p");
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
function myFunction(a) {
    document.getElementById("get_id").value = a;
}
function myFunction3p(a) {
    document.getElementById("get_id_3p").value = a;
}


