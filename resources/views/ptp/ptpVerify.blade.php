@extends('layouts.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Verify Ptp</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Verify
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @if(checkOptionPermission(array(10),3))
    <div class="content-header-right text-md-right col-md-3 col-12 d-md-block">
        <div class="form-group breadcrum-right">
            <div class="dropdown">
                <a name="create_record" id="moveData" href="{{route('verifyPtp.ptpMoveData')}}" class=" btn-icon btn btn-info btn-round" type="button" style="color:white">
                    <i class="feather icon-check-circle"></i> Save </a>
            </div>
        </div>
    </div>
    @endif

</div>
<div class="content-body">
    <!-- account setting page start  "-->
    <section id="page-account-settings">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">List of all Vendors</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="card-body">
                                <div class="users-list-filter">
                                    @if(checkOptionPermission(array(10),4))
                                    <form method="post" id="ptp_filter_form">
                                        @if(Session::has('message'))
                                        <p id="success" class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
                                        @endif
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                <div class="text-bold-300 font-medium-2">
                                                    Vendors @csrf</div>
                                                <fieldset class="form-group">
                                                    <select class="form-control" id="ptp_filter_vendor" name="ptp_filter_vendor" required>
                                                        @foreach($vendor_list as $vendor)
                                                        <option value="{{ $vendor->fk_vendor_name }}">
                                                            {{ $vendor->fk_vendor_name  }}</option>
                                                        @endforeach
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-2 col-12">
                                                <div class="text-bold-300 font-medium-2">
                                                    <div class="text-bold-300 font-medium-2">
                                                        <br />
                                                    </div>
                                                    <fieldset class="form-group">
                                                        <button type="submit" name="delete_record" id="delete_record" class="btn btn-danger btn-round">Delete</button>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    @endif
                                    <div class="table-responsive">
                                        <table id="ptp_table" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Row Id</th>
                                                    <th>Vendor Name</th>
                                                    <th>Category Name</th>
                                                    <th>Shipped Cogs</th>
                                                    <th>Receipt Shipped Units</th>
                                                    <th>Receipt Dollar</th>
                                                    <th>Shipped Units</th>
                                                    <th>PTP Date</th>
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
<script src="{{ asset('js/validation/ptpVerify.js') }}"></script>
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
