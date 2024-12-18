@extends('admin.layouts.after-login-layout')
@section('unique-content')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/dropzone.css')}}">
<link rel="stylesheet" href="{{ asset('/front') }}/assets/css/datepicker.css">
<style>
   .dz-progress,.dz-size{display: none !important;}
   .form-group.sgo-error input,.form-group.sgo-error textarea,.form-group.sgo-error select,.form-group.sgo-error .dropzone,.form-group.sgo-error .select2-selection{border: 2px solid red;}
   .dropzone{
   border-color: #d6d6d6;
   border-radius: 10px;
   border: 1px solid #d6d6d6;
   border-style: dashed;
   }
   .dz-remove{
   color: red;
   font-size: 11px;
   font-weight: 700;
   vertical-align: inherit;
   position: absolute;
   top: 50px;
   z-index: 99999;
   left: 122px;
   }
   .dummy-gallery-trigger {
   color: transparent;
   }
   .form-control{border-radius: 5px;}
   .select2-search--inline{display: none;}
   .cke_toolbar_break{display: inline-block !important;
    clear: left;}
    .color-btn {
    width: 178px;
    height: 45px;
    background: #461848;
    text-align: center;
    font-size: 14px;
    font-style: normal;
    letter-spacing: 0px;
    color: #FFFFFF;
    opacity: 1;
    padding: 13px;
    border-radius: 0px;
    margin-left: 10px;
}
#crwd-img-editor .modal-footer > *{
   margin: 0 !important;
}
</style>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    
     <link href="{{ asset('/front') }}/assets/editor/imgedit-updated.css" rel="stylesheet">
    <style>.desc-group .dropdown-toggle .caret::after{content: none !important;}</style>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.1.3/cropper.css">
    <script src="https://fengyuanchen.github.io/cropperjs/js/cropper.js"></script>
        <style>
            .dropdown-toggle::after{content: none !important;}
        @media (min-width: 768px)
.crwd-editor-img-container {
    min-height: 497px;
}
.crwd-editor-img-container {
    margin-bottom: 1rem;
    max-height: 497px;
    min-height: 200px;
}
.crwd-editor-img-container, .img-preview {
    
    text-align: center;
    width: 100%;
}
.crwd-img-preview img{
    text-align: center;
    width: 100%;
}


