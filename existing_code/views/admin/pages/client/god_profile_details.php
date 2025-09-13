<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Profile Details</h3>         
    </div>

    <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/upload_client_mygod_details') ?>"  id="mygod-profile" action="" class="form-horizontal" data-parsley-validate >
        <div class="panel-body table-responsive">
          <div class="col-md-6 col-md-offset-2">

            <div class="form-group">
              <label class="control-label col-sm-4">Name <font color="red">*</font></label>
              <div class="col-md-8">
                <input type="text" name="title" required class="form-control" id="title" >
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-4">Name in Regional Language</label>
              <div class="col-md-8">
                <input type="text" name="name_regional_lang" class="form-control" id="name_regional_lang" >
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-4">Photo/Logo</label>
              <div class="col-md-8">
                <div class="form-group">
                  <img onclick="$('#fileupload').click();" class="img-responsive img-circle" id="mygod_main_img" style="width:100px;height:100px;display:inline-block" src="<?php echo base_url().'assets/back_end/img/icon/profile.png' ?>">
                  <input hidden="hidden" type="file" id="fileupload" class="file" data-preview-file-type="jpeg" name="union_main_img" accept="image/*">
                  <span id="fileuploadError" style="color:red;display: block;padding-top:5px;padding-bottom:5px;"></span>
                  <span id="percentage-completed_union_main_img" style="font-size: 20px; display: none; position: absolute;top: 34px;left: 24px;right: 0;">0 %</span>
                </div>
                <input type="hidden"  name="union_main_img" id="mygod_main_img_path">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-4">Name of the God <font color="red" >*</font></label>
              <div class="col-md-8">
                <input type="text" name="name_of_god" required class="form-control" id="name_of_god" >
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
                <select class="form-control" required name="mygod_location_country" id="mygod_location_country">
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
                <select class="form-control" required name="mygod_location_state" id="mygod_location_state">
                  <option value="">Select State / Province</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-4">District<font color="red" >*</font></label>
              <div class="col-md-8">
                <select class="form-control" required name="mygod_location_district" id="mygod_location_district">
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
           
          </div>
        </div>
        <div class="panel-footer">
          <center>
            <input type="button" id="up-btn" onclick="submit_mygod_profile_details()" class="btn btn-info" value="Submit">
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
    <div class="panel-body table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Name</th>
            <th>Regional name</th>
            <th>Image</th>
            <th>Name of the God</th>
            <th>Address</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($client_mygod)) { ?>
              <tr>
                <td><?php echo $client_mygod->name ?></td>
                <td><?php echo $client_mygod->regional_lang ?></td>
                <td><img width="10%" class="img-rounded" src="<?php echo $this->filemanager->getFilePath($client_mygod->image)  ?>"> </td>
                <td><?php echo $client_mygod->name_of_the_god ?></td>
                <td><?php echo $client_mygod->address ?></td>
              </tr>
          <?php }else{
            echo " <tr><th colspan='4' class='text-center'><h3>No Found</h3></th></tr>";
          } ?>
        </tbody>
      </table>
    </div>

</div>


<div class="loading_modal"></div>
<style type="text/css">
  .loading_modal {
      display:    none;
      position:   fixed;
      z-index:    1000;
      top:        0;
      left:       0;
      height:     100%;
      width:      100%;
      background: rgba( 255, 255, 255, .8 ) 
                  url('../assets/loading_icon.gif') 
                  50% 50% 
                  no-repeat;
  }

  /* When the body has the loading class, we turn
     the scrollbar off with overflow:hidden */
  body.loading {
      overflow: hidden;   
  }

  /* Anytime the body has the loading class, our
     modal element will be visible */
  body.loading .loading_modal {
      display: block;
  }

  .input--file {
    position: relative;
    color: #7f7f7f;
  }

  .input--file input[type="file"] {
    position: absolute;
    top: 0;
    left: 0;
    opacity: 0;
  }

  input[type=file] {
    display: contents;
  }
