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
            <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Edit Channel</span></a>
          </li>
          <?php if ($mediaType == 'tv' && $mediaType == 'radio') { ?>
            <li>
              <a href="<?php echo site_url('client_controller/live_url_form/'.$mediaType.'/'.$get_media_type->id) ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Live URL</span></a>
            </li>
          <?php } ?>
          
          <li>
            <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Social Icon</span></a>
          </li>
          <li>
            <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Terms & Conditions</span></a>
          </li>
          <li>
            <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Privacy & policy</span></a>
          </li>
          <li>
            <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Enquiry</span></a>
          </li>
          <li>
            <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Address</span></a>
          </li>
          </ul>
        </div>
      </div>
    </div>

