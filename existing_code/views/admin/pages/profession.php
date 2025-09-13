<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Profession</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Profession</h3>
   </div>

   <?php if (!empty($edit_profession)) { ?>
      <form enctype="multipart/form-data" method="post" id="header-slider" action="<?php echo site_url('admin_controller/update_profession/'.$edit_profession->id) ?>" class="form-horizontal" data-parsley-validate > 
         <div class="panel-body">
             <div class="form-group">
               <div class="col-md-8">
                  <div class="col-md-3">
                     <input type="text" required="" placeholder="Profession" value="<?php echo $edit_profession->profession ?>" id="profession" class="form-control" name="profession" >
                  </div>
                  <div class="col-md-2">
                     <button type="submit" class="btn btn-primary">Update</button>
                  </div>
               </div>
             </div>

         </div>
      </form>
   <?php }else{ ?>
      <form enctype="multipart/form-data" method="post" id="header-slider" action="<?php echo site_url('admin_controller/insert_profession') ?>" class="form-horizontal" data-parsley-validate > 
         <div class="panel-body">

             <div class="form-group">
               <div class="col-md-8">
                  <div class="col-md-3">
                     <input type="text" id="profession" placeholder="Profession"  class="form-control" name="profession" >
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
               <th>Education</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
         <?php 
            $i=1;
               foreach ($profession as $key => $val) { ?>
               <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo $val->profession ?></td>
                  <td>
                     <a href="<?= site_url('admin_controller/edit_profession/'.$val->id) ?>"  class="btn btn-info">Edit</a>
                     <a onclick="return confirm('Are you sure do you want delete ?')" href="<?= site_url('admin_controller/delete_profession/'.$val->id) ?>"  class="btn btn-danger">Delete</a>
                  </td>
               </tr>
            <?php } ?>
         </tbody>
      </table>
   </div>
</div>




