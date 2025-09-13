
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/back_end/css/login/fonts/iconic/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/back_end/css/login/css/util.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/back_end/css/login/css/main.css">
      <link rel="stylesheet" type="text/css"  href="<?php echo base_url();?>assets/back_end/css/theme-default.css"/>
<!--===============================================================================================-->
</head>
<body>
<div class="login-container">
    <div class="col-md-12">
        <?php if($this->session->flashdata('flashError')) { ?>
            <div style="margin-top: 60px" class="col-md-5 col-md-offset-4">
                <div style="width: 400px" class="alert alert-danger" role="alert">
                    <center>
                    <?php echo $this->session->flashdata("flashError");?></center>
                </div>
            </div>
        <?php } ?> 
    </div>
   
    <div class="login-box animated fadeInDown">
        <!--  <div class="login-logo"></div> -->
        <div class="login-body">
         <div class="login-title">
                   <span style="background-color: white !important; border-radius: 25px;padding: 8px;overflow: hidden;float: left;border: solid #76d275 1px;">
                   <img class="img-responsive" src="<?php echo base_url().'assets/front/img/logo.jpg' ?>"  style="width: 24px; height: 24px;float: left;"> 
                   </span>
                   
                  <span style="color: black;margin-left: 10px;line-height: 37px;font-weight: bold;"> <?php echo lang('change_password_heading');?></span></div>
         <form id="demo-form" class="form-horizontal" action="<?php echo site_url().'auth/change_password/';?>" method="post">
            <div class="form-group">
                <div class="col-md-12">
                <?php echo form_input($old_password);?>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                 <?php echo form_input($new_password);?>
                  <small class="text-muted"> <?php echo sprintf(lang('change_password_new_password_label'),$min_password_length )?></small>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <?php echo form_input($new_password_confirm);?>
                 <small class="text-muted">  <?php echo lang('change_password_new_password_confirm_label', 'new_password_confirm');?></small>
              </div>
            </div>
                   <center>
            <div class="form-group boxy">
              <button class="btn btn-info btn-block" style="border-radius: 4px;background-color: #00701a;border: solid 1px #00701a;"><?= lang('change_password_submit_btn')?></button>
            </div>
            <div class="form-group boxy">
            <a href="<?php echo site_url('user_controller/profile'); ?>" style="border-radius: 4px;" class="btn btn-warning btn-block">Back to Dashboard</a>
          
            </div>
                  </center>
          </form>
        </div>
    </div>
    </div>
    
<style type="text/css">
   
   .parsley-errors-list li{
       list-style-type: none;
   }
   .parsley-pattern{
      color: #fff;
   }
   
.login-container .login-box .login-body {
    width: 100%;
    float: left;
    background:#D3D3D3 ;
    padding: 20px;
    -moz-border-radius: 0px;
    -webkit-border-radius: 0px;
    border-radius: 8px;
      box-shadow: 0px 2px 25px 2px #797979;
      

}     
.login-container {
    
    background:#eeeeee;
}
      
.boxy
      {
            width: 100%;
      }
      
.login-container .login-box .login-body .form-control {
    border: 0px;
    background: white;
    padding: 10px 15px;
    color: #080808;
    line-height: 20px;
    height: auto;
}
.btn
{
      padding: 10px 15px;
      font-size: 16px;
      
}
      
.text-muted {
    color: #777 !important;
}

</style>
</body>
</html>

