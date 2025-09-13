<ul class="breadcrumb">
  <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
  <li>Labor Permission</li>
</ul>   
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Labor Permission</h3>
   </div>
   <div class="panel-body">
    <?php  $arrayName = array('Labors details','Attendance','Labors profile'); ?>
      <form enctype="multipart/form-data" method="post" action="<?php echo site_url('labor_controller/labor_permission_insert/'.$id) ?>" id="franchies-form" class="form-horizontal" data-parsley-validate >
         <div class="col-md-6 col-md-offset-1">
            <?php 
            foreach ($arrayName as $key => $value) {
                $checked ='';
                if (!empty($accounts)) {
                  if (in_array($value, $accounts)) {
                   $checked ='checked';
                  }
                }
             ?>
              <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="check1" <?php echo $checked ?> name="labor_permission[]" value="<?php echo $value ?>" >
                  <label class="form-check-label"><?php echo $value ?></label>
                </div>
            <?php }

            ?>
            <center>
               <button type="submit" class="btn btn-info">Submit</button>
            </center>
         </div>
      </form>
   </div>
