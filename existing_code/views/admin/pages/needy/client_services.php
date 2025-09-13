<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Needy Services</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Needy Services</h3>
   </div>
   	<div class="panel-body">
	   	<div class="row">
	   		<?php 
	   			$services = ['Doorstep','Centers','Manpower','Online','Myhelp'];

	   			foreach ($services as $key => $val) { ?>
	   				<div class="col-md-2">
							<a href="<?php echo site_url('client_controller/creted_needy_services/'.$val.'/'.$group_id) ?>">
						    	<div class="widget widget-warning widget-item-icon">
						        <div class="widget-item-right">
						            <span class="fa fa-television"></span>
						        </div>                             
						        <div class="widget-data-left">
						            <div class="widget-title"><?php echo $val ?></div>
						            <?php 
						            $countList = 0;
						            if (array_key_exists($val, $services_count)) {
						            	$countList = $services_count[$val];
						            } ?>
						            <div class="widget-subtitle"><?php echo 'Count - '.$countList ?></div>
						        </div>                                     
						    	</div>
					    	</a>
						</div>
	   			<?php }
	   		?>

		</div>
   </div>
</div>

<style type="text/css">
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