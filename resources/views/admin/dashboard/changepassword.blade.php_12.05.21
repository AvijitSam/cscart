@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{$panel_title}}</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
                <!-- SELECT2 EXAMPLE -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">{{$panel_title}}</h3>
                                </div>
      
          
                            <!-- /.card-header -->
                        <div class="card-body">
                                    @if(count($errors) > 0)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            @foreach ($errors->all() as $error)
                                                <span>{{ $error }}</span><br/>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if(Session::has('success'))
                                        <div class="alert alert-success alert-dismissable __web-inspector-hide-shortcut__">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            {{ Session::get('success') }}
                                        </div>
                                    @endif

                                    @if(Session::has('error'))
                                        <div class="alert alert-danger alert-dismissable">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            {{ Session::get('error') }}
                                        </div>
                                    @endif
                                <form class="form-horizontal" method="POST" action="{{route('admin.changePassword')}}" id="change_password">
                                                {{ csrf_field() }}

                                                <div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
                                                    <label for="new-password" class="col-md-4 control-label">Current Password*</label>

                                                    <div class="col-md-6">
                                                        <input id="current-password" type="password" class="form-control" name="current_password" required>

                                                        @if ($errors->has('current_password'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('current_password') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
                                                    <label for="new-password" class="col-md-4 control-label">New Password*</label>

                                                    <div class="col-md-6">
                                                        <input id="new-password" type="password" class="form-control" name="new_password" required>

                                                        @if ($errors->has('new-password'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('new_password') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="new-password-confirm" class="col-md-4 control-label">Confirm New Password*</label>

                                                    <div class="col-md-6">
                                                        <input id="new-password-confirm" type="password" class="form-control" name="confirm_password" required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-6 col-md-offset-4">
                                                    <a class="btn btn-primary back_new" href="{{route('admin.dashboard')}}">Back</a>
                                                    <button id="" type="submit" class="btn btn-success submit_new">
                                                            Change Password
                                                        </button>
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