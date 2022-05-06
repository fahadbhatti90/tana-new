@extends('layouts.app')

@section('PageCSS')
<link rel="stylesheet" type="text/css" href="{{ asset('css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/core/colors/palette-gradient.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/core/colors/custom.css') }}">
@endsection

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Monthly inventory</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Inventory
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content-body">
    <!--  Form Area -->
    <div class="card shadow ">
        <div class="col-xl-12 col-lg-12">
            <div class="card overflow-hidden">
                <div class="card-content">
                    <div class="card-body">
                        <div class="tab-content pt-1">
                            <div class="tab-pane active" id="vendor-fill" role="tabpanel" aria-labelledby="vendor-tab-fill">
                                <div class="vendor-filter">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive border rounded px-1 ">
                                                <h6 class="border-bottom py-1 mx-1 mb-0 font-medium-2">
                                                    <i class="feather icon-file mr-50 "></i>
                                                    Select files from your computer
                                                </h6>
                                                <div class="row">
                                                    <div class="col-sm-12 col-12">
                                                        <br />
                                                        <form name="vendor_inventory_form" id="vendor_inventory_form" action="{{ route('inventory.store') }}" class="form-horizontal" method="post" enctype="multipart/form-data" accept-charset="utf-8">
                                                            <div class="form-horizontal">
                                                                <div class="form-group">
                                                                    <span id="vendor_form_result"></span>
                                                                </div>
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label class="control-label" for="sel1">Select Vendor:</label>
                                                                    <select class="form-control" name="vendor" id="vendor">
                                                                        @foreach($vendor_list as $vendor)
                                                                        <option value="{{ $vendor->vendor_id }}">
                                                                            {{ $vendor->vendor_name ." ". $vendor->region }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label" for="vendor_data_files">Inventory Detail Data File</label>
                                                                    <div class="col-sm-12">
                                                                        <div id="vendor_target_div"></div>
                                                                        <input type="file" class="custom-file-input" id="vendor_monthly_inventory" accept=".csv,.xlsx" name="vendor_monthly_inventory" multiple required>
                                                                        <label class="custom-file-label" id="vendor_files" for="vendor_files">Choose file</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <button type="submit" name="vendor_action_button" id="vendor_action_button" class="btn btn-info mb-1 waves-effect waves-light" value="Upload in inventory">Upload in inventory</button>
                                                                    <button hidden id="vendor_action_button_loader" class="btn btn-info mb-1 waves-effect waves-light" type="button">
                                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                        Loading ...
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
<script src="{{ asset('js/validation/inventory/monthly_upload.js') }}"></script>
@endsection