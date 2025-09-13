  <!DOCTYPE html>
<html lang="en">
<head>
  <title>MY Group</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

   <link rel="stylesheet" href="<?php echo base_url()?>assets/back_end/css/fontawesome/font-awesome.min.css">

  <style>
    /* Remove the navbar's default margin-bottom and rounded borders */ 
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
      min-height: 106px;
    }
    
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    .row.content {height: 450px}
    
    /* Set gray background color and 100% height */
    .sidenav {
      padding-top: 10px;
      height: 100%;
    }
    
    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }
    .navbar-inverse{
      background-color: #d9edf7;
      border-color: #fff
    }
    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height:auto;} 
    }
    .top_head{
      /*height: 144px;
      border-bottom: 2px solid #ccc;
      margin-top: 2px;*/
    }
    .navbar-inverse .navbar-nav>.active>a, .navbar-inverse .navbar-nav>.active>a:focus, .navbar-inverse .navbar-nav>.active>a:hover{
      background-color: inherit; 
    }

    .navbar-brand{
      padding: 0;
      margin-top: 2px;
    }
  </style>
</head>
<body>

<div class="collapse navbar-collapse" id="myNavbar" style="background: #0000;">
  <ul class="nav navbar-nav  navbar-lett" style="margin-top:1rem">
    <?php foreach ($top_icon['myapps'] as $key => $val) { ?>
       <li style="text-align: center;margin-left: 1rem;margin-right: 3.8rem;">
        <img class="img-circle" style="width:25px;" src="<?php echo base_url().$val->icon ?>">
        <a href="<?php echo site_url('group/'.$val->name) ?>" style="font-size:10px; padding: 0;"><?php echo $val->name ?></a>
      </li>
    <?php } ?>

     <?php foreach ($top_icon['myCompany'] as $key => $val) { ?>
       <li style="text-align: center;;margin-left: 1rem;margin-right: 3.8rem;">
        <img class="img-circle" style="width:25px;" src="<?php echo base_url().$val->icon ?>">
        <a href="<?php echo site_url('group/'.$val->name) ?>" style="font-size:10px; padding: 0;"><?php echo $val->name ?></a>
      </li>
    <?php } ?>
  </ul>
</div>


<nav class="navbar navbar-inverse" style="background:<?php echo $logo->background_color ?>">
  <div class="container-fluid">
    <div class="top_head">
      <div class="col-md-6">
        <div class="col-md-4">
          <div class="navbar-header" style="height: 90px;">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>                        
            </button>
            <?php if(!empty($logo)){ ?>
              <a class="navbar-brand" href="<?php echo base_url() ?>"><img style="height: 90px; width: 68%" class="img-responsive" src="<?php echo base_url().$logo->logo ?>"></a>
            <?php }else{ ?>
              MY GROUP 
            <?php } ?>
            
          </div>
        </div>

          <div class="col-md-8">
            <?php if(!empty($logo)){
               echo '<a class="navbar-brand" href="#"><img style="height:90px" class="img-responsive" src="'.base_url().$logo->name_image.'"></a>';
            }else {
              echo 'MY GROUP';
            } ?>
          </div>
      </div>
      <div class="col-md-6">
        <?php if (!empty($header_sliders)) { ?>

          <div id="carousel-example" class="carousel slide" data-ride="carousel" style="margin-top: 2px;margin-bottom: 2px;">
          
            <div class="carousel-inner">
              <div class="item active">
                <a target="_blank" href="<?php echo $header_sliders->header_ads_url_1 ?>"><img style="height:90px; width: 100%" src="<?php echo base_url().$header_sliders->header_ads1; ?>" /></a>
              </div>
              <div class="item">
                <a target="_blank" href="<?php echo $header_sliders->header_ads_url_2 ?>"><img style="height:90px; width: 100%" src="<?php echo base_url().$header_sliders->header_ads2; ?>" /></a>
              </div>
              <div class="item">
                <a target="_blank" href="<?php echo $header_sliders->header_ads_url_3 ?>"><img style="height:90px;width: 100%" src="<?php echo base_url().$header_sliders->header_ads3; ?>" /></a>
              </div>
            </div>

            <a class="left carousel-control" href="#carousel-example" data-slide="prev">
              <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#carousel-example" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
          </div>

        <?php } ?>
      </div>
    </div>
     
    </div>
     <div class="collapse navbar-collapse" id="myNavbar" style="background: #b20f0f;" >
        <ul class="nav navbar-nav  navbar-lett">
          <?php if (isset($base_url)) { ?>
            <li class="<?php if($navName == 'my-apps') echo 'active' ?>" ><a href="<?php echo site_url('home/change_nav/'.'my-apps') ?>">My Apps</a></li>
            <li class="<?php if($navName == 'my-company') echo 'active' ?>" ><a href="<?php echo site_url('home/change_nav/'.'my-company') ?>">My Company</a></li>
            <li class="<?php if($navName == 'my-onine-apps') echo 'active' ?>"><a href="<?php echo site_url('home/change_nav/'.'my-onine-apps') ?>">My Online Apps</a></li>
            <li  class="<?php if($navName == 'my-offline-apps') echo 'active' ?>"><a href="<?php echo site_url('home/change_nav/'.'my-offline-apps') ?>">My Offline Apps</a></li>
          <?php } ?>
         
        </ul>
         <?php 
            $user = $this->ion_auth->user()->row();
            $user_id = '';
            if (!empty($user)) {
              $user_id = $user->id;
            }
          ?>
          <input type="hidden" id="userIdUrl" value="<?php echo  (!empty($user)) ? $user->id : ''  ?>">
          <form class="form-horizontal" id="profile-edit" action="<?php echo site_url('home/edit_profile') ?>" method="post">
            <input type="hidden" name="user_id" id="user_id">
          </form>
                    
          <ul class="nav navbar-nav  navbar-right">
            <?php if (!empty($user)) { ?>
              <li class="nav-item dropdown active">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span>
                    <?php echo  (!empty($user)) ?  $user->display_name: 'Guest Account'  ?>
                  </span>

                  <?php 
                  $img = base_url().'assets/front/logo.jpg';
                  if (!empty($user)) {
                      if ($user->profile_img !='') {
                        $img = $this->filemanager->getFilePath($user->profile_img);
                      }
                    } ?>

                  <img style="width:22px;margin-right: 1rem;" class="img-circle" src="<?php echo $img ?>">
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a id="editButton" class="dropdown-item" href="javascript:void(0)">My Profile</a>

                    <a class="dropdown-item" href="<?php echo site_url('home/tnc_view') ?>">Terms and Conditions</a>
                    <a class="dropdown-item" href="<?php echo site_url('home/pnp_view') ?>">Privicy And Policy</a>
                    <a class="dropdown-item" href="<?php echo site_url('home/feedback_user') ?>">Feedback</a>
                    <a class="dropdown-item" href="<?php echo site_url('home/profile_change_password') ?>">Change Password</a>
                    <a class="dropdown-item" href="<?php echo site_url('home/logout_desktop')?>">Logout</a>
                  </div>
                </li>  
            <?php }else{ ?>
              <li class="active"><a onclick="register_user()"  href="javascript:void(0)">Login/Register</a></li>
            <?php } ?>
          </ul>
      </div>
  </div>
