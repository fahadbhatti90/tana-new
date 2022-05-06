@extends('layouts.app')

@section('content')
@if(checkUserPermission(array(5),1))
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <div class="row ">
            <div class="col-12">
                <h2 class="content-header-title headline_text float-left mb-0" @if(Auth::user()->profile->profile_mode == "dark-layout")
                    style="color: #ebeefd; border-right: 0px solid #d6dce1;"
                    @else
                    style="border-right: 0px solid #d6dce1;"
                    @endif
                    >EXECUTIVE DASHBOARD</h2>
            </div>
        </div>
    </div>
    <div class="content-header-right text-md-right col-md-6 col-12 d-md-block ">
        <div class="form-group breadcrum-right">
            <div class="dropdown">
                <h3 class="content-header-title mb-0">
                    <a class="customizer-toggle subhead_text" href="javascript:void(0)" @if(Auth::user()->profile->profile_mode == "dark-layout")
                        style="color: #C2C6DC;"
                        @else
                        style="color: #636363;"
                        @endif
                        >
                        <i class="feather icon-calendar"></i><span id="selected_date_text">-</span>
                    </a>
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="content-body" id="ED_1p">
    <section id="page-account-settings">
        <h4 class="subhead_text py-1 mx-1 mb-0 font-medium-2">YTD REPORT</h4>
        <div class="row">
            <div class="col-xl-6 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12">
                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body d-flex justify-content-center align-items-center" style="padding: 0.8rem;">
                                <h4 class="card-title subhead_text">{{ Auth::user()->getGlobalBrand() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center">
                                            <!-- <h2 class="subhead_text font-large-1 text-bold-700 mt-2" id="sc_ytd">-</h2> -->
                                            <h2 class="subhead_text font-large-1 mt-2" id="sc_ytd">-</h2>
                                        </div>
                                        <div class="col-sm-12 col-12 d-flex justify-content-center body_text">
                                            <div id="shipped_cogs_ytd">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center body_text">
                                            <p id="sc_ytd_title"> Net Receipts YTD</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center">
                                            <!-- <h2 class="subhead_text font-large-1 text-bold-700 mt-2" id="nr_ytd">-</h2> -->
                                            <h2 class="subhead_text font-large-1 mt-2" id="nr_ytd">-</h2>
                                        </div>
                                        <div class="col-sm-12 col-12 d-flex justify-content-center body_text">
                                            <div id="net_receipts_ytd">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center body_text">
                                            <p id="nr_ytd_title"> Net Receipts YTD</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12">
                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body d-flex justify-content-center align-items-center" style="padding: 0.8rem;">
                                <h4 class="card-title subhead_text" id="vendor_name_card">{{ $edVendor_name }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4 col-sm-4">
                        <div id="vendor_shipped_cog_ytd_card" class="card">
                            <div class=" list-group-flush customer-info" style="margin-bottom: 1.1em;">
                                <div class="d-lg-flex justify-content-center align-items-center">
                                    <div class="col-12 d-flex justify-content-center">
                                        <div id="vendor_shipped_cogs_ytd">
                                        </div>
                                    </div>
                                </div>
                                <div class="row avg-sessions pt-50">
                                    <div class="col-12 text-center body_text">
                                        <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="vendor_sc_value">-</p>
                                        <p class="mb-12" id="vendor_sc_type">Shipped COGS</p>
                                    </div>
                                    <div class="col-12 text-center body_text">
                                        <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="vendor_ptp_sc_value">-</p>
                                        <p class="mb-75">
                                            <span id="vendor_ptp_sc_ytd_percentage">%</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="d-lg-flex justify-content-center align-items-center">
                                    <div class="col-12 d-flex justify-content-center">
                                        <div id="vendor_last6_shipped_cogs_ytd">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4 col-sm-4">
                        <div class="card">
                            <div class=" list-group-flush customer-info" style="margin-bottom: 1.1em;">
                                <div class="d-lg-flex justify-content-center align-items-center">
                                    <div class="col-12 d-flex justify-content-center">
                                        <div id="vendor_net_receipts_ytd">
                                        </div>
                                    </div>
                                </div>
                                <div class="row avg-sessions pt-50">
                                    <div class="col-12 text-center body_text">
                                        <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="vendor_nr_value">-</p>
                                        <p class="mb-12" id="vendor_nr_type">Net Receipts</p>
                                    </div>
                                    <div class="col-12 text-center body_text">
                                        <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="vendor_ptp_nr_value">-</p>
                                        <p class="mb-75">
                                            <span id="vendor_ptp_nr_ytd_percentage">%</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="d-lg-flex justify-content-center align-items-center">
                                    <div class="col-12 d-flex justify-content-center">
                                        <div id="vendor_last6_net_receipts_ytd">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4 col-sm-4">
                        <div class="card">
                            <div class=" list-group-flush customer-info">
                                <div class="row avg-sessions">
                                    <div class="col-12 d-flex justify-content-center align-items-center">
                                        <div class="justify-content-center">
                                            <div id="vendor_trailing_roas_ytd">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center body_text" style="margin-top: -19px;">
                                        <p class="text-bold-600 font-medium-4" id="vendor_roas_value"></p>
                                        <p class="mb-12" id="vendor_roas_type_ytd">ROAS</p>
                                    </div>
                                    <div class="col-12 d-flex justify-content-center align-items-center">
                                        <div class="justify-content-center">
                                            <div id="vendor_line_roas_ytd">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-center body_text">
                                        <p class="text-center" id="line_chart_label_ytd"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h4 class="subhead_text py-1 mx-1 mb-0 font-medium-2" style="border-top: 2px solid #5d5d5d4a;">MTD REPORT</h4>
        <div class="row">
            <div class="col-xl-6 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12">
                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body d-flex justify-content-center align-items-center subhead_text" style="padding: 0.8rem;">
                                <h4 class="card-title">{{ Auth::user()->getGlobalBrand() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center subhead_text">
                                            <h2 class="subhead_text font-large-1 mt-2" id="sc_mtd">-</h2>
                                        </div>
                                        <div class="col-sm-12 col-12 d-flex justify-content-center body_text">
                                            <div id="shipped_cogs_mtd">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center body_text">
                                            <p id="sc_mtd_title">Shipped COGS MTD</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center subhead_text">
                                            <h2 class="subhead_text font-large-1 mt-2" id="nr_mtd">-</h2>
                                        </div>
                                        <div class="col-sm-12 col-12 d-flex justify-content-center body_text">
                                            <div id="net_receipts_mtd">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center body_text">
                                            <p id="nr_mtd_title">Net Receipts MTD</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12">
                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body d-flex justify-content-center align-items-center subhead_text" style="padding: 0.8rem;">
                                <h4 class="card-title" id="vendor_name_card_mtd">{{ $edVendor_name }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4 col-sm-4">
                        <div id="vendor_shipped_cog_mtd_card" class="card">
                            <div class=" list-group-flush customer-info" style="margin-bottom: 1.1em;">
                                <div class="d-lg-flex justify-content-center align-items-center">
                                    <div class="col-12 d-flex justify-content-center">
                                        <div id="vendor_shipped_cogs_mtd">
                                        </div>
                                    </div>
                                </div>
                                <div class="row avg-sessions pt-50">
                                    <div class="col-12 text-center body_text">
                                        <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="vendor_sc_value_mtd">-</p>
                                        <p class="mb-12" id="vendor_sc_type_mtd">Shipped COGS</p>
                                    </div>
                                    <div class="col-12 text-center body_text">
                                        <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="vendor_ptp_sc_value_mtd">-</p>
                                        <p class="mb-75">
                                            <span id="vendor_ptp_sc_mtd_percentage">%</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="d-lg-flex justify-content-center align-items-center">
                                    <div class="col-12 d-flex justify-content-center">
                                        <div id="vendor_last6_shipped_cogs_mtd">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4 col-sm-4">
                        <div class="card">
                            <div class=" list-group-flush customer-info" style="margin-bottom: 1.1em;">
                                <div class="d-lg-flex justify-content-center align-items-center">
                                    <div class="col-12 d-flex justify-content-center">
                                        <div id="vendor_net_receipts_mtd">
                                        </div>
                                    </div>
                                </div>
                                <div class="row avg-sessions pt-50">
                                    <div class="col-12 text-center body_text">
                                        <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="vendor_nr_value_mtd">-</p>
                                        <p class="mb-12" id="vendor_nr_type_mtd">Net Receipts</p>
                                    </div>
                                    <div class="col-12 text-center body_text">
                                        <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="vendor_ptp_nr_value_mtd">-</p>
                                        <p class="mb-75">
                                            <span id="vendor_ptp_nr_mtd_percentage">%</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="d-lg-flex justify-content-center align-items-center">
                                    <div class="col-12 d-flex justify-content-center">
                                        <div id="vendor_last6_net_receipts_mtd">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4 col-sm-4">
                        <div class="card">
                            <div class=" list-group-flush customer-info">
                                <div class="row avg-sessions">
                                    <div class="col-12 d-flex justify-content-center align-items-center">
                                        <div class="justify-content-center">
                                            <div id="vendor_trailing_roas_mtd">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center body_text" style="margin-top: -19px;">
                                        <p class="text-bold-600 font-medium-4" id="vendor_roas_value_mtd"></p>
                                        <p class="mb-12" id="vendor_roas_type_mtd">ROAS</p>
                                    </div>
                                    <div class="col-12 d-flex justify-content-center align-items-center">
                                        <div class="justify-content-center">
                                            <div id="vendor_line_roas_mtd">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-center body_text">
                                        <p class="text-center" id="line_chart_label_mtd"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="tablesDiv1">
            <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header col-xl-12 col-md-12 col-sm-12">
                            <h4 class="card-title headline_text" id="sc_table_merge">SHIPPED COGS & NET RECEIPTS </h4>
                            <div class="collapse-option-switch custom-switch-warning">
                                <div class='custom-control custom-switch custom-control-inline headline_text headline_text'>
                                    <p class="pt-20">YTD/MTD</p>
                                    <input type='checkbox' class='status custom-control-input' style='background-color: #FFD2A0 !important;' name='mtd-ytd-nc-sc-switch' id='mtd-ytd-nc-sc-switch' checked />
                                    <label class='custom-control-label ml-1' for='mtd-ytd-nc-sc-switch'>
                                        <span class="switch-text-right">YT</span>
                                        <span class="switch-text-left">MT</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive" style="overflow: auto; max-height: 60vh;">
                                <table class="table table-striped mb-0" id="nc_sc_Table">
                                    <thead class="thead subhead_text">
                                        <tr style="height: 100px;">
                                            <th class="text-center align-middle" style="white-space: nowrap; border: 1px solid #f8f8f8;  background: white; position: sticky; top: -1px;">Vendor<i class="fa fa-fw fa-sort" onclick="sortTable(0)"></i> </th>
                                            <th class="text-center" style="white-space: nowrap;  border: 1px solid #f8f8f8;  background: white; position: sticky;" id="sc_table_cm_merge_3p" title="Current Month Shipped COGS">Shipped COGS<br>Current Month<i class="fa fa-fw fa-sort" onclick="sortTable(1)"></i><br><br><span class=" shipped_cogs_current">-</span></th>
                                            <th class="text-center" style="white-space: nowrap;  border: 1px solid #f8f8f8;  background: white; position: sticky;" id="nc_table_cm_merge_3p" title="Current Month Net Recipts">Net Receipt<br> Current Month<i class="fa fa-fw fa-sort" onclick="sortTable(2)"></i><br><br><span class="net_received">-</span></th>
                                            <th class="text-center" style="white-space: nowrap;  border: 1px solid #f8f8f8;  background: white; position: sticky;" title="Direct Fulfillment">Direct Fulfillment<i class="fa fa-fw fa-sort" onclick="sortTable(3)"></i><br><br><br><span class="current_dropship">-</span> </th>
                                            <th class="text-center" style="white-space: nowrap;  border: 1px solid #f8f8f8;  background: white; position: sticky;" id="sc_table_py_merge_3p" title="Previous Year Shipped COGS">Shipped COGS<br>Previous Year<i class="fa fa-fw fa-sort" onclick="sortTable(4)"></i><br><br><span class="previous_shipped_cogs">-</span> </th>
                                            <th class="text-center" style="white-space: nowrap;  border: 1px solid #f8f8f8;  background: white; position: sticky;" id="Nc_table_py_merge_3p" title="Previous Year Net Recipts">Net Receipt<br> Previous Year<i class="fa fa-fw fa-sort" onclick="sortTable(5)"></i><br><br><span class="previous_net_received">-</span> </th>
                                            <th class="text-center" style="white-space: nowrap;  border: 1px solid #f8f8f8;  background: white; position: sticky;" title="Percent To Plan">Shipped COGS PTP<i class="fa fa-fw fa-sort" onclick="sortTable(6)"></i><br><br><br> <span class="ptp_shipped_cogs">-</span></th>
                                            <th class="text-center" style="white-space: nowrap;  border: 1px solid #f8f8f8;  background: white; position: sticky;" title="Percent To Plan">Net Receipt PTP<i class="fa fa-fw fa-sort" onclick="sortTable(7)"></i><br><br><br><span class="ptp_net_received">-</span> </th>
                                            <th class="text-center" style="white-space: nowrap;  border: 1px solid #f8f8f8;  background: white; position: sticky;" title="SC Year Over Year">Shipped COGS YOY<i class="fa fa-fw fa-sort" onclick="sortTable(8)"></i><br><br><br><span class="yoy_shipped_cogs">-</span> </th>
                                            <th class="text-center" style="white-space: nowrap;  border: 1px solid #f8f8f8;  background: white; position: sticky;" title="NC Year Over Year">Net Receipt YOY<i class="fa fa-fw fa-sort" onclick="sortTable(9)"></i><br><br><br><span class="yoy_net_received">-</span> </th>
                                        </tr>
                                    </thead>
                                    <tbody id="nc_sc_all_vendor_shipped_cogs_ytd" class="tbody body_text">
                                        <tr>
                                            <td style='padding: 10px; white-space: nowrap;' align='center' colspan='10'>No data found</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- account setting page end -->
</div>
<!-- //model for trailling -->
<div class="container">
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" width="100%">
                <div class="modal-header" style="display: block; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" class="col-xl-8" id="model_header">Vendor Name</h4>
                </div>
                <div class="modal-body">
                    <div id="vendor_sc_nc_mtd" width="100%" style="min-height: 250px; margin-left:-25px; padding-top: 10px;">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- end model here -->

<!-- design for 3p  -->
<div class="row" id="ED_3p" hidden>
    <div class="col-xl-6 col-md-12 col-sm-12">
        <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="card" style="margin-bottom: 1rem;">
                    <div class="card-body d-flex justify-content-center align-items-center subhead_text" style="padding: 0.8rem;">
                        <h4>YTD Report</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-6">
                <div class="card" style="margin-bottom: 1rem;">
                    <div class="card-body d-flex justify-content-center align-items-center" style="padding: 0.8rem;">
                        <h4 class="card-title subhead_text">{{ Auth::user()->getGlobalBrand() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-6">
                <div class="card" style="margin-bottom: 1rem;">
                    <div class="card-body d-flex justify-content-center align-items-center" style="padding: 0.8rem;">
                        <h4 class="card-title subhead_text" id="vendor_name_card_3p">{{ $edVendor_name }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body pt-0">
                            <div class="row" id="ordered" style="height: 257px;">
                                <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center" style="margin-top: 70px;">
                                    <h2 class="subhead_text font-large-1 mt-2" id="sc_ytd1">-</h2>
                                </div>
                                <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center body_text">
                                    <p id="sc_ytd_title1"> Ordered Product</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body pt-0">
                            <div class=" list-group-flush customer-info" style="margin-bottom: 1.1em;" id="orderedProductDetailYtd">
                                <div class="row avg-sessions pt-50" style="margin-top: 125px;">
                                    <div class="col-12 text-center body_text">
                                        <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="opValue">-</p>
                                        <p class="mb-12" id="opTitle">Ordered Product</p>
                                    </div>
                                    <div class="col-12 text-center">
                                        <p class="text-bold-600 font-medium-2 mb-0 mt-25"></p>
                                        <p class="mb-75">
                                        </p>
                                    </div>
                                </div>
                                <div class="d-lg-flex justify-content-center align-items-center">
                                    <div class="col-12 d-flex justify-content-center">
                                        <div id="vendor_last6_ordered_product_ytd" width="100%" style="display: block !important; width: 100%; ">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-xl-6 col-md-12 col-sm-12">
        <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="card" style="margin-bottom: 1rem;">
                    <div class="card-body d-flex justify-content-center align-items-center subhead_text" style="padding: 0.8rem;">
                        <h4 class="card-title">MTD Report</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-6">
                <div class="card" style="margin-bottom: 1rem;">
                    <div class="card-body d-flex justify-content-center align-items-center" style="padding: 0.8rem;">
                        <h4 class="card-title subhead_text">{{ Auth::user()->getGlobalBrand() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-6">
                <div class="card" style="margin-bottom: 1rem;">
                    <div class="card-body d-flex justify-content-center align-items-center" style="padding: 0.8rem;">
                        <h4 class="card-title subhead_text" id="vendor_name_card_mtd_3p">{{ $edVendor_name }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body pt-0">
                            <div class="row" id="orderedMTD" style="height: 257px;">
                                <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center" style="margin-top: 70px;">
                                    <h2 class="font-large-1 subhead_text mt-2" id="ordered_mtd">-</h2>
                                </div>
                                <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center body_text">
                                    <p id="ordered_mtd_title"> Ordered Product</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body pt-0">
                            <div class=" list-group-flush customer-info" style="margin-bottom: 1.1em;">
                                <div class="row avg-sessions pt-50" style="margin-top: 125px;">
                                    <div class="col-12 text-center body_text">
                                        <p class="text-bold-600 font-medium-2 mb-0 mt-25" id="opValueMtd">-</p>
                                        <p class="mb-12" id="opTitleMtd">Ordered Product</p>
                                    </div>
                                    <div class="col-12 text-center">
                                        <p class="text-bold-600 font-medium-2 mb-0 mt-25"></p>
                                        <p class="mb-75">
                                        </p>
                                    </div>
                                </div>
                                <div class="d-lg-flex justify-content-center align-items-center">
                                    <div class="col-12 justify-content-center">
                                        <div id="vendor_last6_ordered_product_mtd" width="100%" style="display: block !important; width: 100%; ">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-12 col-md-12 col-sm-12" id="tablesDiv3p">
        <div class="card">
            <div class="card-content headline_text">
                <div class="card-header col-xl-12 col-md-12 col-sm-12">
                    <h4 class="card-title " id="sc_table_merge_3p">SHIPPED COGS & NET RECEIPTS </h4>
                    <div class="collapse-option-switch custom-switch-warning">
                        <div class='custom-control custom-switch custom-control-inline'>
                            <p class="pt-20">YTD/MTD</p>
                            <input type='checkbox' class='status custom-control-input' style='background-color: #FFD2A0 !important;' name='mtd-ytd-sc-switch-3p-merge' id='mtd-ytd-sc-switch-3p-merge' checked />
                            <label class='custom-control-label ml-1 ' for='mtd-ytd-sc-switch-3p-merge'>
                                <span class="switch-text-right">YT</span>
                                <span class="switch-text-left">MT</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive" style="overflow: auto; max-height: 60vh;">
                        <table class="table table-striped mb-0" id="shipCogsTable3p">
                            <thead class="thead subhead_text">
                                <tr style="    height: 100px;">
                                    <th class="text-center align-middle" style="white-space: nowrap; border: 1px solid #f8f8f8; background: white; position: sticky; top: -1px;">Vendor<i class="fa fa-fw fa-sort" onclick="sortTable1(0)"></i> </th>
                                    <th class="text-center" style="white-space: nowrap;  border: 1px solid #f8f8f8;  background: white; position: sticky;" id="sc_table_cm_merge_3p" title="Current Month Shipped COGS">Shipped COGS<br>Current Month<i class="fa fa-fw fa-sort" onclick="sortTable1(1)"></i><br><br><span class=" shipped_cogs_current">-</span></th>
                                    <th class="text-center" style="white-space: nowrap;  border: 1px solid #f8f8f8;  background: white; position: sticky;" id="nc_table_cm_merge_3p" title="Current Month Net Recipts">Net Receipt<br> Current Month<i class="fa fa-fw fa-sort" onclick="sortTable1(2)"></i><br><br><span class="net_received">-</span></th>
                                    <th class="text-center" style="white-space: nowrap;  border: 1px solid #f8f8f8;  background: white; position: sticky;" title="Direct Fulfillment">Direct Fulfillment<i class="fa fa-fw fa-sort" onclick="sortTable1(3)"></i><br><br><br><span class="current_dropship">-</span> </th>
                                    <th class="text-center" style="white-space: nowrap;  border: 1px solid #f8f8f8;  background: white; position: sticky;" id="sc_table_py_merge_3p" title="Previous Year Shipped COGS">Shipped COGS<br>Previous Year<i class="fa fa-fw fa-sort" onclick="sortTable1(4)"></i><br><br><span class="previous_shipped_cogs">-</span> </th>
                                    <th class="text-center" style="white-space: nowrap;  border: 1px solid #f8f8f8;  background: white; position: sticky;" id="Nc_table_py_merge_3p" title="Previous Year Net Recipts">Net Receipt<br> Previous Year<i class="fa fa-fw fa-sort" onclick="sortTable1(5)"></i><br><br><span class="previous_net_received">-</span> </th>
                                    <th class="text-center" style="white-space: nowrap;  border: 1px solid #f8f8f8;  background: white; position: sticky;" title="Percent To Plan">Shipped COGS PTP<i class="fa fa-fw fa-sort" onclick="sortTable1(6)"></i><br><br><br> <span class="ptp_shipped_cogs">-</span></th>
                                    <th class="text-center" style="white-space: nowrap;  border: 1px solid #f8f8f8;  background: white; position: sticky;" title="Percent To Plan">Net Receipt PTP<i class="fa fa-fw fa-sort" onclick="sortTable1(7)"></i><br><br><br><span class="ptp_net_received">-</span> </th>
                                    <th class="text-center" style="white-space: nowrap;  border: 1px solid #f8f8f8;  background: white; position: sticky;" title="SC Year Over Year">Shipped COGS YOY<i class="fa fa-fw fa-sort" onclick="sortTable1(8)"></i><br><br><br><span class="yoy_shipped_cogs">-</span> </th>
                                    <th class="text-center" style="white-space: nowrap;  border: 1px solid #f8f8f8;  background: white; position: sticky;" title="NC Year Over Year">Net Receipt YOY<i class="fa fa-fw fa-sort" onclick="sortTable1(9)"></i><br><br><br><span class="yoy_net_received">-</span> </th>
                                </tr>
                            </thead>
                            <tbody id="all_vendor_shipped_cogs_ytd_3p" class="tbody body_text">
                                <tr>
                                    <td style='padding: 10px; white-space: nowrap;' align='center' colspan='10'>No data
                                        found
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@if(checkUserPermission(array(5),1))
@section('right-sidebar-content')
<div class="customizer d-md-block">
    <a class="customizer-close" href="javascript:void(0)">
        <i class="feather icon-x"></i>
    </a>
    <a class="customizer-toggle" href="javascript:void(0)">
        <i class="feather icon-chevrons-left fa-fw white"></i>
    </a>
    <div class="customizer-content p-2 ps ps--active-y">
        <h4 class="headline_text text-uppercase mb-0">Report Filter</h4>
        <small></small>
        <hr>
        <div id="collapse-sidebar">
            <div class="collapse-sidebar d-flex justify-content-between">
                <div class="collapse-option-title subhead_text">
                    <h5 class="pt-25">Unit/Dollar</h5>
                </div>
                <div class="collapse-option-switch custom-switch-info">
                    <div class='custom-control custom-switch custom-control-inline'>
                        <input type='checkbox' class='status custom-control-input' style='background-color: #FFB371;' name='dollar-unit-switch' id='dollar-unit-switch' checked />
                        <label class='custom-control-label' for='dollar-unit-switch'>
                            <span class="switch-text-right"></span>
                            <span class="switch-text-left">$</span>
                        </label>
                    </div>
                </div>
            </div>
            <hr>
        </div>
        <form method="post" id="ed_vendor_form">
            <div class="col-12 mb-1 subhead_text">
                <h5>Calender @csrf</h5>
                <input type='hidden' id="filter_range" name="filter_range" value="1" />
                <div class="input-group body_text">
                    <input type='text' id='filter_range_picker' name='filter_range_picker' placeholder="Select Range" class="form-control" aria-describedby="button-addon2" />
                    <input type='hidden' id="ed_filter_date_range" name="ed_filter_date_range" class="form-control pickadate" aria-describedby="button-addon2" />
                    <div class="input-group-append" id="button-addon2">
                        <button type="submit" name="change_report" id="change_report" class="btn btn-info"><i class="feather icon-chevrons-right"></i></button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="col-12 mb-1 subhead_text">
                <h5>Marketplace</h5>
                <select class="form-control body_text" id="marketplace" name="marketplace">
                    <option value="0">ALL</option>
                    <option value="1">1P</option>
                    <option value="2">3P</option>
                </select>
            </div>
            <div class="col-12 mb-1 subhead_text">
                <h5>Vendor</h5>
                <select class="form-control body_text" id="vendor_filter_vendor" name="vendor_filter_vendor">
                    @if(sizeof($platinumVendors) > 0)
                    <optgroup label="Platinum Vendors">
                        @foreach($platinumVendors as $vendor)
                        <option value="{{ $vendor->rdm_vendor_id }}" @if($edVendor_id==$vendor->rdm_vendor_id)
                            selected
                            @endif
                            >{{ $vendor->vendor_alias }}</option>
                        @endforeach
                    </optgroup>
                    @endif
                    @if(sizeof($goldVendors) > 0)
                    <optgroup label="Gold Vendors">
                        @foreach($goldVendors as $vendor)
                        <option value="{{ $vendor->rdm_vendor_id }}" @if($edVendor_id==$vendor->rdm_vendor_id)
                            selected
                            @endif
                            >{{ $vendor->vendor_alias }}</option>
                        @endforeach
                    </optgroup>
                    @endif
                    @if(sizeof($silverVendors) > 0)
                    <optgroup label="Silver Vendors">
                        @foreach($silverVendors as $vendor)
                        <option value="{{ $vendor->rdm_vendor_id }}" @if($edVendor_id==$vendor->rdm_vendor_id)
                            selected
                            @endif
                            >{{ $vendor->vendor_alias }}</option>
                        @endforeach
                    </optgroup>
                    @endif
                    @if(sizeof($threeP) > 0)
                    <optgroup label="3P Vendors">
                        @foreach($threeP as $vendor)
                        <option value="{{ $vendor->rdm_vendor_id }}" @if($edVendor_id==$vendor->rdm_vendor_id)
                            selected
                            @endif
                            >{{ $vendor->vendor_alias }}</option>
                        @endforeach
                    </optgroup>
                    @endif
                </select>
            </div>
        </form>
        <hr>
    </div>
    @endsection
    @endif

    @section('PageCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('c3/css/c3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/ed/edTables.css') }}">
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset('vendors/css/pickers/pickadate/pickadate.css') }}">
    @if(Auth::user()->profile->profile_mode == 'dark-layout')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/ed/ed-extended-dard.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('c3/css/c3-dark.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('datatimepicker/bootstrap-datetimepicker-dark.css') }}" />
    @endif
    <link rel="stylesheet" href="{{ asset('formvalidation/tachyons.min.css') }}">
    @endsection

    @section('VendorCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('datatimepicker/bootstrap-datetimepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/tables/datatable/datatables.min.css') }}">
    @endsection

    @section('PageVendorJS')
    <script type="text/javascript" src="{{ asset('daterangepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('datatimepicker/bootstrap-datetimepicker.min.js') }}"></script>
    @endsection

    @section('PageJS')
    <script>
    {{--var access = 'yes';
        @if(sizeof($vendors) == 0 )
            access = 'no';
        @endif--}}
    </script>
    <!-- Load d3.js and c3.js -->
    <script src="{{ asset('c3/d3.v5.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('c3/js/c3.min.js') }}"></script>
    <script src="{{ asset('js/validation/executiveDashboard/visual1.js') }}"></script>
    @endsection