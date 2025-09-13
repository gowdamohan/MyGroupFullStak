<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Category</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Category</h3>
   </div>

   <?php if (!empty($edit_category2)) { ?>
      <form enctype="multipart/form-data" method="post" id="category-slider" action="<?php echo site_url('labor_controller/update_categroy2/'.$edit_category2->id) ?>" class="form-horizontal" data-parsley-validate > 
         <div class="panel-body">            
            <div class="col-md-8">
               <div class="form-group">
                 <label class="control-label col-sm-4" for="no_of_days">Name</label>
                 <div class="col-sm-8">
                     <input type="text" required="" placeholder="Categroy" value="<?php echo $edit_category2->name ?>" id="category" class="form-control" name="category" >
                 </div>
               </div>

               <div class="form-group">
                 <label class="control-label col-sm-4" for="no_of_days">Mobile Number</label>
                 <div class="col-sm-8">
                     <input type="text" required=""  value="<?php echo $edit_category2->mobile_number ?>" id="mobile_number" class="form-control" name="mobile_number" >
                 </div>
               </div>

               <center>
                  <button type="submit" class="btn btn-primary">Update</button>
               </center>
            </div>
         </div>
      </form>
   <?php }else{ ?>
      <form enctype="multipart/form-data" method="post" id="category-slider" action="<?php echo site_url('labor_controller/insert_categroy2') ?>" class="form-horizontal" data-parsley-validate > 
         <div class="panel-body">
            <div class="col-md-8">
               <div class="form-group">
                 <label class="control-label col-sm-4" for="no_of_days">Name</label>
                 <div class="col-sm-8">
                     <input type="text" required="" placeholder="Categroy" value="" id="category" class="form-control" name="category" >
                 </div>
               </div>

               <div class="form-group">
                 <label class="control-label col-sm-4" for="no_of_days">Mobile Number</label>
                 <div class="col-sm-8">
                     <input type="text" required=""  value="" id="mobile_number" class="form-control" name="mobile_number" >
                 </div>
               </div>

               <center>
                  <button type="submit" class="btn btn-primary">Update</button>
               </center>
            </div>
         </div>
      </form>
   <?php } ?>
   

   <div class="panel-body">
      <table class="table table-bordered">
         <thead>
            <tr>
               <th>#</th>
               <th>Name</th>
               <th>Mobile Number</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
         <?php 
            $i=1;
               foreach ($category2 as $key => $val) { ?>
               <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo $val->name ?></td>
                  <td><?php echo $val->mobile_number ?></td>
                  <td>
                     <a href="<?= site_url('labor_controller/edit_category2/'.$val->id) ?>"  class="btn btn-info">Edit</a>
                     <a onclick="return confirm('Are you sure do you want delete ?')" href="<?= site_url('labor_controller/delete_category2/'.$val->id) ?>"  class="btn btn-danger">Delete</a>
                  </td>
               </tr>
            <?php } ?>
         </tbody>
      </table>
   </div>
</div>

