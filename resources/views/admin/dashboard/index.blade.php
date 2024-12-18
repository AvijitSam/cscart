

  <!-- /.navbar -->

@extends('admin.layouts.after-login-layout')





@section('unique-content')





  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <div class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1 class="m-0 text-dark">Bienvenue sur l'administration</h1>

          </div><!-- /.col -->

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item active">Dashboard</li>

            </ol>

          </div><!-- /.col -->

        </div><!-- /.row -->

      </div><!-- /.container-fluid -->

    </div>

    <!-- /.content-header -->



    <!-- Main content -->

    <section class="content">
      <div class="container-fluid">
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
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-one">
              <div class="inner">
                <h3>
                {{$total_user}}
                </h3>

                <p><strong>NB UTILISATEURS</strong></p>
              </div>
              <div class="icon">
                <i class="fas fa-user-plus"></i>
              </div>
              
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-two">
              <div class="inner">
                <h3>
                  {{$active_user}}
                </h3>

                <p><strong> UTILISATEURS ACTIFS </strong> </p>
              </div>
              <div class="icon">
                <i class="fas fa-user-check"></i>
              </div>
              
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-three">
              <div class="inner">
                <h3>
                {{$inactive_user}}
                </h3>

                <p><strong>INACTIFS</strong></p>
              </div>
              <div class="icon">
                <i class="fas fa-user-slash"></i>
              </div>
              
            </div>
          </div>
    
        </div>
        <!-- /.row -->

        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-one">
              <div class="inner">
                <h3>
                {{$total_project}}
                </h3>

                <p><strong>NB DE PROJETS</strong></p>
              </div>
              <div class="icon">
                <i class="far fa-folder"></i>
              </div>
              
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-two">
              <div class="inner">
                <h3>
                  {{$total_pending_project}}
                </h3>

                <p><strong> PROJET EN ATTENTE </strong> </p>
              </div>
              <div class="icon">
                <i class="fas fa-folder-open"></i>
              </div>
              
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-three">
              <div class="inner">
                <h3>
                {{$total_active_project}}
                </h3>

                <p><strong>PROJETS ACTIFS </strong></p>
              </div>
              <div class="icon">
                <i class="far fa-folder-open"></i>
              </div>
              
            </div>
          </div>
    
        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
          <!-- Left col -->

          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->

          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

  </div>

  <!-- /.content-wrapper -->



@endsection

