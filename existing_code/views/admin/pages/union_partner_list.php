<ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
    <li><a href="<?php echo site_url('admin_controller/advertisements');?>">Advertisements</a></li>
    <li>Right Slider</li>
</ul>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Partner </h3>
  </div>
  <div class="panel-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Union Type</th>
          <th>Union Name</th>
          <th>Union Category</th>
          <th>Name</th>
          <th>Mobile No.</th>
          <th>Profile</th>
          <th>Partner Access</th>
          <th>Login Status</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $i = 1;
        foreach ($partner_list as $key => $list) { ?>
          <tr>
            <td><?php echo $i++ ?></td>
           <!--  <td><?php
            switch ($list->type) {
              case '1':
                $ltype = 'International';
                break;
              case '2':
                $ltype = 'National';
                break;
              case '3':
                $ltype = 'Regional';
                break;
              case '4':
               $ltype = 'Local';
                break;
              default:
                // code...
                break;
            }
            
             echo $ltype ?></td> -->
            <td><?php echo strtoupper($list->union_type) ?></td>
            <td><?php echo $list->name_of_the_organization ?></td>
            <td><?php echo $list->cat_name ?></td>
            <td><?php echo $list->first_name ?></td>
            <td><?php echo $list->phone ?></td>
            <td><a class="btn btn-primary btn-sm" href="<?php echo site_url('admin_controller/view_partner_details/'.$list->id) ?>">Profile</a></td>
            <td>
              <span style="float: right;" >
                <?php if($list->status == 1){ ?>
                  <label class="switch">
                    <input type="checkbox" onclick="union_partner_switch_check('<?php echo $list->id ?>','0')" checked >
                    <span></span>
                    </label>
                <?php }else{ ?>
                    <label class="switch">
                        <input type="checkbox" onclick="union_partner_switch_check('<?php echo $list->id ?>','1')" >
                        <span></span>
                    </label>
                <?php } ?>
              </span>
            </td>
            <td><a class="btn btn-warning btn-sm" href="<?php echo site_url('admin_controller/access_partner_details/'.$list->id) ?>">Accesss</a></td>
            
          </tr>
        <?php }
        ?>
        
      </tbody>
    </table>
  </div>
</div>

<script type="text/javascript">
  function union_partner_switch_check(stngId, value) {
    $.ajax({
        url: '<?php echo site_url('admin_controller/union_partner_switch_check'); ?>',
        type: "post",
        data:{'stngId':stngId, 'value':value},
        success: function (data) {
          console.log(data);
          if (data.trim() == 1) {
            location.reload();
          }else{
            location.reload();
          }
        },
      error: function (err) {
        console.log(err);
      }
    });
  }


</script>