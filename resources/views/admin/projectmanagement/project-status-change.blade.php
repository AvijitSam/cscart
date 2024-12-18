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
               <li class="breadcrumb-item active">Changer de statut</li>
            </ol>
         </div>
      </div>
   </div>
   <!-- /.container-fluid -->
</section>
<section class="content">
   <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="row">
         <div class="col-lg-12">
            <div class="card card-primary">
               <div class="card-header">
                  <h3 class="card-title">Changer de statut</h3>
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
                           <div  class="col-sm-8">{{$thisProject->recomended_amount}} €</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Statut :</label>
                           <div  class="col-sm-8">{{$proj_status}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Abondement :</label>
                           <div  class="col-sm-8">{!!$double_status!!}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Montant de l'abondement :</label>
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
                  <form action="{{route('admin.project-management.project-status-change-save')}}" method="POST"  id="status_change">
                     {{ csrf_field() }}
                     <input type="hidden" name="project_id" value="{{$projectId}}">
                     <div class="row">
                        <div class="col-sm-2">
                           <div  class="row">
                              <label class="col-sm-12">Statut :</label>
                           </div>
                        </div>
                        <div class="col-sm-7">
                           <div  class="row">
                              <select name="project_status" class="form-control" required>
                              @foreach($statuses as $status=>$statusval)
                              <option value="{{$status}}" @if($status==$thisProject['project_status']) selected @endif)>{{$statusval}}</option>
                              @endforeach
                              </select>
                           </div>
                        </div>
                        
                        <!-- </div> -->
                        <!-- </div> -->
                     </div>
                     <hr style="margin-top:3rem;margin-bottom:3rem;">
                      <div class="form-group row">
                         <div class="col-md-10">
                            <h6 class="text-secondary"><b>Abondement ?</b></h6>
                         </div>
                      </div>
                      <div class="form-group row">
                         <label for="Title" class="col-sm-2 col-form-label">Abondement Statut</label>
                         <div class="col-md-10">
                            <select name="is_double" class="form-control" required>
                               <option value="1"  @if($thisProject['is_double']=='1') selected @endif) >oui</option>
                               <option value="0"  @if($thisProject['is_double']=='0') selected @endif) >non</option>   
                            </select>
                         </div>
                      </div>
                      <div class="form-group row">
                         <label for="Title" class="col-sm-2 col-form-label">Montant de l'abondement</label>
                         <div class="col-md-10">
                            <input type="number" name="double_amount_limit" value="{{$thisProject['double_amount_limit']}}" class="form-control" required min="0" max="{{$maxDouble}}">
                            <div id="double_amount_limit_id" class="validation_msg"></div>
                         </div>
                      </div>
                      <div class="form-group row">
                      <div class="col-sm-3">
                           <div  class="row">
                              <a class="btn btn-primary back_new" href="{{route('admin.project-management.project-list')}}" id="retour_id">Retour</a>
                              <button type="submit" class="btn btn-success submit_new" id="first_button">Mettre à jour</button>

                              <span class="btn btn-success submit_new" id="second_button" style="display: none;">Veuillez patienter...</span>
                           </div>
                        </div>
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

@push('custom-scripts')
<!-- Sweet alert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{asset('assets/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="{{ asset('/front') }}/assets/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="{{ asset('/front') }}/assets/js/bootstrap-datepicker.js"></script>
<script>
   $('#status_change').submit(function()
   {
      $('#first_button').hide();
      $('#retour_id').hide();
      $('#second_button').show();
   });
</script>
@endpush