.crwd-editor-img-container-logo .cropper-view-box,
.crwd-editor-img-container-logo .cropper-face {
 border-radius: 50%;
}
.crwd-editor-img-container-logo .crwd-img-preview img{
    border-radius: 50%;
    width: 200px !important;
}
.cover_close{display: none !important;}

    </style>
     <style>
        .dropdown-toggle::after{
            content:none;
            display: none;
        }
        a[data-value="h1"],button[data-event="showVideoDialog"]{
            display: none !important;
        }
    </style>
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Project Modify</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{route('admin.project-management.project-list')}}">Projects List</a></li>
                  <li class="breadcrumb-item active"> Project</li>
               </ol>
            </div>
            <!-- /.col -->
         </div>
         <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
   </div>
   <!-- /.content-header -->
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <!-- Small boxes (Stat box) -->
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
                     <form enctype="multipart/form-data" action="{{route('admin.project-management.project-edit-save')}}" method="POST" id="project_edit">
                        {{ csrf_field() }}
                        <input type="hidden" name="pro_id" value="{{$getDetail->id}}">
                        <div class="row">
                           <div class="col-md-10">
                              <div class="form-group row">
                                 <div class="col-md-10">
                                    <h6 class="text-secondary">1- Informations de base sur la campagne</h6>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Titre du projet : </label>
                                 <div class="col-sm-10">
                                    <input type="text" name="project_title" id="project_title" value="{{$getDetail->project_title}}" class="form-control" placeholder="Titre du projet" title="Titre du projet" required>
                                    <div id="project_title_id" class="validation_msg"></div>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Emplacement du projet : </label>
                                 <div class="col-sm-10">
                                    <input type="text" name="location" id="location" value="{{$getDetail->location}}" class="form-control" placeholder="Emplacement du projet" title="Emplacement du projet" required="">
                                    <div id="location_id" class="validation_msg"></div>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Description courte du projet : </label>
                                 <div class="col-sm-10">
                                    <input type="text" name="short_description" id="short_description" value="{{$getDetail->short_description}}" class="form-control" placeholder="Description courte du projet" title="Description courte du projet" required="">
                                    <div id="short_description_id" class="validation_msg"></div>
                                 </div>
                              </div>
                              <div class="form-group row summernote">
                                 <label for="Title" class="col-sm-2 col-form-label">Description détaillée du projet : </label>
                                 <div class="col-sm-10">
                                    <!-- <textarea class="ckeditor form-control" name="description" id="description" maxlength="5000">{!! $getDetail->description !!}</textarea> -->
                                    
                                    <textarea name="description" id="description" cols="30" rows="10" >{!! $getDetail->description !!}</textarea>
                                    <div id="description_id" class="validation_msg"></div>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Domaine du projet : </label>
                                 <div class="col-sm-10">
                                    <select class="js-example-tags form-control select2-hidden-accessible" name="domain_id[]" id="domain_id"  multiple>
                                    @if(count($domains) > 0)
                                    @foreach($domains as $domain)
                                    <option value="{{$domain->id}}" {{(is_array($selected_domains) && in_array($domain->id,$selected_domains)?'selected':'')}}>{{$domain->domain_name}}</option>
                                    @endforeach
                                    @endif
                                    </select>
                                    <div id="domain_id_id" class="validation_msg"></div>
                                 </div>
                              </div>
                              <hr style="margin-top:3rem;margin-bottom:3rem;">
                              <div class="form-group row">
                                 <div class="col-md-10">
                                    <h6 class="text-secondary">2- Images et médias de la campagne</h6>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Image de couverture</label>
                                 <div class="col-sm-10">
                                    <div class="col-12 preview_cover" style="display: {{(!empty($getDetail->cover_image) && strlen($getDetail->cover_image)>0?'block':'none')}};">
                                       <div class="row image-row-handler">
                                          <input type="hidden" name="cover_image_old" value="{{$getDetail->cover_image}}">
                                          <div class="crd-img-component" id="cov_img">
                                             <img src="{{url('/upload/cover')}}/{{$getDetail->cover_image}}" style="width: 120px;height: 60px;">
                                             <span class="cover_close delete_logo" style="margin: 10px;"><b><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">X</font></font></b></span>
                                          </div>
                                       </div>
                                    </div>
                                    <input type="file" name="cover_image" class="cover_image" id="cover_image" accept="image/*" data-name="cover_image">
                                    <input type="hidden" class="image_name" value="{{$getDetail->cover_image}}">
                                    <input type="hidden" name="cover_image_blob" class="cover_image_blob" id="cover_image_blob"/>
                                    <div id="cov_img_text" class="validation_msg"></div>

                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Galerie d’images</label>
                                 <div class="col-sm-10 dropzone-previews-cwd" data-name="galDropzone">
                                    <div class="form-group col-12 preview_cover image-row-handler" >
                                       <div class="dropzone dropzone-previews" id="crd-gal-dropzone"></div>
                                    </div>
                                    <input type="file" class="dummy-gallery-trigger">
                                    <input type="hidden" name="gal_image_remove" value="">
                                    <div id="gal_first_img_text" class="validation_msg"></div>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Vidéo</label>
                                 <div class="col-sm-10">
                                    <input type="url" name="video" id="video" value="{{$getDetail->video}}" class="form-control" placeholder="Vidéo" title="Vidéo" required="">
                                    <div id="video_id" class="validation_msg"></div>
                                 </div>
                              </div>
                              <hr style="margin-top:3rem;margin-bottom:3rem;">
                              <div class="form-group row">
                                 <div class="col-md-10">
                                    <h6 class="text-secondary">3- Dates et objectif de la campagne</h6>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">End of campaign method : </label>
                                 <div class="col-sm-10">
                                    <select name="campain_end_stage" id="campain_end_stage" class="form-control">
                                    <option value="Objectif cible" {{ ($getDetail->campain_end_stage=='Objectif cible'?'selected':'') }}>Objectif cible</option>
                                    <option value="Date de fin" {{ ($getDetail->campain_end_stage=='Date de fin'?'selected':'') }}>Date de fin</option>
                                    </select>
                                    <div id="campain_end_stage_id" class="validation_msg"></div>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Type de campagne : </label>
                                 <div class="col-sm-10">
                                    <select name="number_of_goal" id="number_of_goal" class="form-control">
                                    <option value="1" {{ ($getDetail->number_of_goal=='1'?'selected':'') }}>1</option>
                                    <option value="3" {{ ($getDetail->number_of_goal=='3'?'selected':'') }}>3</option>
                                    </select>
                                    <div id="campain_end_stage_id" class="validation_msg"></div>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Date de début</label>
                                 <div class="col-sm-10">
                                    <input type="text" name="start_date" id="datepicker1" value="{{$getDetail->start_date}}" class="form-control date_input" placeholder="Date de début" title="Date de début" readonly>
                                    <div id="start_date_id" class="validation_msg"></div>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Date de fin</label>
                                 <div class="col-sm-10">
                                    <input type="text" name="end_date" id="datepicker2" value="{{$getDetail->end_date}}" class="form-control date_input" placeholder="Date de fin" title="Date de fin" readonly>
                                    <div id="end_date_id" class="validation_msg"></div>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Montant recommandé</label>
                                 <div class="col-sm-10">
                                    <input name="recomended_amount" id="recomended_amount" value="{{$getDetail->recomended_amount}}" class="form-control" placeholder="Montant recommandé" title="Montant recommandé" maxlength="50" type="number" pattern="\d*" onkeypress="return isNumber(event)">
                                    <div id="recomended_amount_id" class="validation_msg"></div>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Montants prédéfinis</label>
                                 <div class="col-sm-10">
                                    <input name="predefine_amount" id="predefine_amount" value="{{$getDetail->predefine_amount}}" class="form-control" placeholder="Montant prédéfinis" title="Montant prédéfinis" type="text" >
                                    <span class="small-text">Vous permet de placer les montants de votre choix dans la boite de dons par clic, les montants doivent être séparés par une virgule (,). Exemple : 10, 20, 30, 40</span>
                                    <div id="predefine_amount_id" class="validation_msg"></div>
                                 </div>
                              </div>
                              <hr style="margin-top:3rem;margin-bottom:3rem;">
                              <div class="form-group row">
                                 <div class="col-md-10">
                                    <h6 class="text-secondary">4- Paliers de la campagne</h6>
                                 </div>
                              </div>
                              <div id="campaign_one" style="display: {{ ($getDetail->number_of_goal==1?'block':'none') }};">
                                 <div class="form-group row {{ ($getDetail->number_of_goal==1?'':'exclude') }}">
                                    <label for="Title" class="col-sm-2 col-form-label">Montant de l’objectif</label>
                                    <div class="col-sm-10">
                                       <input type="text" name="single_goal_amount" value="{{$getDetail->single_goal_amount}}" onkeypress="return isNumber(event)" id="single_goal_amount" maxlength="50" class="form-control" title="Montant de l’objectif" placeholder="Montant de l’objectif">
                                       <div id="single_goal_amount_id" class="validation_msg"></div>
                                    </div>
                                 </div>
                                 <div class="form-group row {{ ($getDetail->number_of_goal==1?'':'exclude') }}">
                                    <label for="Title" class="col-sm-2 col-form-label">But du financement</label>
                                    <div class="col-sm-10">
                                       <textarea cols="40" rows="10" name="single_goal" id="single_goal" maxlength="300" class="form-control" placeholder="But du financement">{{$getDetail->single_goal}}</textarea>
                                       <div id="single_goal_id" class="validation_msg"></div>
                                    </div>
                                 </div>
                              </div>
                              <div id="campaign_three" style="display: {{ ($getDetail->number_of_goal==3?'block':'none') }};">
                                 <div class="form-group row {{ ($getDetail->number_of_goal==3?'':'exclude') }}">
                                    <label for="Title" class="col-sm-2 col-form-label">Montant du 1er palier</label>
                                    <div class="col-sm-10">
                                       <input type="text" name="first_goal_amount" value="{{$getDetail->first_goal_amount}}" onkeypress="return isNumber(event)" id="first_goal_amount" maxlength="50" class="form-control">
                                       <div id="first_goal_amount_id" class="validation_msg"></div>
                                    </div>
                                 </div>
                                 <div class="form-group row {{ ($getDetail->number_of_goal==3?'':'exclude') }}">
                                    <label for="Title" class="col-sm-2 col-form-label">But du 1er palier</label>
                                    <div class="col-sm-10">
                                       <textarea cols="40" rows="10" name="first_goal" id="first_goal" maxlength="300" class="form-control">{{$getDetail->first_goal}}</textarea>
                                       <div id="first_goal_id" class="validation_msg"></div>
                                    </div>
                                 </div>
                                 <div class="form-group row {{ ($getDetail->number_of_goal==3?'':'exclude') }}">
                                    <label for="Title" class="col-sm-2 col-form-label">Montant du 2e palier</label>
                                    <div class="col-sm-10">
                                       <input type="text" name="second_goal_amount" value="{{$getDetail->second_goal_amount}}" onkeypress="return isNumber(event)" id="second_goal_amount" maxlength="50" class="form-control">
                                       <div id="second_goal_amount_id" class="validation_msg"></div>
                                    </div>
                                 </div>
                                 <div class="form-group row {{ ($getDetail->number_of_goal==3?'':'exclude') }}">
                                    <label for="Title" class="col-sm-2 col-form-label">But du 2e palier</label>
                                    <div class="col-sm-10">
                                       <textarea cols="40" rows="10" name="second_goal" id="second_goal" maxlength="300" class="form-control">{{$getDetail->second_goal}}</textarea>
                                       <div id="second_goal_id" class="validation_msg"></div>
                                    </div>
                                 </div>
                                 <div class="form-group row {{ ($getDetail->number_of_goal==3?'':'exclude') }}">
                                    <label for="Title" class="col-sm-2 col-form-label">Montant du 3e palier</label>
                                    <div class="col-sm-10">
                                       <input type="text" name="third_goal_amount" value="{{$getDetail->third_goal_amount}}" onkeypress="return isNumber(event)" id="third_goal_amount" maxlength="50" class="form-control">
                                       <div id="third_goal_amount_id" class="validation_msg"></div>
                                    </div>
                                 </div>
                                 <div class="form-group row {{ ($getDetail->number_of_goal==3?'':'exclude') }}">
                                    <label for="Title" class="col-sm-2 col-form-label">But du 3e palier</label>
                                    <div class="col-sm-10">
                                       <textarea cols="40" rows="10" name="third_goal" id="third_goal" maxlength="300" class="form-control">{{$getDetail->third_goal}}</textarea>
                                       <div id="third_goal_id" class="validation_msg"></div>
                                    </div>
                                 </div>
                              </div>
                              <hr style="margin-top:3rem;margin-bottom:3rem;">
                              <div class="form-group row">
                                 <div class="col-md-10">
                                    <h6 class="text-secondary">5- Coordonnées de l’association porteuse du projet</h6>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Nom de l’association</label>
                                 <div class="col-sm-10">
                                    <input type="text" name="associate_name" id="associate_name" value="{{$getDetail->associate_name}}" maxlength="150" class="form-control">
                                    <div id="associate_name_id"></div>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Adresse de l’association</label>
                                 <div class="col-sm-10">
                                    <input type="text" name="associate_address" id="associate_address" value="{{$getDetail->associate_address}}" class="form-control">
                                    <div id="associate_address_id" class="validation_msg"></div>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Téléphone : </label>
                                 <div class="col-sm-10">
                                    <input type="tel" name="associate_phone" id="associate_phone" value="{{$getDetail->associate_phone}}" class="form-control" placeholder="Téléphone" title="Téléphone">
                                    <div id="associate_phone_id" class="validation_msg"></div>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">E-mail :</label>
                                 <div class="col-sm-10">
                                    <input type="email" name="associate_email" id="associate_email" value="{{$getDetail->associate_email}}" class="form-control" placeholder="E-mail" title="E-mail">
                                    <div id="associate_email_id" class="validation_msg"></div>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Logo de l’association</label>
                                 <div class="col-sm-10">
                                    <div class="row image-row-handler" style="display: {{(strlen($getDetail->associate_logo)>0?'block':'none')}};">
                                       <div class="crd-img-component" id="logo_img">
                                          <img src="{{url('/upload/logo')}}/{{$getDetail->associate_logo}}" style="width: 60px;height: 60px;border-radius: 100px;">
                                          <span class="cover_close delete_logo" style="margin: 10px;"><b><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">X</font></font></b></span>
                                          <span class="validation_msg" id="associate_logo_id"></span>
                                       </div>
                                    </div>
                                    <input type="file" id="associate_logo" name="associate_logo" accept="image/*" class="logo_image form-control" data-name="associate_logo">
                                    <input type="hidden" name="associate_logo_blob" class="logo_image_blob" id="logo_image_blob">
                                    <input type="hidden" class="image_name" value="{{$getDetail->associate_logo}}">
                                    <input type="hidden" name="associate_logo_old" value="{{$getDetail->associate_logo}}" class="file-input">
                                    <div class="associate_logo_id validation_msg" ></div>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">A propos de l’association</label>
                                 <div class="col-sm-10">
                                    <textarea rows="8" name="associate_purpose" id="associate_purpose" maxlength="500" class="form-control">{{$getDetail->associate_purpose}}</textarea>
                                    <div id="associate_purpose_id" class="validation_msg"></div>
                                 </div>
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
                                       <option value="1"  @if($getDetail->is_double=='1') selected @endif) >oui</option>
                                       <option value="0"  @if($getDetail->is_double=='0') selected @endif) >non</option>   
                                    </select>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Montant de l'abondement</label>
                                 <div class="col-md-10">
                                    <input type="number" name="double_amount_limit" value="{{$getDetail->double_amount_limit}}" class="form-control" required min="0" max="{{$maxDouble}}">
                                    <div id="double_amount_limit_id" class="validation_msg"></div>
                                 </div>
                              </div>
                              <div class="card-footer">
                                 <div class="">
                                    <button  class="btn btn-success submit_new">Mettre à jour</button>
                                    <a class="btn btn-primary back_new" href="{{route('admin.project-management.project-list')}}">Retour</a>
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
      <!-- /.container-fluid -->
   </section>
   <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<div>
