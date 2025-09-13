<?php
  function admission_is_enabled($enabled_fields, $filter) {
    foreach ($enabled_fields as $f) {
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
  <form enctype="multipart/form-data" method="post" id="demo-form" action="<?php echo site_url('admin_controller/union_staff_registration_insert');?>" data-parsley-validate="" class="form-horizontal">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-titile">Union Staff Registration
            <a style="float: right;" href="<?php echo site_url('admin_controller/union_staff_index') ?>" class="btn btn-primary">View Application</a>
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
          <?php if(admission_is_enabled($enabled_fields, 'name')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Staff Name <font color="red">*</font></label>
              <div class="col-md-8">
                 <input type="text" required="" autocomplete="off" name="director_name" class="form-control" placeholder="Staff Name">
              </div>
            </div>
          <?php endif ?>

          <?php if(admission_is_enabled($enabled_fields, 'designation')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Designation <font color="red">*</font></label>
              <div class="col-md-8">
                 <input type="text" required="" autocomplete="off" name="designation" class="form-control" placeholder="Designation">
              </div>
            </div>
          <?php endif ?>

            <div class="form-group">
              <label class="control-label col-md-3">Mobile Number  <font color="red">*</font></label>
              <div class="col-md-8">
                 <input type="text" required="" onkeyup="check_mobile_no_availability_union()"  autocomplete="off" name="mobile_number" class="form-control" placeholder="Mobile Number">
                 <div id="exit_error" style="color:red"></div>
              </div>
            </div>

          <?php if(admission_is_enabled($enabled_fields, 'email_id')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Email-id </label>
              <div class="col-md-8">
                 <input type="text" autocomplete="off" name="email_id" class="form-control" placeholder="Email-Id">
              </div>
            </div>
          <?php endif ?>

          <?php if(admission_is_enabled($enabled_fields, 'description')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">About  </label>
              <div class="col-md-8">
                <textarea class="form-control"  autocomplete="off" name="description" rows="5"></textarea>
              </div>
            </div>
          <?php endif ?>

          <?php if(admission_is_enabled($enabled_fields, 'address')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Address  </label>
              <div class="col-md-8">
                <textarea class="form-control"  autocomplete="off" name="address" rows="5"></textarea>
              </div>
            </div>
          <?php endif ?>

          <?php if(admission_is_enabled($enabled_fields, 'name_of_media')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Name of the Media  </label>
              <div class="col-md-8">
               <input type="text" autocomplete="off" name="name_of_media" class="form-control" placeholder="Name of the Media">
              </div>
            </div>
          <?php endif ?>

          <?php if(admission_is_enabled($enabled_fields, 'designation_in_media')) :  ?>
            <div class="form-group">
              <label class="control-label col-md-3">Designation in Media  </label>
              <div class="col-md-8">
               <input type="text" autocomplete="off" name="designation_in_media" class="form-control" placeholder="Designation in Media">
              </div>
            </div>
          <?php endif ?>

          <?php if(admission_is_enabled($enabled_fields, 'photo')) :  ?>
            
            <div class="form-group">
              <label class="control-label col-sm-3" for="no_of_days">Staff Photo</label>
              <div class="col-sm-8">
                <img  onclick="$('#fileupload').click();" class="rounded-circle" id="profile_photo" style="width:60px;height:60px">
                <input hidden="hidden" type="file" id="fileupload" class="file" data-preview-file-type="jpeg" name="profile_photo" accept="image/*">
              </div>
            </div>            
          <?php endif ?>


        </div>
        </div>
        <div class="panel-footer">
          <center><button type="submit" id="submitButton" class="btn btn-info">Submit</button></center>
        </div>
      </div>
  </form>
</div>

<script type="text/javascript">
  function check_mobile_no_availability_union() {
      var mobile_number = $('#mobileNumber').val();
      $.ajax({
          url: '<?php echo site_url('client_controller/check_mobile_number_unique'); ?>',
          type: 'post',
          data: {'mobile_number':mobile_number},
          success: function(data) {
              console.log(data);
              if ($.trim(data) == 'exists') {
                  $('#exit_error').html("Mobile Number already Exists.");
                  $("#submitButton").prop('disabled',true);
              } else {
                  $('#exit_error').html("");
                  $("#submitButton").prop('disabled',false);
              }
          }
      });
  }
</script>
<script type="text/javascript">
  $('#fileupload').change(function() {
    var src = $(this).val();
    readURL(this);
  });
  function readURL(input) {
    console.log(input.files);
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#profile_photo').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
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

</script>