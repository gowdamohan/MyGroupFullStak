<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Advertise</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Advertise</h3>
   </div>
   <div class="panel-body">
      <?php foreach ($advertise as $key => $val) { ?>
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
                     <h3><?php echo $val->name ?></h3>
                     <td width="10%">
                        <?php 
                        if (!empty($val->ads1)) { ?>
                           <img id="previewing1_<?php echo $val->id ?>" style="height: 30px;" name="photograph"  src="<?php echo base_url().$val->ads1 ?>" />
                           <input type="hidden" id="ads1_edit_<?php echo $val->id ?>" name="ads1_edit" value="<?php echo $val->ads1 ?>" >
                        <?php }else{ ?>
                           <img id="previewing1_<?php echo $val->id ?>"style="height: 30px;" name="photograph"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
                        <?php } ?>

                        <?php 
                           $ads1_url = '';
                           if (!empty($val->ads1_url)) { 
                              $ads1_url = $val->ads1_url;
                           } 
                        ?>

                        <input type="text" placeholder="URL" style="margin-top: 1rem;" value="<?php echo  $ads1_url ?>" id="header_ads_url_1_<?php echo $val->id ?>" class="form-control">

                        <input class="form-control" id="fileupload1_<?php echo $val->id ?>" onchange="ads_change('<?php echo $val->id ?>', this)" name="icon_photo" type="file" style="display: none;" accept="image/*"/>

                        <button type="button" style="margin-left: 2rem;margin-top: 1rem;" id="file_button1_<?php echo $val->id ?>" onclick="openDialog('<?php echo $val->id ?>')" class="btn btn-info">Change-Image</button>

                        <button type="button" style="margin-top: 1rem;" onclick="update_advertise('<?php echo $val->id ?>')" class="btn btn-success">Update</button>
                     </td>
                     <td width="10%">
                        <?php 
                        if (!empty($val->ads2)) { ?>
                           <img id="previewing2_<?php echo $val->id ?>" style="height: 30px;" name="photograph"  src="<?php echo base_url().$val->ads2 ?>" />
                           <input type="hidden" id="ads2_edit_<?php echo $val->id ?>" name="ads2_edit" value="<?php echo $val->ads2 ?>" >
                        <?php }else{ ?>
                           <img id="previewing2_<?php echo $val->id ?>"style="height: 30px;" name="photograph"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
                        <?php } ?>

                        <?php 
                           $ads2_url = '';
                           if (!empty($val->ads2_url)) { 
                              $ads2_url = $val->ads2_url;
                           } 
                        ?>

                        <input type="text" placeholder="URL" style="margin-top: 1rem;" value="<?php echo  $ads2_url ?>" id="header_ads_url_2_<?php echo $val->id ?>" class="form-control">

                        <input class="form-control" id="fileupload2_<?php echo $val->id ?>" onchange="ads_change1('<?php echo $val->id ?>', this)" name="icon_photo" type="file" style="display: none;" accept="image/*"/>
                        
                        <button type="button" style="margin-left: 2rem;margin-top: 1rem;" id="file_button2_<?php echo $val->id ?>" onclick="openDialog1('<?php echo $val->id ?>')" class="btn btn-info">Change-Image</button>
                        
                        <button type="button" style="margin-top: 1rem;" onclick="update_advertise('<?php echo $val->id ?>')" class="btn btn-success">Update</button>

                     </td>
                     <td width="10%">
                        <?php 
                        if (!empty($val->ads3)) { ?>
                           <img id="previewing3_<?php echo $val->id ?>" style="height: 30px;" name="photograph"  src="<?php echo base_url().$val->ads3 ?>" />
                           <input type="hidden" id="ads3_edit_<?php echo $val->id ?>" name="ads3_edit" value="<?php echo $val->ads3 ?>" >
                        <?php }else{ ?>
                           <img id="previewing3_<?php echo $val->id ?>"style="height: 30px;" name="photograph"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
                        <?php } ?>

                        <?php 
                           $ads3_url = '';
                           if (!empty($val->ads3_url)) { 
                              $ads3_url = $val->ads3_url;
                           } 
                        ?>

                        <input type="text" placeholder="URL" style="margin-top: 1rem;" value="<?php echo  $ads3_url ?>" id="header_ads_url_3_<?php echo $val->id ?>" class="form-control">

                        <input class="form-control" id="fileupload3_<?php echo $val->id ?>" onchange="ads_change2('<?php echo $val->id ?>', this)" name="icon_photo" type="file" style="display: none;" accept="image/*"/>
                        
                        <button type="button" style="margin-left: 2rem;margin-top: 1rem;" id="file_button3_<?php echo $val->id ?>" onclick="openDialog2('<?php echo $val->id ?>')" class="btn btn-info">Change-Image</button>
                        
                        <button type="button" style="margin-top: 1rem;" onclick="update_advertise('<?php echo $val->id ?>')" class="btn btn-success">Update</button>
                     </td>
                   </tr>
               </tbody>
            </table>
         </div>  
      <?php } ?>
       

    
   </div>
