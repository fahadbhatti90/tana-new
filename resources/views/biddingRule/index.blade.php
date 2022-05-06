@extends('layouts.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Bidding Rule</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"> Bidding Rule
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
        <form @if(!checkUserPermission(array(1),2)) hidden @endif id="bidding_rule_form" method="post">
            <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="card" style="min-height: 340px;">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="justify-content-between mt-1">
                                    <div class="row">
                                        <div class="col-12 mb-1">
                                            @csrf
                                            <p class="mb-0">Rule Name <span style="color: red">*</span></p>
                                            <div class="form-group mb-0">
                                                <input id="rule_name" name="rule_name" class="form-control" placeholder="Enter Rule Name" autofocus />
                                            </div>
                                        </div>
                                        <div class="col-12 mb-1">
                                            <p class="mb-0">Profile <span style="color: red">*</span></p>
                                            <div class="form-group mb-0">
                                                <select data-name="profile" id="profile" name="profile" class="form-control" placeholder="Select Profile">
                                                    <option selected disabled value="">Select profile</option>
                                                    @foreach($profiles as $profile)
                                                    <option value="{{ $profile->id."|".$profile->profile_id }}">{{ $profile->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-1">
                                            <p class="mb-0">Ad Type <span style="color: red">*</span></p>
                                            <div class="form-group mb-0">
                                                <ul class="list-unstyled mb-0">
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset class="m-0 p-0">
                                                            <div class="vs-radio-con vs-radio-info">
                                                                <input data-name="rule_ad_type" type="radio" name="rule_ad_type" value="SP" checked="checked">
                                                                <span class="vs-radio">
                                                                    <span class="vs-radio--border"></span>
                                                                    <span class="vs-radio--circle"></span>
                                                                </span>
                                                                <span class="">Product</span>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset class="m-0 p-0">
                                                            <div class="vs-radio-con vs-radio-info">
                                                                <input data-name="rule_ad_type" type="radio" name="rule_ad_type" value="SB">
                                                                <span class="vs-radio">
                                                                    <span class="vs-radio--border"></span>
                                                                    <span class="vs-radio--circle"></span>
                                                                </span>
                                                                <span class="">Brand</span>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset class="m-0 p-0">
                                                            <div class="vs-radio-con vs-radio-info">
                                                                <input data-name="rule_ad_type" type="radio" name="rule_ad_type" value="SD">
                                                                <span class="vs-radio">
                                                                    <span class="vs-radio--border"></span>
                                                                    <span class="vs-radio--circle"></span>
                                                                </span>
                                                                <span class="">Display</span>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-1">
                                            <p class="mb-0">Rules</p>
                                            <div class="form-group mb-0">
                                                <select data-name="old_rule" id="old_rule" name="old_rule" class="form-control" placeholder="Select Rule">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="card" style="min-height: 340px;">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="justify-content-between mt-1">
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="mb-0">Select Type <span style="color: red">*</span></p>
                                            <div class="form-group mb-0">
                                                <ul class="list-unstyled mb-0">
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset class="m-0 p-0">
                                                            <div class="vs-radio-con vs-radio-info">
                                                                <input data-name="rule_select_type" type="radio" name="rule_select_type" value="portfolio" checked="checked">
                                                                <span class="vs-radio">
                                                                    <span class="vs-radio--border"></span>
                                                                    <span class="vs-radio--circle"></span>
                                                                </span>
                                                                <span class="">Portfolio</span>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset class="m-0 p-0">
                                                            <div class="vs-radio-con vs-radio-info">
                                                                <input data-name="rule_select_type" type="radio" name="rule_select_type" value="campaign">
                                                                <span class="vs-radio">
                                                                    <span class="vs-radio--border"></span>
                                                                    <span class="vs-radio--circle"></span>
                                                                </span>
                                                                <span class="">Campaign</span>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-12 mb-1">
                                            <p class="mb-0">Portfolio/Campaign <span style="color: red">*</span></p>
                                            <div class="form-group mb-0">
                                                <select data-name="rule_select_type_value" id="rule_select_type_value" name="rule_select_type_value[]" class="form-control" multiple>
                                                    <option selected disabled value="">Select profile</option>
                                                </select>
                                            </div>
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
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="justify-content-between mt-1">
                                    <div class="row">
                                        <div class="col-xl-4 col-md-6 col-sm-6 mb-1">
                                            <p class="mb-0">Preset Rule</p>
                                            <div class="form-group mb-0">
                                                <select id="pre_set_rule" name="pre_set_rule" class="form-control">
                                                    <option selected value="0">Select</option>
                                                    @foreach($preset_rules as $preset_rule)
                                                    <option value="{{ $preset_rule->id }}">{{ $preset_rule->preset_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-sm-6 mb-1">
                                            <p class="mb-0">Look Back Period <span style="color: red">*</span></p>
                                            <div class="form-group mb-0">
                                                <select id="look_back_period" name="look_back_period" class="form-control" placeholder="Select Profile">
                                                    <option selected disabled value="">Select</option>
                                                    <option value="last_7_days|7">Last 7 days</option>
                                                    <option value="last_14_days|14">Last 14 days</option>
                                                    <option value="last_21_days|21">Last 21 days</option>
                                                    <option value="last_1_month|30">Last 1 Month </option>
                                                    <option value="last_2_months|60">Last 2 Months </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-sm-6 mb-1">
                                            <p class="mb-0">Frequency <span style="color: red">*</span></p>
                                            <div class="form-group mb-0">
                                                <select id="frequency" name="frequency" class="form-control" placeholder="Select Profile">
                                                    <option selected disabled value="">Select</option>
                                                    <option value="once_per_day">Once per day</option>
                                                    <option value="every_other_day">Every other day</option>
                                                    <option value="once_per_week">Once per week</option>
                                                    <option value="once_per_month">Once per month</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-xl-12 col-md-12 col-sm-12 mb-0">
                                            <p class="mt-0 mb-0">Statement <span style="color: red">*</span></p>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-sm-6 mt-0 mb-1">
                                            <div class="form-group mb-0">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">if</span>
                                                    </div>
                                                    <select class="form-control" id="statement_metric" name="statement_metric" placeholder="Select Profile">
                                                        <option selected disabled value="">Select</option>
                                                        <option value="impressions">Impressions</option>
                                                        <option value="clicks">Clicks</option>
                                                        <option value="cost">Cost</option>
                                                        <option value="revenue">Revenue</option>
                                                        <option value="ROAS">ROAS</option>
                                                        <option value="ACOS">ACOS</option>
                                                        <option value="CPC">CPC</option>
                                                        <option value="CPA">CPA</option>
                                                        <option value="bid">Bid</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-sm-6 mb-1">
                                            <div class="form-group mb-0">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">is</span>
                                                    </div>
                                                    <select class="form-control" id="statement_condition" name="statement_condition" placeholder="Select Profile">
                                                        <option selected disabled value="">Select</option>
                                                        <option value="greater_than">Greater Than</option>
                                                        <option value="less_than">Less Than</option>
                                                        <option value="greater_than_equal_to">Greater Than Equal To</option>
                                                        <option value="less_than_equal_to">Less Than Equal To</option>
                                                        <option value="equal_to">Equal To</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-sm-6 mb-1">
                                            <div class="form-group mb-0">
                                                <div class="input-group">
                                                    <input id="statement_value" name="statement_value" class="form-control" placeholder="Value" />
                                                </div>
                                            </div>
                                        </div>
                                        <div id="add_more_button_div" class="col-xl-2 col-md-6 col-sm-6 mb-1">
                                            <button type="button" id="add_more" name="add_more" class="btn btn-primary" value="0">Add More</button>
                                            <input type="text" id="add_more_exist" name="add_more_exist" value="0" hidden />
                                        </div>
                                    </div>
                                    <div hidden id="add_move_statement_div">
                                        <div class="row justify-content-center mt-0 mb-1">
                                            <div class="col-12 align-self-center">
                                                <div class="form-group mb-0">
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="d-inline-block mr-2">
                                                            <fieldset class="m-0 p-0 align-items-end">
                                                                <p class="m-0 p-0 ">Select</p>
                                                            </fieldset>
                                                            <fieldset class="m-0 p-0">
                                                                <div class="vs-radio-con vs-radio-info">
                                                                    <input type="radio" name="rule_select2" value="and" checked="checked">
                                                                    <span class="vs-radio">
                                                                        <span class="vs-radio--border"></span>
                                                                        <span class="vs-radio--circle"></span>
                                                                    </span>
                                                                    <span class="">AND</span>
                                                                </div>
                                                            </fieldset>
                                                        </li>
                                                        <li class="d-inline-block">
                                                            <fieldset class="m-0 p-0">
                                                                <div class="vs-radio-con vs-radio-info">
                                                                    <input type="radio" name="rule_select2" value="or">
                                                                    <span class="vs-radio">
                                                                        <span class="vs-radio--border"></span>
                                                                        <span class="vs-radio--circle"></span>
                                                                    </span>
                                                                    <span class="">OR</span>
                                                                </div>
                                                            </fieldset>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-4 col-md-6 col-sm-6 mb-1">
                                                <div class="form-group mb-0">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">if</span>
                                                        </div>
                                                        <select class="form-control" data-name="temp_statement2_metric" id="temp_statement2_metric" name="statement2_metric[1]" placeholder="Select Profile">
                                                            <option selected disabled value="">Select</option>
                                                            <option value="impressions">Impressions</option>
                                                            <option value="clicks">Clicks</option>
                                                            <option value="cost">Cost</option>
                                                            <option value="revenue">Revenue</option>
                                                            <option value="ROAS">ROAS</option>
                                                            <option value="ACOS">ACOS</option>
                                                            <option value="CPC">CPC</option>
                                                            <option value="CPA">CPA</option>
                                                            <option value="bid">Bid</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6 col-sm-6 mb-1">
                                                <div class="form-group mb-0">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">is</span>
                                                        </div>
                                                        <select class="form-control" data-name="temp_statement2_condition" id="temp_statement2_condition" name="temp_statement2_condition" placeholder="Select Profile">
                                                            <option selected disabled value="">Select</option>
                                                            <option value="greater_than">Greater Than</option>
                                                            <option value="less_than">Less Than</option>
                                                            <option value="greater_than_equal_to">Greater Than Equal To</option>
                                                            <option value="less_than_equal_to">Less Than Equal To</option>
                                                            <option value="equal_to">Equal To</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6 col-sm-6 mb-1">
                                                <div class="form-group mb-0">
                                                    <div class="input-group">
                                                        <input data-name="temp_statement2_value" id="temp_statement2_value" name="temp_statement2_value" class="form-control" placeholder="Value" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-md-6 col-sm-6 mb-1">
                                                <button type="button" id="remove" name="remove" class="btn btn-danger">Remove</button>
                                            </div>
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
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="justify-content-between mt-1">
                                    <div class="row">
                                        <div class="col-xl-4 col-md-4 col-sm-6 mb-1">
                                            <p class="mb-0">Change By <span style="color: red">*</span></p>
                                            <div class="form-group mb-0">
                                                <div class="input-group">
                                                    <select id="bid_cpc_type" name="bid_cpc_type" class="form-control" placeholder="Select">
                                                        <option value="bid">Bid</option>
                                                        <option value="cpc">Adword CPC</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-sm-6 mb-1">
                                            <p class="mb-0">Then <span style="color: red">*</span></p>
                                            <div class="form-group mb-0">
                                                <div class="input-group">
                                                    <select id="bid" name="bid" class="form-control" placeholder="Select">
                                                        <option selected disabled value="">Select</option>
                                                        <option value="raise">Raise</option>
                                                        <option value="lower">Lower</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-sm-6 mb-1">
                                            <p class="mb-0">Value <span style="color: red">*</span></p>
                                            <div class="input-group form-group mb-0">
                                                <input type="text" id="bid_by_value" name="bid_by_value" placeholder="Enter Value" class="form-control" aria-label="Text input with dropdown button">
                                                <div class="input-group-append">
                                                    <select id="bid_by_type" name="bid_by_type" class="form-control" placeholder="Select" style="min-width: 60px;">
                                                        <option value="percent">%</option>
                                                        <option value="dollar">$</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 mb-1 align-self-start">
                                            <p class="mb-0">CC Email</p>
                                            <select id="cc_emails" class="form-control" name="cc_emails[]" multiple="multiple" style="width: 100% !important;">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-md-right">
                                <button type="button" name="save_as_rule" id="save_as_rule" class="btn btn-outline-info" value="Add Preset Rule">Add Preset Rule</button>
                                <button type="submit" name="save" id="save" class="btn btn-info" value="Save">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Bidding Rule History</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table id="bid_rule_history_table" class="table" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="5%">S.no</th>
                                            <th>Rule Name</th>
                                            <th>Campaign/Portfolio</th>
                                            <th>Included</th>
                                            <th>Preset Rule</th>
                                            <th>Look Back Period</th>
                                            <th>Frequency</th>
                                            <th>Statement</th>
                                            <th>Last Execution</th>
                                            <th>Status</th>
                                            <th width="15%">Action</th>
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

@section('formValidation')
@if(checkUserPermission(array(1),1))
<script src="{{ asset('js/validation/biddingRule/viewRule.js') }}"></script>
@endif
<script src="{{ asset('js/validation/biddingRule/rule.js') }}"></script>
@endsection

@section('VendorCSS')
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/tables/datatable/datatables.min.css') }}">
@endsection

@section('PageCSS')
<link rel="stylesheet" type="text/css" href="{{ asset('css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/core/colors/palette-gradient.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom/biddingRule/custom-style.css') }}">
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

@section('model')
<!-- Modal -->
<div class="modal fade text-left" id="ruleModal" tabindex="-1" role="dialog" aria-labelledby="ruleModal" aria-hidden="true">
    <form method="post" id="edit_bidding_rule_form">
        <div class="modal-xl modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="edit_rule_modal_title">Edit Rule</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 450px">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <span id="edit_rule_form_result"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-12">
                            <div class="card" style="min-height: 335px;">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="justify-content-between mt-1">
                                            <div class="row">
                                                <div class="col-12 mb-1">
                                                    @csrf
                                                    <p class="mb-0">Rule Name <span style="color: red">*</span></p>
                                                    <div class="form-group mb-0">
                                                        <input id="edit_rule_name" name="edit_rule_name" class="form-control" placeholder="Enter Rule Name" autofocus />
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-1">
                                                    <p class="mb-0">Profile <span style="color: red">*</span></p>
                                                    <div class="form-group mb-0">
                                                        <select data-name="edit_profile" id="edit_profile" name="edit_profile" class="form-control" placeholder="Select Profile">
                                                            <option selected disabled value="">Select profile</option>
                                                            {{--@foreach($profiles as $profile)
                                                                <option value="{{ $profile->id."|".$profile->profile_id }}">{{ $profile->name }}</option>
                                                            @endforeach--}}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-1">
                                                    <p class="mb-0">Ad Type <span style="color: red">*</span></p>
                                                    <div class="form-group mb-0">
                                                        <ul class="list-unstyled mb-0">
                                                            <li class="d-inline-block mr-2">
                                                                <fieldset class="m-0 p-0">
                                                                    <div class="vs-radio-con vs-radio-info">
                                                                        <input data-name="edit_rule_ad_type" type="radio" name="edit_rule_ad_type" value="SP" checked="checked">
                                                                        <span class="vs-radio">
                                                                            <span class="vs-radio--border"></span>
                                                                            <span class="vs-radio--circle"></span>
                                                                        </span>
                                                                        <span class="">Product</span>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                            <li class="d-inline-block mr-2">
                                                                <fieldset class="m-0 p-0">
                                                                    <div class="vs-radio-con vs-radio-info">
                                                                        <input data-name="edit_rule_ad_type" type="radio" name="edit_rule_ad_type" value="SB">
                                                                        <span class="vs-radio">
                                                                            <span class="vs-radio--border"></span>
                                                                            <span class="vs-radio--circle"></span>
                                                                        </span>
                                                                        <span class="">Brand</span>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                            <li class="d-inline-block mr-2">
                                                                <fieldset class="m-0 p-0">
                                                                    <div class="vs-radio-con vs-radio-info">
                                                                        <input data-name="edit_rule_ad_type" type="radio" name="edit_rule_ad_type" value="SD">
                                                                        <span class="vs-radio">
                                                                            <span class="vs-radio--border"></span>
                                                                            <span class="vs-radio--circle"></span>
                                                                        </span>
                                                                        <span class="">Display</span>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12">
                            <div class="card" style="min-height: 335px;">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="justify-content-between mt-1">
                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="mb-0">Select Type <span style="color: red">*</span></p>
                                                    <div class="form-group mb-0">
                                                        <ul class="list-unstyled mb-0">
                                                            <li class="d-inline-block mr-2">
                                                                <fieldset class="m-0 p-0">
                                                                    <div class="vs-radio-con vs-radio-info">
                                                                        <input data-name="edit_rule_select_type" type="radio" name="edit_rule_select_type" value="portfolio" checked="checked">
                                                                        <span class="vs-radio">
                                                                            <span class="vs-radio--border"></span>
                                                                            <span class="vs-radio--circle"></span>
                                                                        </span>
                                                                        <span class="">Portfolio</span>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                            <li class="d-inline-block mr-2">
                                                                <fieldset class="m-0 p-0">
                                                                    <div class="vs-radio-con vs-radio-info">
                                                                        <input data-name="edit_rule_select_type" type="radio" name="edit_rule_select_type" value="campaign">
                                                                        <span class="vs-radio">
                                                                            <span class="vs-radio--border"></span>
                                                                            <span class="vs-radio--circle"></span>
                                                                        </span>
                                                                        <span class="">Campaign</span>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-1">
                                                    <p class="mb-0">Portfolio/Campaign <span style="color: red">*</span></p>
                                                    <div class="form-group mb-0">
                                                        <select id="edit_rule_select_type_value" class="form-control" name="edit_rule_select_type_value[]" multiple="multiple" style="width: 100% !important;">
                                                        </select>
                                                    </div>
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
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="justify-content-between mt-1">
                                            <div class="row">
                                                <div class="col-xl-4 col-md-6 col-sm-6 mb-1">
                                                    <p class="mb-0">Preset Rule</p>
                                                    <div class="form-group mb-0">
                                                        <select id="edit_pre_set_rule" name="edit_pre_set_rule" class="form-control">
                                                            <option selected disabled value="">Select Preset Rule</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-md-6 col-sm-6 mb-1">
                                                    <p class="mb-0">Look Back Period <span style="color: red">*</span></p>
                                                    <div class="form-group mb-0">
                                                        <select id="edit_look_back_period" name="edit_look_back_period" class="form-control">
                                                            <option selected disabled value="">Select</option>
                                                            <option value="last_7_days|7">Last 7 days</option>
                                                            <option value="last_14_days|14">Last 14 days</option>
                                                            <option value="last_21_days|21">Last 21 days</option>
                                                            <option value="last_1_month|30">Last 1 Month </option>
                                                            <option value="last_2_months|60">Last 2 Months </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-md-6 col-sm-6 mb-1">
                                                    <p class="mb-0">Frequency <span style="color: red">*</span></p>
                                                    <div class="form-group mb-0">
                                                        <select id="edit_frequency" name="edit_frequency" class="form-control">
                                                            <option selected disabled value="">Select</option>
                                                            <option value="once_per_day">Once per day</option>
                                                            <option value="every_other_day">Every other day</option>
                                                            <option value="once_per_week">Once per week</option>
                                                            <option value="once_per_month">Once per month</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-xl-12 col-md-12 col-sm-12 mb-0">
                                                    <p class="mt-0 mb-0">Statement <span style="color: red">*</span></p>
                                                </div>
                                                <div class="col-xl-4 col-md-6 col-sm-6 mt-0 mb-1">
                                                    <div class="form-group mb-0">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">if</span>
                                                            </div>
                                                            <select class="form-control" id="edit_statement_metric" name="edit_statement_metric" placeholder="Select Profile">
                                                                <option selected disabled value="">Select</option>
                                                                <option value="impressions">Impressions</option>
                                                                <option value="clicks">Clicks</option>
                                                                <option value="cost">Cost</option>
                                                                <option value="revenue">Revenue</option>
                                                                <option value="ROAS">ROAS</option>
                                                                <option value="ACOS">ACOS</option>
                                                                <option value="CPC">CPC</option>
                                                                <option value="CPA">CPA</option>
                                                                <option value="bid">Bid</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-md-6 col-sm-6 mb-1">
                                                    <div class="form-group mb-0">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">is</span>
                                                            </div>
                                                            <select class="form-control" id="edit_statement_condition" name="edit_statement_condition" placeholder="Select Profile">
                                                                <option selected disabled value="">Select</option>
                                                                <option value="greater_than">Greater Than</option>
                                                                <option value="less_than">Less Than</option>
                                                                <option value="greater_than_equal_to">Greater Than Equal To</option>
                                                                <option value="less_than_equal_to">Less Than Equal To</option>
                                                                <option value="equal_to">Equal To</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-md-6 col-sm-6 mb-1">
                                                    <div class="form-group mb-0">
                                                        <div class="input-group">
                                                            <input id="edit_statement_value" name="edit_statement_value" class="form-control" placeholder="Value" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="edit_add_more_button_div" class="col-xl-2 col-md-6 col-sm-6 mb-1">
                                                    <button type="button" id="edit_add_more" name="edit_add_more" class="btn btn-primary" value="0">Add More</button>
                                                    <input type="text" id="edit_add_more_exist" name="edit_add_more_exist" value="0" hidden />
                                                </div>
                                            </div>
                                            <div hidden id="edit_add_move_statement_div">
                                                <div class="row justify-content-center mt-0 mb-1">
                                                    <div class="col-12 align-self-center">
                                                        <div class="form-group mb-0">
                                                            <ul class="list-unstyled mb-0">
                                                                <li class="d-inline-block mr-2  align-self-center">
                                                                    <fieldset class="m-0 p-0 align-items-end">
                                                                        <p class="m-0 p-0 ">Select</p>
                                                                    </fieldset>
                                                                    <fieldset class="m-0 p-0">
                                                                        <div class="vs-radio-con vs-radio-info">
                                                                            <input type="radio" name="edit_rule_select2" value="and" checked="checked">
                                                                            <span class="vs-radio">
                                                                                <span class="vs-radio--border"></span>
                                                                                <span class="vs-radio--circle"></span>
                                                                            </span>
                                                                            <span class="">AND</span>
                                                                        </div>
                                                                    </fieldset>
                                                                </li>
                                                                <li class="d-inline-block">
                                                                    <fieldset class="m-0 p-0">
                                                                        <div class="vs-radio-con vs-radio-info">
                                                                            <input type="radio" name="edit_rule_select2" value="or">
                                                                            <span class="vs-radio">
                                                                                <span class="vs-radio--border"></span>
                                                                                <span class="vs-radio--circle"></span>
                                                                            </span>
                                                                            <span class="">OR</span>
                                                                        </div>
                                                                    </fieldset>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-4 col-md-6 col-sm-6 mb-1">
                                                        <div class="form-group mb-0">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">if</span>
                                                                </div>
                                                                <select class="form-control" data-name="edit_temp_statement2_metric" id="edit_temp_statement2_metric" name="edit_statement2_metric[1]" placeholder="Select Profile">
                                                                    <option selected disabled value="">Select</option>
                                                                    <option value="impressions">Impressions</option>
                                                                    <option value="clicks">Clicks</option>
                                                                    <option value="cost">Cost</option>
                                                                    <option value="revenue">Revenue</option>
                                                                    <option value="ROAS">ROAS</option>
                                                                    <option value="ACOS">ACOS</option>
                                                                    <option value="CPC">CPC</option>
                                                                    <option value="CPA">CPA</option>
                                                                    <option value="bid">Bid</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-sm-6 mb-1">
                                                        <div class="form-group mb-0">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">is</span>
                                                                </div>
                                                                <select class="form-control" data-name="edit_temp_statement2_condition" id="edit_temp_statement2_condition" name="edit_temp_statement2_condition" placeholder="Select Profile">
                                                                    <option selected disabled value="">Select</option>
                                                                    <option value="greater_than">Greater Than</option>
                                                                    <option value="less_than">Less Than</option>
                                                                    <option value="greater_than_equal_to">Greater Than Equal To</option>
                                                                    <option value="less_than_equal_to">Less Than Equal To</option>
                                                                    <option value="equal_to">Equal To</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-sm-6 mb-1">
                                                        <div class="form-group mb-0">
                                                            <div class="input-group">
                                                                <input data-name="edit_temp_statement2_value" id="edit_temp_statement2_value" name="edit_temp_statement2_value" class="form-control" placeholder="Value" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-2 col-md-6 col-sm-6 mb-1">
                                                        <button type="button" id="edit_remove" name="edit_remove" class="btn btn-danger">Remove</button>
                                                    </div>
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
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="justify-content-between mt-1">
                                            <div class="row">
                                                <div class="col-xl-4 col-md-4 col-sm-6 mb-1">
                                                    <p class="mb-0">Change By <span style="color: red">*</span></p>
                                                    <div class="form-group mb-0">
                                                        <div class="input-group">
                                                            <select id="edit_bid_cpc_type" name="edit_bid_cpc_type" class="form-control" placeholder="Select">
                                                                <option value="bid">Bid</option>
                                                                <option value="cpc">Adword CPC</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-md-4 col-sm-6 mb-1">
                                                    <p class="mb-0">Then <span style="color: red">*</span></p>
                                                    <div class="form-group mb-0">
                                                        <div class="input-group">
                                                            <select id="edit_bid" name="edit_bid" class="form-control" placeholder="Select">
                                                                <option selected disabled value="">Select</option>
                                                                <option value="raise">Raise</option>
                                                                <option value="lower">Lower</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-md-4 col-sm-6 mb-1">
                                                    <p class="mb-0">Value <span style="color: red">*</span></p>
                                                    <div class="input-group form-group mb-0">
                                                        <input type="text" id="edit_bid_by_value" name="edit_bid_by_value" placeholder="Enter Value" class="form-control" aria-label="Text input with dropdown button">
                                                        <div class="input-group-append">
                                                            <select id="edit_bid_by_type" name="edit_bid_by_type" class="form-control" placeholder="Select">
                                                                <option value="percent">%</option>
                                                                <option value="dollar">$</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-12 col-md-12 col-sm-12 mb-1 align-self-start">
                                                    <p class="mb-0">CC Email</p>
                                                    <select id="edit_cc_emails" class="form-control" name="edit_cc_emails[]" multiple="multiple" style="width: 100% !important;">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_id" id="hidden_id" />
                    <input type="submit" name="edit_save" id="edit_save" class="btn btn-info" value="Save Rule" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection