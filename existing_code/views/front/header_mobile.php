<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="<?php echo base_url()?>assets/front/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/front/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/css/fontawesome/font-awesome.min.css">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    <script src="<?php echo base_url()?>assets/front/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" type="text/css"  href="<?php echo base_url();?>assets/back_end/css/parsley.css"/>
    <link rel="stylesheet" type="text/css"  href="<?php echo base_url();?>assets/back_end/js/parsley.js"/>
  </head>

<?php $switchMode = $this->session->userdata('switch_mode'); ?>
<body class="<?php if($switchMode == 1) echo 'dark-mode' ?>">

<?php $this->load->view('front/_main_page_script.php') ?>

<nav class="navbar navbar-expand-lg fixed-top navbar-dark" id="navabarpadding" style="background:#057284; padding-right: 0; padding-left:0 ">
  <div class="table-responsive">
    <ul class="navbar-nav" id="top_myapps" style="width:max-content;">
    </ul>
  </div>

  <div class="header-logo" style="background:#fff; width: 100%; ">
    <div class="container">
        <!-- left user profile details -->
        <div class="d-flex align-items-start justify-content-end" style="padding:0px;">
          <div class="wrapper" style="padding:0">
            <div class="btn" style="width: 100%; padding: 0;">
              <?php  $user = $this->ion_auth->user()->row(); ?>
                <?php if (!empty($user)) { ?>
                  <a  data-toggle="modal" onclick="open_setting_profile()"  data-target="#profileModal1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php
                    $img = base_url().'assets/front/logo.jpg';
                    if (!empty($user)) {
                        if ($user->profile_img !='') {
                          $img = $this->filemanager->getFilePath($user->profile_img);
                        }
                    } ?>
                    <img style="width:24px;height: 22px; border-radius: 50px;" class="rounded-circle" src="<?php echo $img ?>">
                  </a>
                <?php }else{ ?>
                  <i onclick="register_user()" style="font-size:20px "  aria-hidden="true" class="fa fa-user"></i>
                <?php } ?>
            </div>
          </div>
          <a style="margin-left:0.5rem" href="<?php echo site_url('group/'.$groupname) ?>" >
            <img class="brand-logo" style="width: 70px;" src="<?php echo base_url().$logo->name_image ?>" alt="">
          </a>
        </div>

         <!-- right user group details -->
        <div class="d-flex align-items-end justify-content-end" style="padding:0px;">
          <div class="wrapper" style="padding:0">
            <div class="btn" style="width: 100%; padding: 0;">
              <?php
                $switchMode = $this->session->userdata('switch_mode');
                $className = 'fa-adjust';
                  if ($switchMode) {
                    $className = 'fa-sun-o';
                  }
               ?>
              <i onclick="dark_light_mode()" id="darMode" class="fa <?php echo $className ?>" style="font-size:20px; margin-right: 1rem; " aria-hidden="true"></i>

              <?php  $user = $this->ion_auth->user()->row(); ?>
              <?php if (!empty($user)) { ?>
                <a  data-toggle="modal" onclick="open_group_setting_profile()"  data-target="#group_settings" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <?php
                  $img = base_url().'assets/front/logo.jpg';
                  if (!empty($user)) {
                      if ($user->profile_img !='') {
                        $img = $this->filemanager->getFilePath($user->profile_img);
                      }
                  } ?>

                <img style="width:24px;height: 22px;border-radius: 50px;" class="rounded-circle" src="<?php echo base_url().$logo->logo ?>">
                </a>
              <?php }else{ ?>
                <i onclick="register_user()" style="font-size:20px "  aria-hidden="true" class="fa"><img class="rounded-circle" style="width: 22px;" src="<?php echo base_url().$logo->logo ?>" alt=""></i>
              <?php } ?>

            </div>
          </div>
        </div>

    </div>
  </div>
</nav>


<div class="position-relative">
  <div class="align-items-center justify-content-center text-light">
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" style="margin-top: 24%;margin-bottom: 4px; ">
      <ol class="carousel-indicators" id="ol-carosual">
       
      </ol>
      <div class="carousel-inner" id="carouselInner">
       

      </div>
      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </div>
</div>

<script type="text/javascript">
  function open_setting_profile() {
  
    window.setTimeout(function () {
      $("#profileModal1").css('padding','0');
    }, 500);

  }

  function open_group_setting_profile() {
    $('#group_settings').modal('show');
  }
</script>

