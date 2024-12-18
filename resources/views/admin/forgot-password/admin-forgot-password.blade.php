@extends('admin.layouts.before-login-layout')



@section('content')

    <body class="hold-transition login-page">

    <div class="login-box">



        <div class="login-logo">

            <img src="{{asset('assets/images/logo.svg')}}" height="150" width="150" alt="" />

        </div>

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

                <p class="login-box-msg">Vous avez oublié votre mot de passe ? Ici, vous pouvez facilement récupérer un nouveau mot de passe.</p>

                <form action="{{route('admin.forgot.password')}}" method="post" id="forgot_password">

                    @csrf

                    <div class="input-group mb-3">

                        <input type="email" class="form-control" placeholder="E-mail*" name="email">

                        <div class="input-group-append">

                            <div class="input-group-text">

                                <span class="fas fa-envelope"></span>

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-12">

                            <button type="submit" class="btn btn-signin btn-block">Demander un nouveau mot de passe</button>

                        </div>

                        <!-- /.col -->

                    </div>

                </form>



                <p class="mt-3 mb-1">

                    <a href="{{route('admin.login')}}" class="link">Connexion</a>

                </p>



            </div>

            <!-- /.login-card-body -->

        </div>

    </div>

    <!-- /.login-box -->





@endsection

