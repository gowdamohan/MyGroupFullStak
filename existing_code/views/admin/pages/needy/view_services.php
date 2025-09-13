<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Needy View Services</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Needy View Services</h3>
   </div>
   	<div class="panel-body">
	   	
			<div class="row">
				<div class="panel-body list-group list-group-contacts">
					<?php foreach ($services_list as $header => $value) {
						echo "<h2>".$header."</h2><hr style='margin-top: 0;'>";
						foreach ($value as $key => $val) { ?>
							<div class="col-md-4">                           
			            <a href="#" class="list-group-item">                                 
	                		<span class="contacts-title"><?php echo $val->category ?> 
	                		<div class="list-group-controls">
                         	<span onclick="edit_client_services_list('<?php echo $val->id ?>')" class="fa fa-pencil">Edit</span>
                      	</div>
	                	</span>
			                <p style="font-size:14px;text-align: center; margin-top: 1rem;  ">
									<span style="margin-right: 10px; float: left;" href=""> 0.0 <span class="fa fa-star"></span> </span>
									<span style="margin-right: 10px; text-align: center;" href="">Comments </span>
									<span style="float: right;" >
										<?php if($val->status == 1){ ?>
	                            <label class="switch">
	                                <input type="checkbox" onclick="needy_services_switch_check('<?php echo $val->id ?>','0')" checked >
	                                <span></span>
	                                </label>
	                            <?php }else{ ?>
	                                <label class="switch">
	                                    <input type="checkbox" onclick="needy_services_switch_check('<?php echo $val->id ?>','1')" >
	                                    <span></span>
	                                </label>
	                          <?php } ?></span>
								</p>                                                                           
			            </a>
		           	</div>
						<?php }
					} ?>
	        </div>

			</div>

   </div>
</div>

<script type="text/javascript">
 	function needy_services_switch_check(stngId, value) {
        $.ajax({
            url: '<?php echo site_url('client_controller/needy_services_status_udpate'); ?>',
            type: "post",
            data:{'stngId':stngId, 'value':value},
            success: function (data) {
                if (data.length=='1') {
                  location.reload();
                }else{
                  location.reload();
                }
            },
          error: function (err) {
              console.log(err);
        }
    });
  }

  function edit_client_services_list(id) {
  		window.location.href='<?php echo site_url('client_controller/edit_needy_client_services/') ?>'+id
  }
</script>
<style type="text/css">
	.list-group .list-group-item .fa{
		margin-right: 0px;
	}
	.checked {
	  color: orange;
	}
	.widget.widget-item-icon .widget-item-left, .widget.widget-item-icon .widget-item-right{
		padding: 0px 0px;
	}
	.widget{
	    min-height: 60px;
	}
	.widget .widget-item-left .fa, .widget .widget-item-right .fa, .widget .widget-item-left .glyphicon, .widget .widget-item-right .glyphicon{
		font-size: 34px;
	}
	.widget .widget-title{
	    font-size: 15px;
	}
	a[disabled] {
	    pointer-events: none;
	}
	.disabled{
		opacity: 0.6;
	}
</style>