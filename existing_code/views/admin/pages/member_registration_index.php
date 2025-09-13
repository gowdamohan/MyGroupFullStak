<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Members List</h3>         
        <ul class="panel-controls">
            <li><a href="<?php echo site_url('admin_controller/member_registration') ?>" class="control-primary"><span class="fa fa-plus"></span></a></li>

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
        <!-- <a style="margin-left:3px;" onclick="exportToExcel()" class="btn btn-primary pull-right">Export</a>  -->
        <div id="printArea">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <?php if(admission_is_enabled($enabled_fields, 'full_name')) :  ?>
                        <th>Full Name</th>
                    <?php endif ?>
                     <?php if(admission_is_enabled($enabled_fields, 'display_name')) :  ?>
                        <th>Display Name</th>
                    <?php endif ?>
                    <?php if(admission_is_enabled($enabled_fields, 'mobile_number')) :  ?>
                        <th>Mobile Number</th>
                    <?php endif ?>
                     <?php if(admission_is_enabled($enabled_fields, 'email_id')) :  ?>
                        <th>Email-Id</th>
                    <?php endif ?>
                     <?php if(admission_is_enabled($enabled_fields, 'father_name')) :  ?>
                        <th>Father Name</th>
                    <?php endif ?>
                     <?php if(admission_is_enabled($enabled_fields, 'mother_name')) :  ?>
                        <th>Mother Name</th>
                    <?php endif ?>
                     <?php if(admission_is_enabled($enabled_fields, 'date_of_birth')) :  ?>
                        <th>Date of Birth</th>
                    <?php endif ?>
                    <?php if(admission_is_enabled($enabled_fields, 'gender')) :  ?>
                        <th>Gender</th>
                    <?php endif ?>
                     <?php if(admission_is_enabled($enabled_fields, 'nationality')) :  ?>
                        <th>Nationlity</th>
                    <?php endif ?>
                     <?php if(admission_is_enabled($enabled_fields, 'martial_status')) :  ?>
                        <th>Marital Statu</th>
                    <?php endif ?>
                     <?php if(admission_is_enabled($enabled_fields, 'spouse_name')) :  ?>
                         <th>Spouse Name</th>
                    <?php endif ?>
                    <?php if(admission_is_enabled($enabled_fields, 'education')) :  ?>
                         <th>Education</th>
                    <?php endif ?>
                     <?php if(admission_is_enabled($enabled_fields, 'work_profession')) :  ?>
                        <th>Work Profession</th>
                    <?php endif ?>
                    <?php if(admission_is_enabled($enabled_fields, 'languages_known')) :  ?>
                        <th>Language Known</th>
                    <?php endif ?>
                     <?php if(admission_is_enabled($enabled_fields, 'blood_group')) :  ?>
                        <th>Blood Group</th>
                    <?php endif ?>
                     <?php if(admission_is_enabled($enabled_fields, 'present_address')) :  ?>
                        <th>Present Address</th>
                    <?php endif ?>
                    <?php if(admission_is_enabled($enabled_fields, 'permanent_address')) :  ?>
                        <th>Permanent Address</th>
                    <?php endif ?>
                    <?php if(admission_is_enabled($enabled_fields, 'introducer_name')) :  ?>
                        <th>Introducer Name</th>
                    <?php endif ?>
                    <?php if(admission_is_enabled($enabled_fields, 'introducer_number')) :  ?>
                        <th>Introducer Number</th>
                    <?php endif ?>
                    <!-- <th>Action</th> -->
                </tr>
                </thead>
                <tbody>
                <?php $i=1; foreach ($member_view as $key => $val) { ?>
                    <tr>
                        <td><?php echo $i++ ?></td>
                        <?php if(admission_is_enabled($enabled_fields, 'full_name')) :  ?>
                            <td><?php echo $val->full_name ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'display_name')) :  ?>
                            <td><?php echo $val->display_name ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'mobile_number')) :  ?>
                            <td><?php echo $val->mobile_number ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'email_id')) :  ?>
                            <td><?php echo $val->email_id ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'father_name')) :  ?>
                          <td><?php echo $val->father_name ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'mother_name')) :  ?>
                           <td><?php echo $val->mother_name ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'date_of_birth')) :  ?>
                           <td><?php echo $val->date_of_birth ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'gender')) :  ?>
                            <td><?php echo $val->gender ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'nationality')) :  ?>
                            <td><?php echo $val->nationality ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'martial_status')) :  ?>
                           <td><?php echo $val->martial_status ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'spouse_name')) :  ?>
                           <td><?php echo $val->spouse_name ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'education')) :  ?>
                           <td><?php echo $val->education ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'work_profession')) :  ?>
                           <td><?php echo $val->work_profession ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'languages_known')) :  ?>
                           <td><?php echo $val->languages_known ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'blood_group')) :  ?>
                            <td><?php echo $val->blood_group ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'present_address')) :  ?>
                            <td><?php echo $val->present_address ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'permanent_address')) :  ?>
                            <td><?php echo $val->permanent_address ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'introducer_name')) :  ?>
                            <td><?php echo $val->introducer_name ?></td>
                        <?php endif ?>
                        <?php if(admission_is_enabled($enabled_fields, 'introducer_number')) :  ?>
                            <td><?php echo $val->introducer_number ?></td>
                        <?php endif ?>
                        <!-- <td> -->
                      <!--    <a href="<?php // echo site_url('admin_controller/edit_union_members_list/'.$val->id) ?>" class="btn btn-warning btn-xs mrg" data-placement="top" data-toggle="tooltip"  data-original-title="Edit"><i class='fa fa-edit'></i></a> -->
                          <!--   <a onclick="return confirm('Are you sure do you want delete ?')" href="<?php // echo site_url('admin_controller/deleted_member_list/'.$val->id) ?>" class='btn btn-danger btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><i class='fa fa-trash-o'></i></a> -->
                        <!-- </td> -->
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