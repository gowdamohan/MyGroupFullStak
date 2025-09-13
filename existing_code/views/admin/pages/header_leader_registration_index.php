<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Leader List</h3>
    </div>

    <?php
  function admission_is_enabled($enabled_fields, $filter) {
    foreach ($enabled_fields as $f) {
      if ($f == $filter)
        return 1;
    }
    return 0;
  }
?>
 <?php 
    $arrayDrpp = [];
    switch ($union_location_type->location_type) {
      case '1': // International
        $arrayDrpp = array('2'=>'national_leader','3'=>'state_leader','4'=>'district_leader');
        break;
      case '2': // National
        $arrayDrpp = array('3'=>'state_leader','4'=>'district_leader');
        break;
      case '3': // Regional
        $arrayDrpp = array('4'=>'district_leader');
        break;
      case '4': // Local
        $arrayDrpp = [];
        break;
      default:
        $arrayDrpp = [];
        break;
    }
?>
<div class="panel-body">
    <div class="row">
        <?php 
        if (!empty($arrayDrpp)) {
            foreach ($arrayDrpp as $key => $val) { ?>
                <div class="col-md-3">
                   <a onclick="get_data_locationtype('<?php echo $key ?>','<?php echo $val ?>')" href="javascript:void(0)">
                      <div class="widget widget-warning widget-item-icon">                          
                        <div class="widget-data-left">
                            <div class="widget-title"><?php echo str_replace('_', ' ', $val) ?></div>
                        </div>                                     
                      </div>
                   </a>
                </div>
            <?php }
            } 
         ?>
    </div>
</div>
<script type="text/javascript">
    function get_data_locationtype(key, val) {
        window.location.href='<?php echo site_url('admin_controller/header_leader_index_post/') ?>'+key+'/'+val;
    }
</script>
   <?php if (empty($arrayDrpp)) { ?>
    <div class="panel-body table-responsive" id="header_leaderData">  
        <a class="btn btn-primary" style="float: right;" href="<?php echo site_url('admin_controller/header_leader_registration/'.'4'.'/'.'local') ?>" >ADD LEADER</a>
        <div id="printArea">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <?php if(admission_is_enabled($enabled_fields, 'full_name')) :  ?>
                        <th>Full Name</th>
                    <?php endif ?>
                    <?php if(admission_is_enabled($enabled_fields, 'mobile_number')) :  ?>
                        <th>Mobile Number</th>
                    <?php endif ?>
                     <?php if(admission_is_enabled($enabled_fields, 'email_id')) :  ?>
                        <th>Email-Id</th>
                    <?php endif ?>
                     <?php if(admission_is_enabled($enabled_fields, 'date_of_birth')) :  ?>
                        <th>Date of Birth</th>
                    <?php endif ?>
                    <th>Status</th>
                    <th>View</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $i=1; foreach ($header_leader_view as $key => $val) { ?>
                    <tr>
                        <td><?php echo $i++ ?></td>
                        <?php if(admission_is_enabled($enabled_fields, 'full_name')) :  ?>
                            <td><?php echo $val->full_name ?></td>
                        <?php endif ?>
                    
                        <?php if(admission_is_enabled($enabled_fields, 'mobile_number')) :  ?>
                            <td><?php echo $val->mobile_number ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'email_id')) :  ?>
                            <td><?php echo $val->email_id ?></td>
                        <?php endif ?>
                        
                        <?php if(admission_is_enabled($enabled_fields, 'date_of_birth')) :  ?>
                           <td><?php echo $val->date_of_birth ?></td>
                        <?php endif ?>
                        <td></td>
                        <td></td>
                        <td>
                        <!--  <a href="<?php //echo site_url('admin_controller/edit_union_members_list/'.$val->id) ?>" class="btn btn-warning btn-xs mrg" data-placement="top" data-toggle="tooltip"  data-original-title="Edit"><i class='fa fa-edit'></i></a> -->
                           <!--  <a onclick="return confirm('Are you sure do you want delete ?')" href="<?php echo site_url('admin_controller/deleted_header_leader_list/'.$val->id) ?>" class='btn btn-danger btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><i class='fa fa-trash-o'></i></a> -->
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>  
        </div>
                    
    </div>
   <?php } ?>
    

</div>


<style type="text/css">
    .widget{
        min-height: 42px;
    }
</style>