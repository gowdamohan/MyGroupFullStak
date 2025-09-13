<?php 
		$topHeaders = [];
		if ($mediaType == 'tv') {
			$topHeaders = ['Profile', 'View','Swicher','Playout','Local-Mixer','Online-Mixer'];
		}else if($mediaType == 'radio'){
			$topHeaders = ['Profile', 'View','Swicher','Playout'];
		}else if($mediaType == 'news'){
			$topHeaders = ['Profile', 'View','Upload'];
		}else if($mediaType == 'magazine'){
			$topHeaders = ['Profile', 'View','Upload'];
		}else if($mediaType == 'webnews'){
			$topHeaders = ['Profile', 'View','Link'];
		}else if($mediaType == 'youtube'){
			$topHeaders = ['Profile', 'View','Link'];
		}else{
			$topHeaders = [];
		} ?>	

<div class="page-content">
	<ul class="x-navigation x-navigation-horizontal x-navigation-panel" style="height: 30px; ">
		<li style="text-align: center;float: initial;font-size: 22px;color: #fff;" ><?php echo $get_media_type->media_name ?></li>
	</ul>
	<ul class="x-navigation x-navigation-horizontal x-navigation-panel" style="height: 30px;">

		<li class="xn-icon-button" style="position: relative;">
			<?php $i = 1; foreach ($topHeaders as $key => $val) { ?>
				<span onclick="page_display('<?php echo $val ?>')" class="label label-default label-form <?php 
				if($i == 1){
					echo 'active';
				} ?>"><?php echo $val ?></span>	
			<?php $i++; } ?>                          
		</li>
		<div class="view_profile_section" style="position: absolute;right: 0;top: 0;">
			
			<li class="xn-icon-button pull-right" style="background: #e04b4a;">
			    <a href="#"><img style="width:30px" src="<?php echo base_url().'assets/back_end/img/icon/youtube.png' ?>"></a>                       
			</li>
			<li class="xn-icon-button pull-right" style="background: #1caf9a;">
			    <a href="#"><img style="width:30px" src="<?php echo base_url().'assets/back_end/img/icon/webnews.png' ?>"></a>                       
			</li>
			<li class="xn-icon-button pull-right" style="background: #fe970a;">
			    <a href="#"><img style="width:30px" src="<?php echo base_url().'assets/back_end/img/icon/magzine.png' ?>"></a>                       
			</li>

			<li class="xn-icon-button pull-right" style="background: yellow;">
			    <a href="#"><img style="width:30px" src="<?php echo base_url().'assets/back_end/img/icon/e-paper.png' ?>"></a>
			    <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging ui-draggable">
			        <div class="panel-heading ui-draggable-handle">
			            <h3 class="panel-title"><span class="fa fa-comments"></span> TV </h3>                                
			            <div class="pull-right">
			                <span class="label label-danger">4 new</span>
			            </div>
			        </div>
			        <div class="panel-body list-group list-group-contacts scroll mCustomScrollbar _mCS_2 mCS-autoHide mCS_no_scrollbar" style="height: 200px;"><div id="mCSB_2" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" tabindex="0"><div id="mCSB_2_container" class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y" style="position:relative; top:0; left:0;" dir="ltr">
			            <a href="#" class="list-group-item">
			                <div class="list-group-status status-online"></div>
			                <img src="assets/images/users/user2.jpg" class="pull-left" alt="John Doe">
			                <span class="contacts-title">John Doe</span>
			                <p>Praesent placerat tellus id augue condimentum</p>
			            </a>
			        </div><div id="mCSB_2_scrollbar_vertical" class="mCSB_scrollTools mCSB_2_scrollbar mCS-light mCSB_scrollTools_vertical" style="display: none;"><div class="mCSB_draggerContainer"><div id="mCSB_2_dragger_vertical" class="mCSB_dragger" style="position: absolute; min-height: 30px; top: 0px;" oncontextmenu="return false;"><div class="mCSB_dragger_bar" style="line-height: 30px;"></div></div><div class="mCSB_draggerRail"></div></div></div></div></div>     
			        <div class="panel-footer text-center">
			            <a href="pages-messages.html">Show all messages</a>
			        </div>                            
			    </div>                        
			</li>

			<li class="xn-icon-button pull-right" style="background: #1be81b;">
			    <a href="#"><img style="width:30px" src="<?php echo base_url().'assets/back_end/img/icon/radio.png' ?>"></a>                       
			</li>

			<li class="xn-icon-button pull-right" style="background: #e68589;">
			    <a href="#"><img style="width:30px" src="<?php echo base_url().'assets/back_end/img/icon/tv.png' ?>"></a>                       
			</li>

		</div>
	</ul>
<style type="text/css">
	.x-navigation.x-navigation-horizontal{
	    background: #1caf9a;
	}
	.x-navigation.x-navigation-horizontal>li>a:hover{
		background: none;
	}
	.x-navigation.x-navigation-horizontal{
		height: 60px;
	}
	.x-navigation>li.xn-logo>a:first-child{
		font-size: 20px;
		height: 60px;
		background: #95b75d;;
	}
	.x-navigation{
	    background: #d94544;
	}
	.page-container{
	    background: #f5f5f5;
	}
	ul.panel-controls>li.label-control .label, .label-form{
		font-size: 12px;
    	padding: 6px 18px;
	}
	.active{
		background: #e04b4a;
	}
</style>

<script type="text/javascript">
	function page_display(type) {
		$('.label-default').removeClass('active');
		$(this).addClass("active");   
		var mediaType = '<?php echo $mediaType ?>';
		if (type =='Upload') {
			window.location.href ='<?php echo site_url('Client_controller/upload_page_display/') ?>'+ mediaType;
		}
		if (type =='View') {
			window.location.href ='<?php echo site_url('Client_controller/view_client_document_file/') ?>' + mediaType;
		}
		if (type =='Profile') {
			window.location.href ='<?php echo site_url('Client_controller/create_each_channel_list/') ?>' + mediaType;
		}
		if (type =='Swicher') {
			window.location.href ='<?php echo site_url('Client_controller/swicher_page/') ?>' + mediaType;
		}
	}
</script>