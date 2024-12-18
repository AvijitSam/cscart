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
               <li class="breadcrumb-item active">Détail de la project</li>
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
                  <h3 class="card-title">{{$panel_title}}</h3>
               </div>
               <!-- /.card-header -->
               <div class="card-body">
                  <!-- <div class="row">
                     <div class="col-md-10"> -->
                  <div class="row">
                     <div class="col-sm-4">
                        @if($thisProject->associate_logo == NULL)
                        <image src="{{ asset('/admin/images/no-image-found.jpg') }}" height="200" width="200"/>
                        @else
                        <image src="{{ asset('/upload/logo') }}/{{ $thisProject->associate_logo }}" height="200" width="200" style="border-radius: 100px;"/>
                        @endif
                     </div>
                     @php /**/ @endphp
                     <div class="col-sm-8">
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
                           <label class="col-sm-4">Description détaillée du projet :</label>
                           <div  class="col-sm-8">{!!$thisProject->description!!}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Domaine du projet :</label>
                           <div  class="col-sm-8">{{$domain_name}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Méthode de fin de campagne :</label>
                           <div  class="col-sm-8">{{$thisProject->campain_end_stage}}</div>
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
                           <label class="col-sm-4">Montant recommandé :</label>
                           <div  class="col-sm-8">
                              @if($thisProject->recomended_amount!=NULL or $thisProject->recomended_amount!='')
                                 {{$thisProject->recomended_amount}}  
                              @else
                                 0
                              @endif
                               €</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Montants prédéfinis :</label>
                           <div  class="col-sm-8">{{$thisProject->predefine_amount}}</div>
                        </div>
                     </div>
                     <div class="col-sm-12" style="margin-top: 15px; border-color: lightgrey; border-width: 1px;">
                        <div  class="row">
                           <label class="col-sm-4">Statut :</label>
                           <div  class="col-sm-8">{{$proj_status}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Abondement  :</label>
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
                        <div  class="row">
                           <label class="col-sm-4">A propos de l’association :</label>
                           <div  class="col-sm-8">{{$thisProject->associate_purpose}}</div>
                        </div>
                     </div>
                     <div class="col-sm-12" style="margin-top: 15px; border-color: lightgrey; border-width: 1px;">
                        <div  class="row">
                           <label class="col-sm-4">Vidéo :</label>
                           <div  class="col-sm-8">{{$thisProject->video}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Image de couverture :</label>
                           <div  class="col-sm-8">
                              @if($thisProject->cover_image!='' or $thisProject->cover_image != NULL)
                                 <image style="border: solid 1px grey; margin:5px" src="{{ asset('/upload/cover') }}/{{ $thisProject->cover_image }}" height="200" width="400"/>
                              @endif
                           </div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Galerie d’images :</label>
                           @foreach($galleries as $gallery)
                           <div  class="col-sm-2">
                              <image style="border: solid 1px grey; margin:5px" src="{{ asset('/upload/gallery') }}/{{ $gallery->gal_image }}" height="150" width="150"/>
                           </div>
                           @endforeach
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Type de campagne :</label>
                           <div  class="col-sm-8">{{$thisProject->number_of_goal}}</div>
                        </div>
                        @if($thisProject->number_of_goal == '1')
                        <div  class="row">
                           <label class="col-sm-4">Montant de l’objectif :</label>
                           <div  class="col-sm-8">
                              @if($thisProject->single_goal_amount!=NULL or $thisProject->single_goal_amount!='')
                                 {{$thisProject->single_goal_amount}}  
                              @else
                                 0
                              @endif
                               €</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">But du financement :</label>
                           <div  class="col-sm-8"> {{$thisProject->single_goal}}</div>
                        </div>
                        @else
                        <div  class="row">
                           <label class="col-sm-4">Montant du 1er palier :</label>
                           <div  class="col-sm-8">
                              @if($thisProject->first_goal_amount!=NULL or $thisProject->first_goal_amount!='')
                                 {{$thisProject->first_goal_amount}}  
                              @else
                                 0
                              @endif

                           €</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">But du 1er palier :</label>
                           <div  class="col-sm-8"> {{$thisProject->first_goal}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Montant du 2e palier :</label>
                           <div  class="col-sm-8">
                              @if($thisProject->second_goal_amount!=NULL or $thisProject->second_goal_amount!='')
                                 {{$thisProject->second_goal_amount}}  
                              @else
                                 0
                              @endif
                               €</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">But du 2e palier :</label>
                           <div  class="col-sm-8"> {{$thisProject->second_goal}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Montant du 3e palier :</label>
                           <div  class="col-sm-8">
                              @if($thisProject->third_goal_amount!=NULL or $thisProject->third_goal_amount!='')
                                 {{$thisProject->third_goal_amount}}  
                              @else
                                 0
                              @endif
                               €</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">But du 3e palier :</label>
                           <div  class="col-sm-8"> {{$thisProject->third_goal}}</div>
                        </div>
                        @endif
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