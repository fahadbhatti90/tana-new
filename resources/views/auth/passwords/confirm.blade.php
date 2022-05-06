
<!DOCTYPE html>
<html lang="en"
      lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      data-textdirection="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Login - {{ config('app.name', 'Laravel') }}</title>
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

    <link rel="stylesheet" href="{{ asset('css/pages/authentication.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-laravel.css') }}">
</head>
<body class="vertical-layout vertical-menu-modern 1-column blank-page bg-full-screen-image "
      data-menu="vertical-menu-modern" data-col="1-column" data-layout="light">
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <section class="row flexbox-container">
                <div class="col-xl-8 col-11 d-flex justify-content-center">
                    <div class="card bg-authentication rounded-0 mb-0">
                        <div class="row m-0">
                            <div class="col-lg-6 d-lg-block d-none text-center align-self-center px-1 py-0">
                                <img src="{{ asset('images/tanalytics-design.png') }}" width="100%" alt="Tana Sales logo">
                            </div>
                            <div class="col-lg-6 col-12 p-0">
                                <div class="card rounded-0 mb-0 px-2">
                                    <div class="card-header pb-1">
                                        <div class="card-title">
                                            <h4 class="mb-0">{{ __('Confirm Password') }}</h4>
                                        </div>
                                    </div>
                                    <p class="px-2">{{ __('Please confirm your password before continuing.') }}</p>
                                        <div class="card-content">
                                            <div class="card-body pt-1">
                                                <form action="{{ route('password.confirm') }}" method="POST">
                                                    @csrf
                                                    <fieldset class="form-label-group form-group position-relative has-icon-left">
                                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                                        @error('password')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                        <div class="form-control-position">
                                                            <i class="feather icon-user"></i>
                                                        </div>
                                                        <label for="password">{{ __('Password') }}</label>
                                                    </fieldset>
                                                    <button type="submit" class="btn btn-info float-right btn-inline">
                                                        {{ __('Confirm Password') }}
                                                    </button>
                                                    @if (Route::has('password.request'))
                                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                                            {{ __('Forgot Your Password?') }}
                                                        </a>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                        <div class="login-footer">
                                            <div class="divider">
                                            </div>
                                            <div class="footer-btn d-inline">
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
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

