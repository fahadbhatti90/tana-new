@extends('layouts.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Load Dropship Report</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"> Load Dropship
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
                                    <a class="nav-link active" id="dropship-daily-tab-fill" data-toggle="tab" href="#dropship-daily-fill" role="tab" aria-controls="dropship-daily-fill" aria-selected="false">Load Daily Dropship</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="dropship-weekly-tab-fill" data-toggle="tab" href="#dropship-weekly-fill" role="tab" aria-controls="dropship-weekly-fill" aria-selected="false">Load Weekly Dropship</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="dropship-monthly-tab-fill" data-toggle="tab" href="#dropship-monthly-fill" role="tab" aria-controls="dropship-monthly-fill" aria-selected="false">Load Monthly Dropship</a>
                                </li>
                            </ul>
                            <div class="tab-content pt-1">
                                <div class="tab-pane active" id="dropship-daily-fill" role="tabpanel" aria-labelledby="dropship-daily-tab-fill">
                                    <div class="dropship-daily-filter">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive border rounded px-1 ">
                                                    <h6 class="border-bottom py-1 mx-1 mb-0 font-medium-2">
                                                        <i class="feather icon-calendar mr-50 "></i>
                                                        Load Daily Dropship
                                                    </h6>
                                                    <div class="row">
                                                        <div class="col-sm-8 col-12">
                                                            <br />
                                                            <form class="form form-horizontal" id="load_daily_dropship_form">
                                                                <div class="form-body">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="form-group row">
                                                                                <div class="col-md-3">
                                                                                    <span>Select Date Range</span>
                                                                                </div>
                                                                                <div class="col-md-9">
                                                                                    @csrf
                                                                                    <input type='text' id="load_daily_dropship_range" name="load_daily_dropship_range" class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8 offset-md-3">
                                                                            <button type="submit" name="load_daily_dropship" id="load_daily_dropship" class="btn btn-info mr-1 mb-1 waves-effect waves-light">Load Daily Dropship</button>
                                                                            <button hidden id="load_daily_dropship_loader" class="btn btn-info mb-1 waves-effect waves-light" type="button">
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
                                <div class="tab-pane" id="dropship-weekly-fill" role="tabpanel" aria-labelledby="dropship-weekly-tab-fill">
                                    <div class="dropship-daily-filter">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive border rounded px-1 ">
                                                    <h6 class="border-bottom py-1 mx-1 mb-0 font-medium-2">
                                                        <i class="feather icon-calendar mr-50 "></i>
                                                        Load Weekly Dropship
                                                    </h6>
                                                    <div class="row">
                                                        <div class="col-sm-8 col-12">
                                                            <br />
                                                            <form class="form form-horizontal" id="load_weekly_dropship_form">
                                                                <div class="form-body">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="form-group row">
                                                                                <div class="col-md-3">
                                                                                    <span>Select Week Range</span>
                                                                                </div>
                                                                                <div class="col-md-9">
                                                                                    @csrf
                                                                                    <input type='text' id="load_weekly_dropship_range" name="load_weekly_dropship_range" class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8 offset-md-3">
                                                                            <button type="submit" name="load_weekly_dropship" id="load_weekly_dropship" class="btn btn-info mr-1 mb-1 waves-effect waves-light">Load Weekly Dropship</button>
                                                                            <button hidden id="load_weekly_dropship_loader" class="btn btn-info mb-1 waves-effect waves-light" type="button">
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
                                <div class="tab-pane" id="dropship-monthly-fill" role="tabpanel" aria-labelledby="dropship-monthly-tab-fill">
                                    <div class="dropship-daily-filter">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive border rounded px-1 ">
                                                    <h6 class="border-bottom py-1 mx-1 mb-0 font-medium-2">
                                                        <i class="feather icon-calendar mr-50 "></i>
                                                        Load Monthly Dropship
                                                    </h6>
                                                    <div class="row">
                                                        <div class="col-sm-8 col-12">
                                                            <br />
                                                            <form class="form form-horizontal" id="load_monthly_dropship_form">
                                                                <div class="form-body">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="form-group row">
                                                                                <div class="col-md-3">
                                                                                    <span>Select Month Range</span>
                                                                                </div>
                                                                                <div class="col-md-9">
                                                                                    @csrf
                                                                                    <input type='text' id="load_monthly_dropship_range" name="load_monthly_dropship_range" class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8 offset-md-3">
                                                                            <button type="submit" name="load_monthly_dropship" id="load_monthly_dropship" class="btn btn-info mr-1 mb-1 waves-effect waves-light">Load Monthly Dropship</button>
                                                                            <button hidden id="load_monthly_dropship_loader" class="btn btn-info mb-1 waves-effect waves-light" type="button">
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
<script src="{{ asset('js/validation/dropship/load.js') }}"></script>
@endsection
