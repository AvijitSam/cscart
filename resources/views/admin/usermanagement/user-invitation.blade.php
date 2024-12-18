@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.user-management.site.user.list')}}">App Member List</a></li>
              <li class="breadcrumb-item active">Member Dating Invitation</li>
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
                                            <div class="col-sm-6">
                                                <div  class="row">
                                                    <label class="col-sm-4">First Name :</label>
                                                    <div  class="col-sm-8">{{$getDetail->first_name}}</div>
                                                </div>
                                                <div  class="row">
                                                    <label class="col-sm-4">Last Name :</label>
                                                    <div  class="col-sm-8">{{$getDetail->last_name}}</div>
                                                </div>
                                                
                                                
                                            </div>
                                            <div class="col-sm-6">
                                                

                                            <div  class="row">
                                                    <label class="col-sm-4">Email :</label>
                                                    <div  class="col-sm-8">{{$getDetail->email}}</div>
                                                </div>
                                                <div  class="row">
                                                    <label class="col-sm-4">Phone :</label>
                                                    <div  class="col-sm-8">{{$getDetail->phone}}
                                                    @if($getDetail->phone_verified == '1')
                                                        <small style="color:green"><b>&nbsp; &checkmark; &nbsp;Verified</b></small>
                                                    @else
                                                    <small style="color:red"><b>&nbsp; X &nbsp;Non-verified</b></small>
                                                    @endif
                                                    </div>
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
  
    <!-- <section class="content">
        <div class="container-fluid"> -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Invitation Detail</h3>
                        </div>
                                    

                        <div class="card-body">
                            @if(count($invitationDetails) > 0)
                                @foreach($invitationDetails as $invitationDetail)
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div  class="row">
                                                <div  class="col-sm-12" style="text-align:center;">
                                                    @if($invitationDetail['restaurant_image'] == NULL)
                                                        <image src="{{ asset('/admin/images/no-image-found.jpg') }}" height="150" width="150"/>
                                                    @else
                                                        <image src="{{ asset('/admin/upload/restaurant/thumbnail') }}/{{ $invitationDetail['restaurant_image'] }}" height="150" width="150"/>
                                                    @endif
                                                </div>
                                            </div>

                                            <div  class="row">
                                                <div  class="col-sm-12" style="text-align:center;">
                                                    <b>{{$invitationDetail['restaurant_name']}}</b> ({{$invitationDetail['restaurant_rate']}})
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-sm-8">
                                                                    <div  class="row">
                                                                        @if($invitationDetail['flag'] == 'Send')
                                                                            <label class="col-sm-4">Send to :</label>
                                                                            <div  class="col-sm-8">{{$invitationDetail['send_to']}}</div>
                                                                        @else
                                                                            <label class="col-sm-4">Received from :</label>
                                                                            <div  class="col-sm-8">{{$invitationDetail['send_by']}}</div>
                                                                        @endif 
                                                                    </div>
                                                                    <div  class="row">
                                                                        <label class="col-sm-4">Email :</label>
                                                                        <div  class="col-sm-8">{{$invitationDetail['email']}}</div>
                                                                    </div>
                                                                    <div  class="row">
                                                                        <label class="col-sm-4">Phone :</label>
                                                                        <div  class="col-sm-8">{{$invitationDetail['phone']}}</div>
                                                                    </div>
                                                                    <div  class="row">
                                                                        <label class="col-sm-4">Invitation Status :</label>
                                                                        <div  class="col-sm-8">
                                                                            @switch($invitationDetail['date_status'])
                                                                                @case(0)
                                                                                    <b><span style="color:yellow;">Pending</span> (From {{$invitationDetail['action_date']}})</b>
                                                                                    @break

                                                                                @case(1)
                                                                                    <b><span style="color:green;">Accepted</span>  (On {{$invitationDetail['action_date']}})</b>
                                                                                    @break

                                                                                @case(2)
                                                                                    <b><span style="color:red;">Rejected</span>  (On {{$invitationDetail['action_date']}})</b>
                                                                                    @break

                                                                                @case(3)
                                                                                    <b><span style="color:blue;">Cancel</span>  (On {{$invitationDetail['action_date']}})</b>
                                                                                    @break

                                                                                @default
                                                                                    <span></span>
                                                                            @endswitch
                                                                        </div>
                                                                    </div>

                                        </div>
                                    </div>
                                @endforeach

                            @else
                                No dating invitation record found
                            @endif
                            
                        </div>
                            
                                          
                    </div>
                </div>
            </div>
        <!-- </div>
    </section> -->
    
  

            
            
           
    
        </div>
    </section>

    
    

    
    
  </div>
  @endsection

  
      

         
         
              
             

                

      