</div>


<script type="text/javascript">
   
   // Adds1
   function openDialog(create_id) {
     document.getElementById('fileupload1_'+create_id).click();
   }

   function ads_change(create_id, val) { 
      var src = val.value;
      $('#file_button1_'+create_id).removeClass('btn btn-info');
      $('#file_button1_'+create_id).addClass('btn btn-warning');
      if(src && validatePhoto(val.files[0], 'fileupload')){
         $("#fileuploadError").html("");
         readURL(val, create_id);
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

   function readURL(input, create_id) {
      if (input.files && input.files[0]) {
         var reader = new FileReader();
         reader.onload = function (e) {
         $('#previewing1_'+create_id).attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
   }

    // Adds2
   function openDialog1(create_id) {
     document.getElementById('fileupload2_'+create_id).click();
   }

   function ads_change1(create_id, val) { 
      var src = val.value;
      $('#file_button2_'+create_id).removeClass('btn btn-info');
      $('#file_button2_'+create_id).addClass('btn btn-warning');
      if(src && validatePhoto1(val.files[0], 'fileupload')){
         $("#fileuploadError").html("");
         readURL1(val, create_id);
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

   function readURL1(input, create_id) {
      if (input.files && input.files[0]) {
         var reader = new FileReader();
         reader.onload = function (e) {
         $('#previewing2_'+create_id).attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
   }

    // Adds3
   function openDialog2(create_id) {
     document.getElementById('fileupload3_'+create_id).click();
   }

   function ads_change2(create_id, val) { 
      var src = val.value;
      $('#file_button3_'+create_id).removeClass('btn btn-info');
      $('#file_button3_'+create_id).addClass('btn btn-warning');
      if(src && validatePhoto2(val.files[0], 'fileupload')){
         $("#fileuploadError").html("");
         readURL2(val, create_id);
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

   function readURL2(input, create_id) {
      if (input.files && input.files[0]) {
         var reader = new FileReader();
         reader.onload = function (e) {
         $('#previewing3_'+create_id).attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
   }

    function update_advertise(group_id) {

      var ads1 = $('#fileupload1_'+group_id).prop('files')[0];
      var ads2 = $('#fileupload2_'+group_id).prop('files')[0];
      var ads3 = $('#fileupload3_'+group_id).prop('files')[0];

      var ads1_url = $('#header_ads_url_1_'+group_id).val();
      var ads2_url = $('#header_ads_url_2_'+group_id).val();
      var ads3_url = $('#header_ads_url_3_'+group_id).val();


      var ads1_edit = $('#ads1_edit_'+group_id).val();
      var ads2_edit = $('#ads2_edit_'+group_id).val();
      var ads3_edit = $('#ads3_edit_'+group_id).val();

      var form_data = new FormData();                  
      form_data.append('ads1', ads1);
      form_data.append('ads2', ads2);
      form_data.append('ads3', ads3);
   
      form_data.append('ads1_url', ads1_url);
      form_data.append('ads2_url', ads2_url);
      form_data.append('ads3_url', ads3_url);
      form_data.append('group_id', group_id);
      
      form_data.append('ads1_edit', ads1_edit);
      form_data.append('ads2_edit', ads2_edit);
      form_data.append('ads3_edit', ads3_edit);
      $.ajax({
        url: '<?php echo site_url('admin_controller/upload_group_adertise');?>',
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


   function show_group_modal() {
      $('#group_popupmodel').modal('show');
   }
</script>


<div class="modal fade" id="group_popupmodel" role="dialog" data-backdrop="static">
   <div class="modal-dialog">
     <div class="modal-content" style="margin: auto;margin-top: 8%;border-radius: .75rem;" >
         <div class="modal-header">
            <h4 class="modal-title" id="sessionName"></h4>
            <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true" style="color: #d80403;font-size: 21px;"></i></button>
         </div>
         <div class="modal-body">
            <div class="form-group">
               <label class="col-md-4 control-label">Name</label>
               <div class="col-md-8">
                  <input name="group_name" required="" class="form-control" readonly=""  type="text" id="group_name" />
               </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input style="margin-left: 50%;" type="button"  class="btn btn-primary" id="addNewLesson" value="ADD >>" >
      </div>
  </div>
</div>
