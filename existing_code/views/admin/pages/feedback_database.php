<ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
    <li>Feedback and Suggestions</li>
</ul>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Feedback and Suggestions</h3>
  </div>
  
  <div class="panel-body">
    <div class="table-responsive">
      <table class="table table-bordered datatable">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email Id</th>
            <th>Mobile Number</th>
            <th>Message</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          if (!empty($feedback_database)) {
             foreach ($feedback_database as $key => $val) { ?>
                <tr>
                  <td><?php echo $i++ ?></td>
                  <td><?php echo $val->first_name ?></a></td>
                  <td><?php echo $val->email ?></td>
                  <td><?php echo $val->phone ?></td>
                  <td><?php echo $val->message ?></td>
                </tr>
            <?php }
            }
           ?>         
        </tbody>
      </table>
    </div>
  </div>
</div>