@extends('admin.layouts.after-login-layout')
@section('unique-content')
<div class="content-wrapper">
   <style>
      input[type="checkbox"]:disabled {
         border: #0000000;
}
   </style>
<!-- Content Header (Page header) -->
<section class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Donation Details</h1>
         </div>
         <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
               <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{route('admin.project-management.project-list')}}">Projects</a></li>
                  <li class="breadcrumb-item"><a href="{{route('admin.project-management.donation-list',encrypt($donation->project_id, Config::get('Constant.ENC_KEY')))}}">Donation List</a></li>
                  <li class="breadcrumb-item"> Donation Details</li>
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
                     <div class="col-sm-8">
                        <div  class="row">
                           <label class="col-sm-4">Projet :</label>
                           <div  class="col-sm-8">{{$project['project_title']}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Montant :</label>
                           <div  class="col-sm-8">€{{$donation->amount}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Montant du projet :</label>
                           <div  class="col-sm-8">€{{$donation->amount_to_project}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Montant pour FFD :</label>
                           <div  class="col-sm-8">€{{$donation->amount_to_ngo}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Statut du don :</label>
                           <div  class="col-sm-8">{!! $stats !!}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Stripe ID :</label>
                           <div  class="col-sm-8">{{$stripe_id}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Moyen de paiement :</label>
                           <div  class="col-sm-8">
                              @if($donation->donation_method == 'online')
                                 En ligne
                              @endif

                              @if($donation->donation_method == 'cheque')
                                 Chèque
                              @endif

                              @if($donation->donation_method == 'cash')
                                 Espèces
                              @endif

                              @if($donation->donation_method == 'other')
                                 Autre
                              @endif
                           </div>
                        </div>
                        
                        <div  class="row">
                           <label class="col-sm-4">Type de don :</label>
                           
                           <div  class="col-sm-8">{{$donation_type}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Type de donneur :</label>
                           @php
                            $t=$donation->doner_type;
                            $trans=array('Particular'=>'Particulier','Enterprise'=>'Entreprise');
                            
                           @endphp
                           <div  class="col-sm-8">{{ucfirst($trans[$t])}}</div>
                        </div>
                     </div>
                     <div class="col-sm-8" style="margin-top: 15px; border-color: lightgrey; border-width: 1px;">
                        <div  class="row">
                           <div class="col-sm-12"><hr></div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Nom de l'entreprise :</label>
                           <div  class="col-sm-8">{{$donation->company_name}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Inscrit à la newsletter :</label>
                           <div  class="col-sm-8">{{ $donation->is_newsletter ? 'Yes': 'No' }}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Email du contact :</label>
                           <div  class="col-sm-8">{{$donation->contact_email}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Nom et prénom :</label>
                           <div  class="col-sm-8">{{$donation->contact_first_name}} {{$donation->contact_last_name}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Adresse de contact :</label>
                           <div  class="col-sm-8">{{$donation->contact_address}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Adresse supplémentaire de contact :</label>
                           <div  class="col-sm-8">{{$donation->contact_additional_address}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Code postal :</label>
                           <div  class="col-sm-8">{{$donation->contact_postalcode}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Ville :</label>
                           <div  class="col-sm-8">{{$donation->contact_village}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Pays :</label>
                           <div  class="col-sm-8">{{$donation->contact_country}}</div>
                        </div>
                        <div  class="row">
                           <label class="col-sm-4">Comment avez-vous connu le Fonds ? :</label>
                           <div class="col"><!--  form-check-inline reallocation-donation -->
                             <div class="form-check">
                                 <input class="form-check-input" disabled type="checkbox" id="is_internet" value="1" {{ $donation->is_internet=='1' ? 'checked': '' }}>
                                 <label class="form-check-label" for="is_internet">Internet</label>
                               </div> <!-- form-check-inline reallocation-donation -->
                               <div class="form-check">
                                 <input class="form-check-input" disabled type="checkbox" id="is_brochure" value="1" {{ $donation->is_brochure=='1' ? 'checked': '' }}>
                                 <label class="form-check-label" for="is_brochure">Bouche à oreille</label>
                               </div> <!-- form-check-inline reallocation-donation -->
                               <div class="form-check">
                                 <input class="form-check-input" disabled type="checkbox" id="is_email" value="1" {{ $donation->is_email=='1' ? 'checked': '' }}>
                                 <label class="form-check-label" for="is_email">Emailing</label>
                               </div> <!-- form-check-inline reallocation-donation -->
                               <div class="form-check">
                                 <input class="form-check-input" disabled type="checkbox" id="is_ad" value="1" {{ $donation->is_ad=='1' ? 'checked': '' }}>
                                 <label class="form-check-label" for="is_ad">Publicité</label>
                               </div> <!-- form-check-inline reallocation-donation -->
                               <div class="form-check">
                                 <input class="form-check-input" disabled type="checkbox" id="is_others" value="1" {{ $donation->is_others=='1' ? 'checked': '' }}>
                                 <label class="form-check-label" for="is_others">Autre</label>
                               </div>





                           </div>
                        </div>

                        @if($donation->is_others=='1')

                               <div  class="row">
                                    <label class="col-sm-4">Précisez :</label>
                                    <div  class="col-sm-8">{{$donation->other_reason}}</div>
                               </div>

                        @endif

                        <div  class="row">
                           <label class="col-sm-4">Anonyme ? :</label>
                           @php
                            $t=$donation->is_anonymous;
                            $trans=array('yes'=>'Oui','no'=>'Non');
                           @endphp
                           <div  class="col-sm-8">{{ucfirst($trans[$t])}}</div>
                        </div>

                        <div  class="row">
                           <label class="col-sm-4">Réaffectation du don ? :</label>
                           @php
                            $t=$donation->want_refund;
                            $trans=array('no'=>'Oui','yes'=>'Non');
                           @endphp
                           <div  class="col-sm-8">{{ucfirst($trans[$t])}}</div>
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