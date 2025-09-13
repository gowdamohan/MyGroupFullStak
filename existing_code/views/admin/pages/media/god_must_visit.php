<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Must Visit</h3>         
    </div>
  <?php 
    if (!empty($god_must_visit_edit)) { ?>
      <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/update_god_must_byid/'.$god_must_visit_edit->id) ?>"  id="god_event_form1"  class="form-horizontal" data-parsley-validate >
        <div class="panel-body table-responsive">
          <div class="col-md-6 col-md-offset-2">

            <div class="form-group">
              <label class="control-label col-sm-4">Name of the Place</label>
              <div class="col-md-8">
                <input type="text" name="name_of_the_place" value="<?php echo $god_must_visit_edit->name_of_the_place ?>" class="form-control" id="name_of_the_place" >
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-4" for="awards_path">Upload Image</label>
              <div class="col-sm-8 imgUp">
                 <?php
                   $img = '';
                   $labelName = 'Upload';
                   if (!empty($god_must_visit_edit->god_must_visit_file)) { 
                      $labelName = 'Change';
                      $imgpath = $this->filemanager->getFilePath($god_must_visit_edit->god_must_visit_file); 
                      $img = '<img class="imagePreview" style="background-image:url('.$imgpath.')" >';
                   }
                 ?>

                <div class="imagePreview" id="mygod_main_img" style="margin-bottom: 0.5rem;"> <?php echo $img ?></div>
                <span id="percentage-completed_today_main_img" style="font-size: 40px;position: absolute;top: 31%;left: 38%;right: 0px;color: white;display: none;">0 %</span>
                <label class="btn btn-warning">Change Event Photo<input type="file" name="god_must_visit_file" id="fileupload" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"></label>
              </div>
              <div id="upload-Preview" style="text-align: center;">

              </div>
            </div>

            <div class="form-group" id="textSummernote">
              <label class="control-label col-sm-4" for="summernote">Description</label>
              <div class="col-sm-8">
                <textarea class="summernote" required="" name="description" id="description"><?php echo $god_must_visit_edit->description ?></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-footer">
          <center>
            <input type="button" value="Update" onclick="update_god_event_by_id()" id="up-btn1" class="btn btn-primary">
          </center>
        </div>
      </form>
    <?php }else{ ?>
      <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/update_god_must_visit') ?>"  id="god_event_form"  class="form-horizontal" data-parsley-validate >
        <div class="panel-body table-responsive">
          <div class="col-md-6 col-md-offset-2">

            <div class="form-group">
              <label class="control-label col-sm-4">Name of the Place</label>
              <div class="col-md-8">
                <input type="text" name="name_of_the_place" class="form-control" id="name_of_the_place" >
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-4" for="awards_path">Upload Image</label>

              <div class="col-sm-8 imgUp">
                <div class="imagePreview" id="mygod_main_img" style="margin-bottom: 0.5rem;"></div>
                <span id="percentage-completed_today_main_img" style="font-size: 40px;position: absolute;top: 31%;left: 38%;right: 0px;color: white;display: none;">0 %</span>
                <label class="btn btn-warning">Upload Event Photo<input type="file" name="god_must_visit_file" id="fileupload" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"></label>
              </div>
              <div id="upload-Preview" style="text-align: center;"></div>
            </div>

            <div class="form-group" id="textSummernote">
              <label class="control-label col-sm-4" for="summernote">Description</label>
              <div class="col-sm-8">
                <textarea class="summernote" required="" name="description" id="description"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-footer">
          <center>
            <input type="button" value="Submit" onclick="update_god_event()" id="up-btn" class="btn btn-primary">
          </center>
        </div>
      </form>
    <?php }
  ?>
  
<script type="text/javascript">
  function update_god_event() {
    $('#up-btn').prop('disabled',true).val('Please wait..');
    $('#god_event_form').submit();
  }

  function update_god_event_by_id() {
    $('#up-btn1').prop('disabled',true).val('Please wait..');
    $('#god_event_form1').submit();
  }
</script>
    <div class="panel-body table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Place Name</th>
            <th>Description</th>
            <th>Attached</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($god_must_visit)) { ?>
            <?php $i=1; foreach ($god_must_visit as $key => $aw) { ?>
              <tr>
                <td><?php echo $i++ ?></td>
                <td><?php echo $aw->name_of_the_place ?></td>
                <td><?php echo $aw->description ?></td>
                <td>
                  <label for="logo" class="upload-field" id="file-label">
                    <div class="file-thumbnail">
                      
                      <img id="image-preview" src="<?php echo $this->filemanager->getFilePath($aw->god_must_visit_file) ?>" alt="">
                    </div>
                  </label>
                </td>
                <td><a class="btn btn-danger" href="<?php echo site_url('client_controller/edit_god_must_visit/'.$aw->id) ?>">Edit</a></td>
              </tr>
            <?php } ?>
          <?php }else{
            echo " <tr><th colspan='4' class='text-center'><h3>No About Found</h3></th></tr>";
          } ?>
        </tbody>
      </table>
    </div>

</div>

<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>

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

       // Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

    function fileValue(value) {
        var path = value.value;
        var extenstion = path.split('.').pop();
        var file = event.target.files[0];

        if (extenstion == "pdf") {
            $('#image-preview').hide();
            $('#pdfViewer').show();
            var fileReader = new FileReader();  
            fileReader.onload = function() {
                var pdfData = new Uint8Array(this.result);
                // Using DocumentInitParameters object to load binary data.
                var loadingTask = pdfjsLib.getDocument({data: pdfData});
                loadingTask.promise.then(function(pdf) {
                  // Fetch the first page
                  var pageNumber = 1;
                  pdf.getPage(pageNumber).then(function(page) {
                    var scale = 0.1;
                    var viewport = page.getViewport({scale: scale});
                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#pdfViewer")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                      canvasContext: context,
                      viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function () {
                    });
                  });
                }, function (reason) {
                  // PDF loading error
                  console.error(reason);
                });
            };
            fileReader.readAsArrayBuffer(file);

        }else{
            $('#image-preview').show();
            $('#pdfViewer').hide();
            if(extenstion == "jpg" || extenstion == "jpeg" || extenstion == "png"){
                document.getElementById('image-preview').src = window.URL.createObjectURL(value.files[0]);
                var filename = path.replace(/^.*[\\\/]/, '').split('.').slice(0, -1).join('.');
                document.getElementById("filename").innerHTML = filename;
            }else{
                alert("File not supported. Kindly Upload the Image of below given extension ")
            }
        }

       
    }

</script>

<style type="text/css">

  .upload-field .file-thumbnail {
    cursor: pointer;
    border: 1px dashed #BBD9EC;
    border-radius: 11px;
    text-align: center;
    padding: 20px;
  }

  .upload-field .file-thumbnail img {
    width: 50px;
  }

  .upload-field .file-thumbnail h3 {
    font-size: 12px;
    color: #000000;
    font-weight: 600;
    margin-bottom: 4px;
  }

  .upload-field .file-thumbnail p {
    font-size: 12px;
    color: #9ABCD1;
    margin-bottom: 0;
  }

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

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/plugins/summernote/summernote.js"></script>
