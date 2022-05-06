@extends('layouts.app')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">User Authorization</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ Route('home') }}">Dashboard</a>
                            </li>
                            @php
                                $redirectUrl = route('superadmin.index')
                            @endphp
                            @switch($user->roles()->get()->first()->role_id)
                                @case(1)
                                    @php
                                        $redirectUrl = route('superadmin.index')
                                    @endphp
                                @break
                                @case(2)
                                    @php
                                        $redirectUrl = route('admin.index')
                                    @endphp
                                @break
                                @case(3)
                                    @php
                                        $redirectUrl = route('user.index')
                                    @endphp
                                @break
                                @case(4)
                                    @php
                                        $redirectUrl = route('operator.index')
                                    @endphp
                                @break
                            @endswitch
                            <li class="breadcrumb-item"><a href="{{ $redirectUrl }}">{{ $user->roles()->get()->first()->role_name }}</a>
                            </li>
                            <li class="breadcrumb-item active"> {{ $user->username }}
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
            <div class="row">

                <div class="col-12">
                    <form method="post" id="addUserAuth_form">
                        @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive border rounded px-1 ">
                                <h6 class="border-bottom py-1 mx-1 mb-0 font-medium-2"><i class="feather icon-lock mr-50 "></i>Permissions for {{ $user->username }}</h6>
                                <table class="table table-borderless">
                                    <thead>
                                    <tr>
                                        <th>Module</th>
                                        @foreach($permissions as $permission)
                                            <th>{{ $permission->permission_name }}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($modules as $module)
                                        <tr>
                                            <td>{{ $module->module_name }}</td>
                                            @foreach($permissions as $permission)
                                                    <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="auth[{{ $module->module_name }}-{{ $permission->permission_name }}]"  id="{{ $module->module_name }}-{{ $permission->permission_name }}" class="custom-control-input"
                                                                                                 @if($data[$module->module_name."-".$permission->permission_name]) checked="" @endif>
                                                        <label class="custom-control-label" for="{{ $module->module_name }}-{{ $permission->permission_name }}"></label>
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                                <input type="hidden" name="hidden_id" id="hidden_id" value="{{ $user->user_id }}"/>
                                <button type="button" name="auth_action_button" id="auth_action_button" class="btn btn-info glow mb-1 mb-sm-0 mr-0 mr-sm-1">Save</button>
                                <button type="button" id="back" name="back" class="btn btn-secondary">Back</button>
                            </div>
                        </div>

                    </div>
                </form>
                </div>
            </div>
        </section>
        <!-- account setting page end -->
    </div>
@endsection

@section('formValidation')
    <script type="text/javascript">
        var redirect_url = "{{ $redirectUrl }}";
    </script>
    <script src="{{ asset('js/validation/userAuthorization.js') }}"></script>
@endsection




