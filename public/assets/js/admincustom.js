jQuery(document).ready(function($){
    jQuery('#userid').on('change', function(e){
        e.preventDefault();
        var token = $("input[name=_token]").val();
        var userid = jQuery('#userid').find(":selected").val();
        //alert( jQuery('#countryid').find(":selected").val() );

        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            }
            });/**/
            jQuery.ajax({
            url: "getuseras",
            method: 'post',
            dataType: 'html',
            data: {
                _token: token,
                userid: userid
            },
            success: function(result){
                $("#useras").empty().append(result);
                //$("#cityid").empty().append('<option value="">Select City</option>');
            }
        });
    });


    jQuery('#useras').on('change', function(e){
        e.preventDefault();
        var token = $("input[name=_token]").val();
        var useras = jQuery('#useras').find(":selected").val();
        //alert( jQuery('#countryid').find(":selected").val() );

        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            }
            });/**/
            jQuery.ajax({
            url: "getcategory",
            method: 'post',
            dataType: 'html',
            data: {
                _token: token,
                useras: useras
            },
            success: function(result){
                $("#categoryid").empty().append(result);
                //$("#cityid").empty().append('<option value="">Select City</option>');
            }
        });
    });





});



/*jQuery('#refer_url').keyup(function()
{
    alert(this.value);
    url = this.value;
    if(url!='')
    {
        $("#thissubmit").attr('disabled', 'disabled');
        if(/^(http:\/\/www\.|https:\/\/www\.)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/.test(url)) {
            $(".check_valid").html("");
            $("#thissubmit").removeAttr('disabled');
        }else{
                $(".check_valid").html("<span style='color: red;'>Invalid url</span>");
                $("#thissubmit").attr('disabled', 'disabled');
        }
    }
    else
    {
        $(".check_valid").html("");
        $("#thissubmit").removeAttr('disabled');
    }
    

});*/