<div class="modal fade" id="group_settings" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog m-0" style="height:auto;">
    <div class="modal-content" style="height:auto;">
      <div class="modal-header" style="padding: 15px 30px; border: none; background: #17a2b8; color: #fff; ">
        <img style="width: 35px;" src="<?php echo base_url().$logo->logo ?>" alt="">
        <span><img style="width: 100px;" src="<?php echo base_url().$logo->name_image ?>" ></span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span style="color: #fff" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style=" background: #4c4444; color: #fff;">

        <?php if (!empty($user)) { ?>

          <div class="row" id="linebotton">
            <div class="col-12" style="border-right:1px solid grey;">
              <p><strong>ID : </strong> <?php echo $user->username ?></p>
            </div>
              
            <span style="font-size: 30px;margin-left: 8rem;color: #17a2b8;font-weight: 600;margin-top: 1rem;"><?php echo $groupname ?></span>
            <div class="col-12" style="border-right:1px solid grey;">
              <p id="legalonchange">Legal <i  style="font-size:24px; float:right;" class="fa">&#xf105;</i></p>
            </div>

            <div class="col-12" id="tnc" style="display: none;">
              <p><a href="<?php echo site_url('home/tnc_view/'.$groupname) ?>">Terms and Conditions</a></p>
            </div>

            <div class="col-12" id="pnp" style="display: none;">
              <p><a href="<?php echo site_url('home/pnp_view/'.$groupname) ?>">Privicy And Policy</a></p>
            </div>

            <div class="col-12" style="border-right:1px solid grey;">
              <p id="helponchange">Help & Support <i  style="font-size:24px; float:right;" class="fa">&#xf105;</i></p>
            </div>

            <div class="col-12" id="fns" style="display: none;">
              <p><a href="<?php echo site_url('home/feedback_user/'.$groupname) ?>">Feedback and Suggestions </a></p>
            </div>

            <div class="col-12" id="lc" style="display: none;">
              <p><a href="<?php echo site_url('home/pnp_view/'.$groupname) ?>">Live Chat</a></p>
            </div>
            <div class="col-12" id="con" style="display: none;">
              <p><a href="<?php echo site_url('home/pnp_view/'.$groupname) ?>">Contact Us</a></p>
            </div>

            <div class="col-12" style="border-right:1px solid grey;">
              <p>Reviews <i  style="font-size:24px; float:right;" class="fa">&#xf105;</i></p>
            </div>

            <div class="col-12" style="border-right:1px solid grey;">
              <p id="lastChaild">Ratings <i  style="font-size:24px; float:right;" class="fa">&#xf105;</i></p>
            </div>

           <!--  <div class="col-12" style="border-right:1px solid grey;">
              <p  id="lastChaild"><a style="color: #fff;" onclick="logout_user()" href="javascript:void(0)">Logout</a></p>
            </div> -->
            <div class="footer-social-icon" style="margin-bottom: 14px; text-align:center; width: 100%;">
              <span>Follow us</span>
                <a target="_blank" href="<?php echo (!empty($social_link[5]->url)) ? $social_link[5]->url : '#' ?>"><img class="social-icon-width" src="<?php echo base_url().'assets/front/img/social-icon/web sq.png' ?>"> </a>
                <a target="_blank" href="<?php echo (!empty($social_link[0]->url)) ? $social_link[0]->url : '#' ?>"><img class="social-icon-width" src="<?php echo base_url().'assets/front/img/social-icon/youtube sq.png' ?>"> </a>
                <a target="_blank" href="<?php echo (!empty($social_link[1]->url)) ? $social_link[1]->url : '#' ?>"><img class="social-icon-width" src="<?php echo base_url().'assets/front/img/social-icon/facebook sq.png' ?>"> </a>
                <a target="_blank" href="<?php echo (!empty($social_link[2]->url)) ? $social_link[2]->url : '#' ?>"><img class="social-icon-width" src="<?php echo base_url().'assets/front/img/social-icon/instagram sq.png' ?>"> </a>
                <a target="_blank" href="<?php echo (!empty($social_link[3]->url)) ? $social_link[3]->url : '#' ?>"><img class="social-icon-width" src="<?php echo base_url().'assets/front/img/social-icon/Twitter sq.png' ?>"> </a>
                <a target="_blank" href="<?php echo (!empty($social_link[4]->url)) ? $social_link[4]->url : '#' ?>"><img class="social-icon-width" src="<?php echo base_url().'assets/front/img/social-icon/Linkedin sq.png' ?>"> </a>
                <a target="_blank" href="<?php echo (!empty($social_link[6]->url)) ? $social_link[6]->url : '#' ?>"><img class="social-icon-width" src="<?php echo base_url().'assets/front/img/social-icon/blog.png' ?>"> </a>

            </div>
          </div>
         <?php } ?>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="profileModal1"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog m-0" style="height:auto;">
    <div class="modal-content" style="height:auto;">
      <div class="modal-header" style="border: none; background: #17a2b8; color: #fff; ">
         <?php
          $img = base_url().'assets/front/logo.jpg';
          if (!empty($user)) {
              if ($user->profile_img !='') {
                $img = $this->filemanager->getFilePath($user->profile_img);
              }
          } ?>

         <?php if (!empty($user)) { ?>
         <img onclick="$('#fileupload').click();" class="rounded-circle" id="profile_photo" style="width:34px;height: 34px; margin-right: 1rem;border-radius: 50px; background: #fff;" src="<?php echo $img ?>">
          <br>
        <?php
          $classfa = 'camera';
          if ($this->mobile_detect->isMobile()) {
            $classfa = 'mobile-camera';
          }
        ?>
          <i onclick="$('#fileupload').click();" class="fa fa-camera <?php echo $classfa ?>" aria-hidden="true"></i>
          <input hidden="hidden" type="file" id="fileupload" class="file" data-preview-file-type="jpeg" name="profile_photo" accept="image/*">
          <span id="fileuploadError" style="color:red;display: block;padding-top:5px;padding-bottom:5px;"></span>
        <?php }else{ ?>
          <img class="img-responsive img-circle" id="profile_photo" style="width:100px;height:100px" src="<?php echo $img ?>">
        <?php } ?>


        <h3 id="displaymy"> <?php echo  (!empty($user)) ? ' My '. '<span id="displayName">'.$user->display_name .'</span>' : 'Guest Account'  ?></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span style="color: #fff" aria-hidden="true">&times;</span>
        </button>

        
      </div>

       <input type="hidden" id="userIdUrl" value="<?php echo  (!empty($user)) ? $user->id : ''  ?>">

      <form class="form-horizontal" id="profile-edit_mobile" action="<?php echo site_url('home/edit_profile_mobile') ?>" method="post">
        <input type="hidden" name="user_id" id="user_id">
      </form>

      <div class="modal-header" style="padding: 0px 30px; border-bottom: 2px solid #3c3232;background: #17a2b8;line-height: 3rem;border-radius: initial;">
        <a style="color: #fff;" href="#">ID : <?php echo $user->username ?></a>
      </div>
      <div class="modal-body" style="padding: 0;">
        <div class="container" style="padding: 0;">
          <div class="row">
            <div class="col" style="padding: 0;">
              <a style="width:100%" id="editButtonMobile" class="btn btn-warning" href="javascript:void(0)">Profile</a>
            </div>
            <div class="col" style="padding: 0;">
              <a style="width:100%" class="btn btn-warning" href="#">Personal</a>
            </div>
            <div class="col" style="padding: 0;">
              <a style="width:100%" class="btn btn-warning" href="#">Address</a>
            </div>
            <div class="col" style="padding: 0;">
              <a style="width:100%" class="btn btn-warning" href="#">Billing</a>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-body" style=" background: #4c4444; color: #fff;">
        <?php if (!empty($user)) { ?>

          <span style="font-size:1.2rem;color:#fff;font-weight: 700;">MY Group</span>

          <div class="row" id="linebotton">
            <div class="col-12" style="border-right:1px solid grey;">
              <p><a style="color: #fff;" href="<?php echo base_url() ?>">Home</a></p>
            </div>
            <div class="col-12" style="border-right:1px solid grey;">
              <p style="height: 4rem;"><a style="color: #fff;" onclick="change_location_set_cookie()" href="javascript:void(0)">Set Location <i  style="font-size:24px; float:right;color:#f27474" class="fa">&#xf041;</i></a>
              <br>
              <small style="position: relative;top: -1.5rem;" id="locationSetDisplay"></small>
              </p>
            </div>

            <div class="col-12" style="border-right:1px solid grey;">
               <p id="settingsonchange_mygroup">Settings <i  style="font-size:24px; float:right;" class="fa">&#xf105;</i></p>
            </div>

            <div class="col-12" id="setSecurity" style="display: none;">
              <p><a href="#">Set Security</a></p>
            </div>
            <div class="col-12" id="changeLang" style="display: none;">
              <p><a href="#">Change Language</a></p>
            </div>
            <div class="col-12" id="changeCurr" style="display: none;">
              <p><a href="#">Change Currency</a></p>
            </div>
            <div class="col-12" id="changePass" style="display: none;">
              <p><a href="<?php echo site_url('home/profile_change_password/'.$groupname) ?>">Change Password</a></p>
            </div>

            <div class="col-12" style="border-right:1px solid grey;">
              <p id="legalonchange_mygroup">Legal <i  style="font-size:24px; float:right;" class="fa">&#xf105;</i></p>
            </div>

            <div class="col-12" id="tnc_group" style="display: none;">
              <p><a href="<?php echo site_url('home/tnc_view/'.$groupname) ?>">Terms and Conditions</a></p>
            </div>

            <div class="col-12" id="pnp_group" style="display: none;">
              <p><a href="<?php echo site_url('home/pnp_view/'.$groupname) ?>">Privicy And Policy</a></p>
            </div>

            <div class="col-12" style="border-right:1px solid grey;">
              <p id="helponchange_group">Help & Support <i  style="font-size:24px; float:right;" class="fa">&#xf105;</i></p>
            </div>

            <div class="col-12" id="fns_group" style="display: none;">
              <p><a href="<?php echo site_url('home/feedback_user/'.$groupname) ?>">Feedback and Suggestions </a></p>
            </div>

            <div class="col-12" id="lc_group" style="display: none;">
              <p><a href="<?php echo site_url('home/pnp_view/'.$groupname) ?>">Live Chat</a></p>
            </div>
            <div class="col-12" id="con_group" style="display: none;">
              <p><a href="<?php echo site_url('home/pnp_view/'.$groupname) ?>">Enquiry</a></p>
            </div>

            <div class="col-12" style="border-right:1px solid grey;">
              <p><a style="color: #fff;" href="javascript:void(0)">Share App</a></p>
            </div>
            <div class="col-12" style="border-right:1px solid grey;">
              <p><a style="color: #fff;" href="<?php echo site_url('download') ?>">Download Apps</a></p>
            </div>
            <div class="col-12" style="border-right:1px solid grey;">
              <p>Contact Us <i  style="font-size:24px; float:right;" class="fa">&#xf105;</i></p>
            </div>
            <div class="col-12" style="border-right:1px solid grey;">
              <p>
                <a style="color: #fff;" href="<?php echo site_url('home/review_ratings') ?>">Reviews and Ratings </a> <i  style="font-size:24px; float:right;" class="fa">&#xf105;</i></p>
            </div>


            <div class="col-12" style="border-right:1px solid grey;">
              <p  id="lastChaild"><a style="color: #fff;" onclick="logout_user()" href="javascript:void(0)">Logout</a></p>
            </div>

            <div class="footer-social-icon" style="margin-bottom: 14px; text-align:center; width: 100%;">
              <p style="border-bottom:none;" >Follow us</p>
              <a target="_blank" href="<?php echo (!empty($social_link[5]->url)) ? $social_link[5]->url : '#' ?>"><img class="social-icon-width" src="<?php echo base_url().'assets/front/img/social-icon/web sq.png' ?>"> </a>
              <a target="_blank" href="<?php echo (!empty($social_link[0]->url)) ? $social_link[0]->url : '#' ?>"><img class="social-icon-width" src="<?php echo base_url().'assets/front/img/social-icon/youtube sq.png' ?>"> </a>
              <a target="_blank" href="<?php echo (!empty($social_link[1]->url)) ? $social_link[1]->url : '#' ?>"><img class="social-icon-width" src="<?php echo base_url().'assets/front/img/social-icon/facebook sq.png' ?>"> </a>
              <a target="_blank" href="<?php echo (!empty($social_link[2]->url)) ? $social_link[2]->url : '#' ?>"><img class="social-icon-width" src="<?php echo base_url().'assets/front/img/social-icon/instagram sq.png' ?>"> </a>
              <a target="_blank" href="<?php echo (!empty($social_link[3]->url)) ? $social_link[3]->url : '#' ?>"><img class="social-icon-width" src="<?php echo base_url().'assets/front/img/social-icon/Twitter sq.png' ?>"> </a>
              <a target="_blank" href="<?php echo (!empty($social_link[4]->url)) ? $social_link[4]->url : '#' ?>"><img class="social-icon-width" src="<?php echo base_url().'assets/front/img/social-icon/Linkedin sq.png' ?>"> </a>
              <a target="_blank" href="<?php echo (!empty($social_link[6]->url)) ? $social_link[6]->url : '#' ?>"><img class="social-icon-width" src="<?php echo base_url().'assets/front/img/social-icon/blog.png' ?>"> </a>
            </div>

            <div class="col-12" style="border-right:1px solid grey;">
              <p>Total Users </p>
              <div class="container">
                <div class="row">
            <script type="text/javascript">
              $(document).ready(function(){
                get_location_wise_total_users();
              });
              function get_location_wise_total_users() {
                $.ajax({
                  url:'<?php echo site_url('home/get_location_wise_data') ?>',
                  type:'post',
                  success:function(result){
                    var data = $.parseJSON(result);
                    console.log(data);
                    $('#globalUser').html(data.global.globalCount);
                    $('#nationalUser').html(data.national.natioanlCount);
                    $('#nationalSelection').html(data.national.country);
                    $('#regionalUser').html(data.regional.regionalCount);
                    $('#regionalSelection').html(data.regional.state);
                    $('#localUser').html(data.local.localCount);
                    $('#localSelection').html(data.local.district);
                    $('#locationSetDisplay').html(data.national.country+' / '+data.regional.state+' / '+data.local.district);
                  }
                });
              }
            </script> 
                
                  <table class="table tableColor">
                    <tr>
                      <th>Global</th>
                      <td>Global</td>
                      <td id="globalUser"></td>
                    </tr>
                    <tr>
                      <th>National</th>
                      <td id="nationalSelection">-</td>
                      <td id="nationalUser">-</td>
                     </tr>
                    <tr>
                      <th>Regional</th>
                      <td id="regionalSelection">-</td>
                      <td id="regionalUser">-</td>  
                    </tr>
                    <tr>
                      <th>Local</th>
                      <td id="localSelection">-</td>
                      <td id="localUser">-</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>


            <div class="container">
              <div class="footer-social-icon" style="text-align: center;">
                <span>Download the App</span>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="news-app-promo__section">
                    <div style="margin-bottom: 16px;font-size: 14px;">
                      <a  href="#" style="font-size: 0.8rem;margin-bottom: 1rem; color: #fff; ">
                        <img class="social-icon-width"  style="border-radius: 20px;width: 28px; height: 28px;" src="<?php echo base_url().'assets/front/img/My app.png' ?>"> &nbsp;Mygroup
                      </a>
                    </div>

                    <div class="news-app-promo-subsection">
                      <a class="news-app-promo-subsection--link news-app-promo-subsection--playstore" href="https://play.google.com/store/apps/details?id=com.mygroup.apps" target="_parent">
                        <img class="news-app-promo__play-store " src="<?php echo base_url().'assets/front/img/play_store.png' ?>" width="130" height="auto" border="0">
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                   <div class="news-app-promo__section">
                      <div style="margin-bottom: 16px;font-size: 14px;">
                        <a  href="#" style="font-size: 0.8rem;margin-bottom: 1rem; color: #fff; ">
                          <img class="social-icon-width" style="border-radius: 20px;width: 28px; height: 28px;" src="<?php echo base_url().'assets/front/img/my partner.png' ?>"> &nbsp;Mypartner
                        </a>
                      </div>

                      <div class="news-app-promo-subsection">
                        <a class="news-app-promo-subsection--link news-app-promo-subsection--playstore" href="https://play.google.com/store/apps/details?id=com.mygroup.partner" target="_parent">
                          <img class="news-app-promo__play-store " src="<?php echo base_url().'assets/front/img/play_store.png' ?>" width="130" height="auto" border="0">
                        </a>
                      </div>
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="news-app-promo__section">
                    <div class="news-app-promo-subsection">
                      <a class="news-app-promo-subsection--link news-app-promo-subsection--playstore" href="https://apps.apple.com/us/developer/apple/id284417353?mt=12" target="_parent">
                        <img class="news-app-promo__play-store " src="<?php echo base_url().'assets/front/img/app_store.png' ?>" width="130" height="auto" border="0">
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="news-app-promo__section">
                    <div class="news-app-promo-subsection">
                      <a class="news-app-promo-subsection--link news-app-promo-subsection--playstore" href="https://apps.apple.com/us/developer/apple/id284417353?mt=12" target="_parent">
                        <img class="news-app-promo__play-store " src="<?php echo base_url().'assets/front/img/app_store.png' ?>" width="130" height="auto" border="0">
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="copyright-area" style="text-align: center;padding-top: 0;padding-bottom: 25px;">
                <div class="col-12 text-center">
                  <div class="copyright-text" style="text-align:center;color: #fff;">
                    <p style="line-height: 20px;margin-top: 2rem;border-bottom: none;">All Right Reserved &copy; Mygroup of Company <br> Developed by <a target="_blank" href="http://gomygroup.com/mybiz">My Group</a></p>
                  </div>
                </div>
              </div>
            </div>
      
          </div>
         <?php } ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function logout_user() {
    $.ajax({
        url: '<?php echo site_url('home/logout'); ?>',
        type: 'post',
        success: function(data) {
          location.reload();
        }
    });
  }
