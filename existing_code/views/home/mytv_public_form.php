<div class="container mb-5">
    <div class="row" style="width: 100%;display: contents;">
        <div class="panel panel-default mb-5 " style="padding: 12px;" >
            <div class="panel-body">
                <form enctype="multipart/form-data" method="post" action="" class="form-horizontal" data-parsley-validate >
                  <input type="hidden" name="mytv_type" value="<?php echo $mytvType ?>">
                  <div class="form-group">
                    <p>Country</p>
                      <select name="country" class="form-control" onchange="get_country_wise_state()" id="notice_country">
                        <option value="">Select Country</option>
                        <?php foreach ($country as $key => $val) { ?>
                          <option value="<?php echo $val->id ?>"><?php echo $val->country ?> </option>
                        <?php } ?>
                      </select>

                  </div>
                      <script type="text/javascript">
                        function get_country_wise_state() {
                          $('#notice_state').prop('disabled',false);
                          var countryId =$('#notice_country').val();
                          $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
                            var state = jQuery.parseJSON(data);
                            console.log(state);
                            var output='';
                            output+='<option value="">Select State</option>';
                            var len=state.length;
                            for (var i=0,j=len; i < j; i++) {
                                output+='<option value="'+state[i].id+'">'+state[i].state+'</option>'; 
                            }
                            $('#notice_state').html(output);
                          });
                          
                        }
                      </script>

                     <div class="form-group">
                        <p>State</p>
                       
                          <select name="state" class="form-control" disabled="true" onchange="get_state_wise_disctrict()" id="notice_state">
                            <option value="">Select state</option>
                          </select>

                      </div>

                      <script type="text/javascript">
                        function get_state_wise_disctrict() {
                            $('#notice_district').prop('disabled',false);
                            var state =$('#notice_state').val();
                            $.post("<?php echo site_url('country_controller/get_state_by_district')?>",{state:state},function(data){
                              var districts = jQuery.parseJSON(data);
                              console.log(districts);
                              var output='';
                              output+='<option value="">Select District</option>';
                              var len=districts.length;
                              for (var i=0,j=len; i < j; i++) {
                                  output+='<option value="'+districts[i].id+'">'+districts[i].district+'</option>'; 
                              }
                              $('#notice_district').html(output);
                            });
                          
                        }
                      </script>

                      <div class="form-group">
                        <p>Disctrict</p>
                       
                          <select name="district" class="form-control" disabled="true" id="notice_district">
                            <option value="">Select district</option>
                          </select>
                      </div>

                    <div class="form-group">
                      <p>Select Language <font color="red">*</font></p>
                        <select class="form-control" required="" id="notice_language" name="language">
                          <option value="">Select Language</option>
                            <?php foreach ($language as $key => $lang) { ?>
                              <option value="<?php echo $lang->lang_1 ?>"><?php echo $lang->lang_1.' '.$lang->lang_2 ?></option>
                            <?php } ?>
                           <option value="Others">Others</option>
                        </select>
                    </div>

                    <div class="form-group">
                      <p>Select Category <font color="red">*</font></p>
                        <select class="form-control" required="" onchange="select_category_type(this.value)" id="notice_category" name="category">
                          <option value="1">Image</option>
                          <option value="2">Video</option>
                          <option value="3">Article</option>
                        </select>
                    </div>


                    <div class="container" id="upload-Img" style="padding: 0;">
                    <p class="mb-4">Main Image</p>
                      <div class="row" style="height: 116px;" >
                        <div class="form-group" >
                          <div class="col-sm">
                            <div class="input--file">
                              <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24">
                                  <circle cx="12" cy="12" r="3.2"/>
                                  <path d="M9 2l-1.83 2h-3.17c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-12c0-1.1-.9-2-2-2h-3.17l-1.83-2h-6zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                                  <path d="M0 0h24v24h-24z" fill="none"/>
                                </svg>
                              </span>
                              <input type="file" accept="image/*" class="form-control notice_image" onclick="upload_image_notice(1)" id="image-upload1">
                            </div>
                          </div>
                          <div id="imageDispaly1" class="notice-base-img" style="position: relative;top: -80px;right: -15px; display: none; ">
                            <img style="width: 80px;height: 80px" id="upload-Preview1">
                            <i class="fa fa-remove" onclick="remove_loaded_img(1)" style="font-size:28px;color:red;position: absolute;top: -10px; right: 24px;"></i>
                          </div>

                        </div>
                        <div class="form-group" id="upload-Img">
                          <div class="col-sm">
                            <div class="input--file">
                              <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24">
                                  <circle cx="12" cy="12" r="3.2"/>
                                  <path d="M9 2l-1.83 2h-3.17c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-12c0-1.1-.9-2-2-2h-3.17l-1.83-2h-6zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                                  <path d="M0 0h24v24h-24z" fill="none"/>
                                </svg>
                              </span>
                             <input type="file" accept="image/*" class="form-control notice_image"  onclick="upload_image_notice(2)" id="image-upload2">
                            </div>
                          </div>
                          <div id="imageDispaly2" class="notice-base-img" style="position: relative;top: -80px;right: -15px; display: none;">
                            <img style="width: 80px;height: 80px" id="upload-Preview2">
                            <i class="fa fa-remove" onclick="remove_loaded_img(2)" style="font-size:28px;color:red;position: absolute;top: -10px; right: 24px;"></i>
                          </div>
                        </div>

                        <div class="form-group" id="upload-Img">
                          <div class="col-sm">
                            <div class="input--file">
                              <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24">
                                  <circle cx="12" cy="12" r="3.2"/>
                                  <path d="M9 2l-1.83 2h-3.17c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-12c0-1.1-.9-2-2-2h-3.17l-1.83-2h-6zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                                  <path d="M0 0h24v24h-24z" fill="none"/>
                                </svg>
                              </span>
                             <input type="file" accept="image/*" class="form-control notice_image"  onclick="upload_image_notice(3)" id="image-upload3">
                            </div>
                          </div>
                          <div id="imageDispaly3"  class="notice-base-img" style="position: relative;top: -80px;right: -15px; display: none;">
                            <img style="width: 80px;height: 80px" id="upload-Preview3">
                            <i class="fa fa-remove" onclick="remove_loaded_img(3)" style="font-size:28px;color:red;position: absolute;top: -10px; right: 24px;"></i>
                          </div>
                        </div>
                      </div>

                      <div class="row" style="height: 116px;" >
                        <div class="form-group" style="height: 75px;" >
                          <div class="col-sm">
                            <div class="input--file">
                              <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24">
                                  <circle cx="12" cy="12" r="3.2"/>
                                  <path d="M9 2l-1.83 2h-3.17c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-12c0-1.1-.9-2-2-2h-3.17l-1.83-2h-6zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                                  <path d="M0 0h24v24h-24z" fill="none"/>
                                </svg>
                              </span>
                              <input type="file" accept="image/*" class="form-control notice_image"  onclick="upload_image_notice(4)" id="image-upload4">
                            </div>
                          </div>
                          <div id="imageDispaly4" class="notice-base-img" style="position: relative;top: -80px;right: -15px; display: none;">
                            <img style="width: 80px;height: 80px"  id="upload-Preview4">
                            <i class="fa fa-remove" onclick="remove_loaded_img(4)" style="font-size:28px;color:red;position: absolute;top: -10px; right: 24px;"></i>
                          </div>
                        </div>
                        <div class="form-group" id="upload-Img" style="height: 75px;">
                          <div class="col-sm">
                            <div class="input--file">
                              <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24">
                                  <circle cx="12" cy="12" r="3.2"/>
                                  <path d="M9 2l-1.83 2h-3.17c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-12c0-1.1-.9-2-2-2h-3.17l-1.83-2h-6zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                                  <path d="M0 0h24v24h-24z" fill="none"/>
                                </svg>
                              </span>
                             <input type="file" accept="image/*" class="form-control notice_image"  onclick="upload_image_notice(5)" id="image-upload5">
                            </div>
                          </div>
                          <div id="imageDispaly5" class="notice-base-img" style="position: relative;top: -80px;right: -15px; display: none;">
                            <img style="width: 80px;height: 80px" id="upload-Preview5">
                            <i class="fa fa-remove" onclick="remove_loaded_img(5)" style="font-size:28px;color:red;position: absolute;top: -10px; right: 24px;"></i>
                          </div>
                        </div>
                        
                        <div class="form-group" id="upload-Img" style="height: 75px;">
                          <div class="col-sm">
                            <div class="input--file">
                              <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24">
                                  <circle cx="12" cy="12" r="3.2"/>
                                  <path d="M9 2l-1.83 2h-3.17c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-12c0-1.1-.9-2-2-2h-3.17l-1.83-2h-6zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                                  <path d="M0 0h24v24h-24z" fill="none"/>
                                </svg>
                              </span>
                              <input type="file" accept="image/*" class="form-control notice_image"  onclick="upload_image_notice(6)" id="image-upload6">
                            </div>
                          </div>
                          <div id="imageDispaly6" class="notice-base-img" style="position: relative;top: -80px;right: -15px; display: none;">
                            <img style="width: 80px;height: 80px" id="upload-Preview6">
                            <i class="fa fa-remove" onclick="remove_loaded_img(6)" style="font-size:28px;color:red;position: absolute;top: -10px; right: 24px;"></i>
                          </div>
                        </div>

                      </div>
                    </div>

                    <div class="form-group mt-4" id="upload-video" style="display: none; height: 75px; ">
                      <div class="col-sm">
                        <div class="input--file" style="text-align: center;">
                          <span id="hidden_fileupaload">
                            <svg onclick='$("#fileupload1").click()' xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-cloud-upload" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"/>
                              <path fill-rule="evenodd" d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3z"/>
                            </svg>
                          </span>
                          <input style="display:none" type="file" id="fileupload1" />
                        </div>
                      </div>
                      <div id="videoDispaly" style="position: relative;top: -80px;right: 0px; text-align: center; display: none; ">
                        <img style="width: 80px;height: 80px; margin-bottom: -1rem;" src="<?php echo base_url().'assets/success.png' ?>">
                        <i class="fa fa-remove" onclick="remove_loaded_video()" style="font-size:28px;color:red;position: absolute;top: -10px;"></i>
                        <h3 style="color: green;">Upload Success</h3>
                      </div>
                      <div id="loader-visible" style="position: relative;top: -80px;right: 0px; text-align: center;display: none; ">
                        <img style="width: 80px;height: 80px" src="<?php echo base_url().'assets/img/loading-circle-gif.gif' ?>">
                      </div>

                      <input type="hidden" name="video_path" id="videoPath">
                    </div>


                    <div class="form-group">
                        <p>Title <font color="red">*</font></p>
                        <input type="text" required="" class="form-control" id="missing_title">
                    </div>
                    <div class="form-group" id="textSummernote">
                        <p>Description</p>
                        <textarea class="form-control" id="missing_description"></textarea>
                    </div>

                    <div class="form-group">
                        <p style="margin-bottom: 1rem;" >Select Any One </p>
                        <label class="radio-inline" for="sender_show_button-1" style="margin-right:1rem" >
                          <input type="radio" style="transform: scale(2);margin-right: 0.5rem;margin-bottom: 1rem;width: 20px;" data-parsley-group="block1" class="sender-radio" onclick="show_amount_input_box_entry(1)" name="sender_show_button" id="sender_show_button-1" value="Paid">
                            Paid
                        </label>

                        <label class="radio-inline" for="sender_show_button-0" >
                          <input type="radio" style="transform: scale(2);margin-right: 0.5rem;margin-bottom: 1rem;width: 20px;"  data-parsley-group="block0" class="sender-radio"  onclick="show_amount_input_box_entry(0)" name="sender_show_button" id="sender_show_button-0" value="Free">
                            Free
                        </label>
                    </div>
                    <div class="form-group" id="show_paid_amount" style="display: none;" >
                        <p>Amount <font color="red">*</font></p>
                        <input type="text" required="" class="form-control" name="paid_amount" id="paid_amount">
                    </div>

                </form>
            </div>
            <div class="panel-footer">
                <center>
                    <input type="button" id="up-btn" onclick="uploadMissing()" class="btn btn-info" value="Submit">
                    <a class="btn btn-danger" href="<?php echo site_url('welcome/notice') ?>">Cancel</a>
                </center>
            </div>
        </div>
       
  
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



    </div>
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
</style>
<script type="text/javascript">
  function show_amount_input_box_entry(val) {
    if (val == 1) {
        $('#show_paid_amount').show();
    }else{
      $('#show_paid_amount').hide();
    }
  }
