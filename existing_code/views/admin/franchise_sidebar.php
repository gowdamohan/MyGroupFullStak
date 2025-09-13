<?php 
  $users = $this->ion_auth->user()->row();
  $userid = $this->ion_auth->user()->row()->id;
  $groups = $this->ion_auth->get_users_groups($userid)->row()->name;
  $loginname = '';
  if ($groups=='branch') {
    $result = $this->db->select('dt.district as login_name')
      ->from('franchise_holder fh')
      ->join('district_tbl dt','dt.id=fh.district')
      ->where('fh.user_id',$userid)
      ->get()->row();
    if (!empty($result)) {
      $loginname = $result->login_name;
    }
  }
  if ($groups=='regional') {
      $result = $this->db->select('st.state as login_name')
      ->from('franchise_holder fh')
      ->join('state_tbl st','st.id=fh.state')
      ->where('fh.user_id',$userid)
      ->get()->row();
      if (!empty($result)) {
        $loginname = $result->login_name;
      }
  }
  if ($groups=='head_office') {
      $result = $this->db->select('ct.country as login_name')
      ->from('franchise_holder fh')
      ->join('country_tbl ct','ct.id=fh.country')
      ->where('fh.user_id',$userid)
      ->get()->row();
      if (!empty($result)) {
        $loginname = $result->login_name;
      }
  }
  if ($groups=='corporate') {
      $loginname ='';
  }
?>
<div class="page-container">
  <div class="page-sidebar page-sidebar-fixed scroll mCustomScrollbar _mCS_1 mCS-autoHide"  style="height: 150px;background: #578258;">
    <div id="mCSB_1" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" tabindex="0">
      <div id="mCSB_1_container" class="mCSB_container" style="position:relative; top:0; left:0;" dir="ltr">
        <ul class="x-navigation">
          <li class="xn-logo">
            <a style="height: 75px;" href="<?php echo base_url('admin_controller') ?>"> <?php echo str_replace('_', ' ', strtoupper($groups))  ?>
              <br>
              <br>
              <?php echo $loginname ?>
            </a>
            <a href="#" class="x-navigation-control"></a>
          </li>

          <li>
            <a href="<?= site_url('dashboard')?>"><span class="fa fa-desktop"></span> <span class="xn-text">Dashboard</span></a>
          </li>
          <?php if ($groups == 'head_office') { ?>
            <li>
              <a href="<?= site_url('franchise/create_regional_office_login')?>"><span class="fa fa-desktop"></span> <span class="xn-text">Regional Office Login</span></a>
            </li>

            <li>
              <a href="<?= site_url('franchise/create_branch_office_login')?>"><span class="fa fa-desktop"></span> <span class="xn-text">Branch Office Login</span></a>
            </li>
            <li>
              <a href="<?= site_url('franchise/franchise_offer_ads')?>"><span class="fa fa-desktop"></span> <span class="xn-text">Offer Ads</span></a>
            </li>
          <!--   <li>
              <a href="<?= site_url('franchise/franchise_staff_details')?>"><span class="fa fa-desktop"></span> <span class="xn-text">Staff Details Report</span></a>
            </li> -->
          <?php } ?>

            <li class="xn-openable">
              <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Advertisement</span></a>   
              <ul>            
                  <li><a href="<?php echo site_url('franchise/create_header_ads_head_office') ?>"><span class="fa fa-caret-right"></span>Header Ads</a></li>
                  <?php if ($groups == 'branch') { ?>
                    <li><a href="<?php echo site_url('franchise/create_header_ads_branch_office') ?>"><span class="fa fa-caret-right"></span>Header Ads -1</a></li>
                  <?php } ?>
              </ul>
            </li>

            <li class="xn-openable">
              <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Profile</span></a>   
              <ul>            
                <li><a href="#"><span class="fa fa-caret-right"></span>Admin Details</a></li>
                <li><a href="#"><span class="fa fa-caret-right"></span>Office Address</a></li>
                <li>
                  <a href="<?php echo site_url('franchise/terms_conditions_view') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Terms and Conditions</span></a>
                </li>
                <li>
                  <a href="<?php echo site_url('admin_controller/change_password_branches_dashboard') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Change Password</span></a>
                </li>
              </ul>
            </li>
            
            <li>
              <a href="#"><span class="fa fa-desktop"></span> <span class="xn-text">Franchise Wallet</span></a>
            </li>
            <li>
              <a href="#"><span class="fa fa-desktop"></span> <span class="xn-text">Shipping Details</span></a>
            </li>
            <li>
              <a href="#"><span class="fa fa-desktop"></span> <span class="xn-text">Accounts</span></a>
            </li>
            <li>
              <a href="#"><span class="fa fa-desktop"></span> <span class="xn-text">Client Database</span></a>
            </li>
            <li>
              <a href="#"><span class="fa fa-desktop"></span> <span class="xn-text">Public Database</span></a>
            </li>

          <li>
            <a href="#" class="mb-control" data-box="#mb-signout"><span class="fa fa-sitemap"></span> <span class="xn-text">Logout</span></a>
          </li>

        </ul>
      </div>
    </div>
  </div>
<style type="text/css">
  .x-navigation>li>ul>li>ul>li>a{
    background: #074907;
  }
  .disabled {
    pointer-events:none;
    opacity:0.6;    
  }
  .x-navigation{
    background: #578258;
  }
  .x-navigation li>a{
    color: #f5f5f5;
  }
  .x-navigation li>a .fa, .x-navigation li>a .glyphicon{
    color: #f5f5f5;
  }
  .x-navigation>li.xn-logo>a:first-child{
    background: #a94442;
    font-size: 18px;
  }
</style>