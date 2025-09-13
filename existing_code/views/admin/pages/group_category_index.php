<?php 
  $users = $this->ion_auth->user()->row();
?>

<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Create Category</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title"><?php echo strtoupper($group_name) ?></h3>
   </div>
   <?php 
   $group_list = [];
   $a = 0;
   $b = 0;
   $c = 0;
   switch ($group_name) {
      case 'Mymedia':
         $group_list = ['tv','radio','news','magazine','webnews','youtube','mygod'];
         $a = 0;
         $b = 0;
         $c = 0;
         break;
      // case 'Mydiary':
      //    $group_list = ['myradio','myaudio','mybooks','mypage','mytok','mygames'];
      //    break;
      case 'Myjoy':
         $group_list = ['myvideo','myaudio','mybooks','mypage','mytok','mygames'];
         $a = 0;
         $b = 0;
         $c = 0;
         break;
      case 'Myshop':
         $group_list = ['shop','local','resale','brands','wholesale','echoshop'];
         break;
      case 'Myfriend':
         $group_list = ['myfriend','mymarry','myjobs','health','travel','booking'];
         $a = 0;
         $b = 0;
         $c = 0;
         break;
      case 'Myunions':
         // $group_list = ['news','unions','federation','ids','notice','me'];
         $group_list = ['unions','federation','ids'];
         $a = 0;
         $b = 0;
         $c = 0;
         break;
      case 'Mybiz':
         $group_list = ['production','finance','advertise','franchises','trading','services'];
         $a = 0;
         $b = 0;
         $c = 0;
         break;
      case 'Mytv':
         $group_list = ['reporter','gallery','public'];
         $a = 0;
         $b = 0;
         $c = 0;
         break;
      case 'Myneedy':
         $group_list = ['doorstep','centers','manpower','online','myhelp'  ];
         $a = 0;
         $b = 0;
         $c = 0;
         break;

     
   } ?>
   <div class="panel-body">
      <div class="col-md-3">
         <div class="panel-body list-group list-group-contacts">
            <?php $i=1; foreach ($group_list as $key => $val) { ?>
               <?php 
                  $active = '';
                  if ($category == $val) {
                     $active = 'active';
                  } 
               ?>
               <a href="<?php echo site_url('admin_controller/create_group_category/'.$group_name.'/'.$val.'/'.'singleActive') ?>" class="list-group-item <?php echo $active ?>">                               
                  <span class="contacts-title"><?php echo $i.'. '.strtoupper($val) ?></span>
               </a>
            <?php $i++; } ?>
         </div>
      </div>
      <?php 
      if ($category == 'shop') {
            $a = 1;
            $b = 1;
            $c = 1;
         }elseif($category == 'local'){
            $a = 0;
            $b = 0;
            $c = 0;
         }else if($category == 'resale'){
            $a = 1;
            $b = 1;
            $c = 0;
         }elseif ($category == 'brands') {
            $a = 0;
            $b = 0;
            $c = 0;
         }elseif ($category == 'wholesale') {
            $a = 0;
            $b = 0;
            $c = 0;
         }elseif ($category == 'echoshop') {
            $a = 1;
            $b = 1;
            $c = 0;
         }elseif($category == 'myfriend'){
            $a = 1;
            $b = 1;
            $c = 1;
         }else if($category == 'mymarry'){
            $a = 1;
            $b = 1;
            $c = 0;
         }elseif ($category == 'myjobs') {
            $a = 1;
            $b = 1;
            $c = 0;
         }elseif ($category == 'health') {
            $a = 1;
            $b = 1;
            $c = 0;
         }elseif ($category == 'travel') {
            $a = 1;
            $b = 1;
            $c = 0;
         }elseif($category == 'booking'){
            $a = 0;
            $b = 0;
            $c = 0;
         }elseif($category == 'echoshop'){
            $a = 1;
            $b = 1;
            $c = 0;
         }
      ?>
      <input type="hidden" name="group_name" id="group_name" value="<?php echo $group_name ?>">
      <input type="hidden" name="category" id="category" value="<?php echo $category ?>">

      <div class="col-md-9">
       
         <a <?php if($a == 0) echo 'style="display:none"'?> href="<?php echo site_url('admin_controller/create_group_category/'.$group_name.'/'.$category.'/'.'singleActive') ?>" class="btn btn-primary <?php if($buttonActive == 'singleActive') echo 'selectedactve' ?> ">Category</a>
         <a <?php if($b == 0) echo 'style="display:none"' ?> href="<?php echo site_url('admin_controller/create_group_category/'.$group_name.'/'.$category.'/'.'subActive') ?>" class="btn btn-primary  <?php if($buttonActive == 'subActive') echo 'selectedactve' ?>">Sub Category</a>
         <a <?php if($c == 0) echo 'style="display:none"' ?> href="<?php echo site_url('admin_controller/create_group_category/'.$group_name.'/'.$category.'/'.'subSubActive') ?>" class="btn btn-primary <?php if($buttonActive == 'subSubActive') echo 'selectedactve' ?>">Sub Sub Category</a>

         <form enctype="multipart/form-data" method="post" id="category-form" action="<?php echo site_url('admin_controller/insert_category_data_group_wise/'.$group_name.'/'.$category.'/'.$buttonActive) ?>" class="form-horizontal" data-parsley-validate > 
            <div class="panel-body">
              
                <div class="form-group">
                  <div class="col-md-8">
                     <div class="col-md-5">
                        <input type="text" id="category" required placeholder="Enter Category"  class="form-control" name="category" >
                     </div>
                     <div class="col-md-2">
                        <button type="submit" class="btn btn-primary" >Submit</button>
                     </div>
                  </div>
                </div>

            </div>
         </form>
            <table class="table table-bordered">
               <thead>
                  <tr>
                     <th>#</th>
                     <th>Category Name</th>
                     <th>Image</th>
                  </tr>
               </thead>
             
               <tbody>
                  <?php 
                  $i=1;
                     foreach ($view_cat as $key => $val) { ?>
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
                              <br>
                              <input class="form-control" id="fileupload1_<?php echo $val->id ?>" onchange="cat_change('<?php echo $val->id ?>', this)" name="icon_photo" type="file" style="display: none;" accept="image/*"/>

                              <button type="button" style="margin-top: 1rem;" id="file_button1_<?php echo $val->id ?>" onclick="openDialog('<?php echo $val->id ?>')" class="btn btn-info">Change-Image</button>

                              <button type="button" style="margin-top: 1rem;" onclick="update_category('<?php echo $val->id ?>')" class="btn btn-success">Update</button>
                        </td>
                     </tr>
                  <?php } ?>
               </tbody>
            </table>
      </div>
   </div>
</div>

<style type="text/css">
   .list-group-contacts .list-group-item.active{
      background: #20b120;
      color: #fff;
   }
   .selectedactve{
      background: #20b120;
      color: #fff;
      border-color: #20b120;
   }
   .selectedactve:hover{
      background: #20b120;
      color: #fff;
      border-color: #20b120;
   }
</style>

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
      var group_name = $('#group_name').val();
      var category = $('#category').val();

      var category_edit = $('#category_edit_'+cat_id).val();
      var cat_img = $('#fileupload1_'+cat_id).prop('files')[0];   
      var form_data = new FormData();                  
      form_data.append('category_img', cat_img);
      form_data.append('category', category);
      form_data.append('group_name', group_name);
      form_data.append('category_edit', category_edit);
      form_data.append('cat_id', cat_id);
      $.ajax({
        url: '<?php echo site_url('admin_controller/update_group_category_img');?>',
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