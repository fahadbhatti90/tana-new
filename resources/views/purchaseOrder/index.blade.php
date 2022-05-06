@extends('layouts.app')

@section('PageCSS')
<link rel="stylesheet" type="text/css" href="{{ asset('css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/core/colors/palette-gradient.css') }}">
@endsection

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Purchase Order</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active"> Purchase Order
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
        <div class="card-header ">
            <h6 class="m-0 font-weight-bold text-system">Select files from your computer</h6>
        </div>
        <div class="col-xl-12 col-lg-12">
            <div class="card-body">
                <!-- Standar Form action="{{ route('sales.store') }}"-->
                <form name="po_form" id="po_form" class="form-horizontal" method="post" enctype="multipart/form-data" accept-charset="utf-8">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <span id="form_result"></span>
                        </div>
                        @csrf
                        <div class="form-group">
                            <label class="control-label" for="sel1">Select Vendor:</label>
                            <select class="form-control" name="vendor" id="vendor" onclick="order()">
                                @foreach($vendors_list as $vendor)
                                <option value="{{ $vendor->vendor_id }}">
                                    {{ $vendor->vendor_name ." ". $vendor->region }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="open_agg_file">Open AGG File Or Confirmed Purchase Orders File</label>
                            <div class="col-sm-12">
                                <input type="file" class="custom-file-input" id="open_agg_file" name="open_agg_file" accept=".csv,.xlsx">
                                <label class="custom-file-label" for="open_agg_file">Choose file</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="open_nonagg_file">Open Non-AGG File Or Confirmed Purchase Orders Items File</label>
                            <div class="col-sm-12">
                                <input type="file" class="custom-file-input" id="open_nonagg_file" name="open_nonagg_file" accept=".csv,.xlsx">
                                <label class="custom-file-label" for="open_nonagg_file">Choose file</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="close_agg_file">Close AGG File Or Orders History File</label>
                            <div class="col-sm-12">
                                <input type="file" class="custom-file-input" id="close_agg_file" name="close_agg_file" accept=".csv,.xlsx">
                                <label class="custom-file-label" for="close_agg_file">Choose file</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="close_nonagg_file">Close Non-AGG File Or Purchase Orders Items File</label>
                            <div class="col-sm-12">
                                <input type="file" class="custom-file-input" id="close_nonagg_file" name="close_nonagg_file" accept=".csv,.xlsx">
                                <label class="custom-file-label" for="close_nonagg_file">Choose file</label>
                            </div>
                        </div>

                        <hr />
                        <div class="form-group">
                            <button type="submit" name="action_button" id="action_button" class="btn btn-info mb-1 waves-effect waves-light" value="Upload">Upload</button>
                            <button hidden id="action_button_loader" class="btn btn-info mb-1 waves-effect waves-light" type="button">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading ...
                            </button>
                        </div>
                    </div>

                </form>


                <!-- End Form -->

            </div>
        </div>

        <!-- account setting page end -->
    </div>
</div>
@endsection

@section('formValidation')
<script src=" {{ asset('js/validation/purchaseorder/po.js') }}"></script>
@endsection