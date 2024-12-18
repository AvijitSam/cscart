@extends('admin.layouts.after-login-layout')





@section('unique-content')



<div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1>Gestion de la clientèle</h1>

          </div>

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>

              <li class="breadcrumb-item"><a href="{{route('admin.user-management.site.user.customer.list')}}">Liste de clients</a></li>

              <li class="breadcrumb-item active">Détails du client</li>

            </ol>

          </div>

        </div>

      </div><!-- /.container-fluid -->

    </section>

    <section class="content">

        <div class="container-fluid">

            <!-- SELECT2 EXAMPLE -->

            <div class="row">

                <div class="col-lg-12">

                        <div class="card card-primary">

                                    <div class="card-header">

                                        <h3 class="card-title">{{$panel_title}}</h3>

                                    </div>

                                <!-- /.card-header -->

                            <div class="card-body">



                                <!-- <div class="row">

                                    <div class="col-md-10"> -->

                                        <div class="row">

                                            @php /*<div class="col-sm-4">

                                            @if($getDetail->profile_picture == NULL)

                                                <image src="{{ asset('/admin/images/no-image-found.jpg') }}" height="200" width="200"/>

                                            @else

                                                <image src="{{ asset('/admin/upload/profile/thumbnail') }}/{{ $getDetail->profile_picture }}" height="200" width="200"/>

                                            @endif

                                            

                                            </div>*/ @endphp

                                            <div class="col-sm-12">

                                                <div  class="row">

                                                    <label class="col-sm-4">Prénom :</label>

                                                    <div  class="col-sm-8">{{$getDetail->first_name}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Nom de famille :</label>

                                                    <div  class="col-sm-8">{{$getDetail->last_name}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">E-mail :</label>

                                                    <div  class="col-sm-8">{{$getDetail->email}}</div>

                                                </div>
                                                

                                                

                                                

                                                <div  class="row">

                                                    <label class="col-sm-4">Adresse :</label>

                                                    <div  class="col-sm-8">{{$getDetail->address}}</div>

                                                </div>
                                                <div  class="row">

                                                    <label class="col-sm-4">Code Postal :</label>

                                                    <div  class="col-sm-8">{{$getDetail->postal_code}}</div>

                                                </div>
                                                <div  class="row">

                                                    <label class="col-sm-4">Ville :</label>

                                                    <div  class="col-sm-8">{{$getDetail->village}}</div>

                                                </div>

                                                

                                                <div  class="row">

                                                    <label class="col-sm-4">Statut :</label>

                                                    <div  class="col-sm-8">{!!$user_status!!}</div>

                                                </div>

                                                
                                                
                                                

                                            </div>

                                        </div>

                                    

                    

                                    <!-- </div> -->

                                <!-- </div> -->

                            </div>

                        </div>  

                        

                                  

                    </div>                    

                        

                </div>

            </div>

    

            

    </section>



    

    



    

    

  </div>

  @endsection

      



         

         

              

             



                



      