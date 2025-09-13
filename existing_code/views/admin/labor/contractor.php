<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Contractor</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Contractor</h3>
   </div>

   <?php if (!empty($edit_contractor)) { ?>
      <form enctype="multipart/form-data" method="post" id="category-slider" action="<?php echo site_url('labor_controller/update_contractor/'.$edit_contractor->id) ?>" class="form-horizontal" data-parsley-validate > 
         <div class="panel-body">            
            <div class="col-md-8">
               <div class="form-group">
                 <label class="control-label col-sm-4" for="no_of_days">Name</label>
                 <div class="col-sm-8">
                     <input type="text" required="" placeholder="Categroy" value="<?php echo $edit_contractor->name ?>" id="category" class="form-control" name="category" >
                 </div>
               </div>

               <div class="form-group">
                 <label class="control-label col-sm-4" for="no_of_days">Mobile Number</label>
                 <div class="col-sm-8">
                     <input type="text" required=""  value="<?php echo $edit_contractor->mobile_number ?>" id="mobile_number" class="form-control" name="mobile_number" >
                 </div>
               </div>

               <div class="form-group">
                  <label class="control-label col-sm-4" for="no_of_days">Address</label>
                  <div class="col-sm-8">
                    <textarea class="summernote" name="address" id="address"><?php echo $edit_contractor->address ?></textarea>
                  </div>
               </div>

                <div class="form-group">
                  <label class="control-label col-sm-4" for="no_of_days">Account Details</label>
                  <div class="col-sm-8">
                    <textarea class="summernote" name="account_details" id="account_details"><?php echo $edit_contractor->account_details ?></textarea>
                  </div>
               </div>

               <center>
                  <button type="submit" class="btn btn-primary">Update</button>
               </center>
            </div>
         </div>
      </form>
   <?php }else{ ?>
      <form enctype="multipart/form-data" method="post" id="category-slider" action="<?php echo site_url('labor_controller/insert_contractor') ?>" class="form-horizontal" data-parsley-validate > 
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

               <div class="form-group">
                  <label class="control-label col-sm-4" for="no_of_days">Address</label>
                  <div class="col-sm-8">
                    <textarea class="summernote" name="address" id="address"></textarea>
                  </div>
               </div>

                <div class="form-group">
                  <label class="control-label col-sm-4" for="no_of_days">Account Details</label>
                  <div class="col-sm-8">
                    <textarea class="summernote" name="account_details" id="account_details"></textarea>
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
               <th>Address</th>
               <th>Account Details</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
         <?php 
            $i=1;
               foreach ($contractor as $key => $val) { ?>
               <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo $val->name ?></td>
                  <td><?php echo $val->mobile_number ?></td>
                  <td><?php echo $val->address ?></td>
                  <td><?php echo $val->account_details ?></td>
                  <td>
                     <a href="<?= site_url('labor_controller/edit_contractor/'.$val->id) ?>"  class="btn btn-info">Edit</a>
                     <a onclick="return confirm('Are you sure do you want delete ?')" href="<?= site_url('labor_controller/delete_contractor/'.$val->id) ?>"  class="btn btn-danger">Delete</a>
                  </td>
               </tr>
            <?php } ?>
         </tbody>
      </table>
   </div>
</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/plugins/summernote/summernote.js"></script>
