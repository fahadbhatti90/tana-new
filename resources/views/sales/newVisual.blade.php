@extends('layouts.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0 headline_text">Sales Report</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"> Sales Report
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-header-right text-md-right col-md-6 col-12 d-md-block ">
        <div class="form-group breadcrum-right">
            <div class="dropdown">
                <h3 class="content-header-title mb-0 subhead_text">
                    <a class="customizer-toggle" href="javascript:void(0)" @if(Auth::user()->profile->profile_mode == "dark-layout")
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
<div class="content-body">
    <!-- account setting page start -->
    <section id="page-account-settings">

        <div class="row">
            <div class="col-xl-4 col-md-4 col-sm-12">
                <div class="card mr-0 ml-0" style="min-height: 170px;">
                    <div class="card mb-1">
                        <div class="card-content">
                            <div class="card-header d-flex align-items-start pb-0">
                                <div>
                                    <h4 class="subhead_text mb-0">Shipped COGS</h4>
                                    <div class="dropdown chart-dropdown">
                                        <button class="threshold-value btn btn-sm" type="button" id="0" value="shippedCogsSummary" style="padding: 0.1rem 0.1rem 0.1rem 0.1rem;">
                                            <i class="feather icon-eye text-info font-medium-5"></i>
                                        </button>
                                        @if(checkUserPermission(array(3),2))
                                        <button class="threshold btn btn-sm" type="button" id="0" value="shippedCogsSummary" style="padding: 0.1rem 0.1rem 0.1rem 0.1rem;">
                                            <i class="feather icon-bell text-info font-medium-5"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="m-0">
                                    <h2 class="font-large-0 text-bold-700 mr-3 mb-0 subhead_text" id="shipped_cogs_gauge_value">-</h2>
                                </div>
                            </div>
                            <div class="row card-body d-flex align-items-start p-0">
                                <div class="col-xl-6 col-md-6 col-sm-6 d-flex justify-content-center">
                                    <div class="row">
                                        <div class="mt-0 mb-0">
                                            <div id="shipped_cogs_gauge" style="width: 100%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-6 d-flex flex-column flex-wrap text-center">
                                    <div class="mt-2 mb-0 ml-0 mr-3" id="shipped_cogs_gauge_trailing"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4 col-sm-12">
                <div class="card mr-0 ml-0" style="min-height: 170px;">
                    <div class="card mb-1">
                        <div class="card-content">
                            <div class="card-header d-flex align-items-start pb-0">
                                <div>
                                    <h4 class="subhead_text mb-0">Net Receipts</h4>
                                    <div class="dropdown chart-dropdown">
                                        <button class="threshold-value btn btn-sm" type="button" id="0" value="netReceivedSummary" style="padding: 0.1rem 0.1rem 0.1rem 0.1rem;">
                                            <i class="feather icon-eye text-info font-medium-5"></i>
                                        </button>
                                        @if(checkUserPermission(array(3),2))
                                        <button class="threshold btn btn-sm" type="button" id="0" value="netReceivedSummary" style="padding: 0.1rem 0.1rem 0.1rem 0.1rem;">
                                            <i class="feather icon-bell text-info font-medium-5"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="m-0">
                                    <h2 class="font-large-0 text-bold-700 mr-3 mb-0 subhead_text" id="net_receipts_gauge_value">-</h2>
                                </div>
                            </div>
                            <div class="row card-body d-flex align-items-start p-0">
                                <div class="col-xl-6 col-md-6 col-sm-6 d-flex justify-content-center">
                                    <div class="row">
                                        <div class="mt-0 mb-0">
                                            <div id="net_receipts_gauge" style="width: 100%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-6 d-flex flex-column flex-wrap text-center">
                                    <div class="mt-2 mb-0 ml-0 mr-3" id="net_receipts_gauge_trailing"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4 col-sm-12">
                <div class="card mr-0 ml-0" style="min-height: 170px;">
                    <div class="card mb-1">
                        <div class="card-content">
                            <div class="card-header d-flex align-items-start pb-0">
                                <div>
                                    <h4 class="subhead_text mb-0">PO Plan %</h4>
                                    <div class="dropdown chart-dropdown">
                                        <button class="threshold-value btn btn-sm" type="button" id="0" value="confirmationRateSummary" style="padding: 0.1rem 0.1rem 0.1rem 0.1rem;">
                                            <i class="feather icon-eye text-info font-medium-5"></i>
                                        </button>
                                        @if(checkUserPermission(array(3),2))
                                        <button class="threshold btn btn-sm" type="button" id="0" value="confirmationRateSummary" style="padding: 0.1rem 0.1rem 0.1rem 0.1rem;">
                                            <i class="feather icon-bell text-info font-medium-5"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="m-0">
                                    <h2 class="font-large-0 text-bold-700 mr-3 mb-0 subhead_text" id="po_plan_gauge_value">-</h2>
                                </div>
                            </div>
                            <div class="row card-body d-flex align-items-start p-0">
                                <div class="col-xl-6 col-md-6 col-sm-6 d-flex justify-content-center">
                                    <div class="row">
                                        <div class="mt-0 mb-0">
                                            <div id="po_plan_gauge" style="width: 100%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-6 d-flex flex-column flex-wrap text-center">
                                    <div class="mt-2 mb-0 ml-0 mr-3" id="po_plan_gauge_trailing"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="card" style="min-height: 450px">
                    <div class="card-header">
                        <h4 class="font-large-0 mb-0 subhead_text" id="growth_title">Purchase Order YOY Growth</h4>
                        <div class="dropdown chart-dropdown">
                            <button class="threshold-value btn btn-sm" type="button" id="0" value="yoySummary" style="padding: 0.1rem 0.1rem 0.1rem 0.1rem;">
                                <i class="feather icon-eye text-info font-medium-5"></i>
                            </button>
                            @if(checkUserPermission(array(3),2))
                            <button class="threshold btn btn-sm" type="button" id="0" value="yoySummary" style="padding: 0.1rem 0.1rem 0.1rem 0.1rem;">
                                <i class="feather icon-bell text-info font-medium-5"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body mt-0 pt-0">
                        <div class="justify-content-left d-flex align-items-left mt-0">
                            <p class="text-center" id="yoy_growth_sub_title">Confirmation Rate</p>
                        </div>
                        <div class="justify-content-center d-flex align-items-center mt-1" id="yoy_growth_chart">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="card" style="min-height: 450px">
                    <div class="card-header d-flex pb-0">
                        <h4 class="font-large-0 mb-0 subhead_text">PO Confirmation Rate </h4>
                    </div>
                    <div class="card-body mt-0 pt-0">
                        <div class="d-flex mt-0">
                            <p class="text-center" id="po_confirmed_rate_by_subcategory_chart_title" style="white-space: nowrap;">-</p>
                        </div>
                        <div class="justify-content-center d-flex align-items-center mt-1" id="po_confirmed_rate_by_subcategory_chart" width="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="card" style="min-height: 450px">
                    <div class="card-header">
                        <h4 class="font-large-0 mb-0 subhead_text" id="shipped_cogs_by_granularity_title">Shipped COGS by Week</h4>
                        <div class="dropdown chart-dropdown">
                            <button class="threshold-value btn btn-sm" type="button" id="0" value="shippedCogsSummary" style="padding: 0.1rem 0.1rem 0.1rem 0.1rem;">
                                <i class="feather icon-eye text-info font-medium-5"></i>
                            </button>
                            @if(checkUserPermission(array(3),2))
                            <button class="threshold btn btn-sm" type="button" id="0" value="shippedCogsSummary" style="padding: 0.1rem 0.1rem 0.1rem 0.1rem;">
                                <i class="feather icon-bell text-info font-medium-5"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body mt-0 pt-1">
                        <div class="justify-content-center d-flex align-items-center mt-1 mr-3 ml-3" id="shipped_cogs_by_granularity_chart">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="card" style="min-height: 460px">
                    <div class="card-header d-flex justify-content-center align-items-center pb-0">
                        <h4 class="font-large-0 mb-0 subhead_text">Shipped COGS by Subcategory</h4>
                    </div>
                    <div class="card-body mt-0 pt-1">
                        <div class="justify-content-center d-flex align-items-center mt-0">
                            <p class="text-center" id="shipped_cogs_by_subcategory_chart_title">-</p>
                        </div>
                        <div class="justify-content-center d-flex align-items-center mt-1" id="shipped_cogs_by_subcategory_chart">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="card ml-0" style="min-height: 460px">
                    <div class="card-header d-flex justify-content-center align-items-center pb-0">
                        <h4 class="font-large-0 mb-0 subhead_text">Net Receipts by Subcategory</h4>
                    </div>
                    <div class="card-body mt-0 pt-1">
                        <div class="justify-content-center d-flex align-items-center mt-0">
                            <p class="text-center" id="net_receipts_by_subcategory_chart_title">-</p>
                        </div>
                        <div class="justify-content-center d-flex align-items-center mt-1" id="net_receipts_by_subcategory_chart">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-md-12 col-sm-12">
                <div class="card" style="min-height: 800px;">
                    <div class="card-content">
                        <div class="card-header">
                            <h4 id="top_asin_shipped_cogs_heading" class="card-title subhead_text">TOP 10 ASINS</h4>
                        </div>
                        <div class="card-body">
                            <div id="top_asin_shipped_cogs" class="table-responsive">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-12 col-sm-12">
                <div class="card mb-2" style="min-height: 390px;">
                    <div class="card-content">
                        <div class="card-header">
                            <h4 id="top_asin_increase_heading" class="card-title subhead_text">TOP 5 ASINS Increase</h4>
                        </div>
                        <div class="card-body">
                            <div id="top_asin_increase" class="table-responsive">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-1" style="min-height: 390px;">
                    <div class="card-content">
                        <div class="card-header">
                            <h4 id="top_asin_decrease_heading" class="card-title subhead_text">TOP 5 ASINS Decrease </h4>
                        </div>
                        <div class="card-body">
                            <div id="top_asin_decrease" class="table-responsive">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- account setting page end -->
</div>
@endsection


@section('right-sidebar-content')
@if(checkUserPermission(array(3),3))
<div class="customizer d-md-block">
    <a class="customizer-close" href="javascript:void(0)">
        <i class="feather icon-x"></i>
    </a>
    <a class="customizer-toggle" href="javascript:void(0)">
        <i class="feather icon-chevrons-left fa-fw white"></i>
    </a>
    <div class="customizer-content p-2 ps ps--active-y">
        <h4 class="text-uppercase mb-0 headline_text">Sale Report Filter</h4>
        <small></small>
        <hr>
        <form method="post" id="sales_filter_form">
            <div class="col-12 mb-1">
                <h5 class="subhead_text">Vendors <span style="color: red">*</span> @csrf</h5>
                <select class="form-control body_text" id="sales_filter_vendor">
                    <option value="0">All Vendors</option>
                    @if(sizeof($platinumVendors) > 0)
                    <optgroup label="Platinum Vendors">
                        @foreach($platinumVendors as $vendor)
                        <option value="{{ $vendor->rdm_vendor_id }}">{{ $vendor->vendor_alias }}</option>
                        @endforeach
                    </optgroup>
                    @endif
                    @if(sizeof($goldVendors) > 0)
                    <optgroup label="Gold Vendors">
                        @foreach($goldVendors as $vendor)
                        <option value="{{ $vendor->rdm_vendor_id }}">{{ $vendor->vendor_alias }}</option>
                        @endforeach
                    </optgroup>
                    @endif
                    @if(sizeof($silverVendors) > 0)
                    <optgroup label="Silver Vendors">
                        @foreach($silverVendors as $vendor)
                        <option value="{{ $vendor->rdm_vendor_id }}">{{ $vendor->vendor_alias }}</option>
                        @endforeach
                    </optgroup>
                    @endif
                </select>
                <input type='hidden' id="filter_vendor" name="filter_vendor" value="0" />
            </div>

            <div class="col-12 mb-1">
                <h5 class="subhead_text">Reporting Range <span style="color: red">*</span></h5>
                <select class="form-control body_text" id="sales_filter_range">
                    <option value="1">Daily</option>
                    <option value="2">Weekly</option>
                    <option value="3">Monthly</option>
                    <option value="4">Yearly</option>
                </select>
                <input type='hidden' id="filter_range" name="filter_range" value="1" />
            </div>

            <div class="col-12 mb-1">
                <h5 class="subhead_text">Calender <span style="color: red">*</span></h5>
                <input type='text' id='custom_data_value' name='custom_data_value' class="form-control pickadate" style=" font-family: 'Axiforma Light', sans-serif;" />
                <input type='hidden' id='filter_range_picker' name='filter_range_picker' placeholder="Select Range" class="form-control" />
                <input type='hidden' id="sales_filter_date_range" name="sales_filter_date_range" class="form-control pickadate" />
            </div>

            <div class="col-12 mb-1 mt-2">
                <h5 class="subhead_text">Subcategory <label>(optional)</label></h5>
                <select class="form-control body_text" id="shipped_cogs_subcategory_filter" name="shipped_cogs_subcategory_filter[]" multiple="multiple">

                </select>
            </div>
            @if(checkUserPermission(array(3),2))
            <div class="col-12 mb-1">
                <button type="submit" name="generate_report" id="generate_report" class="btn btn-info ">Apply</button>
            </div>
            @endif
        </form>
        <hr>
    </div>
    @endif
    @endsection

    @section('PageCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('c3/css/c3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core/colors/palette-gradient.css') }}">
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset('vendors/css/pickers/pickadate/pickadate.css') }}">
    @if(Auth::user()->profile->profile_mode == 'dark-layout')
    <link rel="stylesheet" type="text/css" href="{{ asset('c3/css/c3-dark.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('datatimepicker/bootstrap-datetimepicker-dark.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('daterangepicker/daterangepicker-dark.css') }}">
    @else
    <link rel="stylesheet" type="text/css" href="{{ asset('daterangepicker/daterangepicker-light.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('formvalidation/tachyons.min.css') }}">
    @endsection

    @section('VendorCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('datatimepicker/bootstrap-datetimepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('daterangepicker/daterangepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/tables/datatable/datatables.min.css') }}">
    @endsection

    @section('PageVendorJS')
    <script type="text/javascript" src="{{ asset('daterangepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('daterangepicker/daterangepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('datatimepicker/bootstrap-datetimepicker.min.js') }}"></script>
    @endsection

    @section('PageJS')
    <!-- Load d3.js and c3.js -->
    <script>
        var access = 'yes';
        @if(sizeof($goldVendors) == 0 && sizeof($platinumVendors) == 0 && sizeof($silverVendors) == 0)
        access = 'no';
        @endif
    </script>

    <script src="{{ asset('c3/d3.v5.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('c3/js/c3.min.js') }}"></script>
    <script src="{{ asset('js/validation/sales/newVisual.js') }}"></script>
    <script src="{{ asset('js/validation/alerts/newSale.js') }}"></script>
    @endsection

    @section('model')
    <!-- Alert Model -->
    <div class="modal fade text-left" id="thresholdModal" tabindex="-1" role="dialog" aria-labelledby="thresholdModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="threshold_title">Create Alerts Rules</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form form-horizontal" method="POST" id="threshold_form">
                    <div class="modal-body">
                        <div class="form-group">
                            <span id="threshold_form_result"></span>
                        </div>
                        <div class="form-group">
                            @csrf
                        </div>
                        <div id="threshold_form_fields">
                        </div>
                        <div class="cf mb2">
                            <div class="fl w-100">
                                <div class="fl w-5 pa2"></div>
                                <div class="fl w-5 pa2">If&nbsp</div>
                                <div class="fl w-30 mr2">
                                    <select class="input-reset form-control" id="kpi_id" name="kpi_id[0]">
                                        <option selected value="shipped_cogs">Shipped COGS</option>
                                        <option value="shipped_units">Shipped Unit</option>
                                    </select>
                                </div>
                                <div class="fl w-25 mr2">
                                    <select class="input-reset form-control" name="threshold_range[0]">
                                        <option selected value=">">Greater Then</option>
                                        <option value="<">Lesser Then</option>
                                        <option value="=">Equal to</option>
                                    </select>
                                </div>
                                <div class="fl w-25 mr2">
                                    <input type="text" class="input-reset form-control" name="threshold_value[0]" placeholder="Value" />
                                </div>
                                <div class="fl w-10 ph2">
                                    <button type="button" class="ba b--black-20 bg-green white ph3 pv2 br2" id="addButton">+</button>
                                </div>
                            </div>
                        </div>

                        <!-- Template -->
                        <div class="cf mb2" id="template" style="display: none;">
                            <div class="fl w-100">
                                <div class="fl w-5 pa2"></div>
                                <div class="fl w-5 pa2">If&nbsp</div>
                                <div class="fl w-30 mr2">
                                    <select class="input-reset form-control" id="template-kpi-id" data-name="kpi_id">
                                        <option selected value="shipped_cogs">Shipped COGS</option>
                                        <option value="shipped_units">Shipped Unit</option>
                                    </select>
                                </div>
                                <div class="fl w-25 mr2">
                                    <select class="input-reset form-control" data-name="threshold_range">
                                        <option selected value=">">Greater Then</option>
                                        <option value="<">Lesser Then</option>
                                        <option value="=">Equal to</option>
                                    </select>
                                </div>
                                <div class="fl w-25 mr2">
                                    <input type="text" class="input-reset form-control" data-name="threshold_value" placeholder="Value" />
                                </div>
                                <div class="fl w-10 ph2">
                                    <button type="button" class="ba b--black-20 bg-green white ph3 pv2 br2 js-remove-button">-</button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="fk_vendor_id" id="fk_vendor_id" />
                        <input type="submit" name="threshold_form_submit" id="threshold_form_submit" class="btn btn-info" value="Save" />
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Threshold -->
    <div class="modal fade text-left" id="viewthresholdModal" tabindex="-1" role="dialog" aria-labelledby="viewthresholdModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="threshold_title">Alerts Rules</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input hidden id="sub_kpi_value" />
                    <input hidden id="sub_kpi_name" />
                    <input hidden id="report_graph" />
                    <div id="threshold_result_fields">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
    @endsection