<div id="crwd-img-editor" class="crwd-modal">

  <!-- Modal content -->
  <div class="crwd-modal-content editor-modal">
    <div class="modal-header">
        <h3 class="modal-title">Recadrer votre image</h3>
    </div>
    <div class="modal-body preview-off">
        <div class="editor-wrapper">
            <div class="editor-container">
                <div class="editor">
                    <div class="resize-container">
                        <span class="resize-handle resize-handle-nw"></span>
                        <span class="resize-handle resize-handle-ne"></span>
                        <img class="resize-image" src="" alt="">
                        <span class="resize-handle resize-handle-se"></span>
                        <span class="resize-handle resize-handle-sw"></span>
                    </div>
                    <div class="overlay">
                        <div class="overlay-inner"></div>
                    </div>
                    <div class="overlay overlay-preview">
                        <div class="overlay-inner"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="crwd-editor-img-container">
            <img src="" class="crwd-banner-img-edit">
            <div style="display:none;" class="crwd-img-preview">
            <img src="" >
            </div>
        </div>
    </div>
        <div class="modal-footer">
            <div class="upload row">
                
                    <span class="crwd-instruction">
                        Astuce : vous pouvez zoomer / dézoomer dans l’image en utilisant la molette de votre souris, ou avec 2 doigts sur mobile ou pad.
                    </span>
                
                    <div class="col-md-12">
                        <div class="upload-button">
                            <button class="btn color-btn crwd-replace">Charger une autre image <span class="upload-icon"><i class="fa fa-upload"></i></span></button>
                        </div>
                        <div class="upload-button">
                            <div class="edit-button">
                                <span class="crwd-img-editor-errors"></span>
                                <button class="btn color-btn preview-crop">Valider le résultat</button>
                                <button type="submit" class="js-crop btn color-btn" data-dismiss="modal">Enregistrer</button>
                                <button class="btn color-btn crwd-edit-cancel" data-dismiss="modal">Annuler</button>
                                <a href="#" class="downimg"></a>
                            </div>
                            
                            </div>
                    </div>
                    
                    
                
            </div>
        </div>
  </div>

