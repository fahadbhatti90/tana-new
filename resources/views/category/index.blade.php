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
                <h2 class="content-header-title float-left mb-0">Category</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active"> Category
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
                <!-- Standar Form action="{{ route('category.store') }}"-->
                <form name="category_form" id="category_form" class="form-horizontal" method="post" enctype="multipart/form-data" accept-charset="utf-8">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <span id="form_result"></span>
                        </div>
                        @csrf
                        <div class="form-group">
                            <label class="control-label" for="data_files">Kindly select file which includes these columns<strong>(ASIN,Category,Vendor)</strong></label>
                            <div class="col-sm-12">

                                <input type="file" class="custom-file-input" id="categoryFile" accept=".csv,.xlsx" name="categoryFile" multiple required>
                                <label id="custom-file-label" class="custom-file-label" for="files">Choose file</label>
                            </div>
                            <output id="list"></output>
                            <input type="hidden" name="file_values" id="file_values" />
                        </div>
                        <div class="filenames"></div>

                        <hr />
                        <div class="form-group">
                            <input type="submit" name="action_button" id="action_button" value="Upload in category" class="btn btn-info mb-1 waves-effect waves-light" />
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
<script src="{{ asset('js/validation/category.js') }}"></script>

@endsection
