<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forogt Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/back_end/css/login/css/util.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/back_end/css/login/css/main.css">
    <link rel="stylesheet" type="text/css"  href="<?php echo base_url();?>assets/back_end/css/theme-default-profile.css"/>
    <link rel="stylesheet" type="text/css"  href="<?php echo base_url();?>assets/back_end/css/bootstrap/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css"  href="<?php echo base_url();?>assets/back_end/css/fontawesome/font-awesome.min.css"/>
<style>
.glass
  {
width:390px;
background:#f8f8f8f2;
position: relative;
z-index: 1;
overflow: hidden;
margin: 0 auto;
padding: 2rem;
box-sizing: border-box;
/*box-shadow : 0 .5em 1em rgba(0,0,0,.3);*/
border-radius:16px;
} 
.glass::before
{
content: "";
position: absolute;
z-index: -1;
top:0; right:0; bottom:0; left:0;
/* background: #f8f8f8ad; */
/*box-shadow: inset 0 0 3000px rgba(255, 255,255,.5);*/
filter:blur(5px);
margin:-20px;
}
  
.input100 {
      font-family: 'Roboto', sans-serif;
    font-size: 16px;
    color: #000000;
    line-height: 1.2;
    display: block;
    width: 100%;
    height: 56px;
    background-color: #FFFFFF;
    padding: 0 5px 0 38px;
  border-radius: 8px;
  border: none;
}
  
input::placeholder {
    color:#8c8c8c;
    opacity: 1; /* Firefox */
}
  
.focus-input100 {
    position: absolute;
    display: block;
    width: 100%;
    height: 100%;
    top: 4px;
    left: 4px;
    pointer-events: none;
}
.wrap-input100 {
    width: 100%;
    position: relative;
    border: none;
    margin-bottom: 20px;
}
.login100-form-btn::before {
    background-color: #43a047;
    border-radius: 1.17647rem;
} 
.login100-form-btn {
    padding: .75em 1.45em;
    box-sizing: border-box;
    font-size: 16px;
    border-radius: 1.17647rem;
    color: #42597a;
    background-color: #eeeef5;
    margin: 0;
    width: 100%;
    border: solid 1px #43a047;
    white-space: normal;
    color: #fff;
    background: #43a047;
    position: relative;
}
  
.txt1 {
   font-family: 'Roboto', sans-serif;
    font-size: 16px;
    color: #e5e5e5;
    line-height: 1.5;
}
  .contact100-form-checkbox {
    padding-left: 5px;
    padding-top: 5px;
    padding-bottom: 24px;
}

#Verify-Mobile-Number .input-group{
  margin-bottom: 3%;
  height: 38px;
}

#Verify-Mobile-Number .form-control{
  height: 38px;
}

#Verify-Mobile-Number .btn{
  font-size: 14px;
  font-weight: 600;
  width: 48%;
  border-radius: 12px;
}


#reset-form .input-group{
  margin-bottom: 3%;
  height: 38px;
}

#reset-form .form-control{
  height: 38px;
}

#reset-form .btn{
  font-size: 14px;
  font-weight: 600;
  width: 48%;
  border-radius: 12px;
}

#reset-form .input-group-addon{
  line-height: 36px;
}
#reset-form .login100-form-logo{
  margin-bottom: 4rem;
}
.parsley-errors-list{
    display: table-caption;
    font-size: 1.2rem;
    line-height: 1.17647rem;
    color: #e04b4a;
    text-align: center;
}

#sms_error{
    font-size: 1.2rem;
    line-height: 1.17647rem;
    padding-top: .29412rem;
    color: #f0162f;
    margin-top: 4%;
}

p{
  font-size:11px;
}
.form-control ,.input-group-addon{
  border-radius:10px;
}

#resend-timer{
  color: #5e5c57;
  margin-top: .82353rem;
  padding: 0;
  font-size: 1.5rem;
  line-height: 1rem;
  white-space: normal;
  text-align: center;
}
.initial{
  color: #666666;
}
.active{
  color: #428bca;
  font-size: 18px;
  font-weight: 600;
  text-decoration: underline;
  cursor: pointer;
}
.login100-form-logo{
  height: 80px;
  margin-bottom: 2rem;
}
#error_otp{
  color: red;
}
.parsley-errors-list{
  display: none;
}
</style>  
</head>

