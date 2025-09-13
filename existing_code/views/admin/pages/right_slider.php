<ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
    <li><a href="<?php echo site_url('admin_controller/advertisements');?>">Advertisements</a></li>
    <li>Right Slider</li>
</ul>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Right Slider</h3>
  </div>
  <div class="panel-body">
    <?php if (!empty($edit_right_slider)) { ?>
      <form enctype="multipart/form-data" method="post" id="students" action="<?php echo site_url('admin_controller/update_right_slider/'.$edit_right_slider->id) ?>" class="form-horizontal" data-parsley-validate >
        <div class="col-md-5">
         <div class="form-group">
            <label class="control-label col-sm-1" for="no_of_days">Right Slider</label>
            <div class="col-sm-8">
              <input type="file" class="form-control" id="image-upload"  name="right_slider">
              <input type="hidden"  value="<?php echo $edit_right_slider->image ?>" class="form-control" id="right_slider1"  name="right_slider1">
            </div>
          </div>
        </div>
        <div class="col-md-1">
          <input type="submit" value="Upload" class="btn btn-primary">
        </div>
      </form>
    <?php }else{ ?>
      <form enctype="multipart/form-data" method="post" id="students" action="<?php echo site_url('admin_controller/submit_right_slider') ?>" class="form-horizontal" data-parsley-validate >
        <div class="col-md-5">
         <div class="form-group">
            <label class="control-label col-sm-1" for="no_of_days">Right Slider</label>
            <div class="col-sm-8"> 
              <input type="file" required="" class="form-control" id="image-upload" name="right_slider">
            </div>
          </div>
        </div>
        <div class="col-md-1">
          <input type="submit" value="Upload" class="btn btn-primary">
        </div>
      </form>
    <?php } ?>
   
  </div>

  <div class="panel-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Right Slider</th>
          <th width="10%">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach ($rightSliders as $key => $val) { ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><img width="160px;" class="img-responsive" src="<?php echo base_url().$val->image ?>"></td>
            <td>
              <a href="<?= site_url('admin_controller/edit_right_slider/'.$val->id) ?>" class="btn btn-warning btn-xs mrg" data-placement="top" data-toggle="tooltip"  data-original-title="Edit">
              <i class='fa fa-edit'></i>
              </a>
              <a onclick="return confirm('Are you sure do you want delete ?')" href="<?= site_url('admin_controller/delete_right_slider/'.$val->id) ?>" class='btn btn-danger btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><i class='fa fa-trash-o'></i></a>
            </td>
          </tr>    
        <?php } ?>
       
      </tbody>
    </table>
  </div>
</div>