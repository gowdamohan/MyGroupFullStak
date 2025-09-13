<?php
  function union_is_enabled($enabled_fields, $filter) {
    foreach ($enabled_fields as $f) {
      if ($f == $filter)
        return 1;
    }
    return 0;
  }

  function union_is_required_fields($required_fields, $filter){
    foreach ($required_fields as $f) {
      if ($f == $filter)
        return 1;
    }
    return 0;
  }
?>

<ul class="breadcrumb">
  <li><a href="<?php echo site_url('dashboard') ?>">Dashboard</a></li>
  <li class="active">Application form fields</li>
</ul>
<div class="panel panel-default">
  <form enctype="multipart/form-data" method="post" id="demo-form" action="<?php echo site_url('admin_controller/header_leader_registration_insert');?>" data-parsley-validate="" class="form-horizontal">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-titile">Header leader Registration
            <a style="float: right;" href="<?php echo site_url('admin_controller/header_leader_index') ?>" class="btn btn-primary">View Application</a>
          </h3>
        </div>
        <?php 
        $country = 0;
        $state = 0;
        $district = 0;
        switch ($location_type) {
          case '1': // International
            $country =0;
            $state = 0;
            $district = 0;
            break;
          case '2': // National
            $country = 1;
            $state = 0;
            $district = 0;
            break;
          case '3': // Regional
            $country = 1;
            $state = 1;
            $district = 0;
            break;
          case '4': // Local
            $country = 1;
            $state = 1;
            $district = 1;
            break;
          default:
            $country = 0;
            $state = 0;
            $district = 0;
            break;
        }
         ?>
        <div class="panel-body">
          <div class="col-md-8 col-md-offset-1">
            <div class="form-group" >
              <label class="control-label col-md-3">Leader</label>
              <div class="col-md-8">
                <select class="form-control"  name="location_type" id="location_type">
                  <option value="<?php echo $location_type ?>"><?php echo str_replace('_', ' ', strtoupper($leader_head)) ?></option>
                </select>
              </div>
            </div>

            <?php if ($union_location_type->location_type == 1) { ?>
               <div class="form-group" style="<?php echo ($country == 1) ? 'display: block;':'display: none' ?>">
                <label class="control-label col-md-3">Country <?php echo ($country == 1) ? '<font color="red" >*</font>':'' ?></label>
                <div class="col-md-8">
                  <select class="form-control" <?php echo ($country == 1) ? 'required':'' ?> name="union_location_country" id="union_location_country">
                    <option value="">Select Country</option>
                    <?php foreach ($country_flag as $key => $country) { ?>
                      <option value="<?php echo $country->id ?>"><?php echo $country->country ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            <?php } ?>

             <?php if ($union_location_type->location_type == 1 || $union_location_type->location_type == 2) { ?>
              <div class="form-group" style="<?php echo ($state == 1) ? 'display: block;':'display: none' ?>">
                <label class="control-label col-md-3">State <?php echo ($state == 1) ? '<font color="red" >*</font>':'' ?></label>
                <div class="col-md-8">
                    <select class="form-control" <?php echo ($state == 1) ? 'required':'' ?> name="union_location_state" id="union_location_state">
                      <option value="">Select State / Province</option>
                    </select>
                </div>
              </div>
            <?php } ?>
            
             <?php if ($union_location_type->location_type == 2 || $union_location_type->location_type == 3) { ?>
              <div class="form-group" style="<?php echo ($district == 1) ? 'display: block;':'display: none' ?>">
                <label class="control-label col-md-3">District <?php echo ($district == 1) ? '<font color="red" >*</font>':'' ?></label>
                <div class="col-md-8">
                  <select class="form-control" <?php echo ($district == 1) ? 'required':'' ?> name="union_location_district" id="union_location_district">
                    <option value="">Select District</option>
                  </select>
                </div>
              </div>
            <?php } ?>

           
            
          <?php if(union_is_enabled($enabled_fields, 'full_name')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Full Name <?php if(union_is_required_fields($required_fields,'full_name')) echo '<font color="red">*</font>' ?> </label>
              <div class="col-md-8">
                 <input type="text" <?php if(union_is_required_fields($required_fields,'full_name')) echo 'required' ?> autocomplete="off" name="full_name" class="form-control" placeholder="Full Name">
              </div>
            </div>
          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'display_name')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Dispaly Name <?php if(union_is_required_fields($required_fields,'display_name')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                 <input type="text" <?php if(union_is_required_fields($required_fields,'display_name')) echo 'required' ?> autocomplete="off" name="display_name" class="form-control" placeholder="Display Name">
              </div>
            </div>
          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'mobile_number')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Mobile Number <?php if(union_is_required_fields($required_fields,'mobile_number')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                 <input type="text" <?php if(union_is_required_fields($required_fields,'mobile_number')) echo 'required' ?> autocomplete="off" name="mobile_number" class="form-control" placeholder="Mobile Number">
              </div>
            </div>
          <?php endif ?>

         <?php if(union_is_enabled($enabled_fields, 'email_id')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Email-id <?php if(union_is_required_fields($required_fields,'email_id')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                 <input type="text" autocomplete="off" <?php if(union_is_required_fields($required_fields,'email_id')) echo 'required' ?> name="email_id" class="form-control" placeholder="Email-Id">
              </div>
            </div>
          <?php endif ?>


          <?php if(union_is_enabled($enabled_fields, 'father_name')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Father Name <?php if(union_is_required_fields($required_fields,'father_name')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                 <input type="text" autocomplete="off" <?php if(union_is_required_fields($required_fields,'father_name')) echo 'required' ?> name="father_name" class="form-control" placeholder="Father Name">
              </div>
            </div>
          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'mother_name')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Mother Name <?php if(union_is_required_fields($required_fields,'mother_name')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                 <input type="text" autocomplete="off" <?php if(union_is_required_fields($required_fields,'mother_name')) echo 'required' ?> name="mother_name" class="form-control" placeholder="Mother Name">
              </div>
            </div>
          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'date_of_birth')) :  ?>

            <div class="form-group">
              <label class="control-label col-md-3">Date of Birth <?php if(union_is_required_fields($required_fields,'date_of_birth')) echo '<font color="red">*</font>' ?> </label>
              <div class="col-md-8">
                <div class="col-md-4" style="padding-right: 2px;">
                      <select name="from_date" <?php if(union_is_required_fields($required_fields,'date_of_birth')) echo 'required' ?> class="form-control" id="from_date">
                        <option value=''>Date</option>
                        <option value='01'>01</option>
                        <option value='02'>02</option>
                        <option value='03'>03</option>
                        <option value='04'>04</option>
                        <option value='05'>05</option>
                        <option value='06'>06</option>
                        <option value='07'>07</option>
                        <option value='08'>08</option>
                        <option value='09'>09</option>
                        <option value='10'>10</option>
                        <option value='11'>11</option>
                        <option value='12'>12</option>
                        <option value='13'>13</option>
                        <option value='14'>14</option>
                        <option value='15'>15</option>
                        <option value='16'>16</option>
                        <option value='17'>17</option>
                        <option value='18'>18</option>
                        <option value='19'>19</option>
                        <option value='20'>20</option>
                        <option value='21'>21</option>
                        <option value='22'>22</option>
                        <option value='23'>23</option>
                        <option value='24'>24</option>
                        <option value='25'>25</option>
                        <option value='26'>26</option>
                        <option value='27'>27</option>
                        <option value='28'>28</option>
                        <option value='29'>29</option>
                        <option value='30'>30</option>
                        <option value='31'>31</option>
                      </select>
                    </div>

                  <div class="col-md-4" style="padding-left: 0;padding-right: 2px;">
                      <?php
                      $monthArray = range(1, 12);
                      ?>
                      <select name="from_month" <?php if(union_is_required_fields($required_fields,'date_of_birth')) echo 'required' ?> class="form-control"  id="from_month" >
                          <option value="">Month</option>
                          <?php
                          foreach ($monthArray as $month) {
                              $monthPadding = str_pad($month, 2, "0", STR_PAD_LEFT);
                              $fdate = date("F", strtotime("2015-$monthPadding-01"));
                              echo '<option value="'.$monthPadding.'">'.$fdate.'</option>';
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
                          print '<option value="'.$x.'">'.$x.'</option>';
                        }
                      print '</select>';
                    ?>
                  </div>
              </div>
            </div>

          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'gender')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Gender <?php if(union_is_required_fields($required_fields,'gender')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                <label class="radio-inline" for="gender-0" style="margin-right: 22px;" >
                  <input type="radio" data-parsley-group="block0" style="margin-right: 0rem;" class="gender-radio" name="gender" id="gender-0" value="M" checked>
                  Male
              </label>
              <label class="radio-inline" for="gender-1" style="margin-right: 22px;">
                  <input type="radio" data-parsley-group="block1" style="margin-right: 0rem;" class="gender-radio" name="gender" id="gender-1" value="F">
                  Female
              </label>
               <label class="radio-inline" for="gender-1" style="margin-right: 22px;">
                  <input type="radio" data-parsley-group="block2" style="margin-right: 0rem;" class="gender-radio" name="gender" id="gender-2" value="o">
                  Transgender
              </label>
              </div>
            </div>
          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'nationality')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Nationality <?php if(union_is_required_fields($required_fields,'nationality')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                <select class="form-control" <?php if(union_is_required_fields($required_fields,'nationality')) echo 'required' ?> name="nationality" id="nationality">
                  <?php foreach ($country_flag as $key => $val) { ?>
                    <option value="<?php echo $val->nationality ?>"><?php echo $val->nationality ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'martial_status')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Martial Status <?php if(union_is_required_fields($required_fields,'martial_status')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                <label class="radio-inline" for="marital-0" style="margin-right: 22px;">
                  <input type="radio" data-parsley-group="block0" style="margin-right: 0rem; " class="marital-radio" name="martial_status" id="marital-0" value="Single" checked>
                  Single
                </label>
                <label class="radio-inline" for="marital-1" style="margin-right: 22px;">
                    <input type="radio" data-parsley-group="block1" style="margin-right: 0rem;" class="marital-radio" name="martial_status" id="marital-1" value="Married">
                    Married
                </label>
                 <label class="radio-inline" for="marital-1" style="margin-right: 22px;">
                    <input type="radio" data-parsley-group="block2" style="margin-right: 0rem;" class="marital-radio" name="martial_status" id="marital-2" value="Other">
                    Other
                </label>
              </div>
            </div>
          <?php endif ?>


          <?php if(union_is_enabled($enabled_fields, 'spouse_name')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Spouse Name <?php if(union_is_required_fields($required_fields,'spouse_name')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                 <input type="text" <?php if(union_is_required_fields($required_fields,'spouse_name')) echo 'required' ?> autocomplete="off" name="spouse_name" class="form-control" placeholder="Spouse Name">
              </div>
            </div>
          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'education')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Education </label>
              <div class="col-md-8">
                <select class="form-control" <?php if(union_is_required_fields($required_fields,'education')) echo 'required' ?> name="education" id="education">
                    <option value="">Select Eduction / Qualification <?php if(union_is_required_fields($required_fields,'education')) echo '<font color="red">*</font>' ?></option>
                    <?php foreach ($education as $key => $val) { ?>
                      <option value="<?php echo $val->education ?>"><?php echo $val->education ?></option>
                    <?php } ?>
                </select>
              </div>
            </div>
          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'work_profession')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Work Profession <?php if(union_is_required_fields($required_fields,'work_profession')) echo '<font color="red">*</font>' ?> </label>
              <div class="col-md-8">
                <select class="form-control" <?php if(union_is_required_fields($required_fields,'work_profession')) echo 'required' ?> name="work_profession" id="work_profession">
                  <option value="">Select Work / Profession</option>
                  <?php foreach ($profession as $key => $val) { ?>
                   <option value="<?php echo $val->profession ?>"><?php echo $val->profession ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'languages_known')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Languages Known <?php if(union_is_required_fields($required_fields,'languages_known')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                <select class="form-control" <?php if(union_is_required_fields($required_fields,'languages_known')) echo 'required' ?> name="languages_known" id="languages_known">
                  <option value="">Select Languages Known </option>
                   <?php foreach ($language as $key => $val) { ?>
                   <option value="<?php echo $val->id ?>"><?php echo $val->lang_1 .'-' .$val->lang_2 ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'blood_group')) :  ?>
            <?php $blood_groups = ['A +ve','B +ve','O +ve','AB +ve','A -ve','B -ve','O -ve','AB -ve']; ?>
            <div class="form-group">
              <label class="control-label col-md-3">Blood Group <?php if(union_is_required_fields($required_fields,'blood_group')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                <select class="form-control" <?php if(union_is_required_fields($required_fields,'blood_group')) echo 'required' ?> name="blood_group" id="blood_group">
                  <option value="">Select Blood Group </option>
                   <?php foreach ($blood_groups as $key => $val) { ?>
                   <option value="<?php echo $val ?>"><?php echo $val ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'present_address')) :  ?>
            <center>
              <h3>Present Address <?php if(union_is_required_fields($required_fields,'present_address')) echo '<font color="red">*</font>' ?></h3>
            </center>
          
              <div class="form-group">
                <label class="control-label col-md-3">Area <?php if(union_is_required_fields($required_fields,'present_address')) echo '<font color="red">*</font>' ?></label>
                <div class="col-md-8">
                   <input type="text" <?php if(union_is_required_fields($required_fields,'present_address')) echo 'required' ?> autocomplete="off" name="present_area" class="form-control" placeholder="Area Name">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3">Country <?php if(union_is_required_fields($required_fields,'present_address')) echo '<font color="red">*</font>' ?></label>
                <div class="col-md-8">
                  <select class="form-control" <?php if(union_is_required_fields($required_fields,'present_address')) echo 'required' ?> name="present_country" id="present_country">
                    <option value="">Select Country</option>
                    <?php foreach ($country_flag as $key => $country) { ?>
                      <option value="<?php echo $country->id ?>"><?php echo $country->country ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>


              <div class="form-group">
                <label class="control-label col-md-3">State <?php if(union_is_required_fields($required_fields,'present_address')) echo '<font color="red">*</font>' ?></label>
                <div class="col-md-8">
                    <select class="form-control" <?php if(union_is_required_fields($required_fields,'present_address')) echo 'required' ?> name="present_state" id="present_state">
                      <option value="">Select State / Province</option>
                    </select>
                </div>
              </div>

          

              <div class="form-group">
                <label class="control-label col-md-3">District <?php if(union_is_required_fields($required_fields,'present_address')) echo '<font color="red">*</font>' ?></label>
                <div class="col-md-8">
                  <select class="form-control" <?php if(union_is_required_fields($required_fields,'present_address')) echo 'required' ?> name="present_district" id="present_district">
                    <option value="">Select District</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3">Pincode <?php if(union_is_required_fields($required_fields,'present_address')) echo '<font color="red">*</font>' ?></label>
                <div class="col-md-8">
                   <input type="text" <?php if(union_is_required_fields($required_fields,'present_address')) echo 'required' ?> autocomplete="off" name="present_pincode" class="form-control" placeholder="Pincode">
                </div>
              </div>
          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'permanent_address')) :  ?>
            <center>
              <h3>Permanent Address</h3>
            </center>
              <div class="form-group">
                <label class="control-label col-md-3">Area <?php if(union_is_required_fields($required_fields,'permanent_address')) echo '<font color="red">*</font>' ?></label>
                <div class="col-md-8">
                   <input type="text" <?php if(union_is_required_fields($required_fields,'permanent_address')) echo 'required' ?>  autocomplete="off" name="permanent_area" class="form-control" placeholder="Enter Area">
                </div>
              </div>


              <div class="form-group">
                <label class="control-label col-md-3">Country <?php if(union_is_required_fields($required_fields,'permanent_address')) echo '<font color="red">*</font>' ?></label>
                <div class="col-md-8">
                  <select class="form-control" <?php if(union_is_required_fields($required_fields,'permanent_address')) echo 'required' ?> name="permanent_country" id="permanent_country">
                    <option value="">Select Country</option>
                    <?php foreach ($country_flag as $key => $country) { ?>
                      <option value="<?php echo $country->id ?>"><?php echo $country->country ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>


              <div class="form-group">
                <label class="control-label col-md-3">State <?php if(union_is_required_fields($required_fields,'permanent_address')) echo '<font color="red">*</font>' ?></label>
                <div class="col-md-8">
                    <select class="form-control" <?php if(union_is_required_fields($required_fields,'permanent_address')) echo 'required' ?> name="permanent_state" id="permanent_state">
                      <option value="">Select State / Province</option>
                    </select>
                </div>
              </div>

          
          
              <div class="form-group">
                <label class="control-label col-md-3">District <?php if(union_is_required_fields($required_fields,'permanent_address')) echo '<font color="red">*</font>' ?></label>
                <div class="col-md-8">
                  <select class="form-control" <?php if(union_is_required_fields($required_fields,'permanent_address')) echo 'required' ?> name="permanent_district" id="permanent_district">
                    <option value="">Select District</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3">Pincode <?php if(union_is_required_fields($required_fields,'permanent_address')) echo '<font color="red">*</font>' ?></label>
                <div class="col-md-8">
                   <input type="text" <?php if(union_is_required_fields($required_fields,'permanent_address')) echo 'required' ?> autocomplete="off" name="permanent_pincode" class="form-control" placeholder="Pincode">
                </div>
              </div>

          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'introducer_name')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Introducer Name <?php if(union_is_required_fields($required_fields,'introducer_name')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                 <input type="text" <?php if(union_is_required_fields($required_fields,'introducer_name')) echo 'required' ?> autocomplete="off" name="introducer_name" class="form-control" placeholder="Introducer Name">
              </div>
            </div>
          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'introducer_number')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Introducer Number <?php if(union_is_required_fields($required_fields,'introducer_number')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                 <input type="text" <?php if(union_is_required_fields($required_fields,'introducer_number')) echo 'required' ?> autocomplete="off" name="introducer_number" class="form-control" placeholder="Introducer Number">
              </div>
            </div>
          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'member_photo')) :  ?>
            
            <div class="form-group">
              <label class="control-label col-sm-3" for="no_of_days">Header/Leader Photo <?php if(union_is_required_fields($required_fields,'member_photo')) echo '<font color="red">*</font>' ?></label>
              <div class="col-sm-8">
                  <img  onclick="$('#fileupload').click();"  class="img-responsive img-circle" id="profile_photo" src="<?php echo base_url().'assets/front/logo.png' ?>" style="width:60px;height:60px">

                <input hidden="hidden" <?php if(union_is_required_fields($required_fields,'member_photo')) echo 'required' ?> type="file" id="fileupload" class="file" data-preview-file-type="jpeg" name="profile_photo" accept="image/*">
               
              </div>
            </div>            
          <?php endif ?>
        </div>
        </div>
        <div class="panel-footer">
          <center><button type="submit" class="btn btn-info">Submit</button></center>
        </div>
      </div>
  </form>
</div>

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
}

$('#present_country').on('change',function(){
    present_content_get_state();
});
function present_content_get_state() {
   var countryId =$('#present_country').val();
    $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
      var state = jQuery.parseJSON(data);
      console.log(state);
      var output='';
      output+='<option value="">Select State</option>';
      var len=state.length;
      for (var i=0,j=len; i < j; i++) {        
        output+='<option value="'+state[i].id+'">'+state[i].state+'</option>'; 
      }
      $('#present_state').html(output);
   });
}


