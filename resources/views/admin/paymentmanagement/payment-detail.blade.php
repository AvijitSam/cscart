@extends('admin.layouts.after-login-layout')





@section('unique-content')



<div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1>Détail du paiement</h1>

          </div>

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>

              <li class="breadcrumb-item"><a href="{{route('admin.payment-management.payment-list')}}">Paiement</a></li>

              <li class="breadcrumb-item active">Détail du paiement</li>

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

                                        <h3 class="card-title">Détail du paiement</h3>

                                    </div>

                                <!-- /.card-header -->

                            <div class="card-body">



                                <!-- <div class="row">

                                    <div class="col-md-10"> -->

                                        <div class="row">

                                           

                                            <div class="col-sm-12">

                                                <div  class="row">

                                                    <label class="col-sm-4">Montant :</label>

                                                    <div  class="col-sm-8">{{$show_amount}} €</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Date de paiement :</label>

                                                    <div  class="col-sm-8">{{$payment_date}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Type d'achat :</label>

                                                    <div  class="col-sm-8">
                                                        
                                                        @if($getDetail->purchase_type == 'bulk')
                
                                                            Bulk slot purchase
                
                                                        @else
                
                                                            Single booking
                                                        
                                                        @endif

                                                    </div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Statut de paiement :</label>

                                                    <div  class="col-sm-8">

                                                        @if($getDetail->payment_status == 'success')
                
                                                            Success
                
                                                        @else
                
                                                            Fail
                                                        
                                                        @endif

                                                    </div>

                                                </div>

                                                
                                                

                                            </div>

                                            

                                                

                                            

                                        </div>

                                    

                    

                                    <!-- </div> -->

                                <!-- </div> -->

                            </div>

                        </div> 

                        <div class="card card-primary">

                                    <div class="card-header">

                                        <h3 class="card-title">Détails du client</h3>

                                    </div>

                                <!-- /.card-header -->

                            <div class="card-body">



                                <!-- <div class="row">

                                    <div class="col-md-10"> -->

                                        <div class="row">

                                           

                                            <div class="col-sm-12">

                                                <div  class="row">

                                                    <label class="col-sm-4">Prénom :</label>

                                                    <div  class="col-sm-8">{{$getUser->first_name}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Nom de famille :</label>

                                                    <div  class="col-sm-8">{{$getUser->last_name}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">E-mail :</label>

                                                    <div  class="col-sm-8">{{$getUser->email}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Tlphone :</label>

                                                    <div  class="col-sm-8">{{$getUser->phone}}

                                                   

                                                    </div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Adresse :</label>

                                                    <div  class="col-sm-8">{{$getUser->address}}</div>

                                                </div>
                                                <div  class="row">

                                                    <label class="col-sm-4">Code Postal :</label>

                                                    <div  class="col-sm-8">{{$getUser->postal_code}}</div>

                                                </div>
                                                <div  class="row">

                                                    <label class="col-sm-4">Ville :</label>

                                                    <div  class="col-sm-8">{{$getUser->village}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">TVA intracommunautaire :</label>

                                                    <div  class="col-sm-8">{{$getUser->tva}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">SIRET :</label>

                                                    <div  class="col-sm-8">{{$getUser->siret}}</div>

                                                </div>
                                                

                                            </div>

                                            

                                                

                                            

                                        </div>

                                    

                    

                                    <!-- </div> -->

                                <!-- </div> -->

                            </div>

                        </div> 

                        @if($payment_for == 'booking') 

                        <div class="card card-primary">

                                    <div class="card-header">

                                        <h3 class="card-title">Réservation</h3>

                                    </div>

                                <!-- /.card-header -->

                            <div class="card-body">



                                <!-- <div class="row">

                                    <div class="col-md-10"> -->



                                        <div class="row">

                                            <div class="col-sm-12">

                                                <div  class="row">

                                                    <label class="col-sm-4">Date de réservation :</label>

                                                    <div  class="col-sm-8">{{$booking_date}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Statut :</label>

                                                    <div  class="col-sm-8">
                                                        @if($bookingDetail->booking_status == 'active')
                                                            Active
                                                        @else
                                                            @if($bookingDetail->booking_status == 'inactive')
                                                                Inactive
                                                            @else
                                                                @if($bookingDetail->booking_status == 'cancel')
                                                                    Cancel
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Insérer :</label>

                                                    <div  class="col-sm-8">
                                                        @if($bookingDetail->slot_half == 'first')
                                                            First Half ({{Config::get('yourdata.first_half_start')}} - {{Config::get('yourdata.first_half_end')}})
                                                        @else
                                                            @if($bookingDetail->slot_half == 'second')
                                                                Second Half ({{Config::get('yourdata.second_half_start')}} - {{Config::get('yourdata.second_half_end')}})
                                                            @else
                                                                @if($bookingDetail->slot_half == 'full')
                                                                    Full Day ({{Config::get('yourdata.full_day_start')}} - {{Config::get('yourdata.full_day_end')}})
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </div>

                                                </div>
                                                <div  class="row">

                                                    <label class="col-sm-4">Date d'achat :</label>

                                                    <div  class="col-sm-8">{{$purchase_date}}</div>

                                                </div>
                                                @if($bookingDetail->booking_status == 'cancel')
                                                <div  class="row">

                                                    <label class="col-sm-4">Raison :</label>

                                                    <div  class="col-sm-8">{{$bookingDetail->cancel_reason}}</div>

                                                </div>
                                                @endif

                                                @php /*
                                                @if($bookingDetail->booking_status == 'cancel')

                                                <div  class="row">

                                                    <label class="col-sm-4">Rembourser :</label>

                                                    <div  class="col-sm-8">
                                                        @if($bookingDetail->refund_process == 'money')
                                                            Money Refund
                                                        @else
                                                            Balance Adjust
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                                */ @endphp

                                                
                                            </div>
                                            

                                        </div>

                                    

                    

                                    <!-- </div> -->

                                <!-- </div> -->

                            </div>

                        </div>

                        @endif

                        @if($payment_for == 'bulk') 

                        <div class="card card-primary">

                                    <div class="card-header">

                                        <h3 class="card-title">Détail du crédit</h3>

                                    </div>

                                <!-- /.card-header -->

                            <div class="card-body">



                                <!-- <div class="row">

                                    <div class="col-md-10"> -->



                                        <div class="row">

                                            <div class="col-sm-12">

                                                <div  class="row">

                                                    <label class="col-sm-4">Date d'achat :</label>

                                                    <div  class="col-sm-8">{{$purchase_date}}</div>

                                                </div>

                                                <div  class="row">

                                                    <label class="col-sm-4">Nombre de créneaux :</label>

                                                    <div  class="col-sm-8">
                                                        {{$purchaseDetail->total_slot}}
                                                    </div>

                                                </div>

                                                
                                            </div>
                                            

                                        </div>

                                    

                    

                                    <!-- </div> -->

                                <!-- </div> -->

                            </div>

                        </div>

                        @endif

                        

                                  

                    </div>                    

                        

                </div>

            </div>

    

            

    </section>



    

    



    

    

  </div>

  @endsection