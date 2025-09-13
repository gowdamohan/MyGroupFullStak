<style media="screen">
  .footerforicons{
    padding:0.4rem 0.1rem;
    background:rgb(5, 114, 132,1) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    z-index: 99999 !important;
    backdrop-filter:blur(8px);
  }
  .footerforicons a{
    display:flex !important;
    flex-direction: column;
    align-items: center !important;
    justify-content: center !important;
  }
  .footerforicons a:hover{
    background: transparent !important;
  }
  .footerforicons a i{
    background:white;
    height:1.5rem;
    width:1.5rem;
    color:black;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:50%;
    font-size:0.8rem !important;
    margin-bottom: 0.3rem;
  }
  .footerforicons a p{
    text-align:center;
    font-size:0.6rem !important;
    margin-top:0 !important;
    color:white !important;
    text-shadow:none !important;
  }
</style>

 <div class="navbar-bottom" id="buttonMobile">

   
    <?php 
      if ($group_details->name =='Myunions') { ?>
        <a style="border: 1px solid #ccc; border-radius: 50px; height: 30px;" onclick="get_button_group_page('union')" class="bottom-icon-width"  href="javascript:void(0)"><p style="margin-top: 0.3rem;" >Union</p></a>

        <a style="border: 1px solid #ccc; border-radius: 50px; height: 30px;"  onclick="get_button_group_page('chats')" class="bottom-icon-width"  href="javascript:void(0)"><p style="margin-top: 0.3rem;">Chats</p></a>
        <a style="border: 1px solid #ccc; border-radius: 50px; height: 30px;"  onclick="get_button_group_page('posts')" class="bottom-icon-width active1"  href="javascript:void(0)"><p style="margin-top: 0.3rem;">Posts</p></a>
        <a style="border: 1px solid #ccc; border-radius: 50px; height: 30px;"  onclick="get_button_group_page('docs')" class="bottom-icon-width"  href="javascript:void(0)"><p style="margin-top: 0.3rem;">Docs</p></a>
        <a style="border: 1px solid #ccc; border-radius: 50px; height: 30px;"  onclick="get_button_group_page('meetings')" class="bottom-icon-width"  href="javascript:void(0)"><p style="margin-top: 0.3rem;">Meetings</p></a>
      <?php }
    ?>
  </div>
<script type="text/javascript">
  $(".bottom-icon-width").click(function(){
    $(this).addClass("active1").siblings().removeClass("active1");
  });
</script>
<style type="text/css">
  .lngg{
    position:fixed;
    bottom:56px;
    text-align: center;
    left: 30%;
    width: 147px;
  }

  .filter{
    position:fixed;
    bottom:56px;
    text-align: center;
    left: 52%;
    width: 147px;
  }
  .sort{
    position:fixed;
    bottom:56px;
    text-align: center;
    left: 52%;
    width: 147px;
  }
  #buttonMobile a:hover{
    color: #fff;
  }
</style>
<script>

function send_news_users() {
  $.ajax({
    url: '<?php echo site_url('welcome/profile_login_modal'); ?>',
    type: 'post',
    success: function(data) {
      var profile = JSON.parse(data);
      construct_login_profile(profile.country_flag, profile.education, profile.profession, profile.station);
    }
  });
}

function short_list_news(newsId, select) {
  $.ajax({
    url:'<?php echo site_url('welcome/short_list_by_user_id') ?>',
    type:"post",
    data: {'newsId':newsId,'select':select},
    success:function(data){
      // console.log(data);
      location.reload();
    }
  });
}
  function lang_selection_submit($langId) {
    $('#langId').val($langId);
    $('#lang-form').submit();
  }
  function open_lang_selection() {
    $('.lngg').toggle('show');
  }
  
  function open_filter_selection() {
    $('.sort').hide();
    $('.filter').toggle('show');
  }

  function open_sort_selection() {
    $('.sort').toggle('show');
  }

  // $("#bottom-back-button").click(function(){
  //     console.log($("#backBtn").attr('href'));
  //     if($("#backBtn").attr('href') === undefined) {
  //         window.location = '<?php // echo site_url("dashboard"); ?>';
  //     } else {
  //         $("#backBtn").click();
  //     }
  // });
  //$(document).ready(function(){
      //var href = $("#backBtn").attr('href');
      //$("#bottom-back-button").attr('href', href);
  //});
</script>

<style type="text/css">


#titleDiv{
  word-wrap: break-word;
}
.bottom-icon-width{
  width: 25px;
  height: 25px;
}
.navbar-bottom {
  overflow: hidden;
  background-color: #3c3a3a;
  position: fixed;
  bottom: 0;
  width: 100%;
  margin-bottom: 0px;
  box-shadow: 0px 0px 6px #c3c3c3;
  display: flex;
}

.navbar-bottom {
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -ms-flex-align: center;
  align-items: center;
  -ms-flex-pack: justify;
  justify-content: space-between;
}



