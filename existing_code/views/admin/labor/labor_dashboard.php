<?php 
  $users = $this->ion_auth->user()->row();
  
  $this->db->where('labor_mobile_number',$users->username);
  $query = $this->db->get('labor_account')->row();
  // $accountDetails = ['Add Category','Add Category1','Add Category2','Add Labors','Labors Details','Create Login','Attendance'];
  $accountDetails = [];
  if (!empty($query)) {
    $accountDetails = json_decode($query->account_details);
  }
  
?>

<?php if (in_array('Labors details', $accountDetails) || in_array('Labors profile', $accountDetails)) { ?>
  <div class="col-md-2">                        
    <a href="<?php echo site_url('labor_controller/labor_details_seperate') ?>" class="tile tile-success tile-valign">Labors Details
      <div class="informer informer-default dir-bl"><span class="fa fa-globe"></span>Labors Details</div>
    </a>  
  </div>
<?php } ?>

<?php if (in_array('Attendance', $accountDetails)) { ?>
  <div class="col-md-2">                        
    <a href="#" class="tile tile-warning tile-valign">Attendance
      <div class="informer informer-default dir-bl"><span class="fa fa-globe"></span>Attendance</div>
    </a>  
  </div>
<?php } ?>