<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="apple-touch-icon" href="{{ asset('images/cropped-logo-32x32.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/cropped-logo-32x32.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/ui/prism.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/animate/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/extensions/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/extensions/toastr.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
    <!-- FONT FOR ALL PAGES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom/text.css') }}">
    <!-- End Font style -->
    @yield('VendorCSS')
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/themes/semi-dark-layout.css') }}">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    @yield('PageCSS')
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom-laravel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom-scroll.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/extensions/toastr.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('formvalidation/dist/css/formValidation.min.css') }}">
    <!-- END: Custom CSS-->
    <script type="text/javascript">
        var base_url = "{{ URL::to('') }}";
    </script>
    <!-- Ajax Script -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    @yield('ajaxContent')

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-floating footer-static menu-collapsed {{ Auth::user()->profile->profile_mode }} " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav navbar-light navbar-shadow">
        <div class="navbar-wrapper">
            <div class="navbar-container content">
                <div class="navbar-collapse" id="navbar-mobile">
                    <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                        <ul class="nav navbar-nav">
                            <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon feather icon-menu"></i></a></li>
                        </ul>
                    </div>
                    <ul class="nav navbar-nav float-right">
                        <li class="dropdown dropdown-language nav-item">
                            <a class="switchBrand dropdown-toggle nav-link">
                                <i class="feather icon-check-circle"></i>
                                <span class="selected-language">{{ Auth::user()->getGlobalBrand() }}</span>
                            </a>
                        </li>
                        <li class="dropdown dropdown-notification nav-item">
                            <a class="nav-link nav-link-label" href="#" data-toggle="dropdown" aria-expanded="false">
                                <i class="ficon feather icon-bell"></i>
                                <span class="badge badge-pill badge-info badge-up" id="notificationsCount">-</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <div class="dropdown-header m-0">
                                        <h3 class="white"><span class="white" id="notificationsHeaderCount">-</span> Notification</h3>
                                    </div>
                                </li>
                                <li class="scrollable-container media-list ps" id="notificationContent">

                                </li>
                                <li class="dropdown-menu-footer">
                                    <a class="dropdown-item p-1 text-center" href="{{  route('notification.index') }}">Show all notifications</a>
                                </li>
                            </ul>
                        </li>
                        @guest
                        <script type="text/javascript">
                            window.location = "{{ url('/login') }}"; //here double curly bracket
                        </script>
                        @else
                        <li class="dropdown dropdown-user nav-item">
                            <a class="dropdown-toggle nav-link dropdown-user-link" style="padding: 0.7rem 1rem 0.7rem 2rem;" href="#" data-toggle="dropdown">
                                <div class="user-nav d-sm-flex d-none">
                                    <span class="user-name text-bold-600" style='white-space: nowrap;'>{{ Auth::user()->username }}</span>
                                    <span class="user-status">{{ Auth::user()->roles()->get()->first()->role_name }}</span>
                                </div>
                                <span>
                                    <img class="round" src="{{ asset('images/tana-user.png') }}" alt="user" height="40" width="40">
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="changePassword_option dropdown-item">
                                    <i class="feather icon-lock"></i> Change Password
                                </a>
                                <a class="changeMode_option dropdown-item">
                                    @if(Auth::user()->profile->profile_mode == "dark-layout")
                                    <i class="feather icon-layout"></i>Change to Light mode
                                    @else
                                    <i class="feather icon-layout"></i>Change to Dark mode
                                    @endif
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" data-toggle="modal" data-target="#logoutModal">
                                    <i class="feather icon-log-out"></i> Logout
                                </a>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true" onmouseover="hideImage1()" onmouseout="hideImage2()">
        <div class="navbar-header pl-2 mb-1">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto"><a class="navbar-brand" href="{{ route('home') }}">
                        @if(Auth::user()->profile->profile_mode == 'dark-layout')
                        <div class="pl-0 mb-1" id="img3" style="display: none;">
                            <img class="pl-0 mb-0" src="{{ asset('images/dark-cropped-logo-32x32.png') }}" width='100%' />
                        </div>
                        <div class="pl-0 mb-1" id="img4">
                            <img class="pl-0 mb-0" src="{{ asset('images/Tanalytics(white).png') }}" width='100%' />
                        </div>
                        <div class="brand-text pl-0 pb-0 mb-0">
                            <img class="pl-0 mb-0" src="{{ asset('images/dark-design.png') }}" width='160px' />
                            <h2></h2>
                        </div>
                        @else
                        <div class="pl-0 mb-1" id="img1" style="display: none;">
                            <img class="pl-0 mb-0" src="{{ asset('images/cropped-logo-32x32.png') }}" width='100%' />
                        </div>
                        <div class="pl-0 mb-1" id="img2">
                            <img class="pl-0 mb-0" src="{{ asset('images/Tanalytics(black)logo.png') }}" width='100%' />
                        </div>
                        <div class="brand-text pl-0 pb-0 mb-0">
                            <img class="pl-0 mb-0" src="{{ asset('images/design.png') }}" width='160px' />
                            <h2></h2>
                        </div>
                        @endif
                    </a>
                </li>
                <li class="nav-item nav-toggle">
                    <a class="nav-link modern-nav-toggle pr-0 shepherd-modal-target" data-toggle="collapse">
                        <i class="icon-x d-block d-xl-none font-medium-4 info toggle-icon feather icon-disc"></i>
                        <i class="toggle-icon icon-disc font-medium-4 d-none d-xl-block info collapse-toggle-icon feather" data-ticon="icon-disc" tabindex="0">
                        </i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content" id="ps-mid">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="{{ (request()->is('home')) ? 'active' : '' }} nav-item">
                    <a href="{{  route('home') }}">
                        <i class="feather icon-home"></i><span class="menu-title" data-i18n="Dashboard">Dashboard</span>

                    </a>
                </li>

                @if(checkOptionPermission(array(1,2,3,11),1))
                <li class="nav-item has-sub">
                    <a href="">
                        <i class="feather icon-users"></i>
                        <span class="menu-title" data-i18n="">Users</span>
                    </a>
                    <ul class="menu-content">
                        @if(checkOptionPermission(array(1),1))
                        <li class="{{ (request()->is('superadmin')) ? 'active' : '' }}">
                            <a href="{{  route('superadmin.index') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-title" data-i18n="SuperAdmin">Super Admin</span>
                            </a>
                        </li>
                        @endif

                        @if(checkOptionPermission(array(2),1))
                        <li class="{{ (request()->is('admin')) ? 'active' : '' }}">
                            <a href="{{  route('admin.index') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-title" data-i18n="Admin">Admin</span>
                            </a>
                        </li>
                        @endif

                        @if(checkOptionPermission(array(3),1))
                        <li class="{{ (request()->is('user')) ? 'active' : '' }} ">
                            <a href="{{  route('user.index') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-title" data-i18n="User">User</span>
                            </a>
                        </li>
                        @endif

                        @if(checkOptionPermission(array(11),1))
                        <li class="{{ (request()->is('operator')) ? 'active' : '' }} ">
                            <a href="{{  route('operator.index') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-title" data-i18n="Operator">Operator</span>
                            </a>
                        </li>
                        @endif

                    </ul>
                </li>
                @endif

                @if(Auth::user()->roles()->get()->first()->role_id == 1)
                <li class="{{ (request()->is('role')) ? 'active' : '' }} nav-item">
                    <a href="{{  route('role.index') }}">
                        <i class="feather icon-user-check"></i><span class="menu-title" data-i18n="Authorization">Authorization</span>

                    </a>
                </li>
                @endif

                @if(checkOptionPermission(array(4,5),1))
                <li class="nav-item has-sub">
                    <a href="">
                        <i class="feather icon-briefcase"></i>
                        <span class="menu-title" data-i18n="">Management</span>
                    </a>
                    <ul class="menu-content">
                        @if(checkOptionPermission(array(4),1))
                        <li class="{{ (request()->is('brand')) ? 'active' : '' }} nav-item">
                            <a href="{{  route('brand.index') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-title" data-i18n="Brand">Brand</span>
                            </a>
                        </li>
                        @endif
                        @if(checkOptionPermission(array(5),1))
                        <li class="{{ (request()->is('user-vendors')) ? 'active' : '' }} nav-item">
                            <a href="{{  route('user-vendors.index') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-title" data-i18n="user-vendors">Vendor</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(checkOptionPermission(array(1, 2, 3, 4, 5, 11),4))
                <li class="nav-item has-sub">
                    <a href="">
                        <i class="feather icon-trash-2"></i>
                        <span class="menu-title" data-i18n="">Archive</span>
                    </a>
                    <ul class="menu-content">
                        @if(checkOptionPermission(array(1),4))
                        <li class="{{ (request()->is('superadmin/restore')) ? 'active' : '' }}">
                            <a href="{{  route('superadmin.restore') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-title" data-i18n="ArchiveSuperAdmin">Super Admin</span>
                            </a>
                        </li>
                        @endif

                        @if(checkOptionPermission(array(2),4))
                        <li class="{{ (request()->is('admin/restore')) ? 'active' : '' }}">
                            <a href="{{  route('admin.restore') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-title" data-i18n="ArchiveAdmin">Admin</span>
                            </a>
                        </li>
                        @endif

                        @if(checkOptionPermission(array(3),4))
                        <li class="{{ (request()->is('user/restore')) ? 'active' : '' }} ">
                            <a href="{{  route('user.restore') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-title" data-i18n="ArchiveUser">User</span>
                            </a>
                        </li>
                        @endif
                        @if(checkOptionPermission(array(11),4))
                        <li class="{{ (request()->is('operator/restore')) ? 'active' : '' }} ">
                            <a href="{{  route('operator.restore') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-title" data-i18n="ArchiveOperator">Operator</span>
                            </a>
                        </li>
                        @endif
                        @if(checkOptionPermission(array(4),4))
                        <li class="{{ (request()->is('brand/restore')) ? 'active' : '' }} ">
                            <a href="{{  route('brand.restore') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-title" data-i18n="ArchiveBrand">Brand</span>
                            </a>
                        </li>
                        @endif
                        @if(checkOptionPermission(array(5),4))
                        <li class="{{ (request()->is('user-vendors/restore')) ? 'active' : '' }} ">
                            <a href="{{  route('user-vendors.restore') }}">
                                <i class="feather icon-circle"></i>
                                <span class="menu-title" data-i18n="Vendor">Vendor</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(checkUserPermission(array(3),1) || checkUserPermission(array(4),1))
                <li class="nav-item has-sub">
                    <a href="">
                        <i class="feather icon-pie-chart"></i>
                        <span class="menu-title" data-i18n="">Analytics</span>
                    </a>
                    <ul class="menu-content">
                        {{--<li class="{{ (request()->is('sales/visual')) ? 'active' : '' }} nav-item">
                        <a href="{{  route('sales.visual') }}">
                            <i class="feather icon-circle"></i>
                            <span class="menu-title" data-i18n="Sales">Sales</span>
                        </a>
                </li>--}}
                @if(checkUserPermission(array(3),1))
                <li class="{{ (request()->is('sales/visual/new')) ? 'active' : '' }} nav-item">
                    <a href="{{  route('sales.visual.new') }}">
                        <i class="feather icon-circle"></i>
                        <span class="menu-title" data-i18n="SalesExtended">Sales</span>
                    </a>
                </li>
                @endif
                {{--<li class="{{ (request()->is('ed/confirmPO')) ? 'active' : '' }} nav-item">
                <a href="{{  route('ed.confirmPO') }}">
                    <i class="feather icon-circle"></i>
                    <span class="menu-title" data-i18n="ConfirmedPO">Confirmed PO</span>
                </a>
                </li>--}}
                @if(checkUserPermission(array(4),1))
                <li class="{{ (request()->is('ed/confirmPOExtended')) ? 'active' : '' }} nav-item">
                    <a href="{{  route('ed.confirmPOExtended') }}">
                        <i class="feather icon-circle"></i>
                        <span class="menu-title" data-i18n="ConfirmedPOExtended">Confirmed PO</span>
                    </a>
                </li>
                @endif
            </ul>
            </li>
            @endif

            @if(checkUserPermission(array(2),1))
            <li class="nav-item has-sub">
                <a href="">
                    <!-- <i class="fa fa-amazon"></i> -->
                    @if(Auth::user()->profile->profile_mode == 'dark-layout')
                    <img class="pl-0 mb-0" src="{{ asset('images/Tana-click(darkmode).png') }}" style="    max-width: none;" />
                    @else
                    <img class="pl-0 mb-0" src="{{ asset('images/Tana-click.png') }}" style="max-width: none;" />
                    @endif
                    <span class="menu-title" data-i18n="" style="padding-left: 10px;">TanaClicks</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ (request()->is('businessView/visual')) ? 'active' : '' }} nav-item">
                        <a href="{{  route('businessView.visual') }}">
                            <i class="feather icon-circle"></i>
                            <span class="menu-title" data-i18n="BussinessReview">Business Review</span>
                        </a>
                    </li>
                    <li class="{{ (request()->is('flywheel.visual')) ? 'active' : '' }} nav-item">
                        <a href="{{  route('flywheel.visual') }}">
                            <i class="feather icon-circle"></i>
                            <span class="menu-title" data-i18n="SalesExtended">Flywheel</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            @if(checkOptionPermission(array(8,9,10),1))
            <li class="nav-item has-sub">
                <a href="">
                    <i class="feather icon-upload-cloud"></i>
                    <span class="menu-title" data-i18n="">File Uploading</span>
                </a>
                <ul class="menu-content">
                    @if(checkOptionPermission(array(8),1))
                    <li class="has-sub">
                        <a href="">
                            <i class="feather icon-circle"></i>
                            <span class="menu-title" data-i18n="">Sales By Shipped Cogs</span>
                        </a>
                        <ul class="menu-content">
                            @if(checkOptionPermission(array(8),2))
                            <li class="{{ (request()->is('sales')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('sales.index') }}">
                                    <i class="feather icon-upload"></i>
                                    <span class="menu-title" data-i18n="">Upload</span>
                                </a>
                            </li>
                            @endif
                            @if(checkOptionPermission(array(8),1))
                            <li class="{{ (request()->is('verify')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('verify.index') }}">
                                    <i class="feather icon-check-circle"></i>
                                    <span class="menu-title" data-i18n="">Verify</span>
                                </a>
                            </li>
                            @endif
                            @if(checkOptionPermission(array(8),3))
                            <li class="{{ (request()->is('sales/load')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('sales.load') }}">
                                    <i class="feather icon-loader"></i>
                                    <span class="menu-title" data-i18n="">Load</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    <li class="has-sub">
                        <a href="">
                            <i class="feather icon-circle"></i>
                            <span class="menu-title" data-i18n="">Sales Ordered Revenue</span>
                        </a>
                        <ul class="menu-content">
                            @if(checkOptionPermission(array(8),2))
                            <li class="{{ (request()->is('sales')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('salesOrderedRevenue.index') }}">
                                    <i class="feather icon-upload"></i>
                                    <span class="menu-title" data-i18n="">Upload</span>
                                </a>
                            </li>
                            @endif
                            @if(checkOptionPermission(array(8),1))
                            <li class="{{ (request()->is('salesOrder')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('salesOrder.verify_all') }}">
                                    <i class="feather icon-check-circle"></i>
                                    <span class="menu-title" data-i18n="">Verify</span>
                                </a>
                            </li>
                            @endif
                            @if(checkOptionPermission(array(8),3))
                            <li class="{{ (request()->is('salesOrder/load')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('salesOrder.load') }}">
                                    <i class="feather icon-loader"></i>
                                    <span class="menu-title" data-i18n="">Load</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    <li class="has-sub">
                        <a href="">
                            <i class="feather icon-circle"></i>
                            <span class="menu-title" data-i18n="">Traffic</span>
                        </a>
                        <ul class="menu-content">
                            @if(checkOptionPermission(array(8),2))
                            <li class="{{ (request()->is('traffic')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('traffic.index') }}">
                                    <i class="feather icon-upload"></i>
                                    <span class="menu-title" data-i18n="">Upload</span>
                                </a>
                            </li>
                            @endif
                            @if(checkOptionPermission(array(8),2))
                            <li class="{{ (request()->is('traffic/verify_all')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('traffic.verify_all') }}">
                                    <i class="feather icon-check-circle"></i>
                                    <span class="menu-title" data-i18n="">Verify</span>
                                </a>
                            </li>
                            @endif
                            @if(checkOptionPermission(array(8),3))
                            <li class="{{ (request()->is('traffic/load')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('traffic.load') }}">
                                    <i class="feather icon-loader"></i>
                                    <span class="menu-title" data-i18n="">Load</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    <li class="has-sub">
                        <a href="">
                            <i class="feather icon-circle"></i>
                            <span class="menu-title" data-i18n="">Inventory</span>
                        </a>
                        <ul class="menu-content">
                            @if(checkOptionPermission(array(8),2))
                            <li class="{{ (request()->is('inventory')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('inventory') }}">
                                    <i class="feather icon-upload"></i>
                                    <span class="menu-title" data-i18n="">Upload Daily</span>
                                </a>
                            </li>
                            @endif
                            @if(checkOptionPermission(array(8),2))
                            <li class="{{ (request()->is('inventory-monthly')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('inventory-monthly') }}">
                                    <i class="feather icon-upload"></i>
                                    <span class="menu-title" data-i18n="">Upload Monthly</span>
                                </a>
                            </li>
                            @endif
                            @if(checkOptionPermission(array(8),1))
                            <li class="{{ (request()->is('inventory/verify_all')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('inventory.verify_all') }}">
                                    <i class="feather icon-check-circle"></i>
                                    <span class="menu-title" data-i18n="">Verify Daily</span>
                                </a>
                            </li>
                            @endif
                            @if(checkOptionPermission(array(8),1))
                            <li class="{{ (request()->is('inventory-monthly/verify_all')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('inventory-monthly.verify_all') }}">
                                    <i class="feather icon-check-circle"></i>
                                    <span class="menu-title" data-i18n="">Verify Monthly</span>
                                </a>
                            </li>
                            @endif
                            @if(checkOptionPermission(array(8),3))
                            <li class="{{ (request()->is('inventory/load')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('inventory.load') }}">
                                    <i class="feather icon-loader"></i>
                                    <span class="menu-title" data-i18n="">Load</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if(checkOptionPermission(array(8),1))
                    <li class="has-sub">
                        <a href="">
                            <i class="feather icon-circle"></i>
                            <span class="menu-title" data-i18n="">Purchase order</span>
                        </a>
                        <ul class="menu-content">
                            @if(checkOptionPermission(array(8),2))
                            <li class="{{ (request()->is('purchase/upload')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('purchase.upload') }}">
                                    <i class="feather icon-upload"></i>
                                    <span class="menu-title" data-i18n="">Upload</span>
                                </a>
                            </li>
                            @endif
                            @if(checkOptionPermission(array(8),1))
                            <li class="{{ (request()->is('purchaseVerify')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('purchaseVerify.verify') }}">
                                    <i class="feather icon-check-circle"></i>
                                    <span class="menu-title" data-i18n="">Verify</span>
                                </a>
                            </li>
                            @endif
                            @if(checkOptionPermission(array(8),3))
                            <li class="{{ (request()->is('purchaseOrder/load')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('purchaseOrder.load') }}">
                                    <i class="feather icon-loader"></i>
                                    <span class="menu-title" data-i18n="">Load</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if(checkOptionPermission(array(8),1))
                    <li class="has-sub">
                        <a href="">
                            <i class="feather icon-circle"></i>
                            <span class="menu-title" data-i18n="">SC Sales</span>
                        </a>
                        <ul class="menu-content">
                            @if(checkOptionPermission(array(8),2))
                            <li class="{{ (request()->is('sellerCenter')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('sellerCenter.index') }}">
                                    <i class="feather icon-upload"></i>
                                    <span class="menu-title" data-i18n="">Upload</span>
                                </a>
                            </li>
                            @endif
                            @if(checkOptionPermission(array(8),1))
                            <li class="{{ (request()->is('sellerCenter/verifyAll')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('sellerCenter.verifyAll') }}">
                                    <i class="feather icon-check-circle"></i>
                                    <span class="menu-title" data-i18n="">Verify</span>
                                </a>
                            </li>
                            @endif
                            @if(checkOptionPermission(array(8),3))
                            <li class="{{ (request()->is('sellerCenter/load')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('sellerCenter.load') }}">
                                    <i class="feather icon-loader"></i>
                                    <span class="menu-title" data-i18n="">Load</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if(checkOptionPermission(array(8),1))
                    <li class="has-sub">
                        <a href="">
                            <i class="feather icon-circle"></i>
                            <span class="menu-title" data-i18n="">Dropship</span>
                        </a>
                        <ul class="menu-content">
                            @if(checkOptionPermission(array(8),2))
                            <li class="{{ (request()->is('driopship')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('dropship.index') }}">
                                    <i class="feather icon-upload"></i>
                                    <span class="menu-title" data-i18n="">Upload</span>
                                </a>
                            </li>
                            @endif
                            @if(checkOptionPermission(array(8),1))
                            <li class="{{ (request()->is('dropship/verifyAll')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('dropship.verifyAll') }}">
                                    <i class="feather icon-check-circle"></i>
                                    <span class="menu-title" data-i18n="">Verify</span>
                                </a>
                            </li>
                            @endif
                            @if(checkOptionPermission(array(8),3))
                            <li class="{{ (request()->is('dropship/load')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('dropship.load') }}">
                                    <i class="feather icon-loader"></i>
                                    <span class="menu-title" data-i18n="">Load</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if(checkOptionPermission(array(9),1))
                    <li class="has-sub">
                        <a href="">
                            <i class="feather icon-circle"></i>
                            <span class="menu-title" data-i18n="">Category</span>
                        </a>
                        <ul class="menu-content">
                            @if(checkOptionPermission(array(9),2))
                            <li class="{{ (request()->is('category')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('category.index') }}">
                                    <i class="feather icon-upload"></i>
                                    <span class="menu-title" data-i18n="">Upload</span>
                                </a>
                            </li>
                            @endif
                            @if(checkOptionPermission(array(9),1))
                            <li class="{{ (request()->is('verifyCategory')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('verifyCategory.index') }}">
                                    <i class="feather icon-check-circle"></i>
                                    <span class="menu-title" data-i18n="">Verify</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if(checkOptionPermission(array(10),1))
                    <li class="has-sub">
                        <a href="">
                            <i class="feather icon-circle"></i>
                            <span class="menu-title" data-i18n="">PTP</span>
                        </a>
                        <ul class="menu-content">
                            @if(checkOptionPermission(array(10),2))
                            <li class="{{ (request()->is('detailsale')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('detailsale.index') }}">
                                    <i class="feather icon-upload"></i>
                                    <span class="menu-title" data-i18n="">Upload</span>
                                </a>
                            </li>
                            @endif
                            @if(checkOptionPermission(array(10),1))
                            <li class="{{ (request()->is('verifyPtp')) ? 'active' : '' }} is-shown">
                                <a href="{{ route('verifyPtp.index') }}">
                                    <i class="feather icon-check-circle"></i>
                                    <span class="menu-title" data-i18n="">Verify</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            @if(Auth::user()->roles()->get()->first()->role_id == 1)
            <li class="nav-item has-sub">
                <a href="">
                    <i class="fa fa-amazon"></i>
                    <span class="menu-title" data-i18n="">AMS</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ (request()->is('ams/dashboard')) ? 'active' : '' }} is-shown">
                        <a href="{{ route('ams.dashBoard') }}">
                            <i class="feather icon-circle"></i>
                            <span class="menu-title" data-i18n="Management">Management</span>
                        </a>
                    </li>
                    @if(Auth::user()->roles()->get()->first()->role_id == 1)
                    <li class="{{ (request()->is('ams/cron')) ? 'active' : '' }}">
                        <a href="{{  route('ams.cron') }}">
                            <i class="feather icon-circle"></i>
                            <span class="menu-title" data-i18n="Scheduling">Scheduling</span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->roles()->get()->first()->role_id == 1)
                    <li class="{{ (request()->is('ams/report')) ? 'active' : '' }}">
                        <a href="{{  route('ams.report') }}">
                            <i class="feather icon-circle"></i>
                            <span class="menu-title" data-i18n="reportStatus">Report Status</span>
                        </a>
                    </li>
                    @endif
                    <!-- <li class="{{ (request()->is('/ams/errors')) ? 'active' : '' }} is-shown">
                            <a href="{{ route('ams.errors') }}">
                                <i class="feather icon-check-circle"></i>
                                <span class="menu-title" data-i18n="">Log Ams</span>
                            </a>
                        </li> -->
                    <!-- @if(checkOptionPermission(array(8),1))
                        <li class="{{ (request()->is('/ams/verifyCampaign')) ? 'active' : '' }} is-shown">
                        <a href="{{ route('ams.verifyCampaign') }}">
                            <i class="feather icon-check-circle"></i>
                            <span class="menu-title" data-i18n="">Campaign Verify</span>
                        </a>
                    </li>
                    @endif -->
                    @if(checkOptionPermission(array(8),3))
                    <li class="{{ (request()->is('campaign/load')) ? 'active' : '' }} is-shown">
                        <a href="{{ route('campaign.load') }}">
                            <i class="feather icon-check-circle"></i>
                            <span class="menu-title" data-i18n="">Campaign Load</span>
                        </a>
                    </li>
                    <li class="{{ (request()->is('search_term/load')) ? 'active' : '' }} is-shown">
                        <a href="{{ route('searchTerm.load') }}">
                            <i class="feather icon-check-circle"></i>
                            <span class="menu-title" data-i18n="">Search Term Load</span>
                        </a>
                    </li>
                    <li class="{{ (request()->is('product_ads/load')) ? 'active' : '' }} is-shown">
                        <a href="{{ route('ProductAds.load') }}">
                            <i class="feather icon-check-circle"></i>
                            <span class="menu-title" data-i18n="">Product Ads Load</span>
                        </a>
                    </li>
                    @endif
                    @if(checkOptionPermission(array(8),1))
                    <li class="{{ (request()->is('ams/amsVerify')) ? 'active' : '' }} is-shown">
                        <a href="{{ route('ams.amsVerify') }}">
                            <i class="feather icon-check-circle"></i>
                            <span class="menu-title" data-i18n="">Ams Verify</span>
                        </a>
                    </li>
                    @endif
                    <!-- <li class="{{ (request()->is('target/load')) ? 'active' : '' }} is-shown">
                        <a href="{{ route('target.load') }}">
                            <i class="feather icon-check-circle"></i>
                            <span class="menu-title" data-i18n="">Ams Load</span>
                        </a>
                    </li> -->
                </ul>
            </li>
            @endif

            @if(checkUserPermission(array(1),1))
            <li class="{{ (request()->is('biddingRule')) ? 'active' : '' }} nav-item">
                <a href="{{  route('biddingRule') }}">
                    <i class="feather icon-check-circle"></i><span class="menu-title" data-i18n="BiddingRule">Bidding Rule</span>
                </a>
            </li>
            @endif
            @if(Auth::user()->roles()->get()->first()->role_id == 1)
            <li class="{{ (request()->is('process')) ? 'active' : '' }} nav-item">
                <a href="{{  route('process') }}">
                    <i class="feather icon-circle"></i>
                    <span class="menu-title" data-i18n="">Process List</span>
                </a>
            </li>
            @endif
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <!-- BEGIN: Page Inner Content-->
            @yield('content')
            <!-- END: Page Inner Content-->
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix blue-grey lighten-2 mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; {{ now()->year }}<a class="text-bold-800 grey darken-2" href="https://codeinformatics.com/" target="_blank">CODE Informatics,</a>All rights Reserved</span>
            <button class="btn btn-info btn-icon scroll-top" type="button"><i class="feather icon-arrow-up"></i></button>
        </p>
    </footer>
    <!-- END: Footer-->

    @yield('right-sidebar-content')

    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('vendors/js/ui/prism.min.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/polyfill.min.js') }}"></script>
    <script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('pusher/pusher.min.js') }}"></script>
    <script type="text/javascript">
        let PUSHER_APP_KEY = '{{ env('
        PUSHER_APP_KEY ') }}';
        let PUSHER_APP_CLUSTER = '{{ env('
        PUSHER_APP_CLUSTER ') }}';
    </script>
    <script src="{{ asset('pusher/notification.js') }}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    @yield('PageVendorJS')
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('js/core/app-menu.js') }}"></script>
    <script src="{{ asset('js/core/app.js') }}"></script>
    <script src="{{ asset('js/scripts/components.js') }}"></script>
    <script src="{{ asset('js/scripts/customizer.js') }}"></script>
    <script src="{{ asset('js/scripts/footer.js') }}"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    @yield('PageJS')
    <!-- Form Validation -->
    <script type="text/javascript" src="{{ asset('formvalidation/dist/js/FormValidation.full.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('formvalidation/dist/js/plugins/Tachyons.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('formvalidation/dist/js/plugins/Bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
    <script src="{{ asset('js/validation/profile.js') }}"></script>
    @yield('formValidation')
    <!-- END: Page JS-->
