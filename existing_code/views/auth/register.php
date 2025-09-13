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

#Verify-Email-Id .input-group{
  margin-bottom: 3%;
  height: 38px;
}

#Verify-Email-Id .form-control{
  height: 38px;
}

#Verify-Email-Id .btn{
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

#email_error{
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
            <form enctype="multipart/form-data" id="Verify-Email-Id" class="form-horizontal" data-parsley-validate method="post">
             <input type="hidden" name="group_id" id="group_id" value="<?php echo $group_id ?>">
                <input type="hidden" name="group_name" id="group_name" value="<?php echo $groupName ?>">
               
                <span class="login100-form-title p-b-34 p-t-27">
                  <?php 
                  if (!empty($subGroup)) { ?>
                     <input type="hidden" name="sub_group_name" id="sub_group_name" value="<?php echo $subGroup ?>">
                      <img style="height: 90px;" src="<?php echo base_url().'assets/front/img/god.png' ?>">
                  <?php }else{ ?>
                     <input type="hidden" name="sub_group_name" id="sub_group_name" value="">
                    <img style="height: 90px;" src="<?php echo base_url().$logo->logo ?>">
                  <?php } ?>
                </span>
                <div style="padding:1rem 1.5rem;border-radius:16px;text-align: center;">
                  <h4>Lets create your account</h4>
                  <h5>Enter your Email ID</h5>
                </div>
                <div class="input-group">
                  <span class="input-group-addon"><i style="font-size: 22px;" class="fa fa-envelope"></i></span>
                    <input type="email" required="" autofocus="off"  placeholder="Enter email" id="email_id" data-type="email_id" name="email_id" class="form-control input-md">
                </div>

                 <div class="container-login100-form-btn" id="emailPopup" style="display: none;">
                  <p style="font-size:14p; text-align: center;" id="email_show"></p>
                </div>

                <div class="show_password_input" style="display: none;">
                    <label for="password">New Password <font color="red">*</font></label>
                    <div class="input-group">
                        <span class="input-group-addon"><span class="fa fa-eye-slash" id="newIcon" onclick="showPassword('password', 'newIcon')"></span></span>
                        <input data-parsley-errors-container="#errorNew" type="password" id="password" name="new"  class="form-control" placeholder="New Password" data-parsley-error-message="This value seems to be invalid" required=""> 
                        
                    </div> 
                    <div id="errorNew"></div> 

                    <label for="password">Confirm New Password <font color="red">*</font></label>
                    <div class="input-group">
                        <span class="input-group-addon"><span class="fa fa-eye-slash" id="new_confirmIcon" onclick="showPassword('cnfrm-password', 'new_confirmIcon')"></span></span>
                        <input  data-parsley-errors-container="#errorConfirm" type="password" id="cnfrm-password" name="new_confirm"  class="form-control" placeholder="Confirm New Password" data-parsley-error-message="Password not matching" data-parsley-equalto="#new" required="">
                    </div>  
                    <div id="errorConfirm"></div> 
                </div>


                <div class="input-group verifyOTP" style="display: none;">
                  <span class="input-group-addon"><i style="font-size: 22px;" class="fa fa-lock"></i></span>
                  <input class="form-control" id="otpCode" name="otpCode" autofocus="off" placeholder="Enter OTP Number" type="text" minlength="6" maxlength="6">
                </div>

                <div class="container-login100-form-btn" id="hide_verify_button">
                  <button type="button" id="sendOTP" onclick="send_otp()" class="login100-form-btn">Continue </button>
                  <button type="button" style="margin-bottom: 1rem; display: none; " id="verifytype" class="login100-form-btn verify">Verify</button>
                </div>

                <p id="message"></p>

                <div class="container-login100-form-btn" id="submit_button" style="display: none;">
                  <button type="button" id="sendOTP" onclick="register_account()" class="login100-form-btn">Submit</button>
                </div>

                <div class="container-login100-form-btn" id="error_reset" style="display: none;">
                  <h4 id="email_error"></h4>
                </div>

                <div class="container-login100-form-btn" id="errorPopUp" style="display: none;">
                  <h4 id="error_otp"></h4>
                </div>

               
                <div id="resend-code" class="text-center" style="display:none;">
                  <p id="show_hide_timer" style="font-size: 16px;">Resend Code in <span id="timer"></span> </p>
                  <a id="show_hide_resend" style="display: none;font-size: 16px;" onclick="resend_otp()" href="javascript:void(0)"> Resend Code</a>
                </div>
                <div class="container-login100-form-btn" style="margin-top:2rem">
                  <a  href="<?php echo base_url('client-login/'.$groupName) ?>"> Cancel</a>
                </div>
            </form>
          </div>
      </div>
    </div>