</nav>

<script type="text/javascript">
  $('#editButton').click(function() {
    var user_id = '<?php echo  $user_id ?>';
    $('#user_id').val(user_id);
    $('#profile-edit').submit();
  });
</script>


<script type="text/javascript">
  $('#profile_login_popup').on('click',function(){
    $('#profile_login').modal('show');
  });
</script>

<style type="text/css">
  .dropdown-menu{
    min-width: 192px;
   
  }

  .dropdown-menu{
    padding: 20px;
    line-height: 3rem;
  }
  .dropdown-menu .dropdown-item{
    display: block;
    border-bottom: 2px solid #337ab7;
        text-decoration: none;
        line-height: 4rem;
  }
</style>


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
    text-transform: uppercase;
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

.log-input {
  font-size: 18px;
  padding: 4px 6px 6px 4px;
  -webkit-appearance: none;
  display: block;
  color: #636363;
  width: 100%;
  border: none;
  border-radius: 0;
  border-bottom: 1px solid #757575;
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

#registration-form input, textarea, select {
 border: 1px solid #ccc;
}

input[type="text"], input[type="email"], input[type="password"], textarea, select {
    border: none;
    /*font-family: "Montserrat";*/
    /*font-size: 12px;*/
    /*font-weight: 400;*/
    letter-spacing: 0.1em;
    line-height: 24px;
    height: 42px;
    padding-left: 20px;
    padding-right: 20px;
    text-transform: none;
    width: 100%;
    outline: none;
}

input[type="checkbox"]:not(:checked) + label, input[type="checkbox"]:checked + label {
    color: #aaaaaa;
    cursor: pointer;
    font-size: 11px;
    /*font-weight: 600;*/
    letter-spacing: 0.1em;
    padding-left: 10px;
    padding-top: 6px;ś
    position: relative;
    text-transform: uppercase;
}


</style>
<script type="text/javascript">
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
</script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/front/css/owl.carousel.css"/>
<script type="text/javascript" src="<?php echo base_url();?>assets/front/js/owl.carousel.min.js"></script>



<script type="text/javascript">
  function register_user() {

    $.ajax({
      url: '<?php echo site_url('home/profile_login_modal'); ?>',
      type: 'post',
      success: function(data) {
        var profile = JSON.parse(data);
        construct_login_profile(profile.country_flag, profile.education, profile.profession);
      }
    });
  }

