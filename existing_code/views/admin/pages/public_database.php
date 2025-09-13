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
            <th>Full Name</th>
            <th>Display Name</th>
            <th>Mobile Number</th>
            <th>Alternate Mobile</th>
            <th>Email</th>
            <th>DOB</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Martial</th>
            <th>Country</th>
            <th>State</th>
            <th>District</th>
            <th>Nationality</th>
            <th>Education</th>
            <th>Profession</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          if (!empty($public_database)) {
             foreach ($public_database as $key => $val) { ?>
                <tr>
                  <td><?php echo $i++ ?></td>
                  <td><a href="<?php echo site_url('admin_controller/profile_view/'.$val->userId) ?>"><?php echo $val->first_name ?></a></td>
                  <td><?php echo $val->display_name ?></td>
                  <td><?php echo $val->phone ?></td>
                  <td><?php echo $val->mobile_number_alter ?></td>
                  <td><?php echo $val->email ?></td>
                  <td><?php echo $val->dob ?></td>
                  <td><?php 
                  echo date_diff(date_create($val->dob), date_create('today'))->y;
                    ?></td>
                    <td><?php echo $val->gender ?></td>
                    <td><?php echo $val->marital ?></td>
                    <td><?php echo $val->country_name ?></td>
                    <td><?php echo $val->state_name ?></td>
                    <td><?php echo $val->district_name ?></td>
                    <td><?php echo $val->nationality ?></td>
                    <td><?php echo $val->education ?></td>
                    <td><?php echo $val->profession ?></td>
                </tr>
            <?php }
            }
           ?>         
        </tbody>
      </table>
    </div>
  </div>
</div>