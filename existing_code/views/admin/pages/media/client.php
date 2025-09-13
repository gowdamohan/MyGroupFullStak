<ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
    <li>CLient List</li>
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
          <th>Name</th>
          <th>Mobile No.</th>
          <th>Profile</th>
          <th>Partner Access</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $i = 1;
        foreach ($client as $key => $list) { ?>
          <tr>
            <td><?php echo $i++ ?></td>
            <td><?php echo $list->first_name ?></td>
            <td><?php echo $list->phone ?></td>
            <td><a class="btn btn-primary btn-sm" href="#">Profile</a></td>
            <td>
              <span style="float: right;" >
                <?php if($list->status == 1){ ?>
                  <label class="switch">
                    <input type="checkbox" onclick="media_partner_switch_check('<?php echo $list->id ?>','0')" checked >
                    <span></span>
                    </label>
                <?php }else{ ?>
                    <label class="switch">
                        <input type="checkbox" onclick="media_partner_switch_check('<?php echo $list->id ?>','1')" >
                        <span></span>
                    </label>
                <?php } ?>
              </span>
            </td>
           
          </tr>
        <?php }
        ?>
        
      </tbody>
    </table>
  </div>
</div>

<script type="text/javascript">
  function media_partner_switch_check(stngId, value) {
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