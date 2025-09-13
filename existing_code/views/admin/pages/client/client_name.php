<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Name</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Name</h3>
   </div>

   <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/upload_name_color') ?>" id="header-slider" action="#" class="form-horizontal" data-parsley-validate > 
   <div class="panel-body">
      <div class="col-md-6 col-md-offset-2">

         <div class="form-group">
            <label class="control-label col-sm-3" for="no_of_days">Name</label>
            <div class="col-sm-8"> 

               <?php 
               if (!empty($client_name->name)) { ?>
                  <input type="text" name="client_name" class="form-control" value="<?php echo $client_name->name ?>" >
               <?php }else{ ?>
                  <input type="text" name="client_name" class="form-control" >
               <?php } ?>
            </div>
         </div>


         <div class="form-group">
            <label class="control-label col-sm-3" for="no_of_days">Font Color</label>
            <div class="col-sm-8"> 

               <?php 
               if (!empty($client_name->color)) { ?>
                  <input type="color" name="client_color" class="form-control" value="<?php echo $client_name->color ?>" >
               <?php }else{ ?>
                  <input type="color" name="client_color" class="form-control" >
               <?php } ?>
            </div>
         </div>

      </div>
   </div>
   <div class="panel-footer">
      <center><button type="submit" class="btn btn-primary">Save</button></center>
   </div>
   </form>
</div>