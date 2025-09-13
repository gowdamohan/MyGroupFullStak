<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Labor Login</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Labor Login</h3>
   </div>
   <div class="panel-body">
      <form enctype="multipart/form-data" method="post" action="<?php echo site_url('labor_controller/labor_login_insert') ?>" id="franchies-form" class="form-horizontal" data-parsley-validate >
         <div class="col-md-6 col-md-offset-1">
            <div class="form-group">
               <label class="control-label col-sm-4">Name</label>
               <div class="col-sm-8">
                  <input type="text" class="form-control" name="labor_name" id="labor_name">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-4">Mobile Number</label>
               <div class="col-sm-8">
                  <input type="text" class="form-control" name="labor_mobile_number" id="labor_mobile_number">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-4">Designation</label>
               <div class="col-sm-8">
                  <input type="text" class="form-control" name="labor_designation" id="labor_designation">
               </div>
            </div>
           
            <center>
               <button type="submit" class="btn btn-info">Submit</button>
            </center>
         </div>
      </form>
   </div>

   <div class="panel-body">
      <table class="table table-bordered">
         <thead>
            <tr>
               <th>#</th>
               <th>Name</th>
               <th>Mobile Number</th>
               <th>Designation</th>
            </tr>
         </thead>
         <tbody>
            <?php $i=1; foreach ($laborlogin as $key => $val) { ?>
               <tr>
                  <td> <?php echo $i++; ?></td>
                  <td> <?php echo $val->labor_name; ?></td>
                  <td> <?php echo $val->labor_mobile_number; ?></td>
                  <td> <?php echo $val->labor_designation; ?></td>
                  <td><a class="btn btn-info" href="<?php echo site_url('labor_controller/labor_permission_view/'.$val->id) ?>" >Enable Filed</a></td>
               </tr>   
            <?php } ?>
            
         </tbody>
      </table>
   </div>
</div>