<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Regional/Branch Ads</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Regional/Branch Ads</h3>
   </div>
    <div class="panel-body">
        <div class="col-md-6">
            <h3 style="border-bottom: 4px solid #be0808;">Regional Ads</h3>
            <table class="table table-bordered">
                <tr>
                    <td>
                        <button style="margin-top:1rem" data-toggle="modal" data-target="#office_ads" onclick="popupUploadRegionalAds('regional')" class="btn btn-info">Upload Ads</button>
                    </td>
                </tr>
            </table>
            <table class="table table-bordered">
                <tr>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            <?php
                foreach ($offer_ads as $key => $ads) {
                    if ($ads->ads_name =='regional'){ ?>
                        <tr>
                            <td><img style='width:100px; height:50px;' src="<?php echo $this->filemanager->getFilePath($ads->imagepath) ?>"></td>
                            <td><a class="btn btn-danger" href="<?php echo site_url('franchise/delete_franchise_offer_delete/'.$ads->id) ?>">Delete</a></td>
                        </tr>
                    <?php }
                }
            ?>
            </table>
        </div>
        <div class="col-md-6">
            <h3 style="border-bottom: 4px solid #be0808;">Branch Ads</h3>
            <table class="table table-bordered">
                <tr>
                    <td>
                        <button style="margin-top:1rem" data-toggle="modal" data-target="#office_ads" onclick="popupUploadRegionalAds('branch')" class="btn btn-info">Upload Ads</button>
                    </td>
                </tr>
            </table>
            <table class="table table-bordered">
                <tr>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
                <?php
                    foreach ($offer_ads as $key => $ads) {
                        if ($ads->ads_name =='branch'){ ?>
                            <tr>
                                <td><img style='width:100px; height:50px;' src="<?php echo $this->filemanager->getFilePath($ads->imagepath) ?>"></td>
                                <td><a class="btn btn-danger" href="<?php echo site_url('franchise/delete_franchise_offer_delete/'.$ads->id) ?>">Delete</a></td>
                            </tr>
                        <?php }
                    }
                ?>
            </table>
        </div>
   </div>
</div>

<script type="text/javascript">
   function popupUploadRegionalAds(adsName) {
      $("#save-status").html('');
      $('#fileuploadpath').val('');
      $('#ads_name').val(adsName);
   }
</script>


<div id="office_ads" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload Ads</h4>
            </div>
            <div class="modal-body">
                <form data-validation="true" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="file_path" id="fileuploadpath">
                    <input type="hidden" name="ads_name" id="ads_name">
                    <div class="item-inner">
                       <div class="item-content">
                          <div class="image-upload"> 
                            <label style="cursor: pointer;" for="file_upload"> <img src="" alt="" class="uploaded-image">
                              <div class="h-100">
                                <div class="dplay-tbl">
                                  <div class="dplay-tbl-cell"> <i  class="fa fa-cloud-upload"></i>
                                    <h5><b>Choose Your Document to Upload</b></h5>
                                  </div>
                                </div>
                              </div>
                             <input data-required="image" type="file" name="image_name" onchange="upload_document()" id="file_upload" class="image-input" data-traget-resolution="image_resolution" value="">
                            </label> 
                          </div>

                          <div class="text-center" id="progressBarDisplay" style="width: 100%; display: none;">
                             <div class="progress" style="height: 20px;">
                                <div class="progress-bar" id="single-file-percentage" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                             </div>
                             <span id="percentage-completed" style="font-size: 20px;">0 %</span>
                          </div>
                          <div id="save-status" style="font-size: 16px;word-break: break-all;text-align: center;color: #c4a9a9;"></div>
                       </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="save-file"  onclick="insert_uploaded_file()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

var completed_promises = 0;
var in_progress_promises = 0;
var total_promises = 0;
var current_percentage = 0;
// var selectedFiles = [];

function upload_document() {
   $('#progressBarDisplay').show();
  $("#save-file").attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>');
  var file_data = $('#file_upload').prop('files')[0];
  completed_promises = 0;
  current_percentage = 0;
  total_promises = 1;
  in_progress_promises = total_promises;
  $("#save-status").html('');
  // if(file_data.length == 0) {
  //     return false;
  // }
  saveFileToStorage(file_data);
}

function saveFileToStorage(file) {
    return new Promise(function(resolve, reject) {
        try {
            $.ajax({
                url: '<?php echo site_url("S3_controller/getSignedUrl"); ?>',
                type: 'post',
                data: {'filename':file.name, 'file_type':file.type, 'folder':'news'},
                success: function(response) {
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
                                    // console.log((e.loaded / e.total *100|0)+"%");
                                    single_file_progress(e.loaded / e.total *100|0);
                                }
                            };
                            return xhr;
                        },
                        success: function(response) {
                           $('#fileuploadpath').val(path);
                           $('#progressBarDisplay').hide();
                           $("#save-file").attr('disabled', false).html('Save');

                           $("#save-status").html('<span>File Upload Successfully </span>');

                            resolve({path:path, name:file.name, type:file.type});
                            increaseLoading();
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
        } catch(err) {
            reject(err);
        }
    });
}
  function increaseLoading() {
    completed_promises++;
  }

function insert_uploaded_file() {
   var imagepath = $('#fileuploadpath').val();
   var ads_name = $('#ads_name').val();
   
   if (imagepath == '') {
      alert('Upload Image then click on Save Button');
      return false;
   }
  $.ajax({
    url: '<?php echo site_url("franchise/upload_franchise_offer_data"); ?>',
    type: 'post',
    data: {'imagepath':imagepath,'ads_name':ads_name},
    success: function(response) {
      if (response) {
         $('#office_ads').hide();
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
  var progress = document.getElementById('single-file-percentage');
  progress.style.width = current_percentage+'%';
  $("#percentage-completed").html(`${current_percentage} %`);
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