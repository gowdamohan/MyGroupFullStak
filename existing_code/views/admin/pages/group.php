<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Group</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Group</h3>
   </div>

   <form enctype="multipart/form-data" method="post" action="<?php echo site_url('admin_controller/upload_group') ?>" id="header-slider" action="#" class="form-horizontal" data-parsley-validate > 
   <div class="panel-body">
      <div class="col-md-6 col-md-offset-2">

         <div class="form-group">
            <label class="control-label col-sm-3" for="no_of_days">Icon</label>
            <div class="col-sm-8">
               <?php 
               if (!empty($group->icon)) { ?>
                  <img id="previewing" width="10%" height="10%" name="photograph"  src="<?php echo base_url().$group->icon ?>" />
                  <input type="hidden" name="icon_edit" value="<?php echo $group->icon ?>" >
               <?php }else{ ?>
                  <img id="previewing" width="10%" height="10%" name="photograph"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
               <?php } ?>
               <input class="form-control" id="fileupload" name="icon_photo" type="file" style="display: none;" accept="image/*"/>
               <button type="button" style="margin-left: 2rem;" id="file_button" class="btn btn-info">Change</button>
            </div>
         </div>

         <div class="form-group">
            <label class="control-label col-sm-3" for="no_of_days">Logo</label>
            <div class="col-sm-8"> 

               <?php 
               if (!empty($group->logo)) { ?>
                  <img  width="10%" height="10%" name="photograph"  src="<?php echo base_url().$group->logo ?>" />
                  <input type="hidden" name="logo_edit" value="<?php echo $group->logo ?>" >
               <?php }else{ ?>
                  <img id="previewing1" width="10%" height="10%" name="photograph1"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
               <?php } ?>
               <input class="form-control" id="fileupload1" name="logo_photo" type="file" style="display: none;" accept="image/*"/>
               <button type="button" style="margin-left: 2rem;" id="file_button1" class="btn btn-info">Change</button>

            </div>
         </div>

         <div class="form-group">
            <label class="control-label col-sm-3" for="no_of_days">Name Image</label>
            <div class="col-sm-8"> 
               <?php 
               if (!empty($group->name_image)) { ?>
                  <img  width="10%" height="10%" name="photograph"  src="<?php echo base_url().$group->name_image ?>" />
                  <input type="hidden" name="name_photo_edit" value="<?php echo $group->name_image ?>" >
               <?php }else{ ?>
                 <img id="previewing2" width="10%" height="10%" name="photograph2"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
               <?php } ?>
               <input class="form-control" id="fileupload2" name="name_photo" type="file" style="display: none;" accept="image/*"/>
               <button type="button" style="margin-left: 2rem;" id="file_button2" class="btn btn-info">Change</button>

            </div>
         </div>


         <div class="form-group">
            <label class="control-label col-sm-3" for="no_of_days">Background Color</label>
            <div class="col-sm-8"> 
               <?php 
               $bg  ='#ff0000';
               if (!empty($group->background_color)) {
                  $bg = $group->background_color;
               } ?>
              <input type="color" style="width: 35%; height: 39px;" id="favcolor" name="background_color" value="<?php echo $bg ?>"> 
            </div>
         </div>

      </div>

      <div class="col-md-12">
         <h3>Header Ads</h3>
         <div class="col-md-4">
            <div class="form-group box" style="margin:2rem">
               <div class="col-sm-12"> 

                  <?php 
                  if (!empty($group->header_ads1)) { ?>
                     <img  id="previewing3" width="10%" height="10%" name="photograph"  src="<?php echo base_url().$group->header_ads1 ?>" />
                     <input type="hidden" name="header_ads1_edit" value="<?php echo $group->header_ads1 ?>" >
                  <?php }else{ ?>
                     <img id="previewing3" style="width: 80%; height:40px;" name="photograph3"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
                  <?php } ?>

                  <?php 
                  if (!empty($group->header_ads_url_1)) { ?>
                     <input type="text" placeholder="URL" style="margin-top: 1rem;" name="header_ads_url_1" value="<?php echo $group->header_ads_url_1 ?>" class="form-control">
                  <?php }else{ ?>
                     <input type="text" placeholder="URL" style="margin-top: 1rem;" name="header_ads_url_1" class="form-control">
                  <?php } ?>
                  <input class="form-control" id="fileupload3" name="header_ads1" type="file" style="display: none;" accept="image/*"/>
                  <button type="button" style="margin-top: 1rem;" id="file_button3" class="btn btn-info">Change-Image</button>
               </div>
            </div>
         </div>

         <div class="col-md-4">

            <div class="form-group box" style="margin:2rem">
               <div class="col-sm-12">

                  <?php 
                  if (!empty($group->header_ads2)) { ?>
                     <img id="previewing4" width="10%" height="10%" name="photograph"  src="<?php echo base_url().$group->header_ads2 ?>" />
                     <input type="hidden" name="header_ads2_edit" value="<?php echo $group->header_ads2 ?>" >
                  <?php }else{ ?>
                     <img id="previewing4" style="width: 80%; height:40px;" name="photograph4"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
                  <?php } ?>
                  
                  <?php 
                  if (!empty($group->header_ads_url_2)) { ?>
                     <input type="text" placeholder="URL" style="margin-top: 1rem;" name="header_ads_url_2" value="<?php echo $group->header_ads_url_2 ?>" class="form-control">
                  <?php }else{ ?>
                     <input type="text" placeholder="URL" style="margin-top: 1rem;" name="header_ads_url_2" class="form-control">
                  <?php } ?>
                  <input class="form-control" id="fileupload4" name="header_ads2" type="file" style="display: none;" accept="image/*"/>
                  <button type="button" style="margin-top: 1rem;" id="file_button4" class="btn btn-info">Change-Image</button>

               </div>
            </div>

         </div>
         <div class="col-md-4">

            <div class="form-group box" style="margin:2rem">
               <div class="col-sm-12"> 

                  <?php 
                  if (!empty($group->header_ads3)) { ?>
                     <img  id="previewing5" width="10%" height="10%" name="photograph"  src="<?php echo base_url().$group->header_ads3 ?>" />
                     <input type="hidden" name="header_ads3_edit" value="<?php echo $group->header_ads3 ?>" >
                  <?php }else{ ?>
                     <img id="previewing5" style="width: 80%; height:40px;" name="photograph5"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
                  <?php } ?>
                  
                  <?php 
                  if (!empty($group->header_ads_url_3)) { ?>
                     <input type="text" placeholder="URL" style="margin-top: 1rem;" name="header_ads_url_3" value="<?php echo $group->header_ads_url_3 ?>" class="form-control">
                  <?php }else{ ?>
                     <input type="text" placeholder="URL" style="margin-top: 1rem;" name="header_ads_url_3" class="form-control">
                  <?php } ?>
                  <input class="form-control" id="fileupload5" name="header_ads3" type="file" style="display: none;" accept="image/*"/>
                  <button type="button" style="margin-top: 1rem;" id="file_button5" class="btn btn-info">Change-Image</button>

               </div>
            </div>

         </div>

      </div>

     <div class="col-md-4">
         <h3>Side / Popup Ads</h3>
         <div class="col-md-12">
            <div class="form-group box" style="margin:2rem">
               <div class="col-sm-12"> 

                  <?php 
                  if (!empty($group->side_ads)) { ?>
                     <img  id="previewing6" width="10%" height="10%" name="photograph"  src="<?php echo base_url().$group->side_ads ?>" />
                     <input type="hidden" name="side_ads_edit" value="<?php echo $group->side_ads ?>" >
                  <?php }else{ ?>
                     <img id="previewing6" style="width: 80%; height:40px;" name="photograph6"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
                  <?php } ?>

                  <?php 
                  if (!empty($group->side_ads_url)) { ?>
                     <input type="text" placeholder="URL" style="margin-top: 1rem;" name="side_ads_url" value="<?php echo $group->side_ads_url ?>" class="form-control">
                     <input type="text" placeholder="URL" style="margin-top: 1rem;" name="side_seconds" value="<?php echo $group->side_seconds ?>" class="form-control">
                  <?php }else{ ?>
                     <input type="text" placeholder="URL" style="margin-top: 1rem;" name="side_ads_url" class="form-control">
                     <input type="text" placeholder="Seconds Popup" style="margin-top: 1rem;" name="side_seconds" value="" class="form-control">
                  <?php } ?>
                  <input class="form-control" id="fileupload6" name="side_ads" type="file" style="display: none;" >
                  <button type="button" style="margin-top: 1rem;" id="file_button6" class="btn btn-info">Change-Image</button>
               </div>
            </div>
         </div>
      </div>

      <div class="col-md-8">
         <h3>Main Ads</h3>
         <div class="col-md-12">
            <div class="form-group box" style="margin:2rem">
               <div class="col-sm-12"> 

                  <?php 
                  if (!empty($group->main_ads)) { ?>
                     <img  id="previewing7" width="20%" height="20%" name="photograph"  src="<?php echo base_url().$group->main_ads ?>" />
                     <input type="hidden" name="main_edit" value="<?php echo $group->main_ads ?>" >
                  <?php }else{ ?>
                     <img id="previewing7" style="width: 80%; height:80px;" name="photograph7"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
                  <?php } ?>

                  <?php 
                  if (!empty($group->main_ads_url)) { ?>
                     <input type="text" placeholder="URL" style="margin-top: 1rem;" name="main_ads_url" value="<?php echo $group->main_ads_url ?>" class="form-control">
                  <?php }else{ ?>
                     <input type="text" placeholder="URL" style="margin-top: 1rem;" name="main_ads_url" class="form-control">
                  <?php } ?>
                  <input class="form-control" id="fileupload7" name="main_ads" type="file" style="display: none;" accept="image/*"/>
                  <button type="button" style="margin-top: 1rem;" id="file_button7" class="btn btn-info">Change-Image</button>
               </div>
            </div>
         </div>
      </div>

   </div>
   <div class="panel-footer">
      <center><button type="submit" class="btn btn-primary">Save</button></center>
   </div>
   </form>
