<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">About Add</h3>         
    </div>

    <form enctype="multipart/form-data" method="post" id="students" action="" class="form-horizontal" data-parsley-validate >
        <div class="panel-body table-responsive">
           <div class="col-md-6 col-md-offset-2">
                <div class="form-group">
                  <label class="control-label col-sm-4">Group Name <font color="red"> *</font></label>
                  <div class="col-md-8">
                    <select class="form-control" required name="group_name" onchange="get_group_wise_app()" id="groupName" >
                      <option value="">Select Group</option>
                      <option value="My Apps">My Apps</option>
                      <option value="My Company">My Company</option>
                      <option value="My Onine Apps">My Onine Apps</option>
                      <option value="My Offline Apps">My Offline Apps</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-4">Apps Name <font color="red"> *</font></label>
                  <div class="col-md-8">
                    <select class="form-control" required name="apps_name" id="appsName" >
                      <option value="">Select Apps</option>
                      
                    </select>
                  </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4">Title</label>
                    <div class="col-md-8">
                        <input type="text" name="about_title" class="form-control" id="about_title" >
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4" for="no_of_days">Upload Image</label>
                    <div class="col-sm-8">
                       <input type="file" required="" class="form-control" id="image-upload">
                    </div>
                    <div id="upload-Preview" style="text-align: center;"></div>
                </div>

               <div class="form-group" id="textSummernote">
                  <label class="control-label col-sm-4" for="summernote">Content</label>
                  <div class="col-sm-8">
                    <textarea class="summernote" required="" id="summernote"></textarea>
                  </div>
                </div>

          </div>
        </div>
        <div class="panel-footer">
            <center>
               <input type="button" value="Upload" onclick="uploadAbout_apps_name()" id="up-btn" class="btn btn-primary">
               <a class="btn btn-danger" href="<?php echo site_url('admin_controller/application_details') ?>">Cancel / Back</a>
            </center>
        </div>
    </form>
</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/plugins/summernote/summernote.js"></script>


 <script type="text/javascript">

  function get_group_wise_app() {
    var groupName = $('#groupName').val();
    $.ajax({
      url: '<?php echo site_url('admin_controller/get_group_wise_app_byselection') ?>',
      type: 'post',
      data: {'groupName':groupName},
      success: function(data) {
        var res = $.parseJSON(data);
        var option = '<option value="">Select Apps</option>';
        for (var i = 0; i < res.length; i++) {
          option += '<option value="'+res[i].name+'">'+res[i].name+'</option>';
        }
        $('#appsName').html(option);
      }
    });
  }
  </script> 
  <script type="text/javascript">
  $.fn.ImageResize = function (options) { 
    
  var defaults = {  
    maxWidth: Number.MAX_VALUE,  
    maxHeigt: Number.MAX_VALUE,  
    onImageResized: null  
  }  
  var settings = $.extend({}, defaults, options);
  var selector = $(this);
  selector.each(function (index) {  
    var control = selector.get(index);  
    if ($(control).prop("tagName").toLowerCase() == "input" && $(control).attr("type").toLowerCase() == "file") {  
      $(control).attr("accept", "image/*");  
      $(control).attr("multiple", "true");  
      control.addEventListener('change', handleFileSelect, false);  
    }  
    else {  
      cosole.log("Invalid file input field");  
    }  
  });  

  function handleFileSelect(event) {  
    //Check File API support  
    if (window.File && window.FileList && window.FileReader) {  
      var count = 0;  
      var files = event.target.files; 
      for (var i = 0; i < files.length; i++) {  
      var file = files[i];  
      //Only pics  
      if (!file.type.match('image')) continue;  
      var picReader = new FileReader();  
      picReader.addEventListener("load", function (event) {  
        var picFile = event.target;  
        var imageData = picFile.result;  
        var img = new Image();  
        img.src = imageData;  
        img.onload = function () { 
          if (img.width > settings.maxWidth || img.height > settings.maxHeigt) {  
            var width = settings.maxWidth;  
            var height = settings.maxHeigt;  

            if (img.width > settings.maxWidth) {  
                width = settings.maxWidth;  
                var ration = settings.maxWidth / img.width;  
                height = Math.round(img.height * ration);  
            }  

            if (height > settings.maxHeigt) {  
                height = settings.maxHeigt;  
                var ration = settings.maxHeigt / img.height;  
                width = Math.round(img.width * ration);  
            }  

            var canvas = $("<canvas/>").get(0);  
            canvas.width = width;  
            canvas.height = height;  
            var context = canvas.getContext('2d');  
            context.drawImage(img, 0, 0, width, height);  
            imageData = canvas.toDataURL();  
            console.log(imageData);
            if (settings.onImageResized != null && typeof (settings.onImageResized) == "function") {  
              settings.onImageResized(imageData);  
            }  
          } else {
            var canvas = $("<canvas/>").get(0);  
            canvas.width = img.width;  
            canvas.height = img.height;  
            var context = canvas.getContext('2d');  
            context.drawImage(img, 0, 0, img.width, img.height);  
            imageData = canvas.toDataURL();  
            if (settings.onImageResized != null && typeof (settings.onImageResized) == "function") {  
                settings.onImageResized(imageData);  
            } 
          }
        }  
        img.onerror = function () {  
        }  
      });  
        //Read the image  
        picReader.readAsDataURL(file);  
      }  
    } else {  
      console.log("Your browser does not support File API");  
    }  
  }  
}  
    

