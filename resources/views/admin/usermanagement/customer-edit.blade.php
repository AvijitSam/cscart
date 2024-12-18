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

              <li class="breadcrumb-item active">Client Modifier</li>

            </ol>

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



                           

                            <form action="{{route('admin.user-management.user-customer-edit-save')}}" method="POST"  id="user_edit">

                                    {{ csrf_field() }}

                                    <input type="hidden" name="user_id" value="{{$getDetail->id}}">

                                <div class="row">

                                    <div class="col-md-10">

                                    

                                        <div class="form-group row">

                                            <label for="Title" class="col-sm-2 col-form-label">Prénom : <span class="error">*</span></label>

                                            <div class="col-sm-10">

                                                <input type="text" name="first_name" id="first_name"  value="{{$getDetail->first_name}}" class="form-control" placeholder="Prénom" title="Prénom" required>

                                                <div id="msg_first_name"></div>

                                            </div>

                                        </div>



                                        <div class="form-group row">

                                            <label for="Title" class="col-sm-2 col-form-label">Nom de famille : <span class="error">*</span></label>

                                            <div class="col-sm-10">

                                                <input type="text" name="last_name" id="last_name"  value="{{$getDetail->last_name}}" class="form-control" placeholder="Nom de famille" title="Nom de famille" required>

                                                <div id="msg_last_name"></div>

                                            </div>



                                        </div>

                                        

                                        <div class="form-group row">

                                            <label for="Title" class="col-sm-2 col-form-label">E-mail : <span class="error">*</span></label>

                                            <div class="col-sm-10">

                                                <input type="text" name="email" id="email" value="{{$getDetail->email}}" class="form-control text-muted" style="background-color : #d1d1d1 !important;" placeholder="E-mail" title="E-mail" disabled>

                                                <div id="msg_email"></div>

                                            </div>

                                        </div>

                                        <div class="form-group row">

                                            <label for="Title" class="col-sm-2 col-form-label">Téléphone : </label>

                                            <div class="col-sm-10">

                                                <input type="text" name="phone" id="phone" value="{{$getDetail->phone}}" class="form-control" placeholder="Téléphone" title="Téléphone" >

                                                <div id="msg_phone"></div>

                                            </div>

                                        </div>

                                        <div class="form-group row">

                                                    <label for="Title" class="col-sm-2 col-form-label">Adresse : </label>

                                                        <div class="col-sm-10">

                                                        <input type="text" name="address" id="address" class="form-control" placeholder="Adresse" title="Adresse" value="{{$getDetail->address}}">
                                                        <div id="msg_address"></div>
                                                        </div>

                                                        

                                                </div>
                                                <div class="form-group row">

                                                    <label for="Title" class="col-sm-2 col-form-label">Code Postal : </label>

                                                        <div class="col-sm-10">

                                                        <input type="text" name="postal_code" id="postal_code" class="form-control" placeholder="Code Postal" title="Code Postal" value="{{$getDetail->postal_code}}">
                                                        <div id="msg_postal_code"></div>
                                                        </div>

                                                        

                                                </div>
                                                <div class="form-group row">

                                                    <label for="Title" class="col-sm-2 col-form-label">Ville : </label>

                                                        <div class="col-sm-10">

                                                        <input type="text" name="village" id="village" class="form-control" placeholder="Ville" title="Ville" value="{{$getDetail->village}}">
                                                        <div id="msg_address"></div>
                                                        </div>

                                                        

                                                </div>
                                                <div class="form-group row">

                                                    <label for="Title" class="col-sm-2 col-form-label">TVA intracommunautaire : </label>

                                                        <div class="col-sm-10">

                                                        <input type="text" name="tva" id="tva" class="form-control" placeholder="TVA intracommunautaire" title="TVA intracommunautaire" value="{{$getDetail->tva}}">
                                                        <div id="msg_tva"></div>
                                                        </div>

                                                        

                                                </div>
                                                
                                                <div class="form-group row">

                                                    <label for="Title" class="col-sm-2 col-form-label">SIRET : </label>

                                                        <div class="col-sm-10">

                                                        <input type="text" name="siret" id="siret" class="form-control" placeholder="SIRET" title="SIRET" value="{{$getDetail->siret}}">
                                                        <div id="msg_siret"></div>
                                                        </div>

                                                        

                                                </div>





                                        <div class="form-group row">

                                            <label for="Title" class="col-sm-2 col-form-label">Statut : </label>

                                            <div class="col-sm-10">

                                            <select class="form-control select2 select2-danger" name="user_status" data-dropdown-css-class="select2-danger" style="width: 100%;" id="status">

                                                    <option value="">Choose option</option>

                                                    <option value="1" {{ $getDetail->user_status == '1' ? 'selected' : '' }}>Actif</option>

                                                    <option value="0" {{ $getDetail->user_status == '0' ? 'selected' : '' }} >Inactif</option>

                                            </select>

                                            <div id="msg_status"></div>

                                            </div>

                                        </div>

                                        <div class="card-footer">

                                                <div class="">

                                                    

                                                    <button id="" type="submit" class="btn btn-success submit_new">Mettre à jour</button>

                                                    <a class="btn btn-primary back_new" href="{{route('admin.user-management.site.user.customer.list')}}">Retour</a>

                                                </div>

                                        </div>

                                            

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


  @push('custom-scripts')

  <script>
    jQuery(document).ready(function($) {
        $('#user_edit').on('submit', function() {
        //alert('nnn');
        $("#msg_first_name").html('');
        $("#msg_last_name").html('');
        $("#msg_email").html('');
        $("#msg_phone").html('');
        $("#msg_status").html('');

        var fname = $('#first_name').val();
        var lname = $('#last_name').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var status = $('#status').val();

        flag = 0;

        

         if($.trim(fname) == '')
         {
          $('#msg_first_name').html('<small style="color:red">Obligatoire</small>');
          //alert('fname');
          flag = 1;
         }
         else
         {
          $('#msg_first_name').html('');
         }

         if($.trim(lname) == '')
         {
          $('#msg_last_name').html('<small style="color:red">Obligatoire</small>');
          flag = 1;
         }
         else
         {
          $('#msg_last_name').html('');
         }

         

         // if($.trim(phone) == '')
         // {
         //  $('#msg_phone').html('<small style="color:red">Obligatoire</small>');
         //  flag = 1;
         // }
         // else
         // {
         //  $('#msg_phone').html('');
         // }

         if($.trim(status) == '')
         {
          $('#msg_status').html('<small style="color:red">Obligatoire</small>');
          flag = 1;
         }
         else
         {
          $('#msg_status').html('');
         }

         // alert($flag);
         // return false;

         if(flag == 1)
         {
            //alert('ppp');
          return false;
         }
    });
    });



            

    </script>

    @endpush

      



         

         

              

             



                



      