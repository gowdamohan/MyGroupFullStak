<link rel="stylesheet" type="text/css"  href="<?php echo base_url();?>assets/back_end/css/theme-default-profile.css"/>
<link rel="stylesheet" type="text/css"  href="<?php echo base_url();?>assets/back_end/css/parsley.css"/>
<link rel="stylesheet" type="text/css"  href="<?php echo base_url();?>assets/back_end/js/parsley.js"/>
<div class="login-container">
  <?php if ($this->mobile_detect->isMobile()) { ?>
    <div class="login-box animated fadeInDown" style="padding-top: 30px; ">
  <?php }else{ ?>
    <div class="login-box animated fadeInDown" style="width: 800px; padding-top: 30px; ">
  <?php } ?>
    <div class="login-body">
      <img id="watermark" src="<?php echo base_url() .'assets/front/logo.jpg' ?>">
      <form method="post" class="form-horizontal" action='<?php echo site_url("home/user_register_update/".$profile_edit->user_id);?>' data-parsley-validate="">

          <input type="hidden" id="userIdUrl" value="<?php echo  (!empty($profile_edit)) ? $profile_edit->id : ''  ?>">

        <div class="panel-body" id="contentId">
          <div class="login-title text-center"><strong>Register form </strong></div>

          <?php 
            $user = $this->ion_auth->user()->row();
          ?>
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
                <div class="col-md-8">
                    <?php if (!empty($user)) { ?>
                     <img onclick="$('#fileupload').click();" class="rounded-circle" id="profile_photo" style="width:60px;height:60px" src="<?php echo $img ?>">
                      <br>
                    <?php 
                      $classfa = 'camera';
                      if ($this->mobile_detect->isMobile()) {
                        $classfa = 'mobile-camera';
                      }
                    ?>
                      <i onclick="$('#fileupload').click();" class="fa fa-camera <?php echo $classfa ?>" aria-hidden="true"></i>
                      <input hidden="hidden" type="file" id="fileupload" class="file" data-preview-file-type="jpeg" name="profile_photo" accept="image/*">
                      <span id="fileuploadError" style="color:red;display: block;padding-top:5px;padding-bottom:5px;"></span>
                    <?php }else{ ?>
                      <img class="img-responsive img-circle" id="profile_photo" style="width:100px;height:100px" src="<?php echo $img ?>">
                    <?php } ?>
                </div>
              </div>
                
              </div>

            <div class="form-group">
              <label class="control-label col-md-3">Full Name <font color="red">*</font></label>
              <div class="col-md-8">
                 <input type="text" required="" autocomplete="off" name="first_name" value="<?php echo $profile_edit->first_name ?>" class="form-control" placeholder="Full Name">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Display Name <font color="red">*</font></label>
              <div class="col-md-8">
                 <input type="text" required="" autocomplete="off" minlength="2" maxlength="15" data-parsley-minlength="2" data-parsley-maxlength="15"  name="display_name" value="<?php echo $profile_edit->display_name ?>" class="form-control" placeholder="Display Name">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Mobile Number <font color="red">*</font></label>
              <div class="col-md-8" style="display: flex;">
                
                <div class="col-md-1" style="padding: 0px 2px 0px 0px;  width: 10%;">


                  <select class="vodiapicker form-control" disabled="" name="country_flag" id="country_flag">
                    <?php foreach ($country_flag as $key => $flag) { ?>
                      <option <?php if($flag->id == $profile_edit->country_flag) echo 'selected' ?> value="<?php echo $flag->id ?>" class="test" data-thumbnail="<?php echo base_url().$flag->country_flag ?>">
                        <img src="<?php echo base_url().$flag->country_flag ?>">
                       </option>
                    <?php } ?>
                  </select>

                  <div class="lang-select">
                    <button class="btn-select" disabled="" value=""></button>
                    <div class="b">
                      <ul  id="a"></ul>
                    </div>
                  </div>
                </div>

                <div class="col-md-2" style="padding: 0px 2px 0px 0px ;">
                  <select class="form-control" name="country_code" disabled id="counry_code">
                    <option value="<?php echo $profile_edit->country_code ?>"><?php echo $profile_edit->country_code ?></option>
                  </select>
                </div>
                <div class="col-md-9" style="padding: 0px 0px 0px 2px ;">
                  <input type="text" readonly="" class="form-control"  autocomplete="off" value="<?php echo $profile_edit->phone ?>" >
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Alternative  Number <br>(optional)</label>
              <div class="col-md-8" style="display: flex;">
                
                <div class="col-md-1" style="padding: 0px 2px 0px 0px;width: 10%;">

                  <select class="vodiapicker1 form-control" name="country_flag_alter" id="country_flag_alter">
                    <?php foreach ($country_flag as $key => $flag) { ?>
                      <option <?php if($flag->id == $profile_edit->country_flag_alter) echo 'selected' ?> value="<?php echo $flag->id ?>" class="test" data-thumbnail="<?php echo base_url().$flag->country_flag ?>">
                        <img src="<?php echo base_url().$flag->country_flag ?>">
                       </option>
                    <?php } ?>
                  </select>

                  <div class="lang-select">
                    <button class="btn-select1" value=""></button>
                    <div class="b1">
                      <ul id="a1"></ul>
                    </div>
                  </div>
                </div>

                <div class="col-md-2" style="padding: 0px 2px 0px 0px ;">
                  <select class="form-control" name="country_code_alter" id="counry_code_alter">
                     <option value="<?php echo $profile_edit->mobile_number_alter ?>"><?php echo $profile_edit->mobile_number_alter ?></option>
                  </select>
                </div>

                <div class="col-md-9" style="padding: 0px 0px 0px 2px ;">
                  <input type="text" class="form-control" name="mobile_number_alter" id="alternate_number" autocomplete="off" data-parsley-validation-threshold="1" data-parsley-trigger="keyup"  data-parsley-type="number" placeholder="Mobile Number">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Email-ID </label>
              <div class="col-md-8">
                <input type="email" class="form-control" autocomplete="off" name="email" value="<?php echo $profile_edit->email ?>">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label" for="gender"> Gender <font color="red">*</font></label>
              <div class="col-md-8">
                  <label class="radio-inline" for="gender-0" style="margin-right: 22px;">
                      <input type="radio" data-parsley-group="block0"  style="margin-right: 0rem; margin-left: 0; " class="gender-radio" <?php if($profile_edit->gender == 'M') echo 'checked' ?> name="gender" id="gender-0" value="M" checked>
                      Male
                  </label>
                  <label class="radio-inline" for="gender-1" style="margin-right: 22px;">
                      <input type="radio"  data-parsley-group="block1"style="margin-right: 0rem;" class="gender-radio" <?php if($profile_edit->gender == 'F') echo 'checked' ?>  name="gender" id="gender-1" value="F">
                      Female
                  </label>
                   <label class="radio-inline" for="gender-1" style="margin-right: 22px;">
                      <input type="radio" style="margin-right: 0rem;"  data-parsley-group="block2" class="gender-radio" name="gender" <?php if($profile_edit->gender == '0') echo 'checked' ?>   id="gender-2" value="o">
                      Transgender
                  </label>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label" for="gender"> Marital status <font color="red">*</font></label>
              <div class="col-md-8">
                  <label class="radio-inline" for="marital-0" style="margin-right: 22px;">
                      <input type="radio" data-parsley-group="block0" style="margin-right: 0rem; margin-left: 0;" class="marital-radio" name="marital" <?php if($profile_edit->marital == 'Single') echo 'checked' ?>  id="marital-0" value="Single" checked>
                      Single
                  </label>
                  <label class="radio-inline" for="marital-1" style="margin-right: 22px;">
                      <input type="radio" <?php if($profile_edit->marital == 'Married') echo 'checked' ?> data-parsley-group="block1" style="margin-right: 0rem;" class="marital-radio" name="marital" id="marital-1" value="Married">
                      Married
                  </label>
                   <label class="radio-inline" for="marital-1" style="margin-right: 22px;">
                      <input type="radio" <?php if($profile_edit->marital == 'Other') echo 'checked' ?> data-parsley-group="block2" style="margin-right: 0rem;" class="marital-radio" name="marital" id="marital-2" value="Other">
                      Other
                  </label>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label">Date of Birth <font color="red">*</font></label>
              <div class="col-md-6 col-xs-12" style="padding:0;display: flex;"> 
                <div class="col-md-4" style="padding-right: 2px;">
                  <select name="from_date" class="form-control" id="from_date">
                    <option value=''>Date</option>
                    <option <?php if($profile_edit->dob_date == '01') echo 'selected' ?> value='01'>01</option>
                    <option <?php if($profile_edit->dob_date == '02') echo 'selected' ?> value='02'>02</option>
                    <option <?php if($profile_edit->dob_date == '03') echo 'selected' ?> value='03'>03</option>
                    <option <?php if($profile_edit->dob_date == '04') echo 'selected' ?> value='04'>04</option>
                    <option <?php if($profile_edit->dob_date == '05') echo 'selected' ?> value='05'>05</option>
                    <option <?php if($profile_edit->dob_date == '06') echo 'selected' ?> value='06'>06</option>
                    <option <?php if($profile_edit->dob_date == '07') echo 'selected' ?> value='07'>07</option>
                    <option <?php if($profile_edit->dob_date == '08') echo 'selected' ?> value='08'>08</option>
                    <option <?php if($profile_edit->dob_date == '09') echo 'selected' ?> value='09'>09</option>
                    <option <?php if($profile_edit->dob_date == '10') echo 'selected' ?> value='10'>10</option>
                    <option <?php if($profile_edit->dob_date == '11') echo 'selected' ?> value='11'>11</option>
                    <option <?php if($profile_edit->dob_date == '12') echo 'selected' ?> value='12'>12</option>
                    <option <?php if($profile_edit->dob_date == '13') echo 'selected' ?> value='13'>13</option>
                    <option <?php if($profile_edit->dob_date == '14') echo 'selected' ?> value='14'>14</option>
                    <option <?php if($profile_edit->dob_date == '15') echo 'selected' ?> value='15'>15</option>
                    <option <?php if($profile_edit->dob_date == '16') echo 'selected' ?> value='16'>16</option>
                    <option <?php if($profile_edit->dob_date == '17') echo 'selected' ?> value='17'>17</option>
                    <option <?php if($profile_edit->dob_date == '18') echo 'selected' ?> value='18'>18</option>
                    <option <?php if($profile_edit->dob_date == '19') echo 'selected' ?> value='19'>19</option>
                    <option <?php if($profile_edit->dob_date == '20') echo 'selected' ?> value='20'>20</option>
                    <option <?php if($profile_edit->dob_date == '21') echo 'selected' ?> value='21'>21</option>
                    <option <?php if($profile_edit->dob_date == '22') echo 'selected' ?> value='22'>22</option>
                    <option <?php if($profile_edit->dob_date == '23') echo 'selected' ?> value='23'>23</option>
                    <option <?php if($profile_edit->dob_date == '24') echo 'selected' ?> value='24'>24</option>
                    <option <?php if($profile_edit->dob_date == '25') echo 'selected' ?> value='25'>25</option>
                    <option <?php if($profile_edit->dob_date == '26') echo 'selected' ?> value='26'>26</option>
                    <option <?php if($profile_edit->dob_date == '27') echo 'selected' ?> value='27'>27</option>
                    <option <?php if($profile_edit->dob_date == '28') echo 'selected' ?> value='28'>28</option>
                    <option <?php if($profile_edit->dob_date == '29') echo 'selected' ?> value='29'>29</option>
                    <option <?php if($profile_edit->dob_date == '30') echo 'selected' ?> value='30'>30</option>
                    <option <?php if($profile_edit->dob_date == '31') echo 'selected' ?> value='31'>31</option>
                  </select>
                </div>

                <div class="col-md-4" style="padding-left: 0;padding-right: 2px;">
                    <?php
                    $monthArray = range(1, 12);
                    ?>
                    <select name="from_month" class="form-control" id="from_month" >
                        <option value="">Month</option>
                        <?php
                        foreach ($monthArray as $month) {
                            $selected = '';
                            if ($month == $profile_edit->dob_month) {
                             $selected = 'selected';
                            }
                            $monthPadding = str_pad($month, 2, "0", STR_PAD_LEFT);
                            $fdate = date("F", strtotime("2015-$monthPadding-01"));
                            echo '<option '.$selected.' value="'.$monthPadding.'">'.$fdate.'</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-4" style="padding-left: 2px;">
                  <?php 
                    $already_selected_value = 1984;
                    $earliest_year = 1950;
                    print '<select name="from_year" class="form-control">';
                    print '<option value="">Year</option>';
                      foreach (range(date('Y'), $earliest_year) as $x) {
                         $selected = '';
                          if ($x == $profile_edit->dob_year) {
                           $selected = 'selected';
                          }

                        print '<option '.$selected.' value="'.$x.'">'.$x.'</option>';
                      }
                    print '</select>';
                  ?>
                </div>
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-3 control-label">Country <font color="red">*</font></label>
            <div class="col-md-8">
               <select class="form-control" name="country" required="" id="country">
                <option value="">Select Country</option>
                  <?php foreach ($country_flag as $key => $country) { ?>
                    <option <?php if($profile_edit->country == $country->id ) echo 'selected' ?>  value="<?php echo $country->id ?>"><?php echo $country->country ?></option>
                  <?php } ?>
                </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-3 control-label">State / Province <font color="red">*</font></label>
            <div class="col-md-8">
               <select class="form-control" name="state" required="" id="state">
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
                 <select class="form-control" name="nationality" required="" id="nationality">
                  <?php foreach ($country_flag as $key => $country) { ?>
                    <option <?php if($profile_edit->nationality == $country->nationality ) echo 'selected' ?>  value="<?php echo $country->nationality ?>"><?php echo $country->nationality ?></option>
                  <?php } ?>
                  </select>
              </div>
            </div>


          	<div class="form-group">
	            <label class="col-md-3 control-label">Eduction / Qualification <font color="red">*</font></label>
	            <div class="col-md-8">
	               <select class="form-control" name="education" required="" id="education">
                    <option value="">Select Eduction / Qualification</option>
                    <?php foreach ($education as $key => $val) { ?>
                      <option <?php if($profile_edit->education == $val->education ) echo 'selected' ?> value="<?php echo $val->education ?>"><?php echo $val->education ?></option>
                    <?php } ?>
                    <option <?php if($profile_edit->education_others == 'education_others' ) echo 'selected' ?> value="education_others">Others</option>
	                </select>
	            </div>
          	</div>

            <div class="form-group" id="education_others" style="display: none;">
              <label class="col-md-3 control-label">Others</label>
              <div class="col-md-8">
                <input type="text" value="<?php echo $profile_edit->education_others ?>" name="education_others" class="form-control" value="" >
              </div>
            </div>

          	<div class="form-group">
	            <label class="col-md-3 control-label">Work / Profession <font color="red">*</font></label>
	            <div class="col-md-8">
	               <select class="form-control" name="profession" required="" id="profession">
                    <option value="">Select Work / Profession</option>
                   <?php foreach ($profession as $key => $val) { ?>
                     <option <?php if($profile_edit->profession == $val->profession ) echo 'selected' ?>  value="<?php echo $val->profession ?>"><?php echo $val->profession ?></option>
                    <?php } ?>
                    <option <?php if($profile_edit->work_others == 'work_others') echo 'selected' ?> value="work_others">Others</option>
	                </select>
	            </div>
          	</div>

            <div class="form-group" id="work_others" style="display: none;">
              <label class="col-md-3 control-label">Others</label>
              <div class="col-md-8">
                <input type="text"  value="<?php echo $profile_edit->work_others ?>" name="work_others" class="form-control" value="" >
              </div>
            </div>

      			<div class="form-group">
      			  <div class="col-md-5">
      			  </div>
      			  <div class="col-md-6">
      			    <button type="submit" class="btn btn-info">Update</button>
                <a class="btn btn-danger" href="<?php echo site_url('home/profile') ?>">Cancel</a>
      			  </div>
      			</div>
      		</div>
      	</form>
      </div>
    </div>
</div>



<style type="text/css">

[hidden] {
    display: none!important;
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


.vodiapicker{
  display: none; 
}

.vodiapicker1{
  display: none; 
}

#a{
  padding-left: 0px;
}

#a1{
  padding-left: 0px;
}