function construct_login_profile(country_flag, education, professions) {
  var html = `<div class="tab-content">
  <div id="login-form" class="tab-pane fade in active">
    <?php 
      $style = '';
      if ($this->mobile_detect->isMobile()) { 
        $style = 'width:100%';
      } 
    ?>
    <form method="post" class="log-form" style="<?php echo $style ?>">
      <div class="form-group">
        <input type="text" class="form-control" id="mobile_number" name="identity" placeholder="Mobile Number">
      </div>

      <div class="form-group">
        <div class="wrap-input100 validate-input" data-validate="Enter password">
          <input type="password" class="form-control" id="password" name="password"  placeholder="Password">
            <button type="button" class="btn btn-secondary" style="margin-top:-2rem;margin-left: 90%;padding: 0px;border: 0px; display: block; ">
              <i class="fa fa-eye-slash" id="passwordShowIcon"></i>
            </button>
        </div>
      </div>   

      <span class="check left-align">
        <input type="checkbox">
        <label style="font-size:14px; font-weight:400" >Remember Me</label>
      </span>
      <a class="right-align" style="float:right" href="<?php echo site_url('auth/forgot_user_email') ?>">Forgot Password</a>
      <br><br>
      <div class="container-log-btn">
        <button type="button" onclick="login_profile()" name="btn_submit" class="btn btn-info btn-block">
          <span>Login</span>
        </button>

        <a style="float: left;margin-top: 1rem;font-size: 15px; font-weight: 700;" href="#registration-form">Don't have an account? Click Register</a>

      </div>

      <a id="error-login-message" style="float: left;margin-top: 0.5rem;color:red; font-size:14px;" href="javascript:void(0)"></a>
    </form>
    <div class="text-center">
      <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
    </div>
  </div>

  <div id="registration-form" class="tab-pane fade">
    <form method="post"  id="user_registration_form" data-parsley-validate="">
        <div class="form-group">
          <p>Full Name <font color="red">*</font></p>
          <input type="text" required="" autocomplete="off" name="first_name" class="form-control" placeholder="Full Name">
        </div>

        <div class="form-group">
          <p>Display Name <font color="red">*</font></p>
          <input type="text" required="" autocomplete="off" minlength="2" maxlength="12" data-parsley-minlength="2" data-parsley-maxlength="12" data-parsley-trigger="keyup"  data-parsley-error-message="valid input message" name="display_name" class="form-control" placeholder="Display Name">
        </div>

        <div class="form-group">
          <p>Mobile Number <font color="red">*</font></p>
          <div class="col-md-12" style="padding:0; display: flex;">
            <div class="col-md-2" style="padding: 0px 2px 0px 0px;width: 26%;">
              <select class="form-control" style="padding: .3rem .2rem;" name="country_code" id="counry_code">`;
              for(var k in country_flag){
                html += '<option value="'+country_flag[k].phone_code+'">'+country_flag[k].phone_code+'</option>';
              }
              html +=`</select>
            </div>
            <div class="col-md-10" style="padding: 0px 0px 0px 2px ;">
              <input type="text" required="" class="form-control" name="mobile_number" autocomplete="off" data-parsley-validation-threshold="1" data-parsley-trigger="keyup"  data-parsley-type="number"   id="mobileNumber" placeholder="Mobile Number">
            </div>
          </div>
        </div>


        <div class="form-group">
           <p>Alternate Number (optional)</p>

          <div class="col-md-12" style="padding:0; display: flex;" >
            <div class="col-md-2" style="padding: 0px 2px 0px 0px; width: 26%;">
              <select class="form-control" style="padding: .3rem .2rem;"  name="country_code_alter" id="counry_code_alter">`;
            for(var k in country_flag){
              html += '<option value="'+country_flag[k].phone_code+'">'+country_flag[k].phone_code+'</option>';
            }
            html +=`</select>
            </div>

            <div class="col-md-10" style="padding: 0px 0px 0px 2px ;">
              <input type="text" class="form-control" name="mobile_number_alter" id="alternate_number" autocomplete="off" data-parsley-validation-threshold="1" data-parsley-trigger="keyup"  data-parsley-type="number" placeholder="Mobile Number">
            </div>
          </div>
        </div>

        <div class="form-group">
          <p>Email-Id</p>
          <input type="email" class="form-control" autocomplete="off" name="email" placeholder="Email-Id">
        </div>

        <div class="form-group">
          <p>Gender <font color="red">*</font></p>
          <label class="radio-inline" for="gender-0" style="margin-right: 22px;">
            <input type="radio" data-parsley-group="block0"  class="gender-radio" name="gender" id="gender-0" value="M" checked>
              Male
          </label>
          <label class="radio-inline" for="gender-1" style="margin-right: 22px;">
            <input type="radio"  data-parsley-group="block1" class="gender-radio" name="gender" id="gender-1" value="F">
              Female
          </label>
          <label class="radio-inline" for="gender-1" style="margin-right: 22px;">
            <input type="radio"  data-parsley-group="block2" class="gender-radio" name="gender" id="gender-2" value="o">
              Transgender
          </label>
        </div>


        <div class="form-group">
          <p>Marital status <font color="red">*</font></p>
          <label class="radio-inline" for="marital-0" style="margin-right: 22px;">
              <input type="radio" data-parsley-group="block0" class="marital-radio" name="marital" id="marital-0" value="Single" checked>
              Single
          </label>
          <label class="radio-inline" for="marital-1" style="margin-right: 22px;">
              <input type="radio"  data-parsley-group="block1" class="marital-radio" name="marital" id="marital-1" value="Married">
              Married
          </label>
           <label class="radio-inline" for="marital-1" style="margin-right: 22px;">
              <input type="radio"  data-parsley-group="block2"  class="marital-radio" name="marital" id="marital-2" value="Other">
              Other
          </label>
        </div>

        <div class="form-group">
          <p>Date of Birth <font color="red">*</font></p>
          <div class="col-md-12 col-xs-12" style="padding:0; display: flex;"> 
            <div class="col-md-4" style="padding-right: 2px; padding-left: 0; ">`;


         var days = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
         html +=`<select name="from_date" class="form-control" id="from_date">
            <option value=''>Date</option>`;
          for(var day in days){
           
            html += '<option value="'+days[day]+'">'+days[day]+'</option>';
          }

          html +=`</select>
            </div>

            <div class="col-md-4" style="padding-left: 0;padding-right: 2px;">`;
      
        var monthArray =  ['January','February','March','April','May','June','July','August','September','October','November','December'];
        html +=`<select name="from_month" class="form-control" id="from_month" >
              <option value="">Month</option>`;
            for(var month in monthArray){
             
              html += '<option  value="'+monthArray[month]+'">'+monthArray[month]+'</option>';
            }
       html +=`</select>
            </div>

          <div class="col-md-4" style="padding-left: 2px;">
            <select name="from_year" class="form-control">
              <option value="">Year</option>`;
              for (i = new Date().getFullYear(); i > 1900; i--){
                html += '<option value="'+i+'">'+i+'</option>';
              }
              html +=`</select>
              </div>
          </div>
        </div>

        <div class="form-group">
          <p>Country <font color="red">*</font></p>
          <select class="form-control" name="country" required="" onchange="content_get_state()"  id="country">
            <option value="">Select Country</option>`;

            for(var k in country_flag){
              html += '<option value="'+country_flag[k].id+'">'+country_flag[k].country+'</option>';
            }
           html += `</select>
        </div>

        <div class="form-group">
          <p>State / Province <font color="red">*</font></p>
          <select class="form-control" name="state" onchange="get_district_by_state(this.value)" required="" id="state">
            <option value="">Select State / Province</option>
          </select>
        </div>

        <div class="form-group">
          <p>District / City <font color="red">*</font></p>
          <select class="form-control" name="district" required="" id="district">
            <option value="">Select District</option>
          </select>
        </div>

        <div class="form-group">
          <p>Nationality <font color="red">*</font></p>
          <select class="form-control" name="nationality" required="" id="nationality">`;
              for(var k in country_flag){
                
                html += '<option  value="'+country_flag[k].nationality+'">'+country_flag[k].nationality+'</option>';
              }
             html += `</select>
        </div>

        <div class="form-group">
          <p>Eduction / Qualification<font color="red">*</font></p>
          <select class="form-control" name="education" required="" id="education">
            <option value="">Select Eduction / Qualification</option>`;
              for(var k in education){
                html += '<option value="'+education[k].education+'">'+education[k].education+'</option>';
              }
              html += '<option value="education_others">Others</option>';
          html += `</select>
        </div>

        <div class="form-group" id="education_others" style="display: none;">
          <p>Others</p>
          <input type="text" name="education_others" class="form-control" value="" >
        </div>

        <div class="form-group">
          <p>Work / Profession <font color="red">*</font></p>
          <select class="form-control" name="profession" required="" id="profession">
            <option value="">Select Work / Profession</option>`;

              for(var k in professions){
                html += '<option  value="'+professions[k].profession+'">'+professions[k].profession+'</option>';
              }
              html += '<option value="work_others">Others</option>';

           html += `</select>
        </div>

        <div class="form-group" id="work_others" style="display: none;">
          <p>Others</p>
          <input type="text" name="work_others" class="form-control" value="" >
        </div>

        <div class="form-group">
          <p>Password <font color="red">*</font></p>
          <div class="wrap-input100 validate-input" data-validate="Enter password">
            <input data-parsley-errors-container="#errorNew" type="password" id="new" autocomplete="off" name="password" pattern="^.{6}.*$" class="form-control"  placeholder="Password" data-parsley-error-message="This value seems to be invalid" required="">   
            <span class="focus-input100" data-placeholder="&#xf191;"></span>
            <span class="input-group-btn">
              <button type="button" class="btn btn-secondary" style="margin-top:-4em;margin-left: 90%;padding: 0px;border: 0px;">
              <i class="fa fa-eye-slash" id="passwordShowIcon1"></i>
              </button>
            </span>
          </div>
        </div>

        <div class="form-group">
          <p>Confirm Password <font color="red">*</font></p>
          <div class="wrap-input100 validate-input" data-validate="Enter password">
            <input  data-parsley-errors-container="#errorConfirm" type="password" id="new_confirm" pattern="^.{6}.*$" class="form-control" placeholder="Confirm Password" id="password2" data-parsley-error-message="Password not matching" data-parsley-equalto="#new" required="">
            <span class="focus-input100" data-placeholder="&#xf191;"></span>
            <span class="input-group-btn">
              <button type="button" class="btn btn-secondary" style="margin-top:-4em;margin-left: 90%;padding: 0px;border: 0px;">
              <i class="fa fa-eye-slash" id="passwordShowIcon2"></i>
              </button>
            </span>
          </div>
        </div>
        <div class="form-group">
          <center>
            <button type="button" onclick="submit_form()" id="submit_resgiter"  class="btn btn-info">Submit</button>
            <a class="btn btn-danger" onclick="register_model_close()" href="javascript:void(0)">Close</a>
          </center>
        </div>
      </form>
    </div>
</div>`;
  
  $('#login_model-content').html(html);

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

   

  $('#education').on('change',function(){
    var otherEdu = $('#education').val();
    if (otherEdu == 'education_others') {
      $('#education_others').show();
    }else{
      $('#education_others').hide();
    }
  });

  $('#profession').on('change',function(){
    var otherEdu = $('#profession').val();
    if (otherEdu == 'work_others') {
      $('#work_others').show();
    }else{
      $('#work_others').hide();
    }
  });

  $('#profile_login').modal('show');
}

