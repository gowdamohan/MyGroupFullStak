<ul class="breadcrumb">
    <li><a href="#">Dashboard</a></li>
    <li><a href="<?php echo site_url('myads/gallery'); ?>">Galleries</a></li>
</ul>
<hr>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Upload Gallery items to - <?php echo '<strong>' . $gallery_info->gallery_name . '</strong>'; ?></h3>
        <ul class="panel-controls hidden-sm" style="float:right">
            <li><a href="<?php echo site_url('myads/gallery'); ?>" data-placement="top" data-toggle="tooltip" data-original-title="Back" class="control-primary"><span class="fa fa-mail-reply"></span></a>
                <span>&nbsp; &nbsp; &nbsp; &nbsp; </span>
            </li>
        </ul>
    </div>
 <div class="panel-body">
    <div id="fullscreen-loading">Loading&#8230;</div>
    <div class="col-md-12">
        <p>
            <br>
            <strong>Notes: </strong><br>
            1. To upload an image, click on <strong>Choose File</strong> and select a file/multiple files (Maximum 10 files). <br>
            2. Enter the image description and add tags.<br>
            3. Click Upload to upload the image(s).<br>
        </p>
    </div>

<center>
    <div class="gallery col-md-12">
        <div class="border border-primary col-md-3" style=' border: 4px solid #33E2FF;  height: 100%; border-radius: 5px; padding : 0px 4px; margin-top:10px;'>
            
            <form id="multi-form" enctype="multipart/form-data" action="" method='POST'>
                <div>
                    <input type="hidden" name="gal_id" id="gal_id" value="<?php echo $gallery_info->gallery_id; ?>">
                    <span>Select maximum of 10 images at once.</span><br>
                    <div class="form-group">
                        <div class="col-md-12" style="padding: 2px 0px;">
                                <input multiple="" type="file"  id="image-upload" class="form-control" name="photo[]" required accept="image/png, image/jpeg, image/jpg">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12" style="padding: 2px 0px;">
                            <textarea name="image_description" type="text" id="img_desc" class="form-control input-md" placeholder="Enter Description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12" style="padding: 2px 0px;">
                            <input type="button" disabled="" id="up-btn" class="btn btn-success" value="Upload">
                        </div>
                    </div>
                </div>
            </form>
            <div id="original-Img" style="display: none;">
    
            </div>
            <div id="upload-Preview" style="display: none;">
                
            </div>
        </div>
        <div id="images_new">
            <?php foreach ($image_info as $row) { ?>
                <a class="gallery-item" href="<?php echo $this->filemanager->getFilePath($row['image_name']); ?>" title="" data-gallery="">
                    <div class="image">
                        <img style='object-fit: cover;' src="<?php echo $this->filemanager->getFilePath($row['image_name']) ?>">
                        <ul class="gallery-item-controls">

                            <li><span id='deleteimage1' onclick="deleteImage(<?php echo $row['image_id']; ?>)" class="gallery-item-remove"><i class="fa fa-times"></i></span></li>
                        </ul>
                    </div>

                </a>

            <?php } ?>
        </div>
    </div>
</center>
 </div>   
</div>
<div style="display: none;">
    <img id="output" src="">
    <img id="preview" src="">
    <img id="resized_image" src="">
</div>
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class='btn btn-info' href='<?php echo base_url(); ?>'>Edit</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/downscale.js"></script>
<!-- Javascript -->

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
</script>
<script type="text/javascript">
/*START: New multi image with resize functions*/
$(document).ready(function() {
    $('#upload-Image').change(function(evt) {
        var files = evt.target.files;
        if (files.length > 10) { 
            $("#upload-Image").val('');
            bootbox.alert("Maximum of 10 images can be uploaded at once.");
            return false; 
        }
        for(var index in files) {
            // var uploadFile = files[index];
            var uploadFile = evt.target.files[index];
            // var uploadFile = document.querySelector('input[type=file]').files[index];
            // console.log(uploadFile);
            canvasdrawer(uploadFile);
        }
    });
});

