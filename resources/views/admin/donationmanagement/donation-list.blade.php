@extends('admin.layouts.after-login-layout')
@section('unique-content')
<style>
   .swal-modal{width: 511px !important;}
       .swal-button--rollconfirm{background-color: green;}
    .swal-button--roll{background-color: blue;}
    .swal-button--rolltwo{background-color: red;}
    .swal-button--rollthree{background-color: orange;}
</style>
<div class="csrf-token" data-name="csrf-token" data-content="{{ csrf_token() }}"></div>
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Liste des dons 
               </h1>



            </div>
            <!-- /.col -->
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                  <li class="breadcrumb-item active"> Liste des dons</li>
               </ol>
            </div>
            <!-- /.col -->
         </div>
         <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
   </div>
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
                        
                        <!--  <a href="{{route('admin.user-management.user-customer-add')}}"
                           >+ Ajouter un client</a> -->
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
                     <table class="table table table-bordered table-striped" id="site-donation-table">
                        <thead>
                           <tr>
                              <th>Donateur</th>
                              <th>Montant payé</th>
                              <th>Projet</th>
                              <th>Montant alloué au projet</th>
                              <th>Montant pour FFD</th>
                              <th>Statut</th>
                              <th>Abondé ?</th>
                              <th>Type de donateur</th>
                              <th>Réaffectation du don</th>
                              <th>Précisez</th>
                              <th>Anonyme ?</th>
                              <th>Date de création</th>
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
      </div>
      <!-- /.container-fluid -->
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
   jQuery(document).ready(function () {

       $("body").on("click",".trigger-reminder",function(e){
         e.preventDefault();
         var url=$(this).attr("href");
         $.get(url, function(data, status){
          alert("Un rappel a été envoyé avec succès");
        });
         return false;
       });

   
       oTable = $('#site-donation-table').DataTable({
   
           "aaSorting": [],
           "pageLength": 20,
   
           processing: true,
           "iDisplayLength": 50,
   
           "language": {
            url: '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json',
   
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
   
           serverSide: true,
   
           ajax: {
   
               url: "{!! route('admin.donation-management.donation-list-table'); !!}",
   
               data: function (d) {
   
                   console.log(d);
                   //d.type = $('select[name=type]').val();
   
               }
   
           },
   
           columns: [
   
               {data: 'fullname', name: 'fullname'},
               {data: 'amount', name: 'amount'},
               {data: 'project', name: 'project', orderable: false}, 
               {data: 'amount_to_project', name: 'amount_to_project'},
               {data: 'amount_to_ngo', name: 'amount_to_ngo'},
               {data: 'donation_status', name: 'donation_status'},
               {data: 'donation_type', name: 'donation_type'},
               {data: 'doner_type', name: 'doner_type'},
               // // {data: 'double', name: 'double'},
               // {data: 'donation', name: 'donation'},
               // {data: 'project_status_name', name: 'project_status_name'},
               {data: 'is_refund', name: 'is_refund'},
               {data: 'other_reason', name: 'other_reason'},
               {data: 'is_anonym', name: 'is_anonym'},
               {data: 'created_time', name: 'created_time'},
               {data: 'action', name: 'action', orderable: false, searchable: false}
   
               
   
           ],
   
           drawCallback: function () {
   
           }
   
       });
   
       
   
   });
   
   jQuery(document).on('click','.changeStatus',function(e){
                e.preventDefault();
                let redirectUrl= jQuery(this).data('redirect-url');
                var btnId=jQuery(this).attr('id');
                
                    swal({
                        title: "Changement du statut de paiement",
                        text: "Veuillez indiquer le nouveau statut de paiement (cliquez en dehors de la fenêtre pour annuler le changement)",
                        buttons: {
                         cancel: false,
                         confirm: false,
                         rollconfirm: {
                           text: "Validé",
                           value: "completed",
                         },
                         roll: {
                           text: "En cours",
                           value: "init",
                         },
                         rolltwo: {
                           text: "Echoué",
                           value: "fail",
                         },
                         rollthree: {
                           text: "Annulé",
                           value: "canceled",
                         },
                       },
                        icon: "warning",
                        dangerMode: false,
                        })
                    .then((trueResponse ) => {
                        if (trueResponse) {
                           
                            jQuery.ajax({
                              headers: {
                                   'X-CSRF-TOKEN': $('.csrf-token').attr('data-content')
                               },
                                url: redirectUrl,
                                type: "POST",
                                cache: false,
                                data:{st:trueResponse},
                                success: function(response){
                                    //console.log(response);
                                    if(response.has_error == 0){
                                        jQuery(document).Toasts('create', {
                                        class: 'bg-info', 
                                        title: 'Success',
                                        body: response.msg,
                                        delay: 3000,
                                        autohide:true
                                    })
                                        var id="#"+btnId;
                                        jQuery(id).parent().html(response.link);
                                        
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