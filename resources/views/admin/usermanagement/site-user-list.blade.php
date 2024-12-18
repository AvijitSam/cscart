@extends('admin.layouts.after-login-layout')

@section('unique-content')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">User Management</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active"> Loko User List</li>
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
                                <table class="table table table-bordered table-striped" id="app-user-table">
                                    <thead>
                                    <tr>
                                        <th>Display Name</th>
                                        <th>Image</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Coin</th>
                                        <th>Home</th>
                                        <th>School/Work</th>
                                        <th>User Type</th>
                                        <th>Loko Champion</th>
                                        <th>Status</th>
                                        <th>Created On</th>

                                        <!-- <th>Action</th> -->
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
               jQuery(document).ready(function () {
                   oTable = jQuery('#app-user-table').DataTable({
                       "aaSorting": [],
                       processing: true,
                       "language": {
                        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
                       serverSide: true,
                       ajax: {
                           url: '{!! route("admin.user-management.site.user.list.table") !!}',
                           data: function (d) {
                               d.type = jQuery('select[name=type]').val();
                           }
                       },
                       columns: [
                           //{data: 'fullname', name: 'name'},
                           {data: 'display_name', name: 'display_name'},
                           {data: 'image', name: 'image', orderable: false, searchable: false},
                           {data: 'email', name: 'email'},
                           {data: 'phone', name: 'phone'},
                           {data: 'coin_balance', name: 'coin_balance'},
                           {data: 'neighbourhood', name: 'neighbourhood'},
                           {data: 'work_neighbourhood', name: 'work_neighbourhood'},
                           {data: 'role', name: 'role'},
                           {data: 'loko_champion', name: 'loko_champion', orderable: false, searchable: false},
                           {data: 'user_status', name: 'user_status', orderable: false, searchable: false},
                           {data: 'created_at', name: 'created_at'},
                           //{data: 'action', name: 'action', orderable: false, searchable: false}
                       ],
                       drawCallback: function () {
                           // $('[data-toggle=confirmation]').confirmation({
                           //     rootSelector: '[data-toggle=confirmation]',
                           //     container: 'body'
                           // });
                       }
                   });
                   jQuery('select[name="type"]').on("change", function (event) {
                       oTable.draw();
                       event.preventDefault();
                   });
               });
           
            jQuery(document).on('click', '.delete-alert', function (e) {
                    e.preventDefault();
                    var redirectUrl = jQuery(this).data('redirect-url');
                    // alert(redirectUrl)
                    swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this!",
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
            jQuery(document).on('click','.changeStatus',function(e){
                e.preventDefault();
                let redirectUrl= jQuery(this).data('redirect-url');
                var btnId=jQuery(this).attr('id');
                    swal({
                        title: "Are you sure?",
                        text: "Do you want to change the status?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                        })
                    .then((trueResponse ) => {
                        if (trueResponse) {
                            jQuery.ajax({
                                url: redirectUrl,
                                cache: false,
                                success: function(response){
                                    //alert(response);
                                    if(response.has_error == 0){
                                        jQuery(document).Toasts('create', {
                                        class: 'bg-info', 
                                        title: 'Success',
                                        body: response.msg,
                                        delay: 3000,
                                        autohide:true
                                    })
                                        if(jQuery('#'+btnId).hasClass('btn-warning')){
                                            jQuery('#'+btnId).removeClass('btn-warning');
                                            jQuery('#'+btnId).addClass('btn-success');
                                            jQuery('#'+btnId).html('Active');
                                        } else {
                                            jQuery('#'+btnId).removeClass('btn-success');
                                            jQuery('#'+btnId).addClass('btn-warning');
                                            jQuery('#'+btnId).html('Inactive');
                                        }
                                    } else {
                                        alert('Something went wrong ');
                                    }
                                }
                            });
                        } 
                    });
            })


            jQuery(document).on('click','.changeAs',function(e){
                e.preventDefault();
                let redirectUrl= jQuery(this).data('redirect-url');
                var btnId=jQuery(this).attr('id');
                    swal({
                        title: "Are you sure?",
                        text: "Do you want to change the user type?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                        })
                    .then((trueResponse ) => {
                        if (trueResponse) {
                            jQuery.ajax({
                                url: redirectUrl,
                                cache: false,
                                success: function(response){
                                    //alert(response);
                                    if(response.has_error == 0){
                                        jQuery(document).Toasts('create', {
                                        class: 'bg-info', 
                                        title: 'Success',
                                        body: response.msg,
                                        delay: 3000,
                                        autohide:true
                                    })
                                        if(jQuery('#'+btnId).hasClass('btn-warning')){
                                            jQuery('#'+btnId).removeClass('btn-warning');
                                            jQuery('#'+btnId).addClass('btn-success');
                                            jQuery('#'+btnId).html('Yes');
                                        } else {
                                            jQuery('#'+btnId).removeClass('btn-success');
                                            jQuery('#'+btnId).addClass('btn-warning');
                                            jQuery('#'+btnId).html('No');
                                        }
                                    } else {
                                        alert('Something went wrong ');
                                    }
                                }
                            });
                        } 
                    });
            })
    </script>
    @endpush


    