</div>


<script type="text/javascript">

   // icon
   document.getElementById('file_button').addEventListener('click', openDialog);

   function openDialog() {
     document.getElementById('fileupload').click();
   }

   $('#fileupload').change(function(){
      var src = $(this).val();
      $('#file_button').removeClass('btn btn-info');
      $('#file_button').addClass('btn btn-warning');
      if(src && validatePhoto(this.files[0], 'fileupload')){
         $("#fileuploadError").html("");
         readURL(this);
       } else{
         this.value = null;
       }
   });

function validatePhoto(file,errorId){
    if (file.size > 100000 || file.fileSize > 100000)
    {
       $("#"+errorId+"Error").html("Allowed file size exceeded. (Max. 100 Kb)")
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
      $('#previewing').attr('src', e.target.result);
  }

  reader.readAsDataURL(input.files[0]);
 }
}

// logo image

document.getElementById('file_button1').addEventListener('click', openDialog1);

   function openDialog1() {
     document.getElementById('fileupload1').click();
   }

$('#fileupload1').change(function(){
   var src = $(this).val();
   $('#file_button1').removeClass('btn btn-info');
   $('#file_button1').addClass('btn btn-warning');
   if(src && validatePhoto1(this.files[0], 'fileupload1')){
      $("#fileuploadError1").html("");
      readURL1(this);
    } else{
      this.value = null;
    }
});

