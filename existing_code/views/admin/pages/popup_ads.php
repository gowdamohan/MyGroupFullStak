<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Group</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Group</h3>
   </div>

   <form enctype="multipart/form-data" method="post" action="<?php echo site_url('franchise/upload_side_ads') ?>" id="header-slider"  class="form-horizontal"> 
      <div class="panel-body">

     <div class="col-md-4">
         <h3>Side / Popup Ads</h3>
         <div class="col-md-12">
            <div class="form-group box" style="margin:2rem">
               <div class="col-sm-12"> 

                  <?php 
                  if (!empty($popupads->side_ads)) { ?>
                     <img  id="previewing6" width="10%" height="10%" name="photograph"  src="<?php echo base_url().$popupads->side_ads ?>" />
                     <input type="hidden" name="side_ads" value="<?php echo $popupads->side_ads ?>" >
                  <?php }else{ ?>
                     <img id="previewing6" style="width: 80%; height:40px;" name="photograph6"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
                  <?php } ?>

                  <?php 
                  if (!empty($popupads->side_ads_url)) { ?>
                     <input type="text" placeholder="URL" style="margin-top: 1rem;" name="side_ads_url" value="<?php echo $popupads->side_ads_url ?>" class="form-control">
                  <?php }else{ ?>
                     <input type="text" placeholder="URL" style="margin-top: 1rem;" name="side_ads_url" class="form-control">
                  <?php } ?>
                  <input class="form-control" id="fileupload6" name="side_ads" type="file">
                  <button type="button" style="margin-top: 1rem;" id="file_button6" class="btn btn-info">Change-Image</button>
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

   function validatePhoto6(file,errorId){
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

   function readURL6(input) {
    if (input.files && input.files[0]) {
     var reader = new FileReader();

     reader.onload = function (e) {
         $('#previewing6').attr('src', e.target.result);
     }

     reader.readAsDataURL(input.files[0]);
    }
   }
</script>