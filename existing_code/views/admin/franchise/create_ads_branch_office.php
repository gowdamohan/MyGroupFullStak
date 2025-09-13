<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Head Office Create Header Ads -1</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Head Office Create Header Ads -1</h3>
   </div>
   <div class="panel-body">
      <?php foreach ($myapps as $key => $val) {
          $mainSUb = [];
         if (array_key_exists($val->name, $uploaded_data)) {
           $mainSUb = $uploaded_data[$val->name];
         }         
       ?>
         <h3 style="border-bottom: 4px solid #be0808;" ><img  style="width: 20px;" src="<?php echo base_url().$val->icon ?>"><?php echo $val->name ?></h3>
         <table class="table table-bordered">
         <?php 
            if (!empty($val->sub_group)) {
              
               foreach ($val->sub_group as $key => $sub) { ?>
                  <tr>
                     <td width="10%"><?php echo $sub ?></td>
                     <td>
                        <?php 
                        $buttonName = 'Upload Ads';
                        $btnColor = 'btn-info';
                        $imgPath = '';
                        $adsUrl = '';
                        if (array_key_exists($sub, $mainSUb)) {
                            $buttonName = 'Change Ads';
                            $btnColor = 'btn-success';
                           echo "<img style='width:100px; height:50px;' src=".$this->filemanager->getFilePath($mainSUb[$sub]->image_path).">";
                           
                           if (!empty($mainSUb[$sub]->image_path)) {
                               $imgPath = $this->filemanager->getFilePath($mainSUb[$sub]->image_path);
                           }
                           if (!empty($mainSUb[$sub]->image_url)) {
                               $adsUrl = $mainSUb[$sub]->image_url;
                           }
                        }
                        ?>
                        <br>
                        <button style="margin-top:1rem" data-toggle="modal" data-target="#branch_office_ads" onclick="popupUploadAds('<?php echo $val->name ?>','<?php echo $sub ?>','<?php echo $imgPath ?>','<?php echo $adsUrl ?>')" class="btn <?php echo $btnColor ?>"><?php echo $buttonName ?></button>
                     </td>
                  </tr>
               <?php } 
            }
         ?>
          </table>    
      <?php } ?>
      

      
   </div>
</div>
<script type="text/javascript">
   function popupUploadAds(myaps, subAds, imgpath, adsUrl) {
      $('#branch_myappname').val(myaps);
      $('#branch_mysubappname').val(subAds);
      $("#branch_save-status").html('');
      $('#branch_fileuploadpath').val('');
      $('#branch_adsURl').val(adsUrl);
      $('#previewing').attr('src',imgpath);

    var franchHolderId  = '<?php echo $franch->franchise_holder_id ?>';
    $.ajax({
        url: '<?php echo site_url("franchise/get_uploaded_ads_branch_bydata_details"); ?>',
        type: 'post',
        data: {'myaps':myaps,'subAds':subAds,'franchHolderId':franchHolderId},
        success: function(res) {
          var resData = JSON.parse(res);
          if (resData !='') {
            $('#branch_fileuploadpath').val(resData.image_path);
            $('#branch_adsURl').val(resData.image_url);
          }
        }
    });

   }
</script>


<div id="branch_office_ads" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload Ads</h4>
      </div>
      <div class="modal-body">
         <form data-validation="true" method="post" enctype="multipart/form-data">

            <input type="hidden" name="file_path" id="branch_fileuploadpath">
            <input type="hidden" value="<?php echo $franch->franchise_holder_id ?>" name="branch_franchise_holder_id" id="branch_franchise_holder_id">
            <input type="hidden" name="branch_my_app_name" id="branch_myappname">
            <input type="hidden" name="branch_my_sub_app_name" id="branch_mysubappname">

            <div class="item-inner">

               <div class="form-group">  
                    <div class="col-md-8 ">
                        <div id="dvPreview">
                            <img id="previewing"  name="photograph" style="width: 260px;height: 160px;" src="" />
                            <span id="percentage_franchise_completed" style="font-size: 20px; display: none; position: absolute;top: 25px;left: 52px;right: 0;">0 %</span>
                        </div>
                    </div>
                </div>

                <div id="wrapper" class="form-group">
                    <div class="col-md-8">
                        <input class="form-control" id="fileupload" name="image_name" type="file"  accept="image/*"/>
                        <p style="color:red;" id="fileuploadError"></p>
                    </div>
                </div>


               <div class="form-group">
                  <input type="text" name="branch_ads_url" id="branch_adsURl" class="form-control" placeholder="Ads URL">
               </div>

            </div>
          </form>
      </div>
      <div class="modal-footer">
         <button type="button" id="branch_save-file"  onclick="insert_uploaded_file()" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">

$('#fileupload').change(function(){
    var src = $(this).val();

    if(src && validateStaffPhoto(this.files[0], 'fileupload')){

        completed_promises = 0;
        current_percentage = 0;
        total_promises = 1;
        in_progress_promises = total_promises;
        saveFileToStorage(this.files[0]);
        $('#previewing').css('opacity','0.3');
        $("#fileuploadError").html("");
        readURL(this);
    } else{
        this.value = null;
    }
});

function validateStaffPhoto(file,errorId){
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
            $('#previewing').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

 function saveFileToStorage(file) {
    $('#percentage_franchise_completed').show();
    $('#fileupload').attr('disabled','disabled');
    $("#btnSubmit").prop('disabled',true);
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
                    $('#branch_fileuploadpath').val(path);
                    // savePhoto(path, usertype, id, file);
                    $('#percentage_franchise_completed').hide();
                    $('#fileupload').removeAttr('disabled');
                    $('#previewing').css('opacity','1');
                    $("#save-file").prop('disabled',false);
                    // resolve({path:path, name:file.name, type:file.type});
                    // increaseLoading();
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
  $("#percentage_franchise_completed").html(`${current_percentage} %`);
  return false;
}

function insert_uploaded_file() {
   var imagepath = $('#branch_fileuploadpath').val();
   var franchiseHolderId = $('#branch_franchise_holder_id').val();
   var myappName = $('#branch_myappname').val();
   var mysubAppName = $('#branch_mysubappname').val();
   var adsURl = $('#branch_adsURl').val();
   if (imagepath == '') {
      alert('Upload Image then click on Save Button');
      return false;
   }
  $.ajax({
    url: '<?php echo site_url("franchise/upload_franchise_branch_data"); ?>',
    type: 'post',
    data: {'imagepath':imagepath,'franchiseHolderId':franchiseHolderId,'myappName':myappName,'mysubAppName':mysubAppName,'adsURl':adsURl},
    success: function(response) {
      if (response) {
         $('#branch_office_ads').hide();
         location.reload();
      }
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
  var progress = document.getElementById('branch_single-file-percentage');
  progress.style.width = current_percentage+'%';
  $("#branch_percentage-completed").html(`${current_percentage} %`);
  return false;
}

</script>
<style type="text/css">

#imgCenter{
  height: 400px;
  top: 30%;
  position: absolute;
}

.image-upload {
  text-align: center;
}

.image-upload i {
    font-size: 6em;
    color: #ccc
}

.image-upload input {
    cursor: pointer;
    opacity: 0;
    height: 100%;
    width: 100%;
    z-index: 10;
    top: 0;
    left: 0
}

.item-wrapper input {
    height: 43px;
    line-height: 43px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 20px
}
</style>