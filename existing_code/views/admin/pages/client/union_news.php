<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Union News</h3>         
    </div>

    <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/upload_client_union_news') ?>"  id="union-news" action="" class="form-horizontal" data-parsley-validate >
        <div class="panel-body table-responsive">
          <div class="col-md-6 col-md-offset-2">

            <div class="form-group">
              <label class="control-label col-sm-4">Title</label>
              <div class="col-md-8">
                <input type="text" name="news_letter_name" required class="form-control" id="news_letter_name" >
              </div>
            </div>

            <div class="form-group" id="textSummernote">
              <label class="control-label col-sm-4" for="summernote">Description</label>
              <div class="col-sm-8">
                <textarea class="summernote" required="" name="description" id="description"></textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-4">News type</label>
              <div class="col-md-8">
                <select class="form-control" required="" onchange="select_category_type(this.value)" id="notice_category" name="category">
                  <option value="3">Article</option>
                  <option value="1">Image</option>
                  <option value="2">Video</option>
                </select>
              </div>
            </div>

            <div class="row" id="upload-Img" style="padding: 0; display: none; ">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label col-sm-4">Main Image</label>
                  <div class="col-md-8">
                    <div class="form-group">
                      <img onclick="$('#fileupload').click();" class="img-responsive img-circle" id="union_main_img" style="width:100px;height:100px;display:inline-block" src="<?php echo base_url().'assets/back_end/img/icon/profile.png' ?>">
                      <input hidden="hidden" type="file" id="fileupload" class="file" data-preview-file-type="jpeg" name="union_main_img" accept="image/*">
                      <span id="fileuploadError" style="color:red;display: block;padding-top:5px;padding-bottom:5px;"></span>
                      <span id="percentage-completed_union_main_img" style="font-size: 20px; display: none; position: absolute;top: 34px;left: 24px;right: 0;">0 %</span>
                    </div>
                    <input type="hidden"  name="union_main_img" id="union_main_img_path">
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label col-sm-4">Images</label>
                  <div class="col-md-8">
                    <div class="form-group col-sm-4" >
                      <div class="form-group">
                        <img onclick="$('#fileupload1').click();" class="img-responsive img-circle" id="union_main_img1" style="width:100px;height:100px;display:inline-block" src="<?php echo base_url().'assets/back_end/img/icon/profile.png' ?>">
                        <input hidden="hidden" type="file" id="fileupload1" class="file" data-preview-file-type="jpeg" name="union_main_img1" accept="image/*">
                        <span id="fileuploadError" style="color:red;display: block;padding-top:5px;padding-bottom:5px;"></span>
                        <span id="percentage-completed_union_main_img1" style="font-size: 20px; display: none; position: absolute;top: 34px;left: 24px;right: 0;">0 %</span>
                      </div>
                      <input type="hidden"  name="union_main_img1" id="union_main_img_path1">
                    </div>

                    <div class="form-group col-sm-4">
                      <div class="form-group">
                        <img onclick="$('#fileupload2').click();" class="img-responsive img-circle" id="union_main_img2" style="width:100px;height:100px;display:inline-block" src="<?php echo base_url().'assets/back_end/img/icon/profile.png' ?>">
                        <input hidden="hidden" type="file" id="fileupload2" class="file" data-preview-file-type="jpeg" name="union_main_img2" accept="image/*">
                        <span id="fileuploadError" style="color:red;display: block;padding-top:5px;padding-bottom:5px;"></span>
                        <span id="percentage-completed_union_main_img2" style="font-size: 20px; display: none; position: absolute;top: 34px;left: 24px;right: 0;">0 %</span>
                      </div>
                      <input type="hidden" name="union_main_img2" id="union_main_img_path2">
                    </div>

                    <div class="form-group col-sm-4">
                      <img onclick="$('#fileupload3').click();" class="img-responsive img-circle" id="union_main_img3" style="width:100px;height:100px;display:inline-block" src="<?php echo base_url().'assets/back_end/img/icon/profile.png' ?>">
                        <input hidden="hidden" type="file" id="fileupload3" class="file" data-preview-file-type="jpeg" name="union_main_img3" accept="image/*">
                        <span id="fileuploadError" style="color:red;display: block;padding-top:5px;padding-bottom:5px;"></span>
                        <span id="percentage-completed_union_main_img3" style="font-size: 20px; display: none; position: absolute;top: 34px;left: 24px;right: 0;">0 %</span>
                    </div>
                    <input type="hidden" name="union_main_img3" id="union_main_img_path3">
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group" id="upload-video" style="display: none;">
              <label class="control-label col-sm-4">Upload Video</label>
              <div class="col-md-8">
                <div class="form-group col-sm-4" >
                    <div class="input--file" style="text-align: center;">
                      <span id="hidden_fileupaload">
                        <svg onclick='$("#videoupload").click()' xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-cloud-upload" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"/>
                          <path fill-rule="evenodd" d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3z"/>
                        </svg>
                      </span>
                      <input hidden="hidden" type="file" id="videoupload" class="file" name="union_upload">

                      <!-- <input style="display:none" type="file" id="videoupload" /> -->
                    </div>
                    <div id="videoDispaly" style="position: relative;top: -80px;right: 0px; text-align: center; display: none; ">
                      <img style="width: 80px;height: 80px; margin-bottom: -1rem;" src="<?php echo base_url().'assets/success.png' ?>">
                    </div>
                    <span id="percentage-completed_union_video" style="font-size: 20px; display: none; position: absolute;top: 34px;left: 24px;right: 0;">0 %</span>

                    <input type="hidden" name="video_path" id="videoPath">
                </div>
              </div>
            </div>
           
          </div>
        </div>
        <div class="panel-footer">
          <center>
            <input type="button" id="up-btn" onclick="submit_union_news()" class="btn btn-info" value="Submit">
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
            <th>#</th>
            <th>Title</th>
            <th>Description</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($client_union_news)) { ?>
            <?php $i=1; foreach ($client_union_news as $key => $aw) { ?>
              <tr>
                <td><?php echo $i++ ?></td>
                <td><?php echo $aw->news_letter_name ?></td>
                <td><?php echo $aw->description ?></td>
                <td><a onclick="return confirm('Are you going to  delete this document. Are you sure? ')" class="btn btn-danger" href="<?php echo site_url('client_controller/delete_union_news/'.$aw->id) ?>">Delete</a></td>
              </tr>
            <?php } ?>
          <?php }else{
            echo " <tr><th colspan='4' class='text-center'><h3>No News Letter Found</h3></th></tr>";
          } ?>
        </tbody>
      </table>
    </div>

