<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Category</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title"><?php echo strtoupper($needyType) ?></h3>
   </div>

      <form enctype="multipart/form-data" method="post" id="category-slider" action="<?php echo site_url('admin_controller/insert_needy_categroy') ?>" class="form-horizontal" data-parsley-validate > 
         <div class="panel-body">
            <input type="hidden" name="group_id" id="group_id" value="<?php echo $group_id ?>" >
            <input type="hidden" name="needy_type" id="needy_type" value="<?php echo $needyType ?>" >
             <div class="form-group">
               <div class="col-md-8">
                  <div class="col-md-3">
                     <input type="text" id="needy_category" placeholder="Enter Category"  class="form-control" name="needy_category" >
                  </div>
                  <div class="col-md-2">
                     <button type="submit" class="btn btn-primary" >Submit</button>
                  </div>
               </div>
             </div>

         </div>
      </form>

   <div class="panel-body">
      <table class="table table-bordered">
         <thead>
            <tr>
               <th>#</th>
               <th>Category Name</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
         <?php 
            $i=1;
               foreach ($category as $key => $val) { ?>
               <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo $val->category ?></td>
                  <td>

                     <?php 
                        if (!empty($val->category_img)) { ?>
                           <img id="previewing1_<?php echo $val->id ?>" style="height: 30px;" name="photograph"  src="<?php echo base_url().$val->category_img ?>" />
                           <input type="hidden" id="category_edit_<?php echo $val->id ?>" name="category_edit" value="<?php echo $val->category_img ?>" >
                        <?php }else{ ?>
                           <img id="previewing1_<?php echo $val->id ?>"style="height: 30px;" name="photograph"  src="<?php echo base_url().'assets/back_end/img/icon/amazon.png' ?>" />
                        <?php } ?>

                        <input class="form-control" id="fileupload1_<?php echo $val->id ?>" onchange="cat_change('<?php echo $val->id ?>', this)" name="icon_photo" type="file" style="display: none;" accept="image/*"/>

                        <button type="button" style="margin-left: 2rem;margin-top: 1rem;" id="file_button1_<?php echo $val->id ?>" onclick="openDialog('<?php echo $val->id ?>')" class="btn btn-info">Change-Image</button>

                        <button type="button" style="margin-top: 1rem;" onclick="update_category('<?php echo $val->id ?>')" class="btn btn-success">Update</button>

                  </td>
               </tr>
            <?php } ?>
         </tbody>
      </table>
   </div>
</div>




<script type="text/javascript">
   function openDialog(cat_id) {
     document.getElementById('fileupload1_'+cat_id).click();
   }

   function cat_change(cat_id, val) { 
      var src = val.value;
      $('#file_button1_'+cat_id).removeClass('btn btn-info');
      $('#file_button1_'+cat_id).addClass('btn btn-warning');
      if(src && validatePhoto(val.files[0], 'fileupload')){
         $("#fileuploadError").html("");
         readURL(val, cat_id);
      } else{
         alert('Upload within 100kb file size')
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

   function readURL(input, cat_id) {
      if (input.files && input.files[0]) {
         var reader = new FileReader();
         reader.onload = function (e) {
         $('#previewing1_'+cat_id).attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
   }

   function update_category(cat_id) {
      var group_id = $('#group_id').val();
      var needy_type = $('#needy_type').val();
      var category_edit = $('#category_edit_'+cat_id).val();
      var cat_img = $('#fileupload1_'+cat_id).prop('files')[0];   
      var form_data = new FormData();                  
      form_data.append('category_img', cat_img);
      form_data.append('group_id', group_id);
      form_data.append('needy_type', needy_type);
      form_data.append('cat_id', cat_id);
      form_data.append('category_edit', category_edit);
      $.ajax({
        url: '<?php echo site_url('admin_controller/update_needy_category_img');?>',
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