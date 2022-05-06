@extends('layouts.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Verify Purchase Order</h2>
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
    <!-- @if(checkOptionPermission(array(8),3))
    <div class="content-header-right text-md-right col-md-3 col-12 d-md-block">
        <div class="form-group breadcrum-right">
            <div class="dropdown">
                 {{route('verify.saleToCore')}}
                <a name="create_record" id="move" href="{{route('verify.saleToCore')}}" class=" btn-icon btn btn-info btn-round" type="button" style="color:white">
                    <i class="feather icon-check-circle"></i> Move all to core </a>
            </div>
        </div>
    </div>
    @endif -->

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
                            @if(Session::has('message'))
                            <p id="success" class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
                            @endif
                            <div class="table-responsive">
                                <table id="users_table2" class="table" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Vendor name</th>
                                            <th>No. of day(s)</th>
                                            <th>Max Date</th>
                                            <th>Row(s) Count</th>
                                            <th>Duplicate</th>
                                            <th width="20%">Action</th>
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
<script src="{{ asset('js/validation/purchaseorder/poVerify.js') }}"></script>
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

@section('model')
<!-- Modal -->
<div class="modal fade text-left" id="managerListModal" tabindex="-1" role="dialog" aria-labelledby="managerListModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-footer">
                <input type="hidden" name="vendor_id" id="vendor_id" />
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ __('Cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->

@endsection
