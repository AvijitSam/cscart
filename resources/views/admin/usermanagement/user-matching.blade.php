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
              <li class="breadcrumb-item active">Member Matching Detail</li>
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
                                            </div>
                                            <div class="col-sm-6">
                                                

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
    @if(count($topMatches) > 0)
    <!-- <section class="content">
        <div class="container-fluid"> -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Top Matches</h3>
                        </div>
                                    

                        <div class="card-body">

                            <!-- <div class="row">
                                <div class="col-md-10"> -->
                                                @foreach($topMatches as $topMatch)
                                                
                                                    
                                                    <div class="row" style="border-bottom: 1px solid #c8969a; margin-top:20px;">
                                                        <div class="col-sm-6">
                                                                    <div  class="row">
                                                                        <label class="col-sm-4">Name :</label>
                                                                        <div  class="col-sm-8">{{$topMatch['lead_first_name']}} {{$topMatch['lead_last_name']}}</div>
                                                                    </div>
                                                                    <div  class="row">
                                                                        <label class="col-sm-4">Age :</label>
                                                                        <div  class="col-sm-8">{{$topMatch['lead_age']}} Years</div>
                                                                    </div>
                                                                    <div  class="row">
                                                                        <label class="col-sm-4">Details :</label>
                                                                        <div  class="col-sm-8">{{$topMatch['lead_profile_description']}}</div>
                                                                    </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                                    <div  class="row">
                                                                        <label class="col-sm-4">Race :</label>
                                                                        <div  class="col-sm-8">{{$topMatch['lead_race']}}</div>
                                                                    </div>
                                                                    <div  class="row">
                                                                        <label class="col-sm-4">Gender :</label>
                                                                        <div  class="col-sm-8">{{$topMatch['lead_gender']}}</div>
                                                                    </div>
                                                                    <div  class="row">
                                                                        <label class="col-sm-4">Sextual Orientation :</label>
                                                                        <div  class="col-sm-8">{{$topMatch['lead_sexual_orientation']}}</div>
                                                                    </div>
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
                                        <h3 class="card-title">Score List</h3>
                                    </div>
                                <!-- /.card-header -->
                            <div class="card-body">

                                <!-- <div class="row">
                                    <div class="col-md-10"> -->
                                        <div class="row">
                                            
                                            <div class="col-sm-12">
                                            <table class="table table table-bordered table-striped" id="user-table">
                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Score</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($memberScores as $memberScore)
                                                @if($memberScore['email']!='')
                                                <tr>
                                                    <td>{{$memberScore['full_name']}}</td>
                                                    <td>{{$memberScore['email']}}</td>
                                                    <td>{{$memberScore['phone']}}</td>
                                                    <td>{{$memberScore['score']}}</td>
                                                </tr>
                                                @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                                
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

  @push('custom-scripts')
            <!-- DataTables -->
            <script src="{{asset('assets//plugins/datatables/jquery.dataTables.min.js')}}"></script>
            <script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
            <script src="{{asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
            <script src="{{asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
            <!-- Sweet alert -->
            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
            <script src="{{asset('assets/plugins/toastr/toastr.min.js')}}"></script>
           <script>
               jQuery(document).ready(function() {
                    //TableData.init();
                    $('#user-table').DataTable();
                });
    </script>
               @endpush
      

         
         
              
             

                

      