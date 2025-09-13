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
$(document).ready(function(){
  get_union_list()
});

var union_list = [];

function get_union_list() {
  $('.unions_list').html('');
  $('#modal-loader-content').show();
  union_list = [];
  $.ajax({
      url: '<?php echo site_url('myunions/get_union_list_ids'); ?>',
      type: 'post',
      data: {'union_type':union_type},
      success: function(data) {
          var resData = JSON.parse(data);
          console.log(resData);
          if (resData.length > 0) {
              union_list = resData;
              callReportGetter(0);
          }else{
              $('.unions_list').html('<h3 style="text-align: center;margin-top: 3rem;" >Result not found</h3>');
              $('#modal-loader-content').hide();
          }

          // $('.missing_list').html(construct_missing_data(resData));
      }
  });
}
function callReportGetter(index){
  if(index < union_list.length) {
    getReport(index);
  } else {
    $('#modal-loader-content').hide();
  }
}
function getReport(index){
  var unions_ids = union_list[index];
  $.ajax({
    url: '<?php echo site_url('myunions/get_union_list_data'); ?>',
    type: 'post',
    data:{'unions_ids':unions_ids,'union_type':union_type},
    success: function(data) {
      var unionData = JSON.parse(data);
      console.log(unionData);
      constructUnionReport(index, unionData);
    },
  });
}
function constructUnionReport(index, unionData) {
  $('.unions_list').append(construct_union_data(index, unionData));
  index++;
  callReportGetter(index);
}
var groupname = '<?php echo $groupname ?>';
var union_type = '<?php echo $union_type ?>';
function construct_union_data(index, unionData) {
    var html = '';
    for(var k in unionData){
      var logo = '';
      if (unionData[k].client_logo !='') {
        logo = '<?php echo $this->filemanager->getFilePath("") ?>'+unionData[k].client_logo;
      }
      var regionalLangName = unionData[k].regional_lang_name;
      if (unionData[k].regional_lang_name == null) {
        regionalLangName = '';
      }

      html += '<div onclick="single_union_view('+unionData[k].id+')" class="col-11 mx-auto p-2 my-2" style="box-shadow:0 0 10px #578b94;border-radius:20px;border:2px solid #057284;overflow:hidden;position:relative;">';
      html += '<div class="row mx-0">';
      html += '<div class="col-12 px-1">';
      html += '<p class="profilename">'+unionData[k].name_of_the_organization+'</p>';
      html += '</div>';
      html += '<div class="col-12 px-1 mb-2">';
      html += '<p class="regionalname">'+regionalLangName+'</p>';
      html += '</div>';
      html += '<div class="col-2 px-1">';
      html += '<div class="profilepic" style="background:url('+logo+')"></div>';
      html += '</div>';
      html += '<div class="col-10 px-1 d-flex align-items-center justify-content-start">';
      html += '<div class="" style="width:100%;">';
      html += '<p class="mb-0" style="font-size:0.9rem;color:black;">No of Members : <span style="font-weight:500;">'+unionData[k].member_count+'</span> </p>';
      html += '<div class="row mx-0 mt-2">';
      html += '<div class="col-4">';

      html += '</div>';
      html += '<div class="col-8">';
      html += '<div class="d-flex alig-items-center justify-content-between" style="width:100%;">';
      html += '<a class="btnfollow">Follow <i class="fas fa-plus"></i> <i class="fas fa-check"></i></a>';
      html += '<a class="btnshortlist"><i class="far fa-heart"></i></a>';
      html += '</div>';
      html += '</div>';
      html += '</div>';
      html += '</div>';
      html += '</div>';
      html += '</div>';
      html += '</div>';
     
    }
    return html;
}

function single_union_view(union_id) {
  var href1 = '<?php echo site_url('myunions/unions_single/') ?>'+groupname+'/'+union_type+'/'+union_id;
  window.location.href=href1;
}

</script>