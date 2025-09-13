<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Labor Details</h3>
        <ul class="panel-controls">
            <li><a href="<?php echo site_url('labor_controller/add_labor') ?>" class="control-primary"><span class="fa fa-plus"></span></a></li>
        </ul>     
    </div>
    <div class="panel-body table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Labor Photo</th>
            <th>Labor ID</th>
            <th>Labor Name</th>
            <th>Father/Husband Name</th>
            <th>Mobile Number</th>
            <th>Date of Birth</th>
            <th>Blood Group</th>
            <th>Address</th>
            <th>Payment</th>
            <th>Billing Amount</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach ($labor as $key => $val) { ?>
              <tr>
                <td><?php echo $i++ ?></td>
                <td><img style="height: 30px; width: 30px;border-radius: 50%;" src="<?php echo $this->filemanager->getFilePath($val->labor_photo) ?>"></td>
                <td><?php echo $val->labor_id_number ?></td>
                <td><?php echo $val->labor_name ?></td>
                <td><?php echo $val->father_husband_name ?></td>
                <td><?php echo $val->mobile_number ?></td>
                <td><?php echo $val->date_of_birth ?></td>
                <td><?php echo $val->blood_group ?></td>
                <td><?php echo $val->address ?></td>
                <td><?php echo $val->labor_amount ?></td>
                <td><?php echo $val->billing_amount ?></td>
                <td> <a href="<?php echo site_url('labor_controller/edit_labor/'.$val->id) ?>" class="btn btn-warning btn-xs mrg" data-placement="top" data-toggle="tooltip"  data-original-title="Edit"><i class='fa fa-edit'></i></a></td>
              </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
</div>