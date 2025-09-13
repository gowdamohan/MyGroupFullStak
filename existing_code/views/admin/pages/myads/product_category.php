<ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
    <li>Category</li>
</ul>
<div class="panel panel-default">
 <div class="panel-heading">
    <h3 class="panel-title">Category</h3>
 </div>
 <div class="panel-body">
  <?php if (!empty($edit_category)) { ?>
    <form enctype="multipart/form-data" method="post" id="students" action="<?php echo site_url('myads/update_category/'.$edit_category->id) ?>" class="form-horizontal" data-parsley-validate >
      <div class="form-group">
        <div class="col-sm-4"> 
          <input type="text" required="" value="<?php echo $edit_category->category ?>" placeholder="Product Name" class="form-control" id="category"  name="category">
        </div>
         <input type="submit" value="Update" class="btn btn-primary">
      </div>
    </form>
  <?php }else{ ?>
    <form enctype="multipart/form-data" method="post" id="students" action="<?php echo site_url('myads/submit_category') ?>" class="form-horizontal" data-parsley-validate >
     <div class="form-group">
        <div class="col-sm-4"> 
          <input type="text" required="" placeholder="Category Name" class="form-control" id="category"  name="category">
        </div>
         <input type="submit" value="Add >>" class="btn btn-primary">
      </div>
    </form>
  <?php } ?>
 
 </div>
 <div class="panel-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th width="2%" >#</th>
          <th width="58%">Category</th>
          <th width="10%">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($category)) { ?>
          <?php $i=1; foreach ($category as $key => $val) { ?>
            <tr>
              <td><?php echo $i++; ?></td>
              <td><?php echo $val->category ?></td>
              <td>
                <a href="<?= site_url('myads/edit_category/'.$val->id) ?>" class='btn btn-warning btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Edit'><i class='fa fa-edit'></i></a>
              </td>
            </tr>    
          <?php } ?>
          
        <?php } ?>
       
      </tbody>
  </table>
 </div>
</div>
