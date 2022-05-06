@extends('layouts.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Load Sales Ordered Report</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"> Load Sales Ordered
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
                                    <a class="nav-link active" id="sale_order-daily-tab-fill" data-toggle="tab" href="#sale_order-daily-fill" role="tab" aria-controls="sale_order-daily-fill" aria-selected="false">Load Daily Sales Ordered</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="sale_order-weekly-tab-fill" data-toggle="tab" href="#sale_order-weekly-fill" role="tab" aria-controls="sale_order-weekly-fill" aria-selected="false">Load Weekly Sales Ordered</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="sale_order-monthly-tab-fill" data-toggle="tab" href="#sale_order-monthly-fill" role="tab" aria-controls="sale_order-monthly-fill" aria-selected="false">Load Monthly Sales Ordered</a>
                                </li>
                            </ul>
                            <div class="tab-content pt-1">
                                <div class="tab-pane active" id="sale_order-daily-fill" role="tabpanel" aria-labelledby="sale_order-daily-tab-fill">
                                    <div class="sale_order-daily-filter">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive border rounded px-1 ">
                                                    <h6 class="border-bottom py-1 mx-1 mb-0 font-medium-2">
                                                        <i class="feather icon-calendar mr-50 "></i>
                                                        Load Daily Sales Ordered
                                                    </h6>
                                                    <div class="row">
                                                        <div class="col-sm-8 col-12">
                                                            <br />
                                                            <form class="form form-horizontal" id="load_daily_sale_order_form">
                                                                <div class="form-body">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="form-group row">
                                                                                <div class="col-md-3">
                                                                                    <span>Select Date Range</span>
                                                                                </div>
                                                                                <div class="col-md-9">
                                                                                    @csrf
                                                                                    <input type='text' id="load_daily_sale_order_range" name="load_daily_sale_order_range" class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8 offset-md-3">
                                                                            <button type="submit" name="load_daily_sale_order" id="load_daily_sale_order" class="btn btn-info mr-1 mb-1 waves-effect waves-light">Load Daily Sales Order</button>
                                                                            <button hidden id="load_daily_sale_order_loader" class="btn btn-info mb-1 waves-effect waves-light" type="button">
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
                                <div class="tab-pane" id="sale_order-weekly-fill" role="tabpanel" aria-labelledby="sale_order-weekly-tab-fill">
                                    <div class="sale_order-daily-filter">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive border rounded px-1 ">
                                                    <h6 class="border-bottom py-1 mx-1 mb-0 font-medium-2">
                                                        <i class="feather icon-calendar mr-50 "></i>
                                                        Load Weekly Sales Ordered
                                                    </h6>
                                                    <div class="row">
                                                        <div class="col-sm-8 col-12">
                                                            <br />
                                                            <form class="form form-horizontal" id="load_weekly_sale_order_form">
                                                                <div class="form-body">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="form-group row">
                                                                                <div class="col-md-3">
                                                                                    <span>Select Week Range</span>
                                                                                </div>
                                                                                <div class="col-md-9">
                                                                                    @csrf
                                                                                    <input type='text' id="load_weekly_sale_order_range" name="load_weekly_sale_order_range" class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8 offset-md-3">
                                                                            <button type="submit" name="load_weekly_sale_order" id="load_weekly_sale_order" class="btn btn-info mr-1 mb-1 waves-effect waves-light">Load Weekly Sales Order</button>
                                                                            <button hidden id="load_weekly_sale_order_loader" class="btn btn-info mb-1 waves-effect waves-light" type="button">
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
                                <div class="tab-pane" id="sale_order-monthly-fill" role="tabpanel" aria-labelledby="sale_order-monthly-tab-fill">
                                    <div class="sale_order-daily-filter">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive border rounded px-1 ">
                                                    <h6 class="border-bottom py-1 mx-1 mb-0 font-medium-2">
                                                        <i class="feather icon-calendar mr-50 "></i>
                                                        Load Monthly Sales Ordered
                                                    </h6>
                                                    <div class="row">
                                                        <div class="col-sm-8 col-12">
                                                            <br />
                                                            <form class="form form-horizontal" id="load_monthly_sale_order_form">
                                                                <div class="form-body">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="form-group row">
                                                                                <div class="col-md-3">
                                                                                    <span>Select Month Range</span>
                                                                                </div>
                                                                                <div class="col-md-9">
                                                                                    @csrf
                                                                                    <input type='text' id="load_monthly_sale_order_range" name="load_monthly_sale_order_range" class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8 offset-md-3">
                                                                            <button type="submit" name="load_monthly_sale_order" id="load_monthly_sale_order" class="btn btn-info mr-1 mb-1 waves-effect waves-light">Load Monthly Sales Order</button>
                                                                            <button hidden id="load_monthly_sale_order_loader" class="btn btn-info mb-1 waves-effect waves-light" type="button">
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

@endsection

@section('PageJS')
<script src="{{ asset('js/validation/salesOrder/load.js') }}"></script>
@endsection