function validatePhoto1(file,errorId){
    if (file.size > 100000 || file.fileSize > 100000)
    {
       $("#"+errorId+"Error").html("Allowed file size exceeded. (Max. 100 Kb)")
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
      $('#previewing1').attr('src', e.target.result);
  }

  reader.readAsDataURL(input.files[0]);
 }
}


// Name image

document.getElementById('file_button2').addEventListener('click', openDialog2);
   function openDialog2() {
     document.getElementById('fileupload2').click();
   }

$('#fileupload2').change(function(){
   var src = $(this).val();
   $('#file_button2').removeClass('btn btn-info');
   $('#file_button2').addClass('btn btn-warning');
   if(src && validatePhoto2(this.files[0], 'fileupload2')){
      $("#fileuploadError2").html("");
      readURL2(this);
    } else{
      this.value = null;
    }
});

function validatePhoto2(file,errorId){
    if (file.size > 100000 || file.fileSize > 100000)
    {
       $("#"+errorId+"Error").html("Allowed file size exceeded. (Max. 100 Kb)")
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
      $('#previewing2').attr('src', e.target.result);
  }

  reader.readAsDataURL(input.files[0]);
 }
}


// headers1

document.getElementById('file_button3').addEventListener('click', openDialog3);
   function openDialog3() {
     document.getElementById('fileupload3').click();
   }

$('#fileupload3').change(function(){
   var src = $(this).val();
   $('#file_button3').removeClass('btn btn-info');
   $('#file_button3').addClass('btn btn-warning');
   if(src && validatePhoto3(this.files[0], 'fileupload3')){
      $("#fileuploadError3").html("");
      readURL3(this);
    } else{
      this.value = null;
    }
});

function validatePhoto3(file,errorId){
    if (file.size > 100000 || file.fileSize > 100000)
    {
       $("#"+errorId+"Error").html("Allowed file size exceeded. (Max. 100 Kb)")
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
      $('#previewing3').attr('src', e.target.result);
  }

  reader.readAsDataURL(input.files[0]);
 }
}


