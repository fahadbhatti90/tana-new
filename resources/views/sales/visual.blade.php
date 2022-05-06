@extends('layouts.app')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Sales Report</h2>
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
    </div>
    <div class="content-body">
        <!-- account setting page start -->
        <section id="page-account-settings">
            <div class="row">
                <div class="col-xl-4 col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-header d-flex align-items-start pb-0" id="shipped_cogs_summary_herder">
                                <div>
                                    <h2 class="text-bold-700 mb-0" id="shipped_cogs_summary"> - </h2>
                                    <p>Shipped COGS</p>
                                </div>
                                <div class="avatar bg-rgba-success p-50 m-0">
                                    <div class="avatar-content">
                                        <i class="feather icon-trending-up text-success font-medium-5"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <hr class="my-1">
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="uploads">
                                        <p class="font-weight-bold font-medium-2 mb-1" id="shipped_cogs_prior_period"> - </p>
                                        <span class="" id="shipped_cogs_prior_period_heading"></span>
                                    </div>
                                    {{--<div class="followers">
                                        <p class="font-weight-bold font-medium-2 mb-1" id="shipped_cogs_yoy"> - </p>
                                        <span class="">YOY</span>
                                    </div>--}}
                                    <div class="following">
                                        <p class="font-weight-bold font-medium-2 mb-1" id="shipped_cogs_ptp"> - </p>
                                        <span class="">PTP</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><div class="col-xl-4 col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-header d-flex align-items-start pb-0" id="shipped_units_summary_herder">
                                <div>
                                    <h2 class="text-bold-700 mb-0" id="shipped_units_summary"> - </h2>
                                    <p>Shipped Units</p>
                                </div>
                                <div class="avatar bg-rgba-success p-50 m-0">
                                    <div class="avatar-content">
                                        <i class="feather icon-bar-chart text-success font-medium-5"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <hr class="my-1">
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="WOW">
                                        <p class="font-weight-bold font-medium-2 mb-1" id="shipped_units_prior_period"> - </p>
                                        <span class="" id="shipped_units_prior_period_heading"></span>
                                    </div>
                                    {{--<div class="YOY">
                                        <p class="font-weight-bold font-medium-2 mb-1" id="shipped_units_yoy"> - </p>
                                        <span class="">YOY</span>
                                    </div>--}}
                                    <div class="PTP">
                                        <p class="font-weight-bold font-medium-2 mb-1" id="shipped_units_ptp"> - </p>
                                        <span class="">PTP</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 col-sm-12">
                    <div class="card">
                            <div class="card-header d-flex align-items-start pb-0">
                                <div>
                                    <h2 class="text-bold-700 mb-0" id="acu_summary"> - </h2>
                                    <p>ACU</p>
                                </div>
                                <div class="avatar bg-rgba-success p-50 m-0">
                                    <div class="avatar-content">
                                        <i class="feather icon-triangle text-success font-medium-5"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <hr class="my-1">
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="acu-para">
                                        <p class="font-medium-2 mb-1">Will be available when at least one year data is accumulated</p>
                                    </div>
                                </div>
                                </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-header">
                                <h4 id="chart_heading" class="card-title">Graph</h4>
                                <div class="dropdown chart-dropdown">
                                    <button class="threshold-value btn btn-sm" type="button" id="0" value="summary" style="padding: 0.1rem 0.1rem 0.1rem 0.1rem;">
                                        <i class="feather icon-eye text-info font-medium-5"></i>
                                    </button>
                                    <button class="threshold btn btn-sm" type="button" id="0" value="summary" style="padding: 0.1rem 0.1rem 0.1rem 0.1rem;">
                                        <i class="feather icon-bell text-info font-medium-5"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="chart" width="100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-12">

                    <div class="card">
                        <div class="card-content">
                            <div class="card-header">
                                <h4 id="top_asin_shipped_cogs_heading" class="card-title">TOP 10 ASINS</h4>
                            </div>
                            <div class="card-body">
                                <div id="top_asin_shipped_cogs" class="table-responsive"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-content">
                            <div class="card-header">
                                <h4 id="top_asin_decrease_heading" class="card-title">TOP 5 ASINS Decrease </h4>
                            </div>
                            <div class="card-body">
                                <div id="top_asin_decrease" class="table-responsive"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-6 col-sm-12">

                    <div class="card">
                        <div class="card-content">
                            <div class="card-header">
                                <h4 id="top_asin_increase_heading" class="card-title">TOP 5 ASINS Increase</h4>
                            </div>
                            <div class="card-body">
                                <div id="top_asin_increase" class="table-responsive"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-content">
                            <div class="card-header">
                                <h4 id="category_chart_heading" class="card-title">Sales By Category</h4>
                                <div class="dropdown chart-dropdown">
                                    <button class="threshold-value btn btn-sm" type="button" id="0" value="category" style="padding: 0.1rem 0.1rem 0.1rem 0.1rem;">
                                        <i class="feather icon-eye text-info font-medium-5"></i>
                                    </button>
                                    <button class="threshold btn btn-sm" type="button"  id="0" value="category" style="padding: 0.1rem 0.1rem 0.1rem 0.1rem;">
                                        <i class="feather icon-bell text-info font-medium-5"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="category_chart" width="100%"></div>
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
        <h4 class="text-uppercase mb-0">Sale Report Filter</h4>
        <small></small>
        <hr>
        <form method="post" id="sales_filter_form">
            <div class="col-12 mb-1">
                <h5>Vendors @csrf</h5>
                <select class="form-control" id="sales_filter_vendor">
                    <option value="0">All</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->rdm_vendor_id }}">{{ $vendor->vendor_name." - ".$vendor->domain }}</option>
                    @endforeach
                </select>
                <input type='hidden' id="filter_vendor" name="filter_vendor" value="0" />
            </div>

            <div class="col-12 mb-1">
                <h5>Reporting Range</h5>
                <select class="form-control" id="sales_filter_range">
                    <option value="1">Daily</option>
                    <option value="2">Weekly</option>
                    <option value="3">Monthly</option>
                    <option value="4">Yearly</option>
                </select>
                <input type='hidden' id="filter_range" name="filter_range" value="1"/>
            </div>

            <div class="col-12 mb-1">
                <h5>Calender</h5>
                <input type='text' id='custom_data_value' name='custom_data_value' class="form-control pickadate" />
                <input type='hidden' id='filter_range_picker' name='filter_range_picker' placeholder="Select Range" class="form-control" />
                <input type='hidden' id="sales_filter_date_range" name="sales_filter_date_range" class="form-control pickadate" />
            </div>

            <div class="col-12 mb-1">
                    <button type="submit" name="generate_report" id="generate_report" class="btn btn-info btn-round">Apply</button>
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
        @if(sizeof($vendors) == 0 )
            access = 'no';
        @endif
    </script>
    <script src="{{ asset('c3/d3.v5.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('c3/js/c3.min.js') }}"></script>
    <script src="{{ asset('js/validation/sales/visual.js') }}"></script>
    <script src="{{ asset('js/validation/alerts/sale.js') }}"></script>
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
                                    <select class="input-reset form-control"  name="threshold_range[0]">
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
                        <input hidden id="sub_kpi_value"/>
                        <input hidden id="sub_kpi_name"/>
                        <input hidden id="report_graph"/>
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
