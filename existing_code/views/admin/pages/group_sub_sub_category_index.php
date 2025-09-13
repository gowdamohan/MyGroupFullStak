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
         }
      ?>
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
      
      <input type="hidden" name="group_name" id="group_name" value="<?php echo $group_name ?>">
      <input type="hidden" name="category" id="category" value="<?php echo $category ?>">

      <div class="col-md-9">
         <a href="<?php echo site_url('admin_controller/create_group_category/'.$group_name.'/'.$category.'/'.'singleActive') ?>" class="btn btn-primary">Category</a>
         <a href="<?php echo site_url('admin_controller/create_group_category/'.$group_name.'/'.$category.'/'.'subActive') ?>" class="btn btn-primary">Sub Category</a>
         <a href="<?php echo site_url('admin_controller/create_group_category/'.$group_name.'/'.$category.'/'.'subSubActive') ?>" class="btn btn-primary selectedactve">Sub Sub Category</a>

         <form enctype="multipart/form-data" method="post" id="category-form" action="<?php echo site_url('admin_controller/insert_sub_sub_category_data_group_wise/'.$group_name.'/'.$category) ?>" class="form-horizontal" data-parsley-validate > 
            <div class="panel-body">
           
             <div class="form-group">
               <div class="col-md-12">
                  <div class="col-md-3">
                     <select class="form-control" required name="category_id" id="category_id" onchange="get_category_wise_sub_category()" required='' >
                        <option value="">Select Category</option>
                        <?php 
                        $property_types = array();
                        foreach($view_cat as $filter_result){
                            if ( in_array($filter_result->id, $property_types) ) {
                                continue;
                            }
                            $property_types[$filter_result->id] = $filter_result->category;
                        }
                        foreach ($property_types as $catId => $val) { ?>
                           <option value="<?php echo $catId ?>"><?php echo $val ?></option>
                        <?php } ?>
                     </select>
                  </div>

                  <div class="col-md-3">
                     <select class="form-control" required name="sub_category_id" id="sub_category_id">
                        <option value="">Select Sub Category</option>
                     </select>
                  </div>
                  <div class="col-md-3">
                     <input type="text" id="sub_sub_category" required placeholder="Enter Sub Sub Category"  class="form-control" name="sub_sub_category" >
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
                     <th>Sub Category Name</th>
                     <th>Sub Sub Category Name</th>
                     <th>Action</th>
                  </tr>
               </thead>
             
               <tbody>
                  <?php 
                  $i=1;
                     foreach ($view_cat as $key => $val) { ?>
                     <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $val->category ?></td>
                        <td><?php echo $val->sub_category ?></td>
                        <td><?php echo $val->sub_sub_category ?></td>
                        <td></td>
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

   function get_category_wise_sub_category() {

      var group_name = $('#group_name').val();
      var category_id = $('#category_id').val();
      $.ajax({
        url: '<?php echo site_url('admin_controller/get_category_wise_sub_category');?>',
        data: {'group_name':group_name,'category_id':category_id},                         
        type: 'post',
        success: function(data){
            var sub_cat = jQuery.parseJSON(data);
            console.log(sub_cat);
            var output='';
            output+='<option value="">Select Sub Category</option>';
            var len=sub_cat.length;
            for (var i=0; i < sub_cat.length; i++) {
              output+='<option value="'+sub_cat[i].id+'">'+sub_cat[i].sub_category+'</option>'; 
            }
            $('#sub_category_id').html(output);

         }
      });

   }

</script>