$('#present_state').on('change',function(){
    present_content_get_district();
});
function present_content_get_district() {
   var state_id =$('#present_state').val();
    $.post("<?php echo site_url('home/get_disctrict_by_stateId')?>",{state_id:state_id},function(data){
      var district = jQuery.parseJSON(data);
      console.log(district);
      var output='';
      output+='<option value="">Select District</option>';
      var len=district.length;
      for (var i=0,j=len; i < j; i++) {        
        output+='<option value="'+district[i].id+'">'+district[i].district+'</option>'; 
      }
      $('#present_district').html(output);
   });
}

$('#permanent_country').on('change',function(){
    content_get_state();
});
function content_get_state() {
   var countryId =$('#permanent_country').val();
    $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
      var state = jQuery.parseJSON(data);
      console.log(state);
      var output='';
      output+='<option value="">Select State</option>';
      var len=state.length;
      for (var i=0,j=len; i < j; i++) {        
        output+='<option value="'+state[i].id+'">'+state[i].state+'</option>'; 
      }
      $('#permanent_state').html(output);
   });
}


$('#permanent_state').on('change',function(){
    content_get_district();
});
function content_get_district() {
   var state_id =$('#permanent_state').val();
    $.post("<?php echo site_url('home/get_disctrict_by_stateId')?>",{state_id:state_id},function(data){
      var district = jQuery.parseJSON(data);
      console.log(district);
      var output='';
      output+='<option value="">Select District</option>';
      var len=district.length;
      for (var i=0,j=len; i < j; i++) {        
        output+='<option value="'+district[i].id+'">'+district[i].district+'</option>'; 
      }
      $('#permanent_district').html(output);
   });
}

