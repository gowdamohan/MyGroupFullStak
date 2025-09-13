<html lang="en">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/back_end/css/login/css/main.css">

<style type="text/css">


.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    cursor: not-allowed;
    background-color: #fff;
    opacity: 1;
}

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

.container {
  width: 40%;
  min-width: 330px;
  position: absolute;
  -webkit-transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  top: 50%;
  left: 50%;
}
form {
  width: 100%;
}

label {
  font-weight: 500;
  color: #101030;
}
input {
  display: block;
  width: 100%;
  margin-top: 5px;
  padding: 12px;
  border-radius: 5px;
  outline: none;
  color: #101030;
}

input[type="button"] {
  background-color: #7f3fff;
  color: #ffffff;
  font-weight: 500;
  font-size: 18px;
  letter-spacing: 1px;
  border: none;
  cursor: pointer;
  margin-top: 20px;
}
p {
  font-size: 12px;
  margin-bottom: 10px;
  display: inline-block;
  color: #ffffff;
  /*padding: 5px 20px;*/
  border-radius: 11px;
  width: 100%;
  text-align: center;
}

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

#password{
  margin: 0;
    height: 38px;
}

#cnfrm-password{
  margin: 0;
  height: 38px;
}

#username{
    margin: 0;
  height: 38px;
}
label {
  font-size: 14px;
  font-weight: 500;
  color: #101030;
}

input::placeholder {
  font-size: 14px;
  font-weight: 500;
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
}
.login100-form-btn-1::before {
   background:#ff1312;
    border-radius: 1.2rem;
} 
.login100-form-btn-1 {
    padding: .2em 0.2em;
    box-sizing: border-box;
    font-size: 14px;
    border-radius: 1.2rem;
    color: #42597a;
    background-color: #eeeef5;
    margin: 0;
    width:100%;
    border: solid 1px #e4e7ef;
    white-space: normal;
    color: #fff;
    background:#ff1312;
    position: relative;
    height:auto;
}

/*.verify, .resend{
  font-size: 14px;
  font-weight: 600;
  width: 46%;
  border-radius: 12px;
}*/
#usernamedisplay{
  margin-bottom: 0px;
  text-align: left;
  font-size: 16px;
  color: #000000;
  /*font-weight: 600;*/
  padding: 0px;
}

.login100-form-title{
    color: #43a047;
}
</style> 
</head>
  <body>
    <div class="limiter">
      <div class="container-login100" style="background:#ccc">
        <div class="wrap-login100 glass">
          <span class="login100-form-logo">
                         <img width="100px" style="margin-bottom:0.8rem" class="img-responsive" src="<?php echo base_url().$logo->logo ?>">
          </span>
          <p class="login100-form-title p-b-34 p-t-27">Register - Successful</p>
          <p style="color:black; font-size: 16px; font-weight:400">Please login with email and password</p>
          <div class="container-login100-form-btn" style="margin-top:2rem;margin-bottom: 2rem;">               
            <a class="login100-form-btn" href="<?php echo site_url('client-login/'.$groupName);?>">Goto Login</a>
          </div>
         </div>
      </div>
    </div>
  </body>
</html>