<body>

    <div class="limiter">
      <div class="container-login100" style="background:#ccc">
          <div class="wrap-login100 glass">
            <form enctype="multipart/form-data" id="Verify-Mobile-Number" class="form-horizontal" data-parsley-validate method="post">
              <input type="hidden" class="user_name" name="user_name">
              <input type="hidden" name="mobile_number" id="mobileNumber">
              <input type="hidden" name="group_name" value="<?php echo $groupName ?>" >
                <span class="login100-form-logo">
                  <img width="100px" style="margin-bottom:0.8rem" class="img-responsive" src="<?php echo $logo ?>">
                </span>
                <div style="padding:1rem 1.5rem;border-radius:16px;text-align: center;">
                  <h4>Lets find your account</h4>
                  <h5>Enter your registered Email ID</h5>
                </div>
                <div class="input-group">
                  <span class="input-group-addon"><i style="font-size: 22px;" class="fa fa-envelope"></i></span>
                    <input type="text" required="" autofocus="off"  placeholder="Enter email" id="email_mobileNumber" data-type="mobileNumber" name="email_mobileNumber" class="form-control input-md">
                </div>

                 <div class="container-login100-form-btn" id="emailPopup" style="display: none;">
                  <p id="email_show"></p>
                </div>


                <div class="input-group verifyOTP" style="display: none;">
                  <span class="input-group-addon"><i style="font-size: 22px;" class="fa fa-lock"></i></span>
                  <input class="form-control" id="otpCode" name="otpCode" autofocus="off" placeholder="Enter OTP Number" type="text" minlength="4" maxlength="4">
                </div>

                <div class="container-login100-form-btn">
                  <button type="button" id="sendOTP" onclick="send_otp()" class="login100-form-btn">Continue </button>
                  <button type="button" style="margin-bottom: 1rem; display: none; " id="verifytype" class="login100-form-btn verify">Verify</button>
                </div>

                <div class="container-login100-form-btn" id="error_reset" style="display: none;">
                  <h4 id="sms_error"></h4>
                </div>

                <div class="container-login100-form-btn" id="errorPopUp" style="display: none;">
                  <h4 id="error_otp"></h4>
                </div>

               
                <div id="resend-code" class="text-center" style="display:none;">
                  <p id="show_hide_timer" style="font-size: 16px;">Resend Code in <span id="timer"></span> </p>
                  <a id="show_hide_resend" style="display: none;font-size: 16px;" onclick="resend_otp()" href="javascript:void(0)"> Resend Code</a>
                </div>
                <div class="container-login100-form-btn" style="margin-top:2rem">
                  <a  href="<?php echo base_url() ?>"> Cancel</a>
                </div>
            </form>

          </div>
      </div>
    </div>

<script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/jquery/jquery.min.js"></script>    
<script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/parsley.js"></script>



<script type="text/javascript">
  var timerOn = true;
     $('#email_mobileNumber').keypress(function(e){
       $('#error_reset').hide();
        $('#sms_error').html("");
      if(e.which == 13){//Enter key pressed
        send_otp();
      }
    });
  
    function send_otp(){
      var emailId = $("#email_mobileNumber").val();
      if (emailId == '') {
        $('#error_reset').show();
        $('#sms_error').html("Email Id can't be empty!");
      }

      var $form = $('#Verify-Mobile-Number');
      // if ($form.parsley().validate()){
        ajaxcallotp_number(emailId);
      // }
    }
   
    function resend_otp(){
      var emailId = $("#email_mobileNumber").val();
      $("#otpCode").removeAttr('required');
      var $form = $('#Verify-Mobile-Number');
      // if ($form.parsley().validate()){
        ajaxcallotp_number(emailId);
      // }
    }
 
 function resend_timer(remaining) {
   // $('#resend-success').hide();
   // $('#resend-timer').show();
   var m = Math.floor(remaining / 60);
    var s = remaining % 60;
    m = m < 10 ? '0' + m : m;
    s = s < 10 ? '0' + s : s;
    document.getElementById('timer').innerHTML = m + ':' + s;
    remaining -= 1;
    
    if(remaining >= 0 && timerOn) {
      setTimeout(function() {
          resend_timer(remaining);
      }, 1000);
      return;
    }
    if(!timerOn) {
      // Do validate stuff here
      return;
    }
    // $('.initial').addClass('active');

    $('#show_hide_resend').show();
    $('#show_hide_timer').hide();
    
 }
  function ajaxcallotp_number(emailId) {
    // $('.initial').removeClass('active');
    $('#error_reset').hide();
    $.ajax({
      url:'<?php echo site_url('auth/forgot_username_password_otp_new/'); ?>',
      type:'post',
      data: {'emailId':emailId},
      success: function(data) {
        var retData = data;
        console.log(retData);
        if (retData != 0) {
            $('#show_hide_resend').hide();
            $('#show_hide_timer').show();
            $('#resend-code').show();
            resend_timer(59);
            $('#email_mobileNumber').prop('disabled',true);
            $(".verifyOTP").show();
            $(".verify").show();
            $(".resend").show();
            $("#sendOTP").hide();
            $("#otpCode").prop('required',true);
            $('#emailPopup').show();
            $('#email_show').html('OTP Send Email -'+retData);
        }else{
            $('#error_reset').show();
            $('#sms_error').html('No account associated with '+emailId);
        }

      }
    });
  }

  $('#otpCode').on('keyup',function(){
    $('#errorPopUp').hide();
  });
  $(".verify").click(function (e){
    var otp =$('#otpCode').val();
    var emailId = $('#email_mobileNumber').val();
    $.ajax({
      url:'<?php echo site_url('auth/verify_client_otp_mobile_number/'); ?>',
      type:'post',
      data: {'emailId':emailId,'otp':otp},
      success: function(data) {

        var res = data.trim();
        if (res != 0) {
           $('.user_name').val(res);
           $('#mobileNumber').val(emailId);
            url = '<?php echo site_url('auth/forgot_reset_password_client') ?>';
            $('#Verify-Mobile-Number').attr('action',url);
            $('#Verify-Mobile-Number').submit();
        }else{
          $('#errorPopUp').show();
          $('#error_otp').html('Incorrect OTP. Please try again');
        }
      }
    });
  });

</script>

  </body>
</html>