</style>

<script type="text/javascript">

  function submit_mygod_profile_details() {
    $('#up-btn').prop('disabled',true).val('Please wait...');
    $('#mygod-profile').submit();
  }

  $('#fileupload').change(function(){
    var src = $(this).val();
    // var isFileOk = validatePhoto(this.files[0])
    if(src && validatePhoto(this.files[0], 'fileupload')){
        $("#fileuploadError").html("");
        readURL(this);
    } else{
        this.value = null;
       }
  });

 function validatePhoto(file,errorId){
  if (file.size > 10000000 || file.fileSize > 10000000)
  {
     $("#"+errorId+"Error").html("Allowed file size exceeded. (Max. 10 MB)")
     return false;
  }
  if(file.type != 'image/jpeg' && file.type != 'image/jpg' && file.type != 'image/png') {
      $("#"+errorId+"Error").html("Allowed file types are jpeg, jpg and png");
      return false;
  }
  return true;
}

function readURL(input) {
   if (input.files && input.files[0]) {
     var reader = new FileReader();
     reader.onload = function (e) {
        $('#mygod_main_img').attr('src', e.target.result);
        save_god_profile_img();
     }
     reader.readAsDataURL(input.files[0]);
   }
}


function save_god_profile_img() {
    $('#percentage-completed_union_main_img').show();
    var file_data = $('#fileupload').prop('files')[0];
    $('#mygod_main_img').css('opacity','0.3');
    // $("#photo_" + type).prop('disabled', true).html('<i class="fa fa-spinner fa-spin" style="font-size:20px"></i>');
    $('#up-btn').prop('disabled',true);
    completed_promises = 0;
    current_percentage = 0;
    total_promises = 1;
    in_progress_promises = total_promises;
    saveFileToStorage(file_data);
  }

  function saveFileToStorage(file) {
    $.ajax({
        url: '<?php echo site_url("S3_controller/getSignedUrl"); ?>',
        type: 'post',
        data: {'filename':file.name, 'file_type':file.type, 'folder':'profile'},
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
                            single_file_progress(e.loaded / e.total *100|0);
                        }
                    };
                    return xhr;
                },
                success: function(response) {
                  // console.log(path);
                  $('#up-btn').prop('disabled',false);
                  $('#mygod_main_img_path').val(path);
                  $('#percentage-completed_union_main_img').hide();
                  $('#mygod_main_img').css('opacity','1');
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

function single_file_progress(percentage, usertype) {
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
  // var progress = document.getElementById('single-file-percentage');
  // progress.style.width = current_percentage+'%';
  $("#percentage-completed_union_main_img").html(`${current_percentage} %`);
  return false;
}

$('#mygod_location_country').on('change',function(){
    mygod_content_get_state();
});
function mygod_content_get_state() {
   var countryId =$('#mygod_location_country').val();
    $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
      var state = jQuery.parseJSON(data);
      var output='';
      output+='<option value="">Select State</option>';
      var len=state.length;
      for (var i=0,j=len; i < j; i++) {        
        output+='<option value="'+state[i].id+'">'+state[i].state+'</option>'; 
      }
      $('#mygod_location_state').html(output);
   });
}


$('#mygod_location_state').on('change',function(){
    mygod_content_get_district();
});
function mygod_content_get_district() {
   var state_id =$('#mygod_location_state').val();
    $.post("<?php echo site_url('home/get_disctrict_by_stateId')?>",{state_id:state_id},function(data){
      var district = jQuery.parseJSON(data);
      console.log(district);
      var output='';
      output+='<option value="">Select District</option>';
      var len=district.length;
      for (var i=0,j=len; i < j; i++) {        
        output+='<option value="'+district[i].id+'">'+district[i].district+'</option>'; 
      }
      $('#mygod_location_district').html(output);
   });
}
</script>
