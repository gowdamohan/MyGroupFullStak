<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Create Staff Details</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
        <h3 class="panel-title">
            <?php
                $url = site_url('franchise/create_branch_office_login'); 
                if ($loginTransver =='regional') {
                    $url = site_url('franchise/create_regional_office_login'); 
                }else if($loginTransver =='headoffice'){
                    $url = site_url('franchise/create_head_office_login'); 
                }
            ?> 
            <a class="btn btn-success btn-sm" href="<?php echo $url ?>">Back </a> Create Staff
        </h3>
   </div>
   <?php if (!empty($staff_profile)) { ?>

      <form enctype="multipart/form-data" method="post" action="<?php echo site_url('franchise/update_franchise_staff_data/'.$user_id.'/'.$staff_profile->id.'/'.$loginTransver) ?>" id="franchise-staff"  class="form-horizontal" data-parsley-validate >
         <div class="col-md-6" style="border-right:dotted;">
            <center>
               <h3 style="border-bottom: 4px solid red;width: 50%;margin-top: 1rem;" >Profile Section</h3>
            </center>
            <div class="panel-body">
               <div class="form-group">
                  <label class="control-label col-sm-4" for="franchise_staff_name">Name</label>
                  <div class="col-sm-8">
                     <input type="text" name="first_name" value="<?php echo $staff_profile->first_name ?>" class="form-control" >
                  </div>
               </div>

               <div class="form-group">
                  <label class="control-label col-sm-4" for="regional_lang_name">Phone Number</label>
                  <div class="col-sm-8">
                     <input type="text" name="phone" value="<?php echo $staff_profile->phone ?>" class="form-control" >
                  </div>
               </div>
               <div class="form-group">
                  <label class="control-label col-sm-4" for="email">Email-Id</label>
                  <div class="col-sm-8">
                     <input type="text" name="email" value="<?php echo $staff_profile->email ?>" class="form-control" >
                  </div>
               </div>

               <div class="form-group">
                  <label class="control-label col-sm-4" for="regional_lang_name">Address</label>
                  <div class="col-sm-8">
                     <textarea class="summernote" name="address" id="summernote"><?php echo $staff_profile->address ?></textarea>
                  </div>
               </div>
            </div>
            <center>
               <button type="submit" class="btn btn-primary">Submit</button>
            </center>
         </div>
         </form>
         <div class="col-md-6">
            <center>
               <h3 style="border-bottom: 4px solid red;width: 50%;margin-top: 1rem;">Supported Documents</h3>
            </center>
            <div class="panel-body">
               <button style="margin-bottom:2rem" data-toggle="modal" data-target="#staff_documents" onclick="popupUploadDoucments()" class="btn btn-info">Upload </button>
               <table class="table table-bordered">
                   <tr>
                       <th>#</th>
                       <th>Document Name</th>
                       <th>Image</th>
                       <th>Action</th>
                   </tr>
               <?php
               if (!empty($franchise_staff_docs)) {
                  $i=1;
                  foreach ($franchise_staff_docs as $key => $val) { ?>
                           <tr>
                               <td><?php echo $i++; ?></td>
                               <td><?php echo $val->document_name; ?></td>
                               <td>
                                <img style='width:100px; height:50px;' src="<?php echo $this->filemanager->getFilePath($val->imagepath) ?>">
                                </td>
                               <td><a onclick="return confirm('Are you sure do you want delete ?')"  class="btn btn-danger" href="<?php echo site_url('franchise/delete_franchise_staff_document_delete/'.$user_id.'/'.$staff_profile->id.'/'.$loginTransver) ?>">Delete</a></td>
                           </tr>
                  <?php  }
               }
               
               ?>
               </table>
            </div>
         </div>
     
   <?php }else{ ?>
      <form enctype="multipart/form-data" method="post" action="<?php echo site_url('franchise/upload_franchise_staff_data/'.$user_id.'/'.$loginTransver) ?>" id="franchise-staff"  class="form-horizontal" data-parsley-validate > 
         <div class="col-md-6" style="border-right:dotted;">
            <center>
               <h3 style="border-bottom: 4px solid red;width: 50%;margin-top: 1rem;" >Profile Section</h3>
            </center>
            <div class="panel-body">
               <div class="form-group">
                  <label class="control-label col-sm-4" for="franchise_staff_name">Name</label>
                  <div class="col-sm-8">
                     <input type="text" name="first_name" class="form-control" >
                  </div>
               </div>

               <div class="form-group">
                  <label class="control-label col-sm-4" for="regional_lang_name">Phone Number</label>
                  <div class="col-sm-8">
                     <input type="text" name="phone" class="form-control" >
                  </div>
               </div>
               <div class="form-group">
                  <label class="control-label col-sm-4" for="email">Email-Id</label>
                  <div class="col-sm-8">
                     <input type="text" name="email" class="form-control" >
                  </div>
               </div>

               <div class="form-group">
                  <label class="control-label col-sm-4" for="regional_lang_name">Address</label>
                  <div class="col-sm-8">
                     <textarea class="summernote" name="address" id="summernote"></textarea>
                  </div>
               </div>
            </div>
            <center>
               <button type="submit" class="btn btn-primary">Submit</button>
            </center>
         </div>
         </form>
         <div class="col-md-6">
            <center>
               <h3 style="border-bottom: 4px solid red;width: 50%;margin-top: 1rem;">Supported Documents</h3>
            </center>
            <div class="panel-body">
               <h3>Once update the profile section then enabled upload document buttons</h3>
                <button disabled style="margin-bottom:2rem" data-toggle="modal" data-target="#staff_documents" onclick="popupUploadDoucments()" class="btn btn-info">Upload </button>
               <table class="table table-bordered">
                   <tr>
                       <th>#</th>
                       <th>Document Name</th>
                       <th>Image</th>
                       <th>Action</th>
                   </tr>
               <?php
               if (!empty($franchise_staff_docs)) {
                  $i=1;
                  foreach ($franchise_staff_docs as $key => $val) { ?>
                           <tr>
                               <td><?php echo $i++; ?></td>
                               <td><?php echo $val->document_name; ?></td>
                               <td><img style='width:100px; height:50px;' src="<?php echo $this->filemanager->getFilePath($val->imagepath) ?>"></td>
                               <td><a onclick="return confirm('Are you sure do you want delete ?')"  class="btn btn-danger" href="<?php echo site_url('franchise/delete_franchise_staff_document_delete/'.$user_id.'/'.$staff_profile->id.'/'.$loginTransver) ?>">Delete</a></td>
                           </tr>
                  <?php  }
               }
               
               ?>
               </table>
            </div>
         </div>
      
   <?php } ?>
   

</div>

<script type="text/javascript">
   function popupUploadDoucments() {
      $("#save-status").html('');
      $('#fileuploadpath').val('');
   }
</script>

<div id="staff_documents" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload Documents</h4>
            </div>
            <div class="modal-body">
                <form data-validation="true" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="file_path" id="fileuploadpath">
                    <input type="hidden" name="franchise_staff_id" value="<?php echo $staff_profile->id ?>" id="franchise_staff_id">
                    <div class="form-group">
                      <input type="text" name="document_name" id="document_name" placeholder="Documents Name" required class="form-control" >
                    </div>
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
   var franchise_staff_id = $('#franchise_staff_id').val();
   var document_name = $('#document_name').val();
   
   if (imagepath == '') {
      alert('Upload Image then click on Save Button');
      return false;
   }
    if (document_name == '') {
      alert('Enter Document name then click on Save Button');
      return false;
   }
  $.ajax({
    url: '<?php echo site_url("franchise/upload_franchise_staff_document_data"); ?>',
    type: 'post',
    data: {'imagepath':imagepath,'franchise_staff_id':franchise_staff_id,'document_name':document_name},
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

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/plugins/summernote/summernote.js"></script>