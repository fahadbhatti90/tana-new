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
                <h2 class="content-header-title float-left mb-0">Daily Sales</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active"> Sales
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
                <form name="sales_form" id="sales_form" action="{{ route('sales.store') }}" class="form-horizontal" method="post" enctype="multipart/form-data" accept-charset="utf-8">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <span id="form_result"></span>
                        </div>
                        @csrf
                        <div class="form-group">
                            <label class="control-label" for="sel1">Select Vendor:</label>
                            <select class="form-control" name="vendor" id="vendor" onclick="order()">
                                @foreach($vendor_list as $vendor)
                                <option value="{{ $vendor->vendor_id }}">
                                    {{ $vendor->vendor_name ." ". $vendor->region }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="data_files">Sales Detail Data File</label>
                            <div class="col-sm-12">
                                <div id="target_div"></div>
                                <input type="file" class="custom-file-input" id="daily_sales" accept=".csv,.xlsx" name="daily_sales[]" multiple required>
                                <label class="custom-file-label" id="files" for="files">Choose file</label>
                            </div>
                            <div id="output"></div>
                            <output id="list"></output>
                            <input type="hidden" name="file_values" id="file_values" />
                            <input type="hidden" name="count" id="count" value="" />
                        </div>
                        <div class="filenames"></div>
                        <hr />
                        <div class="form-group">
                            <input type="submit" name="action_button" id="action_button" class="btn btn-info mb-1 waves-effect waves-light" value="Upload in sales" />
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
<script src="{{ asset('js/validation/sales.js') }}"></script>
@endsection
