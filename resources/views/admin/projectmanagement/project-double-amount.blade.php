@extends('admin.layouts.after-login-layout')





@section('unique-content')



<div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1>Projects</h1>

          </div>

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>

              <li class="breadcrumb-item"><a href="{{route('admin.project-management.project-list')}}">Projects</a></li>

              <li class="breadcrumb-item active">Double Amount</li>

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

                                        <h3 class="card-title">Double Amount</h3>

                                    </div>

                                <!-- /.card-header -->

                            <div class="card-body">

                                <div class="row">

                                            

                                            <div class="col-sm-6">

                                                <div  class="row">

                                                    <label class="col-sm-4">Titre du projet :</label>

                                                    <div  class="col-sm-8">{{$thisProject->project_title}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Nom :</label>

                                                    <div  class="col-sm-8">{{$fullname}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Emplacement du projet :</label>

                                                    <div  class="col-sm-8">{{$thisProject->location}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Description courte du projet :</label>

                                                    <div  class="col-sm-8">{{$thisProject->short_description}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Domaine du projet :</label>

                                                    <div  class="col-sm-8">{{$domain_name}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Date de début :</label>

                                                    <div  class="col-sm-8">{{$thisProject->start_date}}</div>

                                                </div>


                                                <div  class="row">

                                                    <label class="col-sm-4">Date de fin :</label>

                                                    <div  class="col-sm-8">{{$thisProject->end_date}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Type de campagne :</label>

                                                    <div  class="col-sm-8">{{$thisProject->number_of_goal}}</div>

                                                </div>

                                            </div>

                                            <div class="col-sm-6">
                                                <div  class="row">

                                                    <label class="col-sm-4">Montant recommandé :</label>

                                                    <div  class="col-sm-8">€ {{$thisProject->recomended_amount}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Project Status :</label>

                                                    <div  class="col-sm-8">{{$proj_status}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Double Amount :</label>

                                                    <div  class="col-sm-8">{!!$double_status!!}</div>

                                                </div>

                                                 <div  class="row">

                                                    <label class="col-sm-4">Donation Amount :</label>

                                                    <div  class="col-sm-8">{{$sum}}</div>

                                                </div>

                                                
                                                <div  class="row">

                                                    <label class="col-sm-4">Nom de l’association :</label>

                                                    <div  class="col-sm-8">{{$thisProject->associate_name}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Adresse de l’association :</label>

                                                    <div  class="col-sm-8">{{$thisProject->associate_address}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Téléphone :</label>

                                                    <div  class="col-sm-8">{{$thisProject->associate_phone}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Email :</label>

                                                    <div  class="col-sm-8">{{$thisProject->associate_email}}</div>

                                                </div>
                                                

                                            </div>
                                </div>
                            </div>

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

                               


                                <!-- <div class="row">

                                    <div class="col-md-10"> -->

                                        @if($thisProject->thisProject != 'close')

                                        <form action="{{route('admin.project-management.save-double-amount')}}" method="POST"  id="status_change">

                                        {{ csrf_field() }}

                                        <input type="hidden" name="project_id" value="{{$projectId}}">

                                        <div class="row">

                                            

                                            <div class="col-sm-1">

                                                <div  class="row">

                                                    <label class="col-sm-12">Statut :</label>

                                                    

                                                </div>
                                            </div>

                                            <div class="col-sm-2">

                                                <div  class="row">

                                                    <select name="is_double" class="form-control" required>
                                                        
                                                            <option value="1" @if($is_double=='yes') selected @endif)>Yes</option>

                                                            <option value="0" @if($is_double=='no') selected @endif)>No</option>
                                                        
                                                    </select>

                                                    

                                                </div>

                                                
                                            </div>


                                            <div class="col-sm-2">

                                                <div  class="row">

                                                    <label class="col-sm-12">Double Amount(€) :</label>

                                                    

                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                        
                                                <div  class="row">

                                                   
                                                     <input type="text" name="double_amount_limit" value="{{$double_amount}}" class="form-control" required>
                                                    

                                                </div>

                                                
                                            </div>


                                            <div class="col-sm-3">

                                                <div  class="row">

                                                        <a class="btn btn-primary back_new" href="{{route('admin.project-management.project-list')}}">Retour</a>

                                                        <button id="" type="submit" class="btn btn-success submit_new">Modifier</button>

                                                </div>

                                            </div>

                                    

                    

                                    <!-- </div> -->

                                <!-- </div> -->

                            </div>

                            </form>

                            @endif
                                    

                        </div>  

                        

                                  

                    </div>                    

                        

                </div>

            </div>

    

            

    </section>



    

    



    

    

  </div>

  @endsection