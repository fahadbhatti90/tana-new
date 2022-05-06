@extends('layouts.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Load SC Sale Report</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"> Load SC Sale
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
                            <div class="tab-content pt-1">
                                <div class="tab-pane active" id="sellerCenter-daily-fill" role="tabpanel" aria-labelledby="sellerCenter-daily-tab-fill">
                                    <div class="sellerCenter-daily-filter">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive border rounded px-1 ">
                                                    <h6 class="border-bottom py-1 mx-1 mb-0 font-medium-2">
                                                        <i class="feather icon-calendar mr-50 "></i>
                                                        Load Daily SC Sales
                                                    </h6>
                                                    <div class="row">
                                                        <div class="col-sm-8 col-12">
                                                            <br />
                                                            <form class="form form-horizontal" id="load_daily_seller_center_form">
                                                                <div class="form-body">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="form-group row">
                                                                                <div class="col-md-3">
                                                                                    <span>Select Date Range</span>
                                                                                </div>
                                                                                <div class="col-md-9">
                                                                                    @csrf
                                                                                    <input type='text' id="load_daily_seller_center_range" name="load_daily_seller_center_range" class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8 offset-md-3">
                                                                            <button type="submit" name="load_daily_seller_center" id="load_daily_seller_center" class="btn btn-info mr-1 mb-1 waves-effect waves-light">Load SC sale data</button>
                                                                            <button hidden id="load_daily_seller_center_loader" class="btn btn-info mb-1 waves-effect waves-light" type="button">
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
<script src="{{ asset('js/validation/sellerCenter/load.js') }}"></script>
@endsection
