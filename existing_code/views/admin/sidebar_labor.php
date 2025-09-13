<?php 
  $users = $this->ion_auth->user()->row();
  
  $this->db->where('labor_mobile_number',$users->username);
  $query = $this->db->get('labor_account')->row();

  $accountDetails = [];
  if (!empty($query)) {
    $accountDetails = json_decode($query->account_details);
  }
  
?>
<div class="page-container">
  <?php if (empty($accountDetails)) { ?>
    <div class="page-sidebar page-sidebar-fixed scroll mCustomScrollbar _mCS_1 mCS-autoHide" style="height: 150px;">
    <div id="mCSB_1" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" tabindex="0">
      <div id="mCSB_1_container" class="mCSB_container" style="position:relative; top:0; left:0;" dir="ltr">
        <ul class="x-navigation">
          <li class="xn-logo">
            <a href="<?php echo base_url('admin_controller') ?>"><?php echo $users->username ?></a>
            <a href="#" class="x-navigation-control"></a>
          </li>
          <li>
            <a href="<?= site_url('dashboard')?>"><span class="fa fa-desktop"></span> <span class="xn-text">Dashboard</span></a>
          </li>
            <li>
              <a href="<?php echo site_url('labor_controller/category') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Add Category</span></a>
            </li>
            
            <li>
              <a href="<?php echo site_url('labor_controller/contractor') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Contractor</span></a>
            </li>


            <li>
              <a href="<?php echo site_url('labor_controller/category1') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Sub Contractor</span></a>
            </li>

              <li>
                <a href="<?php echo site_url('labor_controller/category2') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Team Leaders</span></a>
              </li>
            <li>
              <a href="<?php echo site_url('labor_controller/labor_details') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Add Labors</span></a>
            </li>

              <li>
                <a href="<?php echo site_url('labor_controller/labor_details_seperate') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Labors Details</span></a>
              </li>

             <li>
              <a href="<?php echo site_url('labor_controller/labor_create_login') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Create Login</span></a>
            </li>
            <li>
              <a href="#"><span class="fa fa-desktop"></span> <span class="xn-text">Attendance</span></a>
            </li>
          <li>
            <a href="#" class="mb-control" data-box="#mb-signout"><span class="fa fa-sitemap"></span> <span class="xn-text">Logout</span></a>
          </li>

        </ul>
      </div>
    </div>
  </div>
  <?php } ?>
  
<style type="text/css">
  .x-navigation>li>ul>li>ul>li>a{
    background: #074907;
  }
</style>