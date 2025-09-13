<ul class="breadcrumb">
  <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
  <li>Copy rights</li>
</ul>
<div class="panel panel-default">
 <div class="panel-heading">
    <h3 class="panel-title">Copy rights</h3>
 </div>
 <div class="panel-body">
  
  <form enctype="multipart/form-data" method="post" id="students" action="<?php echo site_url('admin_controller/update_copy_rights') ?>" class="form-horizontal" data-parsley-validate >
   <div class="form-group">
      <label class="control-label col-sm-1" for="no_of_days">Copy rights</label>
      <div class="col-sm-8"> 
        <input type="hidden" name="copy_right_id" value="<?php if(!empty($copy_rights)) echo $copy_rights->id ?>">
        <input type="text" value="<?php if(!empty($copy_rights)) echo $copy_rights->copy_right ?>" class="form-control" id="copy_right" name="copy_right">
      </div>
    </div>
    <input type="submit" value="Update" class="btn btn-primary">
  </form>
  
 </div>
 
 <div class="panel-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Copy Rights</th>
        </tr>
      </thead>
      <tbody>
           <tr>
            <td><?php if(!empty($copy_rights)) echo $copy_rights->copy_right ?></td>
          </tr>   
      </tbody>
  </table>
 </div>
</div>