#a img, .btn-select img{
  width: 26px;
  
}

#a1 img, .btn-select1 img{
  width: 26px;
  
}

#a li{
  list-style: none;
  padding-top: 5px;
  padding-bottom: 5px;
}

#a1 li{
  list-style: none;
  padding-top: 5px;
  padding-bottom: 5px;
}


#a li:hover{
 background-color: #F4F3F3;
}

#a1 li:hover{
 background-color: #F4F3F3;
}

#a li img{
  margin: 5px;
}

#a1 li img{
  margin: 5px;
}


#a li span, .btn-select li span{
  margin-left: 30px;
}

#a1 li span, .btn-select1 li span{
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

.b1{
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

.btn-select1{
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

.btn-select1 li{
  list-style: none;
  float: left;
  padding-bottom: 0px;
}

.btn-select:hover li{
  margin-left: 0px;
}

.btn-select1:hover li{
  margin-left: 0px;
}

.btn-select:hover{
  background-color: #F4F3F3;
  border: 1px solid transparent;
  box-shadow: inset 0 0px 0px 1px #ccc;
}

.btn-select1:hover{
  background-color: #F4F3F3;
  border: 1px solid transparent;
  box-shadow: inset 0 0px 0px 1px #ccc;
}

.btn-select:focus{
   outline:none;
}
.btn-select1:focus{
   outline:none;
}
.b{
  position: absolute;
  z-index: 9999;
  background: #fff;
}

.b1{
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

// Alter Number
var langArray1 = [];
  $('.vodiapicker1 option').each(function(){
    var img = $(this).attr("data-thumbnail");
    var text = this.innerText;
    var value = $(this).val();
    var item = '<li><img src="'+ img +'" alt="" value="'+value+'"/><span>'+ text +'</span></li>';
    langArray1.push(item);
  })


  $('#a1').html(langArray1);

  //Set the button value to the first el of the array
  $('.btn-select1').html(langArray1[0]);
  $('.btn-select1').attr('value', 'en');

  //change button stuff on click
  $('#a1 li').click(function(){
     var img = $(this).find('img').attr("src");
     var value = $(this).find('img').attr('value');
     var text = this.innerText;
     var item = '<li><img src="'+ img +'" alt="" /><span>'+ text +'</span></li>';
    $('.btn-select1').html(item);
    $('.btn-select1').attr('value', value);
    $(".b1").toggle();
    //console.log(value);
  });

  $(".btn-select1").click(function(){
    $(".b1").toggle();
  });

  //check local storage for the lang
  var sessionLang = localStorage.getItem('lang');
  if (sessionLang){
    //find an item with value of sessionLang
    var langIndex = langArray.indexOf(sessionLang);
    $('.btn-select1').html(langArray[langIndex]);
    $('.btn-select1').attr('value', sessionLang);
  } else {
     var langIndex = langArray.indexOf('ch');
    $('.btn-select1').html(langArray[langIndex]);
    //$('.btn-select').attr('value', 'en');
  }


   $('#a1 li').click(function(){
      var value = $(this).find('img').attr('value');
       change_country_code1(value);
    });

 $(document).ready(function(){
    var countryFlag1 = $('#country_flag_alter').val();
    change_country_code1(countryFlag1);
  });

 function change_country_code1(country_id) {
    $.ajax({
      url:'<?php echo site_url('home/change_country_code/'); ?>',
      type:'post',
      data: {'country_id':country_id},
      success: function(data) {
        var retData = $.parseJSON(data);
        var output = '<option value="'+retData.phone_code+'" >'+retData.phone_code+'</option>'; 
        $('#counry_code_alter').html(output);
      }
    });
  }


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

$(document).ready(function() {
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

        var stateEdit ='<?php echo $profile_edit->state ?>';
        var selected = '';
        if (stateEdit == state[i].id) {
          selected ='selected';
        }
        output+='<option '+selected+' value="'+state[i].id+'">'+state[i].state+'</option>'; 
      }
      $('#state').html(output);
      content_get_district();
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

      var distEdit ='<?php echo $profile_edit->district ?>';
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
  .login-container{
    padding: 0;
  }
</style> 

<script type="text/javascript">

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
</script>