</script>
<script type="text/javascript">
$(function () {
  $("#fileupload1").change(function () {
    var file_data = $('#fileupload1').prop('files')[0];
    $('#videoDispaly').css('display','none');
    var form_data = new FormData();
    form_data.append('video_file', file_data);
    $('#hidden_fileupaload').hide();
    $('#loader-visible').css('display','block');
    $.ajax({
      url: '<?php echo site_url('mytv/upload_video_publis_form') ?>',
      type: 'post',
      data: form_data,
      cache: false,
      contentType: false,
      processData: false,
      success:function(data){
        var resData = JSON.parse(data);
        // console.log(resData);
        if (resData.status == 'success') {
          $('#videoDispaly').css('display','block');
          $('#videoPath').val(resData.file_name);
        }
        $('#loader-visible').css('display','none');
        $('#hidden_fileupaload').show();

        // $('#video_submit').val('Submit').removeAttr('disabled');
        // $('#video_file').val('');
        // $(".loader-background").hide();
      }
    });

  });
});
</script>

<script type="text/javascript">
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

  function remove_loaded_video() {
    $('#upload-Video-Preview').removeAttr('src');
    $('#videoDispaly').hide();
  }
  function remove_loaded_img(i) {
    $('#upload-Preview'+i).removeAttr('src');
    $('#imageDispaly'+i).hide();
  }

    $(document).ready(function(){
        content_get_state();
    });

    function content_get_state() {
        var countryId = $('#country').val();
        $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
            var state = jQuery.parseJSON(data);
            var output='';
            output+='<option value="">Select State</option>';
            var len=state.length;
            for (var i=0,j=len; i < j; i++) {
              output+='<option value="'+state[i].id+'">'+state[i].state+'</option>'; 
            }
            $('#state').html(output);
        });
    }

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

