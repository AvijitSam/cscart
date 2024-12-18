@extends('admin.layouts.after-login-layout')



@section('unique-content')

    <div class="content-wrapper">

        <div class="content-header">

            <div class="container-fluid">

                <div class="row mb-2">

                    <div class="col-sm-6">

                        <h1 class="m-0 text-dark">Gestion des porteurs de projets
                        
                        </h1>

                    </div><!-- /.col -->

                    <div class="col-sm-6">

                        <ol class="breadcrumb float-sm-right">

                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>

                            <li class="breadcrumb-item active"> Liste des porteurs de projets</li>

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



                                    <h3 class="card-title add-right">

                                        

                                            <a href="{{route('admin.user-management.user-add')}}">Ajouter un porteur de projet <i class="fa fa-plus-square" aria-hidden="true"></i></a>

                                        

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

                                <table class="table table table-bordered table-striped" id="site-user-table">

                                    <thead>

                                    <tr>

                                        <th>Prénom</th>

                                        <th>Nom de famille</th>

                                        
                                        <th>E-mail</th>

                                         

                                        <th>Actif</th>

                                        

                                        <th>Créé le</th>
                                        <th>Nb projets</th>
                                        

                                        <th>Action</th>

                                        

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

                   oTable = $('#site-user-table').DataTable({

                       "aaSorting": [],

                       processing: true,

                       "iDisplayLength": 50,

                       "language": {
                        url: '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json',

                        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},

                       serverSide: true,

                       ajax: {

                           url: '{!! route("admin.user-management.site.user.customer.list.table") !!}',

                           data: function (d) {

                               d.type = $('select[name=type]').val();

                           }

                       },

                       columns: [

                           //{data: 'fullname', name: 'name'},

                           // {data: 'fullname', name: 'fullname'},

                           {data: 'first_name', name: 'first_name'},

                           {data: 'last_name', name: 'last_name'},

                           

                           {data: 'email', name: 'email'},


                           

                           {data: 'user_status', name: 'user_status', orderable: false, searchable: false},

                           

                           {data: 'created_time', name: 'created_time'},
                           {data: 'project_count', name: 'project_count'},
                           

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

                        title: "Es-tu sûr?",

                        text: "Une fois supprimé, vous ne pourrez plus le récupérer !",

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

            $(document).on('click','.changeStatus',function(e){

                e.preventDefault();

                let redirectUrl= $(this).data('redirect-url');

                var btnId=$(this).attr('id');

                    swal({

                        title: "Es-tu sûr?",

                        text: "Voulez-vous changer le statut ?",

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

                                    //alert(response);

                                    if(response.has_error == 0){

                                        $(document).Toasts('create', {

                                        class: 'bg-info', 

                                        title: 'Succès',

                                        body: response.msg,

                                        delay: 3000,

                                        autohide:true

                                    })

                                        if($('#'+btnId).hasClass('isinactive')){

                                            $('#'+btnId).removeClass('isinactive');

                                            $('#'+btnId).addClass('isactive');

                                            $('#'+btnId).html('<i class="fas fa-check"></i>');

                                        } else {

                                            $('#'+btnId).removeClass('isactive');

                                            $('#'+btnId).addClass('isinactive');

                                            $('#'+btnId).html('<i class="fas fa-times"></i>');

                                        }

                                    } else {

                                        alert('Quelque chose s\'est mal passé ');

                                    }

                                }

                            });

                        } 

                    });

            })





            

    </script>

    @endpush





    