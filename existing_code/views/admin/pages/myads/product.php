<ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
    <li>Sub Category</li>
</ul>
<div class="panel panel-default">
 <div class="panel-heading">
    <h3 class="panel-title">Sub Category</h3>
 </div>
 <div class="panel-body">
  <form enctype="multipart/form-data" method="post" action="" class="form-horizontal" data-parsley-validate >
    <div class="col-md-8 col-md-offset-1">
     <div class="form-group">
        <label class="control-label col-sm-4" for="no_of_days">Select Category</label>
        <div class="col-sm-8">
          <p><?php echo $sub_category[0]->category ?></p>
        </div>
      </div>
      <input type="hidden" id="sub_id" value="<?php echo $sub_id ?>">
      <div class="form-group">
        <label class="control-label col-sm-4" for="no_of_days">Sub Category</label>
        <div class="col-sm-8">
          <p><?php echo $sub_category[0]->sub_category ?></p>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-4">Title</label>
        <div class="col-md-8">
          <input type="text" name="myads_title" class="form-control" id="myads_title" >
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
        <label class="control-label col-sm-4" for="summernote">Description</label>
        <div class="col-sm-8">
          <textarea class="summernote" required="" id="summernote"></textarea>
        </div>
      </div>
      <center>
       <input type="button" value="Upload" onclick="uploadSubCategory()" id="up-btn" class="btn btn-primary">
       <a class="btn btn-danger" href="<?php echo site_url('myads/admin_dashboard') ?>">Cancel / Back</a>
      </center>    
    </div>
  
  </form> 
 </div>

 <script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/plugins/summernote/summernote.js"></script>

 <div class="panel-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th width="2%" >#</th>
          <th>Category</th>
          <th>Sub Category</th>
          <th>Image</th>
          <th>Title</th>
          <th>Description</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($sub_category)) { ?>
          <?php $i=1; foreach ($sub_category as $key => $val) { ?>
            <tr>
              <td><?php echo $i++; ?></td>
              <td><?php echo $val->category ?></td>
              <td><?php echo $val->sub_category ?></td>
              <td><img width="20%" src="<?php echo $this->filemanager->getFilePath($val->image) ?>"></td>
              <td><?php echo $val->title ?></td>
              <td><?php echo $val->descriptions ?></td>
              <td>
                <a onclick="return confirm('Are you sure do you want delete this sub category ?')" href="<?= site_url('myads/delete_sub_category/'.$val->id) ?>" class='btn btn-warning btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><i class='fa fa-trash-o'></i></a>
              </td>
            </tr>    
          <?php } ?>
          
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

function uploadSubCategory() {
    $('#up-btn').val('Please wait ...').attr('disabled', 'disabled');
    var file_data = $('#image-upload').prop('files');
    var title = '';
    var summernote = '';
    var sub_category = '';
    var category = '';
    var sub_id = '';

    title = $('#myads_title').val();
    summernote = $('#summernote').code();
    sub_category = $('#sub_category').val();
    category = $('#category').val();
    sub_id = $('#sub_id').val();
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
      form_data.append('myads_title', title);
      form_data.append('summernote', summernote);
      form_data.append('sub_category', sub_category);
      form_data.append('category', category);
      form_data.append('sub_id', sub_id);
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
          url: '<?php echo site_url('myads/upload_myads_sub_category') ?>',
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