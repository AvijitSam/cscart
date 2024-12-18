@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Functionality Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.module-management.functionality.list')}}">Functionality List</a></li>
              <li class="breadcrumb-item active">Functionality Edit</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
          <!-- SELECT2 EXAMPLE -->
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
                      <form action="{{route('admin.module-management.function-editSubmit',[$encryptCode])}}" method="POST"  id="edit_function">
                            {{ csrf_field() }}
                          <div class="row">
                              <div class="col-md-6">
                              
                                      <div class="form-group">
                                          <label>Module Name*</label>
                                          <select name='module_id'id="module_id" class="form-control">
                                              <option value="">Select</option>
                                                @foreach ($allModule as $key => $row)
                                                <option value="{{  $row->id  }}" @if($row->id == $details->module_id ){{'selected'}} @endif>{{ $row->module_name}}</option>
                                                @endforeach  
                            
                                        </select>
                                      </div>
                                      <div class="form-group">
                                          <label>Function Name*</label>
                                          <input type="text" name="function_name" id="function_name" class="form-control" value="{{$details->function_name}}" placeholder="Function Name" title="Function Name">
                                      </div>
                                  
                                      <div class="form-group">
                                          <label>Function Description* </label>
                                          <textarea name="function_description" id="function_description" cols="30" rows="5" class="form-control"  title="Function Description" placeholder="Function Description">{{$details->function_description}}</textarea>
                                      </div>
                                      <div class="form-group">
                                          <label>Slug* </label>
                                          <input type="text" name="slug" id="slug" class="form-control" value="{{$details->slug}}" placeholder="slug" title="slug">
                                      </div>
                                    
                                      <div class="form-group">
                                        <label>Status*</label>
                                        <select class="form-control select2 select2-danger" name="status" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                              <option value="">Choose option</option>
                                              <option value="A" {{ $details->status == 'A' ? 'selected' : '' }}>Active</option>
                                              <option value="I" {{ $details->status == 'I' ? 'selected' : '' }} >Inactive</option>
                                        </select>
                                      </div>
                                      <div class="card-footer">
                                        <div class="">
                                            <a class="btn btn-primary back_new" href="{{route('admin.module-management.functionality.list')}}">Back</a>
                                            <button id="" type="submit" class="btn btn-success submit_new">Update</button>
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
    </section>
    
</div>
      

         
         
              
             

                

@endsection      