<ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
    <li>Public Database</li>
</ul>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Public Database</h3>
  </div>
  
  <div class="panel-body">
    <div class="table-responsive">
      <table class="table table-bordered datatable">
        <thead>
          <tr>
            <th>#</th>
            <th>Apply For</th>
            <th>File</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          if (!empty($apply_database)) {
            foreach ($apply_database as $key => $val) { ?>
              <tr>
                <td><?php echo $i++ ?></td>
                <td><?php echo $val->apply_for ?></td>
                <td><a  target="_blank" download href="<?php echo $this->filemanager->getFilePath($val->upload_path) ?>">Download</a></td>
              </tr>
            <?php }
            }
           ?> 
        </tbody>
      </table>
    </div>
  </div>
</div>