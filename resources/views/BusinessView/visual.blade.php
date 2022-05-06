@extends('layouts.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0 headline_text">Business Review</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"> Business Review
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
        <!-- KPI Cards -->
        <div class="row">
            <div class="col-lg-2-5 col-sm-6 col-12">
                <div class="card mb-1">
                    <div class="d-flex flex-column align-items-center">
                        <p class="mt-1 mb-0">SPEND</p>
                        <h2 class="text-bold-700 mt-0 mb-0" id="kpi_spend">-</h2>
                        <p class="text-bold-700 mt-0 mb-1" id="kpi_spend_percentage">-</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2-5 col-sm-6 col-12">
                <div class="card mb-1">
                    <div class="d-flex flex-column align-items-center">
                        <p class="mt-1 mb-0">AD SALES</p>
                        <h2 class="text-bold-700 mt-0 mb-0" id="kpi_ad_sale">-</h2>
                        <p class="text-bold-700 mt-0 mb-1" id="kpi_ad_sale_percentage">-</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2-5 col-sm-6 col-12">
                <div class="card mb-1">
                    <div class="d-flex flex-column align-items-center">
                        <p class="mt-1 mb-0">CLICKS</p>
                        <h2 class="text-bold-700 mt-0 mb-0" id="kpi_click">-</h2>
                        <p class="text-bold-700 mt-0 mb-1" id="kpi_click_percentage">-</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2-5 col-sm-6 col-12">
                <div class="card mb-1">
                    <div class="d-flex flex-column align-items-center">
                        <p class="mt-1 mb-0">IMPRESSIONS</p>
                        <h2 class="text-bold-700 mt-0 mb-0" id="kpi_impression">-</h2>
                        <p class="text-bold-700 mt-0 mb-1" id="kpi_impression_percentage">-</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2-5 col-sm-6 col-12">
                <div class="card mb-1">
                    <div class="d-flex flex-column align-items-center">
                        <p class="mt-1 mb-0">ROAS</p>
                        <h2 class="text-bold-700 mt-0 mb-0" id="kpi_roas">-</h2>
                        <p class="text-bold-700 mt-0 mb-1" id="kpi_roas_percentage">-</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2-5 col-sm-6 col-12">
                <div class="card mb-1">
                    <div class="d-flex flex-column align-items-center">
                        <p class="mt-1 mb-0">AD ORDERS</p>
                        <h2 class="text-bold-700 mt-0 mb-0" id="kpi_order">-</h2>
                        <p class="text-bold-700 mt-0 mb-1" id="kpi_order_percentage">-</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2-5 col-sm-6 col-12">
                <div class="card mb-1">
                    <div class="d-flex flex-column align-items-center">
                        <p class="mt-1 mb-0">CONVERSION RATE</p>
                        <h2 class="text-bold-700 mt-0 mb-0" id="kpi_conversion_rate">-</h2>
                        <p class="text-bold-700 mt-0 mb-1" id="kpi_conversion_rate_percentage">-</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2-5 col-sm-6 col-12">
                <div class="card mb-1">
                    <div class="d-flex flex-column align-items-center">
                        <p class="mt-1 mb-0">ACOS</p>
                        <h2 class="text-bold-700 mt-0 mb-0" id="kpi_acos">-</h2>
                        <p class="text-bold-700 mt-0 mb-1" id="kpi_acos_percentage">-</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2-5 col-sm-6 col-12">
                <div class="card mb-1">
                    <div class="d-flex flex-column align-items-center">
                        <p class="mt-1 mb-0">ORDERED REVENUE</p>
                        <h2 class="text-bold-700 mt-0 mb-0" id="kpi_ordered_revenue">-</h2>
                        <p class="text-bold-700 mt-0 mb-1" id="kpi_ordered_revenue_percentage">-</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2-5 col-sm-6 col-12">
                <div class="card mb-1">
                    <div class="d-flex flex-column align-items-center">
                        <p class="mt-1 mb-0">PROGRAM VALUE</p>
                        <h2 class="text-bold-700 mt-0 mb-0" id="kpi_program_value">-</h2>
                        <p class="text-bold-700 mt-0 mb-1" id="kpi_program_value_percentage">-</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Donut Charts and Portfolio KPIs Cards -->
        <div class="row">
            <div class="col-lg-3">
                <div class="card" style="min-height: 460px">
                    <div class="card-content">
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-sm-12 col-12 d-flex flex-column flex-wrap">
                                    <p class="subhead_text text-bold-1500 mt-2" style="font-size:small;" id="total_ad_sales_by_type_text"><b>total ad sales by type</b></p>
                                </div>
                                <div class="col-sm-12 col-12 d-flex justify-content-center body_text">
                                    <div id="total_ad_sales_by_type_graph"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card" style="min-height: 460px">
                    <div class="card-content">
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-sm-12 col-12 d-flex flex-column flex-wrap">
                                    <p class="subhead_text text-bold-1500 mt-2" style="font-size:small;" id="total_campaign_spend_by_type_text"><b>total campaign spend by type</b></p>
                                </div>
                                <div class="col-sm-12 col-12 d-flex justify-content-center body_text">
                                    <div id="total_campaign_spend_by_type_graph"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-2" style="min-height: 460px;">
                    <div class="card-content">
                        <div class="card-header">
                            <p class="subhead_text text-bold-700">Portfolio KPI's</p>
                            <div class="d-flex">
                                <!-- <a href="javascript:void(0)" id="exportPortfolio" style="color: #626262;font-size: 20px; margin-left:378px;"> <i class="fa fa-file-excel-o"></i> </a> -->
                                <button type="button" name="exportPortfolio" id="exportPortfolio" title="Export Portfolio KPI's" class="btn-icon btn btn-info mr-1" style="padding: 5px 15px;"><i class="feather icon-download"></i></button>
                                <a data-action="expand" onclick="changeHeight()" style="font-size: 1.45rem;"><i class="feather icon-maximize"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="portfolio_kpi" class="table-responsive" style="max-height: 363px; min-height: 363px; overflow:auto">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ORDERED REVENUE, AD SALES AND CAMPAIGN SPENDS Graph Cards -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <p id="business_report_graph_heading" class="subhead_text text-bold-700">Performance Over Time </p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div id="business_report_graph_dollar" class="business_report_graph_dollar col-lg-9 col-md-9 col-sm-12 " style="padding-right: 2px; padding-left: 1px;">
                                </div>
                                <div class="col-2-pl-1 checkbox-div">
                                    <form>
                                        <label class="checkbox-ad-sales">
                                            <input type="checkbox" id="adSales" class="mb-1 checkbox" value="dollar"> <span>Ad Sales</span>
                                        </label><br>
                                        <label class="checkbox-ad-spend">
                                            <input type="checkbox" id="adSpend" class="mb-1 checkbox" value="dollar"><span>Ad Spend</span>
                                        </label><br>
                                        <label class="checkbox-roas">
                                            <input type="checkbox" id="roas" class="mb-1 checkbox" value="dollar"><span>ROAS</span>
                                        </label><br>
                                        <label class="checkbox-ordered-revenue">
                                            <input type="checkbox" id="orderedRevenue" class="mb-1 checkbox" value="dollar"><span>Ordered Revenue</span>
                                        </label><br>
                                        <label class="checkbox-glance-view">
                                            <input type="checkbox" id="glanceView" class="mb-1 checkbox" value="unit"> <span>Glance View</span>
                                        </label><br>
                                        <label class="checkbox-click">
                                            <input type="checkbox" id="click" class="mb-1 checkbox" value="unit"><span>Clicks</span>
                                        </label><br>
                                        <label class="checkbox-impression">
                                            <input type="checkbox" id="impression" class="mb-1 checkbox" value="unit"><span>Impressions</span>
                                        </label><br>
                                        <label class="checkbox-orders">
                                            <input type="checkbox" id="order" class="mb-1 checkbox" value="unit"><span>Orders</span>
                                        </label><br>
                                        <label class="checkbox-conversion-rate">
                                            <input type="checkbox" id="conversionRtae" class="mb-1 checkbox" value="percentage"><span>Conversion Rate</span>
                                        </label><br>
                                    </form>
                                </div>
                            </div>
                            <hr style="border: 1px dashed rgb(198,194,193); border-radius: 5px;" />
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="d-flex w-100 justify-content-end">
                                                <button type="button" name="exportPerformance" id="exportPerformance" title="Export Performance Over Time" class="btn-icon btn btn-info mr-1" style="padding: 5px 15px;"><i class="feather icon-download"></i></button>
                                                <a data-action="expand" style="font-size: 1.45rem;"><i class="feather icon-maximize"></i></a>
                                            </div>
                                            <!-- <a href="javascript:void(0)" id="exportPerformance" class="subhead_text text-bold-700" style="color: #626262;font-size: 20px;"> <i class="fa fa-file-excel-o"></i> </a>
                                            <a data-action="expand" style="margin-left: 1090px;"><i class="feather icon-maximize"></i></a> -->
                                        </div>
                                        <div class="card-content collapse show">

                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="order_revenue_ad_sales_spend_table" class="table performance table-striped" width="1690px">
                                                        <thead class="performance_thead">
                                                            <tr>
                                                                <th style="font-size: xx-small;">Date</th>
                                                                <th style="font-size: xx-small;">SPEND</th>
                                                                <th style="font-size: xx-small;">PRIOR PERIOD SPEND</th>
                                                                <th style="font-size: xx-small;">AD SALES</th>
                                                                <th style="font-size: xx-small;">PRIOR PERIOD AD SALES</th>
                                                                <th style="font-size: xx-small;">ORDERED REVENUE</th>
                                                                <th style="font-size: xx-small;">GLANCE VIEW</th>
                                                                <th style="font-size: xx-small;">IMPRESSIONS</th>
                                                                <th style="font-size: xx-small;">CLICKS</th>
                                                                <th style="font-size: xx-small;">ROAS</th>
                                                                <th style="font-size: xx-small;">ORDERS</th>
                                                                <th style="font-size: xx-small;">CONVERSION RATE</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
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
        </div>
        <!-- Search Term Sponsored Product Cards -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <p id="top_asin_shipped_cogs_heading" class="subhead_text text-bold-700">Sponsored Product Search Terms – Top 50 by Sales </p>
                            <!-- <a href="javascript:void(0)" id="exportSpTerm" class="subhead_text text-bold-700" style="color: #626262;font-size: 20px;"> <i class="fa fa-file-excel-o"></i> </a> -->
                            <div class="d-flex">
                                <button type="button" name="exportSpTerm" id="exportSpTerm" title="Export Sponsored Product Search Terms" class="btn-icon btn btn-info mr-1" style="padding: 5px 15px;"><i class="feather icon-download"></i></button>
                                <a data-action="expand" style="font-size: 1.45rem;"><i class="feather icon-maximize"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="search_team_sp_table" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th>rank</th>
                                            <th>search term</th>
                                            <th>spend</th>
                                            <th>ad sales</th>
                                            <th>impression</th>
                                            <th>clicks</th>
                                            <th>cpc</th>
                                            <th>ctr</th>
                                            <th>orders</th>
                                            <th>roas</th>
                                            <th>conversion rate</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="card" style="min-height: 320px; overflow-y: scroll;">
                    <div class="card-content">
                        <div class="card-header">
                            <p id="top_asin_sales_heading" class="subhead_text text-bold-700">TOP 10 ASINS BY SALES - SPONSORED PRODUCT</p>
                            <a data-action="expand" style="font-size: 1.45rem;"><i class="feather icon-maximize"></i></a>
                        </div>
                        <div class="card-body pt-0">
                            <div id="top_asin_sales" class="table-responsive">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="card mb-2" style="min-height: 310px;">
                    <div class="card-content">
                        <div class="card-header">
                            <p id="top_asin_increase_heading" class="subhead_text text-bold-700">TOP 5 ASINS INCREASE</p>
                            <a data-action="expand" style="font-size: 1.45rem;"><i class="feather icon-maximize"></i></a>
                        </div>
                        <div class="card-body pt-0">
                            <div id="top_asin_increase" class="table-responsive">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="card mt-1" style="min-height: 310px;">
                    <div class="card-content">
                        <div class="card-header">
                            <p id="top_asin_decrease_heading" class="subhead_text "><b>TOP 5 ASINS DECREASE</b> </p>
                            <a data-action="expand" style="font-size: 1.45rem;"><i class="feather icon-maximize"></i></a>
                        </div>
                        <div class="card-body pt-0">
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
<div class="customizer d-md-block">
    <a class="customizer-close" href="javascript:void(0)">
        <i class="feather icon-x"></i>
    </a>
    <a class="customizer-toggle" href="javascript:void(0)">
        <i class="feather icon-chevrons-left fa-fw white"></i>
    </a>
    <div class="customizer-content p-2 ps ps--active-y">
        <h4 class="text-uppercase mb-0 headline_text">Report Filter</h4>
        <small></small>
        <hr>
        <form method="post" id="filter_form">
            <div class="col-12 mb-1">
                <h5 class="subhead_text">Vendors <span style="color: red">*</span> @csrf</h5>
                <select class="form-control body_text" id="sales_filter_vendor" name="sales_filter_vendor[]" multiple="multiple">
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
                    <option value="0">Daily</option>
                    <option value="1">Weekly</option>
                    <option value="2">Monthly</option>
                </select>
                <input type='hidden' id="filter_range" name="filter_range" value="0" />
            </div>

            <div class="col-12 mb-1">
                <h5 class="subhead_text">Calendar <span style="color: red">*</span></h5>
                <input type='text' id='custom_data_value' name='custom_data_value' class="form-control pickadate" style=" font-family: 'Axiforma Light', sans-serif;" />
                <input type='hidden' id='filter_range_picker' name='filter_range_picker' placeholder="Select Range" class="form-control" />
                <input type='hidden' id="filter_date_range" name="filter_date_range" class="form-control pickadate" />
            </div>
            <div class="col-12 mb-1">
                <button type="submit" name="generate_report" id="generate_report" class="btn btn-info ">Apply</button>
            </div>
        </form>
        <hr>
    </div>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bussinessReport/dashboard_dark.css') }}">
    @else
    <link rel="stylesheet" type="text/css" href="{{ asset('daterangepicker/daterangepicker-light.css') }}">
    <!-- custom css file for bussiness dashboard -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bussinessReport/dashboard.css') }}">
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
    <script src="{{ asset('vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
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
    <script src="{{ asset('js/validation/businessReview/visual.js') }}"></script>
    <script src="{{ asset('js/scripts/datatables/datatable.js') }}"></script>
    @endsection