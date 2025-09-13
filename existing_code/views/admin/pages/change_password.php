<ul class="breadcrumb" id="parent_breadcums">
    <li><a href="#">Dashboard</a></li>
    <li>Change Password</li>
</ul>

<hr>

<div class="page-content-wrap">
    <div class="row" style="margin: 0px">
        <div class="col-md-12" style="padding: 15px;">
            <?php $this->load->library('Mobile_Detect'); ?>
            <div class="card cd_border">
              <div class="card-header panel_heading_new_style_staff_border hidden-xs">
                <div class="row" style="margin: 0px">
                    <div class="col-md-6">
                        <h3 class="card-title panel_title_new_style_staff">
                            <a class="back_anchor" href="#" class="control-primary">
                              <span class="fa fa-arrow-left"></span>
                            </a> 
                            Change Password
                        </h3>
                    </div>
                </div>
              </div>
              <div class="card-header panel_heading_new_style visible-xs">
                <span class="card-title panel_title_new_style"><strong>Change Password</strong></span>
              </div>
              <div class="card-body" style="padding-top: 0px;">
                <p><strong>Notes: </strong><br>
                   1. Password needs to be atleast 8 characters long.<br>
                </p>
                <div class="login-container">
         
                  <div class="login-box animated fadeInDown">
                    <div class="login-body" style="padding:0px;">

                        <form id="demo-form" class="form-horizontal" action="<?php echo site_url().'auth/change_password/';?>" method="post">
                          <div class="form-group">
                              <label class="col-md-2 control-label" for="usernameId">Old Password <font color="red">*</font></label>  
                              <div class="col-md-8">
                                <div class="input-group">
                                    <input data-parsley-errors-container="#errorOld" type="password" id="old" name="old" class="form-control" placeholder="Old Password" required="">
                                    <span class="input-group-addon"><span class="fa fa-eye-slash" id="oldIcon" onclick="showPassword('old', 'oldIcon')"></span></span>
                                </div> 
                                <div id="errorOld"></div> 
                              </div>  
                          </div>
                          <div class="form-group">
                              <label class="col-md-2 control-label" for="usernameId">New Password <font color="red">*</font></label> 
                              <div class="col-md-8">
                                <div class="input-group">
                                    <input data-parsley-errors-container="#errorNew" type="password" id="new" name="new" pattern="^.{8}.*$" class="form-control" placeholder="New Password" data-parsley-error-message="This value seems to be invalid" required=""> 
                                    <span class="input-group-addon"><span class="fa fa-eye-slash" id="newIcon" onclick="showPassword('new', 'newIcon')"></span></span>
                                </div> 
                                <div id="errorNew"></div> 
                              </div>  
                          </div>
                          <div class="form-group">
                              <label class="col-md-2 control-label" for="usernameId">Confirm New Password <font color="red">*</font></label>  
                              <div class="col-md-8">
                                <div class="input-group">
                                    <input  data-parsley-errors-container="#errorConfirm" type="password" id="new_confirm" name="new_confirm" pattern="^.{8}.*$" class="form-control" placeholder="Confirm New Password" data-parsley-error-message="Password not matching" data-parsley-equalto="#new" required="">
                                    <span class="input-group-addon"><span class="fa fa-eye-slash" id="new_confirmIcon" onclick="showPassword('new_confirm', 'new_confirmIcon')"></span></span>
                                </div>  
                                <div id="errorConfirm"></div> 
                              </div>  
                          </div>
                          
                          <center>
                              <div class="form-group boxy">
                                  <button class="btn btn-block" style="border-radius: 4px;background-color: #4165a2;color: white;">Update</button>
                              </div>
                          </center>
                        </form>
                      </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>

<div class="visible-xs">
  <a href="<?php echo site_url('dashboard');?>" id="backBtn" onclick="loader()"><span class="fa fa-mail-reply"></span></a>
</div>

<script type="text/javascript">

  function showPassword(inputId, iconId) {
    var type = $('#'+inputId).attr('type');
    if(type == 'password') {
        $('#'+iconId).removeClass('fa fa-eye-slash').addClass('fa fa-eye');
        $('#'+inputId).attr('type', 'text');
    } else {
        $('#'+iconId).removeClass('fa fa-eye').addClass('fa fa-eye-slash');
        $('#'+inputId).attr('type', 'password');
    }
  }
</script>

<style type="text/css">


.parsley-errors-list li{
  list-style-type: none;
}
.parsley-pattern{
  color: #ca414d;
}

.login-container .login-box .login-body .form-control {
  color: black !important;
  font-size: 18px;
}

.input-group-addon, .input-group-btn {
    vertical-align: middle !important;
}
.login-container {
    background:#fff;
}
    
.boxy
    {
        width: 100%;
    }

.login-container .login-box .login-body {
  width: 100%;
  float: left;
  padding: 20px;
  -moz-border-radius: 0px;
  -webkit-border-radius: 0px;
  border-radius: 8px;
}

.login-title{
  border-bottom:1px solid #00701a;
  padding-bottom: 20px;
}

/*input{
  color:#00701a !important;
  font-size: 16px !important;
}*/

::placeholder{
  color:#c3c3c3 !important;
  /*font-size: 15px;*/
}

.login-container .login-box {
     width: auto !important; 
    margin: 0px auto;
    padding: 0px;
}

.login-container .login-box .login-body .form-control {
    border: 0px;
    background: #fff;
    padding: 8px !important;
    line-height: 10px;
    height: auto;
    border:1px solid #ccc;
}

.form-control {
  background: #fff;
}
.btn
{
    /*padding: 10px 10px;*/
    font-size: 12px;
    width:auto;
}
    
.text-muted {
    color: #777 !important;
}

.parsley-errors-list li.parsley-required, li.parsley-custom-error-message {
    color: #c74141;
}

.form-horizontal .form-group {
    margin-right: -15px;
    margin-left: -20px;
}
html{
  background: white;
}
</style>

