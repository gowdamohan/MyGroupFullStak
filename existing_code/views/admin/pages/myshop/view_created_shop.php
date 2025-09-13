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
			<div class="col-md-2">
				<a href="<?php echo site_url('client_controller/myshop_product/'.'shop') ?>">
			    	<div class="widget widget-warning widget-item-icon">                            
			        <div class="widget-data-left">
			            <div class="widget-title">Shop</div>
			        </div>                                     
			    	</div>
		    	</a>
			</div>
			<div class="col-md-2">
				<a href="<?php echo site_url('client_controller/myshop_product/'.'local') ?>">
			    <div class="widget widget-warning widget-item-icon">                           
			        <div class="widget-data-left">
			            <div class="widget-title">Local</div>
			        </div>                                     
			    </div>
				</a>
			</div>

			<div class="col-md-2">
				<a href="<?php echo site_url('client_controller/myshop_product/'.'wholesale') ?>">
			    <div class="widget widget-warning widget-item-icon">                            
			        <div class="widget-data-left">
			            <div class="widget-title">Wholesale</div>
			        </div>                                     
			    </div>
				</a>
			</div>
			<div class="col-md-2">
				<a href="<?php echo site_url('client_controller/myshop_product/'.'brands') ?>">
			    <div class="widget widget-warning widget-item-icon ">                       
			        <div class="widget-data-left">
			            <div class="widget-title">Brands</div>
			        </div>                                     
			    </div>
				</a>
			</div>
			<div class="col-md-2">
				<a href="<?php echo site_url('client_controller/myshop_product/'.'echoshop') ?>">
				    <div class="widget widget-warning widget-item-icon">                          
				        <div class="widget-data-left">
				            <div class="widget-title">Echoshop</div>
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
	    min-height: 0px;
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