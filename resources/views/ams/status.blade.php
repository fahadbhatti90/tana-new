@extends('layouts.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">AMS Report Status</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active"> AMS Report Status
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
            <div class="col-12">
                <div class="card">
                    <div class="card-header row" style="margin: 0;">
                        <div class="content-header-left col-md-6 col-12 mb-2">
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="content-title">Report Information</h3>
                                </div>
                            </div>
                        </div>
                        <div class="content-header-right col-md-6 col-12 d-md-block d-none">
                            <div class="form-group breadcrum-right">
                                <form method="post" id="report_status_form">
                                    <div class="row">
                                        <div class="col-md-6 col-6 p-0">
                                            <fieldset>
                                                <div class="input-group">
                                                    <select class="form-control" id="currentReport" name="currentReport">
                                                        @foreach($reportList as $report)
                                                        <option value="{{ $report->cron_id }}">{{$report->cron_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6 col-6 p-0">
                                            <fieldset>
                                                <div class="input-group">
                                                    <input type="text" id="currentReportDate" name="currentReportDate" placeholder="Select Date" class="form-control">
                                                    <div class="input-group-append" id="button-addon2">
                                                        <button type="submit" name="change_report" id="change_report" class="btn btn-info waves-effect waves-light"><i class="feather icon-chevrons-right"></i></button>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-content pt-0">
                        <div class="card-body card-dashboard pt-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Profile ID</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Country Code</th>
                                            <th>Status</th>
                                            <th>Report ID</th>
                                            <th>Report Link</th>
                                            <th>Report Link</th>
                                        </tr>
                                    </thead>
                                    <tbody id="complete_report">
                                        <tr>
                                            <td style='padding: 10px; white-space: nowrap;' align='center' colspan='8'>No data found</td>
                                        </tr>
                                    </tbody>
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

@section('right-sidebar-content')
<div id="rightDrawer" class="customizer d-md-block">
    <a class="customizer-close" href="javascript:void(0)">
        <i class="feather icon-x"></i>
    </a>
    <a class="customizer-toggle" href="javascript:void(0)">
        <i class="feather icon-refresh-cw fa fa-spin fa-fw white"></i>
    </a>
    <div class="customizer-content p-2 ps ps--active-y">
        <h4 class="text-uppercase mb-0">Recovery Filter</h4>
        <small></small>
        <hr>
        <form method="post" id="recovery_form">
            <div class="col-12 mb-1">
                <h5>Report</h5>
                <select class="form-control" id="report" name="report">
                    @foreach($reportList as $report)
                    <option value="{{ $report->cron_id }}">{{$report->cron_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-1">
                <h5>Days Back</h5>
                <input class="form-control" type='number' max="60" min="1" id="daysBack" name="daysBack" value="1" />
            </div>
            <div class="col-12 mb-1">
                <button type="submit" name="recover" id="recover" class="btn btn-info">Recover</button>
            </div>
        </form>
        <hr>
        <form method="post" id="recovery_range_from">
            <div class="col-12 mb-1">
                <h5>Report</h5>
                <select class="form-control" id="recovery_range_report" name="recovery_range_report">
                    @foreach($reportList as $report)
                    <option value="{{ $report->cron_id }}">{{$report->cron_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-1">
                <h5>Days Range</h5>
                <input type='text' id='recovery_range_value' name='recovery_range_value' class="form-control pickadate" />
            </div>
            <div class="col-12 mb-1">
                <button type="submit" name="recovery_range_submit" id="recovery_range_submit" class="btn btn-info">Recover</button>
            </div>
        </form>
    </div>
    @endsection

    @section('formValidation')
    <script src="{{ asset('js/validation/ams/reportStatus.js') }}"></script>
    @endsection

    @section('VendorCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('datatimepicker/bootstrap-datetimepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('daterangepicker/daterangepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/tables/datatable/datatables.min.css') }}">
    @endsection

    @section('PageCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core/colors/palette-gradient.css') }}">
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

    @section('PageVendorJS')
    <script src="{{ asset('vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('daterangepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('daterangepicker/daterangepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('datatimepicker/bootstrap-datetimepicker.min.js') }}"></script>
    @endsection

    @section('PageJS')
    <script src="{{ asset('js/scripts/datatables/datatable.js') }}"></script>
    @endsection