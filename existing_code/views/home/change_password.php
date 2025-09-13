<ul class="breadcrumb" id="parent_breadcums">
    <li><a href="<?php echo site_url('home/profile') ?>">Profile > </a>  Change Password</li>
</ul>

 <div class="container mb-5 mt-5">
    <div class="col-md-6 offset-md-3">
       <div class="panel panel-default">
            <div class="panel-heading mb-3">
                <h3 class="panel-title">
                    Change Password
                </h3>
            </div>
            <form id="demo-form" class="form-horizontal" action="<?php echo site_url().'auth/change_password/'.$groupname;?>" method="post" data-parsley-validate method="post">
                <div class="panel-body profile">

                    <div class="form-group">
                      <label class="control-label col-md-5">Old Password <font color="red">*</font></label>
                      <div class="col-md-7">
                         <input data-parsley-errors-container="#errorOld" type="password" id="old" name="old" class="form-control" placeholder="Old Password" required="">
                      </div>
                       <div id="errorOld"></div> 
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-5">New Password <font color="red">*</font></label>
                      <div class="col-md-7">
                        <input data-parsley-errors-container="#errorNew" type="password" id="new" name="new" pattern="^.{6}.*$" class="form-control" placeholder="New Password" data-parsley-error-message="This value seems to be invalid" required=""> 
                      </div>
                       <div id="errorNew"></div> 
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-5">Confirm New Password <font color="red">*</font></label>
                      <div class="col-md-7">
                        <input  data-parsley-errors-container="#errorConfirm" type="password" id="new_confirm" name="new_confirm" pattern="^.{6}.*$" class="form-control" placeholder="Confirm New Password" data-parsley-error-message="Password not matching" data-parsley-equalto="#new" required="">
                      </div>
                      <div id="errorConfirm"></div> 
                    </div>

                </div>
                <div class="panel-footer">
                    <center>
                        <div class="form-group boxy">
                            <button class="btn btn-block" style="border-radius: 4px;background-color: #4165a2;color: white;">Update</button>
                        </div>
                        <div class="form-group boxy">
                            <a class="btn btn-block" href="<?php echo site_url('home') ?>"> Back </a>
                        </div>
                    </center>
                </div>
            </form>
        </div>
    </div>
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

