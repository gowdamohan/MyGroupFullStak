<div class="container mb-5">
    <div class="row" style="background:#3c3a3a">
        <input type="hidden" id="location_value_get">
        <div class="col-4 pl-0">
            <div class="form-group" style="margin-bottom: 0;">
                <select class="form-control" name="select_type" id="notice_selectType" style="height: 28px;padding: 0rem 0.8rem; font-size: 12px; background: #3c3a3a; color: #fff; border: #3c3a3a;">
                    <option value="national">National</option>
                    <option value="regional">Regional</option>
                    <option value="local">Local</option>
                </select>
            </div>
        </div>
        <div class="col-4 pl-0">
            <div class="form-group" id="national_display" style="margin-bottom: 0;">
                <select class="form-control" name="location" id="notice_country_location" style="height: 28px;padding: 0rem 0.8rem; font-size: 12px; background: #3c3a3a; color: #fff; border: #3c3a3a;">
                    <?php foreach ($country as $key => $val) { ?>
                        <option value="<?php echo $val->id ?>"><?php echo $val->country ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group" id="state_display" style="margin-bottom: 0;display: none;">
                <select class="form-control" name="location" onclick="open_popup_location_state()" id="notice_stateLocation" style="height: 28px;padding: 0rem 0.8rem; font-size: 12px; background: #3c3a3a; color: #fff; border: #3c3a3a;">
                    <option value="">Select State</option>
                </select>
            </div>
            <div class="form-group" id="district_display" style="margin-bottom: 0;display: none;">
                <select class="form-control" name="location" onclick="open_popup_location_district()" id="notice_districtLocation" style="height: 28px;padding: 0rem 0.8rem; font-size: 12px; background: #3c3a3a; color: #fff; border: #3c3a3a;">
                    <option value="">Select District</option>
                </select>
            </div>
        </div>
        <div class="col-4 pr-0">
            <div class="form-group" style="margin-bottom: 0;">
                <select class="form-control" name="unions_cat" id="unions_cat" style="height: 28px;padding: 0rem 0.8rem; font-size: 12px; background: #3c3a3a; color: #fff; border: #3c3a3a;">
                    <option value="">All Category</option>
                    <?php foreach ($category as $key => $val) { ?>
                        <option value="<?php echo $val->id ?>"><?php echo $val->category ?></option>
                    <?php } ?>
                </select>
            </div>  
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

</div>

<div class="modal fade" id="location_state_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog m-0" style="height:auto;">
        <div class="modal-content" style="height:auto;">
            <div class="modal-header" style="padding: 15px 30px; border: none; background: #17a2b8; color: #fff; ">
                <h3>Select Location</h3>
            </div>
            <div class="modal-body">
                <div class="form-group">
                  <p>Country <font color="red">*</font></p>
                    <select class="form-control" name="country" required="" onchange="content_get_state()"  id="notice_country">
                        <option value="">Select Country</option>
                        <?php foreach ($country as $key => $val) { ?>
                            <option value="<?php echo $val->id ?>"><?php echo $val->country ?></option>
                        <?php } ?>
                   </select>
                </div>

                <div class="form-group">
                  <p>State / Province <font color="red">*</font></p>
                  <select class="form-control" name="state" required=""  onchange="get_state_location_id(this.value)" id="notice_state">
                    <option value="">Select State / Province</option>
                  </select>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="location_district_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog m-0" style="height:auto;">
        <div class="modal-content" style="height:auto;">
            <div class="modal-header" style="padding: 15px 30px; border: none; background: #17a2b8; color: #fff; ">
                <h3>Select Location</h3>
            </div>
            <div class="modal-body">
                <div class="form-group">
                  <p>Country <font color="red">*</font></p>
                    <select class="form-control" name="country" required="" onchange="content_get_state_local()"  id="notice_local_country">
                        <option value="">Select Country</option>
                        <?php foreach ($country as $key => $val) { ?>
                            <option value="<?php echo $val->id ?>"><?php echo $val->country ?></option>
                        <?php } ?>
                   </select>
                </div>

                <div class="form-group">
                  <p>State / Province <font color="red">*</font></p>
                  <select class="form-control" name="state" onchange="get_district_by_state(this.value)" required="" id="notice_local_state">
                    <option value="">Select State / Province</option>
                  </select>
                </div>

                <div class="form-group">
                  <p>District / City <font color="red">*</font></p>
                  <select class="form-control" name="district" required="" onchange="get_district_location_id(this.value)" id="notice_district">
                    <option value="">Select District</option>
                  </select>
                </div>
            </div>
        </div>
    </div>
</div>



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
    $('#notice_selectType').on('change',function(){
        var selectType = $('#notice_selectType').val();
        if (selectType == 'national') {
            $('#state_display').hide();
            $('#national_display').show();
            $('#district_display').hide();
            $('#location_value_get').val('');
            get_state_wise_missing_board();
        }else if(selectType == 'regional'){
            $('#state_display').show();
            $('#national_display').hide();
            $('#district_display').hide();
            open_popup_location_state();
        }else if(selectType == 'local'){
            $('#state_display').hide();
            $('#national_display').hide();
            $('#district_display').show();
            open_popup_location_district();
        }
    });

    function open_popup_location_state() {
        $('#location_state_modal').modal('show');
    }

    function open_popup_location_district() {
        $('#location_district_modal').modal('show');
    }

    $(document).ready(function(){
        var location_id = $('#notice_country_location').val();
        get_state_wise_missing_board()
    });

    $('#country_location').on('change',function(){
        var location_id = $('#notice_country_location').val();        
        get_state_wise_missing_board();
    });

    function get_state_location_id(value) {
        $('#location_value_get').val('');
        var splitValue =  value.split("_");

        var location_id = splitValue[0];

        var locationName = splitValue[1];

        var output = '<option>'+locationName+'</option>';

        $('#notice_stateLocation').html(output);
        $('#location_value_get').val(location_id);
        $('#location_state_modal').modal('hide');
        get_state_wise_missing_board();
    }

    function get_district_location_id(value) {
        $('#location_value_get').val('');
        var splitValue =  value.split("_");

        var location_id = splitValue[0];
        var locationName = splitValue[1];

        var output = '<option>'+locationName+'</option>';

        $('#notice_districtLocation').html(output);
        $('#location_value_get').val(location_id);
        $('#location_district_modal').modal('hide');
        get_state_wise_missing_board();
    }

    $('#unions_cat').on('change',function(){
        var location_id = $('#news_country_location').val();
        get_state_wise_missing_board();
    });


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
   $('#notice_select_lang').on('change',function(){
        var location_id = $('#notice_country_location').val();
        get_state_wise_missing_board();
    });

var noticeIds = [];
function get_state_wise_missing_board() {
    $('.notice_list').html('');
    $('#modal-loader-content').show();
    noticeIds = [];
    var location_id = $('#location_value_get').val();
    var unions_cat = $('#unions_cat').val();
    var notice_selectType = $('#notice_selectType').val();

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
    $.ajax({
        url: '<?php echo site_url('myunions/get_union_news_posted'); ?>',
        type: 'post',
        data: {'location_id':location_id,'unions_cat':unions_cat,'selectValue':selectValue,'notice_selectType':notice_selectType},
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
        url: '<?php echo site_url('myunions/get_union_news_posted_data'); ?>',
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
function construct_missing_data(index, notice) {
    var html = '';
    for(var k in notice){
        var url = '<?php echo site_url('myunions/unions_news_details/') ?>'+groupname+'/'+union_type+'/'+notice[k].id;
        html +='<div class="row mb-3">';
        html +='<a style="display:flex;width: 100%;" href="'+url+'" >';
        html +='<div class="col-8">';
        html +='<p style="font-size:12px;">'+notice[k].news_letter_name+'</p> <span style="float:right;font-size:10px;" >'+notice[k].post_date+'</span>';
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

<script type="text/javascript">
  function content_get_state() {
        var countryId = $('#notice_country').val();
        $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
            var state = jQuery.parseJSON(data);
            console.log(state);
            var output='';
            output+='<option value="">Select State</option>';
            output1='<option value="">Select District</option>';
            var len=state.length;
            for (var i=0,j=len; i < j; i++) {
              output+='<option  value="'+state[i].id+'_'+state[i].state+'">'+state[i].state+'</option>'; 
            }
            $('#notice_state').html(output);
            $('#notice_district').html(output1);
        });
    }

    function content_get_state_local() {
        var countryId = $('#notice_local_country').val();
        $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
            var state = jQuery.parseJSON(data);
            var output='';
            output+='<option value="">Select State</option>';
            output1='<option value="">Select District</option>';
            var len=state.length;
            for (var i=0,j=len; i < j; i++) {
              output+='<option  value="'+state[i].id+'_'+state[i].state+'">'+state[i].state+'</option>'; 
            }
            $('#notice_local_state').html(output);
            $('#notice_district').html(output1);
        });
    }

    function get_district_by_state(value) {
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
          $('#notice_district').html(output);
       });
    }
</script>