<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Category</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Category</h3>
   </div>

   <?php if (!empty($edit_category)) { ?>
      <form enctype="multipart/form-data" method="post" id="category-slider" action="<?php echo site_url('labor_controller/update_categroy/'.$edit_category->id) ?>" class="form-horizontal" data-parsley-validate > 
         <div class="panel-body">
             <div class="form-group">
               <div class="col-md-8">
                  <div class="col-md-3">
                     <input type="text" required="" placeholder="Categroy" value="<?php echo $edit_category->name ?>" id="category" class="form-control" name="category" >
                  </div>
                  <div class="col-md-2">
                     <button type="submit" class="btn btn-primary">Update</button>
                  </div>
               </div>
             </div>

         </div>
      </form>
   <?php }else{ ?>
      <form enctype="multipart/form-data" method="post" id="category-slider" action="<?php echo site_url('labor_controller/insert_categroy') ?>" class="form-horizontal" data-parsley-validate > 
         <div class="panel-body">

             <div class="form-group">
               <div class="col-md-8">
                  <div class="col-md-3">
                     <input type="text" id="category" placeholder="Category"  class="form-control" name="category" >
                  </div>
                  <div class="col-md-2">
                     <button type="submit" class="btn btn-primary" >Submit</button>
                  </div>
               </div>
             </div>

         </div>
      </form>
   <?php } ?>
   

   <div class="panel-body">
      <table class="table table-bordered">
         <thead>
            <tr>
               <th>#</th>
               <th>Labor Category</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
         <?php 
            $i=1;
               foreach ($category as $key => $val) { ?>
               <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo $val->name ?></td>
                  <td>
                     <a href="<?= site_url('labor_controller/edit_category/'.$val->id) ?>"  class="btn btn-info">Edit</a>
                     <a onclick="return confirm('Are you sure do you want delete ?')" href="<?= site_url('labor_controller/delete_category/'.$val->id) ?>"  class="btn btn-danger">Delete</a>
                  </td>
               </tr>
            <?php } ?>
         </tbody>
      </table>
   </div>
</div>




