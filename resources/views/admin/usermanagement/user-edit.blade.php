@extends('admin.layouts.after-login-layout')

@section('unique-content')
<style>
    .form-control{border-radius: 5px;}
    .form-group.sgo-error input,.form-group.sgo-error textarea,.form-group.sgo-error select,.form-group.sgo-error .dropzone,.form-group.sgo-error .select2-selection{border: 2px solid red;}
</style>
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Modifier le chef de projet</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{route('admin.user-management.site.user.customer.list')}}">Liste de clients</a></li>
                  <li class="breadcrumb-item active"> Modifier le chef de projet</li>
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
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                  <div class="card-header">
                     <h3 class="card-title">{{$panel_title}}</h3>
                  </div>
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
                     <form action="{{route('admin.user-management.user-edit-save')}}" method="POST" id="user-add">
                        {{ csrf_field() }}
                        <input type="hidden" name="Userid" value="{{$getDetail->id}}">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group row">
                                     <label for="Title" class="col-sm-2 col-form-label">Nom : </label>
                                     <div class="col-sm-10">
                                        <input type="text" name="last_name" id="last_name" value="{{$getDetail->last_name}}" class="form-control" placeholder="Nom" title="Nom" required>
                                        <div id="last_name_id" class="validation_msg"></div>
                                     </div>
                                </div>
                                <div class="form-group row">
                                     <label for="Title" class="col-sm-2 col-form-label">Prénom : </label>
                                     <div class="col-sm-10">
                                        <input type="text" name="first_name" id="first_name" value="{{$getDetail->first_name}}" class="form-control" placeholder="Prénom" title="Prénom" required>
                                        <div id="first_name_id" class="validation_msg"></div>
                                     </div>
                                </div>
                                <div class="form-group row exclude">
                                     <label for="Title" class="col-sm-2 col-form-label">Adresse : </label>
                                     <div class="col-sm-10">
                                        <input type="text" name="address" id="address" value="{{$getDetail->address}}" class="form-control" placeholder="Adresse" title="Adresse" required>
                                        <div id="address_id" class="validation_msg"></div>
                                     </div>
                                </div>
                                <div class="form-group row exclude">
                                     <label for="Title" class="col-sm-2 col-form-label">Code postal : </label>
                                     <div class="col-sm-10">
                                        <input type="text" name="postal_code" id="postal_code" value="{{$getDetail->postal_code}}" class="form-control" placeholder="Code postal" title="Code postal" required>
                                        <div id="postal_code_id" class="validation_msg"></div>
                                     </div>
                                </div>
                                <div class="form-group row exclude">
                                     <label for="Title" class="col-sm-2 col-form-label">Ville : </label>
                                     <div class="col-sm-10">
                                        <input type="text" name="village" id="village" value="{{$getDetail->village}}" class="form-control" placeholder="Ville" title="Ville" required>
                                        <div id="village_id" class="validation_msg"></div>
                                     </div>
                                </div>
                                <div class="form-group row">
                                     <label for="Title" class="col-sm-2 col-form-label">Email : </label>
                                     <div class="col-sm-10">
                                        <input type="email" name="email" id="email" value="{{$getDetail->email}}" class="form-control" placeholder="Email" title="Email" required>
                                        <div id="email_id" class="validation_msg"></div>
                                     </div>
                                </div>
                                <div class="form-group row exclude">
                                     <label for="Title" class="col-sm-2 col-form-label">Mot de passe : </label>
                                     <div class="col-sm-10">
                                        <div class="input-group">
                                            <input type="password" name="password" id="password" value="" class="form-control input-lg" placeholder="*****" title="Mot de passe"  rel="gp" data-size="10" data-character-set="a-z,A-Z,0-9,#">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-lg getNewPass" style="margin: -4px;padding: 8px;"><span class="fa fa-refresh"></span></button>
                                            </span>
                                        </div>
                                        
                                        <div id="password_id" class="validation_msg"></div>
                                     </div>
                                </div>
                                <div class="form-group row">
                                     <label for="Title" class="col-sm-2 col-form-label">Statut : </label>
                                     <div class="col-sm-10">
                                        <select class="form-control" id="account_status" name="account_status">
                                            <option value="1" @if($getDetail->account_status=='1'){checked}@endif>Actif</option>
                                            <option value="0" @if($getDetail->account_status=='0'){checked}@endif>Inactif</option>
                                        </select>
                                        <div id="account_status_id" class="validation_msg"></div>
                                     </div>
                                </div>
                                <div class="form-group row">
                                     <label for="Title" class="col-sm-2 col-form-label">Inscrit à la newsletter: </label>
                                     <div class="col-sm-10">
                                        <select class="form-control" id="is_subscribe_newsletter" name="is_subscribe_newsletter">
                                            <option value="Yes" @if($getDetail->is_subscribe_newsletter=='Yes'){checked}@endif>Oui</option>
                                            <option value="No" @if($getDetail->is_subscribe_newsletter=='No'){checked}@endif>Non</option>
                                        </select>
                                        <div id="is_subscribe_newsletter_id" class="validation_msg"></div>
                                     </div>
                                </div>

                              <div class="card-footer">
                                 <div class="">
                                    <button  class="btn btn-success submit_new">Ajouter</button>
                                    <a class="btn btn-primary back_new" href="{{route('admin.user-management.site.user.customer.list')}}">Retour</a>
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

