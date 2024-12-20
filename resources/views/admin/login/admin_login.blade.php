@extends('admin.layouts.before-login-layout')



@section('content')

<body class="hold-transition login-page">

<div class="login-box">

  {{-- <div class="login-logo">
    <img src="{{ asset('/assets') }}/images/logo.svg" height="150" width="150" alt="">
    <!-- Bio Tout Court-->

  </div> --}}

  <!-- /.login-logo -->

  <div class="card">

    <div class="card-body login-card-body">

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

      <p class="login-box-msg">Log in to start</p>



      <form action="{{ route('admin.authentication') }}" method="post" id="login_form">

	  	@csrf

        <div class="input-group mb-3 ">

          <input type="email" class="form-control" placeholder="Email*" name="email">

          <div class="input-group-append">

            <div class="input-group-text">

              <span class="fas fa-envelope"></span>

            </div>

          </div>

        </div>

        <div class="input-group mb-3">

          <input type="password" class="form-control" placeholder="Password*" name="password">

          <div class="input-group-append">

            <div class="input-group-text">

              <span class="fas fa-lock"></span>

            </div>

          </div>

        </div>

        <div class="row">

          <div class="col-6">

            <div class="icheck-primary">

              <input type="checkbox" id="remember" name="remember_me">

              <label for="remember">

                Remember me

              </label>

            </div>

          </div>

          <!-- /.col -->

          <div class="col-6">

            <button type="submit" class="btn btn-signin btn-block">Sign In</button>

          </div>

          <!-- /.col -->

        </div>

      </form>



      <!-- /.social-auth-links -->



      <p class="mb-1">

        <a href="{{route('admin.forgot.password')}}" class="link">Forget Password?</a>

      </p>

    </div>

    <!-- /.login-card-body -->

  </div>

</div>

<!-- /.login-box -->



<!-- jQuery -->



@endsection

