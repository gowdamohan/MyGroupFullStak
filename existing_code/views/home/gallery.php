<div class="col-md-12 pt-2 pb-5">
	<h3><strong>Gallery</strong></h3>
	<div class="row">
		<div class="container">
			<div class="gallery">
					<?php 
						$url = 'home/view_gallery/';
						if (!empty($gallery_info)) {
							foreach ($gallery_info as $row){ ?>
									<a class="gallery-item" href="<?php echo site_url($url.$row->gallery_id.'/'.$groupname); ?>" title=""
										data-gallery="">
										<h6 class='visible-lg' style='text-align:center;margin-top: 15px;'><?php echo $row->gallery_name; ?></h6>
										<div class="image image_style">
											<span style='position:absolute; z-index:30;font-size: 2em; top:3px;left:8px;text-shadow: 0px 0px 10px white;color:#fdb107;'><?php echo ($row->is_new==1)?'<i class="fa fa-star" aria-hidden="true"></i>':''; ?></span>
											<img style='object-fit: cover; width: 100%;' src="<?php echo $this->filemanager->getFilePath($row->image_name) ?>">
										</div>
									</a>
							<?php }
						}else{ ?>
						 	<div class="text-center">
	              <img class="img-fluid" width="40%" src="<?php echo base_url().'assets/img/2.png' ?>">
	           	</div>
					<?php	}
						
					?>
			</div>
		</div>
	</div>
</div>


<style type="text/css">


	.triangle{
		width: 60px;
		height: 60px;
		border-top: solid 30px rgb(104, 147, 202);
		border-right: solid 30px rgb(104, 147, 202);
		border-left: solid 30px transparent;
		border-bottom: solid 30px transparent;
		position: absolute;
		

	}
  @media only screen and (max-width: 768px) {
    .smsHead{
      padding:0px !important;
    }
  }
  .smsLink:hover .smsRow{
    opacity: 0.9;
  }
  html{
	background:white;
  }

  .image_style{
	box-shadow: 0px 8px 8px -2px #ccc;
	border:solid 2px #6893CA;
	border-radius: 6px;
	height: 150px;
  }

  .ul_style{
	position: absolute !important; 
	right: -10px !important; 
	top: 0px !important; 
	display: block !important; 
	list-style: none !important; 
	padding: 0 !important;
	z-index: 2 !important;
  }

  .h3_style{
	position:relative; 
	z-index:20; 
	top:-54px;
	left:-36px;
	color: white;
  }

  /* .gallery .gallery-item {
    height: 320px !important;
} */
.gallery .gallery-item .image img {
    height: -webkit-fill-available !important;
}
</style>