function register_model_close() {
  $('#profile_login').modal('hide');
}


function login_profile() {
  var $form = $('.log-form');
  if ($form.parsley().validate()){
    var form = $('.log-form')[0];
    var formData = new FormData(form);
    $.ajax({
      url: '<?php echo site_url('home/login_profile_popup'); ?>',
      type: 'post',
      data: formData,
      // async: false,
      processData: false,
      contentType: false,
      // cache : false,
      success: function(data) {
        console.log(data);
        if(data!=0) {
          location.reload();
        } else {
          $('#error-login-message').html('Username / Password incorrect. Please try again');
        }
      }
    });
  }
}

function submit_form() {
  var $form = $('#user_registration_form');
  if ($form.parsley().validate()){
  var form = $('#user_registration_form')[0];
  var formData = new FormData(form);
  $('#submit_resgiter').html('Please wait ...');
    $.ajax({
      url: '<?php echo site_url('home/user_register_submit_popup'); ?>',
      type: 'post',
      data: formData,
      // async: false,
      processData: false,
      contentType: false,
      // cache : false,
      success: function(data) {
        $('#submit_resgiter').html('Submit');
        if(data) {
           Swal.fire({
            title: "Successful",
            text: "Register Successfully",
            icon: "success",
          });
          location.reload();
        } else {
          Swal.fire({
              title: "Error",
              text: "Register un- Successfully",
              icon: "error",
          });
        }
      }
    });
  }
}
$('#fileupload').change(function() {
    var src = $(this).val();
    readURL(this);
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

  // $('#fileupload1').change(function() {
  //   var src = $(this).val();
  //   readURL1(this);
  // });

 function readURL1(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#profile_photo1').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
    var id =$('#userIdUrl').val();
    savePhoto1('profile',id);
  }

  function savePhoto1(type, id) {
    $("#photo_" + type).prop('disabled', true).html('<i class="fa fa-spinner fa-spin" style="font-size:20px"></i>');
    var file_data = $('#fileupload1').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data);
    form_data.append('type', type);
    form_data.append('id', id);      
    $.ajax({
      url: '<?php echo site_url('home/profile_pic_update1') ?>',
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

<div class="modal fade login-register-form" class="form-horizontal" id="profile_login" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <ul class="nav nav-tabs">
          <li class="nav-item" style="margin-right:6rem">
            <a class="nav-link active" data-toggle="tab" href="#login-form">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#registration-form">Register</a>
          </li>
        </ul>
      </div>
      <div class="modal-body" id="login_model-content">
        
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  function close_button_refresh() {
    location.reload();
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.12.5/dist/sweetalert2.all.min.js" integrity="sha256-vT8KVe2aOKsyiBKdiRX86DMsBQJnFvw3d4EEp/KRhUE=" crossorigin="anonymous"></script>
<style type="text/css">
.nav-tabs{
  border-bottom: none;
}
.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active{
  color: #ffffff;
  background-color: #0062cc;
  border-color: #dee2e6 #dee2e6 #fff;
}
#profile_login{
  padding-left: 0 !important;
  padding-right: 0 !important;
}

.modal-open{
  padding-right: 0 !important;
}
#navabarpadding{
  padding-right: 0 !important;
}
</style>
<script type="text/javascript">

$('#editButtonMobile').click(function() {
  var user_id = '<?php echo  (!empty($user)) ? $user->id : ''  ?>';
   $.ajax({
        url: '<?php echo site_url('home/edit_profile_mobile'); ?>',
        data: {'user_id': user_id},
        type: 'post',
        success: function(data) {
          var profile = JSON.parse(data);
          construct_edit_profile(profile.profile, profile.country_flag, profile.education, profile.profession);
        }
    });
});
 
function construct_edit_profile(profile_edit, country_flag, education, professions) {
  // console.log(profile_edit);
  var html ='';
  html +=`<form method="post" class="form-horizontal" id="edit_form_user_registration" data-parsley-validate="">
     <div class="panel-body" id="contentId">
          <?php 
            $user = $this->ion_auth->user()->row();
          ?>
          <input type="hidden" id="user_id" name="user_id" value="<?php echo  (!empty($user)) ? $user->id : ''  ?>">
              <div class="profile-image">
                <?php 
                $img = base_url().'assets/front/logo.jpg';
                if (!empty($user)) {
                    if ($user->profile_img !='') {
                      $img = $this->filemanager->getFilePath($user->profile_img);
                    }
                } ?>

              <div class="form-group">
                <label class="control-label col-md-3">Profile Picture </label>
                <div class="container">
                <div class="row">
                <div class="col-md-4 col-4">
                    <?php if (!empty($user)) { ?>
                     <img onclick="$('#fileupload1').click();" class="rounded-circle" id="profile_photo1" style="width:60px;height:60px" src="<?php echo $img ?>">
                      <br>
                    <?php 
                      $classfa = 'camera';
                      if ($this->mobile_detect->isMobile()) {
                        $classfa = 'mobile-camera';
                      }
                    ?>
                      <i onclick="$('#fileupload1').click();" class="fa fa-camera <?php echo $classfa ?>" aria-hidden="true"></i>
                      <input hidden="hidden" type="file" id="fileupload1" class="file" data-preview-file-type="jpeg" name="profile_photo1" accept="image/*">
                      <span id="fileuploadError1" style="color:red;display: block;padding-top:5px;padding-bottom:5px;"></span>
                    <?php }else{ ?>
                      <img class="img-responsive img-circle" id="profile_photo1" style="width:100px;height:100px" src="<?php echo $img ?>">
                    <?php } ?>
                </div>
                <div class="col-md-4 col-8">
                  <h3 id="displaymy"> <?php echo  (!empty($user)) ? ' My '. '<span id="displayName">'.$user->display_name .'</span>' : 'Guest Account'  ?></h3>
                </div>
                </div>
                </div>
              </div>
                
              </div>

            <div class="form-group">
              <label class="control-label col-md-3">Full Name <font color="red">*</font></label>
              <div class="col-md-8">
                 <input type="text" required="" autocomplete="off" name="first_name" value="${profile_edit.first_name}" class="form-control" placeholder="Full Name">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Display Name <font color="red">*</font></label>
              <div class="col-md-8">
                 <input type="text" required="" autocomplete="off" minlength="2" maxlength="12" data-parsley-minlength="2" data-parsley-maxlength="15"  name="display_name" value="${profile_edit.display_name}" class="form-control" placeholder="Display Name">
              </div>
            </div>

             <div class="form-group">
              <label class="control-label col-md-3">Mobile Number <font color="red">*</font></label>
              <div class="col-md-8" style="display: flex;">
                <div class="col-md-2" style="padding: 0px 2px 0px 0px; width:33% ">
                  <select class="form-control" name="country_code" disabled id="counry_code">`;
                    for(var k in country_flag){
                      var selected = '';
                      if (country_flag[k].phone_code == profile_edit.country_code) {
                        selected ='selected';
                      }
                       html += '<option '+selected+' value="'+country_flag[k].phone_code+'">'+country_flag[k].phone_code+'</option>';
                    }
                html +=`</select>
                </div>
                <div class="col-md-10" style="padding: 0px 0px 0px 2px ;">
                  <input type="text" readonly="" class="form-control"  autocomplete="off" value="${profile_edit.phone}" >
                </div>
              </div>
            </div>
           
            <div class="form-group">
              <label class="control-label col-md-3">Alternative  Number (optional)</label>
              <div class="col-md-8" style="display: flex;">`;
              
                  mChecked = (profile_edit.gender == 'M')  ? 'checked' : '';
                  fChecked = (profile_edit.gender == 'F')  ? 'checked' : '';
                  oChecked = (profile_edit.gender == 'o')  ? 'checked' : '';

                  singleChecked = (profile_edit.marital == 'Single')  ? 'checked' : '';
                  marriedChecked = (profile_edit.marital == 'Married')  ? 'checked' : '';
                  otherChecked = (profile_edit.marital == 'Other')  ? 'checked' : '';

                  disabledalter = (profile_edit.alter_number == '') ? '' : 'readonly';

                html += `<div class="col-md-2" style="padding: 0px 2px 0px 0px;width:33% ">
                  <select class="form-control" ${disabledalter} name="country_code_alter" id="counry_code_alter">`;
                    for(var k in country_flag){
                      var selected = '';
                      if (country_flag[k].phone_code == profile_edit.country_code_alter) {
                        selected ='selected';
                      }
                       html += '<option '+selected+' value="'+country_flag[k].phone_code+'">'+country_flag[k].phone_code+'</option>';
                    }
                html +=`</select>
                </div>

                <div class="col-md-10" style="padding: 0px 0px 0px 2px ;">
                  <input type="text" class="form-control" ${disabledalter} name="mobile_number_alter" id="alternate_number" autocomplete="off" data-parsley-validation-threshold="1" value="${profile_edit.alter_number}" data-parsley-trigger="keyup"  data-parsley-type="number" placeholder="Mobile Number">
                </div>
              </div>
            </div>

            <div class="form-group">
            <label class="control-label col-md-3">Email-ID </label>
            <div class="col-md-8">
              <input type="email" class="form-control" autocomplete="off" name="email" value="${profile_edit.email}">
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label" for="gender"> Gender <font color="red">*</font></label>
              <div class="col-md-8">
                  <label class="radio-inline" for="gender-0" style="margin-right: 22px;">

                      <input type="radio" data-parsley-group="block0"  sclass="gender-radio" ${mChecked} name="gender" id="gender-0" value="M" checked>
                      Male
                  </label>
                  <label class="radio-inline" for="gender-1" style="margin-right: 22px;">
                      <input type="radio"  data-parsley-group="block1" class="gender-radio" ${fChecked}  name="gender" id="gender-1" value="F">
                      Female
                  </label>
                   <label class="radio-inline" for="gender-1" style="margin-right: 22px;">
                      <input type="radio"  data-parsley-group="block2" class="gender-radio" name="gender" ${oChecked}  id="gender-2" value="o">
                      Transgender
                  </label>
              </div>
            </div>


            <div class="form-group">
              <label class="col-md-3 control-label" for="gender"> Marital status <font color="red">*</font></label>
              <div class="col-md-8">
                  <label class="radio-inline" for="marital-0" style="margin-right: 22px;">
                      <input type="radio" data-parsley-group="block0" style="margin-right: 0rem; margin-left: 0;" class="marital-radio" name="marital" ${singleChecked} id="marital-0" value="Single" checked>
                      Single
                  </label>
                  <label class="radio-inline" for="marital-1" style="margin-right: 22px;">
                      <input type="radio" ${marriedChecked}  data-parsley-group="block1" style="margin-right: 0rem;" class="marital-radio" name="marital" id="marital-1" value="Married">
                      Married
                  </label>
                   <label class="radio-inline" for="marital-1" style="margin-right: 22px;">
                      <input type="radio" ${otherChecked} data-parsley-group="block2" style="margin-right: 0rem;" class="marital-radio" name="marital" id="marital-2" value="Other">
                      Other
                  </label>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label">Date of Birth <font color="red">*</font></label>
              <div class="col-md-6 col-xs-12" style="padding:0;display: flex;"> 
                <div class="col-md-4" style="padding-right: 2px;">`;


                 var days = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
                 html +=`<select name="from_date" class="form-control" id="from_date">
                    <option value=''>Date</option>`;
                  for(var day in days){
                    var selected = '';
                    if (days[day] == profile_edit.dob_date) {
                      selected ='selected';
                    }
                    html += '<option '+selected+' value="'+days[day]+'">'+days[day]+'</option>';
                  }

                  html +=`</select>
                </div>

                <div class="col-md-4" style="padding-left: 0;padding-right: 2px;">`;
              
                var monthArray =  ['January','February','March','April','May','June','July','August','September','October','November','December'];
                html +=`<select name="from_month" class="form-control" id="from_month" >
                      <option value="">Month</option>`;
                    for(var month in monthArray){
                      var selected = '';
                      if (monthArray[month] == profile_edit.dob_month) {
                        selected ='selected';
                      }
                      html += '<option '+selected+' value="'+monthArray[month]+'">'+monthArray[month]+'</option>';
                    }
               html +=`</select>
                </div>

              <div class="col-md-4" style="padding-left: 2px;">
              <select name="from_year" class="form-control">
                <option value="">Year</option>`;
                for (i = new Date().getFullYear(); i > 1900; i--){
                  var selected = '';
                  if (i == profile_edit.dob_year) {
                   selected = 'selected';
                  }
                  html += '<option '+selected+' value="'+i+'">'+i+'</option>';
                }
              html +=`</select>
                </div>
              </div>
            </div>

              <div class="form-group">
                <label class="col-md-3 control-label">Country <font color="red">*</font></label>
                <div class="col-md-8">
                   <select class="form-control" name="country" required="" id="country">
                   <option value="">Select Country</option>`;

                    for(var k in country_flag){
                      var selected = '';
                      if (country_flag[k].id == profile_edit.country) {
                        selected ='selected';
                      }
                      html += '<option '+selected+' value="'+country_flag[k].id+'">'+country_flag[k].country+'</option>';
                    }
                   html += `</select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 control-label">State / Province <font color="red">*</font></label>
                <div class="col-md-8">
                   <select class="form-control" name="state" onchange="get_district_by_state(this.value)" required="" id="state">
                      <option value="">Select State / Province</option>
                    </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 control-label">District / City <font color="red">*</font></label>
                <div class="col-md-8">
                   <select class="form-control" name="district" required="" id="district">
                      <option value="">Select District</option>
                    </select>
                </div>
              </div>


                <div class="form-group">
                  <label class="col-md-3 control-label">Nationality <font color="red">*</font></label>
                  <div class="col-md-8">
                     <select class="form-control" name="nationality" required="" id="nationality">`;
                      for(var k in country_flag){
                        var selected = '';
                        if (country_flag[k].nationality == profile_edit.nationality) {
                          selected ='selected';
                        }
                        html += '<option '+selected+' value="'+country_flag[k].nationality+'">'+country_flag[k].nationality+'</option>';
                      }
                     html += `</select>
                  </div>
                </div>

                <div class="form-group">
                <label class="col-md-3 control-label">Eduction / Qualification <font color="red">*</font></label>
                <div class="col-md-8">
                   <select class="form-control" name="education" required="" id="education">
                      <option value="">Select Eduction / Qualification</option>`;
                      for(var k in education){
                        var selected = '';
                        if (education[k].education == profile_edit.education) {
                          selected ='selected';
                        }
                         html += '<option '+selected+' value="'+education[k].education+'">'+education[k].education+'</option>';
                      }
                      var selected_others = '';
                        if (profile_edit.education =='education_others') {
                          selected_others ='selected';
                        }
                      html += '<option '+selected_others+' value="education_others">Others</option>';
                  html += `</select>
                </div>
              </div>
              <div class="form-group" id="education_others" style="display: none;">
                <label class="col-md-3 control-label">Others</label>
                <div class="col-md-8">
                  <input type="text" value="${profile_edit.education_others}" name="education_others" class="form-control" value="" >
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 control-label">Work / Profession <font color="red">*</font></label>
                <div class="col-md-8">
                   <select class="form-control" name="profession" required="" id="profession">
                      <option value="">Select Work / Profession</option>`;

                      for(var k in professions){
                        var selected = '';
                        if (professions[k].profession == profile_edit.profession) {
                          selected ='selected';
                        }
                         html += '<option '+selected+' value="'+professions[k].profession+'">'+professions[k].profession+'</option>';
                      }
                      var selected_others = '';
                        if (profile_edit.profession =='work_others') {
                          selected_others ='selected';
                        }
                      html += '<option '+selected_others+' value="work_others">Others</option>';

                   html += `</select>
                </div>
              </div>

              <div class="form-group" id="work_others" style="display: none;">
                <label class="col-md-3 control-label">Others</label>
                <div class="col-md-8">
                  <input type="text"  value="${profile_edit.work_others}" name="work_others" class="form-control" value="" >
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-5">
                </div>
                <div class="col-md-6">
                  <center>
                    <input type="button"  id="update_edit_form"  class="btn btn-info"  value="Update" />
                    <a class="btn btn-danger close_edit_form" href="javascript:void(0)">Close</a>
                  </center>
                </div>
              </div>
              <div class="text-center" id="error">
              <h5 id="update_message"></h5>
              </div>
            </div>
          </div>

        </div>
        </form>`;

    content_get_state_edit(profile_edit.country, profile_edit.state, profile_edit.district);
    $("#contant-profile").html(html);

    $('#fileupload1').change(function() {
      readURL1(this);
    });

    $('#country').on('change',function(){
      content_get_state();
    });

    $('#update_edit_form').on('click',function(){
        edi_form_submit();
    });
   $('#profieEdit').modal('show');

   var education = $('#education').val();
   if (education == 'education_others') {
     $('#education_others').show();
   }

   var profession = $('#profession').val();
   if (profession == 'work_others') {
     $('#work_others').show();
   }

   $('#education').on('change',function(){
    var otherEdu = $('#education').val();
    if (otherEdu == 'education_others') {
      $('#education_others').show();
    }else{
      $('#education_others').hide();
    }
  });

  $('#profession').on('change',function(){
    var otherEdu = $('#profession').val();
    if (otherEdu == 'work_others') {
      $('#work_others').show();
    }else{
      $('#work_others').hide();
    }
  });

   $('.close_edit_form').on('click',function(){
    $('#profieEdit').modal('hide');
    open_setting_profile();
  });


}
</script>

<script type="text/javascript">
  function edi_form_submit() {
    var $form = $('#edit_form_user_registration');
    if ($form.parsley().validate()){
    var form = $('#edit_form_user_registration')[0];
    var formData = new FormData(form);
    $('#update_edit_form').val('Please wait ...').attr('disabled','disabled');
      $.ajax({
        url: '<?php echo site_url('home/user_register_update'); ?>',
        type: 'post',
        data: formData,
        // async: false,
        processData: false,
        contentType: false,
        // cache : false,
        success: function(data) {
        $('#update_edit_form').val('Update').removeAttr('disabled');
          if(data) {
            Swal.fire({
              title: "Successful",
              text: "Update Successfully",
              icon: "success",
            });
            location.reload();
             // $('#update_message').html('<span style="color:green" >Update Successfully</span>')
          } else {
            $('#update_message').html('<span style="color:red" >Something went wrong.</span>');
          }
        }
      });
    } 
}
</script>
<div class="modal fade" id="profieEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog m-0" style="height:auto;">
    <div class="modal-content" style="height:auto; ">
      <div class="modal-header">
        <h3>Edit Profile</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span style="color: #000" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="contant-profile" style="padding: 0;" >
       
      </div>
    </div>
  </div>
</div>

<style type="text/css">

.dark-mode {
  background-color: #3c3a3a;
  color: white !important;
}

.dark-mode #profile_login .modal-content{
  background-color: #3c3a3a;
}
.dark-mode #profieEdit .modal-content{
  background-color: #3c3a3a;
}
#profieEdit .modal-content{
  background-color: #fff;
}
.dark-mode {
  background-color: #3c3a3a;
  color: white !important;
}

