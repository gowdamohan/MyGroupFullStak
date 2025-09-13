<ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
    <li>LOGO</li>
</ul>
<div class="panel panel-default">
 <div class="panel-heading">
    <h3 class="panel-title">Logo</h3>
 </div>
 <div class="panel-body">
  <?php if (!empty($edit_logo)) { ?>
    <form enctype="multipart/form-data" method="post" id="students" action="<?php echo site_url('admin_controller/update_logo/'.$edit_logo->id) ?>" class="form-horizontal" data-parsley-validate >
       <div class="form-group">
          <label class="control-label col-sm-1" for="no_of_days">Logo</label>
          <div class="col-sm-8"> 
            <input type="file" class="form-control" id="logo"  name="logo">
            <input type="hidden" value="<?php echo $edit_logo->logo ?>" class="form-control" id="logo1"  name="logo1">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-1" for="no_of_days">Name</label>
          <div class="col-sm-8"> 
            <input type="text" value="<?php echo $edit_logo->logo_name ?>" class="form-control" id="logo_name"  name="logo_name">
          </div>
        </div>
        <input type="submit" value="Add >>" class="btn btn-primary">
    </form>
  <?php }else{ ?>
      <form enctype="multipart/form-data" method="post" id="students" action="<?php echo site_url('admin_controller/submit_logo') ?>" class="form-horizontal" data-parsley-validate >
       <div class="form-group">
          <label class="control-label col-sm-1" for="no_of_days">Logo</label>
          <div class="col-sm-8"> 
            <input type="file" required="" class="form-control" id="logo"  name="logo">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-1" for="no_of_days">Name</label>
          <div class="col-sm-8"> 
            <input type="text" required="" class="form-control" id="logo_name"  name="logo_name">
          </div>
        </div>
        <input type="submit" value="Add >>" class="btn btn-primary">
      </form>
  <?php } ?>
 
 </div>
 <div class="panel-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Logo</th>
          <th>Name</th>
          <th width="10%">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($logo)) { ?>
           <tr>
            <td><img width="80px" src="<?php echo base_url().$logo->logo ?>"></td>
            <td><?php echo $logo->logo_name ?></td>
            <td>
              <a href="<?= site_url('admin_controller/edit_logo/'.$logo->id) ?>" class="btn btn-warning btn-xs mrg" data-placement="top" data-toggle="tooltip"  data-original-title="Edit">
                <i class='fa fa-edit'></i>
              </a>
              <a onclick="return confirm('Are you sure do you want delete ?')" href="<?= site_url('admin_controller/delete_logo/'.$logo->id) ?>" class='btn btn-danger btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><i class='fa fa-trash-o'></i></a>
            </td>
          </tr>   
        <?php } ?>
        
      </tbody>
  </table>
 </div>
</div>


