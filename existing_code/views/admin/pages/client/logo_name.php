<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Logo and Name</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Logo and Name</h3>
   </div>

   <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/upload_logo_name') ?>" id="header-slider" action="#" class="form-horizontal" data-parsley-validate > 
   <div class="panel-body">
      <div class="col-md-6 col-md-offset-2">

         <?php 
         $country = '';
         $district = '';
         $state = '';
         switch ($unions_details->type) {
            case '1':
              $name = 'International';
               $country = '';
               $district = '';
               $state = '';
               break;
            case '2':
              $name = 'National';
               $country = $unions_details->country_name;
               $district = '';
               $state = '';
               break;
            case '3':
              $name = 'Regional';
               $country = $unions_details->country_name;
               $district = '';
               $state = $unions_details->state_name;
               break;
            case '4':
               $name = 'Local';
               $country = $unions_details->country_name;
               $district = $unions_details->district_name;
               $state = $unions_details->state_name;
               break;
            default:
               $name = '';
               $country = '';
               $district = '';
               $state = '';
               break;
         } ?>
            <div class="form-group">
               <label class="control-label col-sm-4" style="padding: 0;" for="client_name">Location Type</label>
               <div class="col-sm-8">
                  <h3><?php echo $name ?></h3>
               </div>
            </div>

            <?php if (!empty($country)) { ?>
               <div class="form-group">
                  <label class="control-label col-sm-4" style="padding: 0;" for="client_name">Country</label>
                  <div class="col-sm-8">
                     <h3><?php echo $country ?></h3>
                  </div>
               </div>
            <?php } ?>
            
            <?php if (!empty($state)) { ?>
            <div class="form-group">
               <label class="control-label col-sm-4" style="padding: 0;" for="client_name">State</label>
               <div class="col-sm-8">
                  <h3><?php echo $state ?></h3>
               </div>
            </div>
            <?php } ?>
            <?php if (!empty($district)) { ?>
            <div class="form-group">
               <label class="control-label col-sm-4" style="padding: 0;" for="client_name">District</label>
               <div class="col-sm-8">
                  <h3><?php echo $district ?></h3>
               </div>
            </div>
            <?php } ?>

         <div class="form-group">
            <label class="control-label col-sm-4" style="padding: 0;" for="client_name">Union Type</label>
            <div class="col-sm-8">
               <h3><?php echo strtoupper($unions_details->union_type) ?></h3>
            </div>
         </div>

         <div class="form-group">
            <label class="control-label col-sm-4" style="padding: 0;" for="client_name">Union Category</label>
            <div class="col-sm-8">
               <h3><?php echo $unions_details->cat_name ?></h3>
            </div>
         </div>

         <div class="form-group">
            <label class="control-label col-sm-4" for="photograph">Logo</label>
            <div class="col-sm-8"> 
               <?php 
               if (!empty($unions_details->client_logo)) { ?>
                  <img  id="previewing1" width="10%" height="10%" name="photograph"  src="<?php echo $this->filemanager->getFilePath($unions_details->client_logo) ?>" />
                  <input type="hidden" name="logo_edit" value="<?php echo $unions_details->client_logo ?>" >
               <?php }else{ ?>
                  <img id="previewing1" width="10%" height="10%" name="photograph1"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
               <?php } ?>
               <input class="form-control" id="fileupload1" name="logo_photo" type="file" style="display: none;" accept="image/*"/>
               <button type="button" style="margin-left: 2rem;" id="file_button1" class="btn btn-info">Change logo</button>
            </div>
         </div>

         <div class="form-group">
            <label class="control-label col-sm-4" for="client_name">Name of the Organization</label>
            <div class="col-sm-8"> 

               <?php 
               if (!empty($unions_details->name_of_the_organization)) { ?>
                  <input type="text" name="client_name" class="form-control" value="<?php echo $unions_details->name_of_the_organization ?>" >
               <?php }else{ ?>
                  <input type="text" name="client_name" class="form-control" >
               <?php } ?>
            </div>
         </div>

         <div class="form-group">
            <label class="control-label col-sm-4" for="regional_lang_name">Name of the Organization <br> ( in Regional Language)</label>
            <div class="col-sm-8">
               <?php 
               if (!empty($unions_details->regional_lang_name)) { ?>
                  <input type="text" name="regional_lang_name" class="form-control" value="<?php echo $unions_details->regional_lang_name ?>" >
               <?php }else{ ?>
                  <input type="text" name="regional_lang_name" class="form-control" >
               <?php } ?>
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

// logo image

document.getElementById('file_button1').addEventListener('click', openDialog1);

   function openDialog1() {
     document.getElementById('fileupload1').click();
   }

$('#fileupload1').change(function(){
   var src = $(this).val();
   $('#file_button1').removeClass('btn btn-info');
   $('#file_button1').addClass('btn btn-warning');
   readURL1(this);
   // if(src && validatePhoto1(this.files[0], 'fileupload1')){
   //    $("#fileuploadError1").html("");
   //    readURL1(this);
   //  } else{
   //    this.value = null;
   //  }
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
</script>
<style type="text/css">
.box{
   border: 2px solid #000;
   padding: 20px;
   text-align: center;
}
</style>