<style type="text/css">
  [hidden] {
    display: none!important;
}
</style>
<?php 
  $user = $this->ion_auth->user()->row();
  $user_id = '';
  if (!empty($user)) {
    $user_id = $user->id;
  }
?>
  <input type="hidden" id="userIdUrl" value="<?php echo  (!empty($user)) ? $user->id : ''  ?>">

  <section>
    <hgroup>
      <p style="font-size: 20px;font-weight: 800;color: #625353;">Sign In</p>
    </hgroup>

    <?php 
    $style = '';
    if ($this->mobile_detect->isMobile()) { 
          $style = 'width:100%';
        } 
    ?>

    <form method="post" class="log-form" style="<?php echo $style ?>">
      <div class="group log-input">
        <input type="text"  id="mobile_number" name="identity" placeholder="Mobile Number">
      </div>

      <div class="group log-input">
        <div class="wrap-input100 validate-input" data-validate="Enter password">
          <input type="password" id="password" name="password"  placeholder="Password">
            <button type="button" class="btn btn-secondary" style="margin-top:-3rem;margin-left: 90%;padding: 0px;border: 0px; display: block; ">
              <i class="fa fa-eye-slash" id="passwordShowIcon"></i>
            </button>
        </div>
      </div>   

      <span class="check left-align">
        <input type="checkbox">
        <label>Remember Me</label>
      </span>
      <a class="right-align" href="<?php echo site_url('auth/forgot_user_email') ?>">Forgot Password</a>
      <br><br>
      <div class="container-log-btn">
        <button type="button" onclick="login_profile()" name="btn_submit" class="btn btn-info btn-block">
          <span>Login</span>
        </button>
        <a style="float: left;margin-top: 2rem;font-size: 14px; font-weight: 700;" href="<?php echo site_url('home/register_users') ?>">Don't have an account? Register</a>
      </div>

      <div class="container-log-btn" style="padding: 0;">
        <p class="error_mobile" style="display: none;"></p>
      </div>
     
    </form>
  </section>


<style type="text/css">
.rounded-circle {
    border-radius: 50%!important;
}
.mobile-camera{
  position: absolute;
  left: 21px;
  top: 55%;
  color: #343a40;
}
.camera{
  position: absolute;
  left: 60px;
  top: 63%;
  color: #343a40;
}

.profile{
    border: 2px solid #17a2b8;
    padding: 10px;
    border-radius: 10px;
    background: #73acea;
    color: #000000;
    font-weight: 600;
}
  #loginbutton{
    width: 100%;
  }
  #regsiterbutton{
    width: 100%;
  }
  #LoginError{
    color: #c02727;
  }
  #loginButton{
    width: 100%;
  }

  #editButton .fa{
    font-size: 14px;
  }
</style>
<script type="text/javascript">
  $(document).ready(function() {
    $('#onclicktnc').click(function() {
      $('#tncModal').modal('show');
      $.ajax({
        url:'<?php echo site_url('home/get_terms_condition') ?>',
        type:"post",
        success:function(data){
          $('#tnc_content').html(data);
        }
      });
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#onclickpnp').click(function() {
      $('#pnpModal').modal('show');
      $.ajax({
        url:'<?php echo site_url('home/get_terms_condition') ?>',
        type:"post",
        success:function(data){
          $('#tnc_content').html(data);
        }
      });
    });
  });
</script>


<script type="text/javascript">

  $(document).ready(function() {
    $('#loginbutton').click(function() {
      $('#loginModel').modal('show');
    });
  });

  $(document).ready(function() {
    $('#regsiterbutton').click(function() {
      $('#registerModel').modal('show');
    });
  });

  $(document).ready(function() {
    $('#loginFeedBack').click(function() {
      $('#loginModel').modal('show');
    });
  });

  $('#editButton').click(function() {
    var user_id = '<?php echo  $user_id ?>';
    $('#user_id').val(user_id);
    $('#profile-edit').submit();
  });


  $(document).ready(function() {
    $('#payment_button').click(function() {
      $('#payemntModel').modal('show');
    });
  });


 

</script>


<style type="text/css">
.wrap-input100 {
  width: 100%;
  position: relative;
  border: none;
  margin-bottom: 10px;
}
.focus-input100 {
    position: absolute;
    display: block;
    width: 100%;
    height: 100%;
    top: 0px;
    left: 4px;
    pointer-events: none;
}
  .wrap-input100 .btn .fa{
    color: #000000;
  }

</style>
<script type="text/javascript">

 $('#passwordShowIcon').click(function(e) {
    e.preventDefault();
    var type = $('#password').attr('type');
    if(type == 'password') {
        $('#passwordShowIcon').removeClass('fa fa-eye-slash').addClass('fa fa-eye');
        $('#password').attr('type', 'text');
    } else {
        $('#passwordShowIcon').removeClass('fa fa-eye').addClass('fa fa-eye-slash');
        $('#password').attr('type', 'password');
    }
  });


  function login_profile() {
    var mobileNumber = $('#mobile_number').val();
    if (mobileNumber == '') {
      $('.error_mobile').show();
      $('.error_mobile').html('Enter valid Phone-number');
      return false;
    }else{
      $('.error_mobile').hide();
       $('.error_mobile').html('');
    }
    var password = $('#password').val();
    if (password == '') {
      $('.error_mobile').show();
      $('.error_mobile').html('Enter valid password');
      return false;
    }else{
      $('.error_mobile').hide();
      $('.error_mobile').html('');
    }

    $.ajax({
      url :'<?php echo site_url('home/check_user_active') ?>',
      type: 'post',
      data : {'mobileNumber':mobileNumber,'password':password},
      success: function(data){
        if (data == 1) {
          $('.error_mobile').hide();
          url = '<?php echo site_url('home/profile_login') ?>';
          $('.log-form').attr('action',url);
          $('.log-form').submit();
        }else{
          $('.error_mobile').html('Invalid Username / Password. Please try again');
          $('.error_mobile').show();
        }
      }

    });
  }

