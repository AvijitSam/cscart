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
              <li class="breadcrumb-item active">Member Detail</li>
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
                                            <div class="col-sm-4">
                                            @if($getDetail->profile_picture == NULL)
                                                <image src="{{ asset('/admin/images/no-image-found.jpg') }}" height="200" width="200"/>
                                            @else
                                                <image src="{{ asset('/admin/upload/profile/thumbnail') }}/{{ $getDetail->profile_picture }}" height="200" width="200"/>
                                            @endif
                                            </div>
                                            <div class="col-sm-8">
                                                <div  class="row">
                                                    <label class="col-sm-4">First Name :</label>
                                                    <div  class="col-sm-8">{{$getDetail->first_name}}</div>
                                                </div>
                                                <div  class="row">
                                                    <label class="col-sm-4">Last Name :</label>
                                                    <div  class="col-sm-8">{{$getDetail->last_name}}</div>
                                                </div>
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
                                                <div  class="row">
                                                    <label class="col-sm-4">Age :</label>
                                                    <div  class="col-sm-8">{{$age}} Years</div>
                                                </div>

                                                <div  class="row">
                                                    <label class="col-sm-4">Date of birth :</label>
                                                    <div  class="col-sm-8">{{$getDetail->birth_date}}</div>
                                                </div>
                                                <div  class="row">
                                                    <label class="col-sm-4">Gender :</label>
                                                    @foreach($getGender as $gg)
                                                        @if($gg->id == $getDetail->gender)
                                                        <div  class="col-sm-8">{{$gg->gender}}</div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <div  class="row">
                                                    <label class="col-sm-4">Race :</label>
                                                    @foreach($getRace as $gr)
                                                        @if($gr->id == $getDetail->race)
                                                        <div  class="col-sm-8">{{$gr->race}}</div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <div  class="row">
                                                    <label class="col-sm-4">Personal Description :</label>
                                                    <div  class="col-sm-8">
                                                        @if($getDetail->profile_description == NULL)
                                                            Not available
                                                        @else
                                                            {{$getDetail->profile_description}}
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
    @if(count($getMemberQA) > 0)
    <!-- <section class="content">
        <div class="container-fluid"> -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Q/A Session</h3>
                        </div>
                                    

                        <div class="card-body">

                            <!-- <div class="row">
                                <div class="col-md-10"> -->
                                                @foreach($getMemberQA as $mqa)
                                                
                                                    
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            @foreach($getQuestion as $q)
                                                                @if($q->id == $mqa->question_id)
                                                                    @if($q->question_image == NULL)
                                                                        <image src="{{ asset('/admin/images/no-image-found.jpg') }}" height="75" width="75"/>
                                                                    @else
                                                                        <image src="{{ asset('/admin/upload/question/thumbnail') }}/{{ $q->question_image }}" height="75" width="75"/>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        <div class="col-sm-8">
                                                            @foreach($getQuestion as $q)
                                                                @if($q->id == $mqa->question_id)
                                                                    <div  class="row">
                                                                        <label class="col-sm-4">Question :</label>
                                                                        <div  class="col-sm-8"><b>{{$q->full_question}}</b></div>
                                                                    </div>
                                                                @endif
                                                            @endforeach

                                                            @foreach($getAnswer as $a)
                                                                @if($a->id == $mqa->answer_id)
                                                                    <div  class="row">
                                                                        <label class="col-sm-4">Answer :</label>
                                                                        <div  class="col-sm-8">{{$a->answer_option}}</div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                            
                                                            
                                                        </div>
                                                    </div>
                                                @endforeach
                        </div>
                            
                                            <!-- </div> -->
                                        <!-- </div> -->
                    </div>
                </div>
            </div>
        <!-- </div>
    </section> -->
    
    @endif

            <div class="row">
                <div class="col-lg-12">
                        <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Preferences</h3>
                                    </div>
                                <!-- /.card-header -->
                            <div class="card-body">

                                <!-- <div class="row">
                                    <div class="col-md-10"> -->
                                        <div class="row">
                                            
                                            <div class="col-sm-12">
                                                <div  class="row">
                                                    <label class="col-sm-4">Minimum Age :</label>
                                                    <div  class="col-sm-8">{{$getFilterMinAge}} Years</div>
                                                </div>
                                                <div  class="row">
                                                    <label class="col-sm-4">Maximum Age :</label>
                                                    <div  class="col-sm-8">{{$getFilterMaxAge}} Years</div>
                                                </div>
                                                
                                                <div  class="row">
                                                    <label class="col-sm-4">Gender :</label>
                                                    <div  class="col-sm-8">
                                                        @foreach($getGender as $gg)
                                                            @if(in_array($gg->id, $genderArr))
                                                                {{$gg->gender}},
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div  class="row">
                                                    <label class="col-sm-4">Race :</label>
                                                    <div  class="col-sm-8">
                                                        @foreach($getRace as $gr)
                                                            @if(in_array($gr->id, $raceArr))
                                                            {{$gr->race}},
                                                            @endif
                                                        @endforeach
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
            
           
    
        </div>
    </section>

    
    

    
    
  </div>
  @endsection
      

         
         
              
             

                

      