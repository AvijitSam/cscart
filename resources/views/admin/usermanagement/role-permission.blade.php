@extends('admin.layouts.after-login-layout')


@section('unique-content')

    @php
        $moduleArray=array();
    @endphp

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>User Management</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Role Permission</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">        
                        <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">{{$panel_title}}</h3>
                                </div>
                                <!-- /.card-header -->
                            <div class="card-body">
                                <form action="{{route('admin.user-management.role.permission',['encryptCode'=>$encryptId])}}" method="POST"  id="changePasswordForm">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        @foreach ($PermissionsData as $role)
                                            @if (!in_array($role->module_id,$moduleArray))
                                                <div class="col-md-6">
                                                    <div class="card card-secondary">
                                                        <div class="card-header">
                                                            <h3 class="card-title">{{ $role->module->module_name }}</h3>
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                                @foreach ($PermissionsData as $key=>$val)
                                                                    @if ($val->module_id == $role->module_id)
                                                                        <div class="form-group">
                                                                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                                                <input type="checkbox"  name="permission[]" value="{{ $val->id }}"  class="custom-control-input customSwitch3" id="customSwitch{{$val->id}}" @if ($val->status == 'A') {{ 'checked' }} @endif>
                                                                                <label class="custom-control-label" for="customSwitch{{$val->id}}">{{ $val->functionality->function_name }}</label>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>
                                                </div>
                                            @endif
                                                @php
                                                    array_push($moduleArray,$role->module_id);
                                                @endphp
                                        @endforeach



                                    </div>
                                    <div class="card-footer">
                                        <div class="">
                                            <a class="btn btn-primary back_new" href="{{route('admin.user-management.role-list')}}">Back</a>
                                            <button id="" type="submit" class="btn btn-success submit_new">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>









@endsection
