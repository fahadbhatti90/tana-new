@extends('layouts.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Load Campaign Report</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"> Load Campaign
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
            <div class="col-sm-12">
                <div class="card overflow-hidden">
                    <div class="card-content">
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="campaign-daily-tab-fill" data-toggle="tab" href="#campaign-daily-fill" role="tab" aria-controls="campaign-daily-fill" aria-selected="false">Load Daily Campaign</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="campaign-weekly-tab-fill" data-toggle="tab" href="#campaign-weekly-fill" role="tab" aria-controls="campaign-weekly-fill" aria-selected="false">Load Weekly Campaign</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="campaign-monthly-tab-fill" data-toggle="tab" href="#campaign-monthly-fill" role="tab" aria-controls="campaign-monthly-fill" aria-selected="false">Load Monthly Campaign</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="campaign-dashboard-tab-fill" data-toggle="tab" href="#campaign-dashboard-fill" role="tab" aria-controls="campaign-dashboard-fill" aria-selected="false">Dashboard</a>
                                </li>
                            </ul>
                            <div class="tab-content pt-1">
                                <div class="tab-pane active" id="campaign-daily-fill" role="tabpanel" aria-labelledby="campaign-daily-tab-fill">
                                    <div class="campaign-daily-filter">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive border rounded px-1 ">
                                                    <h6 class="border-bottom py-1 mx-1 mb-0 font-medium-2">
                                                        <i class="feather icon-calendar mr-50 "></i>
                                                        Load Daily Campaign
                                                    </h6>
                                                    <div class="row">
                                                        <div class="col-sm-8 col-12">
                                                            <br />
                                                            <form class="form form-horizontal" id="load_daily_campaign_form">
                                                                <div class="form-body">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="form-group row">
                                                                                <div class="col-md-3">
                                                                                    <span>Select Date Range</span>
                                                                                </div>
                                                                                <div class="col-md-9">
                                                                                    @csrf
                                                                                    <input type='text' id="load_daily_campaing_range" name="load_daily_campaing_range" class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8 offset-md-3">
                                                                            <button type="submit" name="load_daily_campaign" id="load_daily_campaign" class="btn btn-info mr-1 mb-1 waves-effect waves-light">Load Daily Campaign</button>
                                                                            <button hidden id="load_daily_campaign_loader" class="btn btn-info mb-1 waves-effect waves-light" type="button">
                                                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                                Loading ...
                                                                            </button>
                                                                        </div>
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
                                <div class="tab-pane" id="campaign-weekly-fill" role="tabpanel" aria-labelledby="campaign-weekly-tab-fill">
                                    <div class="campaign-daily-filter">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive border rounded px-1 ">
                                                    <h6 class="border-bottom py-1 mx-1 mb-0 font-medium-2">
                                                        <i class="feather icon-calendar mr-50 "></i>
                                                        Load Weekly Campaign
                                                    </h6>
                                                    <div class="row">
                                                        <div class="col-sm-8 col-12">
                                                            <br />
                                                            <form class="form form-horizontal" id="load_weekly_campaign_form">
                                                                <div class="form-body">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="form-group row">
                                                                                <div class="col-md-3">
                                                                                    <span>Select Week Range</span>
                                                                                </div>
                                                                                <div class="col-md-9">
                                                                                    @csrf
                                                                                    <input type='text' id="load_weekly_campaing_range" name="load_weekly_campaing_range" class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8 offset-md-3">
                                                                            <button type="submit" name="load_weekly_campaign" id="load_weekly_campaign" class="btn btn-info mr-1 mb-1 waves-effect waves-light">Load Weekly Campaign</button>
                                                                            <button hidden id="load_weekly_campaign_loader" class="btn btn-info mb-1 waves-effect waves-light" type="button">
                                                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                                Loading ...
                                                                            </button>
                                                                        </div>
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
                                <div class="tab-pane" id="campaign-monthly-fill" role="tabpanel" aria-labelledby="campaign-monthly-tab-fill">
                                    <div class="campaign-daily-filter">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive border rounded px-1 ">
                                                    <h6 class="border-bottom py-1 mx-1 mb-0 font-medium-2">
                                                        <i class="feather icon-calendar mr-50 "></i>
                                                        Load Monthly Campaign
                                                    </h6>
                                                    <div class="row">
                                                        <div class="col-sm-8 col-12">
                                                            <br />
                                                            <form class="form form-horizontal" id="load_monthly_campaign_form">
                                                                <div class="form-body">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="form-group row">
                                                                                <div class="col-md-3">
                                                                                    <span>Select Month Range</span>
                                                                                </div>
                                                                                <div class="col-md-9">
                                                                                    @csrf
                                                                                    <input type='text' id="load_monthly_campaing_range" name="load_monthly_campaing_range" class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8 offset-md-3">
                                                                            <button type="submit" name="load_monthly_campaign" id="load_monthly_campaign" class="btn btn-info mr-1 mb-1 waves-effect waves-light">Load Monthly Campaign</button>
                                                                            <button hidden id="load_monthly_campaign_loader" class="btn btn-info mb-1 waves-effect waves-light" type="button">
                                                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                                Loading ...
                                                                            </button>
                                                                        </div>
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
                                <div class="tab-pane" id="campaign-dashboard-fill" role="tabpanel" aria-labelledby="campaign-dashboard-tab-fill">
                                    <!-- account setting page start  "-->
                                    <section id="page-account-settings">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-content">
                                                        <div class="card-body card-dashboard">
                                                            <div class="table-responsive">
                                                                <table id="dashboard" class="table" width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Profile ID</th>
                                                                            <th>Profile Name</th>
                                                                            <th>Report Type</th>
                                                                            <th>Daily Max Date</th>
                                                                            <th>Weekly Max Date</th>
                                                                            <th>MonthlyMax Date</th>
                                                                            <th>Inserted At</th>
                                                                        </tr>
                                                                    </thead>
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

@section('PageCSS')
<link rel="stylesheet" type="text/css" href="{{ asset('c3/css/c3.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/core/colors/palette-gradient.css') }}">
@if(Auth::user()->profile->profile_mode == 'dark-layout')
<link rel="stylesheet" type="text/css" href="{{ asset('c3/css/c3-dark.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('daterangepicker/daterangepicker-dark.css') }}">
@else
<link rel="stylesheet" type="text/css" href="{{ asset('daterangepicker/daterangepicker-light.css') }}">
@endif
@endsection

@section('VendorCSS')
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
<script src="{{ asset('js/validation/ams/campaignLoad.js') }}"></script>
<script src="{{ asset('js/scripts/datatables/datatable.js') }}"></script>
@endsection