</script>

<div class="modal fade login-register-form" data-backdrop="static" class="form-horizontal" id="profile_login" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header" id="register_form_show" style="display: none;" >
        <ul class="nav nav-tabs">
          <li class="nav-item">
            <a class="nav-link" id="registerTab" data-toggle="tab" href="#registration-form">Register</a>
          </li>
        </ul>
      </div>

      <div class="modal-header" id="register_form_hide">
       
        <ul class="nav nav-tabs">
          <li class="nav-item" style="margin-right:2rem; width: 100px; text-align: center; ">
            <a class="nav-link" id="loginTab" data-toggle="tab" href="#login-form">Login</a>
          </li>
          <li class="nav-item" style="width: 100px; text-align: center;">
            <a class="nav-link active" id="registerTab" data-toggle="tab" href="#registration-form">Register</a>
          </li>
        </ul>
      </div>
      <div class="modal-body" id="login_model-content">
        
      </div>
   <!--    <div class="modal-header">

        <ul class="nav nav-tabs">
          <li class="nav-item" style="margin-right:6rem">
            <a class="nav-link active" data-toggle="tab" href="#login-form">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#registration-form">Register</a>
          </li>
        </ul>
         <button type="button" class="close" onclick="close_button_refresh()" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="login_model-content">

      </div> -->
    </div>
  </div>
</div>

<script type="text/javascript">
  function close_button_refresh() {
    location.reload();
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.12.5/dist/sweetalert2.all.min.js" integrity="sha256-vT8KVe2aOKsyiBKdiRX86DMsBQJnFvw3d4EEp/KRhUE=" crossorigin="anonymous"></script>
<style type="text/css">
.nav-tabs{
  border-bottom: none;
}
.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active{
  color: #ffffff;
  background-color: #0062cc;
  border-color: #dee2e6 #dee2e6 #fff;
}
#profile_login{
  padding-left: 0 !important;
  padding-right: 0 !important;
}

.modal-open{
  padding-right: 0 !important;
}
#navabarpadding{
  padding-right: 0 !important;
}
</style>
<script type="text/javascript">

$('#editButtonMobile').click(function() {
  var user_id = '<?php echo  (!empty($user)) ? $user->id : ''  ?>';
   $.ajax({
        url: '<?php echo site_url('home/edit_profile_mobile'); ?>',
        data: {'user_id': user_id},
        type: 'post',
        success: function(data) {
          var profile = JSON.parse(data);
          construct_edit_profile(profile.profile, profile.country_flag, profile.education, profile.profession);
        }
    });
});

