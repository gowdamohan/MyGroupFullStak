<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Language</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Language</h3>
   </div>

   <?php if (!empty($edit_language)) { ?>
      <form enctype="multipart/form-data" method="post" id="header-slider" action="<?php echo site_url('admin_controller/update_langague/'.$edit_language->id) ?>" class="form-horizontal" data-parsley-validate > 
         <div class="panel-body">
             <div class="form-group">
               <div class="col-md-8">
                  <div class="col-md-3">
                     <input type="text" required="" placeholder="Language-1" value="<?php echo $edit_language->lang_1 ?>" id="lang_1" class="form-control" name="lang_1" >
                  </div>
                  <div class="col-md-3">
                      <p>Language-2</p>
                     <input type="text" id="lang_2"  placeholder="Language-2"  value="<?php echo $edit_language->lang_2 ?>" class="form-control" name="lang_2" >
                  </div>
                  <div class="col-md-2">
                     <button type="submit" class="btn btn-primary">Update</button>
                  </div>
               </div>
             </div>

         </div>
      </form>
   <?php }else{ ?>
      <form enctype="multipart/form-data" method="post" id="header-slider" action="<?php echo site_url('admin_controller/insert_langague') ?>" class="form-horizontal" data-parsley-validate > 
         <div class="panel-body">

             <div class="form-group">
               <div class="col-md-8">
                  <div class="col-md-3">
                     <input type="text" required="" placeholder="Language-1"  id="lang_1" class="form-control" name="lang_1" >
                  </div>

                  <div class="col-md-3">
                     <input type="text" id="lang_2" placeholder="Language-2"  class="form-control" name="lang_2" >
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
               <th>Lang-1</th>
               <th>Lang-2</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
         <?php 
            $i=1;
               foreach ($language as $key => $val) { ?>
               <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo $val->lang_1 ?></td>
                  <td><?php echo $val->lang_2 ?></td>
                  <td>
                     <a href="<?= site_url('admin_controller/edit_langauge/'.$val->id) ?>"  class="btn btn-info">Edit</a>
                     <a onclick="return confirm('Are you sure do you want delete ?')" href="<?= site_url('admin_controller/delete_language/'.$val->id) ?>"  class="btn btn-danger">Delete</a>
                  </td>
               </tr>
            <?php } ?>
         </tbody>
      </table>
   </div>
</div>