// headers2

document.getElementById('file_button4').addEventListener('click', openDialog4);
   function openDialog4() {
     document.getElementById('fileupload4').click();
   }

$('#fileupload4').change(function(){
   var src = $(this).val();
   $('#file_button4').removeClass('btn btn-info');
   $('#file_button4').addClass('btn btn-warning');
   if(src && validatePhoto4(this.files[0], 'fileupload4')){
      $("#fileuploadError4").html("");
      readURL4(this);
    } else{
      this.value = null;
    }
});

function validatePhoto4(file,errorId){
    if (file.size > 100000 || file.fileSize > 100000)
    {
       $("#"+errorId+"Error").html("Allowed file size exceeded. (Max. 100 Kb)")
       return false;
    }
    if(file.type != 'image/jpeg' && file.type != 'image/jpg' && file.type != 'image/png') {
        $("#"+errorId+"Error").html("Allowed file types are jpeg, jpg and png");
        return false;
    }
    return true;
}

function readURL4(input) {
 if (input.files && input.files[0]) {
  var reader = new FileReader();

  reader.onload = function (e) {
      $('#previewing4').attr('src', e.target.result);
  }

  reader.readAsDataURL(input.files[0]);
 }
}


// headers3

document.getElementById('file_button5').addEventListener('click', openDialog5);
   function openDialog5() {
     document.getElementById('fileupload5').click();
   }

$('#fileupload5').change(function(){
   var src = $(this).val();
   $('#file_button5').removeClass('btn btn-info');
   $('#file_button5').addClass('btn btn-warning');
   if(src && validatePhoto5(this.files[0], 'fileupload5')){
      $("#fileuploadError5").html("");
      readURL5(this);
    } else{
      this.value = null;
    }
});

function validatePhoto5(file,errorId){
    if (file.size > 100000 || file.fileSize > 100000)
    {
       $("#"+errorId+"Error").html("Allowed file size exceeded. (Max. 100 Kb)")
       return false;
    }
    if(file.type != 'image/jpeg' && file.type != 'image/jpg' && file.type != 'image/png') {
        $("#"+errorId+"Error").html("Allowed file types are jpeg, jpg and png");
        return false;
    }
    return true;
}

function readURL5(input) {
 if (input.files && input.files[0]) {
  var reader = new FileReader();

  reader.onload = function (e) {
      $('#previewing5').attr('src', e.target.result);
  }

  reader.readAsDataURL(input.files[0]);
 }
}

// Side Ads

document.getElementById('file_button6').addEventListener('click', openDialog6);
   function openDialog6() {
     document.getElementById('fileupload6').click();
   }

$('#fileupload6').change(function(){
   var src = $(this).val();
   $('#file_button6').removeClass('btn btn-info');
   $('#file_button6').addClass('btn btn-warning');
   readURL6(this);
});


function readURL6(input) {
 if (input.files && input.files[0]) {
  var reader = new FileReader();

  reader.onload = function (e) {
   $('#previewing6').attr('src', e.target.result);
  }

  reader.readAsDataURL(input.files[0]);
 }
}

// Main Ads

document.getElementById('file_button7').addEventListener('click', openDialog7);
   function openDialog7() {
     document.getElementById('fileupload7').click();
   }

$('#fileupload7').change(function(){
   var src = $(this).val();
   $('#file_button7').removeClass('btn btn-info');
   $('#file_button7').addClass('btn btn-warning');
   readURL7(this);   
   // if(src && validatePhoto7(this.files[0], 'fileupload7')){
   //    $("#fileuploadError7").html("");
   //    readURL6(this);
   //  } else{
   //    this.value = null;
   //  }
});

function validatePhoto7(file,errorId){
    if (file.size > 100000 || file.fileSize > 100000)
    {
       $("#"+errorId+"Error").html("Allowed file size exceeded. (Max. 100 Kb)")
       return false;
    }
    if(file.type != 'image/jpeg' && file.type != 'image/jpg' && file.type != 'image/png') {
        $("#"+errorId+"Error").html("Allowed file types are jpeg, jpg and png");
        return false;
    }
    return true;
}

function readURL7(input) {
 if (input.files && input.files[0]) {
  var reader = new FileReader();

  reader.onload = function (e) {
      $('#previewing7').attr('src', e.target.result);
  }

  reader.readAsDataURL(input.files[0]);
 }
}
</script>
<style type="text/css">
.box{
   border: 2px solid #000;
   padding: 20px;
   text-align: center;
}
</style>