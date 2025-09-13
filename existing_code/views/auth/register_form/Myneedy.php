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

    <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/jquery/jquery.min.js"></script>    
    <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/parsley.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.12.5/dist/sweetalert2.all.min.js" integrity="sha256-vT8KVe2aOKsyiBKdiRX86DMsBQJnFvw3d4EEp/KRhUE=" crossorigin="anonymous"></script>
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

.input100:read-only {
    /*background-color: #ccc;*/
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
.abc{
    font-size: 20px;
    color: #8c8c8c;
    content: attr(data-placeholder);
    display: block;
    width: 100%;
    position: absolute;
    top: 12px;
    left: 0px;
    padding-left: 5px;
    width: 10%;
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
                  <form id="register-form" enctype="multipart/form-data"  class="form-horizontal" action="<?php echo site_url().'/home/client_register_submit';?>" style="text-align: center;" method="post">
                    <input type="hidden" name="group_id" id="group_id" value="<?php echo $group_id ?>">
                    <input type="hidden" name="group_name" id="group_name" value="<?php echo $groupName ?>">
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id ?>">

                    <span class="login100-form-title p-b-34 p-t-27">
                        <img style="height: 90px;" src="<?php echo base_url().$logo->logo ?>">
                    </span>


                    <div class="wrap-input100 validate-input" >
                        <input id="first_name" class="input100" type="text" required="" name="first_name" placeholder="Your Name">
                        <span class="glyphicon glyphicon-user abc"></span>
                    </div>


                    <div class="wrap-input100 validate-input" >
                        <input id="mobilenumber" class="input100" type="text" required="" name="mobile_number" placeholder="Mobile Number">
                        <span class="glyphicon glyphicon-phone abc"></span>
                    </div>

                   
                    <div class="wrap-input100 validate-input" >
                        <select name="country" class="input100" onchange="get_country_wise_state()" id="country">
                            <option value="">Select Country</option>
                            <?php foreach ($country as $key => $val) { ?>
                              <option value="<?php echo $val->id ?>"><?php echo $val->country ?> </option>
                            <?php } ?>
                        </select>
                    </div>

                    <script type="text/javascript">
                        function get_country_wise_state() {
                         
                            var countryId =$('#country').val();
                            $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
                              var state = jQuery.parseJSON(data);
                              console.log(state);
                              var output='';
                              output+='<option value="">Select State</option>';
                              var len=state.length;
                              for (var i=0,j=len; i < j; i++) {
                                  output+='<option value="'+state[i].id+'">'+state[i].state+'</option>'; 
                              }
                              $('#state').html(output);
                            });
                          
                        }
                      </script>

                    <div class="wrap-input100 validate-input" >
                        <select name="state" class="input100" onchange="get_state_wise_disctrict()" id="state">
                            <option value="">Select state</option>
                        </select>
                    </div>


                      <script type="text/javascript">
                        function get_state_wise_disctrict() {
                        
                            var state =$('#state').val();
                            $.post("<?php echo site_url('country_controller/get_state_by_district')?>",{state:state},function(data){
                              var districts = jQuery.parseJSON(data);
                              console.log(districts);
                              var output='';
                              output+='<option value="">Select District</option>';
                              var len=districts.length;
                              for (var i=0,j=len; i < j; i++) {
                                  output+='<option value="'+districts[i].id+'">'+districts[i].district+'</option>'; 
                              }
                              $('#district').html(output);
                            });
                        }
                      </script>

                    <div class="wrap-input100 validate-input" >
                        <select name="district" class="input100" id="district">
                            <option value="">Select district</option>
                        </select>
                    </div>

                    
                    <div class="wrap-input100 validate-input" >
                        <input id="pin_code" class="input100" type="text" required="" autocomplete="off" name="address" placeholder="Address">
                    </div>

                    <div class="wrap-input100 validate-input" >
                        <input id="pin_code" class="input100" type="text" required="" autocomplete="off" name="pin_code" placeholder="Pincode">
                    </div>

                    <div class="col-12 text-center loading-icon" style="display: none;">
                        <i class="fa fa-spinner fa-spin" style="font-size: 40px;"></i>
                    </div>
                    <div class="container-login100-form-btn">
                        <span id="error_pin" style="color: red;font-size: 18px;"></span>
                        <button type="submit" id="regsiter" class="login100-form-btn"> <i class="fa fa-sign-in" aria-hidden="true"></i> &nbsp; Submit</button>
                    </div>

                    <div class="text-center p-t-90">
                        <a class="txt1 btn-warning btn-block" href="<?php echo site_url('client-login/'.$groupName);?>">
                        Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
     </div>



</body>
</html>