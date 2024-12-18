@extends('admin.layouts.after-login-layout')


@section('unique-content')



    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Home</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Setting</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <!-- SELECT2 EXAMPLE -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{$panel_title}} </h3>
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
                      
                        <form action="{{route('admin.settings')}}"
                              method="POST" id="">
                            
                            @csrf
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">TimeZone :<span class="error">*</span></label>
                                        <div class="col-sm-10">
                                           
                                        <select class="form-control select2bs4  select2-hidden-accessible" name="timezone"
                                                data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                 
                                            <option value="">--Choose Time Zone --</option>
                                           
                                            @foreach($timezones as $timezone)
                                                <option value="{{ $timezone->tz_name }}"@if($timezone->tz_name == $settingObj->timezone ){{'selected'}}@endif>{{ $timezone ->tz_name.'    '.$timezone->current_utc_offset }}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Date Format :<span class="error">*</span></label>
                                        <div class="col-sm-10">
                                        <select class="form-control select2bs4  select2-hidden-accessible" name="date_format"
                                                data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                <option value="">--Choose Data format--</option>
                                                <option value="d-M-Y"@if($settingObj->date_format=='d-M-Y'){{'selected'}}@endif>{{date('d-M-Y')}}</option>
                                                <option value="d/M/Y"@if($settingObj->date_format==  'd/M/Y'){{'selected'}}@endif>{{date('d/M/Y')}}</option>
                                                <option value="Y-m-d"@if($settingObj->date_format == 'Y-m-d'){{'selected'}}@endif>{{date('Y-m-d')}}</option>
                                                <option value="M/d/y"@if($settingObj->date_format == 'M/d/y'){{'selected'}}@endif>{{date('M/d/y')}}</option>
                                                <option value="M/d/yy"@if($settingObj->date_format == 'M/d/yy'){{'selected'}}@endif>{{date('M/d/yy')}}</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Time Format :<span class="error">*</span></label>
                                        <div class="col-sm-10">
                                        <select class="form-control select2bs4  select2-hidden-accessible" name="time_format"
                                                data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                <option value="">--Choose Time format--</option>
                                                <option value="H:i:s"@if($settingObj->time_format== 'H:i:s'){{'selected'}}@endif>{{  \Carbon::now()->setTimezone($settingObj->timezone)->format('H:i:s') }}</option>
                                                <option value="H:i"@if($settingObj->time_format== 'H:i'){{'selected'}}@endif>{{  \Carbon::now()->setTimezone($settingObj->timezone)->format('H:i') }}</option>
                                                <option value="G:i:s A"@if($settingObj->time_format== 'G:i:s A'){{'selected'}}@endif>{{ \Carbon::now()->setTimezone($settingObj->timezone)->format('G:i:s A')}}</option>
                                                <option value="g:i A"@if($settingObj->time_format== 'g:i A'){{'selected'}}@endif>{{ \Carbon::now()->setTimezone($settingObj->timezone)->format('g:i A')}}</option>  
                                        </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="">
                                    <a class="btn btn-primary back_new"
                                       href="{{route('admin.dashboard')}}">Back</a>
                                    <button id="" type="submit" class="btn btn-success submit_new">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('custom-scripts')
    <script !src="">
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
        $(document).on('click','#return_request',function() {
            if($(this).is(":checked")){
                $('#limit_count').show();
            } else {
                $('#limit_count').hide();
            }
        });
    </script>
@endpush
