<?php 
  $users = $this->ion_auth->user()->row();
?>
<div class="page-container">
  <div class="page-sidebar page-sidebar-fixed scroll mCustomScrollbar _mCS_1 mCS-autoHide" style="height: 150px;background: #578258;">
    <div id="mCSB_1" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" tabindex="0">
      <div id="mCSB_1_container" class="mCSB_container" style="position:relative; top:0; left:0;" dir="ltr">
        <ul class="x-navigation">
          <li class="xn-logo">
            <a style="height: 52px;" href="<?php echo base_url('admin_controller') ?>"><?php echo $users->first_name ?></a>
            <a href="#" class="x-navigation-control"></a>
          </li>

          <li>
            <a href="<?= site_url('dashboard')?>"><span class="fa fa-desktop"></span> <span class="xn-text">Dashboard</span></a>
          </li>
          
          <li>
            <a href="<?= site_url('franchise/create_head_office_login')?>"><span class="fa fa-desktop"></span> <span class="xn-text">Head Office Login</span></a>
          </li>

          <li>
            <a href="<?= site_url('franchise/corporate_header_ads')?>"><span class="fa fa-desktop"></span> <span class="xn-text">Header Ads</span></a>
          </li>
          <li><a href="<?php echo site_url('franchise/popup_add') ?>"><span class="fa fa-caret-right"></span> Popup Add</a></li>

          <li><a href="<?php echo site_url('franchise/my_company_header_ads') ?>"><span class="fa fa-caret-right"></span> My Company Header Ads</a></li>

          <li><a href="<?php echo site_url('franchise/main_page_ads') ?>"><span class="fa fa-caret-right"></span>Main Page Ads</a></li>

          <li>
            <a href="#"><span class="fa fa-desktop"></span> <span class="xn-text">Profile</span></a>
          </li>

          <li>
            <a href="<?php echo site_url('admin_controller/application_details') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Applcation details</span></a>
          </li>

          <li>
            <a href="<?php echo site_url('franchise/terms_conditions') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Terms and Conditions</span></a>
          </li>


          <li class="xn-openable">
            <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Footer</span></a>   
            <ul>
               <li>
                <a href="<?php echo site_url('admin_controller/about_us') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">About Us</span></a>
              </li>
              <?php
              $menus = array(
                'awards' =>'Awards', 
                'newsroom' =>'Newsroom', 
                'events' =>'Events', 
                'careers' =>'Careers', 
                'clients' =>'Clients', 
                'milestones' =>'Milestones', 
                'testimonials' =>'Testimonials', 
              );
              ?>
              <?php foreach ($menus as $key => $val) { ?>
                <li>
                  <a href="<?php echo site_url('admin_controller/footer_same_page/'.$key) ?>"><span class="fa fa-desktop"></span> <span class="xn-text"><?php echo $val ?></span></a>
                </li>
              <?php } ?>
              <li>
                <a href="<?php echo site_url('admin_controller/gallery') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Gallery</span></a>
              </li>
              <!--  <li>
                <a href="<?php echo site_url('admin_controller/copy_rights') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Copy Rights</span></a>
              </li> -->
              <li>
                <a href="<?php echo site_url('admin_controller/contact_us') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Contact Us</span></a>
              </li>
              <li>
                <a href="<?= site_url('admin_controller/social_link')?>"><span class="fa fa-desktop"></span> <span class="xn-text">Social Media Link</span></a>
              </li>
              <li>
                <a href="<?php echo site_url('admin_controller/tnc') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Terms And Conditions</span></a>
              </li>
              <li>
                <a href="<?php echo site_url('admin_controller/pnp') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Privacy and Policy</span></a>
              </li>
              <li>
                <a href="#"><span class="fa fa-desktop"></span> <span class="xn-text">Instructions</span></a>
              </li>
            </ul>
          </li>

          <li>
            <a href="<?php echo site_url('admin_controller/public_database') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Public database</span></a>
          </li>
          <li>
            <a href="#"><span class="fa fa-desktop"></span> <span class="xn-text">Client database</span></a>
          </li>

          <li>
            <a href="<?php echo site_url('admin_controller/franchise_application') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Franchise Application</span></a>
            <a href="<?php echo site_url('admin_controller/job_application') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Job Application</span></a>
            <a href="<?php echo site_url('admin_controller/enquiry_form') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Enquiry Form</span></a>
            <a href="#"><span class="fa fa-desktop"></span> <span class="xn-text">Technical Support</span></a>
          </li>


          <li class="xn-openable">
            <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Supports</span></a>   
            <ul>
              <li>
                <a href="<?= site_url('admin_controller/feed_back_suggetion')?>"><span class="fa fa-desktop"></span> <span class="xn-text">Feedback and Suggestions</span></a>
                <a href="<?= site_url('admin_controller/feed_back_users')?>"><span class="fa fa-desktop"></span> <span class="xn-text">Chat with Us</span></a>
              </li>
            </ul>
          </li>


          <li>
            <a href="<?php echo site_url('admin_controller/change_password_head_dashboard') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Change Password</span></a>
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