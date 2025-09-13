<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Group</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Group</h3>
      <ul class="panel-controls">
         <li>
            <input type="button" class="btn btn-primary" onclick="myapps('My Apps')" id="my_apps" value="My Apps">
            <input type="button" class="btn btn-info" id="my_company" onclick="myapps('My Company')" value="My Company">
            <input type="button" class="btn btn-warning" id="my_onine" onclick="myapps('My Onine Apps')" value="My Onine Apps">
            <input type="button" class="btn btn-danger" id="my_offline" onclick="myapps('My Offline Apps')" value="My Offline Apps">
         </li>
      </ul>
   </div>

<script type="text/javascript">
   function myapps(value) {
      $('#appsName').val(value);
   }
</script>
   <form enctype="multipart/form-data" method="post" id="header-slider" action="<?php echo site_url('admin_controller/create_associates') ?>" class="form-horizontal" data-parsley-validate > 
      <div class="panel-body">

          <div class="form-group">
            <div class="col-md-8 col-md-offset-2">
               <div class="col-md-3">
                  <input type="text" readonly="" required="" placeholder="Apps Name" id="appsName" class="form-control" name="apps_name" >
               </div>

               <div class="col-md-7">
                  <input type="text" placeholder="Group Name" class="form-control" name="group_name" >
               </div>
               <div class="col-md-2">
                  <button type="submit" class="btn btn-primary" >Create New</button>
               </div>
            </div>
          </div>

      </div>
   </form>

   <div class="panel-body">
      <?php 
      foreach ($create as $key => $val) { ?>
         <form enctype="multipart/form-data" method="post" style="width:95%; margin: auto;"  id="create_details" class="form-horizontal" data-parsley-validate > 
            <div class="form-group">
            <table class="table table-bordered">
               <thead>
                  <tr>
                     <th>Icon</th>
                     <th>Logo</th>
                     <th>Name Image</th>
                     <th>Color</th>
                     <th>Banner</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody style="text-align: center;">
                  <tr>
                     <h3><?php echo $val->name ?></h3>
                     <td width="10%">
                        <?php 
                        if (!empty($val->icon)) { ?>
                           <img id="previewing1_<?php echo $val->id ?>" style="height: 30px;" name="photograph"  src="<?php echo base_url().$val->icon ?>" />
                           <input type="hidden" id="icon_edit_<?php echo $val->id ?>" name="icon_edit" value="<?php echo $val->icon ?>" >
                        <?php }else{ ?>
                           <img id="previewing1_<?php echo $val->id ?>"style="height: 30px;" name="photograph"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
                        <?php } ?>
                         <br>
                        <input class="form-control" id="fileupload1_<?php echo $val->id ?>" onchange="icon_change('<?php echo $val->id ?>', this)" name="icon_photo" type="file" style="display: none;" accept="image/*"/>
                        <button type="button" style="margin-left: 2rem;" id="file_button1_<?php echo $val->id ?>" onclick="openDialog('<?php echo $val->id ?>')" class="btn btn-info">Change</button>

                     </td>
                     <td width="10%">

                        <?php 
                        if (!empty($val->logo)) { ?>
                           <img  id="previewing2_<?php echo $val->id ?>" style="height: 30px;"  name="photograph"  src="<?php echo base_url().$val->logo ?>" />
                           <input type="hidden" id="logo_edit_<?php echo $val->id ?>" value="<?php echo $val->logo ?>" >
                        <?php }else{ ?>
                           <img id="previewing2_<?php echo $val->id ?>" style="height: 30px;"  name="photograph1"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
                        <?php } ?>
                        <br>
                        <input class="form-control" id="fileupload2_<?php echo $val->id ?>"  onchange="logo_change('<?php echo $val->id ?>', this)" name="logo_photo" type="file" style="display: none;" accept="image/*"/>
                        <button type="button" style="margin-left: 2rem;" id="file_button2_<?php echo $val->id ?>" onclick="openDialog1('<?php echo $val->id ?>')" class="btn btn-info">Change</button>
                     </td>
                     <td width="10%">

                        <?php 
                        if (!empty($val->name_image)) { ?>
                           <img  id="previewing3_<?php echo $val->id ?>" style="height: 30px;" name="photograph2"  src="<?php echo base_url().$val->name_image ?>" />
                           <input type="hidden" id="name_photo_edit_<?php echo $val->id ?>" value="<?php echo $val->name_image ?>" >
                        <?php }else{ ?>
                          <img id="previewing3_<?php echo $val->id ?>" style="height: 30px;" name="photograph2"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
                        <?php } ?>
                        <br>
                        <input class="form-control" id="fileupload3_<?php echo $val->id ?>" name="name_photo"  onchange="name_change('<?php echo $val->id ?>', this)" type="file" style="display: none;" accept="image/*"/>
                        <button type="button" style="margin-left: 2rem;" id="file_button3_<?php echo $val->id ?>"  onclick="openDialog2('<?php echo $val->id ?>')" class="btn btn-info">Change</button>
                     </td>
                     <td width="10%">
                        <?php 
                        $bg  ='#ff0000';
                        if (!empty($val->background_color)) {
                           $bg = $val->background_color;
                        } ?>
                       <input type="color" style="width: 35%; height: 39px;" id="background_color_<?php echo $val->id ?>" name="background_color" value="<?php echo $bg ?>"> 

                     </td>
                     <td width="10%">

                        <?php 
                        if (!empty($val->banner)) { ?>
                           <img  id="previewing4_<?php echo $val->id ?>" style="height: 30px;" name="photograph3"  src="<?php echo base_url().$val->banner ?>" />
                           <input type="hidden" id="banner_edit_<?php echo $val->id ?>" value="<?php echo $val->banner ?>" >
                        <?php }else{ ?>
                          <img id="previewing4_<?php echo $val->id ?>" style="height: 30px;" name="photograph3"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
                        <?php } ?>
                        <br>
                        <input class="form-control" id="fileupload4_<?php echo $val->id ?>" name="banner" onchange="banner_image('<?php echo $val->id ?>', this)" type="file" style="display: none;" accept="image/*"/>
                        <button type="button" style="margin-left: 2rem;" id="file_button4_<?php echo $val->id ?>" onclick="openDialog3('<?php echo $val->id ?>')" class="btn btn-info">Change</button>
                     </td>
                     <td width="10%">
                        <a href="javascript:void(0)" onclick="update_create(<?php echo $val->id ?>)" class="btn btn-success">Update</a>
                        <!-- <a onclick="return confirm('Are you sure do you want delete ?')" href="<?//= site_url('admin_controller/delete_group_create/'.$val->id) ?>"  class="btn btn-danger">Delete</a> -->
                     </td>
                   </tr>
                   <tr>
                     <th colspan="5">
                        <?php 
                        $url  ='';
                        if (!empty($val->url)) {
                           $url = $val->url;
                        } ?>
                       <input type="text" class="form-control" id="url_<?php echo $val->id ?>" name="url" value="<?php echo $url ?>">
                     </th>
                   </tr>
               </tbody>
             </table>
            </div>
         </form>
      <?php } ?>
   </div>