function construct_edit_profile(profile_edit, country_flag, education, professions) {
  // console.log(profile_edit);
  var html ='';
  html +=`<form method="post" class="form-horizontal" id="edit_form_user_registration" data-parsley-validate="">
     <div class="panel-body" id="contentId">
          <?php
            $user = $this->ion_auth->user()->row();
          ?>
          <input type="hidden" id="user_id" name="user_id" value="<?php echo  (!empty($user)) ? $user->id : ''  ?>">
              <div class="profile-image">
                <?php
                $img = base_url().'assets/front/logo.jpg';
                if (!empty($user)) {
                    if ($user->profile_img !='') {
                      $img = $this->filemanager->getFilePath($user->profile_img);
                    }
                } ?>

              <div class="form-group">
                <label class="control-label col-md-3">Profile Picture </label>
                <div class="container">
                <div class="row">
                <div class="col-md-4 col-4">
                    <?php if (!empty($user)) { ?>
                     <img onclick="$('#fileupload1').click();" class="rounded-circle" id="profile_photo1" style="width:60px;height:60px" src="<?php echo $img ?>">
                      <br>
                    <?php
                      $classfa = 'camera';
                      if ($this->mobile_detect->isMobile()) {
                        $classfa = 'mobile-camera';
                      }
                    ?>
                      <i onclick="$('#fileupload1').click();" class="fa fa-camera <?php echo $classfa ?>" aria-hidden="true"></i>
                      <input hidden="hidden" type="file" id="fileupload1" class="file" data-preview-file-type="jpeg" name="profile_photo1" accept="image/*">
                      <span id="fileuploadError1" style="color:red;display: block;padding-top:5px;padding-bottom:5px;"></span>
                    <?php }else{ ?>
                      <img class="img-responsive img-circle" id="profile_photo1" style="width:100px;height:100px" src="<?php echo $img ?>">
                    <?php } ?>
                </div>
                <div class="col-md-4 col-8">
                  <h3 id="displaymy"> <?php echo  (!empty($user)) ? ' My '. '<span id="displayName">'.$user->display_name .'</span>' : 'Guest Account'  ?></h3>
                </div>
                </div>
                </div>
              </div>

              </div>

            <div class="form-group">
              <label class="control-label col-md-3">Full Name <font color="red">*</font></label>
              <div class="col-md-8">
                 <input type="text" required="" autocomplete="off" name="first_name" value="${profile_edit.first_name}" class="form-control" placeholder="Full Name">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Display Name <font color="red">*</font></label>
              <div class="col-md-8">
                 <input type="text" required="" autocomplete="off" minlength="2" maxlength="12" data-parsley-minlength="2" data-parsley-maxlength="15"  name="display_name" value="${profile_edit.display_name}" class="form-control" placeholder="Display Name">
              </div>
            </div>`;
            mChecked = (profile_edit.gender == 'M')  ? 'checked' : '';
            fChecked = (profile_edit.gender == 'F')  ? 'checked' : '';
            oChecked = (profile_edit.gender == 'o')  ? 'checked' : '';

            singleChecked = (profile_edit.marital == 'Single')  ? 'checked' : '';
            marriedChecked = (profile_edit.marital == 'Married')  ? 'checked' : '';
            otherChecked = (profile_edit.marital == 'Other')  ? 'checked' : '';

            disabledalter = (profile_edit.alter_number == '') ? '' : 'readonly';

            html +=`<div class="form-group">
            <label class="control-label col-md-3">Email-ID </label>
            <div class="col-md-8">
              <input type="email" class="form-control" autocomplete="off" name="email" value="${profile_edit.email}">
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label" for="gender"> Gender <font color="red">*</font></label>
              <div class="col-md-8">
                  <label class="radio-inline" for="gender-0" style="margin-right: 22px;">

                      <input type="radio" data-parsley-group="block0"  style="margin-right: 0rem; margin-left: 0; " class="gender-radio" ${mChecked} name="gender" id="gender-0" value="M" checked>
                      Male
                  </label>
                  <label class="radio-inline" for="gender-1" style="margin-right: 22px;">
                      <input type="radio"  data-parsley-group="block1"style="margin-right: 0rem;" class="gender-radio" ${fChecked}  name="gender" id="gender-1" value="F">
                      Female
                  </label>
                   <label class="radio-inline" for="gender-1" style="margin-right: 22px;">
                      <input type="radio" style="margin-right: 0rem;"  data-parsley-group="block2" class="gender-radio" name="gender" ${oChecked}  id="gender-2" value="o">
                      Transgender
                  </label>
              </div>
            </div>


            <div class="form-group">
              <label class="col-md-3 control-label" for="gender"> Marital status <font color="red">*</font></label>
              <div class="col-md-8">
                  <label class="radio-inline" for="marital-0" style="margin-right: 22px;">
                      <input type="radio" data-parsley-group="block0" style="margin-right: 0rem; margin-left: 0;" class="marital-radio" name="marital" ${singleChecked} id="marital-0" value="Single" checked>
                      Single
                  </label>
                  <label class="radio-inline" for="marital-1" style="margin-right: 22px;">
                      <input type="radio" ${marriedChecked}  data-parsley-group="block1" style="margin-right: 0rem;" class="marital-radio" name="marital" id="marital-1" value="Married">
                      Married
                  </label>
                   <label class="radio-inline" for="marital-1" style="margin-right: 22px;">
                      <input type="radio" ${otherChecked} data-parsley-group="block2" style="margin-right: 0rem;" class="marital-radio" name="marital" id="marital-2" value="Other">
                      Other
                  </label>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label">Date of Birth <font color="red">*</font></label>
              <div class="col-md-6 col-xs-12" style="padding:0;display: flex;">
                <div class="col-md-4" style="padding-right: 2px;">`;


                 var days = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
                 html +=`<select name="from_date" class="form-control" id="from_date">
                    <option value=''>Date</option>`;
                  for(var day in days){
                    var selected = '';
                    if (days[day] == profile_edit.dob_date) {
                      selected ='selected';
                    }
                    html += '<option '+selected+' value="'+days[day]+'">'+days[day]+'</option>';
                  }

                  html +=`</select>
                </div>

                <div class="col-md-4" style="padding-left: 0;padding-right: 2px;">`;

                //var monthArray =  ['January','February','March','April','May','June','July','August','September','October','November','December'];

                var monthArray = [{id:'01', name:'January'},{id:'02', name:'February'},{id:'03', name:'March'},{id:'04', name:'April'},{id:'05', name:'May'},{id:'06', name:'June'},{id:'07', name:'July'},{id:'08', name:'August'},{id:'09', name:'September'},{id:'10', name:'October'},{id:'11', name:'November'},{id:'12', name:'December'}];

                html +=`<select name="from_month" class="form-control" id="from_month" >
                      <option value="">Month</option>`;
                    for(var month in monthArray){
                      var selected = '';
                      if (monthArray[month].id == profile_edit.dob_month) {
                        selected ='selected';
                      }
                      html += '<option '+selected+' value="'+monthArray[month].id+'">'+monthArray[month].name+'</option>';
                    }
               html +=`</select>
                </div>

              <div class="col-md-4" style="padding-left: 2px;">
              <select name="from_year" class="form-control">
                <option value="">Year</option>`;
                for (i = new Date().getFullYear(); i > 1900; i--){
                  var selected = '';
                  if (i == profile_edit.dob_year) {
                   selected = 'selected';
                  }
                  html += '<option '+selected+' value="'+i+'">'+i+'</option>';
                }
              html +=`</select>
                </div>
              </div>
            </div>

              <div class="form-group">
                <label class="col-md-3 control-label">Country <font color="red">*</font></label>
                <div class="col-md-8">
                   <select class="form-control" name="country" required="" id="country">
                   <option value="">Select Country</option>`;

                    for(var k in country_flag){
                      var selected = '';
                      if (country_flag[k].id == profile_edit.country) {
                        selected ='selected';
                      }
                      html += '<option '+selected+' value="'+country_flag[k].id+'">'+country_flag[k].country+'</option>';
                    }
                   html += `</select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 control-label">State / Province <font color="red">*</font></label>
                <div class="col-md-8">
                   <select class="form-control" name="state" onchange="get_district_by_state(this.value)" required="" id="state">
                      <option value="">Select State / Province</option>
                    </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 control-label">District / City <font color="red">*</font></label>
                <div class="col-md-8">
                   <select class="form-control" name="district" required="" id="district">
                      <option value="">Select District</option>
                    </select>
                </div>
              </div>


                <div class="form-group">
                  <label class="col-md-3 control-label">Nationality <font color="red">*</font></label>
                  <div class="col-md-8">
                     <select class="form-control" name="nationality" required="" id="nationality">`;
                      for(var k in country_flag){
                        var selected = '';
                        if (country_flag[k].nationality == profile_edit.nationality) {
                          selected ='selected';
                        }
                        html += '<option '+selected+' value="'+country_flag[k].nationality+'">'+country_flag[k].nationality+'</option>';
                      }
                     html += `</select>
                  </div>
                </div>

                <div class="form-group">
                <label class="col-md-3 control-label">Eduction / Qualification <font color="red">*</font></label>
                <div class="col-md-8">
                   <select class="form-control" name="education" required="" id="education">
                      <option value="">Select Eduction / Qualification</option>`;
                      for(var k in education){
                        var selected = '';
                        if (education[k].education == profile_edit.education) {
                          selected ='selected';
                        }
                         html += '<option '+selected+' value="'+education[k].education+'">'+education[k].education+'</option>';
                      }
                      var selected_others = '';
                        if (profile_edit.education =='education_others') {
                          selected_others ='selected';
                        }
                      html += '<option '+selected_others+' value="education_others">Others</option>';
                  html += `</select>
                </div>
              </div>
              <div class="form-group" id="education_others" style="display: none;">
                <label class="col-md-3 control-label">Others</label>
                <div class="col-md-8">
                  <input type="text" value="${profile_edit.education_others}" name="education_others" class="form-control" value="" >
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 control-label">Work / Profession <font color="red">*</font></label>
                <div class="col-md-8">
                   <select class="form-control" name="profession" required="" id="profession">
                      <option value="">Select Work / Profession</option>`;

                      for(var k in professions){
                        var selected = '';
                        if (professions[k].profession == profile_edit.profession) {
                          selected ='selected';
                        }
                         html += '<option '+selected+' value="'+professions[k].profession+'">'+professions[k].profession+'</option>';
                      }
                      var selected_others = '';
                        if (profile_edit.profession =='work_others') {
                          selected_others ='selected';
                        }
                      html += '<option '+selected_others+' value="work_others">Others</option>';

                   html += `</select>
                </div>
              </div>

              <div class="form-group" id="work_others" style="display: none;">
                <label class="col-md-3 control-label">Others</label>
                <div class="col-md-8">
                  <input type="text"  value="${profile_edit.work_others}" name="work_others" class="form-control" value="" >
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-5">
                </div>
                <div class="col-md-6">
                  <center>
                    <input type="button"  id="update_edit_form"  class="btn btn-info"  value="Update" />
                    <a class="btn btn-danger close_edit_form" href="javascript:void(0)">Close</a>
                  </center>
                </div>
              </div>
              <div class="text-center" id="error">
              <h5 id="update_message"></h5>
              </div>
            </div>
          </div>

        </div>
        </form>`;

    content_get_state_edit(profile_edit.country, profile_edit.state, profile_edit.district);
    $("#contant-profile").html(html);

    $('#fileupload1').change(function() {
      readURL1(this);
    });

    $('#country').on('change',function(){
      content_get_state();
    });

    $('#update_edit_form').on('click',function(){
        edi_form_submit();
    });
   $('#profieEdit').modal('show');

   var education = $('#education').val();
   if (education == 'education_others') {
     $('#education_others').show();
   }

   var profession = $('#profession').val();
   if (profession == 'work_others') {
     $('#work_others').show();
   }

   $('#education').on('change',function(){
    var otherEdu = $('#education').val();
    if (otherEdu == 'education_others') {
      $('#education_others').show();
    }else{
      $('#education_others').hide();
    }
  });

  $('#profession').on('change',function(){
    var otherEdu = $('#profession').val();
    if (otherEdu == 'work_others') {
      $('#work_others').show();
    }else{
      $('#work_others').hide();
    }
  });

   $('.close_edit_form').on('click',function(){
    $('#profieEdit').modal('hide');
    open_setting_profile();
  });


}


</script>