<script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/jquery/jquery.min.js"></script>    
<script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/parsley.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.12.5/dist/sweetalert2.all.min.js" integrity="sha256-vT8KVe2aOKsyiBKdiRX86DMsBQJnFvw3d4EEp/KRhUE=" crossorigin="anonymous"></script>



<script type="text/javascript">
  var timerOn = true;
     $('#email_id').keypress(function(e){
       $('#error_reset').hide();
      if(e.which == 13){//Enter key pressed
        send_otp();
      }
    });
  
    function send_otp(){
        $("#sendOTP").html('Please wait...');
        var emailId = $("#email_id").val();
        if (emailId == '') {
            $('#error_reset').show();
        }

      var $form = $('#Verify-Email-Id');
      // if ($form.parsley().validate()){
        ajaxcallotp_email(emailId);
      // }
    }
   
    function resend_otp(){
      var emailId = $("#email_id").val();
      $("#otpCode").removeAttr('required');
      var $form = $('#Verify-Email-Id');
      // if ($form.parsley().validate()){
        ajaxcallotp_email(emailId);
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
  function ajaxcallotp_email(emailId) {
    // $('.initial').removeClass('active');
    $('#error_reset').hide();
    var group_id = $('#group_id').val();
    $.ajax({
      url:'<?php echo site_url('auth/register_with_otp_through_email/'); ?>',
      type:'post',
      data: {'emailId':emailId,'group_id':group_id},
      success: function(data) {
        console.log(data);
        var retData = data;
        if (retData != 0) {
            $('#show_hide_resend').hide();
            $('#show_hide_timer').show();
            $('#resend-code').show();
            resend_timer(59);
            $('#email_id').prop('disabled',true);
            $(".verifyOTP").show();
            $(".verify").show();
            $(".resend").show();
            $("#sendOTP").hide();
            $("#otpCode").prop('required',true);
            $('#emailPopup').show();
            $('#email_show').html('<b>OTP Send to Email </b><br>'+retData);
        }else{
            $('#error_reset').show();
            $('#email_error').html('Already register this '+emailId);
        }

      }
    });
  }

  $('#otpCode').on('keyup',function(){
    $('#errorPopUp').hide();
  });

  $(".verify").click(function (e){
    var otp =$('#otpCode').val();
    var emailId = $('#email_id').val();
    $.ajax({
      url:'<?php echo site_url('auth/client_register_verify_otp_mobile_number/'); ?>',
      type:'post',
      data: {'emailId':emailId,'otp':otp},
      success: function(data) {
        var res = data.trim();
        if (res != 0) {
            $(".verifyOTP").hide();
            $('#hide_verify_button').hide();
            $('#emailPopup').hide();
            $('#resend-code').hide();
            $('.show_password_input').show();
            $('#submit_button').show();
        }else{
          $('#errorPopUp').show();
          $('#error_otp').html('Incorrect OTP. Please try again');
        }
      }
    });
  });

  function register_account(){
    var password = document.getElementById("password").value;
    var cnfrmPassword = document.getElementById("cnfrm-password").value;
    var message = document.getElementById("message");

    if (password.length != 0 && cnfrmPassword.length != 0) {
      if (password == cnfrmPassword) {
        var emailId = $('#email_id').val();
        var group_id = $('#group_id').val();
        var password = $('#password').val();
        var group_name = $('#group_name').val();
        var uri = '<?php echo $this->uri->segment(3) ?>';
        if (uri == '') {
          uri = 0;
        }
        $.ajax({
          url:'<?php echo site_url('home/client_registeration'); ?>',
          type:'post',
          data: {'emailId':emailId,'group_id':group_id,'password':password,'uri':uri},
          success: function(data) {
            var res = data.trim();
            if (res != 0) {
                Swal.fire({
                  title: 'Success',
                  text: 'Register Successfully',
                  icon: 'success',
                }).then((result) => {
                  window.location.href = '<?php echo site_url('client-form/') ?>'+group_name+'/'+group_id+'/'+res+'/'+uri;
                });
            }else{
                Swal.fire({
                    title: 'Failed',
                    text: 'Failed to registration',
                    icon: 'error',
                });
            }
          }
        });
      } else {
        message.textContent = "Passwords don't Match";
        message.style.color = "#ff4d4d";
      }
    } else {
      message.textContent = "Passwords can't be empty!";
      message.style.color = "#ff4d4d";      
    }

  
  }

</script>

  </body>
</html>