</div>


<script type="text/javascript">

// Icon
function openDialog(create_id) {
  document.getElementById('fileupload1_'+create_id).click();
}

function icon_change(create_id, val) { 
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
   if (file.size > 100000 || file.fileSize > 100000){
      $("#"+errorId+"Error").html("Allowed file size exceeded. (Max. 100 Kb)")
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


// Logo
function openDialog1(create_id) {
  document.getElementById('fileupload2_'+create_id).click();
}

function logo_change(create_id, val) { 
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
   if (file.size > 100000 || file.fileSize > 100000){
      $("#"+errorId+"Error").html("Allowed file size exceeded. (Max. 100 Kb)")
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


// Name Image
function openDialog2(create_id) {
  document.getElementById('fileupload3_'+create_id).click();
}

function name_change(create_id, val) { 
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
   if (file.size > 100000 || file.fileSize > 100000){
      $("#"+errorId+"Error").html("Allowed file size exceeded. (Max. 100 Kb)")
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

// Banner Image
function openDialog3(create_id) {
  document.getElementById('fileupload4_'+create_id).click();
}

function banner_image(create_id, val) { 
   var src = val.value;
   $('#file_button4_'+create_id).removeClass('btn btn-info');
   $('#file_button4_'+create_id).addClass('btn btn-warning');
   if(src && validatePhoto3(val.files[0], 'fileupload')){
      $("#fileuploadError").html("");
      readURL3(val, create_id);
   } else{
      val.value = null;
   }
}

function validatePhoto3(file,errorId){
   if (file.size > 100000 || file.fileSize > 100000){
      $("#"+errorId+"Error").html("Allowed file size exceeded. (Max. 100 Kb)")
      return false;
   }
   if(file.type != 'image/jpeg' && file.type != 'image/jpg' && file.type != 'image/png') {
      $("#"+errorId+"Error").html("Allowed file types are jpeg, jpg and png");
      return false;
   }
   return true;
}

function readURL3(input, create_id) {
   if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
      $('#previewing4_'+create_id).attr('src', e.target.result);
   }
   reader.readAsDataURL(input.files[0]);
 }
}
        
   function show_group_modal() {
      $('#group_popupmodel').modal('show');
   }

   function update_create(group_id) {

      var icon_photo = $('#fileupload1_'+group_id).prop('files')[0];
      var logo_photo = $('#fileupload2_'+group_id).prop('files')[0];
      var name_photo = $('#fileupload3_'+group_id).prop('files')[0];
      var banner = $('#fileupload4_'+group_id).prop('files')[0];

      var background_color = $('#background_color_'+group_id).val();
      var url = $('#url_'+group_id).val();

      var icon_edit = $('#icon_edit_'+group_id).val();
      var logo_edit = $('#logo_edit_'+group_id).val();
      var name_photo_edit = $('#name_photo_edit_'+group_id).val();
      var banner_edit = $('#banner_edit_'+group_id).val();

      var form_data = new FormData();                  
      form_data.append('icon_photo', icon_photo);
      form_data.append('logo_photo', logo_photo);
      form_data.append('name_photo', name_photo);
      form_data.append('banner', banner);
      form_data.append('background_color', background_color);
      form_data.append('group_id', group_id);
      form_data.append('url', url);
      form_data.append('icon_edit', icon_edit);
      form_data.append('logo_edit', logo_edit);
      form_data.append('name_photo_edit', name_photo_edit);
      form_data.append('banner_edit', banner_edit);
      $.ajax({
        url: '<?php echo site_url('admin_controller/upload_group_create');?>',
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
