@extends('layouts.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Load Target and Keyword Report</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"> Load Target and keyword
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content-body">
    <!-- account setting page start -->
    <div class="campaign-daily-filter">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive border rounded px-1 " style="background-color: white;">
                    <h6 class="border-bottom py-1 mx-1 mb-0 font-medium-2">
                        <i class="feather icon-calendar mr-50 "></i>
                        Load Target Or Keyword
                    </h6>
                    <div class="row">
                        <div class="col-sm-8 col-12">
                            <br />
                            <form class="form form-horizontal" id="load_daily_campaign_form">
                                <div class="form-body">
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-9">
                                                @csrf
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 offset-md-3">
                                        <button type="submit" name="load_daily_campaign" id="load_daily_campaign" class="btn btn-info mr-1 mb-1 waves-effect waves-light">Load Target </button>
                                        <button hidden id="load_daily_campaign_loader" class="btn btn-info mb-1 waves-effect waves-light" type="button">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Loading ...
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <form class="form form-horizontal" id="load_weekly_campaign_form">
                                <div class="form-body">
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-9">
                                                @csrf
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 offset-md-3">
                                        <button type="submit" name="load_daily_keyword" id="load_daily_keyword" class="btn btn-info mr-1 mb-1 waves-effect waves-light">Load Keyword</button>
                                        <button hidden id="load_daily_keyword_loader" class="btn btn-info mb-1 waves-effect waves-light" type="button">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Loading ...
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- account setting page end -->
</div>
@endsection

@section('PageCSS')
<link rel="stylesheet" type="text/css" href="{{ asset('c3/css/c3.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/core/colors/palette-gradient.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('daterangepicker/daterangepicker-light.css') }}">
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
<script src="{{ asset('js/validation/ams/targetLoad.js') }}"></script>
@endsection
