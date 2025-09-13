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
<div class="panel panel-default">
  <form enctype="multipart/form-data" method="post" id="demo-form" action="<?php echo site_url('myunions/member_registration_update/'.$edit_member_form->id);?>" data-parsley-validate="" class="form-horizontal">
    <input type="hidden" name="groupname" value="<?php echo $groupname ?>">
    <input type="hidden" name="union_type" value="<?php echo $union_type ?>">
    <input type="hidden" name="client_user_id" value="<?php echo $client_user_id ?>">
    <input type="hidden" name="apply_for" value="Public">
    <input type="hidden" name="member_status" value="Received">
      <div class="panel panel-default">
        <div class="panel-heading text-center">
          <h3 class="panel-titile">Member Registration</h3>
        </div>
        
        <?php 
        $country = 0;
        $state = 0;
        $district = 0;
        switch ($union_location_type->location_type) {
          case '1': // International
            $country = 1;
            $state = 1;
            $district = 1;
            break;
          case '2': // National
            $country = 0;
            $state = 1;
            $district = 1;
            break;
          case '3': // Regional
            $country = 0;
            $state = 0;
            $district = 1;
            break;
          case '4': // Local
            $country = 0;
            $state = 0;
            $district = 0;
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
            <div class="form-group" style="<?php echo ($country == 1) ? 'display: block':'display: none' ?>">
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


          <div class="form-group" style="<?php echo ($state == 1) ? 'display: block':'display: none' ?>">
            <label class="control-label col-md-3">State <?php echo ($state == 1) ? '<font color="red" >*</font>':'' ?></label>
            <div class="col-md-8">
                <select class="form-control" <?php echo ($state == 1) ? 'required':'' ?> name="union_location_state" id="union_location_state">
                  <option value="">Select State / Province</option>
                </select>
            </div>
          </div>

      
      
          <div class="form-group" style="<?php echo ($district == 1) ? 'display: block':'display: none' ?>">
            <label class="control-label col-md-3">District <?php echo ($district == 1) ? '<font color="red" >*</font>':'' ?></label>
            <div class="col-md-8">
              <select class="form-control" <?php echo ($district == 1) ? 'required':'' ?> name="union_location_district" id="union_location_district">
                <option value="">Select District</option>
              </select>
            </div>
          </div>
          
          <?php if(union_is_enabled($enabled_fields, 'full_name')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Full Name <?php if(union_is_required_fields($required_fields,'full_name')) echo '<font color="red">*</font>' ?> </label>
              <div class="col-md-8">
                 <input type="text" <?php if(union_is_required_fields($required_fields,'full_name')) echo 'required' ?> autocomplete="off" name="full_name" class="form-control" value='<?php echo $edit_member_form->full_name ?>' placeholder="Full Name">
              </div>
            </div>
          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'display_name')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Dispaly Name <?php if(union_is_required_fields($required_fields,'display_name')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                 <input type="text" <?php if(union_is_required_fields($required_fields,'display_name')) echo 'required' ?> autocomplete="off" name="display_name" class="form-control" value='<?php echo $edit_member_form->display_name ?>' placeholder="Display Name">
              </div>
            </div>
          <?php endif ?>

            <div class="form-group">
              <label class="control-label col-md-3">Mobile Number <?php if(union_is_required_fields($required_fields,'mobile_number')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                 <input type="text" <?php if(union_is_required_fields($required_fields,'mobile_number')) echo 'required' ?> autocomplete="off" name="mobile_number" readonly value='<?php echo $edit_member_form->mobile_number ?>' class="form-control">
              </div>
            </div>

         <?php if(union_is_enabled($enabled_fields, 'email_id')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Email-id <?php if(union_is_required_fields($required_fields,'email_id')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                 <input type="text" autocomplete="off" <?php if(union_is_required_fields($required_fields,'email_id')) echo 'required' ?> name="email_id" class="form-control" value='<?php echo $edit_member_form->email_id ?>' placeholder="Email-Id">
              </div>
            </div>
          <?php endif ?>


          <?php if(union_is_enabled($enabled_fields, 'father_name')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Father Name <?php if(union_is_required_fields($required_fields,'father_name')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                 <input type="text" autocomplete="off" <?php if(union_is_required_fields($required_fields,'father_name')) echo 'required' ?> name="father_name" value='<?php echo $edit_member_form->father_name ?>' class="form-control" placeholder="Father Name">
              </div>
            </div>
          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'mother_name')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Mother Name <?php if(union_is_required_fields($required_fields,'mother_name')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                 <input type="text" autocomplete="off" <?php if(union_is_required_fields($required_fields,'mother_name')) echo 'required' ?> name="mother_name" value='<?php echo $edit_member_form->mother_name ?>'  class="form-control" placeholder="Mother Name">
              </div>
            </div>
          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'date_of_birth')) :  ?>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3">Date of Birth <?php if(union_is_required_fields($required_fields,'date_of_birth')) echo '<font color="red">*</font>' ?> <font color="red">*</font></label>
              <div class="col-12 row">
                <div class="col-4" style="padding-right: 2px;">
                      <select name="from_date" <?php if(union_is_required_fields($required_fields,'date_of_birth')) echo 'required' ?> class="form-control" id="from_date">
                        <option value=''>Date</option>
                        <option <?php if($edit_member_form->dob_date == '01') echo 'selected' ?> value='01'>01</option>
                        <option <?php if($edit_member_form->dob_date == '02') echo 'selected' ?> value='02'>02</option>
                        <option <?php if($edit_member_form->dob_date == '03') echo 'selected' ?> value='03'>03</option>
                        <option <?php if($edit_member_form->dob_date == '04') echo 'selected' ?> value='04'>04</option>
                        <option <?php if($edit_member_form->dob_date == '05') echo 'selected' ?> value='05'>05</option>
                        <option <?php if($edit_member_form->dob_date == '06') echo 'selected' ?> value='06'>06</option>
                        <option <?php if($user_regiser->dob_date == '07') echo 'selected' ?> value='07'>07</option>
                        <option <?php if($user_regiser->dob_date == '08') echo 'selected' ?> value='08'>08</option>
                        <option <?php if($user_regiser->dob_date == '09') echo 'selected' ?> value='09'>09</option>
                        <option <?php if($user_regiser->dob_date == '10') echo 'selected' ?> value='10'>10</option>
                        <option <?php if($user_regiser->dob_date == '11') echo 'selected' ?> value='11'>11</option>
                        <option <?php if($user_regiser->dob_date == '12') echo 'selected' ?> value='12'>12</option>
                        <option <?php if($user_regiser->dob_date == '13') echo 'selected' ?>value='13'>13</option>
                        <option <?php if($user_regiser->dob_date == '14') echo 'selected' ?> value='14'>14</option>
                        <option <?php if($user_regiser->dob_date == '15') echo 'selected' ?> value='15'>15</option>
                        <option <?php if($user_regiser->dob_date == '16') echo 'selected' ?> value='16'>16</option>
                        <option <?php if($user_regiser->dob_date == '17') echo 'selected' ?>value='17'>17</option>
                        <option <?php if($user_regiser->dob_date == '18') echo 'selected' ?> value='18'>18</option>
                        <option <?php if($user_regiser->dob_date == '19') echo 'selected' ?> value='19'>19</option>
                        <option <?php if($user_regiser->dob_date == '20') echo 'selected' ?> value='20'>20</option>
                        <option <?php if($user_regiser->dob_date == '21') echo 'selected' ?> value='21'>21</option>
                        <option <?php if($user_regiser->dob_date == '22') echo 'selected' ?> value='22'>22</option>
                        <option <?php if($user_regiser->dob_date == '23') echo 'selected' ?> value='23'>23</option>
                        <option <?php if($user_regiser->dob_date == '24') echo 'selected' ?> value='24'>24</option>
                        <option <?php if($user_regiser->dob_date == '25') echo 'selected' ?> value='25'>25</option>
                        <option <?php if($user_regiser->dob_date == '26') echo 'selected' ?> value='26'>26</option>
                        <option <?php if($user_regiser->dob_date == '27') echo 'selected' ?> value='27'>27</option>
                        <option <?php if($user_regiser->dob_date == '28') echo 'selected' ?> value='28'>28</option>
                        <option <?php if($user_regiser->dob_date == '29') echo 'selected' ?> value='29'>29</option>
                        <option <?php if($user_regiser->dob_date == '30') echo 'selected' ?> value='30'>30</option>
                        <option <?php if($user_regiser->dob_date == '31') echo 'selected' ?> value='31'>31</option>
                      </select>
                    </div>

                   <div class="col-4" style="padding-left: 0;padding-right: 2px;">
                      <?php
                      $monthArray = range(1, 12);
                      ?>
                      <select name="from_month" <?php if(union_is_required_fields($required_fields,'date_of_birth')) echo 'required' ?> class="form-control"  id="from_month" >
                          <option value="">Month</option>
                          <?php
                          foreach ($monthArray as $month) {
                              $monthPadding = str_pad($month, 2, "0", STR_PAD_LEFT);
                              $fdate = date("F", strtotime("2015-$monthPadding-01"));
                              ?>
                             <option <?php if($user_regiser->dob_month == $monthPadding) echo 'selected' ?> value="<?php echo $monthPadding ?>"><?php echo $fdate ?></option>
                        <?php
                          }
                          ?>
                      </select>
                    </div>

                  <div class="col-4" style="padding-left: 2px;">
                    <?php 
                      $already_selected_value = 1984;
                      $earliest_year = 1950;
                      print '<select name="from_year" class="form-control">';
                      print '<option value="">Year</option>';
                        foreach (range(date('Y'), $earliest_year) as $x) {
                          $selected = '';
                          if ($user_regiser->dob_year == $x) {
                            $selected = 'selected';
                          }
                          print '<option '.$selected.' value="'.$x.'">'.$x.'</option>';
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
                  <input type="radio" data-parsley-group="block0" <?php if($user_regiser->gender == 'M') echo 'checked' ?> style="margin-right: 0rem;" class="gender-radio" name="gender" id="gender-0" value="M">
                  Male
              </label>
              <label class="radio-inline" for="gender-1" style="margin-right: 22px;">
                  <input type="radio" data-parsley-group="block1" <?php if($user_regiser->gender == 'F') echo 'checked' ?> style="margin-right: 0rem;" class="gender-radio" name="gender" id="gender-1" value="F">
                  Female
              </label>
               <label class="radio-inline" for="gender-1" style="margin-right: 22px;">
                  <input type="radio" data-parsley-group="block2" <?php if($user_regiser->gender == 'o') echo 'checked' ?> style="margin-right: 0rem;" class="gender-radio" name="gender" id="gender-2" value="o">
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
                    <option <?php if($user_regiser->nationality == $val->nationality) echo 'selected' ?> value="<?php echo $val->nationality ?>"><?php echo $val->nationality ?></option>
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
                  <input type="radio" data-parsley-group="block0" <?php if($user_regiser->marital == 'Single') echo 'checked' ?> style="margin-right: 0rem; " class="marital-radio" name="martial_status" id="marital-0" value="Single" checked>
                  Single
                </label>
                <label class="radio-inline" for="marital-1" style="margin-right: 22px;">
                    <input type="radio" data-parsley-group="block1" <?php if($user_regiser->marital == 'Married') echo 'checked' ?> style="margin-right: 0rem;" class="marital-radio" name="martial_status" id="marital-1" value="Married">
                    Married
                </label>
                 <label class="radio-inline" for="marital-1" style="margin-right: 22px;">
                    <input type="radio" data-parsley-group="block2" <?php if($user_regiser->marital == 'Other') echo 'checked' ?> style="margin-right: 0rem;" class="marital-radio" name="martial_status" id="marital-2" value="Other">
                    Other
                </label>
              </div>
            </div>
          <?php endif ?>


          <?php if(union_is_enabled($enabled_fields, 'spouse_name')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Spouse Name <?php if(union_is_required_fields($required_fields,'spouse_name')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                 <input type="text" <?php if(union_is_required_fields($required_fields,'spouse_name')) echo 'required' ?> autocomplete="off" value='<?php echo $edit_member_form->spouse_name ?>'  name="spouse_name" class="form-control" placeholder="Spouse Name">
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
                      <option <?php if($edit_member_form->education == $val->education) echo 'selected' ?> value="<?php echo $val->education ?>"><?php echo $val->education ?></option>
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
                   <option <?php if($edit_member_form->profession == $val->profession) echo 'selected' ?> value="<?php echo $val->profession ?>"><?php echo $val->profession ?></option>
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
                   <option <?php if($edit_member_form->languages_known == $val->id) echo "selected"; ?> value="<?php echo $val->id ?>"><?php echo $val->lang_1 .'-' .$val->lang_2 ?></option>
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
                   <option <?php if($edit_member_form->blood_group == $val) echo "selected"; ?> value="<?php echo $val ?>"><?php echo $val ?></option>
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
                   <input type="text" <?php if(union_is_required_fields($required_fields,'present_address')) echo 'required' ?> autocomplete="off" value='<?php echo $edit_member_form->present_area ?>' name="present_area" class="form-control" placeholder="Area Name">
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
                   <input type="text" <?php if(union_is_required_fields($required_fields,'present_address')) echo 'required' ?> autocomplete="off" value='<?php echo $edit_member_form->present_pincode ?>' name="present_pincode" class="form-control" placeholder="Pincode">
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
                   <input type="text" <?php if(union_is_required_fields($required_fields,'permanent_address')) echo 'required' ?>  autocomplete="off" value='<?php echo $edit_member_form->permanent_area ?>' name="permanent_area" class="form-control" placeholder="Enter Area">
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
                   <input type="text" <?php if(union_is_required_fields($required_fields,'permanent_address')) echo 'required' ?> autocomplete="off" value='<?php echo $edit_member_form->permanent_pincode ?>' name="permanent_pincode" class="form-control" placeholder="Pincode">
                </div>
              </div>

          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'introducer_name')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Introducer Name <?php if(union_is_required_fields($required_fields,'introducer_name')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                 <input type="text" <?php if(union_is_required_fields($required_fields,'introducer_name')) echo 'required' ?> autocomplete="off" value='<?php echo $edit_member_form->introducer_name ?>' name="introducer_name" class="form-control" placeholder="Introducer Name">
              </div>
            </div>
          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'introducer_number')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Introducer Number <?php if(union_is_required_fields($required_fields,'introducer_number')) echo '<font color="red">*</font>' ?></label>
              <div class="col-md-8">
                 <input type="text" <?php if(union_is_required_fields($required_fields,'introducer_number')) echo 'required' ?> autocomplete="off" value='<?php echo $edit_member_form->introducer_number ?>' name="introducer_number" class="form-control" placeholder="Introducer Number">
              </div>
            </div>
          <?php endif ?>

          <?php if(union_is_enabled($enabled_fields, 'member_photo')) :  ?>
            
             <div class="form-group">
              <label class="control-label col-sm-3" for="no_of_days">Member Photo <?php if(union_is_required_fields($required_fields,'member_photo')) echo '<font color="red">*</font>' ?></label>
              <div class="col-sm-8">
                <img  onclick="$('#publicfileupload').click();"  class="img-responsive img-circle" id="profile_photo" src="<?php echo (!empty($edit_member_form->member_photo) == '') ? base_url().'assets/front/logo.png' : $this->filemanager->getFilePath($edit_member_form->member_photo); ?> " style="width:60px;height:60px">

                <input hidden="hidden" type="file" id="publicfileupload" class="file" data-preview-file-type="jpeg" name="profile_photo" value="<?php echo $edit_member_form->member_photo ?>" accept="image/*">
              </div>
            </div>

          <?php endif ?>
        </div>
        </div>
        <div class="panel-footer" style="margin-bottom: 6rem;">
          <center><button type="submit" class="btn btn-info">Update</button></center>
        </div>
      </div>
  </form>
</div>

<script type="text/javascript">

$('#publicfileupload').change(function() {
  var src = $(this).val();
  publicreadURL(this);
});
function publicreadURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      $('#union_member_profile_photo').attr('src', e.target.result);
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

$(document).ready(function(){
  var countryId = '<?php echo $user_regiser->country ?>';
  location_content_get_state(countryId);
});

$('#union_location_country').on('change',function(){
    location_content_get_state();
});
function location_content_get_state(countryId) {
    if (countryId =='' || countryId == 'undefined') {
      var countryId =$('#union_location_country').val();
    }
    $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
      var state = jQuery.parseJSON(data);
      var output='';
      output+='<option value="">Select State</option>';
      var len=state.length;
      for (var i=0,j=len; i < j; i++) {  
      var stateId = '<?php echo $user_regiser->state ?>';
        var selected = '';
      if (state[i].id == stateId) {
        selected = 'selected';
      }
        output+='<option '+selected+' value="'+state[i].id+'">'+state[i].state+'</option>'; 
      }
      $('#union_location_state').html(output);
      location_content_get_district();
   });
}


$('#union_location_state').on('change',function(){
    location_content_get_district();
});
function location_content_get_district() {
   var state_id =$('#union_location_state').val();
    $.post("<?php echo site_url('home/get_disctrict_by_stateId')?>",{state_id:state_id},function(data){
      var district = jQuery.parseJSON(data);
      var output='';
      output+='<option value="">Select District</option>';
      var len=district.length;
      for (var i=0,j=len; i < j; i++) {  
       var districtId = '<?php echo $user_regiser->district ?>';
        var selected = '';
        if (district[i].id == districtId) {
          selected = 'selected';
        }      
        output+='<option '+selected+' value="'+district[i].id+'">'+district[i].district+'</option>'; 
      }
      $('#union_location_district').html(output);
   });
}
</script>