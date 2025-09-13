<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style media="screen">
  .profilepic{
    height:3rem;
    width:3rem;
    border-radius:50%;
  }
  .profilename{
    font-size:1.1rem;
    font-weight: 500;
    color:#057284;
    margin:0;
    line-height: 1.1;
  }
  .regionalname{
    font-size:0.8rem;
    font-weight: 500;
    color:#057284;
    margin:0;
    line-height: 1.1;
  }
  .btncategory{
    border:1px solid #035866;
    padding:2px 4px 3px;
    font-weight:500;
    font-size:10px;
    border-radius:5px;
    line-height: 1;
    color:#035866;
    background:#c0e9f0;
  }
  .btnfollow{
    border:1px solid #052ba8;
    padding:3px 6px 2px;
    font-weight:500;
    font-size:10px;
    border-radius:5px;
    line-height: 1;
    color:#052ba8;
    background:#dce2f7;

  }

  .btnfollowbutton{
    /*border:1px solid #052ba8;*/
    padding:3px 14px 2px;
    /*font-weight:600;*/
    font-size:14px;
    border-radius:5px;
    line-height: 1;
    color:#ffffff;
    background:#28a745;
  }
  .btnshortlist{
    border:1px solid #eb0570;
    padding:3px 6px 2px;
    font-weight:500;
    font-size:10px;
    border-radius:5px;
    line-height: 1;
    color:#eb0570;
    background:#f7dce9;
  }
  .label-success, .badge-success {
    background-color: #95b75d;
  }
  .label-danger, .badge-danger {
    background-color: #E04B4A;
  }
  .label {
    font-weight: 500;
  }
  .label {
    display: inline;
    padding: 0.2em 0.6em 0.3em;
    font-size: 75%;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25em;
}
</style>
<section class="pb-5">
  <div class="unions_list">
  </div>

  <div id="modal-loader-content" style="display: none; text-align: center;">
    <img src="<?php echo base_url('assets/loading-circle-gif.gif');?>" style="width:10%;">
  </div>
</section>

<style type="text/css">
    .checked {
      color: orange;
    }
</style>

<script type="text/javascript">
var groupname = '<?php echo $groupname ?>';
var union_type = '<?php echo $union_type ?>';
function check_user_login() {
  var groupname = '<?php echo $groupname ?>';
  var union_type = '<?php echo $union_type ?>';
  var userId = '<?php echo $isLoggedin ?>';
  if (userId !=0) {
      var url ='<?php echo site_url('myunions/myunions_me/') ?>'+groupname+'/'+union_type;
      window.location.href = url;
  }else{
      register_user();
  }
}


$(document).ready(function(){
  get_union_list_me()
});



function get_union_list_me() {
  var groupname = '<?php echo $groupname ?>';
  var union_type = '<?php echo $union_type ?>';
  var userId = '<?php echo $isLoggedin ?>';
  if (userId !=0) {
    $('.unions_list').html('');
    $('#modal-loader-content').show();
    union_list = [];
    $.ajax({
        url: '<?php echo site_url('myunions/get_union_list_me'); ?>',
        type: 'post',
        data: {'union_type':union_type},
        success: function(data) {
            var resData = JSON.parse(data);
            console.log(resData);
            if (resData) {
                $('.unions_list').html(construct_union_data(resData));
            }else{
              $('.unions_list').html('<h4 style="text-align: center;margin-top: 3rem;" >This number is not registered with any Unions</h4>');
              $('#modal-loader-content').hide();
            }
             $('#modal-loader-content').hide();
        }
    });
  }else{
    register_user();
    return false;
  }


}

function construct_union_data(unionData) {
    var html = '';
    for(var k in unionData){
      if (unionData[k].user_id !='') {
        var logo = '';
        if (unionData[k].client_logo !='') {
          logo = '<?php echo $this->filemanager->getFilePath("") ?>'+unionData[k].client_logo;
        }
        var urlView = '<?php echo site_url('myunions/union_me_single_page_unions_details/') ?>'+groupname+'/'+union_type+'/'+unionData[k].user_id;
        var JoinName = '';
        var JoinName1 = '';
        if (k == 'invite') {
          url = '<?php echo site_url('myunions/invite_membership_form/') ?>'+groupname+'/'+union_type+'/'+unionData[k].user_id;
          JoinName = 'Click here to Join Now';
          JoinName1 = 'Invited';
        }
        var regionalLang = unionData[k].regional_lang_name;
        if (unionData[k].regional_lang_name == null) {
          regionalLang = '';
        }
        html += '<div class="col-11 mx-auto p-2 my-2" style="box-shadow:0 0 10px #578b94;border-radius:20px;border:2px solid #057284;overflow:hidden;position:relative;">';
        html += '<div class="row mx-0">';
        if (k == 'Approved' || k == 'Director') {
          html += '<a href='+urlView+' >';
        }
        html += '<div class="col-12 px-1">';
        html += '<p class="profilename">'+unionData[k].name_of_the_organization+'</p>';
        html += '</div>';
        html += '<div class="col-12 px-1 mb-2">';
        html += '<p class="regionalname">'+regionalLang+'</p>';
        html += '</div>';
        html += '<div class="col-3 px-1">';
        html += '<div class="profilepic" style="background:url('+logo+')"></div>';
        html += '</div>';
        html += '<div class="col-7 px-1">';
        if (JoinName1 != '') {
          html += '<button onclick="url_onclick(\''+url+'\')" class="btn btn-primary">'+JoinName1+'</button>';
        }
        if (k == 'Received') {
          url1 = '<?php echo site_url('myunions/edit_membership_form/') ?>'+groupname+'/'+union_type+'/'+unionData[k].user_id;
          html += '<span class="label label-success">Application Submitted</span><br>';
          html += '<span onclick="edit_application_member(\''+url1+'\')"  class="label label-danger">Edit Application <i style="background-color:#E04B4A" class="fa fa-pencil-square"></i></span>';
        }
        if (k == 'Approved') {
          html += '<span class="label label-success">'+k+'</span>';
        }
        if (k == 'Rejected') {
          html += '<span class="label label-danger">'+k+'</span>';
        }
        if (k == 'Pending') {
          html += '<span class="label label-warning">'+k+'</span>';
        }
        if (k == 'Director') {
         html += '<span class="label label-success">'+k+'</span>';
        }

        if (JoinName !='') {
          html += '<p class="mb-0" onclick="url_onclick(\''+url+'\')" style="font-size:0.9rem;color:black;"><span class="btnfollowbutton">'+JoinName+'</span></p>';
        }
        html += '</div>';
        html += '</div>';
        html += '</div>';
        if (k == 'Approved' || k == 'Director') {
        html += '</a>';
        }
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
      }
     
     
    }
    return html;
}

function url_onclick(url){
  window.location.href = url;
}

function edit_application_member(url) {
  window.location.href = url;
}

</script>