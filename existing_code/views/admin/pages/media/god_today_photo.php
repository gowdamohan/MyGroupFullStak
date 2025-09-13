<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Today Photo</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Today Photo</h3>
   </div>
   <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/insert_needy_client_services_details') ?>" id="needy_client_service" action="#" class="form-horizontal" data-parsley-validate >
      <div class="col-md-6 col-md-offset-2">

         <br>
         <div class="container">
            <div class="row">
              <div class="col-sm-2 imgUp">
                  <label style="font-size:18px;" >Date : <?php echo date('d-m-Y') ?></label>
                  <?php
                     $img = '';
                     $labelName = 'Upload';
                     if (!empty($today_god_photo)) { 
                        $labelName = 'Change';
                        $imgpath = $this->filemanager->getFilePath($today_god_photo->image); 
                        $img = '<img class="imagePreview" style="background-image:url('.$imgpath.')" >';
                     }
                   ?>
                  <div class="imagePreview" id="mygod_main_img" style="margin-bottom: 0.5rem;">
                     <?php echo $img ?>
                  </div>

                  <span id="percentage-completed_today_main_img" style="font-size: 40px;position: absolute;top: 31%;left: 38%;right: 0px;color: white;display: none;">0 %</span>
                  
                  <label class="btn btn-warning"><?php echo $labelName ?><input type="file" id="fileupload" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"></label>

                  <button type="button" onclick="save_today_god_photo()" id="up-btn" class="btn btn-info" style="width: 100%;margin-top: 1rem;">Save</button>
              </div>
            </div>
         </div>

      </div>
   </form>
   <div class="panel-body">
    <h3>Previous Photos</h3>
        <br>
     <div class="container">
        <div class="row">
            <?php foreach ($old_god_photo as $key => $val) { ?>
                <div class="col-sm-2 imgUp">
                    <label style="font-size:18px;" >Date : <?php echo date('d-m-Y',strtotime($val->image_date)) ?></label>
                    <?php
                        $img = '';
                        if (!empty($val->image)) { 
                            $imgpath = $this->filemanager->getFilePath($val->image); 
                            $img = '<img class="imagePreview" style="background-image:url('.$imgpath.')" >';
                        }
                    ?>
                    <div class="imagePreview" id="mygod_main_img" style="margin-bottom: 0.5rem;">
                        <?php echo $img ?>
                    </div>
                </div>

            <?php } ?>
         
        </div>
     </div>

   </div>
</div>

<style type="text/css">
 .imagePreview {
   width: 100%;
   height: 180px;
   background-position: center center;
   background:url(http://cliquecities.com/assets/no-image-e3699ae23f866f6cbdf8ba2443ee5c4e.jpg);
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
  
   $(document).on("click", "i.del" , function() {
      $(this).parent().remove();
   });
   $(function() {
      $(document).on("change",".uploadFile", function(){
         var uploadFile = $(this);
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

function save_today_god_photo() {
   $('#percentage-completed_today_main_img').show();
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
                            single_file_progress(e.loaded / e.total *100|0);
                        }
                    };
                    return xhr;
                },
                success: function(response) {
                  // console.log(path);
                  $('#up-btn').prop('disabled',false);
                  // console.log(path);
                  save_god_today_photo(path);

                  // $('#mygod_main_img_path').val(path);

                  $('#percentage-completed_today_main_img').hide();
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

function single_file_progress(percentage) {
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
  $("#percentage-completed_today_main_img").html(`${current_percentage} %`);
  return false;
}

function save_god_today_photo(path) {
   $.ajax({
      url: '<?php echo site_url("Client_controller/upload_god_today_photo"); ?>',
      type: 'post',
      data: {'path':path},
      success: function(response) {
         location.reload();
      }
   });
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