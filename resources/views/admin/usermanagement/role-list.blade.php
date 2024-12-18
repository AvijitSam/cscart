@extends('admin.layouts.after-login-layout')


@section('unique-content')



    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">User Management</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Role List</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">{{$panel_title}} <a href="{{route('admin.user-management.role-add')}}"
                                                                           class="btn btn-success btn-xs">Role
                                        Create</a></h3>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6"></div>
                                        <div class="col-sm-12 col-md-6"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="roleTable"
                                                   class="table table-bordered table-hover dataTable dtr-inline"
                                                   role="grid" aria-describedby="example2_info">
                                                <thead>
                                                <tr role="row">
                                                    <th class="sorting_desc" tabindex="0" aria-controls="example2"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Rendering engine: activate to sort column ascending"
                                                        aria-sort="descending">Role Name
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="example2"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Browser: activate to sort column ascending">Role
                                                        Description
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="example2"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Engine version: activate to sort column ascending">
                                                        Status
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="example2"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Engine version: activate to sort column ascending">
                                                        Created On
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="example2"
                                                        rowspan="1" colspan="1"
                                                        aria-label="CSS grade: activate to sort column ascending">Action
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($viewRole as $data )
                                                    <tr role="row" class="odd">
                                                        <td class="sorting_1" tabindex="0">{{ $data->role_name }}</td>
                                                        <td class="">{{$data->role_description}}</td>
                                                     
                                                        <td class="">
                                                            @if($data->status == 'A')
                                                                <button type="button" id="status_{{$data->id}}" data-rowid="{{$data->id}}" class="btn btn-block btn-success btn-xs changeStatus"
                                                                 data-redirect-url="{{route('admin.user-management.reset-role-status',['encryptCode'=>encrypt($data->id, Config::get('Constant.ENC_KEY'))])}}">
                                                                Active</button>
                                                            @else
                                                                <button type="button" id="status_{{$data->id}}" data-rowid="{{$data->id}}" data-rowid="" class="btn btn-block btn-warning btn-xs changeStatus" 
                                                                data-redirect-url="{{route('admin.user-management.reset-role-status',['encryptCode'=>encrypt($data->id, Config::get('Constant.ENC_KEY'))])}}">
                                                                Inactive</button>
                                                            @endif
                                                        </td>
                                                        <td class="">{{date($settingObj->date_format,strtotime($data->created_at))}}</td>
                                                            <td class="">
                                                            <div class="btn-group">
                                                                
                                                                <ul class="nav nav-pills ml-auto p-2"> 
                                                                <li class="nav-item dropdown">
                                                                    <a class="nav-link dropdown-toggle btn-primary btn-sm" data-toggle="dropdown" href="#" aria-expanded="false" style="color: white;">
                                                                      Actions <span class="caret"></span>
                                                                    </a>
                                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 40px, 0px);">
                                                                        <a onclick="return confirm('Are you sure ? Want to remove')"
                                                                           href="{{ route('admin.user-management.delete',[$data->id]) }}"
                                                                           title="Delete"
                                                                           class="dropdown-item" abindex="-1">
                                                                            Delete
                                                                        </a>
                                                                        <a href="{{route('admin.user-management.role.permission',['encryptCode'=>encrypt($data->id, \Config::get('Constant.ENC_KEY'))])}}"
                                                                           class="dropdown-item" abindex="-1">Permission</a>
                                                                        <a href="{{route('admin.user-management.edit',[$data->id])}}"
                                                                           class="dropdown-item" abindex="-1">Edit</a>
                                                                    </div>
                                                                 </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

@endsection

@push('custom-scripts')
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <!-- Sweet alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{asset('assets/plugins/toastr/toastr.min.js')}}"></script>
<script>
    $(document).ready( function () {
       var roleTable= $('#roleTable').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "paging":false
        });
} );

$(document).on('click','.changeStatus',function(e){
                e.preventDefault();
                let redirectUrl= $(this).data('redirect-url');
                var rowId = $(this).data('rowid'); 
                    swal({
                        title: "Are you sure?",
                        text: "Do you want to change the status?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                        })
                    .then((trueResponse ) => {
                        if (trueResponse) {
                            $.ajax({
                                url: redirectUrl,
                                cache: false,
                                success: function(response){ 
                                    if(response.has_error == 0){
                                        $(document).Toasts('create', {
                                        class: 'bg-info', 
                                        title: 'Success',
                                        body: response.msg,
                                        delay: 3000,
                                        autohide:true
                                    });   
                                    if ($('#status_'+rowId).hasClass('btn-warning')) {
                                        $('#status_'+rowId).removeClass('btn-warning');
                                        $('#status_'+rowId).addClass('btn-success');
                                        $('#status_'+rowId).html('Active');
                                    } else {
                                        $('#status_'+rowId).removeClass('btn-success');
                                        $('#status_'+rowId).addClass('btn-warning');
                                        $('#status_'+rowId).html('Inactive');
                                    }
                                    
                                    } else {
                                        alert('Something went wrong ');
                                    }
                                }
                            });
                        } 
                    });
            });
</script>
@endpush