<script type="text/javascript">
  function edi_form_submit() {
    var $form = $('#edit_form_user_registration');
    if ($form.parsley().validate()){
    var form = $('#edit_form_user_registration')[0];
    var formData = new FormData(form);
    $('#update_edit_form').val('Please wait ...').attr('disabled','disabled');
      $.ajax({
        url: '<?php echo site_url('home/user_register_update'); ?>',
        type: 'post',
        data: formData,
        // async: false,
        processData: false,
        contentType: false,
        // cache : false,
        success: function(data) {
        $('#update_edit_form').val('Update').removeAttr('disabled');
          if(data) {
            Swal.fire({
              title: "Successful",
              text: "Update Successfully",
              icon: "success",
            });
            location.reload();
             // $('#update_message').html('<span style="color:green" >Update Successfully</span>')
          } else {
            $('#update_message').html('<span style="color:red" >Something went wrong.</span>');
          }
        }
      });
    }
}
</script>
<div class="modal fade" id="profieEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog m-0" style="height:auto;">
    <div class="modal-content" style="height:auto; ">
      <div class="modal-header">
        <h3>Edit Profile</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span style="color: #000" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="contant-profile" style="padding: 0;" >

      </div>
    </div>
  </div>
</div>

<style type="text/css">

.dark-mode {
  background-color: #3c3a3a;
  color: white !important;
}

.dark-mode #profile_login .modal-content{
  background-color: #3c3a3a;
}
.dark-mode #profieEdit .modal-content{
  background-color: #3c3a3a;
}
#profieEdit .modal-content{
  background-color: #fff;
}
.dark-mode {
  background-color: #3c3a3a;
  color: white !important;
}

.dark-mode #enter_send{
  color: #fff;
}

.dark-mode  .messages.messages-img .item.in .text{
   color: #000;
}


.dark-mode a{
  color: white;
}

.dark-mode .list-group-item{
  background-color: #3c3a3a;
}

.dark-mode small{
  color: white;
}
.dark-mode .navbar-bottom p{
  color: #fff;
}

.mobile-bar{
  position: absolute;
    top: 44%;
    right: 9px;
    border: 1px solid #ccc;
    border-radius: 48px;
    background: #fff;
    font-size: 12px;
    color: #938d8d;
    width: 17px;
    height: 17px;
}
.fa-navicon:before, .fa-reorder:before, .fa-bars:before {
    content: "\f0c9";
    font-size: 10px;
}
.mobile-camera{
  position: absolute;
  left: 54px;
  top: 40px;
  color: #fff;
}
#linebotton p{
  border-bottom: 1px solid #ccc;
  line-height: 3rem;
  margin: 0;
}
#lastChaild{
  border-bottom:none !important;
  line-height: 3rem;
  margin: 0;
}
#linebotton i{
  line-height: 3rem;
}
  #mobile_more .nav-link{
    padding: .5rem 0rem;
  }

.btn-secondary{
  color: #000;
  background-color: inherit;
}
.btn-secondary:hover{
   color: #000;
  background-color: inherit;
}
.btn-secondary:not(:disabled):not(.disabled).active, .btn-secondary:not(:disabled):not(.disabled):active, .show>.btn-secondary.dropdown-toggle{
  color: #000;
  background-color: inherit;
}
</style>

<script type="text/javascript">
$('#legalonchange').on('click',function(){
  $("#tnc").toggle();
  $("#pnp").toggle();
});

$('#legalonchange_mygroup').on('click',function(){
  $("#tnc_group").toggle();
  $("#pnp_group").toggle();
});

$('#settingsonchange_mygroup').on('click',function(){
  $("#setSecurity").toggle();
  $("#changeLang").toggle();
  $("#changeCurr").toggle();
  $("#changePass").toggle();
});



$('#helponchange').on('click',function(){
  $("#fns").toggle();
  $("#lc").toggle();
  $("#con").toggle();
});

$('#helponchange_group').on('click',function(){
  $("#fns_group").toggle();
  $("#lc_group").toggle();
  $("#con_group").toggle();
});


function register_user() {

  $.ajax({
    url: '<?php echo site_url('home/profile_login_modal'); ?>',
    type: 'post',
    success: function(data) {
      var profile = JSON.parse(data);
      construct_login_profile(profile.country_flag, profile.education, profile.profession);
    }
  });
}

