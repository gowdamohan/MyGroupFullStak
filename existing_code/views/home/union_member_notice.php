<section style="background:#ebebeb;">
  <div class="row">
    <div class="col-2">
      <a class="btn btn-info btn-sm" style="font-style:italic;" href="<?php echo site_url('myunions/unions_single/'.$groupname.'/'.$union_type.'/'.$unionId) ?>">Back</a>
    </div>
    <div class="col-10">
      <center>
        <h3>Union Notice</h3>
      </center>
    </div>
  </div>
  <div class="row mt-2 mb-4" style="background: #e5eff0;">
    <div class="col-3">
      <div class="form-group" style="margin-bottom: 0;">
        <input type="button"id="notice_all" onclick="get_notice_board_details('All')" style="width: 100%;" class="btn btn-info btn-sm" value="All" >
      </div>
    </div>
    <div class="col-3">
      <div class="form-group" style="margin-bottom: 0;">
        <input type="button" id="image_button" onclick="get_notice_board_details('1')" style="width: 100%;" class="btn btn-sm" value="Image" >
      </div>
    </div>
    <div class="col-3">
      <div class="form-group" style="margin-bottom: 0;">
        <input type="button" id="video_button" onclick="get_notice_board_details('2')" style="width: 100%;" class="btn btn-sm" value="Video" >
      </div>  
    </div>
    <div class="col-3">
      <div class="form-group" style="margin-bottom: 0;">
        <input type="button" id="text_button" onclick="get_notice_board_details('3')" style="width: 100%;" class="btn btn-sm" value="Text" >
      </div>  
    </div>
</div>

<div class="notice_list">
</div>

<div id="modal-loader-content" style="display: none; text-align: center;">
  <img src="<?php echo base_url('assets/loading-circle-gif.gif');?>" style="width:10%;">
</div>
</section>


<style type="text/css">
.btn-plus {
    background: #d31f26;
    border: 0;
    border-radius: 50%;
    width: 42px;
    height: 42px;
    display: inline-block;
    color: #fff;
    outline: none;
    position: fixed;
    text-align: center;
    line-height: 57px;
    right: 10px;
    bottom: 75px;
    z-index: 99;
}
.btn-plus img {
    width: 35px;
    height: 35px;
    margin-top: -18px;
    animation: rotate-img 1s infinite;
}
</style>


<script type="text/javascript">
  
  function get_notice_board_details(selectValue) {
      if (selectValue =='All') {
          $('#notice_all').addClass('btn-info');
          $('#image_button').removeClass('btn-info');
          $('#video_button').removeClass('btn-info');
          $('#text_button').removeClass('btn-info');
      }else if(selectValue =='1'){
          $('#notice_all').removeClass('btn-info');
          $('#image_button').addClass('btn-info');
          $('#video_button').removeClass('btn-info');
          $('#text_button').removeClass('btn-info');
      }else if(selectValue =='2'){
          $('#notice_all').removeClass('btn-info');
          $('#image_button').removeClass('btn-info');
          $('#video_button').addClass('btn-info');
          $('#text_button').removeClass('btn-info');

      }else if(selectValue =='3'){
          $('#notice_all').removeClass('btn-info');
          $('#image_button').removeClass('btn-info');
          $('#video_button').removeClass('btn-info');
          $('#text_button').addClass('btn-info');
      }
      var location_id = $('#notice_country_location').val();
      get_state_wise_missing_board();
  }
  
var noticeIds = [];
function get_state_wise_missing_board() {
    $('.notice_list').html('');
    $('#modal-loader-content').show();
    noticeIds = [];
  
    var selectValue = $('.btn-info').val();

    if (selectValue =='Image') {
        selectValue ='1';
    }
    if (selectValue =='Video') {
        selectValue ='2';
    }
    if (selectValue =='Text') {
        selectValue ='3';
    }
    var unionId = '<?php echo $unionId ?>';
    $.ajax({
        url: '<?php echo site_url('myunions/get_union_notice_posted'); ?>',
        type: 'post',
        data: {'selectValue':selectValue,'unionId':unionId},
        success: function(data) {
            var resData = JSON.parse(data);
            if (resData.length > 0) {
                noticeIds = resData;
                callReportGetter(0);
            }else{
                $('.notice_list').html('<h3 style="text-align: center;margin-top: 3rem;" >Result not found</h3>');
                $('#modal-loader-content').hide();
            }

            // $('.missing_list').html(construct_missing_data(resData));
        }
    });
}

function callReportGetter(index){
    if(index < noticeIds.length) {
        getReport(index);
    } else {
        $('#modal-loader-content').hide();
    }
}


function getReport(index){
    var notice_ids = noticeIds[index];
    $.ajax({
        url: '<?php echo site_url('myunions/get_union_notice_posted_data'); ?>',
        type: 'post',
        data:{'notice_ids':notice_ids},
        success: function(data) {
            var noticeData = JSON.parse(data);
            console.log(noticeData);
            constructMissingReport(index, noticeData);
        },
    });
}

 function constructMissingReport(index, newsData) {
    $('.notice_list').append(construct_missing_data(index, newsData));
    index++;
    callReportGetter(index);
}
var groupname = '<?php echo $groupname ?>';
var union_type = '<?php echo $union_type ?>';
var unionId = '<?php echo $unionId ?>';
function construct_missing_data(index, notice) {
    var html = '';
    for(var k in notice){
        var url = '<?php echo site_url('myunions/unions_notice_details/') ?>'+groupname+'/'+union_type+'/'+notice[k].id+'/'+unionId;
        html +='<div class="row mb-3">';
        html +='<a style="display:flex;width: 100%;" href="'+url+'" >';
        html +='<div class="col-8">';
        html +='<p style="font-size:12px;">'+notice[k].news_letter_name+'</p>';
        html +='</div>';
        html +='<div class="col-4">';
        html +='<img class="d-block w-100" style="height:80px;" src="'+notice[k].img+'" alt="First slide">';
        html +='</div>';
        html +='</a>';
        html +='</div>';
    }
    return html;
}


</script>

<div class="modal fade" id="missing_found_model" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog m-0" style="height:auto;">
        <div class="modal-content" style="height:auto;">
            <div class="modal-header">
              <h4 class="modal-title">Details</h4>
              <button type="button" class="close" onclick="$('#missing_found_model').modal('hide');">&times;</button>
            </div>
           
            <div class="modal-body" id="content_missing_found" >
                
            </div>
            <div class="text-right">
                <a class="btn btn-danger btn-sm" style="width: 6rem; border-radius: 3.45rem;" onclick="$('#missing_found_model').modal('hide');" href="javascript:void(0)">Close</a>
            </div>
        </div>
    </div>
</div>