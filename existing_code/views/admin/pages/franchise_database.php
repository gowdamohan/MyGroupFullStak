<ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
    <li>Franchise Application Details</li>
</ul>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Franchise Application Details</h3>
  </div>
  
  <div class="panel-body">
    <div class="table-responsive">
      <table class="table table-bordered datatable">
        <thead>
          <tr>
            <th>#</th>
            <th>Country</th>
            <th>State</th>
            <th>District</th>
            <th>Name</th>
            <th>Mobile Number</th>
            <th>Email Id</th>
            <th>Laptop/Computer</th>
            <th>Govt School</th>
            <th>Eduation Qualifiation</th>
            <th>Work Expericence</th>
            <th>Other Skils</th>
            <th>Attached</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          if (!empty($franchise_database)) {
             foreach ($franchise_database as $key => $val) { ?>
                <tr>
                  <td><?php echo $i++ ?></td>
                  <td><?php echo $val->country_name ?></td>
                  <td><?php echo $val->state_name ?></td>
                  <td><?php echo $val->district_name ?></td>

                  <td><?php echo $val->applier_name ?></a></td>
                  <td><?php echo $val->applyeer_mobile ?></td>
                  <td><?php echo $val->applyeer_email_id ?></td>
                  <td><?php echo $val->laptop_computer ?></td>
                  <td><?php echo $val->govt_school ?></td>
                  <td><?php echo $val->applier_education ?></td>
                 
                  <td><?php echo $val->applyeer_experience ?></td>
                  <td><?php echo $val->applyeer_any_other_details ?></td>
                  <td><a download="" href="<?php echo $this->filemanager->getFilePath($val->upload_path) ?>">Download</a></td>
                   
                </tr>
            <?php }
            }
           ?>         
        </tbody>
      </table>
    </div>
  </div>
</div>