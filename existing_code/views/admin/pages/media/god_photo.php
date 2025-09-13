<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Photo</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Photo</h3>
   </div>
   <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/upload_god_photo') ?>" class="form-horizontal" id="godPhotos_form" data-parsley-validate >
      <div class="col-md-6 col-md-offset-2">
         <br>
         <div class="container">
            <div class="row">
              <div class="col-sm-2 imgUp">
                  <div class="imagePreview"></div>
                     <label class="btn btn-primary">Upload<input type="file" name="god_images[]" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"></label>
              </div>
               <i class="fa fa-plus imgAdd"></i>
            </div>
         </div>
      </div>
      <div class="panel-footer">
         <center>
            <button type="button" onclick="save_god_photo()" id="up-btn" class="btn btn-info" style="width: 20%;margin-top: 1rem;">Save</button>
         </center>
      </div>
   </form>

   <div class="panel-body">
    <h3>Photos</h3>
        <br>
     <div class="container">
        <div class="row">
            <?php foreach ($god_photo as $key => $val) { ?>
                <div class="col-sm-2 imgUp">
                    <?php
                        $img = '';
                        if (!empty($val->image)) { 
                            $imgpath = $this->filemanager->getFilePath($val->image); 
                            $img = '<img class="imagePreview" style="background-image:url('.$imgpath.')" >';
                        }
                    ?>
                    <div class="imagePreview" id="mygod_main_img" style="margin-bottom: 0.5rem;">
                        <?php echo $img ?>
                        <i onclick="deletegodphoto(<?php echo $val->id ?>)" onclick="return confirm('Are you sure you want delete this photo ?')" style="font-size: 18px;color: red;background: #f5f5f5;" class="fa fa-times del"></i>
                    </div>
                </div>

            <?php } ?>
         
        </div>
     </div>

   </div>
</div>

<script>
   function deletegodphoto(id) {
      if (confirm('You want delete this photo. Are you sure?')) {
         window.location.href='<?php echo site_url('client_controller/deletegodphotobyid/') ?>'+id
      }
   }
</script>
<style type="text/css">
 .imagePreview {
    width: 100%;
    height: 180px;
    background-position: center center;
  background:url(http://cliquecities.com/assets/no-image-e3699ae23f866f6cbdf8ba2443ee5c4e.jpg);
  background-color:#fff;
    background-size: cover;
  background-repeat:no-repeat;
    display: inline-block;
  box-shadow:0px -3px 6px 2px rgba(0,0,0,0.2);
}
.btn-primary
{
  display:block;
  border-radius:0px;
  box-shadow:0px 4px 6px 2px rgba(0,0,0,0.2);
  margin-top:-5px;
}
.imgUp
{
  margin-bottom:15px;
}
.del
{
  position:absolute;
  top:0px;
  right:15px;
  width:30px;
  height:30px;
  text-align:center;
  line-height:30px;
  background-color:rgba(255,255,255,0.6);
  cursor:pointer;
}
.imgAdd
{
  width:30px;
  height:30px;
  border-radius:50%;
  background-color:#4bd7ef;
  color:#fff;
  box-shadow:0px 0px 2px 1px rgba(0,0,0,0.2);
  text-align:center;
  line-height:30px;
  margin-top:0px;
  cursor:pointer;
  font-size:15px;
}
</style>

<script type="text/javascript">
   $(".imgAdd").click(function(){
      $(this).closest(".row").find('.imgAdd').before('<div class="col-sm-2 imgUp"><div class="imagePreview"></div><label class="btn btn-primary">Upload<input type="file" class="uploadFile img" name="god_images[]" value="Upload Photo" style="width:0px;height:0px;overflow:hidden;"></label><i class="fa fa-times del"></i></div>');
   });
   $(document).on("click", "i.del" , function() {
      $(this).parent().remove();
   });
   $(function() {
      $(document).on("change",".uploadFile", function(){
         var uploadFile = $(this);
         var files = !!this.files ? this.files : [];
         if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
 
         if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file
 
            reader.onloadend = function(){ // set image data as background of div
                //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
               uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url("+this.result+")");
            }
         }
      });
   });

   function save_god_photo() {
      $('#godPhotos_form').submit();
      // var file_data = $('#file').prop('files')[0];
   }
</script>

<style type="text/css">
.loaderclass {
  border: 8px solid #eee;
  border-top: 8px solid #7193be;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  position: fixed;
  z-index: 1;
  animation: spin 2s linear infinite;
  margin-top: 60%;
  margin-left: 40%;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>