@push('custom-scripts')
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script>
    function randString(id){
      var dataSet = $(id).attr('data-character-set').split(',');  
      var possible = '';
      if($.inArray('a-z', dataSet) >= 0){
        possible += 'abcdefghijklmnopqrstuvwxyz';
      }
      if($.inArray('A-Z', dataSet) >= 0){
        possible += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      }
      if($.inArray('0-9', dataSet) >= 0){
        possible += '0123456789';
      }
      if($.inArray('#', dataSet) >= 0){
        possible += '![]{}()%&*$#^<>~@|';
      }
      var text = '';
      for(var i=0; i < $(id).attr('data-size'); i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
      }
      return text;
    }
    $(".getNewPass").click(function(){
      var field = $(this).closest('div').find('input[rel="gp"]');
      field.val(randString(field));
      $(this).closest('div').find('input[rel="gp"]').attr("type","text");
    });
    $('input[rel="gp"]').on("click", function () {
        var check=$(this).val();
        if(check.length==0 || check=='*****')
        {
            $(this).val('').attr("type","text");
        }
        else
        {
            $(this).attr("type","text");
        }
       
       $(this).select();
    });
    $('.submit_new').on('click', function(e) {
        e.preventDefault();
        var flag=0;
        var finalLocate="";
        $(".form-group").removeClass("sgo-error");
        $(".validation_msg").html("");
        $(".form-group").each(function () {
            if($(this).hasClass("exclude"))
            {
              return true;
            }
            var temp="";
            var i=$(this).find("input[type='text']").length;
            var e=$(this).find("input[type='email']").length;
            if(i<0 || e<0)
            {
              return true;
            }
            var locate=$(this);
            if(i>0 || e>0)
            {
                if(i>0)
                  {
                    temp=$(this).find("input").val();
                    if($.trim(temp) == '')
                    {
                       $(this).find(".validation_msg").html('<small style="color:red">Champs requis</small>');
                    }
                  }
                if(e>0)
                  {
                     
                     temp=$(this).find("input[type='email']").val();
                     
                      var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                      if(!regex.test( temp ))
                      {
                         $(this).addClass("sgo-error");
                           $(this).find(".validation_msg").html('<small style="color:red">Saisissez une adresse e-mail valide</small>');
                           
                         if(flag<1)
                         {
                            finalLocate=locate;
                         }
                         flag=flag+1;
                         
                      }
                  }
                 if(temp.length<1)
                {
                  $(this).addClass("sgo-error");
                  if(flag<1)
                  {
                     finalLocate=locate;
                     
                  }
                  flag=flag+1;
                }
                

            }
        });
        if(flag>0)
        {
           var pos = $(finalLocate).offset().top;
           pos=pos-30;
           $('body, html').animate({scrollTop: pos},2000);
           return false;
        }
        if(flag==0)
        {
            $("#user-add").trigger("submit");
        }
        
    });
</script>
@endpush