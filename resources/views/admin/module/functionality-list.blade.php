@extends('admin.layouts.after-login-layout')

@section('unique-content')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">@lang('msg.access_control')</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Functionality List</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12">

                        <!-- /.card -->

                        <div class="card card-primary">
                        
                            <div class="card-header">
                                <h3 class="card-title">{{$panel_title}}
                                @if(checkFunctionPermission('module-management.function.add'))
                                <a href="{{route('admin.module-management.function.add')}}"
                                                                           class="btn btn-success btn-xs">Functionality
                                        Create</a>
                                        @endif
                                        </h3>
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
                                <table class="table table table-bordered table-striped" id="functionality-table">
                                    <thead>
                                    <tr>
                                        <th>Module  Name</th>
                                        <th>Function Name</th>
                                        <th>Function Description</th>
                                        <th>Slug</th>
                                        <th>Status</th>
                                        <th><em class="fa fa-cog"></em>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
                <!-- Main row -->
                <div class="row">
                    <!-- Left col -->

                    <!-- /.Left col -->
                    <!-- right col (We are only adding the ID to make the widgets sortable)-->

                    <!-- right col -->
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <div>

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
               $(document).ready(function () {
                   oTable = $('#functionality-table').DataTable({
                       processing: true,
                       serverSide: true,
                       ajax: {
                           url: '{!! route("admin.module-management.functionality.table") !!}',
                           data: function (d) {
                               d.type = $('select[name=type]').val();
                           }
                       },
                       columns: [
                            {data: 'module_data.module_name', name: 'module_name'},
                           {data: 'function_name', name: 'function_name'},
                           {data: 'function_description', name: 'function_description'},
                           {data: 'slug', name: 'slug'},
                           {data: 'status', name: 'status'},
                           {data: 'action', name: 'action', orderable: false, searchable: false}
                       ],
                       drawCallback: function () {
                           // $('[data-toggle=confirmation]').confirmation({
                           //     rootSelector: '[data-toggle=confirmation]',
                           //     container: 'body'
                           // });
                       }
                   });
                   $('select[name="type"]').on("change", function (event) {
                       oTable.draw();
                       event.preventDefault();
                   });
               });
          
       $(document).on('click', '.delete-alert', function (e) {
            e.preventDefault();
            var redirectUrl = $(this).data('redirect-url');
            // alert(redirectUrl)
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this imaginary file!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
            .then((willDelete) => {
                if (willDelete) {
                    window.location.href = redirectUrl;
                } 
            });
        });

        $(document).on('click','.fchangeStatus',function(e){
                e.preventDefault();
                let redirectUrl= $(this).data('redirect-url');
                var btnId=$(this).attr('id');
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
                                    })
                                        if($('#'+btnId).hasClass('btn-warning')){
                                            $('#'+btnId).removeClass('btn-warning');
                                            $('#'+btnId).addClass('btn-success');
                                            $('#'+btnId).html('Active');
                                        } else {
                                            $('#'+btnId).removeClass('btn-success');
                                            $('#'+btnId).addClass('btn-warning');
                                            $('#'+btnId).html('Inactive');
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