.dark-mode #enter_send{
  color: #fff;
}

.dark-mode  .messages.messages-img .item.in .text{
   color: #000;
}


.dark-mode a{
  color: white;
}

.dark-mode .list-group-item{
  background-color: #3c3a3a;
}

.dark-mode small{
  color: white;
}
.dark-mode .navbar-bottom p{
  color: #fff;
}

.mobile-bar{
  position: absolute;
    top: 44%;
    right: 9px;
    border: 1px solid #ccc;
    border-radius: 48px;
    background: #fff;
    font-size: 12px;
    color: #938d8d;
    width: 17px;
    height: 17px;
}
.fa-navicon:before, .fa-reorder:before, .fa-bars:before {
    content: "\f0c9";
    font-size: 10px;
}
.mobile-camera{
  position: absolute;
  left: 54px;
  top: 40px;
  color: #fff;
}
#linebotton p{
  border-bottom: 1px solid #ccc;
  line-height: 3rem;
  margin: 0;
}
#lastChaild{
  border-bottom:none !important;
  line-height: 3rem;
  margin: 0;
}
#linebotton i{
  line-height: 3rem;
}
  #mobile_more .nav-link{
    padding: .5rem 0rem;
  }

.btn-secondary{
  color: #000;
  background-color: inherit;
}
.btn-secondary:hover{
   color: #000;
  background-color: inherit;
}
.btn-secondary:not(:disabled):not(.disabled).active, .btn-secondary:not(:disabled):not(.disabled):active, .show>.btn-secondary.dropdown-toggle{
  color: #000;
  background-color: inherit;
}
</style>

