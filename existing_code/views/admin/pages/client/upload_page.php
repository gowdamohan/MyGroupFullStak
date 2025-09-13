<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Upload</li>
</ul>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Upload</h3>
  </div>
  <div class="panel-body">

    <form data-validation="true" action="<?php echo site_url('Client_controller/insert_media_client_document') ?>" method="post" enctype="multipart/form-data">
    <div class="row">
     <?php 
        $months = array(
          '01'=>'Jan',
          '02'=>'Feb',
          '03'=>'March',
          '04'=>'April',
          '05'=>'May',
          '06'=>'June',
          '07'=>'July',
          '08'=>'Aug',
          '09'=>'Sept',
          '10'=>'Oct',
          '11'=>'Nov',
          '12'=>'Dec',
        );
        $dates = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];

        $currentDate = date('d');
        $currentMonth = date('m');
      ?>
      <div class="form-group">
        <div class="col-md-2">
          <select class="form-control" name="year_select" id="year_select">
            <option value="<?php echo date('Y') ?>"><?php echo date('Y') ?></option>
          </select>
        </div>
        <div class="col-md-10">
          <?php 
          foreach ($months as $key => $val) { ?>
            <a class="<?php if ($currentMonth == $key) echo 'currentDate' ?> " style="font-size: 24px;margin-right: 1rem;"><?php echo $val ?></a>
            <input type="hidden" name="current_month" id="current_month" value="<?php echo $currentMonth ?>" >
          <?php } ?>
        </div>
      </div>

      <div class="form-group">
        <?php 
        $currentDate = date('d');
        foreach ($dates as $key => $val) { ?>
          <a class="<?php if ($currentDate == $val) echo 'currentDate' ?> " style="font-size: 24px;margin-right: 1rem;" href=""><?php echo $val ?></a>
           <input type="hidden" name="current_date" id="current_date" value="<?php echo $currentDate ?>" >
        <?php } ?>
      </div>
    </div>

      <input type="hidden" name="file_path" id="fileuploadpath"> 
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

              <div class="text-center" style="width: 100%;">
                <div class="progress" style="height: 20px;">
                  <div class="progress-bar" id="single-file-percentage" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span id="percentage-completed" style="font-size: 20px;">0 %</span>
                <div id="save-status" style="font-size: 20px;word-break: break-all">
               </div>
             </div>

           <!--  <div id="loader-visible" style="position: relative;top: -80px;right: 0px; text-align: center;display: none; ">
                <img style="width: 80px;height: 80px" src="<?php echo base_url().'assets/loading-circle-gif.gif' ?>">
            </div> -->

          </div>
          <!-- <center>
            <button type="submit" id="save-file" class="btn btn-success">Submit</button>
          </center> -->
      </div>
    </form>

  </div>
</div>


<style type="text/css">
  .currentDate{
    color: green;
    font-weight: bold;
  }
</style>

<script type="text/javascript">

var completed_promises = 0;
var in_progress_promises = 0;
var total_promises = 0;
var current_percentage = 0;
// var selectedFiles = [];

function upload_document() {
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

  // Promise.all(promise).then((values) => {    
  //   $("#save-status").html('<span>Finishing Submission...</span>');
  //   saveFilePaths(values);
  // }).catch((err) => {
  //   $("#save-file").attr('disabled', false).html('Retry');
  //   $("#save-status").html('<span class="text-danger">Failed<br><span>('+JSON.stringify(err)+')</span></span><br><button class="btn btn-danger" data-dismiss="modal" href="#">Close</button>');
  // });
}

// function promisefunction(values) {
//   console.log(values);
//   saveFilePaths(values);
// }

function saveFilePaths(file_paths) {
  console.log(file_paths);
}

function saveFileToStorage(file) {
    // return new Promise(function(resolve, reject) {
        // try {
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
                          insert_uploaded_file(path);
                          // $("#save-status").html('<span>File Upload Successfully </span>');

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
        // } catch(err) {
        //     reject(err);
        // }
    // });
}
  function increaseLoading() {
    completed_promises++;
  }

function insert_uploaded_file(path) {
  var media_channel_id = '<?php echo $get_media_type->id ?>';
  var media_type = '<?php echo $mediaType ?>';
  var date = $('#year_select').val()+'-'+$('#current_month').val()+'-'+$('#current_date').val();
  $.ajax({
    url: '<?php echo site_url("Client_controller/insert_media_client_document"); ?>',
    type: 'post',
    data: {'file_path':path, 'media_channel_id':media_channel_id,'media_type':media_type,'date':date},
    success: function(response) {
      $("#save-status").html('<span>File Upload Successfully</span>');
      $("#save-file").attr('disabled', true).html('');
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