<!DOCTYPE html>
<html lang="en">
<head>
    <!--<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />


    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/back_end/css/login/css/util.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/back_end/css/login/css/main.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back_end/css/theme-default-profile.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back_end/css/bootstrap/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back_end/css/fontawesome/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/back_end/css/login/fonts/iconic/css/material-design-iconic-font.min.css">
<style>
.glass
    {
width:400px;
background:inherit;
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
background: inherit;
/*box-shadow: inset 0 0 3000px rgba(255, 255,255,.5);*/
filter:blur(5px);
margin:-20px;
}
    
.input100 {
      font-family: 'Roboto', sans-serif;
    font-size: 14px;
    color: #000000;
    line-height: 1.2;
    display: block;
    width: 100%;
    height: 45px;
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
    top: 0px;
    left: 4px;
    pointer-events: none;
}
.wrap-input100 {
    width: 100%;
    position: relative;
    border: none;
    margin-bottom: 10px;
}
.login100-form-btn::before {
    content: "";
    display: block;
    position: absolute;
    z-index: -1;
    width: 100%;
    height: 100%;
    border-radius: 8px;
    background-color: #43a047;
    top: 0;
    left: 0;
    opacity: 1;
    -webkit-transition: all 0.4s;
    -o-transition: all 0.4s;
    -moz-transition: all 0.4s;
    transition: all 0.4s;
}   
.login100-form-btn {
  font-family: 'Roboto', sans-serif;
    font-size: 22px;
    color: #FFFFFF;
    line-height: 1.2;
    display: -webkit-box;
    display: -webkit-flex;
    display: -moz-box;
    display: -ms-flexbox;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0 8px;
    width:100%;
    height: 40px;
    border-radius: 8px;
    background: #43a047;
    position: relative;
    z-index: 1;
    -webkit-transition: all 0.4s;
    -o-transition: all 0.4s;
    -moz-transition: all 0.4s;
    transition: all 0.4s;
}
    
.txt1 {
   font-family: 'Roboto', sans-serif;
   font-weight: 500;
    font-size: 16px;
    color: #11 !important;
    line-height: 1.5;
    padding: 6px 30px;
    border-radius: 8px;
    margin: 10px 0px;
}
.txt1:hover {
    color: #f0f8ff !important;
}
    .contact100-form-checkbox {
    padding-left: 5px;
    padding-top: 5px;
    padding-bottom: 24px;
}

.btn-warning {
    background-color: #ffb550;
    border-color: #ffb550;
}

.btn-info {
    background-color: #2d8275;
    border-color: #2d8275;
}
</style>    
<style type="text/css">
  .logo { 
  /*  border: 2px solid #607D8B;
    padding: 10px;
    border-radius: 14px;
    margin-top: -12px;*/
    text-align: center;
  }
</style>
</head>
<body>
   <?php if ($this->session->flashdata('flashError')) { ?>
        <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Error!</strong> <?php echo $this->session->flashdata('flashError'); ?>
        </div>
    <?php } else if ($this->session->flashdata('flashSuccess')) { ?>
        <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Success!</strong> <?php echo $this->session->flashdata('flashSuccess'); ?>
        </div>
    <?php } ?>
    <div class="limiter">
        <div class="container-login100" style="background-image: url('<?php echo base_url() . 'assets/client_bg.png' ?>');">
            <div class="wrap-login100 glass">
                  <form id="demo-form" autocomplete="off" class="form-horizontal" action="<?php echo site_url().'/auth/client_login/'.$groupName ?>" method="post">
                    <div class="logo float-left">
                      <a href="#intro" class="scrollto">
                         <?php 
                          if (!empty($subGroup)) { ?>
                              <img style="height: 90px;" src="<?php echo base_url().'assets/front/img/god.png' ?>">
                          <?php }else{ ?>
                            <img style="height: 90px; " src="<?php echo base_url().$logo->logo ?>">
                          <?php } ?>
                      </a>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="Enter username">
                        <input id="username" class="input100" type="text" name="identity" placeholder=" Register Email-Id">
                        <span class="focus-input100" data-placeholder="&#xf207;"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <input class="input100 form-control" type="password" name="password" placeholder="Password" id="password">
                        <span class="focus-input100" data-placeholder="&#xf191;"></span>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-secondary" style="margin-top:-4em;margin-left: 90%;padding: 0px;border: 0px;">
                            <i class="fa fa-eye-slash" id="passwordShowIcon"></i>
                            </button>
                          </span>
                    </div>
                    <p>
                        <a style="color: #fff" href="<?php echo site_url().'forgot-client/'.$groupName ?>" class="btn btn-link btn-block">Forgot your password?</a>
                    </p>
                     <!-- <a style="float: right;" href="<?php //echo site_url().'forgot' ?>">Forgot your password?</a> -->
                    <div class="contact100-form-checkbox">
                        <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember"> 
                        <label class="label-checkbox100" for="ckb1">
                            Remember me
                        </label>

                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn"> <i class="fa fa-sign-in" aria-hidden="true"></i> &nbsp; Login</button>
                    </div>

                    <div class="text-center p-t-90">
                    <?php $uri = $this->uri->segment(3); 
                        if (!empty($uri)) { ?>
                        <a class="txt1 btn-warning btn-block" href="<?php echo site_url('god-register-form/'.$groupName.'/'.'Mygod');?>">
                            Register
                        </a>
                    <?php }else{ ?>
                        <a class="txt1 btn-warning btn-block" href="<?php echo site_url('register-form/'.$groupName);?>">
                            Register
                        </a>
                    <?php } ?>
                   
                  
                    </div>
                </form>
            </div>
        </div>
     </div>
<script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/jquery/jquery.min.js"></script>    
<script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/parsley.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.12.5/dist/sweetalert2.all.min.js" integrity="sha256-vT8KVe2aOKsyiBKdiRX86DMsBQJnFvw3d4EEp/KRhUE=" crossorigin="anonymous"></script>


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
</script>

</body>
</html>