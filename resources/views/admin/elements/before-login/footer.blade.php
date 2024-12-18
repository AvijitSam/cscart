<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js')  }}"></script>
<!-- Jquery form-validate -->
<script src="{{asset('js/jquery.validate.js')}}"></script>

<script src="{{ asset('js/development-admin.js')}}"></script>

<script>

    $(document).ready(function(){
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

		$("#resetpass").submit(function(){

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
		  	flag = 0;
		  }


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
		  	// flag = 0;
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
		  // alert(flag);
		  // return false;

		  if(flag == 1)
		  {
		  	return false;
		  }
			
		});

    });
</script>
</body>
</html>