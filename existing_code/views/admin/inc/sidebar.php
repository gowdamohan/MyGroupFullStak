<?php 
  $users = $this->ion_auth->user()->row();
?>
<div class="page-container">
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
          <?php if ($users->group_id == 0) { ?>
          <li class="xn-openable">
            <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Profile</span></a>   
            <ul>
            
                <li><a href="<?php echo site_url('admin_controller/group') ?>"><span class="fa fa-caret-right"></span>Group</a></li>
                <li><a href="<?php echo site_url('admin_controller/create') ?>"><span class="fa fa-caret-right"></span> Created</a></li>
                <li><a href="<?php echo site_url('admin_controller/advertise') ?>"><span class="fa fa-caret-right"></span> Advertise</a></li>
                <li><a href="<?php echo site_url('admin_controller/user_group_creation') ?>"><span class="fa fa-caret-right"></span> Group Account</a></li>
               <li><a href="<?php echo site_url('admin_controller/popup_add') ?>"><span class="fa fa-caret-right"></span> Popup Add</a></li>
               <li>
                <a href="<?php echo site_url('admin_controller/change_password') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Change password</span></a>
              </li>
            </ul>
          </li>  
           <?php } ?>

          <?php if ($users->group_id != 0) { ?>
          <li class="xn-openable">
            <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Advertisement</span></a>   
            <ul>
               <li><a href="<?php echo site_url('admin_controller/popup_add') ?>"><span class="fa fa-caret-right"></span> Popup Add</a></li>
               <li>
                <a href="#"><span class="fa fa-desktop"></span> <span class="xn-text">Header Add</span></a>
              </li>
            </ul>
          </li>  
           <?php } ?>
          <?php if ($users->group_id == 0) { ?>
          <li class="xn-openable">
            <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Content</span></a>   
            <ul>
              <li class="xn-openable">
                  <a href="tables.html"><span class="fa fa-table"></span> <span class="xn-text">Country List</span></a>
                  <ul>                            
                    <li><a href="<?php echo site_url('country_controller/continent')?>"><span class="fa fa-caret-right"></span>Continent</a></li>
                    <li><a href="<?php echo site_url('country_controller/country')?>"><span class="fa fa-caret-right"></span> Country</a></li>
                    <li><a href="<?php echo site_url('country_controller/state')?>"><span class="fa fa-caret-right"></span> State</a></li>
                    <li><a href="<?php echo site_url('country_controller/district')?>"><span class="fa fa-caret-right"></span> District</a></li>                     
                  </ul>
              </li>
              <li><a href="<?php echo site_url('admin_controller/language') ?>"><span class="fa fa-caret-right"></span> Language</a></li>
              <li><a href="<?php echo site_url('admin_controller/education') ?>"><span class="fa fa-caret-right"></span> Education</a></li>
              <li><a href="<?php echo site_url('admin_controller/profession') ?>"><span class="fa fa-caret-right"></span> Profession</a></li>
            </ul>
          </li>
          <?php } ?>
          <?php if ($users->group_id == 0) { ?>
            <li class="xn-openable">
              <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Create Category</span></a>   
              <ul>
                <li><a href="<?php echo site_url('admin_controller/create_category/'.'Mymedia') ?>"><span class="fa fa-desktop"></span><span class="xn-text">My Media</span></a></li>
                <!-- <li><a href="<?php echo site_url('admin_controller/create_category/'.'Mydiary') ?>"><span class="fa fa-desktop"></span><span class="xn-text">My Dairy</span></a></li> -->
                <li><a href="<?php echo site_url('admin_controller/create_category/'.'Myjoy') ?>"><span class="fa fa-desktop"></span><span class="xn-text">My Joy</span></a></li>
                <li><a href="<?php echo site_url('admin_controller/create_category/'.'Myshop') ?>"><span class="fa fa-desktop"></span><span class="xn-text">My Shop</span></a></li>
                <li><a href="<?php echo site_url('admin_controller/create_category/'.'Myfriend') ?>"><span class="fa fa-desktop"></span><span class="xn-text">My Friend</span></a></li>
                <li><a href="<?php echo site_url('admin_controller/create_category/'.'Myunions') ?>"><span class="fa fa-desktop"></span><span class="xn-text">My Unions</span></a></li>
                <li><a href="<?php echo site_url('admin_controller/create_category/'.'Mybiz') ?>"><span class="fa fa-desktop"></span><span class="xn-text">My Biz</span></a></li>
                <li><a href="<?php echo site_url('admin_controller/create_category/'.'Mytv') ?>"><span class="fa fa-desktop"></span><span class="xn-text">My TV</span></a></li>
                <li><a href="<?php echo site_url('admin_controller/create_category/'.'Myneedy') ?>"><span class="fa fa-desktop"></span><span class="xn-text">My Needy</span></a></li>
              </ul>
            </li>
          <?php } ?>

         
          <li class="xn-openable">
            <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Footer</span></a>   
            <ul>
               <li>
                <a href="<?php echo site_url('admin_controller/about_us') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">About Us</span></a>
              </li>
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

           <?php if ($users->username == 'mymedia') { ?>
            <li>
              <a href="<?php echo site_url('admin_controller/add_media_category/'.$users->group_id.'/'.'tv') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">TV</span></a>
              <a href="<?php echo site_url('admin_controller/add_media_category/'.$users->group_id.'/'.'radio') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Radio</span></a>
              <a href="<?php echo site_url('admin_controller/add_media_category/'.$users->group_id.'/'.'news') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">News</span></a>
              <a href="<?php echo site_url('admin_controller/add_media_category/'.$users->group_id.'/'.'magazine') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Magazine</span></a>
              <a href="<?php echo site_url('admin_controller/add_media_category/'.$users->group_id.'/'.'webnews') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Webnews</span></a>
              <a href="<?php echo site_url('admin_controller/add_media_category/'.$users->group_id.'/'.'youtube') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Youtube</span></a>
              <a href="<?php echo site_url('admin_controller/add_media_category/'.$users->group_id.'/'.'mygod') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">My-God</span></a>
            </li>
          <?php } ?>
          
          <?php if ($users->username == 'myneedy') { ?>
            <li>
              <a href="<?php echo site_url('admin_controller/add_needy_category/'.$users->group_id.'/'.'Doorstep') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Doorstep</span></a>
              <a href="<?php echo site_url('admin_controller/add_needy_category/'.$users->group_id.'/'.'Centers') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Centers</span></a>
              <a href="<?php echo site_url('admin_controller/add_needy_category/'.$users->group_id.'/'.'Manpower') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Manpower</span></a>
              <a href="<?php echo site_url('admin_controller/add_needy_category/'.$users->group_id.'/'.'Online') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Online</span></a>
              <a href="<?php echo site_url('admin_controller/add_needy_category/'.$users->group_id.'/'.'Myhelp') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Myhelp</span></a>
              <a href="<?php echo site_url('admin_controller/needy_my_orders/'.$users->group_id.'/'.'Myorders') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Myorders</span></a>
            </li>
          <?php } ?>


          <li class="xn-openable">
            <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Data Base</span></a>   
            <ul>
              <li>
                <a href="#>"  class="mb-control" data-box="#mb-signout"><span class="fa fa-bookmark"></span><span class="xn-text">Client Database</span></a>
              </li>

              <li>
                <a href="<?php echo site_url('admin_controller/public_database') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Public Database</span></a>
              </li>
               <li>
                <a href="#"  class="mb-control" data-box="#mb-signout"><span class="fa fa-bookmark"></span><span class="xn-text">Public Login Chart</span></a>
              </li>
              <li>
                <a href="<?php echo site_url('admin_controller/apply_database') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Carerr Applications</span></a>
              </li>
            </ul>
          </li>

          <li class="xn-openable">
            <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Supports</span></a>   
            <ul>
              <li>
                <a href="<?= site_url('admin_controller/feed_back_users')?>"><span class="fa fa-desktop"></span> <span class="xn-text">Feedback and Suggestions</span></a>
              </li>
            </ul>
          </li>
          <?php if ($users->username == 'myunions') { ?>
            <li>
              <a href="<?php echo site_url('admin_controller/category') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Category</span></a>
            </li>
          <?php } ?>
          <?php if ($users->username == 'myunions') { ?>
            <li>
              <a href="<?php echo site_url('admin_controller/applications_form') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Applications</span></a>
            </li>
          <?php } ?>
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
</style>