$(document).ready(function () {  
    $("#image-upload").ImageResize({  
        maxWidth: 300,  
        onImageResized: function (imageData) { 
           $("#upload-Preview").html($("<img/>", { src: imageData }));  
        }
    }); 

 }); 

function uploadAbout_apps_name() {
    $('#up-btn').val('Please wait ...').attr('disabled', 'disabled');
    var file_data = $('#image-upload').prop('files');
    var title = '';
    var summernote = '';

    title = $('#about_title').val();
    group_name = $('#groupName').val();
    apps_name = $('#appsName').val();

    summernote = $('#summernote').code();
    if (title == '' || summernote == '') {
        alert('Please Select Required Documents');
        $('#up-btn').val('Upload').prop('disabled', false);
        return false; 
    }
    if (file_data.length === 0) { 
        alert('Please choose an image to upload');
        $('#up-btn').val('Upload').prop('disabled', false);
        return false; 
    }

      var images = $('#upload-Preview').find('img').map(function() { return this.src; }).get();
        
      var blobs = [];
      var form_data = new FormData();
      form_data.append('file[]', file_data);
      form_data.append('about_title', title);
      form_data.append('summernote', summernote);
      form_data.append('group_name', group_name);
      form_data.append('apps_name', apps_name);
      var total_images = 0;
      for(var i in images) {
          var image = images[i];
          var base64ImageContent = image.replace(/^data:image\/(png|jpg|jpeg);base64,/, "");
          var blob = base64ToBlob(base64ImageContent, 'image/png');
          form_data.append('file_name[]', blob);
          total_images++;
      }
      // console.log(form_data);
      // return false;
      $.ajax({
          url: '<?php echo site_url('admin_controller/upload_about_apps') ?>',
          type: 'post',
          data: form_data,
          cache: false,
          contentType: false,
          processData: false,
          success: function(data) {
            console.log(data);
            // var failed = parseInt(data);
            $(function(){
              new PNotify({
                title: 'Success',
                text: 'uploaded images successfully',
                type: 'success',
              });
            });
            location.reload();
          }
      });
}

function base64ToBlob(base64, mime) {
  mime = mime || '';
  // var sliceSize = 9000000;
  // var sliceSize = 500000;
  var sliceSize = 1024;
  var byteChars = window.atob(base64);
  // var byteChars = new Blob(base64, mime);
  var byteArrays = [];

  for (var offset = 0, len = byteChars.length; offset < len; offset += sliceSize) {
      var slice = byteChars.slice(offset, offset + sliceSize);

      var byteNumbers = new Array(slice.length);
      for (var i = 0; i < slice.length; i++) {
          byteNumbers[i] = slice.charCodeAt(i);
      }

      var byteArray = new Uint8Array(byteNumbers);

      byteArrays.push(byteArray);
  }

  return new Blob(byteArrays, {type: mime});
}
</script>

<style type="text/css">
  img{
    width: 24%;
  }
</style>