function construct_login_profile(country_flag, education, professions) {
  var html = `<div class="tab-content">
  <div id="login-form" class="tab-pane fade">
    <?php
      $style = '';
      if ($this->mobile_detect->isMobile()) {
        $style = 'width:100%';
      }
    ?>
    <form method="post" class="log-form" style="<?php echo $style ?>">
      <div class="form-group">
        <input type="text" class="form-control" id="mobile_number" name="identity" placeholder="Mobile Number">
      </div>

      <div class="form-group">
        <div class="wrap-input100 validate-input" data-validate="Enter password">
          <input type="password" class="form-control" id="password" name="password"  placeholder="Password">
            <button type="button" class="btn btn-secondary" style="margin-top:-2rem;margin-left: 90%;padding: 0px;border: 0px; display: block; ">
              <i class="fa fa-eye-slash" id="passwordShowIcon"></i>
            </button>
        </div>
      </div>

      <span class="check left-align">
        <input type="checkbox">
        <label style="font-size:14px; font-weight:400" >Remember Me</label>
      </span>
      <a class="right-align" style="float:right" href="<?php echo site_url('auth/forgot_user_email') ?>">Forgot Password</a>
      <br><br>
      <div class="container-log-btn">
        <button type="button" onclick="login_profile()" name="btn_submit" class="btn btn-info btn-block">
          <span>Login</span>
        </button>
        <a style="float: left;margin-top: 1rem;font-size: 15px; font-weight: 700;" href="#registration-form">Don't have an account? Click Register</a>
      </div>

      <a id="error-login-message" style="float: left;margin-top: 0.5rem;color:red; font-size:14px;" href="javascript:void(0)"></a>
    </form>
  </div>

  <div id="registration-form" class="tab-pane fade in active show">
    <form method="post"  id="first_registration_form" data-parsley-validate="">
      <div class="first-step-register">
        <div class="form-group">
          <p>Full Name <font color="red">*</font></p>
          <input type="text" required="" autocomplete="off" name="first_name" class="form-control" placeholder="Full Name">
        </div>

        
        <div class="form-group">
          <p>Mobile Number <font color="red">*</font></p>
          <div class="col-md-12" style="padding:0; display: flex;">
            <div class="col-md-2" style="padding: 0px 2px 0px 0px;width: 26%;">
              <select class="form-control" style="padding: .3rem .2rem;" name="country_code" id="counry_code">`;
              for(var k in country_flag){
                html += '<option value="'+country_flag[k].phone_code+'">'+country_flag[k].phone_code+'</option>';
              }
              html +=`</select>
            </div>
            <div class="col-md-10" style="padding: 0px 0px 0px 2px ;">
              <input type="text" required="" class="form-control" name="mobile_number" autocomplete="off" data-parsley-validation-threshold="1" data-parsley-trigger="keyup"  data-parsley-type="number"   id="mobileNumber" placeholder="Mobile Number">
              <span id="error_unique" style="color:red;font-size:12px"></span>
            </div>
          </div>
        </div>

        <div class="form-group">
          <p>Password <font color="red">*</font></p>
          <div class="wrap-input100 validate-input" data-validate="Enter password">
            <input data-parsley-errors-container="#errorNew" type="password" id="new" autocomplete="off" name="password" pattern="^.{6}.*$" class="form-control"  placeholder="Password" data-parsley-error-message="This value seems to be invalid" required="">
            <span class="focus-input100" data-placeholder="&#xf191;"></span>
            <span class="input-group-btn">
              <button type="button" class="btn btn-secondary" style="margin-top:-4em;margin-left: 90%;padding: 0px;border: 0px;">
              <i class="fa fa-eye-slash" id="passwordShowIcon1"></i>
              </button>
            </span>
          </div>
        </div>

        <div class="form-group">
          <p>Confirm Password <font color="red">*</font></p>
          <div class="wrap-input100 validate-input" data-validate="Enter password">
            <input  data-parsley-errors-container="#errorConfirm" type="password" id="new_confirm" pattern="^.{6}.*$" class="form-control" placeholder="Confirm Password" id="password2" data-parsley-error-message="Password not matching" data-parsley-equalto="#new" required="">
            <span class="focus-input100" data-placeholder="&#xf191;"></span>
            <span class="input-group-btn">
              <button type="button" class="btn btn-secondary" style="margin-top:-4em;margin-left: 90%;padding: 0px;border: 0px;">
              <i class="fa fa-eye-slash" id="passwordShowIcon2"></i>
              </button>
            </span>
          </div>
        </div>
        <div class="form-group">
          <center>
          <input type="button" class="btn btn-info" id="form_first_step" onclick="submit_form_first_step()" value="Register" style="width: 6rem; border-radius: 3.45rem;" >
          </center>
          </div>
          <p style="color:red" id="error_exits"></p>
        </div>
      </form>
      <form method="post"  id="second_registration_form" data-parsley-validate="" style="display:none">
        <div class="second-step-register">
         <input type="hidden" id="register_user_id" name="register_user_id" value="">
         <input type="hidden" id="register_username" name="register_username" value="">
         <input type="hidden" id="register_password" name="register_password" value="">
          <div class="form-group">
            <p>Display Name (Nickname) <font color="red">*</font></p>
            <input type="text" required="" autocomplete="off" minlength="2" maxlength="12" data-parsley-minlength="2" data-parsley-maxlength="12" data-parsley-trigger="keyup"  data-parsley-error-message="valid input message" name="display_name" class="form-control" placeholder="Display Name">
          </div>
       

        <div class="form-group">
          <p>Email-Id</p>
          <input type="email" class="form-control" autocomplete="off" name="email" placeholder="Email-Id">
        </div>

        <div class="form-group">
          <p>Gender <font color="red">*</font></p>
          <label class="radio-inline" for="gender-0" style="margin-right: 22px;">
            <input type="radio" data-parsley-group="block0" style="margin-right: 0rem; margin-left: 0; " class="gender-radio" name="gender" id="gender-0" value="M" checked>
              Male
          </label>
          <label class="radio-inline" for="gender-1" style="margin-right: 22px;">
            <input type="radio"  data-parsley-group="block1" style="margin-right: 0rem;" class="gender-radio" name="gender" id="gender-1" value="F">
              Female
          </label>
          <label class="radio-inline" for="gender-1" style="margin-right: 22px;">
            <input type="radio"  data-parsley-group="block2" style="margin-right: 0rem;" class="gender-radio" name="gender" id="gender-2" value="o">
              Transgender
          </label>
        </div>


        <div class="form-group">
          <p>Marital status <font color="red">*</font></p>
          <label class="radio-inline" for="marital-0" style="margin-right: 22px;">
              <input type="radio" data-parsley-group="block0" style="margin-right: 0rem; margin-left: 0;" class="marital-radio" name="marital" id="marital-0" value="Single" checked>
              Single
          </label>
          <label class="radio-inline" for="marital-1" style="margin-right: 22px;">
              <input type="radio"  data-parsley-group="block1" style="margin-right: 0rem;" class="marital-radio" name="marital" id="marital-1" value="Married">
              Married
          </label>
           <label class="radio-inline" for="marital-1" style="margin-right: 22px;">
              <input type="radio"  data-parsley-group="block2" style="margin-right: 0rem;" class="marital-radio" name="marital" id="marital-2" value="Other">
              Other
          </label>
        </div>

        <div class="form-group">
          <p>Date of Birth <font color="red">*</font></p>
          <div class="col-md-12 col-xs-12" style="padding:0; display: flex;">
            <div class="col-md-4" style="padding-right: 2px; padding-left: 0; ">`;


         var days = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
         html +=`<select name="from_date" class="form-control" id="from_date">
            <option value=''>Date</option>`;
          for(var day in days){

            html += '<option value="'+days[day]+'">'+days[day]+'</option>';
          }

          html +=`</select>
            </div>

            <div class="col-md-4" style="padding-left: 0;padding-right: 2px;">`;
          var monthArray = [{id:'01', name:'January'},{id:'02', name:'February'},{id:'03', name:'March'},{id:'04', name:'April'},{id:'05', name:'May'},{id:'06', name:'June'},{id:'07', name:'July'},{id:'08', name:'August'},{id:'09', name:'September'},{id:'10', name:'October'},{id:'11', name:'November'},{id:'12', name:'December'}];

          html +=`<select name="from_month" class="form-control" id="from_month" >
              <option value="">Month</option>`;
            for(var month in monthArray){
              html += '<option  value="'+monthArray[month].id+'">'+monthArray[month].name+'</option>';
            }
          html +=`</select>
            </div>

          <div class="col-md-4" style="padding-left: 2px;">
            <select name="from_year" class="form-control">
              <option value="">Year</option>`;
              for (i = new Date().getFullYear(); i > 1900; i--){
                html += '<option value="'+i+'">'+i+'</option>';
              }
              html +=`</select>
              </div>
          </div>
        </div>

        <div class="form-group">
          <p>Country <font color="red">*</font></p>
          <select class="form-control" name="country" required="" onchange="content_get_state()"  id="country">
            <option value="">Select Country</option>`;

            for(var k in country_flag){
              html += '<option value="'+country_flag[k].id+'">'+country_flag[k].country+'</option>';
            }
           html += `</select>
        </div>

        <div class="form-group">
          <p>State / Province <font color="red">*</font></p>
          <select class="form-control" name="state" onchange="get_district_by_state(this.value)" required="" id="state">
            <option value="">Select State / Province</option>
          </select>
        </div>

        <div class="form-group">
          <p>District / City <font color="red">*</font></p>
          <select class="form-control" name="district" required="" id="district">
            <option value="">Select District</option>
          </select>
        </div>

        <div class="form-group">
          <p>Nationality <font color="red">*</font></p>
          <select class="form-control" name="nationality" required="" id="nationality">`;
              for(var k in country_flag){

                html += '<option  value="'+country_flag[k].nationality+'">'+country_flag[k].nationality+'</option>';
              }
             html += `</select>
        </div>

        <div class="form-group">
          <p>Eduction / Qualification<font color="red">*</font></p>
          <select class="form-control" name="education" required="" id="education">
            <option value="">Select Eduction / Qualification</option>`;
              for(var k in education){
                html += '<option value="'+education[k].education+'">'+education[k].education+'</option>';
              }
              html += '<option value="education_others">Others</option>';
          html += `</select>
        </div>

        <div class="form-group" id="education_others" style="display: none;">
          <p>Others</p>
          <input type="text" name="education_others" class="form-control" value="" >
        </div>

        <div class="form-group">
          <p>Work / Profession <font color="red">*</font></p>
          <select class="form-control" name="profession" required="" id="profession">
            <option value="">Select Work / Profession</option>`;

              for(var k in professions){
                html += '<option  value="'+professions[k].profession+'">'+professions[k].profession+'</option>';
              }
              html += '<option value="work_others">Others</option>';

           html += `</select>
        </div>

        <div class="form-group" id="work_others" style="display: none;">
          <p>Others</p>
          <input type="text" name="work_others" class="form-control" value="" >
        </div>

        <div class="form-group">
          <center>
            <button type="button" onclick="submit_form_second_step()" id="submit_resgiter"  style="width: 6rem; border-radius: 3.45rem;" class="btn btn-info">Submit</button>
            <a class="btn btn-danger" onclick="register_model_close()" href="javascript:void(0)">Close</a>
          </center>
        </div>
      </form>
    </div>
</div>`;

  $('#login_model-content').html(html);

  $('#passwordShowIcon1').click(function(e) {
    e.preventDefault();
    var type = $('#new').attr('type');
    if(type == 'password') {
        $('#passwordShowIcon1').removeClass('fa fa-eye-slash').addClass('fa fa-eye');
        $('#new').attr('type', 'text');
    } else {
        $('#passwordShowIcon1').removeClass('fa fa-eye').addClass('fa fa-eye-slash');
        $('#new').attr('type', 'password');
    }
  });

  $('#mobileNumber').on('keyup',function(){
    var mobileNumber = $('#mobileNumber').val();
    $.post("<?php echo site_url('home/register_unique_check')?>",{mobileNumber:mobileNumber},function(data){
      console.log(data);
      if (data) {
        $('#error_unique').html('This mobile number already exits.');
      }else{
        $('#error_unique').html('');
      }
   });

  });

  $('#passwordShowIcon2').click(function(e) {
    e.preventDefault();
    var type = $('#new_confirm').attr('type');
    if(type == 'password') {
        $('#passwordShowIcon2').removeClass('fa fa-eye-slash').addClass('fa fa-eye');
        $('#new_confirm').attr('type', 'text');
    } else {
        $('#passwordShowIcon2').removeClass('fa fa-eye').addClass('fa fa-eye-slash');
        $('#new_confirm').attr('type', 'password');
    }
  });


  $('#passwordShowIcon').click(function(e) {
    e.preventDefault();
    var type = $('#password').attr('type');
    if(type == 'password') {
        $('#passwordShowIcon').removeClass('fa fa-eye-slash').addClass('fa fa-eye');
        $('#password').attr('type', 'text');
    } else {
        $('#passwordShowIcon').removeClass('fa fa-eye').addClass('fa fa-eye-slash');
        $('#password').attr('type', 'password');
    }
  });



  $('#education').on('change',function(){
    var otherEdu = $('#education').val();
    if (otherEdu == 'education_others') {
      $('#education_others').show();
    }else{
      $('#education_others').hide();
    }
  });

  $('#profession').on('change',function(){
    var otherEdu = $('#profession').val();
    if (otherEdu == 'work_others') {
      $('#work_others').show();
    }else{
      $('#work_others').hide();
    }
  });

  $('#profile_login').modal('show');
}

function register_model_close() {
  $('#profile_login').modal('hide');
}


function login_profile() {
  var $form = $('.log-form');
  if ($form.parsley().validate()){
    var form = $('.log-form')[0];
    var formData = new FormData(form);
    var mobile_number = $('#mobile_number').val();
    var password = $('#password').val();
    $.ajax({
      url: '<?php echo site_url('home/login_profile_popup'); ?>',
      type: 'post',
      data: formData,
      // async: false,
      processData: false,
      contentType: false,
      // cache : false,
      success: function(data) {
        // console.log(data);
        if(data == 'success') {
          location.reload();
        } else if(data == 0){
          $('#error-login-message').html('Username / Password incorrect. Please try again');
        }else{
          $('#register_user_id').val(data);
          $('#register_username').val(mobile_number);
          $('#register_password').val(password);
          var tab_href = $('#registerTab').attr('href');
          $('#loginTab').removeClass('active');
          $('#registerTab').addClass('active');
          $('#first_registration_form').css('display','none');
          $('#second_registration_form').css('display','block');
          $('#login-form').removeClass('active show');
          $(tab_href).addClass('current active show');
        }
      }
    });
  }
}

