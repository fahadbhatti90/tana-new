@extends('layouts.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Verify Campaign</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active"> Verify Campaign
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @if(checkOptionPermission(array(8),3))
    <div class="content-header-right text-md-right col-md-3 col-12 d-md-block">
        <div class="form-group breadcrum-right">
            <div class="dropdown">
                <div class="dropdown" style="float:right;">
                    <button type="button" name="moveToCore" id="moveToCore" title="Move Records" class="moveToCore btn-icon btn btn-warning btn-round"><i class="feather icon-check-circle"></i>Save All </button>
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
                                <a class="nav-link active" id="vendor-tab-fill" data-toggle="tab" href="#vendor-fill" role="tab" aria-controls="vendor-fill" aria-selected="false">Sponsor Product</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="vendorID-tab-fill" data-toggle="tab" href="#vendorID-fill" role="tab" aria-controls="vendorID-fill" aria-selected="false">Sponsor Brand</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="vendorID1-tab-fill" data-toggle="tab" href="#vendorID1-fill" role="tab" aria-controls="vendorID1-fill" aria-selected="false">Sponsor Display</a>
                            </li>
                        </ul>
                        <div class="tab-content pt-1">
                            <div class="tab-pane active" id="vendor-fill" role="tabpanel" aria-labelledby="vendor-tab-fill">
                                <!-- account setting page start  "-->
                                <section id="page-account-settings">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header-right">
                                                    <div class="form-group breadcrum-right">
                                                        <div class="dropdown" style="float:right;">
                                                            <button type="button" name="removeDublication" id="removeDublication" title="Delete Records" class="removeDublication btn-icon btn btn-danger btn-round"><i class="feather icon-trash-2"></i>Delete Duplication</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-content">
                                                    <div class="card-body card-dashboard">
                                                        <div class="table-responsive">
                                                            <table id="ams_error_table" class="table" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Vendor ID</th>
                                                                        <th>Vendor Name</th>
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
                            <div class="tab-pane" id="vendorID-fill" role="tabpanel" aria-labelledby="vendorID-tab-fill">
                                <!-- account setting page start  "-->
                                <section id="page-account-settings">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header-right">
                                                    <div class="form-group breadcrum-right">
                                                        <div class="dropdown" style="float:right;">
                                                            <button type="button" name="removeDublicationSb" id="removeDublicationSb" title="Delete Records" class="removeDublicationSb btn-icon btn btn-danger btn-round"><i class="feather icon-trash-2"></i>Delete Duplication</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-content">
                                                    <div class="card-body card-dashboard">
                                                        <div class="table-responsive">
                                                            <table id="ams_error_table1" class="table" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Vendor ID</th>
                                                                        <th>Vendor Name</th>
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
                            <div class="tab-pane" id="vendorID1-fill" role="tabpanel" aria-labelledby="vendorID1-tab-fill">
                                <!-- account setting page start  "-->
                                <section id="page-account-settings">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header-right">
                                                    <div class="form-group breadcrum-right">
                                                        <div class="dropdown" style="float:right;">
                                                            <button type="button" name="removeDublicationSd" id="removeDublicationSd" title="Delete Records" class="removeDublicationSd btn-icon btn btn-danger btn-round"><i class="feather icon-trash-2"></i>Delete Duplication </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-content">
                                                    <div class="card-body card-dashboard">
                                                        <div class="table-responsive">
                                                            <table id="ams_error_table2" class="table" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Vendor ID</th>
                                                                        <th>Vendor Name</th>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- account setting page end -->
    </div>
</div>
@endsection
@section('formValidation')
<script src="{{ asset('js/validation/ams/campaignReportVerify.js') }}"></script>
@endsection

@section('VendorCSS')
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/tables/datatable/datatables.min.css') }}">
@endsection

@section('PageCSS')
<link rel="stylesheet" type="text/css" href="{{ asset('css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/core/colors/palette-gradient.css') }}">
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