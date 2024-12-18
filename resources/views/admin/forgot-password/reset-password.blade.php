@extends('admin.layouts.before-login-layout')



@section('content')

    <body class="hold-transition login-page">

    <div class="login-box">

        <div class="login-logo">

            <img src="{{asset('assets/images/Logo_la-grange.svg')}}" alt="" />

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

                <p class="login-box-msg">Vous n'êtes qu'à un pas de votre nouveau mot de passe, récupérez votre mot de passe maintenant.</p>



                <form  method="post" id="resetpass">

                    @csrf

                    <div class="input-group mb-3">

                        <input type="password" class="form-control checkpass" id="newpass" placeholder="Mot de passe" name="new_password">

                        <div class="input-group-append">

                            <div class="input-group-text">

                                <span class="fas fa-lock"></span>

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-12"><span class="check_msg" id="checkpass_msg" style="color: red;"></span></div>

                    </div>

                    <div class="input-group mb-3">

                        <input type="password" class="form-control" id="confpass" placeholder="Confirmez le mot de passe" name="confirm_password">

                        <div class="input-group-append">

                            <div class="input-group-text">

                                <span class="fas fa-lock"></span>

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-12"><span class="check_msg" id="checkpass_confirm_msg" style="color: red;"></span></div>

                    </div>

                    <div class="row">

                        <div class="col-12">

                            <button type="submit" class="btn btn-signin btn-block">Changer le mot de passe</button>

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

            



@endsection





