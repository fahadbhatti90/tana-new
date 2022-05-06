@extends('layouts.app')

@section('content')
<?php
if (isset($_COOKIE["check"])) {
?>
    <script>
        var type = "{{ $_COOKIE['type'] }}";
        var date = "{{ $_COOKIE['date'] }}";
    </script>
<?php
}
?>
<div class="content-header row">
    <div class="content-header-left col-md-8 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Ams Verify</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Ams Verify
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @if(checkOptionPermission(array(8),3))
    <div class="content-header-right text-md-right col-md-4 col-12 d-md-block">
        <div class="form-group breadcrum-right">
            <div class="dropdown d-flex justify-content-end">
                <div class="dropdown mr-1">
                    <button type="button" name="removeDublication" id="removeDublication" title="Delete Records" class="removeDublication btn-icon btn btn-danger"><i class="feather icon-trash-2"></i>Delete Duplication</button>
                </div>
                <div class="dropdown mr-1">
                    <button type="button" name="moveToCore" id="moveToCore" title="Move Records" class="moveToCore btn-icon btn btn-info"><i class="feather icon-check-circle"></i> Save All</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<div class="content-body">
    <!--  Form Area -->
    <div class="card shadow ">
        <div class="col-xl-12 col-lg-12">
            <div class="card overflow-hidden">
                <div class="card-content">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <?php if (isset($_COOKIE['pageCheck'])) { ?>
                                    <a class="nav-link <?php if (isset($_COOKIE['pageCheck']) && $_COOKIE['pageCheck'] == 'sp') {
                                                            echo "active";
                                                        } ?>" id="vendor-tab-fill" data-toggle="tab" href="#vendor-fill" role="tab" aria-controls="vendor-fill" aria-selected="false">Sponsor Product</a>
                                <?php } else { ?>
                                    <a class="nav-link active" id="vendor-tab-fill" data-toggle="tab" href="#vendor-fill" role="tab" aria-controls="vendor-fill" aria-selected="false">Sponsor Product</a>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php if (isset($_COOKIE['pageCheck']) && $_COOKIE['pageCheck'] == 'sb') {
                                                        echo "active";
                                                    } ?>" id="vendorID-tab-fill" data-toggle="tab" href="#vendorID-fill" role="tab" aria-controls="vendorID-fill" aria-selected="false">Sponsor Brand</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php if (isset($_COOKIE['pageCheck'])  && $_COOKIE['pageCheck'] == 'sd') {
                                                        echo "active";
                                                    } ?>" id="vendorID1-tab-fill" data-toggle="tab" href="#vendorID1-fill" role="tab" aria-controls="vendorID1-fill" aria-selected="false">Sponsor Display</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="vendorID2-tab-fill" data-toggle="tab" href="#vendorID2-fill" role="tab" aria-controls="vendorID2-fill" aria-selected="false">Dashboard</a>
                            </li>
                        </ul>
                        <div class="tab-content pt-1"><?php if (isset($_COOKIE['pageCheck'])) { ?>
                                <div class="tab-pane <?php if (isset($_COOKIE['pageCheck'])  && $_COOKIE['pageCheck'] == 'sp') {
                                                                echo "active";
                                                            } ?>" id="vendor-fill" role="tabpanel" aria-labelledby="vendor-tab-fill">
                                <?php } else { ?>
                                    <div class="tab-pane active" id="vendor-fill" role="tabpanel" aria-labelledby="vendor-tab-fill">
                                    <?php } ?>
                                    <!-- account setting page start  "-->
                                    <section id="page-account-settings">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-content">
                                                        <div class="card-body card-dashboard">
                                                            <div class="table-responsive">
                                                                <table id="ams_error_table" class="table" width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Profile ID</th>
                                                                            <th>Profile Name</th>
                                                                            <th>Domain</th>
                                                                            <th>No Of Days</th>
                                                                            <th>Max Date</th>
                                                                            <th>Count</th>
                                                                            <th>Duplication</th>
                                                                            <th>Action</th>
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
                                    <div class="tab-pane <?php if (isset($_COOKIE['pageCheck'])  && $_COOKIE['pageCheck'] == 'sb') {
                                                                echo "active";
                                                            } ?>" id="vendorID-fill" role="tabpanel" aria-labelledby="vendorID-tab-fill">
                                        <!-- account setting page start  "-->
                                        <section id="page-account-settings">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card">
                                                        <div class="card-content">
                                                            <div class="card-body card-dashboard">
                                                                <div class="table-responsive">
                                                                    <table id="ams_error_table1" class="table" width="100%">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Profile ID</th>
                                                                                <th>Profile Name</th>
                                                                                <th>Domain</th>
                                                                                <th>No Of Days</th>
                                                                                <th>Max Date</th>
                                                                                <th>Count</th>
                                                                                <th>Duplication</th>
                                                                                <th>Action</th>
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
                                    <div class="tab-pane <?php if (isset($_COOKIE['pageCheck'])  && $_COOKIE['pageCheck'] == 'sd') {
                                                                echo "active";
                                                            } ?>" id="vendorID1-fill" role="tabpanel" aria-labelledby="vendorID1-tab-fill">
                                        <!-- account setting page start  "-->
                                        <section id="page-account-settings">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card">
                                                        <div class="card-content">
                                                            <div class="card-body card-dashboard">
                                                                <div class="table-responsive">
                                                                    <table id="ams_error_table2" class="table" width="100%">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Profile ID</th>
                                                                                <th>Profile Name</th>
                                                                                <th>Domain</th>
                                                                                <th>No Of Days</th>
                                                                                <th>Max Date</th>
                                                                                <th>Count</th>
                                                                                <th>Duplication</th>
                                                                                <th>Action</th>
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
                                    <div class="tab-pane" id="vendorID2-fill" role="tabpanel" aria-labelledby="vendorID2-tab-fill">
                                        <!-- account setting page start  "-->
                                        <section id="page-account-settings">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card">
                                                        <div class="card-content">
                                                            <div class="card-body card-dashboard">
                                                                <div class="table-responsive">
                                                                    <table id="ams_error_table3" class="table" width="100%">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Profile ID</th>
                                                                                <th>Profile Name</th>
                                                                                <th>Report Type</th>
                                                                                <th>Max Date</th>
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
            <!-- account setting page end -->
        </div>
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
            <h4 class="text-uppercase mb-0 headline_text">Report Filter</h4>
            <small></small>
            <hr>
            <div id="collapse-sidebar">
                <div class="collapse-sidebar d-flex justify-content-between">
                </div>
            </div>
            <form id="filter_form">
                <div class="col-12 mb-1 subhead_text">
                    <h5>Reporting Range @csrf</h5>
                    <div class="input-group">
                        <select class="form-control body_text" id="po_filter_range">
                            <option value="Keyword">Keyword</option>
                            <option value="Keyword_search_term">Keyword Search Term</option>
                            <option value="Product_ads">Product Ads</option>
                            <option value="Targets">Target</option>
                            <option value="Campaing">Campaign</option>
                            <option value="Targets_audience">Targets Audience</option>
                        </select>
                        <input type='hidden' id="filter_range" name="filter_range" value="1" />
                    </div>
                </div>
                <div class="col-12 mb-1">
                    <h5 class="subhead_text">Calender</h5>
                    <div class="input-group body_text">
                        <input type='text' id='custom_data_value' name='custom_data_value' placeholder="Select Range custom" class="form-control pickadate" />
                        <input type='hidden' id='filter_range_picker' name='filter_range_picker' placeholder="Select Range" class="form-control" />
                        <input type='hidden' id="ed_filter_date_range" name="ed_filter_date_range" class="form-control pickadate" />
                        <div class="input-group-append" id="button-addon2">
                            <button type="submit" name="change_report_vendor" id="change_report_vendor" class="btn btn-info"><i class="feather icon-chevrons-right"></i></button>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            <form id="generate_log_form">
                <div class="col-12 mb-1 subhead_text">
                    <h5>Reporting Range @csrf</h5>
                    <div class="input-group">
                        <select class="form-control body_text" id="generate_log_range" name="generate_log_range">
                            <option value="keyword">Keyword</option>
                            <option value="Keyword_search_term">Keyword Search Term</option>
                            <option value="Product_ads">Product Ads</option>
                            <option value="targets">Target</option>
                            <option value="campaign">Campaign</option>
                            <option value="audience">Targets Audience</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 mb-1">
                    <button type="submit" name="generate_log_button" id="generate_log_button" title="Generate Log Table" class="btn btn-info btn-round"><i class="feather icon-menu"></i> Generate Log</button>
                </div>
            </form>
        </div>
        @endsection
        @section('formValidation')
        <script src="{{ asset('js/validation/ams/keywordTargetVerify.js') }}"></script>
        @endsection


        @section('VendorCSS')
        <link rel="stylesheet" type="text/css" href="{{ asset('datatimepicker/bootstrap-datetimepicker.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('daterangepicker/daterangepicker.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/tables/datatable/datatables.min.css') }}">
        @endsection

        @section('PageCSS')
        <link rel="stylesheet" type="text/css" href="{{ asset('css/core/menu/menu-types/vertical-menu.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/core/colors/palette-gradient.css') }}">
        <!-- vendor css files -->
        <link rel="stylesheet" href="{{ asset('vendors/css/pickers/pickadate/pickadate.css') }}">
        @if(Auth::user()->profile->profile_mode == 'dark-layout')
        <link rel="stylesheet" type="text/css" href="{{ asset('datatimepicker/bootstrap-datetimepicker-dark.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('daterangepicker/daterangepicker-dark.css') }}">
        @else
        <link rel="stylesheet" type="text/css" href="{{ asset('daterangepicker/daterangepicker-light.css') }}">
        @endif
        <link rel="stylesheet" href="{{ asset('formvalidation/tachyons.min.css') }}">
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
        <script src="{{ asset('js/scripts/datatables/datatable.js') }}"></script>
        @endsection