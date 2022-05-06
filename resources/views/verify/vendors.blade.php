@extends('layouts.app')

@section('content')
<script type="text/javascript">
    var vendor_id = "{{ $vendor_id }}";
</script>
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Sale Detail</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboards</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ Route('verify.index') }}">Verify</a>
                        </li>
                        <li class="breadcrumb-item active">Sales Detail
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
                <a name="create_record" href="{{app('url')->route('verify.moveToCore', $vendor_id, true)}}" class="btn-icon btn btn-info btn-round" type="button">
                    <i class="feather icon-check-circle"></i> Save
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
                        <h4 class="card-title">List of all detail sale records </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table id="vendors_table" class="table" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Vendor Name</th>
                                            <th>Sale Date</th>
                                            <th>Row(s) Count</th>
                                            <th>Duplicate row</th>
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
@endsection

@section('formValidation')
<script src="{{ asset('js/validation/verifyvendors.js') }}"></script>
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
<div class="modal fade text-left" id="addVendorModal" tabindex="-1" role="dialog" aria-labelledby="addVendorModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="brand_model_title" class="modal-title">Associate Vendor</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="assign_vendor_form">
                <div class="modal-footer">
                    <input type="hidden" name="add_brand_id" id="add_brand_id" value="{{ $vendor_id }}" />
                    <input type="submit" name="assign_vendor_button" id="assign_vendor_button" class="btn btn-info" value="Assign Vendor" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