</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/plugins/summernote/summernote.js"></script>

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

  function submit_union_news() {
    $('#up-btn').prop('disabled',true).val('Please wait...');
    $('#union-news').submit();
  }
   function select_category_type() {
    var category = $('#notice_category').val();
    if (category == 1) {
      $('#upload-Img').show();
      $('#upload-video').hide();
      $('#image-upload').attr('required','required');
      $('#video-upload').removeAttr('required');
    }else if(category == 2){
      $('#upload-Img').hide();
      $('#upload-video').show();
      $('#video-upload').attr('required','required');
      $('#image-upload').removeAttr('required');

    }else if(category == 3){
      $('#upload-Img').hide();
      $('#upload-video').hide();
      $('#video-upload').removeAttr('required');
      $('#image-upload').removeAttr('required');
    }else{

    }
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
        $('#union_main_img').attr('src', e.target.result);
        save_union_news_img();
     }

     reader.readAsDataURL(input.files[0]);
   }
}

$('#videoupload').change(function(){
    videoupload();
});


function videoupload() {
  $('#videoDispaly').hide();
  $('#percentage-completed_union_video').show();
  var file_data = $('#videoupload').prop('files')[0];
  $('#hidden_fileupaload').css('opacity','0.3');
  // $("#photo_" + type).prop('disabled', true).html('<i class="fa fa-spinner fa-spin" style="font-size:20px"></i>');
  $('#up-btn').prop('disabled',true);
  completed_promises = 0;
  current_percentage = 0;
  total_promises = 1;
  in_progress_promises = total_promises;
  savevideoFileToStorage(file_data);
}

