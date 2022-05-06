@extends('layouts.app')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Confirmed PO</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"> Confirmed PO
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-right col-md-6 col-12 d-md-block ">
            <div class="form-group breadcrum-right">
                <div class="dropdown">
                    <h3 class="content-header-title mb-0">
                        <a class="customizer-toggle" href="javascript:void(0)"
                           @if(Auth::user()->profile->profile_mode == "dark-layout")
                           style="color: #C2C6DC;"
                           @else
                           style="color: #636363;"
                            @endif
                        >
                            <i class="feather icon-calendar"></i> <span id="selected_date_text">-</span>
                        </a>
                        @if(Auth::user()->roles()->get()->first()->role_id == 1)
                            &nbsp;
                            <button name="po_plan_record" title="PO Plan" id="po_plan_record" class="btn-icon btn btn-info btn-round btn-sm dropdown-toggle waves-effect waves-light" type="button">
                                <i class="feather icon-settings"></i>
                            </button>
                        @endif
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <!-- account setting page start -->
        <section id="page-account-settings">
            <div class="row">
                <div class="col-xl-6 col-md-12 col-sm-12">
                    <div class="card" style="min-height: 370px">
                        <div class="card-content">
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center">
                                        <h2 class="font-large-1 text-bold-700 mt-2" id="confirm_po_ytd">-</h2>
                                    </div>
                                    <div class="col-sm-12 col-12 justify-content-center pl-3 pr-3">
                                        <div class="justify-content-center d-flex align-items-center mt-0" id="po-confirmed" width="100%">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center">
                                        <h4 class="text-bold-700 mt-1">YTD</h4>
                                        <h4 class="text-bold-700 mt-0 pt-0">Confirmed PO's</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-12 col-sm-12">
                    <div class="card" style="min-height: 370px">
                        <div class="card-content">
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-sm-12 col-12 d-flex flex-column flex-wrap text-center">
                                        <h2 class="font-large-1 text-bold-700 mt-2" id="average_confirmation_rate">- %</h2>
                                        <p class="text-center">Average Confirmation Rate</p>
                                    </div>
                                    <div class="col-sm-12 col-12 justify-content-center pl-3 pr-3">
                                        <div class="justify-content-center d-flex align-items-center mt-0" id="all_vendor_PO_confirmed_rate" width="100%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">

                    <div class="row">

                    </div>
                    <div class="card">
                        <div class="card-content">
                            <div class="card-header">
                                <h4 class="card-title">Weekly Confirmed PO's</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                        <tr>
                                            <th rowspan='2' class="text-center align-middle" style="white-space: nowrap;">Vendor Name</th>
                                            <th colspan='2' class="text-center align-middle" style="white-space: nowrap;"><span id="current_week">-</span></th>
                                            <th colspan='2' class="text-center align-middle" style="white-space: nowrap;"><span id="week_1">-</span></th>
                                            <th colspan='2' class="text-center align-middle" style="white-space: nowrap;"><span id="week_2">-</span></th>
                                            <th colspan='2' class="text-center align-middle" style="white-space: nowrap;"><span id="week_3">-</span></th>
                                            <th rowspan='2' class="text-center align-middle" style="white-space: nowrap;">Action</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="white-space: nowrap;"><span class="dollar_unit_label">Dollar</span></th>
                                            <th class="text-center" style="white-space: nowrap;">%</th>

                                            <th class="text-center" style="white-space: nowrap;"><span class="dollar_unit_label">Dollar</span></th>
                                            <th class="text-center" style="white-space: nowrap;">%</th>

                                            <th class="text-center" style="white-space: nowrap;"><span class="dollar_unit_label">Dollar</span></th>
                                            <th class="text-center" style="white-space: nowrap;">%</th>

                                            <th class="text-center" style="white-space: nowrap;"><span class="dollar_unit_label">Dollar</span></th>
                                            <th class="text-center" style="white-space: nowrap;">%</th>
                                        </tr>
                                        </thead>
                                        <tbody id="all_vendors_weekly_confirmed_PO">
                                        <tr>
                                            <td style='padding: 10px; white-space: nowrap;' align='center' colspan='10'>No data found</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="all_vendors_cards">
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
            <h4 class="text-uppercase mb-0">Report Filter</h4>
            <small></small>
            <hr>
            <div id="collapse-sidebar">
                <div class="collapse-sidebar d-flex justify-content-between">
                    <div class="collapse-option-title">
                        <h5 class="pt-25">Unit/Dollar</h5>
                    </div>
                    <div class="collapse-option-switch custom-switch-info">
                        <div class='custom-control custom-switch custom-control-inline'>
                            <input type='checkbox' class='status custom-control-input' style='background-color: #FFB371;' name='dollar-unit-switch' id='dollar-unit-switch' checked/>
                            <label class='custom-control-label' for='dollar-unit-switch'>
                                <span class="switch-text-right"></span>
                                <span class="switch-text-left">$</span>
                            </label>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
            <form id="filter_form">
                <div class="col-12 mb-1">
                    <h5>Calender @csrf</h5>
                    <input type='hidden' id="filter_range" name="filter_range" value="1"/>
                    <div class="input-group">
                        <input type='text' id='filter_range_picker' name='filter_range_picker' placeholder="Select Range" class="form-control" aria-describedby="button-addon2"/>
                        <input type='hidden' id="ed_filter_date_range" name="ed_filter_date_range" class="form-control pickadate" aria-describedby="button-addon2"/>
                        <div class="input-group-append" id="button-addon2">
                            <button type="submit" name="change_report_vendor" id="change_report_vendor" class="btn btn-info"><i class="feather icon-chevrons-right"></i></button>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            <div id="collapse-sidebar">
                <div class="col-12 mb-0">
                    <h5 class="mb-1">Conditional Formatting</h5>
                    <div class="collapse-sidebar d-flex justify-content-between">
                        <div class="collapse-option-title">
                            <p>Red (75% and below)</p>
                        </div>
                        <div class="collapse-option-switch custom-switch-danger">
                            <div class='custom-control custom-switch custom-control-inline'>
                                <input type='checkbox' class='custom-control-input' style='background-color: #FFB371;' name='red_color_formatting_switch' id='red_color_formatting_switch' checked/>
                                <label class='custom-control-label' for='red_color_formatting_switch'>
                                    <span class="switch-text-right"></span>
                                    <span class="switch-text-left"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-0">
                    <div class="collapse-sidebar d-flex justify-content-between">
                        <div class="collapse-option-title">
                            <p>Orange (75% - 80%)</p>
                        </div>
                        <div class="collapse-option-switch custom-switch-info">
                            <div class='custom-control custom-switch custom-control-inline'>
                                <input type='checkbox' class='custom-control-input' style='background-color: #FFB371;' name='orange_color_formatting_switch' id='orange_color_formatting_switch' checked/>
                                <label class='custom-control-label' for='orange_color_formatting_switch'>
                                    <span class="switch-text-right"></span>
                                    <span class="switch-text-left"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-0">
                    <div class="collapse-sidebar d-flex justify-content-between">
                        <div class="collapse-option-title">
                            <p>Green (80% - 100%)</p>
                        </div>
                        <div class="collapse-option-switch custom-switch-success">
                            <div class='custom-control custom-switch custom-control-inline'>
                                <input type='checkbox' class='custom-control-input' style='background-color: #FFB371;' name='green_color_formatting_switch' id='green_color_formatting_switch' checked/>
                                <label class='custom-control-label' for='green_color_formatting_switch'>
                                    <span class="switch-text-right"></span>
                                    <span class="switch-text-left"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
            @endif
            <link rel="stylesheet" href="{{ asset('formvalidation/tachyons.min.css') }}">
        @endsection

        @section('VendorCSS')
            <link rel="stylesheet" type="text/css" href="{{ asset('datatimepicker/bootstrap-datetimepicker.css') }}" />
            <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/tables/datatable/datatables.min.css') }}">
        @endsection

        @section('PageVendorJS')
            <script type="text/javascript" src="{{ asset('daterangepicker/moment.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('datatimepicker/bootstrap-datetimepicker.min.js') }}"></script>
        @endsection

        @section('PageJS')
            <script>
                {{--var access = 'yes';
                @if(sizeof($vendors) == 0 )
                    access = 'no';
                @endif--}}
            </script>
            <!-- Load d3.js and c3.js -->
            <script src="{{ asset('c3/d3.v5.min.js') }}" charset="utf-8"></script>
            <script src="{{ asset('c3/js/c3.min.js') }}"></script>
            <script src="{{ asset('js/validation/executiveDashboard/visual2.js') }}"></script>
            @if(Auth::user()->roles()->get()->first()->role_id == 1)
                <script src="{{ asset('js/validation/executiveDashboard/poPlan.js') }}"></script>
            @endif
        @endsection

        @section('model')
            @if(Auth::user()->roles()->get()->first()->role_id == 1)
            <!-- Modal -->
                <div class="modal fade text-left" id="poPlanModal" tabindex="-1" role="dialog" aria-labelledby="poPlanModal" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Set PO Plan</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="post" id="po_plan_form">
                                <div class="modal-body">
                                    <div class="form-group">
                                        @csrf
                                    </div>
                                    <div class="form-group">
                                        <span id="form_result"></span>
                                    </div>
                                    <div class="form-group">
                                        <input id="po_value" type="number" class="form-control" name="po_value" placeholder="PO Value" autocomplete="po_value" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <input id="po_unit" type="number" class="form-control" name="po_unit" placeholder="PO Unit"  autocomplete="po_unit">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="submit" name="po_plan_action_button" id="po_plan_action_button" class="btn btn-info" value="Save" />
                                    <button hidden id="po_plan_action_button_loader" class="btn btn-info waves-effect waves-light" type="button">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Loading ...
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        {{ __('Cancel') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <div class="modal fade text-left" id="vendorConfirmedPOModal" tabindex="-1" role="dialog" aria-labelledby="vendorConfirmedPOModal" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="vendorNameConfirmedPO">-</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="vendorGraphConfirmedPO" width="100%" style="min-height: 260px;">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
@endsection