<style type="text/css">

[hidden] {
  display: none!important;
}
.dark-mode #linebotton a{
  color: #007bff;
}
.dark-mode .breadcrumb li{
  color: #000;
}
</style>

<script type="text/javascript">

function content_get_state() {
  var countryId = $('#country').val();
  $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
    var state = jQuery.parseJSON(data);
    var output='';
    output+='<option value="">Select State</option>';
    output1='<option value="">Select District</option>';
    var len=state.length;
    for (var i=0,j=len; i < j; i++) {
      output+='<option value="'+state[i].id+'">'+state[i].state+'</option>'; 
    }
    $('#state').html(output);
    $('#district').html(output1);
 });
}

function content_get_state_edit(countryId, state_id, district_id) {
    $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
      var state = jQuery.parseJSON(data);
      var output='';
      output+='<option value="">Select State</option>';
      var len=state.length;
      for (var i=0,j=len; i < j; i++) {

        var stateEdit = state_id;
        var selected = '';
        if (stateEdit == state[i].id) {
          selected ='selected';
        }
        output+='<option '+selected+' value="'+state[i].id+'">'+state[i].state+'</option>'; 
      }
      $('#state').html(output);
      content_get_district_edit(district_id);
   });
}


function get_district_by_state(state) {
  content_get_district(state);
}
function content_get_district(state_id) {
    $.post("<?php echo site_url('home/get_disctrict_by_stateId')?>",{state_id:state_id},function(data){
      var district = jQuery.parseJSON(data);
      console.log(district);
      var output='';
      output+='<option value="">Select District</option>';
      var len=district.length;
      for (var i=0,j=len; i < j; i++) {
        output+='<option value="'+district[i].id+'">'+district[i].district+'</option>'; 
      }
      $('#district').html(output);
   });
}