function canvasdrawer(uploadFile) {
    var reduce = 1;
    if(uploadFile.size > 250000) {
        reduce = 4;
    }
    var fileReader = new FileReader();
    fileReader.readAsDataURL(uploadFile);
    fileReader.onload = function (event) {
        var image = new Image();
        image.onload=function(){
            $("#original-Img").append('<img src="'+image.src+'"/>');
            var canvas=document.createElement("canvas");
            var context=canvas.getContext("2d");
            // var imgWidth = image.width;
            // var imgHeight = image.height;
            // var max = 500;
            // var ratio = max/imgWidth;
            // newImgWidth = max;
            // newImgHeight = imgHeight / ratio;

            // //if width dosn't work
            // if(newImgHeight > max) {
            //     ratio = max/imgHeight;
            //     imgHeight = max;
            //     newImgWidth = imgWidth / ratio;
            // }
            // canvas.width = newImgWidth;
            // canvas.height = newImgHeight;
            // console.log('imgWidth:'+imgWidth);
            // console.log('imgHeight:'+imgHeight);
            // console.log('screenWidth:'+screenWidth);
            // console.log('screenHeight:'+screenHeight);
            // console.log('screenWidth:'+canvas.width);
            // console.log('screenHeight:'+canvas.height);

            /*if(reduce == 1 && (image.width > 900 && image.height > 900)) {
                reduce = 2;
            }*/
            canvas.width=image.width/reduce;
            canvas.height=image.height/reduce;
            // console.log('aWidth:'+canvas.width);
            // console.log('aHeight:'+canvas.height);
            context.drawImage(image,0,0,image.width,image.height,0,0,canvas.width,canvas.height);
            var dataURL = canvas.toDataURL();
            $("#upload-Preview").append('<img src="'+dataURL+'"/>');
            /*var destInput = document.createElement("input");
            destInput.type = "hidden";
            destInput.name = "image[]";
            destInput.value = dataURL;
            // Append image to form as hidden input
            $("#uploadForm").append(destInput);*/
        }
        image.src=event.target.result;
    };
}

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
        var desc = $('#img_desc').val();
        var gal_id = $('#gal_id').val();
        var blobs = [];
        var form_data = new FormData();
        form_data.append('file[]', file_data);
        form_data.append('gal_id', gal_id);
        form_data.append('desc', desc);
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
            url: '<?php echo site_url('myads/upload_multiple_images') ?>',
            type: 'post',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                // console.log(data);
                $('#img_desc').val('');
                $("#image_tags").remove('.tagsinput');
                var failed = parseInt(data);
                if (failed != 0) {
                    $(function(){
                        new PNotify({
                            title: 'Error',
                            text: failed+' / '+total_images + ' failed to upload',
                            type: 'error',
                        });
                    });
                } else {
                    $(function(){
                        new PNotify({
                            title: 'Success',
                            text: 'uploaded images successfully',
                            type: 'success',
                        });
                    });
                }
                $("#fullscreen-loading").hide();
                location.reload();
            }
        });
});

/*END: New multi image with resize functions*/
</script>

