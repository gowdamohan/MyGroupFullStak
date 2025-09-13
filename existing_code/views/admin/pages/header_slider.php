<ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
    <li><a href="<?php echo site_url('admin_controller/advertisements');?>">Advertisements</a></li>
    <li>Header Slider</li>
</ul>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Header Slider</h3>
  </div>
  <div class="panel-body">
    <?php if (!empty($edit_header_slider)) { ?>
      <form enctype="multipart/form-data" method="post" id="header-slider" action="<?php echo site_url('admin_controller/update_header_slider/'.$edit_header_slider->id) ?>" class="form-horizontal" data-parsley-validate >

        <div class="col-md-5">
         <div class="form-group">
            <label class="control-label col-sm-4" for="no_of_days">Edit Header Slider</label>
            <div class="col-sm-8"> 
              <input type="file" class="form-control" id="image-upload"  name="header_slider">
              <input type="hidden"  value="<?php echo $edit_header_slider->image ?>" class="form-control" id="header_slider1"  name="header_slider1">
            </div>
          </div>
        </div>
        <div class="col-md-1">
          <input type="submit" value="Upload" id="up-btn" class="btn btn-primary">
        </div>
      </form>
    <?php }else{ ?>
      <form enctype="multipart/form-data" method="post" id="header-slider" action="<?php echo site_url('admin_controller/submit_header_slider') ?>" class="form-horizontal" data-parsley-validate >
        <!-- <div id="fullscreen-loading">Loading&#8230;</div> -->
        <div class="col-md-5">
         <div class="form-group">
         
            <label class="control-label col-sm-4" for="no_of_days">Header Slider</label>
            <div class="col-sm-8"> 
              <input type="file" required="" class="form-control" id="image-upload"  name="header_slider">
            </div>
          </div>
          <span id="fileuploadError" style="color:red;"></span>
        </div>
        <div class="col-md-1">
          <input type="submit" value="Upload"  class="btn btn-primary">
        </div>
        <div id="upload-Preview" style="display: none;">
                  
        </div>
      </form>
    <?php } ?>
    
  </div>

  <div class="panel-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Header Slider</th>
          <th width="10%">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach ($sliders as $key => $val) { ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><img width="160px;" class="img-responsive" src="<?php echo base_url().$val->image ?>"></td>
            <td>
              <a href="<?= site_url('admin_controller/edit_header_slider/'.$val->id) ?>" class="btn btn-warning btn-xs mrg" data-placement="top" data-toggle="tooltip"  data-original-title="Edit">
              <i class='fa fa-edit'></i>
              </a>   
              <a onclick="return confirm('Are you sure do you want delete ?')"  href="<?= site_url('admin_controller/delete_header_slider/'.$val->id) ?>" class='btn btn-danger btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><i class='fa fa-trash-o'></i></a>
            </td>
          </tr>    
        <?php } ?>
       
      </tbody>
    </table>
  </div>
</div>

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
     
                            if (settings.onImageResized != null && typeof (settings.onImageResized) == "function") {  
                                settings.onImageResized(imageData);  
                            }  
                        }  else {
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
        maxWidth: 500,  
        onImageResized: function (imageData) { 
             $("#upload-Preview").append($("<img/>", { src: imageData }));  
             $("#up-btn").html('Upload').prop('disabled', false);
        }  
    });  
 }); 


$("#up-btn").click(function() {
    $('#up-btn').val('Please wait ...').attr('disabled', 'disabled');
      var file_data = $('#image-upload').prop('files');
      if (file_data.length === 0) { 
          alert('Please choose an image to upload');
          $('#up-btn').val('Upload').prop('disabled', false);
          return false; 
      }
      $("#fullscreen-loading").show();
      var images = $('#upload-Preview').find('img').map(function() { return this.src; }).get();
      console.log(images);
      // var desc = $('#img_desc').val();
      // var tags = $('#image_tags').val();
      // var gal_id = $('#gal_id').val();
      var form_data = new FormData();
      form_data.append('file[]', file_data);
      // form_data.append('gal_id', gal_id);
      // form_data.append('desc', desc);
      // form_data.append('tags', tags);
      var image = images[0];
      var base64ImageContent = image.replace(/^data:image\/(png|jpg|jpeg);base64,/, "");
      var blob = base64ToBlob(base64ImageContent, 'image/png');
      form_data.append('file_name', blob);
      // var image = images;
      // var base64ImageContent = image.replace(/^data:image\/(png|jpg|jpeg);base64,/, "");
      // var blob = base64ToBlob(base64ImageContent, 'image/png');
      // form_data.append('file_name[]', blob);
      // console.log(form_data);
      // return false;
      $.ajax({
          url: '<?php echo site_url('admin_controller/submit_header_slider') ?>',
          type: 'post',
          data: form_data,
          cache: false,
          contentType: false,
          processData: false,
          success: function(data) {
            console.log(data);

           // $("#fullscreen-loading").hide();
           //  location.reload();
          }
      });
});

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