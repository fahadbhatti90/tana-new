@extends('layouts.app')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">AMS</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">AMS
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        @if(checkOptionPermission(array(4),2))
            <div class="content-header-right text-md-right col-md-3 col-12 d-md-block">
                <div class="form-group breadcrum-right">
                    <div class="dropdown">
                        <a class="btn-icon btn btn-info waves-effect waves-light"
                        href="https://www.amazon.com/ap/oa?client_id=amzn1.application-oa2-client.570442d019be482ea08dd54b03553b80&scope=advertising::campaign_management&response_type=code&redirect_uri={{ route('ams.code') }}">
                            <i class="fa fa-amazon"></i> Login with Amazon
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="content-body">
        <!-- account setting page start -->
        <section id="page-account-settings">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">List of all AMS Auth Token</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <div class="table-responsive">
                                    <table id="cron_table" class="table" width="100%">
                                        <thead>
                                        <tr>
                                            <th width="5%">S.no</th>
                                            <th>ClientID</th>
                                            <th>Client Secret</th>
                                            <th>Number Of Profiles</th>
                                            <th>Creation Date</th>
                                            <th>status</th>
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
@endsection


@section('VendorCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/tables/datatable/datatables.min.css') }}">
@endsection

@section('formValidation')
    <script src="{{ asset('js/validation/ams/management.js') }}"></script>
@endsection


@section('PageCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core/colors/brand-custom-style.css') }}">
@endsection

@section('PageVendorJS')
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
    <script src="{{ asset('js/scripts/datatables/datatable.js') }}"></script>
@endsection
