@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Module Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.module-management.module.list')}}">Moduel List</a></li>
              <li class="breadcrumb-item active">Module Edit</li>
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
                                <form action="{{route('admin.module-management.editSubmit',[$encryptCode])}}" method="POST"  id="edit_module">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-6">
                                        
                                                <div class="form-group">
                                                    <label>Module Name*</label>
                                                    <input type="text" name="module_name" id="module_name" class="form-control" value="{{$details->module_name}}" placeholder="Module Name" title="Module Name">
                                                </div>
                                            
                                                <div class="form-group">
                                                    <label>Module Description* </label>
                                                    <textarea name="module_description" id="module_description" cols="30" rows="5" class="form-control"  title="Module Description" placeholder="Module Description">{{$details->module_description}}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Slug *</label>
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
                                                      <a class="btn btn-primary back_new" href="{{route('admin.module-management.module.list')}}">Back</a>
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
      </div>
    </section>
    
</div>
      

         
         
              
             

                

@endsection      