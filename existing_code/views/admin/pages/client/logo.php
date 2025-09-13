<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Group</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Group</h3>
   </div>

   <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/upload_logo') ?>" id="header-slider" action="#" class="form-horizontal" data-parsley-validate > 
   <div class="panel-body">
      <div class="col-md-6 col-md-offset-2">

         <div class="form-group">
            <label class="control-label col-sm-3" for="no_of_days">Logo</label>
            <div class="col-sm-8"> 
               <?php 
               if (!empty($logo->logo)) { ?>
                  <img  id="previewing1" width="10%" height="10%" name="photograph"  src="<?php echo $this->filemanager->getFilePath($logo->logo) ?>" />
                  <input type="hidden" name="logo_edit" value="<?php echo $logo->logo ?>" >
               <?php }else{ ?>
                  <img id="previewing1" width="10%" height="10%" name="photograph1"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
               <?php } ?>
               <input class="form-control" id="fileupload1" name="logo_photo" type="file" style="display: none;" accept="image/*"/>
               <button type="button" style="margin-left: 2rem;" id="file_button1" class="btn btn-info">Change</button>
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