function content_get_district_edit(district_id) {
   var state_id =$('#state').val();
    $.post("<?php echo site_url('home/get_disctrict_by_stateId')?>",{state_id:state_id},function(data){
      var district = jQuery.parseJSON(data);
      console.log(district);
      var output='';
      output+='<option value="">Select District</option>';
      var len=district.length;
      for (var i=0,j=len; i < j; i++) {

      var distEdit = district_id
      var selected = '';
        if (distEdit == district[i].id) {
          selected = 'selected';
        } 
        output+='<option '+selected+' value="'+district[i].id+'">'+district[i].district+'</option>'; 
      }
      $('#district').html(output);
   });
}

</script>


<script type="text/javascript">
 

</script>

<script type="text/javascript">
  $('#education').on('change',function(){
    var otherEdu = $('#education').val();
    if (otherEdu == 'education_others') {
      $('#education_others').show();
    }else{
      $('#education_others').hide();
    }
  });

  $('#profession').on('change',function(){
    var otherEdu = $('#profession').val();
    if (otherEdu == 'work_others') {
      $('#work_others').show();
    }else{
      $('#work_others').hide();
    }
  });

</script>


<style type="text/css">
@media only screen and (max-width: 768px){
  input[class=gender-radio] {
    margin: 1px -14px -6px;
  }
  input[name=has_sibling] {
    margin: 0px 3px 0;
  }
  input[name=sibling_in] {
    margin: 1px 0px 0px;
  }
}
</style>

<style type="text/css">
  .login-container .login-box .login-body label{
    /*color: #fff;*/
  }
  
  .login-container .login-box .login-body ul li{
    /*color: #fff;*/
  }

  .login-container{
    /*background: url(../assets/img/1.jpg); */
    /*background-size: 100% 100%; */
    background: #fff;
  }

 /* .login-container .login-box .login-body{
    border: 2px solid #e5e5e5;
    border-radius: 30px;
  }*/

  .login-container .login-box .login-body .login-title{
    color: #000;
  }
 
#watermark{
  opacity: 0.1;
  margin-left: 15%;
  height: 350px;
  position: absolute;
  margin-top: 25%;
}

</style>
<style> 
  ::placeholder { /* Firefox, Chrome, Opera */ 
    color: #000 !important;
  } 
    
  :-ms-input-placeholder { /* Internet Explorer 10-11 */ 
      color: #000 !important;
  } 
    
  ::-ms-input-placeholder { /* Microsoft Edge */ 
      color: #000 !important;
  }
  #get_others_name{
     color: #000 !important;
  }
  #class_name{
     color: #000 !important;
  }
  .login-container .login-box .login-body .form-control{
    color: #000 !important;
  }
  .login-container{
    padding: 0;
  }
</style> 

