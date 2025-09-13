<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Corporate Login</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Corporate Login</h3>
   </div>
   <div class="panel-body">
      <form enctype="multipart/form-data" method="post" action="<?php echo site_url('franchise/corporate_login_insert') ?>" id="franchies-form" class="form-horizontal" data-parsley-validate >
         <div class="col-md-6 col-md-offset-1">
            <div class="form-group">
               <label class="control-label col-sm-4">Name</label>
               <div class="col-sm-8">
                  <input type="text" class="form-control" name="franchise_name" id="franchise_name">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-4">Mobile Number</label>
               <div class="col-sm-8">
                  <input type="text" class="form-control" name="franchise_mobile_number" id="franchise_mobile_number">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-4">Email-Id</label>
               <div class="col-sm-8">
                  <input type="email" class="form-control" name="franchise_email_id" id="franchise_email_id">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-4">User Name</label>
               <div class="col-sm-8">
                  <input type="text" class="form-control" name="username" id="username">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-4">Password</label>
               <div class="col-sm-8">
                  <input type="text" class="form-control" name="password" id="password">
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
               <th>Email</th>
               <th>Username</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php $i=1; foreach ($users as $key => $val) { ?>
               <tr>
                  <td> <?php echo $i++; ?></td>
                  <td> <?php echo $val->first_name; ?></td>
                  <td> <?php echo $val->phone; ?></td>
                  <td> <?php echo $val->email; ?></td>
                  <td> <?php echo $val->username; ?></td>
                  <td><a class="btn btn-danger" href="<?php echo site_url('franchise/reset_password/'.$val->id) ?>">Rest password</a></td>
               </tr>   
            <?php } ?>
            
         </tbody>
      </table>
   </div>
</div>