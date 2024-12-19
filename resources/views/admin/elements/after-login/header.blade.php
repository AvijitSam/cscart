<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{Config::get('yourdata.company_name')}}</title>

    <!-- Tell the browser to be responsive to screen width -->

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->

    <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">

    <!-- Ionicons -->

    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Tempusdominus Bbootstrap 4 -->

    <link rel="stylesheet"

          href="{{asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">

    <link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">

    <link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

    <!-- iCheck -->

    <link rel="stylesheet" href="{{asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

    <!-- JQVMap -->

    <link rel="stylesheet" href="{{asset('assets/plugins/jqvmap/jqvmap.min.css')}}">

    <!-- Theme style -->

    <link rel="stylesheet" href="{{asset('assets/dist/css/adminlte.css')}}">

    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">

    <!-- overlayScrollbars -->

    <link rel="stylesheet" href="{{asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">

    <!-- Daterange picker -->

    <link rel="stylesheet" href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}">

    <!-- summernote -->

    <link rel="stylesheet" href="{{asset('assets/plugins/summernote/summernote-bs4.css')}}">



    <!-- custom -->

    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/font-awesome.min.css')}}">
    <!-- DataTables -->

    <link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">

    <link rel="stylesheet" href="{{asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">

    <!-- Google Font: Source Sans Pro -->

    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- <link rel="stylesheet" href="{{asset('assets/css/styles.css')}}"> -->

    <link rel="shortcut icon" href="{{asset('assets/images/admin/favicon.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
    
 

    <!-- jQuery -->

<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>

<!-- jQuery UI 1.11.4 -->

<script src="{{asset('assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<link rel="stylesheet" type="text/css" href="{{ asset('/front') }}/assets/css/summernote.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/front') }}/assets/css/summernote-bs3.min.css">
    <style>
        .dropdown-toggle::after{
            content:none;
            display: none;
        }
        a[data-value="h1"],button[data-event="showVideoDialog"]{
            display: none !important;
        }
    </style>


<style>

div.dataTables_wrapper div.dataTables_processing {

    position: absolute;

    top: 20%;

    left: 50%;

    width: 200px;

    margin-left: -100px;

    margin-top: -26px;

    text-align: center;

    padding: 1em 0;

    color: #230f94;

}

</style>



</head>

<!-- <body class="hold-transition sidebar-mini layout-fixed text-sm"> -->

<body class="hold-transition layout-top-nav">

<div class="wrapper">

    <!-- Navbar -->

    <nav class="main-header navbar navbar-expand navbar-white">

        <div class="container">

          <div class="navbar-header">

                      <a href="{{route('admin.dashboard')}}" class="navbar-brand">

                          <img src="{{asset('assets/dist/img/logo.svg')}}" alt="User Logo"

                              class="brand-image" height="150" width="150">

                          <!--<span class="brand-text font-weight-light">Coworking</span>-->

                      </a>

                      <!-- Left navbar links -->
                      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                  <!--<span class="navbar-toggler-icon"></span>-->
                  <i class="fa fa-bars"></i>
                </button>
          </div>
          
            <!-- top nav -->

            <div class="collapse navbar-collapse" id="navbarCollapse">

            <!-- Left navbar links -->

            <ul class="navbar-nav">

              <li class="nav-item">

                    <a href="{{route('admin.order-management.coupon-list' )}}" class="nav-link @if(Route::currentRouteName()=='admin.order-management.coupon-list')

                    ){{'active'}}@endif">Coupon List</a>

              </li>

            </ul>
            

          </div>

          <!--top nav end-->
            <!-- Right navbar links -->

            <div class="navbar-custom-menu">

              <ul class="nav navbar-nav">

                <!-- User Account Menu -->

                <li class="dropdown user user-menu">

                  <!-- Menu Toggle Button -->

                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                    <span class="sm_user"><i class="fa fa-user-o" aria-hidden="true"></i></span>

                    <!-- hidden-xs hides the username on small devices so only the image appears. -->

                    <span class="hidden-xs">{{\Auth::user()->first_name}} {{\Auth::user()->last_name}}</span>

                  </a>

                  <ul class="dropdown-menu">

                    <!-- The user image in the menu -->

                    <li class="user-header">

                      <p>

                        

                        <small>Member Since {{date('M Y', strtotime(\Auth::user()->created_at))}}</small>

                      </p>

                    </li>


                    <!-- Menu Body -->

                    

                      <li class="user-body">

                        <div class="row">

                          <div class="col-md-12 text-center">

                            <a href="{{route('admin.changePassword')}}">Modifier le mot de passe</a>

                          </div>

                        </div>

                        <!-- /.row -->

                      </li>

                    

                    <!-- Menu Footer-->

                    <li class="user-footer">

                      

                      <div >

                        <a href="{{route('admin.logout')}}" class="btn btn-default btn-flat red_button" title="Sign out">
                        <img src="{{ asset('/assets') }}/images/sign-out.png" alt="">
                        </a>

                      </div>

                    </li>

                  </ul>

                </li>

              </ul>

            </div>


        </div>



    </nav>



