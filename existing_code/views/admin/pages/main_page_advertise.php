<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Advertise</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Advertise</h3>
   </div>
   <div class="panel-body">
         <div class="form-group">
            <table class="table table-bordered">
               <thead>
                  <tr>
                     <th>Ads-1</th>
                     <th>Ads-2</th>
                     <th>Ads-3</th>
                  </tr>
               </thead>
               <tbody style="text-align: center;">
                  <tr>
                     <td width="10%">
                        <?php 
                        if (!empty($main_ads->ads1)) { ?>
                           <img id="previewing1" style="height: 30px;" name="photograph"  src="<?php echo base_url().$main_ads->ads1 ?>" />
                           <input type="hidden" id="ads1_edit" name="ads1_edit" value="<?php echo $main_ads->ads1 ?>" >
                        <?php }else{ ?>
                           <img id="previewing1"style="height: 30px;" name="photograph"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
                        <?php } ?>

                        <?php 
                           $ads1_url = '';
                           if (!empty($main_ads->ads1_url)) { 
                              $ads1_url = $main_ads->ads1_url;
                           } 
                        ?>

                        <input type="text" placeholder="URL" style="margin-top: 1rem;" value="<?php echo  $ads1_url ?>" id="header_ads_url_1" class="form-control">

                        <input class="form-control" id="fileupload1" onchange="ads_change(this)" name="icon_photo" type="file" style="display: none;" accept="image/*"/>

                        <button type="button" style="margin-left: 2rem;margin-top: 1rem;" id="file_button1" onclick="openDialog()" class="btn btn-info">Change-Image</button>

                        <button type="button" style="margin-top: 1rem;" onclick="update_advertise()" class="btn btn-success">Update</button>
                     </td>
                     <td width="10%">
                        <?php 
                        if (!empty($main_ads->ads2)) { ?>
                           <img id="previewing2" style="height: 30px;" name="photograph"  src="<?php echo base_url().$main_ads->ads2 ?>" />
                           <input type="hidden" id="ads2_edit" name="ads2_edit" value="<?php echo $main_ads->ads2 ?>" >
                        <?php }else{ ?>
                           <img id="previewing2"style="height: 30px;" name="photograph"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
                        <?php } ?>

                        <?php 
                           $ads2_url = '';
                           if (!empty($main_ads->ads2_url)) { 
                              $ads2_url = $main_ads->ads2_url;
                           } 
                        ?>

                        <input type="text" placeholder="URL" style="margin-top: 1rem;" value="<?php echo  $ads2_url ?>" id="header_ads_url_2" class="form-control">

                        <input class="form-control" id="fileupload2" onchange="ads_change1(this)" name="icon_photo" type="file" style="display: none;" accept="image/*"/>
                        
                        <button type="button" style="margin-left: 2rem;margin-top: 1rem;" id="file_button2" onclick="openDialog1()" class="btn btn-info">Change-Image</button>
                        
                        <button type="button" style="margin-top: 1rem;" onclick="update_advertise()" class="btn btn-success">Update</button>

                     </td>
                     <td width="10%">
                        <?php 
                        if (!empty($main_ads->ads3)) { ?>
                           <img id="previewing3" style="height: 30px;" name="photograph"  src="<?php echo base_url().$main_ads->ads3 ?>" />
                           <input type="hidden" id="ads3_edit" name="ads3_edit" value="<?php echo $main_ads->ads3 ?>" >
                        <?php }else{ ?>
                           <img id="previewing3"style="height: 30px;" name="photograph"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
                        <?php } ?>

                        <?php 
                           $ads3_url = '';
                           if (!empty($main_ads->ads3_url)) { 
                              $ads3_url = $main_ads->ads3_url;
                           } 
                        ?>

                        <input type="text" placeholder="URL" style="margin-top: 1rem;" value="<?php echo  $ads3_url ?>" id="header_ads_url_3" class="form-control">

                        <input class="form-control" id="fileupload3" onchange="ads_change2(this)" name="icon_photo" type="file" style="display: none;" accept="image/*"/>
                        
                        <button type="button" style="margin-left: 2rem;margin-top: 1rem;" id="file_button3" onclick="openDialog2()" class="btn btn-info">Change-Image</button>
                        
                        <button type="button" style="margin-top: 1rem;" onclick="update_advertise()" class="btn btn-success">Update</button>
                     </td>
                   </tr>
               </tbody>
            </table>
         </div>
   </div>
</div>


<script type="text/javascript">
   
   // Adds1
   function openDialog() {
     document.getElementById('fileupload1').click();
   }

   function ads_change(val) { 
      var src = val.value;
      $('#file_button1').removeClass('btn btn-info');
      $('#file_button1').addClass('btn btn-warning');
      if(src && validatePhoto(val.files[0], 'fileupload')){
         $("#fileuploadError").html("");
         readURL(val);
      } else{
         val.value = null;
      }
   }

   function validatePhoto(file,errorId){
      if (file.size > 1000000 || file.fileSize > 1000000){
         $("#"+errorId+"Error").html("Allowed file size exceeded. (Max. 10MB)")
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
         $('#previewing1').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
   }

    // Adds2
   function openDialog1() {
     document.getElementById('fileupload2').click();
   }

   function ads_change1(val) { 
      var src = val.value;
      $('#file_button2').removeClass('btn btn-info');
      $('#file_button2').addClass('btn btn-warning');
      if(src && validatePhoto1(val.files[0], 'fileupload')){
         $("#fileuploadError").html("");
         readURL1(val);
      } else{
         val.value = null;
      }
   }

   function validatePhoto1(file,errorId){
      if (file.size > 1000000 || file.fileSize > 1000000){
         $("#"+errorId+"Error").html("Allowed file size exceeded. (Max. 10MB)")
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
         $('#previewing2').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
   }

    // Adds3
   function openDialog2() {
     document.getElementById('fileupload3').click();
   }

   function ads_change2(val) { 
      var src = val.value;
      $('#file_button3').removeClass('btn btn-info');
      $('#file_button3').addClass('btn btn-warning');
      if(src && validatePhoto2(val.files[0], 'fileupload')){
         $("#fileuploadError").html("");
         readURL2(val);
      } else{
         val.value = null;
      }
   }

   function validatePhoto2(file,errorId){
      if (file.size > 1000000 || file.fileSize > 1000000){
         $("#"+errorId+"Error").html("Allowed file size exceeded. (Max. 10 MB)")
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
         $('#previewing3').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
   }

    function update_advertise() {

      var ads1 = $('#fileupload1').prop('files')[0];
      var ads2 = $('#fileupload2').prop('files')[0];
      var ads3 = $('#fileupload3').prop('files')[0];

      var ads1_url = $('#header_ads_url_1').val();
      var ads2_url = $('#header_ads_url_2').val();
      var ads3_url = $('#header_ads_url_3').val();


      var ads1_edit = $('#ads1_edit').val();
      var ads2_edit = $('#ads2_edit').val();
      var ads3_edit = $('#ads3_edit').val();

      var form_data = new FormData();                  
      form_data.append('ads1', ads1);
      form_data.append('ads2', ads2);
      form_data.append('ads3', ads3);
   
      form_data.append('ads1_url', ads1_url);
      form_data.append('ads2_url', ads2_url);
      form_data.append('ads3_url', ads3_url);
      
      form_data.append('ads1_edit', ads1_edit);
      form_data.append('ads2_edit', ads2_edit);
      form_data.append('ads3_edit', ads3_edit);
      $.ajax({
        url: '<?php echo site_url('franchise/upload_group_main_adertise');?>',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(data){
         console.log(data);
           location.reload();
         }
      });
   }
</script>