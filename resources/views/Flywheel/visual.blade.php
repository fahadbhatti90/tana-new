@extends('layouts.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0 headline_text">Flywheel Report</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"> Flywheel Report
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
            <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="card mr-0 ml-0" style="min-height: 170px;">
                    <div class="card mb-1">
                        <div class="card-content">
                            <div class="row card-body">
                                <div class="col-xl-12 col-md-12 col-sm-12 justify-content-center">
                                    <form method="post" id="flywheel_inpage_filter_form">
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-xl-4 col-md-4 col-sm-4">
                                                <label for="test">Product (optional)</label>
                                                <select id='product_info' style='width: 100%;'>
                                                </select>
                                            </div>

                                            <div class="form-group col-xl-4 col-md-4 col-sm-4">
                                                <label for="asin_info">ASIN (optional)</label>
                                                <select id='asin_info' style='width: 100%;'>
                                                </select>
                                            </div>

                                            <div class="form-group col-xl-4 col-md-4 col-sm-4">
                                                <label for="category_info">Category (optional)</label>
                                                <select id='category_info' style='width: 100%;'>
                                                </select>
                                            </div>
                                            <div class="col-12 mb-1">
                                                <button type="submit" name="generate_flywheel_report_filter" id="generate_flywheel_report_filter" class="btn btn-info " style="float: right;">Apply</button>
                                            </div>
                                        </div>
                                    </form>
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
                        <p class="text-left">TOTAL ORDERED REVENUE AND SP AD SALES BY DATE</p>
                    </div>
                    <br>
                    <div class="card-body mt-0 pt-0 pl-0">
                        <div class="justify-content-center d-flex align-items-center mt-1" id="ordered_revenue_sp_adsales">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="card" style="min-height: 450px">
                    <div class="card-header d-flex pb-0">
                        <p>TOTAL CONVERSIONS AND ASP BY DATE </p>
                    </div>
                    <br>
                    <div class="card-body mt-0 pt-0">
                        <div class="justify-content-center d-flex align-items-center mt-1" id="conversion_asp_chart" width="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="card" style="min-height: 450px">
                    <div class="card-header d-flex justify-content-left align-items-left pb-0">
                        <p class="text-left">SP IMPRESSIONS AND TOTAL GLANVE VIEWS BY DATE</p>
                    </div>
                    <br>
                    <div class="card-body mt-0 pt-1">
                        <div class="justify-content-center d-flex align-items-center mt-1" id="glance_view_sp_impression_chart">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="card ml-0" style="min-height: 450px">
                    <div class="card-header d-flex justify-content-left align-items-left pb-0">
                        <p class="text-left">INVENTORY UNITS AND ORDERED UNITS BY DATE</p>
                    </div>
                    <br>
                    <div class="card-body mt-0 pt-1">

                        <div class="justify-content-center d-flex align-items-center mt-1" id="inventory_ordered_unit_chart">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="card" style="min-height: 450px">
                    <div class="card-header">
                        <p class="text-left">SPEND BY ADTYPE</p>
                    </div>
                    <div class="card-body mt-0 pt-0">
                        <div class="justify-content-center d-flex align-items-center " id="spend_ad_type_chart">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="card" style="min-height: 450px">
                    <div class="card-header d-flex pb-0">
                        <p>SALES BY ADTYPE</p>
                    </div>
                    <div class="card-body mt-0 pt-0">
                        <div class="justify-content-center d-flex align-items-center " id="sales_ad_type_chart" width="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="card" style="min-height: 400px;">
                    <div class="card-content">
                        <div class="card-header">
                            <h4 id="category_detail_heading" class="card-title subhead_text">CATEGORY DETAIL</h4>
                        </div>
                        <div class="card-body">
                            <div id="category_detail_table" class="table-responsive" style="max-height: 363px; min-height: 363px; overflow:auto">
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
        <h4 class="text-uppercase mb-0 headline_text">Flywheel Report Filter</h4>
        <small></small>
        <hr>
        <form method="post" id="flywheel_filter_form">
            <div class="col-12 mb-1">
                <h5 class="subhead_text">Vendors <span style="color: red">*</span> @csrf</h5>
                <select class="form-control body_text" id="flywheel_filter_vendor" name="flywheel_filter_vendor[]" multiple="multiple">
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
                <select class="form-control body_text" id="flywheel_filter_range">
                    <option value="0">Daily</option>
                    <option value="1">Weekly</option>
                    <option value="2">Monthly</option>
                </select>
                <input type='hidden' id="filter_range" name="filter_range" value="1" />
            </div>

            <div class="col-12 mb-1">
                <h5 class="subhead_text">Calender <span style="color: red">*</span></h5>
                <input type='text' id='custom_data_value' name='custom_data_value' class="form-control pickadate" style=" font-family: 'Axiforma Light', sans-serif;" />
                <input type='hidden' id='filter_range_picker' name='filter_range_picker' placeholder="Select Range" class="form-control" />
                <input type='hidden' id="flywheel_filter_date_range" name="flywheel_filter_date_range" class="form-control pickadate" />
            </div>
            <div class="col-12 mb-1">
                <button type="submit" name="generate_report" id="generate_report" class="btn btn-info ">Apply</button>
            </div>
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
    <!-- custom css -->

    @if(Auth::user()->profile->profile_mode == 'dark-layout')
    <link rel="stylesheet" type="text/css" href="{{ asset('c3/css/c3-dark.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('datatimepicker/bootstrap-datetimepicker-dark.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('daterangepicker/daterangepicker-dark.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/flywheel/flywheel_dark.css') }}">
    @else
    <link rel="stylesheet" type="text/css" href="{{ asset('daterangepicker/daterangepicker-light.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/flywheel/flywheel.css') }}">
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
    <script src="{{ asset('js/validation/flywheel/visual.js') }}"></script>
    @endsection