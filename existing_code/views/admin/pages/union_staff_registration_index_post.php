<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Staff List</h3>         
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
        $arrayDrpp = array('2'=>'national_Staff','3'=>'state_staff','4'=>'district_staff');
        break;
      case '2': // National
        $arrayDrpp = array('3'=>'state_staff','4'=>'district_staff');
        break;
      case '3': // Regional
        $arrayDrpp = array('4'=>'district_staff');
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
        window.location.href='<?php echo site_url('admin_controller/union_staff_index_post/') ?>'+key+'/'+val;
    }
</script>

    <div class="panel-body table-responsive">  
          <a class="btn btn-primary" style="float: right;" href="<?php echo site_url('admin_controller/union_staff_registration/'.$type.'/'.$leader_head) ?>" >ADD <?php echo str_replace('_', ' ', strtoupper($leader_head)) ?></a>
        <div id="printArea">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <?php if(admission_is_enabled($enabled_fields, 'name')) :  ?>
                        <th>Staff Name</th>
                    <?php endif ?>
                     <?php if(admission_is_enabled($enabled_fields, 'designation')) :  ?>
                        <th>Designation</th>
                    <?php endif ?>
                    <?php if(admission_is_enabled($enabled_fields, 'mobile_number')) :  ?>
                        <th>Mobile Number</th>
                    <?php endif ?>
                     <?php if(admission_is_enabled($enabled_fields, 'email_id')) :  ?>
                        <th>Email-Id</th>
                    <?php endif ?>
                    <?php if(admission_is_enabled($enabled_fields, 'description')) :  ?>
                        <th>Description</th>
                    <?php endif ?>
                    <?php if(admission_is_enabled($enabled_fields, 'address')) :  ?>
                        <th>Address</th>
                    <?php endif ?>
                    <?php if(admission_is_enabled($enabled_fields, 'name_of_media')) :  ?>
                        <th>Name of th Media</th>
                    <?php endif ?>
                    <?php if(admission_is_enabled($enabled_fields, 'designation_in_media')) :  ?>
                        <th>Designation of th Media</th>
                    <?php endif ?>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $i=1; foreach ($union_staff_view as $key => $val) { ?>
                    <tr>
                        <td><?php echo $i++ ?></td>
                        <?php if(admission_is_enabled($enabled_fields, 'name')) :  ?>
                            <td><?php echo $val->name ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'designation')) :  ?>
                            <td><?php echo $val->designation ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'mobile_number')) :  ?>
                            <td><?php echo $val->mobile_number ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'email_id')) :  ?>
                            <td><?php echo $val->email_id ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'description')) :  ?>
                            <td><?php echo $val->description ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'address')) :  ?>
                            <td><?php echo $val->address ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'name_of_media')) :  ?>
                            <td><?php echo $val->name_of_media ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'designation_in_media')) :  ?>
                            <td><?php echo $val->designation_in_media ?></td>
                        <?php endif ?>

                        <td>
                          <!--   <a href="<?php //echo site_url('admin_controller/edit_members_list/'.$val->id) ?>" class="btn btn-warning btn-xs mrg" data-placement="top" data-toggle="tooltip"  data-original-title="Edit"><i class='fa fa-edit'></i></a> -->
                            <a onclick="return confirm('Are you sure do you want delete ?')" href="<?php echo site_url('admin_controller/deleted_union_staff_list/'.$val->id) ?>" class='btn btn-danger btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><i class='fa fa-trash-o'></i></a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>  
        </div>
                    
    </div>
</div>
<script type="text/javascript">
    function exportToExcel(){
      var htmls = "";
      var uri = 'data:application/vnd.ms-excel;base64,';
      var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>';
      var base64 = function(s) {
          return window.btoa(unescape(encodeURIComponent(s)))
      };

      var format = function(s, c) {
          return s.replace(/{(\w+)}/g, function(m, p) {
              return c[p];
          })
      };

      
      var mainTable = $("#printArea").html();
     
      htmls = mainTable;

      var ctx = {
          worksheet : 'Spreadsheet',
          table : htmls
      }
      var link = document.createElement("a");
      link.download = "memberslist.xls";
      link.href = uri + base64(format(template, ctx));
      link.click();

  }
</script>

<style type="text/css">
    .widget{
        min-height: 42px;
    }
</style>