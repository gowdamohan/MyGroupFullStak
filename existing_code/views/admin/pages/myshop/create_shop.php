<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>My Shop</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title"><?php echo ucfirst($shop_type).' Shop' ?></h3>
   </div>
   <form enctype="multipart/form-data" method="post" id="category-slider" action="<?php echo site_url('client_controller/insert_create_shop_new') ?>" class="form-horizontal" data-parsley-validate > 
	 		<input type="hidden" name="shop_type" value="<?php echo $shop_type ?>">
	      <div class="panel-body">
	      	<div class="col-md-6 col-md-offset-2">
            <div class="form-group">
                <label class="control-label col-sm-4">Category <font color="red" >*</font></label>
                <div class="col-md-8">
                   <select class="form-control" required  name="category" id="category" >
                    <option value="">Select Category</option>
                    <?php foreach ($shop_category as $key => $val) { ?>
                       <option value="<?php echo $val->id ?>"><?php echo $val->category ?></option>
                    <?php } ?>
                   </select>
                </div>
            </div>

          	<div class="form-group">
          		<label class="control-label col-md-4">Shop Name <font color="red" >*</font></label>
	            <div class="col-md-8">
	               <input type="text" id="shop_name" required placeholder="Enter Shop Name"  class="form-control" name="shop_name" >
	            </div>
	         	</div>
            <?php if ($shop_type == 'local' || $shop_type == 'wholesale' || $shop_type == 'brands') { ?>
              <div class="form-group">
                  <label class="control-label col-md-4">Shop Name in Regional Language</label>
                  <div class="col-md-8">
                     <input type="text" id="shop_name_regional" placeholder="Enter Regional Language"  class="form-control" name="shop_name_regional" >
                  </div>
              </div>

              <div class="form-group">
                  <label class="control-label col-md-4">Address <font color="red" >*</font></label>
                  <div class="col-md-8">
                    <textarea class="form-control" required name="address"></textarea>
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-4">Landmark <font color="red" >*</font></label>
                  <div class="col-md-8">
                     <input type="text" id="landmark" required placeholder="Enter landmark"  class="form-control" name="landmark" >
                  </div>
              </div>

              <div class="form-group">
                  <label class="control-label col-md-4">Pincode <font color="red" >*</font></label>
                  <div class="col-md-8">
                     <input type="text" id="pincode" required placeholder="Enter pincode"  class="form-control" name="pincode" >
                  </div>
              </div>
            <?php } ?>
	         	
	         	
	         	<div class="form-group" id="upload-Img">
	          		<label class="control-label col-md-4">Shop Logo</label>
		           <div class="col-sm-6">
                 <div class="form-group mt-4" id="upload-video" style="height: 98px;">
                    <div class="col-sm-12">
                      <div class="input--file" style="height: 80px; float: left;">
                        <span id="hidden_fileupaload">
                          <svg onclick='$("#fileupload1").click()' xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-cloud-upload" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"/>
                            <path fill-rule="evenodd" d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3z"/>
                          </svg>
                        </span>
                        <input style="display:none" required type="file" data-parsley-required-message="Please Upload Logo" id="fileupload1" />
                      </div>
                    </div>
                    <div id="logoDispaly" style="position: absolute;top: 80px;left: 0px; text-align: center; display: none; ">
                      <img style="width: 80px;height: 80px;" src="<?php echo base_url().'assets/success.png' ?>">
                      <i class="glyphicon glyphicon-remove-circle" onclick="remove_loaded_video()" style="font-size:28px;color:red;position: relative;top: -27px;"></i>
                      <h3 style="color: green;">Upload Success</h3>
                    </div>
                    <div id="loader-visible" style="position: relative;top: -80px;right: 0px; text-align: center;display: none; ">
                      <img style="width: 80px;height: 80px" src="<?php echo base_url().'assets/loading-circle-gif.gif' ?>">
                    </div>
                    <input type="hidden" name="img_path" id="logo_imgPath">
                  </div>
              </div>
	         	</div>

	      	</div>
	      </div>
	      <div class="panel-footer">
	      	<center>
	      		<button type="submit" class="btn btn-primary">Submit</button>
	      		<a class="btn btn-danger" href="<?php echo site_url('client_controller/create_client_shop') ?>">Cancel</a>
	      	</center>
	      </div>
      </form>

</div>
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
                  url('../assets/img/loading_icon.gif') 
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
    top: 20px;
    left: 0;
    opacity: 0;
  }
</style>
<script type="text/javascript">
    $(function () {
      $("#fileupload1").change(function () {
        var file_data = $('#fileupload1').prop('files')[0];
        $('#logoDispaly').css('display','none');
        var form_data = new FormData();
        form_data.append('media_logo_file', file_data);
        $('#hidden_fileupaload').hide();
        $('#loader-visible').css('display','block');
        $.ajax({
          url: '<?php echo site_url('client_controller/upload_channel_media_logo') ?>',
          type: 'post',
          data: form_data,
          cache: false,
          contentType: false,
          processData: false,
          success:function(data){
            var resData = JSON.parse(data);
            // console.log(resData);
            if (resData.status == 'success') {
              $('#logoDispaly').css('display','block');
              $('#logo_imgPath').val(resData.file_name);
              $('#hidden_fileupaload').css('display','none');
            }
            $('#loader-visible').css('display','none');

          }
        });

      });
    });
</script>

<script type="text/javascript">
  	function upload_image_notice(k) {
      $('#image-upload'+k).val('');
      $("#image-upload"+k).ImageResize({  
        maxWidth: 500,  
        onImageResized: function (imageData) { 
          $('#imageDispaly'+k).css('display','block');
          $("#upload-Preview"+k).attr("src",imageData);  
        }
      });
 	}

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
      // $(control).attr("multiple", "false");
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