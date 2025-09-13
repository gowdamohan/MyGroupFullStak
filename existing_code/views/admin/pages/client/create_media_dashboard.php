<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Media</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Media</h3>
   </div>
   	<div class="panel-body">
	   	<div class="row">
			<?php 
				$tvCount = 0;
				$radioCount = 0;
				$newsCount = 0;
				$magazineCount = 0;
				$webnewsCount = 0;
				$youtubeCount = 0;
				$mygodCount = 0;
				$defaults = 0;
				foreach ($lock_media as $mediaType => $val) {
						switch ($mediaType) {
						 case 'tv':
	              		$tvCount = count($val);
		                break;
		            case 'radio':
		              	$radioCount = count($val);
	                	break;
		            case 'news':
	                	$newsCount = count($val);
		                break;
		            case 'magazine':
	                	$magazineCount = count($val);
		                break;
		            case 'webnews':
		               $webnewsCount = count($val);
		                break;
		            case 'youtube':
		               $youtubeCount = count($val);
		                break;
		            // case 'mygod':
		            //    $mygodCount = count($val);
		            //     break;
		            default:
	                	$defaults = '';
		                break;
						}
				} 
			?>
			<div class="col-md-2">
				<a <?php if($tvCount >= 1) echo 'disabled' ?> href="<?php echo site_url('client_controller/create_media/'.'tv') ?>">
			    	<div class="widget widget-warning widget-item-icon <?php if($tvCount >= 1) echo 'disabled' ?>">
			        <div class="widget-item-right">
			            <span class="fa fa-television"></span>
			        </div>                             
			        <div class="widget-data-left">
			            <div class="widget-title">TV</div>
			            <div class="widget-subtitle">one tv</div>
			        </div>                                     
			    	</div>
		    	</a>
			</div>
			<div class="col-md-2">
				<a <?php if($radioCount >= 1) echo 'disabled' ?> href="<?php echo site_url('client_controller/create_media/'.'radio') ?>">
			    <div class="widget widget-warning widget-item-icon <?php if($radioCount >= 1) echo 'disabled' ?>">
			        <div class="widget-item-right">
			            <span class="fa fa-radio"></span>
			        </div>                             
			        <div class="widget-data-left">
			            <div class="widget-title">Radio</div>
			            <div class="widget-subtitle">one Radio</div>
			        </div>                                     
			    </div>
				</a>
			</div>

			<div class="col-md-2">
				<a <?php if($newsCount > 2) echo 'disabled' ?> href="<?php echo site_url('client_controller/create_media/'.'news') ?>">
			    <div class="widget widget-warning widget-item-icon <?php if($newsCount > 2) echo 'disabled' ?>">
			        <div class="widget-item-right">
			            <span class="fa fa-envelope"></span>
			        </div>                             
			        <div class="widget-data-left">
			            <div class="widget-title">e-Paper</div>
			            <div class="widget-subtitle">3 e-Paper</div>
			        </div>                                     
			    </div>
				</a>
			</div>
			<div class="col-md-2">
				<a <?php if($magazineCount >= 1) echo 'disabled' ?> href="<?php echo site_url('client_controller/create_media/'.'magazine') ?>">
			    <div class="widget widget-warning widget-item-icon <?php if($magazineCount >= 1) echo 'disabled' ?>">
			        <div class="widget-item-right">
			            <span class="fa fa-envelope"></span>
			        </div>                             
			        <div class="widget-data-left">
			            <div class="widget-title">e-Magazine</div>
			            <div class="widget-subtitle">3 e-Magazine</div>
			        </div>                                     
			    </div>
				</a>
			</div>
			<div class="col-md-2">
				<a <?php if($webnewsCount >= 1) echo 'disabled' ?> href="<?php echo site_url('client_controller/create_media/'.'webnews') ?>">
				    <div class="widget widget-warning widget-item-icon <?php if($webnewsCount >= 1) echo 'disabled' ?> ">
				        <div class="widget-item-right">
				            <span class="fa fa-envelope"></span>
				        </div>                             
				        <div class="widget-data-left">
				            <div class="widget-title">Web</div>
				            <div class="widget-subtitle">Web</div>
				        </div>                                     
				    </div>
				</a>
			</div>
			<div class="col-md-2">
				<a <?php if($youtubeCount >= 1) echo 'disabled' ?> href="<?php echo site_url('client_controller/create_media/'.'youtube') ?>">
			    <div class="widget widget-warning widget-item-icon <?php if($youtubeCount >= 1) echo 'disabled' ?>">
			        <div class="widget-item-right">
			            <span class="fa fa-envelope"></span>
			        </div>                             
			        <div class="widget-data-left">
			            <div class="widget-title">Youtube</div>
			            <div class="widget-subtitle">Youtube</div>
			        </div>                                     
			    </div>
				</a>
			</div>
			<!-- <div class="col-md-2">
				<a <?php if($mygodCount >= 1) echo 'disabled' ?> href="<?php echo site_url('client_controller/create_media/'.'mygod') ?>">
			    <div class="widget widget-warning widget-item-icon <?php if($mygodCount >= 1) echo 'disabled' ?>">
			        <div class="widget-item-right">
			            <span class="fa fa-envelope"></span>
			        </div>                             
			        <div class="widget-data-left">
			            <div class="widget-title">My-God</div>
			            <div class="widget-subtitle">My-God</div>
			        </div>                                     
			    </div>
				</a>
			</div> -->

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