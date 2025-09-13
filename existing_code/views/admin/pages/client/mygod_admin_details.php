<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Admin Details</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Admin Details</h3>
   </div>

   <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/upload_mygod_admin_details') ?>"  class="form-horizontal" data-parsley-validate > 
   <div class="panel-body">
      <div class="col-md-6 col-md-offset-2">

         <div class="form-group">
            <label class="control-label col-sm-4" for="client_name">Admin Name</label>
            <div class="col-sm-8"> 

               <?php 
               if (!empty($user_details->first_name)) { ?>
                  <input type="text" name="first_name" class="form-control" value="<?php echo $user_details->first_name ?>" >
               <?php }else{ ?>
                  <input type="text" name="first_name" class="form-control" >
               <?php } ?>
            </div>
         </div>

         <div class="form-group">
            <label class="control-label col-sm-4" for="regional_lang_name">Admin Phone Number</label>
            <div class="col-sm-8">
               <?php 
               if (!empty($user_details->phone)) { ?>
                  <input type="text" name="phone" class="form-control" value="<?php echo $user_details->phone ?>" >
               <?php }else{ ?>
                  <input type="text" name="phone" class="form-control" >
               <?php } ?>
            </div>
         </div>
          <div class="form-group">
            <label class="control-label col-sm-4" for="email">Admin Email-Id</label>
            <div class="col-sm-8">
               <?php 
               if (!empty($mygod_admin_details->email)) { ?>
                  <input type="text" name="email" class="form-control" value="<?php echo $mygod_admin_details->email ?>" >
               <?php }else{ ?>
                  <input type="text" name="email" class="form-control" >
               <?php } ?>
            </div>
         </div>

         <div class="form-group">
            <label class="control-label col-sm-4" for="regional_lang_name">Admin details</label>
            <div class="col-sm-8">
               <textarea class="summernote" name="address" id="summernote">
               <?php if(!empty($mygod_admin_details->address)) {
                  echo $mygod_admin_details->address;
               } ?></textarea>
            </div>
         </div>

      </div>
   </div>
   <div class="panel-footer">
      <center><button type="submit" class="btn btn-primary">Save</button></center>
   </div>
   </form>
</div>


<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/plugins/summernote/summernote.js"></script>
