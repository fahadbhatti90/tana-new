@extends('layouts.app')

@section('content')
<script type="text/javascript">
    var brand_id = "{{ $brand_id }}";
</script>
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Associated Vendor</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ Route('brand.index') }}">Brands</a>
                        </li>
                        <li class="breadcrumb-item active">Associated Vendors
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @if(checkOptionPermission(array(7),2))
    <div class="content-header-right text-md-right col-md-3 col-12 d-md-block">
        <div class="form-group breadcrum-right">
            <div class="dropdown">
                <button name="create_record" id="create_record" class="btn-icon btn btn-info waves-effect waves-light" type="button">
                    <i class="feather icon-link"></i> Assign Vendor
                </button>
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
                        <h4 class="card-title">List of all assigned vendors </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table id="vendors_table" class="table" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="10%">S.no</th>
                                            <th>Vendor Name</th>
                                            <th>Region</th>
                                            <th>Tier</th>
                                            <th>Status</th>
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
<script src="{{ asset('js/validation/brand/vendors.js') }}"></script>
@endsection

@section('VendorCSS')
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/tables/datatable/datatables.min.css') }}">
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

@section('model')
<!-- Modal -->
<div class="modal fade text-left" id="vendorModal" tabindex="-1" role="dialog" aria-labelledby="userModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="vendor_modal_title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="vendors_form">
                <div class="modal-body">
                    <div class="form-group">
                        @csrf
                    </div>
                    <div class="form-group">
                        <span id="form_result"></span>
                    </div>
                    <div class="form-group">
                        <input id="vendor_name" type="text" class="form-control" name="vendor_name" placeholder="Vendor" autocomplete="vendor_name" autofocus>
                    </div>
                    <div class="form-group">
                        <input id="region" type="text" class="form-control" name="region" placeholder="Region" autocomplete="Region" autofocus>
                    </div>
                    <div class="form-group">
                        <input id="tier" type="text" class="form-control" name="tier" placeholder="Tier" autocomplete="tier" autofocus>
                    </div>
                    <!-- <div class="form-group">
                        <input id="email" type="email" class="form-control" name="email" placeholder="Email" autocomplete="email">
                    </div> -->
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_id" id="hidden_id" />
                    <input type="hidden" name="form_action" id="form_action" value="Add" />
                    <input type="submit" name="action_button" id="action_button" class="btn btn-info" value="Edit" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade text-left" id="addVendorModal" role="dialog" aria-labelledby="addVendorModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document" style="max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="brand_model_title" class="modal-title">Associate Vendor</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="assign_vendor_form">
                <div class="modal-body" style="overflow: hidden;">
                    <div class="form-group">
                        @csrf
                    </div>
                    <div class="form-group">
                        <label> Select Vendor</label>
                        <select id="vendor_info" class="form-control" name="vendor_info[]" multiple="multiple" style="width: 100% !important;">

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="add_brand_id" id="add_brand_id" value="{{ $brand_id }}" />
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