.navbar-bottom a:disabled {
  pointer-events: none; 
  color: #fff
}

.navbar-bottom a {
  float: left;
  display: block;
  color: #fff;
  text-align: center;
  padding: 0px 6px;
  text-decoration: none;
  font-size: 12px;
  width: 16.6%;
}

.navbar-bottom a i{
  font-size: 28px;
}

.navbar-bottom a:hover {
  background: #3c3a3a;
  color: black;
}
.navbar-bottom a.active {
    /* background-color: #6893CA; */
    color: #fff;
    /* border-radius: 8px; */
}
p
{
  margin-bottom: 0px;
  margin-top: -3px;
}
.navbar-bottom a i {
    font-size: 22px;
}
.navbar-bottom   a i.active {
    font-size: 25px;
}
.disabled_new{
  color: #eee !important;
}
</style>

<style type="text/css">
  

  .footer-social-icon a{
    margin-right: 8px;
  }

  .social-icon-width{
     width: 35px;
      height: 35px;
  }
  .footer-social-icon .fa{
    font-size: 27px;
    color: #fff;
    width: 35px;
    height: 35px;
  }

.footer-section {
  background: #332e2e;
  position: relative;
}
.footer-cta {
  border-bottom: 1px solid #373636;
}
.single-cta i {
  color: #ff5e14;
  font-size: 30px;
  float: left;
  margin-top: 8px;
}
.cta-text {
  padding-left: 15px;
  display: inline-block;
}
.cta-text h4 {
  color: #fff;
  font-size: 20px;
  font-weight: 600;
  margin-bottom: 2px;
}
.cta-text span {
  color: #757575;
  font-size: 15px;
}
.footer-content {
  /*position: relative;*/
  z-index: 2;
}
.footer-pattern img {
  position: absolute;
  top: 0;
  left: 0;
  height: 330px;
  background-size: cover;
  background-position: 100% 100%;
}

.footer-logo img {
    max-width: 200px;
}
.footer-text p {
  margin-bottom: 14px;
  font-size: 14px;
      color: #7e7e7e;
  line-height: 28px;
}
.footer-social-icon span {
  color: #fff;
  display: block;
  font-size: 20px;
  font-weight: 700;
  font-family: 'Poppins', sans-serif;
  margin-bottom: 20px;
}

.footer-widget-heading h3 {
  color: #fff;
  font-size: 20px;
  font-weight: 600;
  margin-bottom: 40px;
  position: relative;
}
.footer-widget-heading h3::before {
  content: "";
  position: absolute;
  left: 0;
  bottom: -15px;
  height: 2px;
  width: 50px;
  background: #ff5e14;
}
.footer-widget ul li {
  display: inline-block;
  float: left;
  width: 50%;
  margin-bottom: 12px;
}
.footer-widget ul li a:hover{
  color: #ff5e14;
}
.footer-widget ul li a {
  color: #878787;
  text-transform: capitalize;
}
.subscribe-form {
  position: relative;
  overflow: hidden;
}
.subscribe-form input {
  width: 100%;
  padding: 14px 28px;
  background: #fff;
  border: 1px solid #2E2E2E;
  color: #fff;
}
.subscribe-form button {
    position: absolute;
    right: 0;
    background: #ff5e14;
    padding: 13px 20px;
    border: 1px solid #ff5e14;
    top: 0;
}
.subscribe-form button i {
  color: #fff;
  font-size: 22px;
  transform: rotate(-6deg);
}
.copyright-area{
  background: #202020;
  padding: 25px 0;
}
.copyright-text p {
  margin: 0;
  font-size: 14px;
  color: #878787;
}
.copyright-text p a{
  color: #ff5e14;
}
.footer-menu li {
  display: inline-block;
  margin-left: 20px;
}
.footer-menu li:hover a{
  color: #ff5e14;
}
.footer-menu li a {
  font-size: 14px;
  color: #878787;
}
</style>
 <script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/parsley.js"></script>
 <script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/pnotify.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init();
</script>
<!-- bootstrap script -->


<script type="text/javascript">
  
$(".input").focus(function() {
  $("#search").addClass("move");
});
$(".input").focusout(function() {
  $("#search").removeClass("move");
  $(".input").val("");
});

$(".fa-search").click(function() {
  $('.form').toggleClass('background')
  $(".input").toggleClass("active");
  $("#search").toggleClass("active");
});


function search_go() {
  var search_name =  $('#search_go').val();
  window.location.href = '<?php echo site_url('welcome/search_bar_list/'); ?>'+search_name;
}


</script>

<script type="text/javascript">
  function terms_conditions() {
    $('#tnc').model('show');
    $.ajax({
      url:'<?php echo site_url('welcome/get_terms_condition') ?>',
      type:"post",
      success:function(data){
        $('#tnc_content').html(data);
      }
    });
  }
</script>


</body>
</html>
