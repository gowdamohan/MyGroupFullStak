 <link rel="stylesheet" type="text/css"  href="<?php echo base_url();?>assets/back_end/css/theme-default.css"/>
<div class="login-container">
  <?php if ($this->mobile_detect->isMobile()) { ?>
    <div class="login-box animated fadeInDown" style="padding-top: 30px; ">
  <?php }else{ ?>
    <div class="login-box animated fadeInDown" style="width: 800px; padding-top: 30px; ">
  <?php } ?>
    <div class="login-body">
      <img id="watermark" src="<?php echo base_url() .'assets/front/logo.jpg' ?>">
      <form method="post" class="form-horizontal"  data-parsley-validate="">
        <div class="panel-body" id="contentId">
          <div class="login-title"><img width="90px" height="90px" src="<?php echo $this->filemanager->getFilePath($profile_database->profile_img);?>"></div>
            <div class="form-group">
              <label class="control-label col-md-3">Full Name <font color="red">*</font></label>
              <div class="col-md-8">
                <p><?php echo $profile_database->first_name ?></p>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Display Name <font color="red">*</font></label>
              <div class="col-md-8">
                <p><?php echo $profile_database->display_name ?></p>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Mobile Number <font color="red">*</font></label>
              <div class="col-md-8">
                <p><?php echo $profile_database->phone ?></p>
              </div>
            </div>

             <div class="form-group">
              <label class="control-label col-md-3">Alter Mobile Number <font color="red">*</font></label>
              <div class="col-md-8">
                <p><?php echo $profile_database->mobile_number_alter ?></p>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Email-ID </label>
              <div class="col-md-8">
                <p><?php echo $profile_database->email ?></p>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label" for="gender"> Gender <font color="red">*</font></label>
              <div class="col-md-8">
                <p><?php echo $profile_database->gender ?></p>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label" for="gender"> Martial <font color="red">*</font></label>
              <div class="col-md-8">
                <p><?php echo $profile_database->marital ?></p>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label">Date of Birth</label>
              <div class="col-md-8" style="padding:0"> 
                <p><?php echo $profile_database->dob ?></p>
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-3 control-label">Country </label>
            <div class="col-md-8">
              <p><?php echo $profile_database->country_name ?></p>
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-3 control-label">State / Province </label>
            <div class="col-md-8">
              <p><?php echo $profile_database->state_name ?></p>
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-3 control-label">District / City </label>
            <div class="col-md-8">
              <p><?php echo $profile_database->district_name ?></p>
            </div>
          </div>


            <div class="form-group">
              <label class="col-md-3 control-label">Nationality</label>
              <div class="col-md-8">
                <p><?php echo $profile_database->nationality ?></p>
              </div>
            </div>


            <div class="form-group">
              <label class="col-md-3 control-label">Eduction / Qualification</label>
              <div class="col-md-8">
                <p><?php echo $profile_database->education ?></p>
              </div>
            </div>


            <div class="form-group">
              <label class="col-md-3 control-label">Work / Profession</label>
              <div class="col-md-8">
                <p><?php echo $profile_database->profession ?></p>
              </div>
            </div>
            <div class="container-login100-form-btn">
              <center>
              <a href="<?php echo site_url('admin_controller/public_database') ?>"> Back</a>
              </center>
            </div>
    </div>
  </form>
</div>
</div>
</div>



<style type="text/css">
  .vodiapicker{
  display: none; 
}

#a{
  padding-left: 0px;
}

#a img, .btn-select img{
  width: 26px;
  
}

#a li{
  list-style: none;
  padding-top: 5px;
  padding-bottom: 5px;
}

#a li:hover{
 background-color: #F4F3F3;
}

#a li img{
  margin: 5px;
}

#a li span, .btn-select li span{
  margin-left: 30px;
}

/* item list */

.b{
  display: none;
  width: 100%;
  max-width: 350px;
  box-shadow: 0 6px 12px rgba(0,0,0,.175);
  border: 1px solid rgba(0,0,0,.15);
  border-radius: 5px;
  
}

.open{
  display: show !important;
}

.btn-select{
  width: 100%;
  max-width: 350px;
  height: 40px;
  border-radius: 5px;
  background-color: #fff;
  border: 1px solid #ccc;
 
}
.btn-select li{
  list-style: none;
  float: left;
  padding-bottom: 0px;
}

.btn-select:hover li{
  margin-left: 0px;
}

.btn-select:hover{
  background-color: #F4F3F3;
  border: 1px solid transparent;
  box-shadow: inset 0 0px 0px 1px #ccc;
  
  
}

.btn-select:focus{
   outline:none;
}
.b{
  position: absolute;
    z-index: 9999;
    background: #fff;
}

</style>
<script type="text/javascript">
  //test for getting url value from attr
  // var img1 = $('.test').attr("data-thumbnail");
  // console.log(img1);

  //test for iterating over child elements
  var langArray = [];
  $('.vodiapicker option').each(function(){
    var img = $(this).attr("data-thumbnail");
    var text = this.innerText;
    var value = $(this).val();
    var item = '<li><img src="'+ img +'" alt="" value="'+value+'"/><span>'+ text +'</span></li>';
    langArray.push(item);
  })

  $('#a').html(langArray);

  //Set the button value to the first el of the array
  $('.btn-select').html(langArray[0]);
  $('.btn-select').attr('value', 'en');

  //change button stuff on click
  $('#a li').click(function(){
     var img = $(this).find('img').attr("src");
     var value = $(this).find('img').attr('value');
     var text = this.innerText;
     var item = '<li><img src="'+ img +'" alt="" /><span>'+ text +'</span></li>';
    $('.btn-select').html(item);
    $('.btn-select').attr('value', value);
    $(".b").toggle();
    //console.log(value);
  });

  $(".btn-select").click(function(){
          $(".b").toggle();
    });

  //check local storage for the lang
  var sessionLang = localStorage.getItem('lang');
  if (sessionLang){
    //find an item with value of sessionLang
    var langIndex = langArray.indexOf(sessionLang);
    $('.btn-select').html(langArray[langIndex]);
    $('.btn-select').attr('value', sessionLang);
  } else {
     var langIndex = langArray.indexOf('ch');
    console.log(langIndex);
    $('.btn-select').html(langArray[langIndex]);
    //$('.btn-select').attr('value', 'en');
  }


   $('#a li').click(function(){
      var value = $(this).find('img').attr('value');
       change_country_code(value);
    });

  $(document).ready(function(){
    var countryFlag = $('#country_flag').val();
    change_country_code(countryFlag);
  });

  function change_country_code(country_id) {
    $.ajax({
      url:'<?php echo site_url('home/change_country_code/'); ?>',
      type:'post',
      data: {'country_id':country_id},
      success: function(data) {
        var retData = $.parseJSON(data);
        var output = '<option value="'+retData.phone_code+'" >'+retData.phone_code+'</option>'; 
        $('#counry_code').html(output);
      }
    });
  }

$('#country').on('change',function(){
    content_get_state();
});
function content_get_state() {
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


$('#state').on('change',function(){
    content_get_district();
});
function content_get_district() {
   var state_id =$('#state').val();
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
  #contentId{
    top: 22%;
    width: 100%;
    /*background: #e5e5e5;*/
    border: 2px solid #e5e5e5;
    border-radius: 30px;
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
</style> 

