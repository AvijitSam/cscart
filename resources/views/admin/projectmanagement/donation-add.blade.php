@extends('admin.layouts.after-login-layout')
@section('unique-content')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/dropzone.css')}}">
<link rel="stylesheet" href="{{ asset('/front') }}/assets/css/datepicker.css">
<style>
   .donation-content {
   padding: 10px;
   box-shadow: 0px 3px 6px #00000029;
   border: 0.5px solid #1f2d3d;
   text-align: center;
   margin-bottom: 55px;}
   .donation-text_big {
   color: #461848;
   font-size: 16px;
   font-weight: 400;
   line-height: 24px;
   }
   .price-highlited {
   color: #FB9D24;
   font-size: 16px;
   font-weight: 600;
   }
   .main-footer{
   margin-left: 0 !important;
   }
   /*.fade:not(.show){display: none;}*/
   .form-control {
   border-radius: 5px;
   }
   .price-btn.active-btn, .price-btn:hover {
   background-color: #1f2d3d !important;
   border-color: #FB9D24;
   color: #fff;
   }
   .cat{
   margin: 4px;
   background-color: #104068;
   border-radius: 4px;
   border: 1px solid #fff;
   overflow: hidden;
   float: left;
   }
   .cat label {
   float: left; line-height: 3.0em;
   width: 8.0em; height: 3.0em;
   }
   .cat label span {
   text-align: center;
   padding: 3px 0;
   display: block;
   }
   .cat label input {
   position: absolute;
   display: none;
   color: #fff !important;
   }
   /* selects all of the text within the input element and changes the color of the text */
   .cat label input + span{color: #fff;}
   .cat input:checked + span {
   color: #ffffff;
   text-shadow: 0 0  6px rgba(0, 0, 0, 0.8);
   }
   .comedy input:checked + span{background-color: #F75A1B;}
   .form-group.sgo-error input,.form-group.sgo-error textarea,.form-group.sgo-error select,.form-group.sgo-error .dropzone,.form-group.sgo-error .select2-selection{border: 2px solid red;}
</style>
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Ajouter un don</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{route('admin.project-management.project-list')}}">Projects List</a></li>
                  <li class="breadcrumb-item"><a href="{{route('admin.project-management.donation-list',encrypt($project->id, Config::get('Constant.ENC_KEY')))}}">Donation List</a></li>
                  <li class="breadcrumb-item active"> Ajouter un don</li>
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
         <div class="row">
            <div class="col-12">
               <div class="card card-primary">
                  <div class="card-header">
                     <h3 class="card-title">{{$panel_title}}</h3>
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
                     <form enctype="multipart/form-data" action="{{route('admin.project-management.donation-add-save')}}" method="POST" id="donation_add">
                        {{ csrf_field() }}
                        <input type="hidden" name="project_id" value="{{$project->id}}">
                        <div class="row">
                           <div class="col-md-10">
                              <div class="row">
                                 <div class="col-md-10">
                                    <h6 class="text-secondary">1- Mon don</h6>
                                 </div>
                              </div>
                              <!----------->
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Je donne : </label>
                                 <div class="col-sm-10" id="preval">
                                    @php $c = 1; @endphp
                                    @foreach($prefields as $prefield)
                                    <div class="cat comedy">
                                       <label>
                                       <input type="checkbox" value="{{$prefield}}" @if($c == 1) checked @endif><span>{{$prefield}} €</span>
                                       </label>
                                    </div>
                                    @php $c++ @endphp
                                    @endforeach
                                 </div>
                                 <div class="col-sm-2"></div>
                                 <div class="col-sm-10">
                                    <div class="validation_msg"></div>
                                 </div>
                              </div>
                              <!----------->
                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Montant libre : </label>
                                 <div class="col-sm-10 price-input input-group">
                                    <input  type="number" class="form-control" id="textamount" placeholder="Montant libre" aria-label="Montant libre" aria-describedby="basic-addon2" onkeyup="return userDonationAmountInput();" >
                                    <span class="input-group-text" id="basic-addon2">€</span>
                                 </div>
                                 <div class="col-sm-2"></div>
                                 <div class="col-sm-10 price-input">
                                    <div id="" class="form-text">Montant recommandé : € {{$project->recomended_amount}}</div>
                                    <span id="exact_amount_id" class="warn"></span>
                                 </div>
                                 <div class="col-sm-2"></div>
                                 <div class="col-sm-10">
                                    <div class="validation_msg"></div>
                                 </div>
                              </div>
                              <!----------->
                              @if($project['is_double'] == '0')
                              <input type="hidden" id="donation_type" name="donation_type" value="self">
                              @else
                              <input type="hidden" id="donation_type" name="donation_type" value="double">
                              @endif
                              <input type="hidden" id="doner_type" name="doner_type" value="particular">
                              <input type="hidden" id="only-selected-amount"  name="only-selected-amount" value="{{$initamount}}">
                              <div class="row">
                                 <div class="col-sm-12">
                                    <div class="donation-content" id="particular-tax">
                                       <div class="donation-text_big">
                                          <p>
                                             @if($project['is_double'] == '1')
                                             Soit un don de <span class="price-highlited "><span class="doubleamount">{{$initdouble}}</span> €</span> pour le projet après abondement du Fonds Fraternité pour demain. 
                                             @endif
                                             Votre don ne vous coûte que <span class="price-highlited"><span class="percentamt">{{$percent_particular}}</span> €</span> après réduction d’impôts 
                                          </p>
                                       </div>
                                       <small>(66 % dans la limite de 20 % de votre revenu imposable. L’excédent est reportable sur 5 ans)</small>
                                    </div>
                                    <div class="donation-content" id="entreprise-tax" style="display: none;">
                                       <div class="donation-text_big">
                                          <p>
                                             @if($project['is_double'] == '1')
                                             Soit un don de <span class="price-highlited "><span class="doubleamount">{{$initdouble}}</span> €</span> pour le projet après abondement du Fonds Fraternité pour demain. 
                                             @endif
                                             Votre don ne vous coûte que <span class="price-highlited"><span class="percentamt">{{$percent_enterprise}}</span> €</span> après réduction d’impôt.
                                          </p>
                                       </div>
                                       <small>(60 % plafonné à 20 000 € ou 0,5 % du chiffre d’affaires annuel ht. L’excédent est reportable sur 5 ans)</small>
                                    </div>
                                 </div>
                              </div>
                              <!----------->
                              <div class="form-group row">
                                 <div class="col-sm-12 form-check">
                                    <label for="Title" class="col-form-label">
                                       <input class="form-check-input" type="checkbox" value="no" id="flexCheckChecked" name="want_refund" checked>
                                    <strong>Réaffectation du don.</strong> En cas d’annulation de la collecte ou du projet, j’accepte que le Fonds 
                                    Fraternité pour Demain réoriente mon don vers un autre projet.
                                    
                                    </label>
                                    <div class="col-sm-12">
                                       <div class="validation_msg"></div>
                                    </div>
                                 </div>
                              </div>
                              <!----------->
                              <div class="form-group row">
                                 <div class="col-sm-12 form-check">
                                    <label for="Title" class="col-form-label">
                                       <input class="form-check-input" type="checkbox" name="is_anonymous" value="yes" id="flexCheckDefault">
                                    <strong>Don anonyme.</strong> Mon nom et mes coordonnées ne seront pas divulguées à l’association porteuse du projet.
                                    
                                    </label>
                                 </div>
                                 <div class="col-sm-12">
                                    <div class="validation_msg"></div>
                                 </div>
                              </div>
                              <!----------->
                              <div class="row">
                                 <div class="col-md-12">
                                    <hr>
                                 </div>
                                 <div class="col-md-10">
                                    <h6 class="text-secondary">2- Mes coordonnées</h6>
                                 </div>
                              </div>
                              <!----------->
                              <div class="my-donation_form--block contact_form-block">
                                 <!-- Tab section -->
                                 <ul class="align-items-center justify-content-center nav nav-tabs contact_form-tabs" id="pills-tab" role="tablist" style="margin-bottom: 25px;">
                                    <li class="nav-item" role="presentation" style="margin-right: 20px;">
                                       <button class="nav-link active" id="particular_tab" data-bs-toggle="pill" data-bs-target="#pills-particular" type="button" role="tab" aria-controls="pills-particular" aria-selected="true" onclick="return getTaxContent('particular');">Particulier</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                       <button class="nav-link" id="company_tab" data-bs-toggle="pill" data-bs-target="#pills_company" type="button" role="tab" aria-controls="pills_company" aria-selected="false" onclick="return getTaxContent('enterprise');">Organisme</button>
                                    </li>
                                 </ul>
                                 <div class="tab-content" id="pills-tabContent" style="min-height: 550px;">
                                    <div class="tab-pane fade show active" id="pills-particular" role="tabpanel" aria-labelledby="particular_tab">
                                       <div class="particular-form contact_form">
                                          <div class="form-group row">
                                             <label for="contact_email" class="col-form-label col-sm-2">Email*</label>
                                             <div class="col-sm-10">
                                                <input type="email" class="form-control" id="contact_email" placeholder="" name="contact_email">
                                                <span id="contact_email_id" class="warn"></span>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="form-group row">
                                             <label for="contact_first_name" class="col-form-label col-sm-2">Nom*</label>
                                             <div class="col-sm-10">
                                                <input type="text" class="form-control" placeholder="" aria-label="Nom" id="contact_first_name" name="contact_first_name">
                                                <span id="contact_first_name_id" class="warn"></span>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="form-group row">
                                             <label for="contact_last_name" class="col-form-label col-sm-2">Prénom*</label>
                                             <div class="col-sm-10">
                                                <input type="text" class="form-control" placeholder="" aria-label="Prénom" id="contact_last_name" name="contact_last_name">
                                                <span id="contact_last_name_id" class="warn"></span>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="form-group row">
                                             <label for="contact_address" class="col-form-label col-sm-2">Adresse*</label>
                                             <div class="col-sm-10">
                                                <input type="text" class="form-control" id="contact_address" placeholder="" name="contact_address">
                                                <span id="contact_address_id" class="warn"></span>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="form-group row">
                                             <label for="contact_additional_address" class="col-form-label col-sm-2">Complément d’adresse</label>
                                             <div class="col-sm-10">
                                                <input type="text" class="form-control" id="contact_additional_address" placeholder="" name="contact_additional_address">
                                                <span id="contact_additional_address_id" class="warn"></span>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="form-group row">
                                             <label for="contact_postalcode" class="col-form-label col-sm-2">Code Postal*</label>
                                             <div class="col-sm-10">
                                                <input type="text" class="form-control" id="contact_postalcode" placeholder="" name="contact_postalcode">
                                                <span id="contact_postalcode_id" class="warn"></span>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="form-group row">
                                             <label for="contact_village" class="col-form-label col-sm-2">Ville*</label>
                                             <div class="col-sm-10">
                                                <input type="text" class="form-control" id="contact_village" placeholder="" name="contact_village">
                                                <span id="contact_village_id" class="warn"></span>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="form-group row">
                                             <label for="contact_country" class="col-form-label col-sm-2">Pays*</label>
                                             <div class="col-sm-10">
                                                <select class="form-control js-example-basic-single select2-hidden-accessible js-example-tags" id="contact_country" name="contact_country">
                                                   @foreach($countries as $country)
                                                   <option value="{{$country}}" @if(old('contact_country_en') == $country or $country == 'France') selected @endif>{{$country}}</option>
                                                   @endforeach
                                                </select>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="form-group row newsletter-row">
                                             <label for="contact_country" class="col-form-label col-sm-2">Je souhaite recevoir l’info-lettre</label><!--  reallocation-donation -->
                                             <div class="col-sm-10">
                                                <div class="form-check">
                                                   <input class="form-check-input" type="checkbox" value="1" id="is_newsletter" name="is_newsletter">
                                                   <span id="is_newsletter_id" class="warn"></span>
                                                </div>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="form-group row block-checkbox">
                                             <label for="contact_country" class="col-form-label col-sm-2">
                                             Comment avez-vous connu le Fonds ?
                                             </label>
                                             <div class="col-sm-10">
                                                <!--   -->
                                                <div class="form-check">
                                                   <input class="form-check-input" type="checkbox" id="is_internet" value="1" name="is_internet">
                                                   <label class="form-check-label" for="is_internet">Internet</label>
                                                </div>
                                                <!--  -->
                                                <div class="form-check">
                                                   <input class="form-check-input" type="checkbox" id="is_brochure" value="1" name="is_brochure">
                                                   <label class="form-check-label" for="is_brochure">Bouche à oreille</label>
                                                </div>
                                                <!--  -->
                                                <div class="form-check">
                                                   <input class="form-check-input" type="checkbox" id="is_email" value="1" name="is_email">
                                                   <label class="form-check-label" for="is_email">Emailing</label>
                                                </div>
                                                <!--  -->
                                                <div class="form-check">
                                                   <input class="form-check-input" type="checkbox" id="is_ad" value="1" name="is_ad">
                                                   <label class="form-check-label" for="is_ad">Publicité</label>
                                                </div>
                                                <!--  -->
                                                <div class="form-check">
                                                   <input class="form-check-input" type="checkbox" id="is_others" value="1" name="is_others" onclick="return toggleReason();">
                                                   <label class="form-check-label" for="is_others">Autre</label>
                                                </div>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                             <span id="source_id" class="warn"></span>
                                          </div>

                                          <div class="row" id="other_reason_div_id" style="display: none;">
                                                <div class="col">
                                                  <label for="other_reason" class="form-label">Précisez</label>
                                                  <input type="text" class="form-control" id="other_reason" placeholder="" name="other_reason" value="{{old('other_reason')}}">
                                                  <span id="other_reason_id" class="warn"></span>
                                                </div>
                                            </div>

                                          <div class="row">
                                             <label for="contact_country" class="col-form-label col-sm-12">* champs obligatoires, ces informations sont indispensables pour bénéficier de votre reçu fiscal.</label>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills_company" role="tabpanel" aria-labelledby="company_tab">
                                       <div class="particular-form company-form">
                                          <div class="form-group row">
                                             <label for="contact_email_en" class="col-form-label col-sm-2">Email*</label>
                                             <div class="col-sm-10">                                             
                                                <input type="email" class="form-control" id="contact_email_en" placeholder="" name="contact_email_en">
                                                <span id="contact_email_en_id" class="warn"></span>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="form-group row">
                                             <label for="company_name" class="col-form-label col-sm-2">Raison sociale de l’organisme*</label>
                                             <div class="col-sm-10">
                                                <input type="text" class="form-control" id="company_name" placeholder="" name="company_name">
                                                <span id="company_name_id" class="warn"></span>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="form-group row">
                                             <label for="contact_first_name_en" class="col-form-label col-sm-2">Nom</label>
                                             <div class="col-sm-10">
                                                <input type="text" class="form-control" placeholder="" aria-label="Nom" id="contact_first_name_en" name="contact_first_name_en">
                                                <span id="contact_first_name_en_id" class="warn"></span>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="form-group row">
                                             <label for="contact_last_name_en" class="col-form-label col-sm-2">Prénom</label>
                                             <div class="col-sm-10">
                                                <input type="text" class="form-control" placeholder="" aria-label="Prénom" id="contact_last_name_en" name="contact_last_name_en">
                                                <span id="contact_last_name_en_id" class="warn"></span>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="form-group row">
                                             <label for="contact_address_en" class="col-form-label col-sm-2">Adresse*</label>
                                             <div class="col-sm-10">
                                                <input type="text" class="form-control" id="contact_address_en" placeholder="" name="contact_address_en">
                                                <span id="contact_address_en_id" class="warn"></span>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="form-group row">
                                             <label for="contact_additional_address_en" class="col-form-label col-sm-2">Complément d’adresse</label>
                                             <div class="col-sm-10">
                                                <input type="text" class="form-control" id="contact_additional_address_en" placeholder="" name="contact_additional_address_en">
                                                <span id="contact_additional_address_en_id" class="warn"></span>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="form-group row">
                                             <label for="contact_postalcode_en" class="col-form-label col-sm-2">Code Postal*</label>
                                             <div class="col-sm-10">
                                                <input type="text" class="form-control" id="contact_postalcode_en" placeholder="" name="contact_postalcode_en">
                                                <span id="contact_postalcode_en_id" class="warn"></span>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="form-group row">
                                             <label for="contact_village_en" class="col-form-label col-sm-2">Ville*</label>
                                             <div class="col-sm-10">
                                                <input type="text" class="form-control" id="contact_village_en" placeholder="" name="contact_village_en">
                                                <span id="contact_village_en_id" class="warn"></span>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="form-group row">
                                             <label for="contact_country_en" class="col-form-label col-sm-2">Pays*</label>
                                             <div class="col-sm-10">
                                                <select class="form-control js-example-basic-single select2-hidden-accessible js-example-tags" id="contact_country_en" name="contact_country_en">
                                                   @foreach($countries as $country)
                                                   <option value="{{$country}}" @if(old('contact_country_en') == $country or $country == 'France') selected @endif>{{$country}}</option>
                                                   @endforeach
                                                </select>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="row">
                                             <div class="col"><span id="contact_country_en_id" class="warn"></span>
                                             </div>
                                          </div>
                                          <div class="form-group row newsletter-row exclude">
                                             <label for="contact_country" class="col-form-label col-sm-2">Je souhaite recevoir l’info-lettre</label>
                                             <div class="col-sm-10">
                                                <div class="form-check reallocation-donation">
                                                   <input class="form-check-input" type="checkbox" value="1" id="is_newsletter_en" name="is_newsletter_en">
                                                   <span id="is_newsletter_en_id" class="warn"></span>
                                                </div>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                          </div>
                                          <div class="row form-group">
                                             <label for="contact_country" class="col-form-label col-sm-2">
                                             Comment avez-vous connu le Fonds ?
                                             </label>
                                             <div class="col-sm-10">
                                                <div class="form-check">
                                                   <input class="form-check-input" type="checkbox" id="is_internet_en" value="1" name="is_internet_en">
                                                   <label class="form-check-label" for="is_internet_en">Internet</label>
                                                </div>
                                                <div class="form-check ">
                                                   <input class="form-check-input" type="checkbox" id="is_brochure_en" value="1" name="is_brochure_en">
                                                   <label class="form-check-label" for="is_brochure_en">Bouche à oreille</label>
                                                </div>
                                                <div class="form-check ">
                                                   <input class="form-check-input" type="checkbox" id="is_email_en" value="1" name="is_email_en">
                                                   <label class="form-check-label" for="is_email_en">Emailing</label>
                                                </div>
                                                <div class="form-check ">
                                                   <input class="form-check-input" type="checkbox" id="is_ad_en" value="1" name="is_ad_en">
                                                   <label class="form-check-label" for="is_ad_en">Publicité</label>
                                                </div>
                                                <div class="form-check ">
                                                   <input class="form-check-input" type="checkbox" id="is_others_en" value="1" name="is_others_en" onclick="return toggleReasonEn();">
                                                   <label class="form-check-label" for="is_others_en">Autre</label>
                                                </div>
                                             </div>
                                             <div class="col-sm-2"></div>
                                             <div class="col-sm-10">
                                                <div class="validation_msg"></div>
                                             </div>
                                             <span id="source_en_id" class="warn"></span>
                                          </div>
                                          <div class="row" id="other_reason_en_div_id" style="display: none;">
                                                <div class="col">
                                                  <label for="other_reason_en" class="form-label">Précisez</label>
                                                  <input type="text" class="form-control" id="other_reason_en" placeholder="" name="other_reason_en" value="{{old('other_reason_en')}}">
                                                  <span id="other_reason_en_id" class="warn"></span>
                                                </div>
                                            </div>
                                          <div class="row info-text">
                                             <label for="contact_country" class="col-form-label col-sm-12">* champs obligatoires, ces informations sont indispensables pour bénéficier de votre reçu fiscal.</label>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12">
                                    <hr>
                                 </div>
                                 <div class="col-md-10">
                                    <h6 class="text-secondary">3- Mon règlement</h6>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12">
                                    <label class="col-form-label">Pour le projet « <!-- Les abeilles à l’école -->{{$project->project_title}} » Je donne <span class="price-highlited" id="onlyprice">{{$initamount}}€</span></label>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label class="col-form-label col-sm-2">Je participe aux frais du Fonds Fraternité pour Demain 
                                 <small>(qui propose gratuitement ses services)</small></label>
                                 <div class="price-section col-sm-10">
                                    <div class="input-group price-input">
                                       <input type="hidden" id="hidden-to-ngo" name="hidden-to-ngo" value="0">
                                       <input type="hidden" id="hidden-except-ngo" name="hidden-except-ngo" value="0">
                                       <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon3" value="{{$init_ngo}}" id="tongo" onkeyup="return userDonationNGO();">
                                       <span class="input-group-text" id="basic-addon3">€</span>
                                    </div>
                                 </div>
                                 <div class="col-sm-2"></div>
                                 <div class="col-sm-10">
                                    <div class="validation_msg"></div>
                                 </div>
                              </div>
                              <div class="row">
                                 <label class="col-form-label col-sm-2">Total :</label>
                                 <div class="col-sm-10">
                                    <p><span class="price-highlited" id="with-ngo-total">{{$initamount}} €</span></p>
                                    <div class="form-text">La totalité de votre don fera l’objet d’un reçu fiscal<small>(y compris votre contribution pour le Fonds Fraternité pour Demain)</small></div>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="donation_method" class="col-form-label col-sm-2">Mode de paiement</label>
                                 <div class="col-sm-10">
                                    <select class="form-control" id="donation_method" name="donation_method">
                                       <option value="cheque">Chèque</option>
                                       <option value="cash">Espèces</option>
                                       <option value="other">Autre</option>
                                    </select>
                                 </div>
                                 <div class="col-sm-2"></div>
                                 <div class="col-sm-10">
                                    <div class="validation_msg"></div>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="donation_status" class="col-form-label col-sm-2">Statut du don</label>
                                 <div class="col-sm-10">
                                    <select class="form-control" id="donation_status" name="donation_status">
                                       <option value="completed">Validé</option>
                                       <option value="init" selected>En cours</option>
                                       <option value="fail">Echoué</option>
                                       <option value="canceled">Annulé</option>

                                    </select>
                                 </div>
                                 <div class="col-sm-2"></div>
                                 <div class="col-sm-10">
                                    <div class="validation_msg"></div>
                                 </div>
                              </div>

                              <div class="form-group row">
                                 <label for="Title" class="col-sm-2 col-form-label">Date du don</label>
                                 <div class="col-sm-10">
                                    <input type="text" name="donation_date" id="datepicker1" value="" class="form-control date_input" placeholder="Date du don" title="Date du don">
                                    <div id="donation_date_id" class="validation_msg"></div>
                                 </div>
                              </div>

                              <input type="hidden" name="amount" value="{{$initamount}}" id="chargeprice"/>
                              <input type="hidden" name="amount_to_project" id="amount_to_project" value="{{$initamount}}">
                              <input type="hidden" name="amount_to_ngo" id="amount_to_ngo" value="0">
                              <!-- Tab section -->
                           </div>
                        </div>
                  </div>
                  <div class="card-footer">
                  <div class="">
                  <button  class="btn btn-success submit_new">Ajouter un don</button>
                  <a class="btn btn-primary back_new" href="{{route('admin.project-management.project-list')}}">Retour</a>
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
<!-- Sweet alert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{asset('assets/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="{{ asset('/front') }}/assets/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="{{ asset('/front') }}/assets/js/bootstrap-datepicker.js"></script>
<script>

   function toggleReasonEn()
   {
       // alert('ss');
       if($('#is_others_en').prop("checked") == true)
       {
           $('#other_reason_en_div_id').show();
       }
       else
       {
           $('#other_reason_en_div_id').hide();
       }
   }

   function toggleReason()
   {
       // alert($('#is_others').prop("checked"));
       if($('#is_others').prop("checked") == true)
       {
           $('#other_reason_div_id').show();
       }
       else
       {
           $('#other_reason_div_id').hide();
       }
   }

   $("#datepicker1").datepicker({
        autoclose: true,
        todayHighlight: true,
        //startDate: '0d',
        format: 'dd-mm-yyyy',
        useCurrent: false,
  }).datepicker();

   jQuery(".js-example-tags").select2({
        tags: true,
        dropdownAutoWidth : true,
        width: '100%',
        dropdownCssClass: "select-list",
        maximumSelectionLength: 3
    });
   jQuery(document).ready(function(){
      calculatetotal();
   });
   jQuery(".comedy input").change(function() {
        
     $(".comedy input").not(this).prop('checked', false);
     $('.comedy input:checked').each(function() {
         $('#textamount').val(0);
         userDonationAmount(this.value);
   
      });
        
   });
   function userDonationAmount(thisamount, thisnum)
   {
    calculatetotal();
   }

   function calculatetotal()
   {
      var t=0;
      $('.comedy').closest('.form-group').removeClass("exclude");
      $('#textamount').closest('.form-group').removeClass("exclude");
      $('.comedy input:checked').each(function() {
        t=t+parseInt(this.value);
        $('#textamount').closest('.form-group').addClass("exclude");
      });
      var t2=parseInt($('#textamount').val());
      if(t2>0)
      {
         $('.comedy').closest('.form-group').addClass("exclude");
         t=t2;
      }
      var dblamt = t*2;
        $('.doubleamount').html(dblamt);
        var parcent_amount = 0;
        if($('#doner_type').val() == 'particular')
        {
            parcent_amount = t - (t *  (66/100));
        }
        else
        {
            parcent_amount = t - (t *  (60/100));
        }
        var tempP=parcent_amount.toPrecision(3);
        $('.percentamt').html(tempP);
        
        $('#onlyprice').html(t+'€');
         $("#amount_to_project").val(t);

         //////////////////////////
         if(t < 100)
         {
            var tongo = $('#tongo').val();

            to_the_ngo = tongo;

            // if(tongo < 5)
            // {
            //    to_the_ngo = 5;
            // }
            // else
            // {
            //    to_the_ngo = tongo;
            // }
         }
         else
         {
               var tongo = $('#tongo').val();

               var approx_percent = (8 / 100) * t;

               if(approx_percent % 1 === 0)
               {
                  to_the_ngo = approx_percent;
               }
               else
               {
                  to_the_ngo = parseFloat(((8 / 100) * t)).toFixed(2);
               }

               

               /*if(tongo < 8)
               {
                  var approx_percent = (8 / 100) * t;

                  if(approx_percent % 1 === 0)
                  {
                      to_the_ngo = approx_percent;
                  }
                  else
                  {
                      to_the_ngo = parseFloat(((8 / 100) * t)).toFixed(2);
                  }
               }
               else
               {
                  to_the_ngo = tongo;
               }*/
               
               
         }
         $('#tongo').val(to_the_ngo);
         /////////////////////////

        var ngo=$("#tongo").val();

        var tempNgo=parseFloat(t)+parseFloat(ngo);
         $("#amount_to_ngo").val(ngo);
        
        // if(ngo>0)
        // {
        //  var tempNgo=parseFloat(t)+parseFloat(ngo);
        //  $("#amount_to_ngo").val(ngo);
        // }
        // else
        // {
        //  var tempNgo=t;
        // }
        $('#with-ngo-total').html(tempNgo+' €');

        $("#chargeprice").val(tempNgo);
   }

   //old one
   
   /*function calculatetotal()
   {
      var t=0;
      $('.comedy').closest('.form-group').removeClass("exclude");
      $('#textamount').closest('.form-group').removeClass("exclude");
      $('.comedy input:checked').each(function() {
        t=t+parseInt(this.value);
        $('#textamount').closest('.form-group').addClass("exclude");
      });
      var t2=parseInt($('#textamount').val());
      if(t2>0)
      {
         $('.comedy').closest('.form-group').addClass("exclude");
         t=t2;
      }
      var dblamt = t*2;
        $('.doubleamount').html(dblamt);
        var parcent_amount = 0;
        if($('#doner_type').val() == 'particular')
        {
            parcent_amount = t - (t *  (66/100));
        }
        else
        {
            parcent_amount = t - (t *  (60/100));
        }
        var tempP=parcent_amount.toPrecision(3);
        $('.percentamt').html(tempP);
        
        $('#onlyprice').html(t+'€');
         $("#amount_to_project").val(t);

        var ngo=parseInt($("#tongo").val());
        if(ngo>0)
        {
         var tempNgo=t+ngo;
         $("#amount_to_ngo").val(ngo);
        }
        else
        {
         var tempNgo=t;
        }
        $('#with-ngo-total').html(tempNgo+' €');
        $("#chargeprice").val(tempNgo);
   }*/
   
   function userDonationAmountInput()
   {
   $(".comedy input").prop('checked', false); 
   calculatetotal();
   }
   
   function userDonationNGO()
   {
      $ngo = $('#tongo').val();
      calculatetotal();
      $('#tongo').val($ngo);

      var t=parseInt($('#amount_to_project').val());
      var ngo=$("#tongo").val();
      var tempNgo=parseFloat(t)+parseFloat(ngo);
      $('#with-ngo-total').html(tempNgo+' €');
   }
   
   function getTaxContent(tabname)
   {
    // alert(tabname);
    $('#doner_type').val(tabname);
    calculatetotal();
   }
   $('.submit_new').on('click', function(e) {
   e.preventDefault();
   var flag=0;
   var finalLocate="";
   $(".form-group").removeClass("sgo-error");
   $(".validation_msg").html("");

   $ngo = $('#tongo').val();
   calculatetotal();
   $('#tongo').val($ngo);
   var t=parseInt($('#amount_to_project').val());
   var ngo=$("#tongo").val();
   var tempNgo=parseFloat(t)+parseFloat(ngo);
   $('#with-ngo-total').html(tempNgo+' €');
   $("#chargeprice").val(tempNgo);
   $("#amount_to_ngo").val(ngo);
   // return false;


   var type=$('#doner_type').val();
   if(type=='particular')
   {
      $("#pills_company").find(".form-group").addClass("exclude");
   }
   else
   {
      $("#pills-particular").find(".form-group").addClass("exclude");
   }
   $(".newsletter-row").addClass("exclude");

            if($('#doner_type').val() == 'particular')
            {
                var flag_go = 0;
                var amount = $('#chargeprice').val();
                var contact_email = $('#contact_email').val();
                var contact_first_name = $('#contact_first_name').val();
                var contact_last_name = $('#contact_last_name').val();
                var contact_address = $('#contact_address').val();
                var contact_postalcode = $('#contact_postalcode').val();
                var contact_village = $('#contact_village').val();
                var contact_country = $('#contact_country').val();
                var donation_date = $('#datepicker1').val();

                var flag = 0;

                 

                 if($.trim(contact_email) == '')
                 {
                  $('#contact_email_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  
                  if(flag_go<1)
                  {
                     finalLocate=$('#contact_email_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                  if(!regex.test(contact_email)) {
                    $('#contact_email_id').html('<small style="color:red">Saisissez une adresse e-mail valide</small>');
                    flag = 1;
                    
                    if(flag_go<1)
                     {
                        finalLocate=$('#contact_email_id');
                        flag_go=flag_go+1;
                     }
                     
                    
                  }else{
                    $('#contact_email_id').html('');
                  }
                 }

                 if($.trim(contact_address) == '')
                 {
                  $('#contact_address_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#contact_address_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#contact_address_id').html('');
                 }

                 if($.trim(contact_first_name) == '')
                 {
                  $('#contact_first_name_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#contact_first_name_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#contact_first_name_id').html('');
                 }

                 if($.trim(contact_last_name) == '')
                 {
                  $('#contact_last_name_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#contact_last_name_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#contact_last_name_id').html('');
                 }

                 if($.trim(contact_address) == '')
                 {
                  $('#contact_address_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#contact_address_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#contact_address_id').html('');
                 }

                 if($.trim(contact_postalcode) == '')
                 {
                  $('#contact_postalcode_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#contact_postalcode_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#contact_postalcode_id').html('');
                 }

                 if($.trim(contact_village) == '')
                 {
                  $('#contact_village_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#contact_village_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#contact_village_id').html('');
                 }

                 if($.trim(contact_country) == '')
                 {
                  $('#contact_country_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#contact_country_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#contact_country_id').html('');
                 }

                 if($.trim(amount) == '')
                 {
                  $('#exact_amount_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#exact_amount_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#exact_amount_id').html('');
                 }

                 // if($("#flexCheckDefault").prop('checked') == false)
                 // {
                 //  $('#flexCheckDefault_id').html('<small style="color:red">Champs requis</small>');
                 //  flag = 1;
                 // }
                 // else
                 // {
                 //  $('#flexCheckDefault_id').html('');
                 // }

                 // if($("#flexCheckChecked").prop('checked') == false)
                 // {
                 //  $('#flexCheckChecked_id').html('<small style="color:red">Champs requis</small>');
                 //  flag = 1;
                 //  if(flag_go<1)
                 //  {
                 //     finalLocate=$('#flexCheckChecked_id');
                 //     flag_go=flag_go+1;
                 //  }
                  
                 // }
                 // else
                 // {
                 //  $('#flexCheckChecked_id').html('');
                 // }


                 if($.trim(donation_date) == '')
                 {
                  $('#donation_date_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#donation_date_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#donation_date_id').html('');
                 }


                

                 // if(flag == 1)
                 // {
                 //    return false;
                 // }
                 console.log(finalLocate);
                 if(flag_go>0)
                  {
                     var pos = $(finalLocate).offset().top;
                     pos=pos-30;
                     $('body, html').animate({scrollTop: pos},2000);
                     return false;
                  }
                  if(flag==0)
                  {
                    $("#donation_add").trigger("submit");
                     
                  }
            }
            else
            {
                
                var flag_go = 0;
                var amount = $('#chargeprice').val();
                var contact_email = $('#contact_email_en').val();
                var contact_first_name = $('#contact_first_name_en').val();
                var contact_last_name = $('#contact_last_name_en').val();
                var contact_company = $('#company_name').val();
                var contact_address = $('#contact_address_en').val();
                var contact_postalcode = $('#contact_postalcode_en').val();
                var contact_village = $('#contact_village_en').val();
                var contact_country = $('#contact_country_en').val();
                var donation_date = $('#datepicker1').val();

                var flag = 0;



                 if($.trim(contact_company) == '')
                 {
                  $('#company_name_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#company_name_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#company_name_id').html('');
                 }

                 if($.trim(contact_email) == '')
                 {
                  $('#contact_email_en_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#contact_email_en_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                  if(!regex.test(contact_email)) {
                    $('#contact_email_en_id').html('<small style="color:red">Saisissez une adresse e-mail valide</small>');
                    flag = 1;
                    if(flag_go<1)
                     {
                        finalLocate=$('#contact_email_en_id');
                        flag_go=flag_go+1;
                     }
                     
                  }else{
                    $('#contact_email_en_id').html('');
                  }
                 }

                 if($.trim(contact_address) == '')
                 {
                  $('#contact_address_en_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#contact_email_en_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#contact_address_en_id').html('');
                 }

                 /*if($.trim(contact_first_name) == '')
                 {
                  $('#contact_first_name_en_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#contact_first_name_en_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#contact_first_name_en_id').html('');
                 }

                 if($.trim(contact_last_name) == '')
                 {
                  $('#contact_last_name_en_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#contact_last_name_en_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#contact_last_name_en_id').html('');
                 }*/

                 if($.trim(contact_address) == '')
                 {
                  $('#contact_address_en_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#contact_address_en_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#contact_address_en_id').html('');
                 }

                 if($.trim(contact_postalcode) == '')
                 {
                  $('#contact_postalcode_en_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#contact_postalcode_en_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#contact_postalcode_en_id').html('');
                 }

                 if($.trim(contact_village) == '')
                 {
                  $('#contact_village_en_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#contact_village_en_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#contact_village_en_id').html('');
                 }

                 if($.trim(contact_country) == '')
                 {
                  $('#contact_country_en_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#contact_country_en_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#contact_country_en_id').html('');
                 }

                 if($.trim(amount) == '')
                 {
                  $('#exact_amount_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#exact_amount_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#exact_amount_id').html('');
                 }

                 // if($("#flexCheckDefault").prop('checked') == false)
                 // {
                 //  $('#flexCheckDefault_id').html('<small style="color:red">Champs requis</small>');
                 //  flag = 1;
                 //  if(flag_go<1)
                 //  {
                 //     finalLocate=$('#flexCheckDefault_id');
                 //     flag_go=flag_go+1;
                 //  }
                  
                 // }
                 // else
                 // {
                 //  $('#flexCheckDefault_id').html('');
                 // }

                 // if($("#flexCheckChecked").prop('checked') == false)
                 // {
                 //  $('#flexCheckChecked_id').html('<small style="color:red">Champs requis</small>');
                 //  flag = 1;
                 //  if(flag_go<1)
                 //  {
                 //     finalLocate=$('#flexCheckChecked_id');
                 //  }
                 //  flag_go=flag_go+1;
                 // }
                 // else
                 // {
                 //  $('#flexCheckChecked_id').html('');
                 // }

                 if($.trim(donation_date) == '')
                 {
                  $('#donation_date_id').html('<small style="color:red">Champs requis</small>');
                  flag = 1;
                  if(flag_go<1)
                  {
                     finalLocate=$('#donation_date_id');
                     flag_go=flag_go+1;
                  }
                  
                 }
                 else
                 {
                  $('#donation_date_id').html('');
                 }

                 // if(flag == 1)
                 // {
                 //    return false;
                 // }
                 console.log(finalLocate);

                  if(flag_go>0)
                  {
                     var pos = $(finalLocate).offset().top;
                     pos=pos-30;
                     $('body, html').animate({scrollTop: pos},2000);
                     return false;
                  }
                  if(flag==0)
                  {
                    $("#donation_add").trigger("submit");
                     
                  }
            }

   //    $(".form-group").each(function () {
   //        if($(this).hasClass("exclude"))
   //        {
   //          return true;
   //        }
   //       var temp="";
   //       var i=$(this).find("input[type='text']").length;
   //       var p=$(this).find("input[type='tel']").length;
   //       var e=$(this).find("input[type='email']").length;
   //       var u=$(this).find("input[type='url']").length;
   //       var n=$(this).find("input[type='number']").length;
   //       var c=$(this).find("input[type='checkbox']").length;
   //       var t=$(this).find("textarea").length;
   //       var s=$(this).find("select").length;
   //       var d=$(this).find(".dropzone-previews-cwd").length;
   //       var f=$(this).find(".image_name").length;
   //       if(i<0 || p<0 || e<0 || u<0 || n<0 || t<0 || s<0 || d<0 || f<0 || c<0)
   //       {
   //          return true;
   //       }
         
   //       var locate=$(this);
   //       if(i>0 || p>0 || e>0 || u>0 || n>0 || t>0 || s>0 || d>0 || f>0 || c>0)
   //       {
   //          if(i>0)
   //          {
   //             temp=$(this).find("input").val();
   //            if($.trim(temp) == '')
   //            {
   //               $(this).find(".validation_msg").html('<small style="color:red">Champs requis</small>');
   //            }
   //          }
   //          if(t>0)
   //          {
   //             temp=$(this).find("textarea").val();
   //            if($.trim(temp) == '')
   //            {
   //               $(this).find(".validation_msg").html('<small style="color:red">Champs requis</small>');
   //            }
   //          }
   //          if(n>0)
   //          {
   //             temp=$(this).find("input").val();
   //            if($.trim(temp) == '')
   //            {
   //               $(this).find(".validation_msg").html('<small style="color:red">Champs requis</small>');
   //            }
   //          }
   //          if(s>0)
   //           {
                  
   //               temp=$(this).find("select").val();
   //               if($.trim(temp) == '')
   //               {
   //                  $(this).find(".validation_msg").html('<small style="color:red">Champs requis</small>');
   //               }
               
   //           }
   //          if(e>0)
   //          {
               
   //             temp=$(this).find("input[type='email']").val();
               
   //             var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
   //             if(!regex.test( temp ))
   //             {
   //                $(this).addClass("sgo-error");
   //                  $(this).find(".validation_msg").html('<small style="color:red">Saisissez une adresse e-mail valide</small>');
                    
   //                if(flag<1)
   //                {
   //                   finalLocate=locate;
   //                }
   //                flag=flag+1;
                  
   //             }
   //          }
   //          if(p>0)
   //           {
   //             temp=$(this).find("input[type='tel']").val();
   //             var phoneReg = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
   //             if(!phoneReg.test( temp ))
   //             {
   //                $(this).addClass("sgo-error");
   //                  $(this).find(".validation_msg").html('<small style="color:red">Entrez un numéro de téléphone valide</small>');
   //                if(flag<1)
   //                {
   //                   finalLocate=locate;
   //                }
   //                flag=flag+1;
   //             }
   //           }
   //           if(u>0)
   //           {
   //             temp=$(this).find("input[type='url']").val();
               
               
   //             var regex = /^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;
   //             if(!regex.test( temp ))
   //             {
   //                $(this).addClass("sgo-error");
   //                  $(this).find(".validation_msg").html('<small style="color:red">Entrez une URL valide</small>');
   //                if(flag<1)
   //                {
   //                   finalLocate=locate;
   //                }
   //                flag=flag+1;
   //             }
   //           }
   //           if(c>0)
   //           {
   //             $(this).find("input[type='checkbox']:checked").each(function() {
   //               temp='abc';
   //             });
   //             if(temp.length<1)
   //             {
   //                $(this).find(".validation_msg").html('<small style="color:red">Champs requis</small>');
   //                if(flag<1)
   //                {
   //                   finalLocate=locate;
   //                }
   //                flag=flag+1;
   //             }
   //           }
   //          if(temp.length<1)
   //          {
   //             $(this).addClass("sgo-error");
   //             if(flag<1)
   //             {
   //                finalLocate=locate;
                  
   //             }
   //             flag=flag+1;
   //          }
   
   //       }
   
   
   
   //    });
   // if(flag>0)
   //          {
   //             var pos = $(finalLocate).offset().top;
   //             pos=pos-30;
   //             $('body, html').animate({scrollTop: pos},2000);
   //             return false;
   //          }
   // if(flag==0)
   //         {
   //            $("#donation_add").trigger("submit");
               
   //         }
      console.log("hellow");
   });
</script>
@endpush