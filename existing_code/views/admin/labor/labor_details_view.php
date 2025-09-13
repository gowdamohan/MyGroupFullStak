  <?php 
      $users = $this->ion_auth->user()->row();
      
      $this->db->where('labor_mobile_number',$users->username);
      $query = $this->db->get('labor_account')->row();
      // $accountDetails = ['Add Category','Add Category1','Add Category2','Add Labors','Labors Details','Create Login','Attendance'];
      $accountDetails = [];
      if (!empty($query)) {
        $accountDetails = json_decode($query->account_details);
      }else{
        $accountDetails = ['Labors profile','Labors details'];
      }
      
    ?>
<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Labor Details</h3>
        <?php if (empty($query)) { ?>
          <ul class="panel-controls">
            <li><a href="<?php echo site_url('labor_controller/add_labor') ?>" class="control-primary"><span class="fa fa-plus"></span></a></li>
          </ul>  
        <?php } ?>
    </div>


    <div class="panel-body table-responsive">
      <table class="table table-bordered" id="laborDetails">
        <thead>
          <tr>
            <th>#</th>
            <th>Labor ID</th>
            <th>Labor Name</th>
            <th>Category</th>
            <?php if (in_array('Labors profile', $accountDetails)) { ?>
              <th>Date of Birth</th>
              <th>Aadhar Number</th>
              <th>Blood Group</th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach ($labor as $key => $val) { ?>
            <tr>
              <td><?php echo $i++ ?></td>
              <td><a href="<?php echo site_url('labor_controller/labor_view_add/'.$val->id) ?>" ><?php echo $val->labor_id_number ?></a> </td>
              <td><?php echo $val->labor_name ?></td>
              <td><?php echo $val->category ?></td>
              <?php if (in_array('Labors profile', $accountDetails)) { ?>
              <td><?php echo $val->date_of_birth ?></td>
              <td><?php echo $val->aadhar_number ?></td>
              <td><?php echo $val->blood_group ?></td>
              <?php } ?>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    $('#laborDetails').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'excel', 'print'
        ]
    } );
} );




</script>
  <!--  For Datatables -->
    <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/datatables/jszip.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/datatables/vfs_fonts.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/datatables/buttons.flash.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/datatables/buttons.html5.min.js"></script>
   

    <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/datatables/buttons.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/datatables/buttons.print.min.js"></script>
   
    <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/datatables/pdfmake.min.js"></script>
  

    <link rel="stylesheet" type="text/css"  href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css"  href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css"/>
    