function submit_form_first_step() {

    $('#first_registration_form').css('display','block');
    $('#second_registration_form').css('display','none');
    $('#error_exits').html('');
    var $form = $('#first_registration_form');
    if ($form.parsley().validate()){
    var form = $('#first_registration_form')[0];
    var formData = new FormData(form);
    $('#form_first_step').val('Please wait ...');
    var register_username = $('#mobileNumber').val();
    var register_password = $('#new').val();
    $.ajax({
      url: '<?php echo site_url('home/first_step_register_submit_popup'); ?>',
      type: 'post',
      data: formData,
      // async: false,
      processData: false,
      contentType: false,
      // cache : false,
      success: function(result) { 
        if (result == 'exits') {
          $('#error_exits').html('Mobile number already registered.');
          $('#form_first_step').val('Register');
          $('#mobileNumber').attr('required');
          $('#mobileNumber').removeClass('parsley-success');
          $('#mobileNumber').addClass('parsley-error');
          return false;
        }
        if(result !=0) {
          // console.log(result);
           Swal.fire({
            title: "Successful",
            text: "Your mobile number is registered. Please continue to complete the registration",
            icon: "success",
            confirmButtonText:'Continue',
            confirmButtonColor: '#14722a'
          });
          $('#first_registration_form').css('display','none');
          $('#second_registration_form').css('display','block');
          $('#register_user_id').val(result);
          $('#register_username').val(register_username);
          $('#register_password').val(register_password);
          open_form_second_step(result);
          // location.reload();
        } else {
          Swal.fire({
              title: "Error",
              text: "Register un- Successfully",
              icon: "error",
          });
          $('#form_first_step').val('Register');
        }

      }
    });
  }
}

function open_form_second_step() {
  $('#register_form_hide').css('display','none');
  $('#register_form_show').css('display','block');
}

function submit_form_second_step() {

  var register_username = $('#register_username').val();
  var register_password = $('#register_password').val();
  var register_user_id = $('#register_user_id').val();

  var $form = $('#second_registration_form');
  if ($form.parsley().validate()){
  var form = $('#second_registration_form')[0];
  var formData = new FormData(form);

  formData.append('register_user_id', register_user_id);
  formData.append('register_password', register_password);
  formData.append('register_username', register_username);

  $('#submit_resgiter').html('Please wait ...');
    $.ajax({
      url: '<?php echo site_url('home/user_update_register_submit_popup'); ?>',
      type: 'post',
      data: formData,
      // async: false,
      processData: false,
      contentType: false,
      // cache : false,
      success: function(result) { 
      console.log(result);
        $('#submit_resgiter').html('Submit');
        if(result !=0) {
          // console.log(result);
           Swal.fire({
            title: "Successful",
            text: "Register Successfully",
            icon: "success",
          });
          location.reload();
        } else {
          Swal.fire({
              title: "Error",
              text: "Register un- Successfully",
              icon: "error",
          });
        }
      }
    });
  }
}


// function submit_form() {
//   var $form = $('#user_registration_form');
//   if ($form.parsley().validate()){
//   var form = $('#user_registration_form')[0];
//   var formData = new FormData(form);
//   $('#submit_resgiter').html('Please wait ...');
//     $.ajax({
//       url: '<?php echo site_url('home/user_register_submit_popup'); ?>',
//       type: 'post',
//       data: formData,
//       // async: false,
//       processData: false,
//       contentType: false,
//       // cache : false,
//       success: function(data) {
//         $('#submit_resgiter').html('Submit');
//         if(data) {
//            Swal.fire({
//             title: "Successful",
//             text: "Register Successfully",
//             icon: "success",
//           });
//           location.reload();
//         } else {
//           Swal.fire({
//               title: "Error",
//               text: "Register un- Successfully",
//               icon: "error",
//           });
//         }
//       }
//     });
//   }
// }

$('#fileupload').change(function() {
    var src = $(this).val();
    readURL(this);
  });

 function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#profile_photo').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
    var id =$('#userIdUrl').val();
    savePhoto('profile',id);
  }

  function savePhoto(type, id) {
    $("#photo_" + type).prop('disabled', true).html('<i class="fa fa-spinner fa-spin" style="font-size:20px"></i>');
    var file_data = $('#fileupload').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data);
    form_data.append('type', type);
    form_data.append('id', id);
    $.ajax({
      url: '<?php echo site_url('home/profile_pic_update') ?>',
      type: 'post',
      data: form_data,
      cache: false,
      contentType: false,
      processData: false,
      success: function(data) {
        console.log(data);
      }
    });
  }

  // $('#fileupload1').change(function() {
  //   var src = $(this).val();
  //   readURL1(this);
  // });

 function readURL1(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#profile_photo1').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
    var id =$('#userIdUrl').val();
    savePhoto1('profile',id);
  }

  function savePhoto1(type, id) {
    $("#photo_" + type).prop('disabled', true).html('<i class="fa fa-spinner fa-spin" style="font-size:20px"></i>');
    var file_data = $('#fileupload1').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data);
    form_data.append('type', type);
    form_data.append('id', id);
    $.ajax({
      url: '<?php echo site_url('home/profile_pic_update1') ?>',
      type: 'post',
      data: form_data,
      cache: false,
      contentType: false,
      processData: false,
      success: function(data) {
        console.log(data);
      }
    });
  }
</script>



<?php if (!empty($popupads->side_ads)) { ?>
<div class="modal" tabindex="-1" role="dialog" id="popup">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
      <?php
        $filetype = pathinfo($popupads->side_ads, PATHINFO_EXTENSION);
        if ($filetype == 'mp4') { ?>
          <video id="myVideo" width="100%" height="auto"  autoplay="autoplay" loop preload="metadata" muted="">
            <source src="<?php echo $popupads->side_ads ?>" type="video/mp4">
          </video>
        <?php }else{ ?>
          <img style="width: 100%;" src="<?php echo base_url().$popupads->side_ads ?>">
        <?php }
        ?>
        <center>
          <a href="<?php echo $popupads->side_ads_url ?>">
            <span style="font-size: 14px; padding: 10px;" class="badge badge-primary ">Visit</span>
          </a>
        </center>
      </div>
      <div id="hideTime" style="text-align: center;" >Skip in <span id="timer"></span></div>
      <div class="modal-footer" id="skiputton" style="display: none;">
        <span data-dismiss="modal" class="badge badge-success">Skip</span>
      </div>
    </div>
  </div>
</div>
<?php } ?>


<script type="text/javascript">
  var userId = '<?php echo json_encode($this->ion_auth->user()->row()) ?>';
   var users ='';
   if (userId != null) {
     var users = $.parseJSON(userId);
   }
 
$(document).ready(function() {
    var ua = navigator.userAgent.toLowerCase();
    var isAndroid = ua.indexOf("android") > -1;
    console.log(isAndroid);
    if (!isAndroid) {
      // $('#show_download_app').delay(2000).fadeIn();
      localStorage.setItem('popState','shown')
    }
  $('#popup-close, #show_download_app').click(function() {
    // $('#show_download_app').fadeOut();
  });
  if(!users){
    register_user();
  }
});


var video=document.getElementById("myVideo") ;

$(video).on("click", function(e){
  video.muted = !video.muted;
});

var timerOn = true;
var timerClose = true;
$(document).ready(function(){
  var url = '<?php echo isset($base_url) ? $base_url : 0  ?>';
  var sides = '<?php echo  (isset($header_sliders->side_ads) && ($header_sliders->side_ads !='') ) ? 1 : 0  ?>';
  var videoCloseTime = '<?php echo  isset($header_sliders->side_seconds) ? $header_sliders->side_seconds : '' ?>';
  if (url == true && sides == 1) {
    close_popup(videoCloseTime);
    skip_timer(3);
    if (users !=null) {
      if (users.length > 0) {
        $("#popup").modal('show');
      }
    }
    
  }
});

function close_popup(closeTime) {
    var m = Math.floor(closeTime / 60);
    var s = closeTime % 60;
    m = m < 10 ? '0' + m : m;
    s = s < 10 ? '0' + s : s;
    closeTime -= 1;
    if(closeTime >= 0 && timerClose) {
      setTimeout(function() {
          close_popup(closeTime);
      }, 1000);
      return;
    }
    if(!timerClose) {
      // Do validate stuff here
      return;
    }
   $("#popup").modal('hide');
}

 function skip_timer(remaining) {
   var m = Math.floor(remaining / 60);
    var s = remaining % 60;
    m = m < 10 ? '0' + m : m;
    s = s < 10 ? '0' + s : s;
    document.getElementById('timer').innerHTML = m + ':' + s;
    remaining -= 1;

    if(remaining >= 0 && timerOn) {
      setTimeout(function() {
          skip_timer(remaining);
      }, 1000);
      return;
    }
    if(!timerOn) {
      // Do validate stuff here
      return;
    }
    $('#hideTime').hide();
    $('#skiputton').show();
 }
</script>


<style type="text/css">

[hidden] {
  display: none!important;
}
.dark-mode #linebotton a{
  color: #007bff;
}
.dark-mode .breadcrumb li{
  color: #000;
}
</style>

<script type="text/javascript">