<script>
    $("#links").click(function(event) {
        event = event || window.event;
        var target = event.target || event.srcElement;
        var link = target.src ? target.parentNode : target;
        var options = {
            index: link,
            event: event,
            onclosed: function() {
                setTimeout(function() {
                    $("body").css("overflow", "");
                }, 200);
            }
        };
        var links = this.getElementsByTagName('a');
        blueimp.Gallery(links, options);
    });

    $("#multi-files").on("change", function() {
        if ($("#multi-files")[0].files.length > 10) {
            $("#multi-files").val('');
            bootbox.alert("Maximum of 10 images can be uploaded at once.");
        }
    });

    $("#uploader-btn").click(function(){
        $("#fullscreen-loading").show();
        $("#multi-form").submit();
    });

    function deleteImage(image_id) {
        bootbox.confirm({
            title: "Confirm",
            message: "Are you sure about deleting this image?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function(result) {
                if (result) {
                    $.ajax({
                        //$(#deleteimage1).addClass('gallery-item-remove');
                        url: '<?php echo site_url('myads/delete_image'); ?>',
                        type: 'post',
                        data: {
                            'image_id': image_id
                        },
                        success: function(data) {
                            if (data == 1) {
                                $(this).parents(".gallery-item").fadeOut(400, function() {
                                    $(this).remove();
                                });


                            } else {
                                alert("failed");
                            }
                        }
                    });
                    //alert(gId);
                } else {
                    location.reload();
                    return false;
                }
            }
        });
    }

    $('#uploadWithoutResize').click(function() {
        $('#uploadWithoutResize').val('Please wait ...').attr('disabled', 'disabled');
        
        var image = $('#output').attr('src');
        // console.log(image);
        // var base64ImageContent = image.replace(/^data:image\/(png|jpg|jpeg);base64,/, "");
        // var blob = base64ToBlob(base64ImageContent, 'image/png');
        // alert(blob);
        // console.log(blob);

        // var imgName = $("#imgArea").files[0].name;
        var desc = $('#img_desc').val();
        var tags = $('#image_tags').val();
        // var file_name = $('#output').val();

        var id = $('#id').val();
        //alert("Test")
        var file_data = $('#file').prop('files')[0];
        if (file_data == null) {
            alert('Please choose an image to upload');
            location.reload();
            return false;
        }
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('gal_id', id);
        form_data.append('desc', desc);
        form_data.append('tags', tags);
        // form_data.append('file_name', blob);

     
        $.ajax({
            url: '<?php echo site_url('admin_controller/ajax_insert_data_without_resize') ?>',
            type: 'post',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            // data: {'desc':desc,'tag':tag,'gal_id':id, 'imgName':imgName},
            success: function(data) {
                console.log(data);
                $('#uploadWithoutResize').val('Upload W/O Resize').removeAttr('disabled');
                $('#img_desc').val('');
                $("#image_tags").remove('.tagsinput');
                data = JSON.parse(data);
                // alert(data);
                if (data == "error") {
                    alert("Error in Uploading an Image.");
                } else {
                    var img = data.name;
                    //alert(img);  style='padding: 0px 2px;'
                    $("#images_new").prepend('<a class="gallery-item"  href="' + img + '"><div class="image"><img src="' + img + '"><ul class="gallery-item-controls"><li><span id = "deleteimage1" onclick="deleteImage()" class="gallery-item-remove"><i class="fa fa-times"></i></span></li></ul></div></a>');
                    $("#file").val('');
                    $("#file").val('');
                    $("#file").val('');
                }
            }
        });
    });

    // AJax call to upload and reload
    $('#uploadServerside').click(function() {
        $('#uploadServerside').val('Please wait ...').attr('disabled', 'disabled');
        
        var image = $('#output').attr('src');
        // console.log(image);
        // var base64ImageContent = image.replace(/^data:image\/(png|jpg|jpeg);base64,/, "");
        // var blob = base64ToBlob(base64ImageContent, 'image/png');
        // alert(blob);
        // console.log(blob);

        // var imgName = $("#imgArea").files[0].name;
        var desc = $('#img_desc').val();
        var tags = $('#image_tags').val();
        // var file_name = $('#output').val();

        var id = $('#id').val();
        //alert("Test")
        var file_data = $('#file').prop('files')[0];
        if (file_data == null) {
            alert('Please choose an image to upload');
            location.reload();
            return false;
        }
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('gal_id', id);
        form_data.append('desc', desc);
        form_data.append('tags', tags);
        // form_data.append('file_name', blob);

     
        $.ajax({
            url: '<?php echo site_url('galleries/ajax_insert_data_with_server_side_resize') ?>',
            type: 'post',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            // data: {'desc':desc,'tag':tag,'gal_id':id, 'imgName':imgName},
            success: function(data) {
                console.log(data);
                $('#uploadServerside').val('Upload Server-side').removeAttr('disabled');
                $('#img_desc').val('');
                $("#image_tags").remove('.tagsinput');
                data = JSON.parse(data);
                // alert(data);
                if (data == "error") {
                    alert("Error in Uploading an Image.");
                } else {
                    var img = data.name;
                    //alert(img);  style='padding: 0px 2px;'
                    $("#images_new").prepend('<a class="gallery-item"  href="' + img + '"><div class="image"><img src="' + img + '"><ul class="gallery-item-controls"><li><span id = "deleteimage1" onclick="deleteImage()" class="gallery-item-remove"><i class="fa fa-times"></i></span></li></ul></div></a>');
                    $("#file").val('');
                    $("#file").val('');
                    $("#file").val('');
                }
            }
        });
    });

    $('#upload').click(function() {
        $('#upload').val('Please wait ...').attr('disabled', 'disabled');
        
        var image = $('#resized_image').attr('src');
        // console.log(image);
        var base64ImageContent = image.replace(/^data:image\/(png|jpg|jpeg);base64,/, "");
        var blob = base64ToBlob(base64ImageContent, 'image/png');
        // alert(blob);
        // console.log(blob);

        // var imgName = $("#imgArea").files[0].name;
        var desc = $('#img_desc').val();
        var tags = $('#image_tags').val();
        // var file_name = $('#output').val();

        var id = $('#id').val();
        //alert("Test")
        var file_data = $('#file').prop('files')[0];
        if (file_data == null) {
            alert('Please choose an image to upload');
            location.reload();
            return false;
        }
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('gal_id', id);
        form_data.append('desc', desc);
        form_data.append('tags', tags);
        form_data.append('file_name', blob);

     
        $.ajax({
            url: '<?php echo site_url('galleries/ajax_insert_data') ?>',
            type: 'post',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            // data: {'desc':desc,'tag':tag,'gal_id':id, 'imgName':imgName},
            success: function(data) {
                console.log(data);
                $('#upload').val('Upload').removeAttr('disabled');
                $('#img_desc').val('');
                $("#image_tags").remove('.tagsinput');
                data = JSON.parse(data);
                // alert(data);
                if (data == "error") {
                    alert("Error in Uploading an Image.");
                } else {
                    var img = data.name;
                    //alert(img);  style='padding: 0px 2px;'
                    $("#images_new").prepend('<a class="gallery-item"  href="' + img + '"><div class="image"><img src="' + img + '"><ul class="gallery-item-controls"><li><span id = "deleteimage1" onclick="deleteImage()" class="gallery-item-remove"><i class="fa fa-times"></i></span></li></ul></div></a>');
                    $("#file").val('');
                    $("#file").val('');
                    $("#file").val('');
                }
            }
        });
    });

    $(document).ready(function() {
        $('#file').change(function(evt) {

            var files = evt.target.files;
            var file = files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
            // ResizeImage();
            resize_image();
        });
    });

    function resize_image() {
        var filesToUploads = document.getElementById('file').files;
        var file = filesToUploads[0];
        downscale(file, 400, 400).
        then(function(dataURL) {
          var destInput = document.createElement("input");
          destInput.type = "hidden";
          destInput.name = "image[]";
          destInput.value = dataURL;
          // Append image to form as hidden input
          document.forms[0].appendChild(destInput);
          // Preview image
          // var destImg = document.createElement("img");
          // destImg.src = dataURL;
        //   console.log(dataURL);
          document.getElementById('resized_image').src = dataURL;
          // $("#resized_image").attr('src', dataURL);
          // $("#hiddenImages").appendChild(destImg);
        })
    }

    function ResizeImage() {
        if (window.File && window.FileReader && window.FileList && window.Blob) {
            var filesToUploads = document.getElementById('file').files;
            var file = filesToUploads[0];
            if (file) {

                var reader = new FileReader();
                // Set the image once loaded into file reader
                reader.onload = function(e) {

                    var img = document.createElement("img");
                    img.src = e.target.result;

                    var canvas = document.createElement("canvas");
                    var ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0);

                    var MAX_WIDTH = 400;
                    var MAX_HEIGHT = 400;
                    var width = img.width;
                    var height = img.height;

                    if (width > height) {
                        if (width > MAX_WIDTH) {
                            height *= MAX_WIDTH / width;
                            width = MAX_WIDTH;
                        }
                    } else {
                        if (height > MAX_HEIGHT) {
                            width *= MAX_HEIGHT / height;
                            height = MAX_HEIGHT;
                        }
                    }
                    canvas.width = width;
                    canvas.height = height;
                    var ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0, width, height);

                    dataurl = canvas.toDataURL(file.type);
                    document.getElementById('output').src = dataurl;
                }
                reader.readAsDataURL(file);

            }

        } else {
            alert('The File APIs are not fully supported in this browser.');
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

    function validatePhoto(file) {
        if (file.size > 5000000 || file.fileSize > 5000000) {
            // $("#fileError").html("Allowed file size exceeded. (Max. 100 Kb)")
            alert("Please upload an image with less than 5MB. ");
            return false;
        }
        if (file.type != 'image/jpeg' && file.type != 'image/jpg' && file.type != 'image/png') {
            $("#" + errorId + "Error").html("Allowed file types are jpeg, jpg and png");
            return false;
        }
        return true;
    }
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/js/plugins/dropzone/dropzone.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/js/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>