</body>
<!-- END: Body-->
<!-- Models  -->
<!-- Logout Modal -->

@guest

@else
<!-- Modal -->
<div class="modal fade text-left" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">Logout</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <p>Are you sure you want to logout?</p>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-info" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade text-left" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="changePassword" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="admin_model_title" class="modal-title">Change Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="change_password_form">
                <div class="modal-body">
                    <div class="form-group">
                        @csrf
                    </div>
                    <div class="form-group">
                        <span id="change_password_result"></span>
                    </div>
                    <div class="form-group">
                        <input id="current_password" type="password" class="form-control" name="current_password" placeholder="Current Password" autocomplete="current_password" autofocus>
                    </div>
                    <div class="form-group">
                        <input id="new_password" type="password" class="form-control" name="new_password" placeholder="New Password" autocomplete="new_password" autofocus>
                    </div>
                    <div class="form-group">
                        <input id="new_confirm_password" type="password" class="form-control" name="new_confirm_password" placeholder="Confirm Password" autocomplete="new_confirm_password" autofocus>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="change_password_action" id="change_password_action" class="btn btn-info" value="Update Password" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade text-left" id="switchBrand" tabindex="-1" role="dialog" aria-labelledby="switchBrand" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="switch_brand_title" class="modal-title">Switch Brand</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="switch_brand_form">
                <div class="modal-body">
                    <div class="form-group">
                        @csrf
                    </div>
                    <div class="form-group">
                        <span id="switch_brand_result"></span>
                    </div>
                    <div class="form-group">
                        <select id="switch_brand_info" class="form-control" name="switch_brand_info" autocomplete="switch_brand_info" autofocus>

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="switch_brand_action" id="switch_brand_action" class="btn btn-info" value="Switch Brand " />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endguest
@yield('model')

</html>