$body = $("body");
function uploadMissing() {
    
   
    var category = $('#notice_category').val();
    var file_data = '';
    if (category == 1) {
      var file_data = $('.notice_image').prop('files');
    }
    var videoPath ='';
    if (category == 2) {
      videoPath = $('#videoPath').val();
    }
    var title = '';
    var description = '';
    title = $('#missing_title').val();
    description = $('#missing_description').val();
    var country = $('#notice_country').val();
    var state = $('#notice_state').val();
    var district = $('#notice_district').val();
    var type = $('#notice_type').val();
    var sender_radio =  $('input[name=sender_show_button]:checked').val();

    var language = $('#notice_language').val();

    if (type == '2') {
      if (country =='') {
        alert('Please Select Country');
        $('#up-btn').val('Upload').prop('disabled', false);
        return false; 
      }
    }

    if (type == '3') {
      if (state =='') {
        alert('Please Select state');
        $('#up-btn').val('Upload').prop('disabled', false);
        return false; 
      }
    }
    
    if (type == '4') {
      if (district =='') {
        alert('Please Select District');
        $('#up-btn').val('Upload').prop('disabled', false);
        return false; 
      }
    }


    if (type == '') {
      alert('Selec Type cannot be empty');
      $('#up-btn').val('Upload').prop('disabled', false);
      return false; 
    }
    if (language == '') {
      alert('Language cannot be empty');
      $('#up-btn').val('Upload').prop('disabled', false);
      return false; 
    }
    if (title == '') {
        alert('Title cannot be empty');
        $('#up-btn').val('Upload').prop('disabled', false);
        return false; 
    }
    
    if (category == 1) {
      if (file_data.length === 0) { 
          alert('Please choose an image to upload');
          $('#up-btn').val('Upload').prop('disabled', false);
          return false; 
      }
    }

    if (category == 3) {
      if (description =='') {
        alert('Please Enter Description');
        $('#up-btn').val('Upload').prop('disabled', false);
        return false; 
      }
    }

    if (sender_radio ==undefined) {
        alert('Please Choose Dispaly Name');
        $('#up-btn').val('Upload').prop('disabled', false);
        return false; 
    }
    var groupname = '<?php echo $groupname ?>';
    var mytvType = '<?php echo $mytvType ?>';
  var images = $('.notice-base-img').find('img').map(function() { return this.src; }).get();
    var blobs = [];
    var form_data = new FormData();
    form_data.append('file[]', file_data);
    form_data.append('missing_title', title);
    form_data.append('description', description);
    form_data.append('country', country);
    form_data.append('state', state);
    form_data.append('district', district);
    form_data.append('location_type', type);
    form_data.append('category', category);
    form_data.append('language', language);
    form_data.append('videoPath',videoPath);
    form_data.append('sender_status',sender_radio);
    var total_images = 0;
    for(var i in images) {
      var image = images[i];
      if (images[i] !='') {
        var base64ImageContent = image.replace(/^data:image\/(png|jpg|jpeg);base64,/, "");
        var blob = base64ToBlob(base64ImageContent, 'image/png');
        form_data.append('file_name[]', blob);
        total_images++;
      }
    }
    $('#up-btn').val('Please wait ...').attr('disabled', 'disabled');
    $body.addClass("loading");
    $.ajax({
      url: '<?php echo site_url('mytv/upload_mytv_public') ?>',
      type: 'post',
      data: form_data,
      cache: false,
      contentType: false,
      processData: false,
      success: function(data) {
        console.log(data);
         $body.removeClass("loading");
         // return false;
        // var failed = parseInt(data);
        // $(function(){
        //   new PNotify({
        //     title: 'Success',
        //     text: 'uploaded images successfully',
        //     type: 'success',
        //   });
        // });
        window.location.href='<?php echo site_url('mytv/mtv_public_success/') ?>'+groupname+'/'+mytvType;;
        // location.reload();
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