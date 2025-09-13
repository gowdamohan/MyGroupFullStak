<ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
    <li>Sub Category</li>
</ul>
<div class="panel panel-default">
 <div class="panel-heading">
    <h3 class="panel-title">Sub Category</h3>
 </div>
 <div class="panel-body">
  <form enctype="multipart/form-data" method="post" id="students" action="<?php echo site_url('myads/submit_sub_category') ?>" class="form-horizontal" data-parsley-validate >
    <div class="col-md-8 col-md-offset-1">
     <div class="form-group">
        <label class="control-label col-sm-4" for="no_of_days">Select Category</label>
        <div class="col-sm-8">
          <select class="form-control" name="category" id="category">
            <option value="">Select category</option>
            <?php foreach ($category as $key => $val) {
              echo "<option value=".$val->id." >".$val->category."</option>";
            } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-4" for="no_of_days">Sub Category</label>
        <div class="col-sm-8">
          <input type="text" required="" placeholder="Sub Category Name" class="form-control" id="sub_category"  name="sub_cat">
        </div>
      </div>
      <center>
       <input type="submit" value="Submit" class="btn btn-primary">
       <a class="btn btn-danger" href="<?php echo site_url('myads/admin_dashboard') ?>">Cancel / Back</a>
      </center>    
    </div>
  
  </form> 
 </div>

 <div class="panel-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th width="2%" >#</th>
          <th>Category</th>
          <th>Sub Category</th>
          <th width="10%" >Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($sub_category)) { ?>
          <?php $i=1; foreach ($sub_category as $key => $val) { ?>
            <tr>
              <td><?php echo $i++; ?></td>
              <td><?php echo $val->category ?></td>
              <td><?php echo $val->sub_category ?></td>
              <td>
                <a onclick="return confirm('Are you sure do you want delete this sub category ?')" href="<?= site_url('myads/delete_sub_category/'.$val->id) ?>" class='btn btn-warning btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><i class='fa fa-trash-o'></i></a>

                <a href="<?= site_url('myads/product_sub_category/'.$val->id) ?>" class='btn btn-info btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='add product'>Add Product</a>
              </td>
            </tr>    
          <?php } ?>
          
        <?php } ?>
       
      </tbody>
  </table>
 </div>
</div>

