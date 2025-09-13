<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Meeting</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Meeting</h3>
   </div>
	<div class="panel-body">
   	<div class="row">
			<div class="col-md-4">
				<a href="#">
			    	<div class="widget widget-warning widget-item-icon">                            
			        <div class="widget-data-left">
			            <div class="widget-title">Create Metting Schedule</div>
			        </div>                                     
			    	</div>
		    	</a>
			</div>
			<div class="col-md-4">
				<a href="#">
			    <div class="widget widget-warning widget-item-icon">                           
			        <div class="widget-data-left">
			            <div class="widget-title">Start Metting</div>
			        </div>                                     
			    </div>
				</a>
			</div>
			<div class="col-md-4">
				<a href="#">
			    <div class="widget widget-warning widget-item-icon">                            
			        <div class="widget-data-left">
			            <div class="widget-title">History</div>
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