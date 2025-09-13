<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Logo and Name</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Logo and Name</h3>
   </div>

   <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/upload_admin_details/'.$client_admin->id) ?>" id="header-slider" action="#" class="form-horizontal" data-parsley-validate > 
   <div class="panel-body">
      <div class="col-md-6 col-md-offset-2">

         <div class="form-group">
            <label class="control-label col-sm-4" for="client_name">Admin Name</label>
            <div class="col-sm-8"> 

               <?php 
               if (!empty($client_admin->first_name)) { ?>
                  <input type="text" name="first_name" class="form-control" value="<?php echo $client_admin->first_name ?>" >
               <?php }else{ ?>
                  <input type="text" name="first_name" class="form-control" >
               <?php } ?>
            </div>
         </div>

         <div class="form-group">
            <label class="control-label col-sm-4" for="regional_lang_name">Admin Phone Number</label>
            <div class="col-sm-8">
               <?php 
               if (!empty($client_admin->phone)) { ?>
                  <input type="text" name="phone" class="form-control" value="<?php echo $client_admin->phone ?>" >
               <?php }else{ ?>
                  <input type="text" name="phone" class="form-control" >
               <?php } ?>
            </div>
         </div>
          <div class="form-group">
            <label class="control-label col-sm-4" for="email">Admin Email-Id</label>
            <div class="col-sm-8">
               <?php 
               if (!empty($client_admin->phone)) { ?>
                  <input type="text" name="email" class="form-control" value="<?php echo $client_admin->email ?>" >
               <?php }else{ ?>
                  <input type="text" name="email" class="form-control" >
               <?php } ?>
            </div>
         </div>

         <div class="form-group">
            <label class="control-label col-sm-4" for="regional_lang_name">Admin Office Address</label>
            <div class="col-sm-8">
               <textarea class="summernote" name="address" id="summernote"><?php echo $client_admin->address ?></textarea>
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
