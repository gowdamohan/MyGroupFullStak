<ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
    <li>Add New</li>
</ul>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Add New</h3>
  </div>
  <div class="panel-body">
    <?php if (!empty($edit_sub_logo)) { ?>
      <form enctype="multipart/form-data" method="post" id="students" action="<?php echo site_url('admin_controller/update_sub_logo/'.$edit_sub_logo->id) ?>" class="form-horizontal" data-parsley-validate >
        <div class="form-group">
          <label class="control-label col-sm-1" for="logo_name">Name</label>
          <div class="col-sm-8"> 
            <input type="text" value="<?php echo $edit_sub_logo->logo_name ?>" class="form-control" id="logo_name"  name="logo_name">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-1" for="icon">Icon</label>
          <div class="col-sm-8"> 
            <input type="file"  class="form-control" id="icon"  name="icon">
            <input type="hidden"  value="<?php echo $edit_sub_logo->icon ?>" class="form-control" id="icon1"  name="icon1">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-1" for="logo">Banner</label>
          <div class="col-sm-8"> 
            <input type="file"  class="form-control" id="logo"  name="logo">
            <input type="hidden"  value="<?php echo $edit_sub_logo->logo ?>" class="form-control" id="logo1"  name="logo1">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-1" for="url">URL</label>
          <div class="col-sm-8"> 
            <input type="text" value="<?php echo $edit_sub_logo->url ?>" class="form-control" id="url"  name="url">
          </div>
        </div>
        <input type="submit" value="Update" class="btn btn-primary">
      </form>

    <?php }else{ ?>
      <form enctype="multipart/form-data" method="post" id="students" action="<?php echo site_url('admin_controller/submit_sub_logo') ?>" class="form-horizontal" data-parsley-validate >
        <div class="form-group">
          <label class="control-label col-sm-1" for="logo_name">Name</label>
          <div class="col-sm-8"> 
            <input type="text" required="" class="form-control" id="logo_name"  name="logo_name">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-1" for="icon">Icon</label>
          <div class="col-sm-8"> 
            <input type="file" required="" class="form-control" id="icon"  name="icon">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-1" for="logo">Banner</label>
          <div class="col-sm-8"> 
            <input type="file" required="" class="form-control" id="logo"  name="logo">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-1" for="url">URL</label>
          <div class="col-sm-8"> 
            <input type="text" required="" class="form-control" id="url"  name="url">
          </div>
        </div>
        <input type="submit" value="Submit" class="btn btn-primary">
      </form>
    <?php } ?>
    
  </div>

  <div class="panel-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Logo Name</th>
          <th>Icon</th>
          <th>logo</th>
          <th>URL</th>
          <th width="10%">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach ($sub_logo as $key => $val) { ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $val->logo_name; ?></td>
            <td><img width="80px" src="<?php echo base_url().$val->icon; ?>"> </td>
            <td><img width="80px" src="<?php echo base_url().$val->logo ?>"></td>
            <td><?php echo $val->url; ?></td>
            <td>

              <a href="<?= site_url('admin_controller/edit_sub_logo/'.$val->id) ?>" class="btn btn-warning btn-xs mrg" data-placement="top" data-toggle="tooltip"  data-original-title="Edit">
              <i class='fa fa-edit'></i>
              </a>

              <a onclick="return confirm('Are you sure you want delete?')" href="<?= site_url('admin_controller/delete_sub_logo/'.$val->id) ?>" class='btn btn-danger btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><i class='fa fa-trash-o'></i></a>
            </td>
          </tr>    
        <?php } ?>
       
      </tbody>
    </table>
  </div>
</div>