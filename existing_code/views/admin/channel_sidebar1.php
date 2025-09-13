<?php 
  $users = $this->ion_auth->user()->row();
?>
<div class="page-container">
  <div class="page-sidebar page-sidebar-fixed scroll mCustomScrollbar _mCS_1 mCS-autoHide" style="height: 150px;">
    <div id="mCSB_1" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" tabindex="0">
      <div id="mCSB_1_container" class="mCSB_container" style="position:relative; top:0; left:0;" dir="ltr">
        <ul class="x-navigation">
          <li class="xn-logo">
            <a href="<?php echo base_url('admin_controller') ?>">My Media Dashboard</a>
            <a href="#" class="x-navigation-control"></a>
          </li>
          
           <li>
            <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Subscription</span></a>
            </li>
            <li>
              <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Followers</span></a>
            </li>
            <li>
              <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Likes</span></a>
            </li>
            <li>
              <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Un-Likes</span></a>
            </li>
            <li>
              <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Earnings</span></a>
            </li>
            <li>
              <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Comments</span></a>
            </li>

          </ul>
        </div>
      </div>
    </div>

