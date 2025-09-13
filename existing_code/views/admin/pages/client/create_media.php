<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Create Media</li>
</ul>
  <?php 
        $mType = '';
        switch ($mediaType) {
            case 'tv':
               $mType = 'TV';
                break;
            case 'radio':
               $mType = 'Radio';
                break;
            case 'news':
               $mType = 'e-Paper';
                break;
            case 'magazine':
               $mType = 'e-Magazine';
                break;
            case 'webnews':
               $mType = 'Web';
                break;
            case 'youtube':
               $mType = 'Youtube';
                break;
            case 'mygod':
               $mType = 'My-god';
                break;
            default:
               $mType = 'Media';
                break;
        }
    ?>

<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Create Media <?php echo $mType ?></h3>
   </div>

   <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/upload_media_channel_form') ?>" id="header-slider" action="#" class="form-horizontal" data-parsley-validate > 
   <div class="panel-body">
      <div class="col-md-6 col-md-offset-2">

      
        <div class="form-group">
          <label class="control-label col-sm-4" for="no_of_days">Media Type <font color="red">*</font></label>
          <div class="col-sm-6">
           <select class="form-control" name="media_type" id="media_type">
              <option value="<?php echo $mediaType ?>"><?php echo $mType ?></option>
           </select>
          </div>
        </div>
    <?php
        $display = '';
        $disabled = 'disabled';
        $locatSelect = '';
        if ($mediaType == 'webnews' || $mediaType == 'youtube') {
           $display = 'none';
            $disabled = '';
            $locatSelect = '4';
        }
    ?>
      <?php if ($mType == 'e-Magazine') { ?>
        <div class="form-group">
          <label class="control-label col-sm-4" for="type">Select Periodicity <font color="red">*</font></label>
          <div class="col-sm-6"> 
               <select class="form-control" required="" data-parsley-required-message="Please select Location Type" onchange="filter_country_dropdown(this.value)" id="type" name="type">
                <option value="">Select Periodicity</option>
                <option value="Weekly">Weekly</option>
                <option value="Fortnightly">Fortnightly</option>
                <option value="">Monthly</option>
                <option value="Quarterly">Quarterly</option>
                <option value="Half-yearly">Half-yearly</option>
                <option value="Yearly">Yearly</option>
               </select>
             </div>
        </div>

      
       <div class="form-group">
          <div class="col-md-1 lable-box">Sun</div>
          <div class="col-md-1 lable-box">Mon</div>
          <div class="col-md-1 lable-box">Tue</div>
          <div class="col-md-1 lable-box">Wed</div>
          <div class="col-md-1 lable-box">Thu</div>
          <div class="col-md-1 lable-box">Fri</div>
          <div class="col-md-1 lable-box">Sat</div>
       </div>

<style type="text/css">
  .lable-box{
     border: 1px solid #ccc;
    border-radius: 8px;
    margin-left: 0.5rem;
    padding-right: 10px;
    text-align: center;
  }
 
}
</style>
      <?php } ?>
      <div class="form-group">
        <label class="control-label col-sm-4" for="type">Select Type <font color="red">*</font></label>
        <div class="col-sm-6"> 
             <select class="form-control" required="" data-parsley-required-message="Please select Location Type" onchange="filter_country_dropdown(this.value)" id="type" name="type">
               <option value="">Select Type</option>
               <option <?php if($locatSelect == 4) echo 'disabled' ?> value="1">International</option>
               <option <?php if($locatSelect == 4) echo 'disabled' ?> value="2">National</option>
               <option <?php if($locatSelect == 4) echo 'disabled' ?> value="3">Regional</option>
               <option <?php if($locatSelect == 4) echo 'selected' ?> value="4">Local</option>
             </select>
           </div>
      </div>
      <script type="text/javascript">
        function filter_country_dropdown(value) {
          if (value == 1 || value == '') {
            $('#country').prop('disabled',true);
          }else{
            $('#country').prop('disabled',false);
            $('#country').attr('required','required');
          }
          get_country_wise_state();
          get_state_wise_disctrict();
        }
      </script>

        <div class="form-group">
          <label class="control-label col-sm-4" for="country">Country</label>
           <div class="col-sm-6">
             <select name="country" class="form-control" data-parsley-required-message="Please select Country" <?php echo $disabled ?> onchange="get_country_wise_state()" id="country">
               <option value="">Select Country</option>
               <?php foreach ($country as $key => $val) { ?>
                 <option value="<?php echo $val->id ?>"><?php echo $val->country ?> </option>
               <?php } ?>
             </select>
           </div>
         </div>
         <script type="text/javascript">
           function get_country_wise_state() {
             var type = $('#type').val();
             if (type == 3 || type == 4) {
               $('#state').prop('disabled',false);
               $('#state').attr('required','required');
               var countryId =$('#country').val();
               $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
                 var state = jQuery.parseJSON(data);
                 console.log(state);
                 var output='';
                 output+='<option value="">Select State</option>';
                 var len=state.length;
                 for (var i=0,j=len; i < j; i++) {
                     output+='<option value="'+state[i].id+'">'+state[i].state+'</option>'; 
                 }
                 $('#state').html(output);
               });
             }else{
                $('#state').val('');
                $('#state').prop('disabled',true);
             }
           }
         </script>

        <div class="form-group">
          <label class="control-label col-sm-4" for="state">State</label>
           <div class="col-sm-6">
             <select name="state" class="form-control" data-parsley-required-message="Please select State" disabled="true" onchange="get_state_wise_disctrict()" id="state">
               <option value="">Select state</option>
             </select>
           </div>
         </div>

         <script type="text/javascript">
           function get_state_wise_disctrict() {
             var type = $('#type').val();
             if (type == 4) {
               $('#district').prop('disabled',false);
               $('#district').attr('required','required');
               var state =$('#state').val();
               $.post("<?php echo site_url('country_controller/get_state_by_district')?>",{state:state},function(data){
                 var districts = jQuery.parseJSON(data);
                 console.log(districts);
                 var output='';
                 output+='<option value="">Select District</option>';
                 var len=districts.length;
                 for (var i=0,j=len; i < j; i++) {
                     output+='<option value="'+districts[i].id+'">'+districts[i].district+'</option>'; 
                 }
                 $('#district').html(output);
               });
             }else{
                $('#district').val('');
                $('#district').prop('disabled',true);
             }
           }
         </script>

         <div class="form-group">
          <label class="control-label col-sm-4" for="district">Disctrict</label>
           <div class="col-sm-6">
             <select name="district" class="form-control" data-parsley-required-message="Please select District" disabled="true" id="district">
               <option value="">Select district</option>
             </select>
           </div>
         </div>

         <div class="form-group">
            <label class="control-label col-sm-4" for="no_of_days">Category <font color="red">*</font></label>
            <div class="col-sm-6">
               <select class="form-control" required data-parsley-required-message="Please select Category" name="media_category" id="media_category">
                  <option value="">Select Category</option>
                  <?php foreach ($media_category as $key => $cat) { ?>
                    <option value="<?php echo $cat->id ?>"><?php echo $cat->category ?></option>
                  <?php } ?>
               </select>
            </div>
         </div>
         <div class="form-group">
            <label class="control-label col-sm-4" for="no_of_days">Language <font color="red">*</font></label>
            <div class="col-sm-6">
               <select class="form-control" required data-parsley-required-message="Please select Language" name="language" id="language">
                  <option value="">Select Language</option>
                   <?php foreach ($language as $key => $lang) { ?>
                    <option value="<?php echo $lang->id ?>"><?php echo $lang->lang_1.' '.$lang->lang_2 ?></option>
                  <?php } ?>
               </select>
            </div>
         </div>

        <div class="form-group">
            <label class="control-label col-sm-4" for="no_of_days">Media Name (English) <font color="red">*</font></label>
            <div class="col-sm-6">
               <input type="text" required name="media_name" data-parsley-required-message="Please enter Meida Name" class="form-control">
            </div>
         </div> 
        <div class="form-group">
            <label class="control-label col-sm-4" for="no_of_days">Media Name (Regional language)</label>
            <div class="col-sm-6">
               <input type="text" data-parsley-required-message="Please enter Valid Meida Name" name="media_name_regional" class="form-control">
            </div>
         </div> 
     
         <div class="form-group">
            <label class="control-label col-sm-4" for="no_of_days">Media Logo <font color="red">*</font></label>
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
            <a class="btn btn-danger" href="<?php echo site_url('client_controller/media_dashboard') ?>">Back</a>
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