<ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
    <li><a href="<?php echo site_url('admin_controller/add_mygroup_sub_logo');?>">Add New</a></li>
    <li>Slides</li>
</ul>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Add Slider</h3>
  </div>
  <div class="panel-body">
    <form enctype="multipart/form-data" method="post" id="students" action="<?php echo site_url('admin_controller/submit_sub_logo_slide/'.$id) ?>" class="form-horizontal" data-parsley-validate >

      <div class="form-group">
        <label class="control-label col-sm-1" for="logo">Slides</label>
        <div class="col-sm-8"> 
          <input type="file" required="" class="form-control" id="sub_logo_slides"  name="sub_logo_slides">
        </div>
      </div>

      <input type="submit" value="Add >>" class="btn btn-primary">
    </form>
  </div>

  <div class="panel-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Slides</th>
          <th width="10%">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach ($sub_logo_slides as $key => $val) { ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><img width="100x" src="<?php echo base_url().$val->slides; ?>"> </td>
            <td>
              <a onclick="return confirm('Are you sure you want delete?')" href="<?= site_url('admin_controller/delete_sub_logo_slides/'.$val->id.'/'.$id) ?>" class='btn btn-danger btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><i class='fa fa-trash-o'></i></a>
            </td>
          </tr>    
        <?php } ?>
       
      </tbody>
    </table>
  </div>
</div>