function savevideoFileToStorage(file) {
    $.ajax({
      url: '<?php echo site_url("S3_controller/getSignedUrl"); ?>',
      type: 'post',
      data: {'filename':file.name, 'file_type':file.type, 'folder':'profile'},
      success: function(response) {
          console.log('Response: ',response)
          single_video_progress(0);
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
                          single_video_progress(e.loaded / e.total *100|0);
                      }
                  };
                  return xhr;
              },
              success: function(response) {
                // console.log(path);
                $('#up-btn').prop('disabled',false);
                $('#videoPath').val(path);
                $('#percentage-completed_union_video').hide();
                $('#hidden_fileupaload').css('opacity','1');
                $('#videoDispaly').show();
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

function single_video_progress(percentage) {
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
  $("#percentage-completed_union_video").html(`${current_percentage} %`);
  return false;
}
function save_union_news_img() {
    $('#percentage-completed_union_main_img').show();
    var file_data = $('#fileupload').prop('files')[0];
    $('#union_main_img').css('opacity','0.3');
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
                  $('#union_main_img_path').val(path);
                  $('#percentage-completed_union_main_img').hide();
                  $('#union_main_img').css('opacity','1');
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

// img1
$('#fileupload1').change(function(){
    var src = $(this).val();
    // var isFileOk = validatePhoto(this.files[0])
    if(src && validatePhoto1(this.files[0], 'fileupload1')){
        $("#fileuploadError").html("");
        readURL1(this);
    } else{
        this.value = null;
       }
  });

 function validatePhoto1(file,errorId){
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

function readURL1(input) {
   if (input.files && input.files[0]) {
     var reader = new FileReader();
     reader.onload = function (e) {
        $('#union_main_img1').attr('src', e.target.result);
        save_union_news_img1();
     }

     reader.readAsDataURL(input.files[0]);
   }
}


function save_union_news_img1() {
    $('#percentage-completed_union_main_img1').show();
    var file_data = $('#fileupload1').prop('files')[0];
    $('#union_main_img1').css('opacity','0.3');
    // $("#photo_" + type).prop('disabled', true).html('<i class="fa fa-spinner fa-spin" style="font-size:20px"></i>');
    $('#up-btn').prop('disabled',true);
    completed_promises = 0;
    current_percentage = 0;
    total_promises = 1;
    in_progress_promises = total_promises;
    saveFileToStorage1(file_data);
  }

  function saveFileToStorage1(file) {
    $.ajax({
        url: '<?php echo site_url("S3_controller/getSignedUrl"); ?>',
        type: 'post',
        data: {'filename':file.name, 'file_type':file.type, 'folder':'profile'},
        success: function(response) {
            // console.log('Response: ',response)
            single_file_progress1(0);
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
                            single_file_progress1(e.loaded / e.total *100|0);
                        }
                    };
                    return xhr;
                },
                success: function(response) {
                  // console.log(path);
                  $('#up-btn').prop('disabled',false);
                  $('#union_main_img_path1').val(path);
                  $('#percentage-completed_union_main_img1').hide();
                  $('#union_main_img1').css('opacity','1');
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

function single_file_progress1(percentage, usertype) {
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
  $("#percentage-completed_union_main_img1").html(`${current_percentage} %`);
  return false;
}

// img2
$('#fileupload2').change(function(){
    var src = $(this).val();
    // var isFileOk = validatePhoto(this.files[0])
    if(src && validatePhoto2(this.files[0], 'fileupload2')){
        $("#fileuploadError").html("");
        readURL2(this);
    } else{
        this.value = null;
       }
  });

 function validatePhoto2(file,errorId){
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

function readURL2(input) {
   if (input.files && input.files[0]) {
     var reader = new FileReader();
     reader.onload = function (e) {
        $('#union_main_img2').attr('src', e.target.result);
        save_union_news_img2();
     }

     reader.readAsDataURL(input.files[0]);
   }
}


function save_union_news_img2() {
    $('#percentage-completed_union_main_img2').show();
    var file_data = $('#fileupload2').prop('files')[0];
    $('#union_main_img2').css('opacity','0.3');
    // $("#photo_" + type).prop('disabled', true).html('<i class="fa fa-spinner fa-spin" style="font-size:20px"></i>');
    $('#up-btn').prop('disabled',true);
    completed_promises = 0;
    current_percentage = 0;
    total_promises = 1;
    in_progress_promises = total_promises;
    saveFileToStorage2(file_data);
  }

  function saveFileToStorage2(file) {
    $.ajax({
        url: '<?php echo site_url("S3_controller/getSignedUrl"); ?>',
        type: 'post',
        data: {'filename':file.name, 'file_type':file.type, 'folder':'profile'},
        success: function(response) {
            // console.log('Response: ',response)
            single_file_progress2(0);
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
                            single_file_progress2(e.loaded / e.total *100|0);
                        }
                    };
                    return xhr;
                },
                success: function(response) {
                  // console.log(path);
                  $('#up-btn').prop('disabled',false);
                  $('#union_main_img_path2').val(path);
                  $('#percentage-completed_union_main_img2').hide();
                  $('#union_main_img2').css('opacity','1');
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

function single_file_progress2(percentage, usertype) {
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
  $("#percentage-completed_union_main_img2").html(`${current_percentage} %`);
  return false;
}

// img3
$('#fileupload3').change(function(){
    var src = $(this).val();
    // var isFileOk = validatePhoto(this.files[0])
    if(src && validatePhoto3(this.files[0], 'fileupload2')){
        $("#fileuploadError").html("");
        readURL3(this);
    } else{
        this.value = null;
       }
  });

 function validatePhoto3(file,errorId){
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

function readURL3(input) {
   if (input.files && input.files[0]) {
     var reader = new FileReader();
     reader.onload = function (e) {
        $('#union_main_img3').attr('src', e.target.result);
        save_union_news_img3();
     }

     reader.readAsDataURL(input.files[0]);
   }
}


function save_union_news_img3() {
    $('#percentage-completed_union_main_img3').show();
    var file_data = $('#fileupload3').prop('files')[0];
    $('#union_main_img3').css('opacity','0.3');
    // $("#photo_" + type).prop('disabled', true).html('<i class="fa fa-spinner fa-spin" style="font-size:20px"></i>');
    $('#up-btn').prop('disabled',true);
    completed_promises = 0;
    current_percentage = 0;
    total_promises = 1;
    in_progress_promises = total_promises;
    saveFileToStorage3(file_data);
  }

  function saveFileToStorage3(file) {
    $.ajax({
        url: '<?php echo site_url("S3_controller/getSignedUrl"); ?>',
        type: 'post',
        data: {'filename':file.name, 'file_type':file.type, 'folder':'profile'},
        success: function(response) {
            // console.log('Response: ',response)
            single_file_progress3(0);
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
                            single_file_progress3(e.loaded / e.total *100|0);
                        }
                    };
                    return xhr;
                },
                success: function(response) {
                  // console.log(path);
                  $('#up-btn').prop('disabled',false);
                  $('#union_main_img_path3').val(path);
                  $('#percentage-completed_union_main_img3').hide();
                  $('#union_main_img3').css('opacity','1');
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

function single_file_progress3(percentage, usertype) {
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
  $("#percentage-completed_union_main_img3").html(`${current_percentage} %`);
  return false;
}
</script>
