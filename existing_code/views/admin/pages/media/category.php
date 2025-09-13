<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Category</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title"><?php echo strtoupper($mediaType) ?></h3>
   </div>

      <form enctype="multipart/form-data" method="post" id="category-slider" action="<?php echo site_url('admin_controller/insert_media_categroy') ?>" class="form-horizontal" data-parsley-validate > 
         <div class="panel-body">
            <input type="hidden" name="group_id" value="<?php echo $group_id ?>" >
            <input type="hidden" name="media_type" value="<?php echo $mediaType ?>" >
             <div class="form-group">
               <div class="col-md-8">
                  <div class="col-md-3">
                     <input type="text" id="media_category" placeholder="Enter Category"  class="form-control" name="media_category" >
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
                     <!-- <a href="<?= site_url('admin_controller/edit_category/'.$val->id) ?>"  class="btn btn-info">Edit</a> -->
                     <a onclick="return confirm('Are you sure do you want delete ?')" href="<?= site_url('admin_controller/delete_media_category/'.$val->id.'/'.$group_id.'/'.$mediaType) ?>"  class="btn btn-danger">Delete</a>
                  </td>
               </tr>
            <?php } ?>
         </tbody>
      </table>
   </div>
</div>