</div>
@endsection
@push('custom-scripts')
<!-- DataTables -->

<script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<!-- Sweet alert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{asset('assets/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/dropzone.js')}}"></script>
<!-- <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="{{ asset('/front') }}/assets/js/bootstrap-datepicker.js"></script>
<script src="{{ asset('/front') }}/assets/js/bootstrap-summernote.min.js"></script>
<script src="{{ asset('/front') }}/assets/js/summernote.js"></script>
<script src="{{ asset('/front') }}/assets/editor/img.js"></script>
<script>
   jQuery(function($){
        $('#description').summernote({
            height: 200,
            styleTags: [
            'p',
                { title: 'Blockquote', tag: 'blockquote', className: 'blockquote', value: 'blockquote' },
                'pre', 'h2', 'h3', 'h4', 'h5', 'h6'
            ],
        });
    });
   //CKEDITOR.replace('description', { customConfig: "{{ asset('/front/ckeditor_config.js') }}",filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",filebrowserUploadMethod: 'form' });
   
   Dropzone.autoDiscover = false;
   Dropzone.autoDiscover = false;

    var startDate = $("#datepicker1").datepicker({
        autoclose: true,
        todayHighlight: true,
        //startDate: '0d',
        format: 'dd-mm-yyyy',
        useCurrent: false,
  }).datepicker();

    // startDate.on('change', function (e) {
    //if ($("#datepicker1").datepicker("getDate") != null) {

        var endDate = $("#datepicker2").datepicker({
        autoclose: true,
        todayHighlight: false,
        startDate: $("#datepicker1").datepicker("getDate"),
        format: 'dd-mm-yyyy',
        //startDate: '-3d'
        //useCurrent: false,
        }).datepicker();

    //}
  // });
   
   
   $(document).ready(function(){
        //$('.ckeditor').ckeditor();
        $(".js-example-tags").select2({
             tags: true,
             dropdownAutoWidth : true,
             width: '100%',
             dropdownCssClass: "select-list",
             maximumSelectionLength: 3
         });
      $("body").on("click",".delete_logo",function(e){
         
         $(this).closest(".form-group").find("input[type='file']").val('');
           $(this).closest(".form-group").find(".image_name").val('');
         $(this).closest(".crd-img-component").html('');
         
      });
      var eduRemoveList=new Array();
        var CSRF_TOKEN = $("input[name='_token']").val();
      var galDropzone = new Dropzone("div#crd-gal-dropzone", {
           autoProcessQueue:false,
           addRemoveLinks: true, 
           maxFiles: 10,
           acceptedFiles: ".pdf,.docx,.doc,.jpg,.jpeg",
           url: '{!! route('admin.project-management.project-gallery-save') !!}',
           parallelUploads:10,
             uploadMultiple:true,
           params:{"projectID":{!! $getDetail->id !!},"_token":CSRF_TOKEN,"_method":"POST"},
           success: function (file, response) {
               
          },
          init: function() { 
            myDropzone = this;
            var response={!! json_encode($galleries_json) !!}
            $.each(response, function(key,value) {
                var mockFile = { name: value.name, size: value.size,serverID: value.id };
                  myDropzone.emit("addedfile", mockFile);
                 myDropzone.createThumbnailFromUrl(mockFile, value.path);
                 myDropzone.emit("success", mockFile);
                 myDropzone.emit("complete", mockFile);
                 myDropzone.files.push(mockFile);
                
   
              });
              this.on("complete", function (file) {
                 console.log(this.getUploadingFiles().length);
                 if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                   $("#project_edit").trigger("submit");
                 }
               });
              
          },
          removedfile: function(file) {
            var id = file.serverID; 
             eduRemoveList.push(id);
             $("input[name='gal_image_remove']").val(eduRemoveList);
             var _ref;
             return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
          }
           
       });
        
       galDropzone.on("maxfilesexceeded", function(file)
      {
          this.removeFile(file);
      });
       // $("#cover_image").change(function () {
       //       const file = this.files[0];
       //       if (file) {
               
       //            let reader = new FileReader();
       //            reader.onload = function (event) {
       //                $("#cov_img")
       //                  .html('<img src="'+event.target.result+'" style="width: 60px;height: 60px;" /><span class="cover_close delete_logo" style="margin: 10px;"><b><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">X</font></font></b></span>');
       //                $("#cov_img").closest(".form-group").find(".image_name").val('fileUploaded');
                      
       //            };
       //            reader.readAsDataURL(file);
       //         }
       //    });
         // $("#associate_logo").change(function () {
         //        const file = this.files[0];
         //        if (file) {
         //            let reader = new FileReader();
         //            reader.onload = function (event) {
         //                $("#logo_img")
         //                  .html('<img src="'+event.target.result+'" style="width: 60px;height: 60px;" /><span class="cover_close delete_logo" style="margin: 10px;"><b><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">X</font></font></b></span>');
         //                  $("#logo_img").closest(".form-group").find(".image_name").val('fileUploaded');
         //            };
         //            reader.readAsDataURL(file);
         //        }
         //    });
             function isNumber(evt) {
               evt = (evt) ? evt : window.event;
               var charCode = (evt.which) ? evt.which : evt.keyCode;
               if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                   return false;
               }
               return true;
           }
         $("#number_of_goal").on("change",function(){
           var c=$("#number_of_goal option:selected").val();
           if(c==3)
           {
              $("#campaign_one").hide();
              $("#campaign_one .form-group").addClass("exclude");
              $("#campaign_three").fadeIn();
              $("#campaign_three .form-group").removeClass("exclude");
           }
           else
           {
              $("#campaign_three").hide();
              $("#campaign_three .form-group").addClass("exclude");
              $("#campaign_one").fadeIn();
              $("#campaign_one .form-group").removeClass("exclude");
           }
         });
         galDropzone.removeEventListeners();
         $(".dummy-gallery-trigger").on("click",function(e){
           e.preventDefault();
           galDropzone.setupEventListeners();
           document.getElementsByClassName("dropzone")[0].click();
           galDropzone.removeEventListeners();
           return false;
         });
       $('.submit_new').on('click', function(e) {
         e.preventDefault();
   
         var flag=0;
         var finalLocate="";
         $(".form-group").removeClass("sgo-error");
         $(".validation_msg").html("");

            $(".form-group").each(function () {
   
               if($(this).hasClass("exclude"))
                {
                  return true;
                }
                if($(this).find("textarea").attr('name')==undefined)
                {
                  return true;
                }
   
               var temp="";
               var i=$(this).find("input[type='text']").length;
               var p=$(this).find("input[type='tel']").length;
               var e=$(this).find("input[type='email']").length;
               var u=$(this).find("input[type='url']").length;
               var n=$(this).find("input[type='number']").length;
               var t=$(this).find("textarea").length;
               var s=$(this).find("select").length;
               var d=$(this).find(".dropzone-previews-cwd").length;
              var f=$(this).find(".image_name").length;
              
               
               if(i<0 || p<0 || e<0 || u<0 || n<0 || t<0 || s<0 || d<0 || f<0)
               {
                  return true;
               }
               
               var locate=$(this);
               if(i>0 || p>0 || e>0 || u>0 || n>0 || t>0 || s>0 || d>0 || f>0)
               {
                  if(f>0)
                 {
                    temp=$(this).find(".image_name").val();
                    if($.trim(temp) == '')
                    {
                       $(this).find(".validation_msg").html('<small style="color:red">Champs requis</small>');
                    }
                    
                 }
                  if(i>0)
                  {
                     temp=$(this).find("input").val();
                    if($.trim(temp) == '')
                    {
                       $(this).find(".validation_msg").html('<small style="color:red">Champs requis</small>');
                    }
                  }
                  if(t>0)
                  {
                     if($(this).hasClass('summernote'))
                     {
                        temp=$('#description').code();
                     }
                     else{
                        temp=$(this).find("textarea").val();
                     }
                    
                    
                    if($.trim(temp) == '')
                    {
                       $(this).find(".validation_msg").html('<small style="color:red">Champs requis</small>');
                    }
                  }
                  if(n>0)
                  {
                     temp=$(this).find("input").val();
                    if($.trim(temp) == '')
                    {
                       $(this).find(".validation_msg").html('<small style="color:red">Champs requis</small>');
                    }
                    if($(this).find("input[name='double_amount_limit']").val()>{!!$maxDouble!!})
                    {
                        $(this).find(".validation_msg").html('<small style="color:red">Le montant du don est supérieur à {!!$maxDouble!!}</small>');
                        temp='';
                    }
                  }
                  if(s>0)
                   {
                        
                       temp=$(this).find("select").val();
                       if($.trim(temp) == '')
                       {
                          $(this).find(".validation_msg").html('<small style="color:red">Champs requis</small>');
                       }
                     
                   }
                  if(e>0)
                  {
                     
                     temp=$(this).find("input[type='email']").val();
                     
                     var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                     if(!regex.test( temp ))
                     {
                        $(this).addClass("sgo-error");
                          $(this).find(".validation_msg").html('<small style="color:red">Saisissez une adresse e-mail valide</small>');
                          
                        if(flag<1)
                        {
                           finalLocate=locate;
                        }
                        flag=flag+1;
                        
                     }
                  }
                  if(p>0)
                   {
                     temp=$(this).find("input[type='tel']").val();
                     temp=temp.replace(/\s/g, '');

                     var phoneReg = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
                     if(!phoneReg.test( temp ))
                     {
                        $(this).addClass("sgo-error");
                          $(this).find(".validation_msg").html('<small style="color:red">Entrez un numéro de téléphone valide</small>');
                        if(flag<1)
                        {
                           finalLocate=locate;
                        }
                        flag=flag+1;
                     }
                   }
                if(u>0)
                {
                  temp=$(this).find("input[type='url']").val();
                  
                  
                  var regex = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
                  if(!regex.test( temp ))
                  {
                     $(this).addClass("sgo-error");
                       $(this).find(".validation_msg").html('<small style="color:red">Entrez une URL valide</small>');
                     if(flag<1)
                     {
                        finalLocate=locate;
                     }
                     flag=flag+1;
                  }
                }
                if(d>0)
                {
                  if($(this).find(".dropzone-previews-cwd").attr("data-name")=='galDropzone')
                  {
                     temp=galDropzone.files.length;
                  }
                  if(temp<1)
                  {
                     $(this).addClass("sgo-error");
                       $(this).find(".validation_msg").html('<small style="color:red">Champs requis</small>');
                     if(flag<1)
                     {
                        finalLocate=locate;
                     }
                     flag=flag+1;
                  }
                }
                
                if(temp.length<1)
               {
                  $(this).addClass("sgo-error");
                  if(flag<1)
                  {
                     finalLocate=locate;
                     
                  }
                  flag=flag+1;
               }
               
               }
   
            });
            if(flag>0)
            {
               var pos = $(finalLocate).offset().top;
               pos=pos-30;
               $('body, html').animate({scrollTop: pos},2000);
               return false;
            }
           if(flag==0)
           {
              if(galDropzone.getQueuedFiles().length>0)
              {
                 galDropzone.processQueue();
              }
              else
              {
                 $("#project_edit").trigger("submit");
              }
               
           }
         });
   });
</script>
@endpush