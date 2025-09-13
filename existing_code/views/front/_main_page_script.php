<script type="text/javascript">
	$(document).ready(function(){
		get_my_groups_apps();
		get_header_ads_group_wise_location_wise();
	});

	function get_my_groups_apps(apps_name = 'My Apps') {
		$.ajax({
			url:'<?php echo site_url('home/get_my_groups_apps') ?>',
			type:'post',
			data:{'apps_name':apps_name},
			success:function(data){
				var resData = $.parseJSON(data);
				$('#top_myapps').html(construct_my_group_apps_data(resData));
			}
		});
	}
	function construct_my_group_apps_data(resData) {
	var html =`<li class="nav-item text-center" style="line-height: 0.5; position: fixed;background:#057284;width: 2rem;line-height: 0.9;">
        <a class="nav-link" data-toggle="modal" onclick="get_all_mygroups_apps()" data-target="#more_groups_apps" href="#">
          <i class="fa fa-th-large" style="color:#f0e8e8;" data-toggle="modal" data-target="#more_groups_apps"></i><br>
          <span style="font-size:9px; color:#f0e8e8">More</span>
        </a>
      </li>`;
      for (var i = 0; i < resData.length; i++) {
      	var url = '<?php echo site_url('group/') ?>'+resData[i].name
      	var baseurl ='<?php echo base_url() ?>'+resData[i].icon;
      	var style = 'line-height: 0.5; margin-right: 1.7rem;';
      	if (i == 0) {
      		style = 'line-height: 0.5; margin-right: 1.7rem;position: relative;z-index: 99999;margin-left: 3rem;'
      	}
      	html+=` <li class="nav-item text-center" style="${style}">
          <a class="nav-link" href="${url}">
            <img  style="width: 20px;" src="${baseurl}"><br>
            <span style="font-size:9px">${resData[i].name}</span>
            </a>
        </li>`;
      }
      return html;
	}
	function get_all_mygroups_apps() {
		$.ajax({
			url:'<?php echo site_url('home/get_all_mygroups_apps') ?>',
			type:'GET',
		 	dataType: 'json',
			success:function(data){
				var html ='';
				$.each(data, function(index, element) {
		         	html +='<span style="font-size:1.2rem;color:#fff;font-weight: 700;">'+index+'</span>';
					html +='<hr class="mt-1">';					
					html +='<div class="row text-center">';
					for (var i = 0; i < element.length; i++) {
						var groupUrl =  '<?php echo site_url('group/') ?>'+element[i].name;
						var groupbaseurl ='<?php echo base_url() ?>'+element[i].icon
						html +='<div class="col-3" id="mobile_more">';
			           	html +='<a class="nav-link" href="'+groupUrl+'">'
			            html +='<img  style="width: 36px;" src="'+groupbaseurl+'"><br>'
			            html +='<span style="font-size:9px;color:#fff">'+element[i].name+'</span>'
			            html +='</a>';
			            html +='</div>';
					}
					html +='</div>';
		        });
				$('#total_apps_popup').html(html);
			}
		});
	}

function dark_light_mode() {

    var mode = $('#darMode').attr("class");
    var switch_mode = 1;
    if (mode == 'fa fa-sun-o') {
      switch_mode = 0
    }
    $.ajax({
      url:'<?php echo site_url('home/switch_darkmode') ?>',
      type:'post',
      data:{'switch_mode':switch_mode},
      success : function(data){
        // console.log(data);
        if (switch_mode == 0) {
          $('#darMode').addClass("fa-adjust");
          $('#darMode').removeClass("fa-sun-o");
        }else{
          $('#darMode').removeClass("fa-adjust");
          $('#darMode').addClass("fa-sun-o");
        }
        location.reload();
      }
    });
  }

function get_header_ads_group_wise_location_wise() {
	$.ajax({
      url:'<?php echo site_url('home/get_header_ads') ?>',
      type:'post',
      data:{'main_app':'<?php echo $this->uri->segment(3) ?>', 'sub_app':'<?php echo $this->uri->segment(4) ?>'},
      success : function(data){
       	var adsdata = $.parseJSON(data);
       	console.log(adsdata);
       	var ol_html ='';
       	var k = 1;
     		for (var i = 0; i < adsdata.length; i++) {
     			var active = '';
     			if (k == 1){
     				active = 'active';
     			} 
     			ol_html +=`<li data-target="#carouselExampleIndicators" data-slide-to="${k} ?>" class="${active}"></li>`;
     			k++; 
     		}

     		$('#ol-carosual').html(ol_html);

   			var carouselInner_html ='';
       	var l = 1;
     		for (var i = 0; i < adsdata.length; i++) {
     			var active = '';
     			if (l == 1){
     				active = 'active';
     			}
     			var image_path ='';
     			if (adsdata[i].img != null) {
     				image_path =adsdata[i].img
     			}
     			
     			carouselInner_html +=` <div class="carousel-item ${active}">
     			 <a target="_blank" href="${adsdata[i].image_url}">
              <img class="d-block w-100" height="150px" src="${image_path}" alt="First slide">
            </a>
          </div>
     			`;
     			l++; 
     		}
     		$('#carouselInner').html(carouselInner_html);

      }
    });
}

</script>

 

<div class="modal fade" id="more_groups_apps" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog m-0" style="height:auto;">
    <div class="modal-content" style="height:auto; background: #4c4444; ">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span style="color: #fff" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="total_apps_popup">
       
      </div>
    </div>
  </div>
</div>

