<?php 
  $users = $this->ion_auth->user()->row();
  $this->db->where('user_id',$users->id);
  $clientReg =  $this->db->get('client_registration')->row();
  $disabled = 'disabled';
  if ($clientReg->status == 1) {
    $disabled = '';
  }
  $userid = $this->ion_auth->user()->row()->id;
  $groups = $this->ion_auth->get_users_groups($userid)->row()->name;
?>
<div class="page-container">
  <div class="page-sidebar page-sidebar-fixed scroll mCustomScrollbar _mCS_1 mCS-autoHide" style="height: 150px;">
    <div id="mCSB_1" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" tabindex="0">
      <div id="mCSB_1_container" class="mCSB_container" style="position:relative; top:0; left:0;" dir="ltr">
        <ul class="x-navigation">
          <li class="xn-logo">
            <a style="height: 75px;" href="<?php echo base_url('admin_controller') ?>">
              <?php 
              if (!empty($clientReg->name_of_the_organization)) {
                echo $clientReg->name_of_the_organization;
              }else{
                 echo $users->first_name;
              } ?>
               </a>
            <a href="#" class="x-navigation-control"></a>
          </li>

          <li>
            <a href="<?= site_url('dashboard')?>"><span class="fa fa-desktop"></span> <span class="xn-text">Dashboard</span></a>
          </li>
            <!--  My Media -->
          <?php if ($users->group_id == 8) { ?>

            <?php 
              if ($groups == 'client_god') { ?>
                <li class="xn-openable <?php echo $disabled ?>">
                  <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text"> Profile</span></a>   
                  <ul>
                    <li><a href="<?php echo site_url('client_controller/mygod_profile_details') ?>"><span class="fa fa-caret-right"></span>Details</a></li>
                    <li><a href="<?= site_url('client_controller/mygodsocial_link')?>"><span class="fa fa-caret-right"></span>Social Media Links</a></li>
                    <li><a href="<?= site_url('client_controller/mygodlivelink')?>"><span class="fa fa-caret-right"></span>Live Link</a></li>
                    <li><a href="<?= site_url('client_controller/mygod_admin_details')?>"><span class="fa fa-caret-right"></span>Admin Details</a></li>
                    <li>
                    <a href="<?php echo site_url('admin_controller/change_password') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Change password</span></a>
                    </li>
                  </ul>
                </li>
                <li class="<?php echo $disabled ?>">
                  <a href="<?php echo site_url('client_controller/client_god_timings') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> Timings </span></a>
                </li>
                <li class="<?php echo $disabled ?>">
                  <a href="<?php echo site_url('client_controller/client_god_desciption/'.'Prayer_Pooja Timings') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> Prayer / Pooja Timings </span></a>
                </li>
                <li class="<?php echo $disabled ?>">
                  <a href="<?php echo site_url('client_controller/client_god_desciption/'.'Notice Board') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> Notice board</span></a>
                </li>

                <li class="<?php echo $disabled ?>">
                  <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text"> Offline Page</span></a>
                </li>
                <li class="<?php echo $disabled ?>">
                  <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text"> Live Page</span></a>
                </li>
                
                <li class="<?php echo $disabled ?>">
                  <a href="<?php echo site_url('client_controller/client_god_today_photo') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> Today Photo</span></a>
                </li>
                <li class="<?php echo $disabled ?>">
                  <a href="<?php echo site_url('client_controller/client_god_event') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> Events</span></a>
                </li>
                <li class="<?php echo $disabled ?>">
                  <a href="<?php echo site_url('client_controller/client_god_gallery') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> Gallery</span></a>
                </li>
                <li class="<?php echo $disabled ?>">
                  <a href="<?php echo site_url('client_controller/client_god_must_visit/') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> Places to See</span></a>
                </li>
                <li class="<?php echo $disabled ?>">
                  <a href="<?php echo site_url('client_controller/client_god_desciption/'.'Food') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> Food</span></a>
                </li>
                <li class="<?php echo $disabled ?>">
                  <a href="<?php echo site_url('client_controller/client_god_desciption/'.'Accomadation') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> Accomadation</span></a>
                </li>
                <li class="<?php echo $disabled ?>">
                  <a href="<?php echo site_url('client_controller/client_god_desciption/'.'Other Facilities') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> Other Facilities</span></a>
                </li>
                <li class="<?php echo $disabled ?>">
                  <a href="<?php echo site_url('client_controller/client_god_how_to_reach/') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> How to Reach</span></a>
                </li>
                <li class="<?php echo $disabled ?>">
                  <a href="<?php echo site_url('client_controller/client_god_nearest_places/') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> Nearest Places</span></a>
                </li>
                <li class="<?php echo $disabled ?>">
                  <a href="<?php echo site_url('client_controller/client_god_desciption/'.'Donation Details') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> Donation Details</span></a>
                </li>

                <li class="<?php echo $disabled ?>">
                  <a href="<?php echo site_url('client_controller/client_god_desciption/'.'History_Details') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> History / Details</span></a>
                </li>
                <li class="<?php echo $disabled ?>">
                  <a href="<?php echo site_url('client_controller/client_god_desciption/'.'Fee Details') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> Fee Details</span></a>
                </li>
                <li class="<?php echo $disabled ?>">
                  <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text"> Prayer</span></a>
                </li>
                <li class="<?php echo $disabled ?>">
                  <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text"> Reviews</span></a>
                </li>
                <li class="<?php echo $disabled ?>">
                  <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text"> Video Reviews</span></a>
                </li>
                <li class="<?php echo $disabled ?>">
                  <a href="<?php echo site_url('client_controller/client_god_photos/') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> Photos</span></a>
                </li>


              <?php }else{ ?>

                <li class="xn-openable <?php echo $disabled ?>">
                  <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text"> Profile</span></a>   
                  <ul>
                    <li><a href="#"><span class="fa fa-caret-right"></span>Edit Profile</a></li>
                    <li>
                    <a href="<?php echo site_url('admin_controller/change_password') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Change password</span></a>
                    </li>
                  </ul>
                </li> 

                <li class="<?php echo $disabled ?>">
                  <a href="<?php echo site_url('client_controller/media_dashboard') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> Create Media </span></a>
                </li>

                <li class="<?php echo $disabled ?>">
                  <a href="<?php echo site_url('client_controller/my_channel_list') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> My Channel List </span></a>
                </li>
              <?php }
            ?>
          <?php }else if($users->group_id == 37){ ?>
            <li class="xn-openable <?php echo $disabled ?>">
              <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Profile</span></a>   
              <ul>
                <li><a href="<?php echo site_url('client_controller/client_name') ?>"><span class="fa fa-caret-right"></span> Name</a></li>
                <li><a href="<?php echo site_url('client_controller/client_document') ?>"><span class="fa fa-caret-right"></span> Documents</a></li>
                <li><a href="#"><span class="fa fa-caret-right"></span> Address</a></li>
                <li>
                <a href="<?php echo site_url('admin_controller/change_password') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Change password</span></a>
                </li>
              </ul>
            </li>

            <li class="<?php echo $disabled ?>">
              <a href="<?php echo site_url('client_controller/create_services/'.$users->group_id) ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Create Services</span></a>
            </li>
            <li class="<?php echo $disabled ?>">
              <a href="<?php echo site_url('client_controller/view_listservices/'.$users->group_id) ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Services List</span></a>
            </li>

            <li class="<?php echo $disabled ?>">
              <a href="<?php echo site_url('client_controller/validate_services/'.$users->group_id) ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Validity</span></a>
            </li>
            <li class="<?php echo $disabled ?>">
              <a href=""><span class="fa fa-bookmark"></span><span class="xn-text">My Orders</span></a>
            </li>
            <li class="<?php echo $disabled ?>">
              <a href=""><span class="fa fa-bookmark"></span><span class="xn-text">Payments</span></a>
            </li>

          <?php }else if($users->group_id == 11){ ?>
            <li class="xn-openable">
              <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Profile</span></a>   
              <ul>
                <li><a href="<?php echo site_url('client_controller/client_name') ?>"><span class="fa fa-caret-right"></span> Name</a></li>
                <li><a href="<?php echo site_url('client_controller/client_document') ?>"><span class="fa fa-caret-right"></span> Documents</a></li>
                <li><a href="#"><span class="fa fa-caret-right"></span> Address</a></li>
                <li>
                <a href="<?php echo site_url('admin_controller/change_password') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Change password</span></a>
                </li>
              </ul>
            </li>

            <li>
              <a href="<?php echo site_url('client_controller/create_client_shop') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Create New Shop</span></a>
            </li>

            <li>
              <a href="<?php echo site_url('client_controller/view_created_client_shop') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">View Created Shop</span></a>
            </li>
          <?php } else{ 
              $disabled = 'disabled';
              if ($clientReg->status == 1) {
                $disabled = '';
              }
            ?>
            <li class="xn-openable <?php echo $disabled ?> ">
              <a  href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Profile</span></a>   
              <ul>
                <li><a href="<?php echo site_url('client_controller/unions_details') ?>"><span class="fa fa-caret-right"></span>Logo and Name</a></li>
                <li><a href="<?php echo site_url('client_controller/client_about_us') ?>"><span class="fa fa-caret-right"></span> About us</a></li>
                <li><a href="<?php echo site_url('client_controller/client_document') ?>"><span class="fa fa-caret-right"></span> Documents</a></li>
                <li><a href="<?php echo site_url('client_controller/client_admin_details') ?>"><span class="fa fa-caret-right"></span>  Admin details</a></li>
                <li><a href="<?php echo site_url('client_controller/client_awards') ?>"><span class="fa fa-caret-right"></span> Awards</a></li>
                <li><a href="<?php echo site_url('client_controller/client_news_letter') ?>"><span class="fa fa-caret-right"></span> News letter</a></li>
                <li><a href="<?php echo site_url('client_controller/client_objectives') ?>"><span class="fa fa-caret-right"></span> Objectivies</a></li>
                <li>
                  <li><a href="#"><span class="fa fa-caret-right"></span> Annual Report</a></li>
                <li>
                <a href="<?php echo site_url('client_controller/change_password_client') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Change password</span></a>
                </li>
              </ul>
            </li>  
           <li class="xn-openable <?php echo $disabled ?> ">
            <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Application Settings</span></a>   
            <ul>
              <li>
                <a href="<?php echo site_url('admin_controller/member_create_form') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Create Application</span></a>
              </li>
              <li>
                <a href="<?php echo site_url('client_controller/enabled_for_public') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Enabled for public</span></a>
              </li>
              <li>
                <a href="<?php echo site_url('client_controller/member_validity') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Members validity</span></a>
              </li>
              <li>
                <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Members Fees</span></a>
              </li>
            </ul>
          </li>

          <li class="<?php echo $disabled ?>">
            <a href="<?php echo site_url('admin_controller/director_registration_index') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Directors</span></a>
          </li>
          <li class="<?php echo $disabled ?>">
            <a href="<?php echo site_url('admin_controller/header_leader_index') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Heads/Leaders</span></a>
          </li>
          <li class="<?php echo $disabled ?>">
            <a href="<?php echo site_url('admin_controller/union_staff_index') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Staff</span></a>
          </li>
          <li class="<?php echo $disabled ?>">
            <a href="<?php echo site_url('admin_controller/member_registration_index') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Members</span></a>
          </li>
          
          <li class="<?php echo $disabled ?>">
            <a href="<?php echo site_url('client_controller/union_application') ?>"><span class="fa fa-bookmark"></span><span class="xn-text"> Application</span></a>
          </li>

          <li class="<?php echo $disabled ?>">
            <a href="<?php echo site_url('client_controller/id_cards_view') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">ID Cards</span></a>
          </li>
          <li class="<?php echo $disabled ?>">
            <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Letter-head</span></a>
          </li>
          <li class="<?php echo $disabled ?>">
            <a href="<?php echo site_url('client_controller/client_union_news') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">News</span></a>
          </li>
         
          <li class="<?php echo $disabled ?>">
            <a href="<?php echo site_url('client_controller/client_union_notice') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Notice</span></a>
          </li>
          <li class="<?php echo $disabled ?>">
            <a href="<?php echo site_url('client_controller/client_union_invitation') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Invitations</span></a>
          </li>
          <li class="<?php echo $disabled ?>">
            <a href="<?php echo site_url('client_controller/client_union_press_note') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Press Note</span></a>
          </li>
          <li class="<?php echo $disabled ?>">
            <a href="<?php echo site_url('client_controller/client_union_meeting') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Meeting</span></a>
          </li>
          <li class="<?php echo $disabled ?>">
            <a href="<?php echo site_url('client_controller/client_union_live_streaming') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Live Streaming</span></a>
          </li>


          <li class="<?php echo $disabled ?>">
            <a href="<?php echo site_url('client_controller/client_union_sms_service') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">SMS Service</span></a>
          </li>

          <li class="<?php echo $disabled ?>">
            <a href="<?php echo site_url('client_controller/client_union_email_service') ?>"><span class="fa fa-bookmark"></span><span class="xn-text">Email Service</span></a>
          </li>
          <li class="<?php echo $disabled ?>">
            <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Payment Details</span></a>
          </li>

          <li class="xn-openable <?php echo $disabled ?>">
            <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Design</span></a>   
            <ul>
              <li>
                <a href="#"><span class="fa fa-desktop"></span> <span class="xn-text">ID Card</span></a>
              </li>
              <li>
                <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Certificate</span></a>
              </li>
              <li>
                <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Letterhead</span></a>
              </li>
              <li>
                <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Visiting Card</span></a>
              </li>
              <li>
                <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Invoice</span></a>
              </li>
              <li>
                <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Medals</span></a>
              </li>
              <li>
                <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Posters</span></a>
              </li>
              <li>
                <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Callander</span></a>
              </li>
            </ul>
          </li>

          <li class="<?php echo $disabled ?>">
            <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Medal</span></a>
          </li>
          <li class="<?php echo $disabled ?>">
            <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text">Certificates</span></a>
          </li>
          <li class="<?php echo $disabled ?>">
            <a href="#"><span class="fa fa-bookmark"></span><span class="xn-text"> Visibility</span></a>
          </li>
          <li class="xn-openable <?php echo $disabled ?>">
            <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Footer</span></a>   
            <ul>
               <li>
                <a href="<?php echo site_url('client_controller/client_about') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">About Union</span></a>
              </li>
              <li>
                <a href="#"><span class="fa fa-desktop"></span> <span class="xn-text">Gallery</span></a>
              </li>
              <li>
                <a href="#"><span class="fa fa-desktop"></span> <span class="xn-text">Social Media Link</span></a>
              </li>
              <li>
                <a href="<?php echo site_url('admin_controller/tnc') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Terms And Conditions</span></a>
              </li>
              <li>
                <a href="<?php echo site_url('admin_controller/pnp') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Privacy and Policy</span></a>
              </li>
            </ul>
          </li>

          <?php } ?>
          <li class="xn-openable <?php echo $disabled ?>">
            <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Support</span></a>   
            <ul>
              <li><a href="#"><span class="fa fa-caret-right"></span>Enquiry</a></li>
              <li><a href="#"><span class="fa fa-caret-right"></span> Feedback and Suggestions</a></li>
               <?php 
                if ($groups != 'client_god') { ?>
                  <li><a href="#"><span class="fa fa-caret-right"></span> Live Chat</a></li>
                <?php } ?>
                <?php 
                if ($groups == 'client_god') { ?>
                  <li><a href="#"><span class="fa fa-caret-right"></span>Chat Box</a></li>
                <?php } ?>
            </ul>
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
</style>