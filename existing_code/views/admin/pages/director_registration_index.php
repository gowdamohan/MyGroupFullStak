<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Director List</h3>         
        <ul class="panel-controls">
            <li><a href="<?php echo site_url('admin_controller/director_registration') ?>" class="control-primary"><span class="fa fa-plus"></span></a></li>
        </ul>
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
    <div class="panel-body table-responsive">  
        <a style="margin-left:3px;" onclick="exportToExcel()" class="btn btn-primary pull-right">Export</a> 
        <div id="printArea">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <?php if(admission_is_enabled($enabled_fields, 'name')) :  ?>
                        <th>Director Name</th>
                    <?php endif ?>
                     <?php if(admission_is_enabled($enabled_fields, 'designation')) :  ?>
                        <th>Designation</th>
                    <?php endif ?>
                    <th>Mobile Number</th>
                     <?php if(admission_is_enabled($enabled_fields, 'email_id')) :  ?>
                        <th>Email-Id</th>
                    <?php endif ?>
                    <?php if(admission_is_enabled($enabled_fields, 'name_of_media')) :  ?>
                        <th>Name of th Media</th>
                    <?php endif ?>
                    <?php if(admission_is_enabled($enabled_fields, 'designation_in_media')) :  ?>
                        <th>Designation of th Media</th>
                    <?php endif ?>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $i=1; foreach ($director_view as $key => $val) { ?>
                    <tr>
                        <td><?php echo $i++ ?></td>
                        <?php if(admission_is_enabled($enabled_fields, 'name')) :  ?>
                            <td><?php echo $val->name ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'designation')) :  ?>
                            <td><?php echo $val->designation ?></td>
                        <?php endif ?>
                            <td><?php echo $val->mobile_number ?></td>
                        <?php if(admission_is_enabled($enabled_fields, 'email_id')) :  ?>
                            <td><?php echo $val->email_id ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'name_of_media')) :  ?>
                            <td><?php echo $val->name_of_media ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'designation_in_media')) :  ?>
                            <td><?php echo $val->designation_in_media ?></td>
                        <?php endif ?>
                        <td>
                          <span style="float: right;" >
                            <?php if($val->status == 1){ ?>
                              <label class="switch">
                                <input type="checkbox" onclick="union_director_switch_check('<?php echo $val->id ?>','0')" checked >
                                <span></span>
                                </label>
                            <?php }else{ ?>
                                <label class="switch">
                                    <input type="checkbox" onclick="union_director_switch_check('<?php echo $val->id ?>','1')" >
                                    <span></span>
                                </label>
                            <?php } ?>
                          </span>
                        </td>
                        <td>
                         <a href="<?php echo site_url('admin_controller/edit_director_list/'.$val->id) ?>" class="btn btn-warning btn-xs mrg" data-placement="top" data-toggle="tooltip"  data-original-title="Edit"><i class='fa fa-edit'></i></a>
                            <a onclick="return confirm('Are you sure do you want delete ?')" href="<?php echo site_url('admin_controller/deleted_director_list/'.$val->id) ?>" class='btn btn-danger btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><i class='fa fa-trash-o'></i></a>
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

   function union_director_switch_check(stngId, value) {
    $.ajax({
        url: '<?php echo site_url('admin_controller/union_director_switch_check'); ?>',
        type: "post",
        data:{'stngId':stngId, 'value':value},
        success: function (data) {
          if (data.length=='1') {
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