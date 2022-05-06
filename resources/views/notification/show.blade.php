@extends('layouts.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Notification Information</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ Route('notification.index') }}">Notification</a>
                        </li>
                        <li class="breadcrumb-item active"> Notification Information
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content-body">
    <!-- account setting page start -->
    <section id="page-account-settings">
        <div class="container">

            <div class="col-xl-12 col-lg-12">
                @if(isset($notification->alert_name))
                <!-- Vendor Table Div -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h4 class="title container"><b>{{ $notification->alert_name }} </b></h4>
                        <h5 class="container"><small>{{ date('F d, Y', strtotime( $notification->trigger_date)) }}</small></h5>

                        <hr>
                        <div class="container">
                            <h6><b>Detail of Notification</b></h6>
                            <p>The complete detail of this notification is given as follow</p>
                            <p>
                                <b>Vendor: </b>{{ $vendor }}<br>
                                <b>Report Range: </b>{{ $notification->report_range }}<br>
                                <b>Report Date: </b>{{ $notification->reported_date }}<br>
                                @switch( $notification->reported_attribute )
                                @case( 'shipped_cogs' )
                                <b>Reported Attribute: </b>Shipped COGS<br>
                                @break
                                @case( 'shipped_units' )
                                <b>Reported Attribute: </b>Shipped Units<br>
                                @break
                                @case( 'shipped_unit' )
                                <b>Reported Attribute: </b>Shipped Units<br>
                                @break
                                @case( 'net_received' )
                                <b>Reported Attribute: </b>Net Receipts<br>
                                @break
                                @case( 'confirmation_rate' )
                                <b>Reported Attribute: </b>Confirmation Rate<br>
                                @break
                                @case( 'yoy' )
                                <b>Reported Attribute: </b>YOY<br>
                                @break
                                @default
                                <b>Reported Attribute: </b>{{ $notification->reported_attribute }}<br>
                                @break
                                @endswitch

                                @if($notification->sub_reported_value != 'None')
                                <b>Sub Reported Attribute: </b>{{ $notification->sub_reported_attribute }}<br>
                                <b>Sub Reported Value: </b>{{ $notification->sub_reported_value }}<br>
                                @endif
                                <b>Reported Value: </b>{{ $notification->reported_value }}<br>
                            </p>
                        </div>
                    </div>
                </div>
                @else
                <script type="text/javascript">
                    window.location = "{{ url('/notification') }}"; //here double curly bracket
                </script>
                @endif
            </div>
        </div>
    </section>
    <!-- account setting page end -->
</div>
@endsection

@section('PageCSS')
<link rel="stylesheet" type="text/css" href="{{ asset('css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/core/colors/palette-gradient.css') }}">
@endsection