function content_get_state() {
  var countryId = $('#country').val();
  $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
    var state = jQuery.parseJSON(data);
    var output='';
    output+='<option value="">Select State</option>';
    output1='<option value="">Select District</option>';
    var len=state.length;
    for (var i=0,j=len; i < j; i++) {
      output+='<option value="'+state[i].id+'">'+state[i].state+'</option>';
    }
    $('#state').html(output);
    $('#district').html(output1);
 });
}

function content_get_state_edit(countryId, state_id, district_id) {
    $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
      var state = jQuery.parseJSON(data);
      var output='';
      output+='<option value="">Select State</option>';
      var len=state.length;
      for (var i=0,j=len; i < j; i++) {

        var stateEdit = state_id;
        var selected = '';
        if (stateEdit == state[i].id) {
          selected ='selected';
        }
        output+='<option '+selected+' value="'+state[i].id+'">'+state[i].state+'</option>';
      }
      $('#state').html(output);
      content_get_district_edit(district_id);
   });
}


function get_district_by_state(state) {
  content_get_district(state);
}
function content_get_district(state_id) {
    $.post("<?php echo site_url('home/get_disctrict_by_stateId')?>",{state_id:state_id},function(data){
      var district = jQuery.parseJSON(data);
      console.log(district);
      var output='';
      output+='<option value="">Select District</option>';
      var len=district.length;
      for (var i=0,j=len; i < j; i++) {
        output+='<option value="'+district[i].id+'">'+district[i].district+'</option>';
      }
      $('#district').html(output);
   });
}

function content_get_district_edit(district_id) {
   var state_id =$('#state').val();
    $.post("<?php echo site_url('home/get_disctrict_by_stateId')?>",{state_id:state_id},function(data){
      var district = jQuery.parseJSON(data);
      console.log(district);
      var output='';
      output+='<option value="">Select District</option>';
      var len=district.length;
      for (var i=0,j=len; i < j; i++) {

      var distEdit = district_id
      var selected = '';
        if (distEdit == district[i].id) {
          selected = 'selected';
        }
        output+='<option '+selected+' value="'+district[i].id+'">'+district[i].district+'</option>';
      }
      $('#district').html(output);
   });
}

</script>


<script type="text/javascript">


</script>

<script type="text/javascript">
  $('#education').on('change',function(){
    var otherEdu = $('#education').val();
    if (otherEdu == 'education_others') {
      $('#education_others').show();
    }else{
      $('#education_others').hide();
    }
  });

  $('#profession').on('change',function(){
    var otherEdu = $('#profession').val();
    if (otherEdu == 'work_others') {
      $('#work_others').show();
    }else{
      $('#work_others').hide();
    }
  });

</script>


<style type="text/css">
@media only screen and (max-width: 768px){
  input[class=gender-radio] {
    margin: 1px -14px -6px;
  }
  input[name=has_sibling] {
    margin: 0px 3px 0;
  }
  input[name=sibling_in] {
    margin: 1px 0px 0px;
  }
}
</style>

<style type="text/css">
  .login-container .login-box .login-body label{
    /*color: #fff;*/
  }

  .login-container .login-box .login-body ul li{
    /*color: #fff;*/
  }

  .login-container{
    /*background: url(../assets/img/1.jpg); */
    /*background-size: 100% 100%; */
    background: #fff;
  }

 /* .login-container .login-box .login-body{
    border: 2px solid #e5e5e5;
    border-radius: 30px;
  }*/

  .login-container .login-box .login-body .login-title{
    color: #000;
  }

#watermark{
  opacity: 0.1;
  margin-left: 15%;
  height: 350px;
  position: absolute;
  margin-top: 25%;
}

</style>
<style>
  ::placeholder { /* Firefox, Chrome, Opera */
    color: #000 !important;
  }

  :-ms-input-placeholder { /* Internet Explorer 10-11 */
      color: #000 !important;
  }

  ::-ms-input-placeholder { /* Microsoft Edge */
      color: #000 !important;
  }
  #get_others_name{
     color: #000 !important;
  }
  #class_name{
     color: #000 !important;
  }
  .login-container .login-box .login-body .form-control{
    color: #000 !important;
  }
  .login-container{
    padding: 0;
  }
</style>
<script type="text/javascript">
  function change_location_set_cookie() {
      $('#location_change_modal').modal('show');
  }
</script>

<div class="modal fade" id="location_change_modal" tabindex="-1" aria-labelledby="locationChangeModalLabel" aria-hidden="true">
    <div class="modal-dialog m-0" style="height:auto;">
        <div class="modal-content" style="height:auto;">
            <div class="modal-header" style="padding: 15px 30px; border: none; background: #17a2b8; color: #fff; ">
                <h3>Set Location</h3>
            </div>
            <div class="modal-body">
                <div class="form-group">
                  <?php 
                   $country = $this->db->get('country_tbl')->result();
                  ?>
                 
                  <p>Country <font color="red">*</font></p>
                    <select class="form-control" name="country" required="" onchange="content_get_state_local_current()"  id="current_local_country">
                        <option value="">Select Country</option>
                        <?php foreach ($country as $key => $val) { ?>
                            <option value="<?php echo $val->id ?>"><?php echo $val->country ?></option>
                        <?php } ?>
                   </select>
                </div>

                <div class="form-group">
                  <p>State / Province <font color="red">*</font></p>
                  <select class="form-control" name="state" onchange="get_district_by_state_current(this.value)" required="" id="current_local_state">
                    <option value="">Select State / Province</option>
                  </select>
                </div>

                <div class="form-group">
                  <p>District / City <font color="red">*</font></p>
                  <select class="form-control" name="district" required="" id="current_district">
                    <option value="">Select District</option>
                  </select>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" onclick="submit_change_location()" class="btn btn-primary">Okay</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
   function content_get_state_local_current() {
        var countryId = $('#current_local_country').val();
        $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
            var state = jQuery.parseJSON(data);
            var output='';
            output+='<option value="">Select State</option>';
            output1='<option value="">Select District</option>';
            var len=state.length;
            for (var i=0,j=len; i < j; i++) {
              output+='<option  value="'+state[i].id+'_'+state[i].state+'">'+state[i].state+'</option>'; 
            }
            $('#current_local_state').html(output);
            $('#current_district').html(output1);
        });
    }

    function get_district_by_state_current(value) {
        var splitValue =  value.split("_");
        var state = splitValue[0];
        $.post("<?php echo site_url('country_controller/get_state_by_district')?>",{state:state},function(data){
          var district = jQuery.parseJSON(data);
          var output='';
          output+='<option value="">Select District</option>';
          var len=district.length;
          for (var i=0,j=len; i < j; i++) {
            output+='<option value="'+district[i].id+'_'+district[i].district+'">'+district[i].district+'</option>'; 
          }
          $('#current_district').html(output);
       });
    }

    function get_district_location_id_current(district) {
      var current_country = $('#current_local_country').val();
      var current_state = $('#current_local_state').val();
       $.post("<?php echo site_url('home/set_current_location_by_user')?>",{district:district,current_country:current_country,current_state:current_state},function(data){
        // console.log(data);
        location.reload();
        // var re = jQuery.parseJSON(data);
        
      });
    }

    function submit_change_location() {
      var current_country = $('#current_local_country').val();
      var current_state = $('#current_local_state').val();
      var current_district = $('#current_district').val();
       $.post("<?php echo site_url('home/set_current_location_by_user')?>",{district:current_district,current_country:current_country,current_state:current_state},function(data){
          $('#location_change_modal').modal('hide');
          get_location_wise_total_users();
      });
    }
</script>


<div class="modal" id="show_download_app" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="news-app-promo-text__download">Download the Mygroup App</div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="news-app-promo__section">

          <div class="container" style="margin-bottom: 12px;font-size: 20px;">
            <a class="flex-c-m s1-txt2 size3 how-btn" href="#" style="color:#3a464b; margin-bottom: 1rem; ">
              <img style="width: 20px;" src="<?php echo base_url().'assets/front/img/logo.png' ?>"> &nbsp;&nbsp; My Group
            </a>
          </div>

          <div class="news-app-promo-subsection">
            <a class="news-app-promo-subsection--link news-app-promo-subsection--playstore" href="https://play.google.com/store/apps/details?id=com.mygroup.apps" target="_parent">
              <img class="news-app-promo__play-store" src="//news.files.bbci.co.uk/include/newsspec/19854/assets/app-project-assets/google_play_store.svg" width="161" height="auto" border="0">
            </a>
          </div>

        </div>
      </div>
      
    </div>
  </div>
</div>


<style type="text/css">
  .news-app-promo__section {
    display: inline-block;
    margin: 0 auto;
    position: relative;
    width: 100%;
    text-align: center;
    margin-top: 8px;
  }
  .news-app-promo-text__download {
    font-size: 18px;
    font-weight: 600;
  }
  .news-app-promo-subsection {
    margin: 0 auto;
    margin-right: 10px;
    display: inline-flex;
  }

  .news-app-promo_mygroup-logo {
    display: inline-block;
    width: 50px;
    height: 50px;
    margin-bottom: 8px;
  }

  .news-app-promo__play-store{
    background: #607d8b;
    border-radius: 10px;
  }

  .news-app-promo-subsection--link {
    text-decoration: none;
    border: 0;
  }
  .social-icon-width{
     width: 35px;
      height: 35px;
  }
  .tableColor th, .tableColor td{
    color: #fff;
  }
</style>