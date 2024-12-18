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
               <h1 class="m-0 text-dark">Order List 
               </h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                  <li class="breadcrumb-item active"> Order List</li>
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
                     <div class="date-filter d-flex align-items-center mb-3">
                        <label for="start_date" class="mr-2">Start Date:</label>
                        <input type="text" id="start_date" class="form-control datepicker mr-3" placeholder="YYYY-MM-DD" autocomplete="off">
                    
                        <label for="end_date" class="mr-2">End Date:</label>
                        <input type="text" id="end_date" class="form-control datepicker mr-3" placeholder="YYYY-MM-DD" autocomplete="off">
                    
                        <button id="filter-date" class="btn btn-primary">Filter</button>
                    </div>

                     <table class="table table table-bordered table-striped" id="site-donation-table">
                        <thead>
                           <tr>
                              <th>Sl.No</th>
                              <th>Order Id</th>
                              <th>Date</th>
                              <th>Name</th>
                              <th>Total</th>
                              <th>Discount</th>
                              <th>Coupon</th>
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
      $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
      });
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
         // url: '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json',

         processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},

         serverSide: true,

         ajax: {

            url: "{!! route('admin.order-management.order-list-table'); !!}",

            data: function (d) {
               d.start_date = $('#start_date').val();
               d.end_date = $('#end_date').val();

            }

         },

         columns: [
            {data: null, name: 'serial', render: function (data, type, row, meta) {
               return meta.row + 1 + meta.settings._iDisplayStart;
            }},
            {data: 'orderid', name: 'orderid'},
            {data: 'purchase_date', name: 'purchase_date'},
            {data: 'name', name: 'name'},
            {data: 'total', name: 'total'},
            {data: 'discount', name: 'discount'},
            {data: 'coupon', name: 'coupon'},
         ],

         drawCallback: function () {

         }

      });
      $('#filter-date').on('click', function () {
         const startDate = $('#start_date').val();
         const endDate = $('#end_date').val();

         if (!startDate || !endDate) {
            alert('Both Start Date and End Date are required!');
            return;
         }

         if (new Date(startDate) > new Date(endDate)) {
            alert('Start Date cannot be greater than End Date!');
            return;
         }

         oTable.draw(); // Trigger the DataTable reload
      });
   });
</script>
@endpush