var uSelectedType = '<?php echo $union_location_type->location_type ?>';

if (uSelectedType == 2) {
  var countryId ='<?php echo $union_location_type->union_country ?>';
  $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
    var state = jQuery.parseJSON(data);
    var output='';
    output+='<option value="">Select State</option>';
    var len=state.length;
    for (var i=0,j=len; i < j; i++) {        
      output+='<option value="'+state[i].id+'">'+state[i].state+'</option>'; 
    }
    $('#union_location_state').html(output);
  });
}

if (uSelectedType == 3) {
  var state_id ='<?php echo $union_location_type->union_state ?>';
  $.post("<?php echo site_url('home/get_disctrict_by_stateId')?>",{state_id:state_id},function(data){
    var district = jQuery.parseJSON(data);
    console.log(district);
    var output='';
    output+='<option value="">Select District</option>';
    var len=district.length;
    for (var i=0,j=len; i < j; i++) {        
      output+='<option value="'+district[i].id+'">'+district[i].district+'</option>'; 
    }
    $('#union_location_district').html(output);
  });
}

$('#union_location_country').on('change',function(){
    location_content_get_state();
});
function location_content_get_state() {
   var countryId =$('#union_location_country').val();
    $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
      var state = jQuery.parseJSON(data);
      var output='';
      output+='<option value="">Select State</option>';
      var len=state.length;
      for (var i=0,j=len; i < j; i++) {        
        output+='<option value="'+state[i].id+'">'+state[i].state+'</option>'; 
      }
      $('#union_location_state').html(output);
   });
}


$('#union_location_state').on('change',function(){
    location_content_get_district();
});
function location_content_get_district() {
   var state_id =$('#union_location_state').val();
    $.post("<?php echo site_url('home/get_disctrict_by_stateId')?>",{state_id:state_id},function(data){
      var district = jQuery.parseJSON(data);
      console.log(district);
      var output='';
      output+='<option value="">Select District</option>';
      var len=district.length;
      for (var i=0,j=len; i < j; i++) {        
        output+='<option value="'+district[i].id+'">'+district[i].district+'</option>'; 
      }
      $('#union_location_district').html(output);
   });
}
</script>