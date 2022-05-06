<!DOCTYPE html>
<html lang="en"
      lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      data-textdirection="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/cropped-logo-32x32.png') }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/ui/prism.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-extended.css') }}">
    <link rel="stylesheet" href="{{ asset('css/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/themes/dark-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/themes/semi-dark-layout.css') }}">

    <link rel="stylesheet" href="{{ asset('css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/core/colors/palette-gradient.css') }}">

    <link rel="stylesheet" href="{{ asset('css/custom-laravel.css') }}">

</head>
<body
    class="vertical-layout vertical-menu-modern 1-column blank-page bg-full-screen-image "
    data-menu="vertical-menu-modern" data-col="1-column" data-layout="light">
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <!-- maintenance -->
            <section class="row flexbox-container">
                <div class="col-xl-7 col-md-8 col-12 d-flex justify-content-center">
                    <div class="card auth-card bg-transparent shadow-none rounded-0 mb-0 w-100">
                        <div class="card-content">
                            <div class="card-body text-center">
                                <img src="{{ asset('images/404.png') }}" class="img-fluid align-self-center" alt="branding logo">
                                <h1 class="font-large-2 my-1">403 - Forbidden!</h1>
                                <p class="px-2">
                                    You don't have permission to access this page,
                                </p>

                                <a class="btn btn-outline-danger btn-lg mt-1" href="{{ Route('home')  }}">Back to Portal Dashboard</a>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- maintenance end -->

        </div>
    </div>
</div>
<!-- End: Content-->
<script src="{{ asset('vendors/js/vendors.min.js') }}"></script>
<script src="{{ asset('vendors/js/ui/prism.min.js') }}"></script>

<script src="{{ asset('js/core/app-menu.js') }}"></script>
<script src="{{ asset('js/core/app.js') }}"></script>
<script src="{{ asset('js/scripts/components.js') }}"></script>
</body>
</html>




