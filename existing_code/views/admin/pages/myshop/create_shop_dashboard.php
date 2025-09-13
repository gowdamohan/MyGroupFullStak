<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>My Shop</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">My Shop</h3>
   </div>
   	<div class="panel-body">
	   	<div class="row">
			<?php 
			   $group_list = ['local','wholesale','brands'];
				$localCount = 0;
				$wholeSaleCount = 0;
				$brandsCount = 0;
				$defaults = 0;
				foreach ($client_shop_create as $shopType => $val) {
						switch ($shopType) {
		            case 'local':
		              	$localCount = count($val);
	                	break;
		            case 'wholesale':
	                	$wholeSaleCount = count($val);
		                break;
		            case 'brands':
	                	$brandsCount = count($val);
		                break;
		            default:
	                	$defaults = '';
		                break;
						}
				} 
			?>
			
			<div class="col-md-2">
				<a <?php if ($localCount == 1) echo 'disabled' ?> href="<?php echo site_url('client_controller/create_shop_details/'.'local') ?>">
			    <div class="widget widget-warning widget-item-icon">                           
			        <div class="widget-data-left">
			            <div class="widget-title">Local</div>
			        </div>                                     
			    </div>
				</a>
			</div>

			<div class="col-md-2">
				<a <?php if ($wholeSaleCount == 1) echo 'disabled' ?> href="<?php echo site_url('client_controller/create_shop_details/'.'wholesale') ?>">
			    <div class="widget widget-warning widget-item-icon">                            
			        <div class="widget-data-left">
			            <div class="widget-title">Wholesale</div>
			        </div>                                     
			    </div>
				</a>
			</div>
			<div class="col-md-2">
				<a <?php if ($brandsCount == 1) echo 'disabled' ?> href="<?php echo site_url('client_controller/create_shop_details/'.'brands') ?>">
			    <div class="widget widget-warning widget-item-icon ">                       
			        <div class="widget-data-left">
			            <div class="widget-title">Brands</div>
			        </div>                                     
			    </div>
				</a>
			</div>
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