$('#fileupload').change(function() {
    var src = $(this).val();
    // var isFileOk = validatePhoto(this.files[0])
    if (src && validatePhoto(this.files[0], 'fileupload')) {
      $("#fileuploadError").html("");
      readURL(this);
      $("#photo_profile").show();
    } else {
      this.value = null;
      $("#photo_profile").hide();
    }
  });

 function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#profile_photo').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
    var id =$('#userIdUrl').val();
    savePhoto('profile',id);
  }

  function validatePhoto(file, errorId) {
    if (file.size > 10000000 || file.fileSize > 10000000) {
      $("#" + errorId + "Error").html("Allowed file size exceeded. (Max. 10 MB)")
      return false;
    }
    if (file.type != 'image/jpeg' && file.type != 'image/jpg' && file.type != 'image/png') {
      $("#" + errorId + "Error").html("Allowed file types are jpeg, jpg and png");
      return false;
    }
    return true;
  }


   function savePhoto(type, id) {
      $("#photo_" + type).prop('disabled', true).html('<i class="fa fa-spinner fa-spin" style="font-size:20px"></i>');
      var file_data = $('#fileupload').prop('files')[0];
      var form_data = new FormData();
      form_data.append('file', file_data);
      form_data.append('type', type);
      form_data.append('id', id);      
      $.ajax({
        url: '<?php echo site_url('home/profile_pic_update') ?>',
        type: 'post',
        data: form_data,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
          console.log(data);
        }
      });
    }
</script>



<script type="text/javascript">
    $('#passwordShowIcon1').click(function(e) {
    e.preventDefault();
    var type = $('#new').attr('type');
    if(type == 'password') {
        $('#passwordShowIcon1').removeClass('fa fa-eye-slash').addClass('fa fa-eye');
        $('#new').attr('type', 'text');
    } else {
        $('#passwordShowIcon1').removeClass('fa fa-eye').addClass('fa fa-eye-slash');
        $('#new').attr('type', 'password');
    }
  });

   $('#passwordShowIcon2').click(function(e) {
    e.preventDefault();
    var type = $('#new_confirm').attr('type');
    if(type == 'password') {
        $('#passwordShowIcon2').removeClass('fa fa-eye-slash').addClass('fa fa-eye');
        $('#new_confirm').attr('type', 'text');
    } else {
        $('#passwordShowIcon2').removeClass('fa fa-eye').addClass('fa fa-eye-slash');
        $('#new_confirm').attr('type', 'password');
    }
  });
</script>

<style type="text/css">

.error_mobile{
     font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.1em;
    line-height:34px;
    text-transform: uppercase;
    color: #f23131;
}
hgroup { 
  text-align:center;
  margin-top: 1em;
}

.log-form span {
    font-size: 0.95em;
    font-weight: 600;
    /*letter-spacing: 0.3em;*/
    line-height: 24px;
    /*text-transform: uppercase;*/
}

.btn-secondary{
  background-color: aliceblue;
}
/*------------------------------------------------------------------
[ Login Form ]*/



.group { 
  position: relative; 
  margin-bottom: 18px; 
}


.log-input:focus { outline: none; }

.log-form a {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.1em;
    line-height:34px;
    text-transform: uppercase;
    color: #007bff;
}

.left-align {
    float: left;
    text-align: left;
}

.right-align {
    float: right;
    text-align: right;
}


/*------------------------------------------------------------------
[ Button same code as contact form ]*/

.container-log-btn {
  display: -webkit-box;
  display: -webkit-flex;
  display: -moz-box;
  display: -ms-flexbox;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  padding-top: 10px;
}

.log-form-btn {
  display: -webkit-box;
  display: -webkit-flex;
  display: -moz-box;
  display: -ms-flexbox;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 0 20px;
  min-width: 250px;
  height: 50px;
  background-color: transparent;
  border-radius:7px;
  cursor: pointer;

  font-size: 16px;
  color: #000;
  line-height: 1.2;
  text-transform: uppercase;

  -webkit-transition: all 0.4s;
  -o-transition: all 0.4s;
  -moz-transition: all 0.4s;
  transition: all 0.4s;
  position: relative;
  z-index: 1;
}

.log-form-btn::before {
  content: "";
  display: block;
  position: absolute;
  z-index: -1;
  width: 100%;
  height: 100%;
  top: 0;
  left: 50%;
  -webkit-transform: translateX(-50%);
  -moz-transform: translateX(-50%);
  -ms-transform: translateX(-50%);
  -o-transform: translateX(-50%);
  transform: translateX(-50%);
  border-radius: 7px;
  background-color: #9e8c7b;
  pointer-events: none;
  
  -webkit-transition: all 0.4s;
  -o-transition: all 0.4s;
  -moz-transition: all 0.4s;
  transition: all 0.4s;
}

.log-form-btn:hover:before {
  background-color: #916439;
}


</style>