<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Education</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Education</h3>
   </div>

   <?php if (!empty($edit_education)) { ?>
      <form enctype="multipart/form-data" method="post" id="header-slider" action="<?php echo site_url('admin_controller/update_education/'.$edit_education->id) ?>" class="form-horizontal" data-parsley-validate > 
         <div class="panel-body">
             <div class="form-group">
               <div class="col-md-8">
                  <div class="col-md-3">
                     <input type="text" required="" placeholder="Education" value="<?php echo $edit_education->education ?>" id="education" class="form-control" name="education" >
                  </div>
                  <div class="col-md-2">
                     <button type="submit" class="btn btn-primary">Update</button>
                  </div>
               </div>
             </div>

         </div>
      </form>
   <?php }else{ ?>
      <form enctype="multipart/form-data" method="post" id="header-slider" action="<?php echo site_url('admin_controller/insert_education') ?>" class="form-horizontal" data-parsley-validate > 
         <div class="panel-body">

             <div class="form-group">
               <div class="col-md-8">
                  <div class="col-md-3">
                     <input type="text" id="education" placeholder="Education"  class="form-control" name="education" >
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
               foreach ($education as $key => $val) { ?>
               <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo $val->education ?></td>
                  <td>
                     <a href="<?= site_url('admin_controller/edit_education/'.$val->id) ?>"  class="btn btn-info">Edit</a>
                     <a onclick="return confirm('Are you sure do you want delete ?')" href="<?= site_url('admin_controller/delete_education/'.$val->id) ?>"  class="btn btn-danger">Delete</a>
                  </td>
               </tr>
            <?php } ?>
         </tbody>
      </table>
   </div>
</div>




