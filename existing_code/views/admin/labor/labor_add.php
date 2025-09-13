<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Profile Details</h3>         
    </div>

    <form enctype="multipart/form-data" method="post" action="<?php echo site_url('labor_controller/insert_labor_details') ?>"  id="labor-profile" action="" class="form-horizontal" data-parsley-validate >
      <div class="panel-body table-responsive">
        <div class="col-md-6 col-md-offset-2">

          <div class="form-group">
            <label class="control-label col-sm-4">Category <font color="red">*</font></label>
            <div class="col-md-8">
              <select class="form-control" name="category">
                <option value="">Select</option>
                <?php foreach ($category as $key => $val) { ?>
                  <option value="<?php echo $val->name ?>"><?php echo $val->name ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-4">Contractor <font color="red">*</font></label>
            <div class="col-md-8">
              <select class="form-control" name="contractor">
                <option value="">Select</option>
                <?php foreach ($contractor as $key => $val) { ?>
                  <option value="<?php echo $val->name ?>"><?php echo $val->name ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-4">Sub Contractor <font color="red">*</font></label>
            <div class="col-md-8">
              <select class="form-control" name="category1">
                <option value="">Select</option>
                <?php foreach ($category1 as $key => $val) { ?>
                  <option value="<?php echo $val->name ?>"><?php echo $val->name ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-4">Team Leaders<font color="red">*</font></label>
            <div class="col-md-8">
              <select class="form-control" name="category2">
                <option value="">Select</option>
                <?php foreach ($category2 as $key => $val) { ?>
                  <option value="<?php echo $val->name ?>"><?php echo $val->name ?></option>
                <?php } ?>
              </select>
            </div>
          </div>



          <div class="form-group">
            <label class="control-label col-sm-4">Labor Name <font color="red">*</font></label>
            <div class="col-md-8">
              <input type="text" name="labor_name" required class="form-control" id="labor_name" >
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-4">Father/Husband Name</label>
            <div class="col-md-8">
              <input type="text" name="father_husband_name" class="form-control" id="father_husband_name" >
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-4">Mobile Number</label>
            <div class="col-md-8">
              <input type="text" name="mobile_number" class="form-control" id="mobile_number" >
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-4">Date of Birth  </label>
            <div class="col-md-8">
              <div class="col-md-4" style="padding-right: 2px;">
                    <select name="from_date" class="form-control" id="from_date">
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
                    <select name="from_month" class="form-control"  id="from_month" >
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

          <?php $blood_groups = ['A +ve','B +ve','O +ve','AB +ve','A -ve','B -ve','O -ve','AB -ve']; ?>
          <div class="form-group">
            <label class="control-label col-md-4">Blood Group </label>
            <div class="col-md-8">
              <select class="form-control" name="blood_group" id="blood_group">
                <option value="">Select Blood Group </option>
                 <?php foreach ($blood_groups as $key => $val) { ?>
                 <option value="<?php echo $val ?>"><?php echo $val ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-4">Aadhar Number</label>
            <div class="col-md-8">
              <input type="text" name="aadhar_number" class="form-control" id="aadhar_number" >
            </div>
          </div>

          <div class="row">
             <label class="control-label col-sm-4">Aadhar</label>
              <div class="form-group imgUp col-md-4">
                <label class="control-label">Front Photo</label>
                  <div class="imagePreview" id="front_photo" style="margin-bottom: 0.5rem;"></div>
                  <span id="percentage-completed_front_photo" style="font-size: 40px;position: absolute;top: 31%;left: 38%;right: 0px;color: white;display: none;">0 %</span>
                  
                  <label class="btn btn-warning">Update Photo<input type="file" id="aadharFrontPhoto" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"></label>
                  <input type="hidden" name="aadhar_front_photo" id="aadhar_front_photo">
                  <button type="button" onclick="save_front_photo()" id="up-btn" class="btn btn-info" style="width: 100%;margin-top: 1rem;">Save</button>

              </div>

            <div class="form-group imgUp col-md-4">
               <label class="control-label">Back Photo</label>
                <div class="imagePreview" id="back_photo" style="margin-bottom: 0.5rem;"></div>
                <span id="percentage-completed_back_photo" style="font-size: 40px;position: absolute;top: 31%;left: 38%;right: 0px;color: white;display: none;">0 %</span>
                
                <label class="btn btn-warning">Update Photo<input type="file" id="aadharBackPhoto" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"></label>
                <input type="hidden" name="aadhar_back_photo" id="aadhar_back_photo">
                <button type="button" onclick="save_back_photo()" id="up-btn1" class="btn btn-info" style="width: 100%;margin-top: 1rem;">Save</button>
            </div>
          </div>


          <div class="form-group">
            <label class="control-label col-sm-4">Photo</label>
            <div class="col-md-8 imgUp">
              <div class="imagePreview" id="labor_photo" style="margin-bottom: 0.5rem;"></div>
                <span id="percentage-completed_labor_photo" style="font-size: 40px;position: absolute;top: 31%;left: 38%;right: 0px;color: white;display: none;">0 %</span>
                <label class="btn btn-warning">Update Photo<input type="file" id="laborPhoto" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"></label>
                <input type="hidden" name="labor_photo" id="labor_photo_path">
                <button type="button" onclick="save_labor_photo()" id="up-btn2" class="btn btn-info" style="width: 100%;margin-top: 1rem;">Save</button>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-4">Address Line 1</label>
            <div class="col-md-8">
              <input type="text" name="address_line1" class="form-control" id="address_area" >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4">Address Line 2</label>
            <div class="col-md-8">
              <input type="text" name="address_line2" class="form-control" id="address_area" >
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-4">Country <font color="red" >*</font></label>
            <div class="col-md-8">
              <select class="form-control" required name="location_country" id="labor_location_country">
                <option value="">Select Country</option>
                <?php foreach ($country_flag as $key => $country) { ?>
                  <option value="<?php echo $country->id ?>"><?php echo $country->country ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-4">State <font color="red" >*</font></label>
            <div class="col-md-8">
              <select class="form-control" required name="location_state" id="labor_location_state">
                <option value="">Select State / Province</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-4">District<font color="red" >*</font></label>
            <div class="col-md-8">
              <select class="form-control" required name="location_district" id="labor_location_district">
                <option value="">Select District</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-4">Pincode <font color="red" >*</font></label>
            <div class="col-md-8">
              <input type="text" name="address_pincode" required class="form-control" id="address_pincode" >
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-4">Labor ID Number <font color="red" >*</font></label>
            <div class="col-md-8">
              <input type="text" name="labor_id_number" required class="form-control" id="labor_id_number" >
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-4">Payment </label>
            <div class="col-md-8">
              <input type="text" name="labor_amount"  class="form-control" id="labor_amount" >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-4">Billing Amount </label>
            <div class="col-md-8">
              <input type="text" name="billing_amount"  class="form-control" id="billing_amount" >
            </div>
          </div>
         
        </div>
      </div>
      <div class="panel-footer">
        <center>
          <input type="button" id="up-btn-labor" onclick="submit_labor_profile_details()" class="btn btn-info" value="Submit">
        </center>
      </div>
    </form>
     <div class="loader-background" style="display:none;">
        <div style="text-align:center;height: 100%;z-index: 1000;transform: translate(0%, -52%);">
            <i style="color:#000;font-size: 50px; margin-top: 100px;" class="fa fa-spinner fa-spin"></i>
            <br>
            <span id="percent-span" style="color:#000;font-size: 25px; margin-top: 100px;">0</span><span style="color:#000;font-size: 25px; margin-top: 100px;">&nbsp;of 100%</span>
            <br>
            <button id="cancel-btn" class="btn btn-lg btn-danger mt-2" style="width: 36%;">Cancel</button>
            <br>
        </div>
      </div>

</div>


<div class="loading_modal"></div>

<style type="text/css">
 .imagePreview {
   width: 100%;
   height: 180px;
   background-position: center center;
   background:url('');
   background-color:#fff;
   background-size: cover;
   background-repeat:no-repeat;
   display: inline-block;
   box-shadow:0px -3px 6px 2px rgba(0,0,0,0.2);
}
.btn-warning
{
  display:block;
  border-radius:0px;
  box-shadow:0px 4px 6px 2px rgba(0,0,0,0.2);
  margin-top:-5px;
}
.imgUp
{
  margin-bottom:15px;
}
.del
{
  position:absolute;
  top:0px;
  right:15px;
  width:30px;
  height:30px;
  text-align:center;
  line-height:30px;
  background-color:rgba(255,255,255,0.6);
  cursor:pointer;
}
.imgAdd
{
  width:30px;
  height:30px;
  border-radius:50%;
  background-color:#4bd7ef;
  color:#fff;
  box-shadow:0px 0px 2px 1px rgba(0,0,0,0.2);
  text-align:center;
  line-height:30px;
  margin-top:0px;
  cursor:pointer;
  font-size:15px;
}
</style>



<script type="text/javascript">
  
  function submit_labor_profile_details() {
    // $('#up-btn-labor').prop('disabled',true);
    $('#labor-profile').submit();
  }

   $(document).on("click", "i.del" , function() {
      $(this).parent().remove();
   });
   $(function() {
      $(document).on("change",".uploadFile", function(){
         var uploadFile = $(this);
         console.log(uploadFile);
         var files = !!this.files ? this.files : [];
         if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
 
         if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file
 
            reader.onloadend = function(){ // set image data as background of div
                //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
               uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url("+this.result+")");
            }
         }
      });
   });

function save_front_photo() {
  $('#percentage-completed_front_photo').show();
  var file_data = $('#aadharFrontPhoto').prop('files')[0];
  $('#front_photo').css('opacity','0.3');
    // $("#photo_" + type).prop('disabled', true).html('<i class="fa fa-spinner fa-spin" style="font-size:20px"></i>');
  $('#up-btn').prop('disabled',true);
  completed_promises = 0;
  current_percentage = 0;
  total_promises = 1;
  in_progress_promises = total_promises;
  saveFileToStorage(file_data,'completed_front_photo','aadhar_front_photo');
}

function save_back_photo() {
    $('#percentage-completed_back_photo').show();
   var file_data = $('#aadharBackPhoto').prop('files')[0];
   $('#back_photo').css('opacity','0.3');
    // $("#photo_" + type).prop('disabled', true).html('<i class="fa fa-spinner fa-spin" style="font-size:20px"></i>');
   $('#up-btn1').prop('disabled',true);
   completed_promises = 0;
   current_percentage = 0;
   total_promises = 1;
   in_progress_promises = total_promises;
   saveFileToStorage(file_data,'completed_back_photo','aadhar_back_photo');
}

function save_labor_photo() {
   $('#percentage-completed_labor_photo').show();
   var file_data = $('#laborPhoto').prop('files')[0];
   $('#labor_photo').css('opacity','0.3');
    // $("#photo_" + type).prop('disabled', true).html('<i class="fa fa-spinner fa-spin" style="font-size:20px"></i>');
   $('#up-btn2').prop('disabled',true);
   completed_promises = 0;
   current_percentage = 0;
   total_promises = 1;
   in_progress_promises = total_promises;
   saveFileToStorage(file_data,'completed_labor_photo','labor_photo_path');
}

function saveFileToStorage(file, processing, filePath) {
    $.ajax({
        url: '<?php echo site_url("S3_controller/getSignedUrl"); ?>',
        type: 'post',
        data: {'filename':file.name, 'file_type':file.type, 'folder':'god'},
        success: function(response) {
            // console.log('Response: ',response)
            single_file_progress(0);
            response = JSON.parse(response);
            var path = response.path;
            var signedUrl = response.signedUrl;
            $.ajax({
                url: signedUrl,
                type: 'PUT',
                headers: {
                    "Content-Type": file.type, 
                    "x-amz-acl":"public-read" 
                },
                processData: false,
                data: file,
                xhr: function () {
                    var xhr = $.ajaxSettings.xhr();
                    xhr.upload.onprogress = function (e) {
                        // For uploads
                        if (e.lengthComputable) {
                            single_file_progress(e.loaded / e.total *100|0, processing);
                        }
                    };
                    return xhr;
                },
                success: function(response) {
                  // console.log(path);
                  $('#up-btn').prop('disabled',false);
                  // console.log(path);
                  $('#'+filePath).val(path);
                  $('#'+filePath).css('opacity','1');
                  $('#percentage-'+processing).hide();
                },
                error: function(err) {
                    // console.log(err);
                    reject(err);
                }
            });
        },
        error: function (err) {
            reject(err);
        }
    });
}

function single_file_progress(percentage, processing) {
  if(percentage == 100) {
      in_progress_promises--;
      if(in_progress_promises == 0) {
          current_percentage = percentage;
      }
  } else {
      if(current_percentage<percentage) {
          current_percentage = percentage;
      }
  }
  $("#percentage-"+processing).html(`${current_percentage} %`);
  return false;
}

</script>


<style type="text/css">
.loaderclass {
  border: 8px solid #eee;
  border-top: 8px solid #7193be;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  position: fixed;
  z-index: 1;
  animation: spin 2s linear infinite;
  margin-top: 60%;
  margin-left: 40%;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>


<script type="text/javascript">

$('#labor_location_country').on('change',function(){
    mygod_content_get_state();
});
function mygod_content_get_state() {
   var countryId =$('#labor_location_country').val();
    $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
      var state = jQuery.parseJSON(data);
      var output='';
      output+='<option value="">Select State</option>';
      var len=state.length;
      for (var i=0,j=len; i < j; i++) {        
        output+='<option value="'+state[i].id+'">'+state[i].state+'</option>'; 
      }
      $('#labor_location_state').html(output);
   });
}


$('#labor_location_state').on('change',function(){
    mygod_content_get_district();
});
function mygod_content_get_district() {
   var state_id =$('#labor_location_state').val();
    $.post("<?php echo site_url('home/get_disctrict_by_stateId')?>",{state_id:state_id},function(data){
      var district = jQuery.parseJSON(data);
      console.log(district);
      var output='';
      output+='<option value="">Select District</option>';
      var len=district.length;
      for (var i=0,j=len; i < j; i++) {        
        output+='<option value="'+district[i].id+'">'+district[i].district+'</option>'; 
      }
      $('#labor_location_district').html(output);
   });
}
</script>
