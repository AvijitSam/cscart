<footer class="main-footer">

    Copyright &copy; {{date('Y')}} <a href="#">{{Config::get('yourdata.company_name')}}</a>.

    All rights reserved.

    <div class="float-right d-none d-sm-inline-block">

     <!-- <b>Version</b> {{Config::get('imagelink.admin_version')}}-->

    </div>

  </footer>



  <!-- Control Sidebar -->

  <aside class="control-sidebar control-sidebar-dark">

    <!-- Control sidebar content goes here -->

  </aside>

  <!-- /.control-sidebar -->

    </div>

<!-- ./wrapper -->



<div class="modal fade" id="modal-sm">

        <div class="modal-dialog modal-sm">

          <div class="modal-content">

            <div class="modal-header">

              <h4 class="modal-title">Verify Account</h4>

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                <span aria-hidden="true">&times;</span>

              </button>

            </div>

            <form method="POST"  id="verifyuser">

            {{ csrf_field() }}

                <div class="modal-body">

                   

                        <input type="hidden" id="downloadLinkId" value="" />



                        <div class="row">

                                        <div class="col-md-12">

                                                <div class="form-group row">

                                                    

                                                    <div class="col-sm-12">

                                                    <input type="password" name="yourpassword" id="yourpassword" class="form-control" placeholder="Password" title="Password">



                                                    <span id="msg"></span>

                                                    

                                                </div>

                                        </div>

                        </div>



                </div>

                <div class="modal-footer justify-content-between">

                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                  <button type="submit" class="btn btn-primary">Verify</button>

                </div>

            </form>

          </div>

          <!-- /.modal-content -->

        </div>

        <!-- /.modal-dialog -->

      </div>

<script>

  $.widget.bridge('uibutton', $.ui.button)

</script>

<!-- Bootstrap 4 -->

<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<!-- ChartJS -->

<script src="{{asset('assets/plugins/chart.js/Chart.min.js')}}"></script>

<!-- Sparkline -->

<script src="{{asset('assets/plugins/sparklines/sparkline.js')}}"></script>

<!-- JQVMap -->

<script src="{{asset('assets/plugins/jqvmap/jquery.vmap.min.js')}}"></script>

<script src="{{asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>

<!-- jQuery Knob Chart -->

<script src="{{asset('assets/plugins/jquery-knob/jquery.knob.min.js')}}"></script>

<!-- daterangepicker -->

<script src="{{asset('assets/plugins/moment/moment.min.js')}}"></script>

<script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>

<!-- Tempusdominus Bootstrap 4 -->

<script src="{{asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>

<!-- Summernote -->

<script src="{{asset('assets/plugins/summernote/summernote-bs4.min.js')}}"></script>

<!-- overlayScrollbars -->

<script src="{{asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>

<!-- AdminLTE App -->

<script src="{{asset('assets/dist/js/adminlte.js')}}"></script>



<script src="{{asset('assets//plugins/select2/js/select2.full.min.js')}}"></script>

<!-- AdminLTE for demo purposes -->

<script src="{{asset('assets/dist/js/demo.js')}}"></script>

<!-- Jquery form-validate -->

<script src="{{asset('js/jquery.validate.js')}}"></script>



<script src="{{ asset('js/development-admin.js')}}"></script>

<script src="{{ asset('assets/js/admincustom.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>

  $(document).ready(function(){

    $('.hidethis').hide();



    $(".hidenseek").mouseenter(function(){

      $(this).children(".displaythis").hide();

      $(this).children(".hidethis").show();

    });



    $(".hidenseek").mouseleave(function(){

      $(this).children(".displaythis").show();

      $(this).children(".hidethis").hide();

    });







    function checkPassword(str)

    {

        // at least one number, one lowercase and one uppercase letter

        // at least six characters

        //var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;

        var re = /(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}/;

        return re.test(str);

    }

      $(".checkpass").focusout(function(){



      if(!checkPassword($('#newpass').val()))

      {

        $('#checkpass_msg').html('');

        $('#checkpass_msg').html('<small>Password must have 1 uppercase(A-Z), lowercase(a-z), special charecter(@$!%*?&) and mininum lenght is 6</small>');

      }

      else

      {

        $('#checkpass_msg').html('');

      }

    });



    $(".checkpass").keyup(function(){

      if(!checkPassword($('#newpass').val()))

      {

        $('#checkpass_msg').html('');

        $('#checkpass_msg').html('<small>Password must have 1 uppercase(A-Z), lowercase(a-z), special charecter(@$!%*?&) and mininum lenght is 6</small>');

      }

      else

      {

        $('#checkpass_msg').html('');

      }

    });



    $("#confpass").focusout(function(){

      if($('#newpass').val() != $('#confpass').val())

      {

        $('#checkpass_confirm_msg').html('');

        $('#checkpass_confirm_msg').html('<small>New password and confirm password must be same</small>');

      }

      else

      {

        $('#checkpass_confirm_msg').html('');

      }

    });



    $("#change_password").submit(function(){

      //alert('jjj');

      var flag = 0;

      if(!checkPassword($('#newpass').val()))

      {

        $('#checkpass_msg').html('');

        $('#checkpass_msg').html('<small>Password must have 1 uppercase(A-Z), lowercase(a-z), special charecter(@$!%*?&) and mininum lenght is 6</small>');

        flag = 1;

      }

      else

      {

        $('#checkpass_msg').html('');

        if($flag == 0)

        {

          flag = 0;

        }

        

      }

      // alert('p1-'+flag);

      // return false;



      

      if(checkPassword($('#newpass').val()))

      {

        $('#checkpass_msg').html('');

        flag = 0;

      }

      



      if($('#newpass').val() != $('#confpass').val())

      {

        $('#checkpass_confirm_msg').html('');

        $('#checkpass_confirm_msg').html('<small>New password and confirm password must be same</small>');

        flag = 1;

      }

      else

      {

        // $('#checkpass_confirm_msg').html('');

        // if($flag == 0)

        // {

        //   flag = 0;

        // }

        if($('#newpass').val() == $('#confpass').val())

        {

          if(!checkPassword($('#newpass').val()))

          {

            $('#checkpass_msg').html('');

            $('#checkpass_msg').html('<small>Password must have 1 uppercase(A-Z), lowercase(a-z), special charecter(@$!%*?&) and mininum lenght is 6</small>');

            flag = 1;

          }

          else

          {

            $('#checkpass_msg').html('');

            if($flag == 0)

            {

              flag = 0;

            }

            

          }

        }

      }

      //alert('ppp');

      // alert('p2-'+flag);

      // return false;



      if($('#newpass').val() == '')

      {

        $('#checkpass_msg').html('');

        $('#checkpass_msg').html('<small>Required</small>');

        flag = 1;

      }



      if($('#confpass').val() == '')

      {

        $('#checkpass_confirm_msg').html('');

        $('#checkpass_confirm_msg').html('<small>Required</small>');

        flag = 1;

      }



      if($('#oldpass').val() == '')

      {

        $('#checkpass_old_msg').html('');

        $('#checkpass_old_msg').html('<small>Required</small>');

        flag = 1;

      }

      // alert(flag);

      // return false;



      if(flag != 0)

      {

        return false;

      }

      

    });



  });

</script>

@stack('custom-scripts')

</body>

</html>





   

    

