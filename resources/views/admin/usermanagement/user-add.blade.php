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
              <li class="breadcrumb-item"><a href="{{route('admin.user-management.user.list')}}">User List</a></li>
              <li class="breadcrumb-item active">User Create</li>
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
                                <form action="{{route('admin.user-management.user.add')}}" method="POST"  id="Create_User">
                                        {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-10">
                                                <div class="form-group row">
                                                    <label for="Title" class="col-sm-2 col-form-label">Name : <span class="error">*</span></label>
                                                    <div class="col-sm-10">
                                                    <input type="text" name="name" id="name" class="form-control" placeholder=" Name" title="Name">
                                                    
                                                    </div>
                                                </div>
                                                
                                                    <div class="form-group row">
                                                    <label for="Title" class="col-sm-2 col-form-label">Email : <span class="error">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="email" id="email" class="form-control" placeholder=" Email" title="Email">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                    <label for="Title" class="col-sm-2 col-form-label">Phone : <span class="error">*</span></label>
                                                       <div class="col-sm-10">
                                                        <input type="text" name="phone" id="phone" class="form-control" placeholder=" Phone" title="Phone">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                    <label for="Title" class="col-sm-2 col-form-label">Password : <span class="error">*</span></label>
                                                       <div class="col-sm-10">
                                                        <input type="password" name="password" id="password" class="form-control" placeholder=" Password" title="Password">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                    <label for="Title" class="col-sm-2 col-form-label">Confirm Password : <span class="error">*</span></label>
                                                        <div class="col-sm-10">
                                                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" title="Confirm Password">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                    <label for="Title" class="col-sm-2 col-form-label">Roles: <span class="error">*</span></label>
                                                       <div class="col-sm-10">
                                                        <select name='role_id' id="role_id"class="form-control">
                                                                <option value="">Select</option>
                                                                @foreach ($allRole as $key => $row)
                                                                    <option value="{{  $row->id  }}">{{ $row->name }}</option>
                                                                @endforeach  
                                                
                                                        </select>
                                                        </div>
                                                    </div>

                                                    {{-- <div class="form-group row">
                                                    <label for="Title" class="col-sm-2 col-form-label">User Type : <span class="error">*</span></label>
                                                    <div class="col-sm-10">
                                                    <select class="form-control select2 select2-danger" name="usertype" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                        <option value="">Choose option</option>
                                                        <option value="app" >App User</option>
                                                        <option value="subadmin">Sub Admin</option>
                                                    </select>
                                                    </div>
                                                    </div> --}}

                                                    <div class="form-group row">
                                                    <label for="Title" class="col-sm-2 col-form-label">Status : <span class="error">*</span></label>
                                                    <div class="col-sm-10">
                                                    <select class="form-control select2 select2-danger" name="status" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                        <option value="">Choose option</option>
                                                        <option value="A" selected >Active</option>
                                                        <option value="I">Inactive</option>
                                                    </select>
                                                    </div>
                                                    </div> 
                                            <div class="">
                                                        <div class="">
                                                        <a class="btn btn-primary back_new" href="{{route('admin.user-management.user.list')}}">Back</a>
                                                        <button id="" type="submit" class="btn btn-success submit_new">Add</button>
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