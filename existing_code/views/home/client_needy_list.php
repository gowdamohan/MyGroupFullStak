<section class="pb-5">
    <div class="mt-3" id="client_needy_display">

    </div>
    <div id="modal-loader-content" style="display: none; text-align: center;">
      <img src="<?php echo base_url('assets/img/loading_icon.gif');?>" style="width:100%; height:400px;">
    </div>

</section>

<script type="text/javascript">
    $(document).ready(function(){
        get_client_needy_list()
    });


    var client_needy = [];
    function get_client_needy_list() {
        client_needy = [];
        var needy_type_id = '<?php echo $needy_type_id ?>';
        $('#client_needy_display').html('');
        $('#modal-loader-content').show();
        $.ajax({
            url: '<?php echo site_url('needy/get_client_needy_details'); ?>',
            type: 'post',
            data:{'needy_type_id':needy_type_id},
            success: function(data) {
                var needyData = JSON.parse(data);
                console.log(needyData);
                if (needyData.length > 0) {
                    client_needy = needyData;
                    callReportGetter(0);
                }else{
                    $('#client_needy_display').html('Result not found');
                    $('#modal-loader-content').hide();
                }
            },
        });
    }

    function callReportGetter(index){
      if(index < client_needy.length) {
        get_client_needy_report(index);
      } else {
        $('#modal-loader-content').hide();
      }
    }


    function get_client_needy_report(index){
        var client_neeyd_ids = client_needy[index];
        $.ajax({
            url: '<?php echo site_url('needy/get_client_needy_list'); ?>',
            type: 'post',
            data:{'client_neeyd_ids':client_neeyd_ids},
            success: function(data) {
                var needyData = JSON.parse(data);
                console.log(needyData);
                constructReport(index, needyData);
            },
        });
    }

    function constructReport(index, needyData) {
      $('#client_needy_display').append(construct_news_data(index, needyData));
      index++;
      callReportGetter(index);
    }

    function construct_news_data(index, needyData) {
        var groupname = '<?php echo $groupname ?>';
        var needy_type = '<?php echo $needy_type ?>';
        var html = '';
        for(var k in needyData){
            var url = '<?php echo site_url('needy/client_needy_view_by_id/') ?>'+groupname+'/'+needy_type+'/'+needyData[k].id;
            html +='<div class="col-11 mx-auto p-2 mb-2" style="box-shadow:0 0 10px #578b94;border-radius:20px;border:2px solid #057284;">';
            html +='<div class="row mx-0">';

            html +='<div class="col-4 px-1 d-flex align-items-center justify-content-center" style="flex-direction:column;">';
            html +='<div class="profilepic" style="background:url('+needyData[k].img+')"></div>';
            html +='<h6 class=" mt-2 mb-0" style="color:#057284;">4.5 <i class="fas fa-star" aria-hidden="true"></i></h6>';
            html +='</div>';
            
            html +='<div class="col-8 px-1">';
            html +='<h4 class="profilename">'+needyData[k].services_name+'</h4>';
            html +='<h4 class="regionalname">'+needyData[k].name_regional_language+'</h4>';
            html +='<p class="vfees" style="font-size:0.8rem;">Visiting Fee: '+needyData[k].consultancy_charges_from+' to '+needyData[k].consultancy_charges_to+'</p>';
            html +='<p class="vfees" style="font-size:0.8rem;">Area: '+needyData[k].area+'</p>';
            html +='<div class="row mx-0">';
            html +='<div class="col-8 px-0">';
            html +='<a href="'+url+'" class="btn btn-sm btn-info col-12" style="background:#057284;border-radius:30px;">Book Now</a>';
            html +='</div>';
            html +='<div class="col-4 px-0 d-flex align-items-center justify-content-center">';
            html +='<a href="#">';
            html +='<i class="fas fa-heart-o" aria-hidden="true" style="color:#fa0f4e;font-size:1.3rem;"></i>';
            html +='</a>';
            html +='</div>';
            html +='</div>';
            html +='</div>';
            html +='</div>';
            html +='</div>';

            // html +='<div class="row mb-3">';
            //  html +='<a style="display:flex;width: 100%;" href="'+url+'" >';
            // html +='<div class="col-4">';
            // html +='<img class="d-block w-100" style="height:80px;" src="'+needyData[k].img+'" alt="First slide">';
            // html +='</div>';
            // html +='<div class="col-8">';
            // html +='<p style="font-size:12px;">'+needyData[k].services_name+' ( ' +needyData[k].name_regional_language+ ' )'+'</p>';
            // html +='<p style="font-size:12px;">'+needyData[k].area+'</p>';
            // html +='<p style="font-size:12px;"><span class="fas fa-star checked"></span><span class="fas fa-star checked"></span><span class="fas fa-star checked"></span><span class="fas fa-star"></span><span class="fas fa-star"></span></p>';
            // html +='</div>';
            // html +='</a>';
            // html +='</div>';
        }
        return html;
    }



</script>
<style media="screen">
  .profilepic{
    height:5rem;
    width:5rem;
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
</style>
<!-- <section class="pb-5">

  <?php
  for ($i=1; $i < 10 ; $i++) {
    ?>
    <div class="col-11 mx-auto p-2 mb-2" style="box-shadow:0 0 10px #578b94;border-radius:20px;border:2px solid #057284;">
      <div class="row mx-0">
        <div class="col-4 px-1 d-flex align-items-center justify-content-center" style="flex-direction:column;">
          <div class="profilepic" style="background:url('../../../assets/radio/1.jpg')">

          </div>
          <h6 class=" mt-2 mb-0" style="color:#057284;">4.5 <i class="fas fa-star" aria-hidden="true"></i></h6>
        </div>
        <div class="col-8 px-1">
          <h4 class="profilename">Name</h4>
          <h4 class="regionalname">ಶಂಕರ್</h4>
          <p class="vfees" style="font-size:0.8rem;">Visiting Fee: 500.00</p>
          <p class="vfees" style="font-size:0.8rem;">Area: Kengeri</p>
          <div class="row mx-0">
            <div class="col-8 px-0">
              <a href="#" class="btn btn-sm btn-info col-12" style="background:#057284;border-radius:30px;">Book Now</a>
            </div>
            <div class="col-4 px-0 d-flex align-items-center justify-content-center">
              <a href="#">
                <i class="fas fa-heart-o" aria-hidden="true" style="color:#fa0f4e;font-size:1.3rem;"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
  }
   ?>

</section> -->

<style type="text/css">
    .checked {
      color: orange;
    }
</style>
