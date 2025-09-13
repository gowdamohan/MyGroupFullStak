
<meta property="og:site_name" content="Policetv">
<?php if (!empty($unions_news)) { ?>
<meta property="og:image" itemprop="image" content="<?php echo $this->filemanager->getFilePath($unions_news->union_main_img1); ?>">
<?php } ?>
<meta property="og:image:width" content="300" />
<meta property="og:image:height" content="300" />
<meta property="og:title" content="<?php echo $unions_news->news_letter_name ?>" />
<meta property="og:type" content="news" />
<meta property="og:url" content="http://www.gomygroup.com/">
<link rel="icon" type="image/png"  href="<?php echo base_url().$logo->icon ?>" />


<div class="mt-3 mb-2" style="background: #e2e7ec;padding: 10px;">
 <a class="back_anchor mt-3" href="<?php echo site_url('myunions/myunions_news/'.$groupname.'/'.$union_type); ?>">
    <span class="fa fa-arrow-left"></span>
    Back
  </a> 
</div>
<div class="container">
  <div class="row">
    <div class="col-sm-12">
    	<?php if (!empty($unions_news)) { ?>
    		<div class="align-items-center justify-content-center text-light">
			 		<div id="carouselMissingIndicators" class="carousel slide" data-ride="carousel">
			        <ol class="carousel-indicators">
			        	<?php if ($unions_news->union_main_img1 !='') {?>
			        		<li data-target="#carouselMissingIndicators" data-slide-to="1" class="active"></li>
			        	<?php } ?>
			        	<?php if ($unions_news->union_main_img2 !='') {?>
			        		<li data-target="#carouselMissingIndicators" data-slide-to="2" ></li>
			        	<?php } ?>
			        	<?php if ($unions_news->union_main_img3 !='') {?>
			        		<li data-target="#carouselMissingIndicators" data-slide-to="3" ></li>
			        	<?php } ?>
			        </ol>
			        <div class="carousel-inner">
			        	<?php if ($unions_news->union_main_img1 !='') {?>
			        		<div class="carousel-item active">
	              		<img class="d-block w-100" height="350px" src="<?php echo $this->filemanager->getFilePath($unions_news->union_main_img1) ?>" alt="missing slide">
		          		</div>	
			        	<?php } ?>

		        		<?php if ($unions_news->union_main_img2 !='') {?>
			        		<div class="carousel-item">
		              	<img class="d-block w-100" height="350px" src="<?php echo $this->filemanager->getFilePath($unions_news->union_main_img2) ?>" alt="missing slide">
			          	</div>
			        	<?php } ?>

			        	<?php if ($unions_news->union_main_img3 !='') {?>
			        		<div class="carousel-item">
		              	<img class="d-block w-100" height="350px" src="<?php echo $this->filemanager->getFilePath($unions_news->union_main_img3) ?>" alt="missing slide">
			          	</div>
			        	<?php } ?>

			        </div>
			        <a class="carousel-control-prev" href="#carouselMissingIndicators" role="button" data-slide="prev">
			            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
			            <span class="sr-only">Previous</span>
			        </a>
			        <a class="carousel-control-next" href="#carouselMissingIndicators" role="button" data-slide="next">
			            <span class="carousel-control-next-icon" aria-hidden="true"></span>
			            <span class="sr-only">Next</span>
			        </a>
				    </div>
				</div>
    	<?php } ?>
			
				<?php if (!empty($unions_news->video_path)) { ?>
    		<div class="align-items-center justify-content-center text-light">
		 			<video id="video" style="width: 100%;" controls=""controlsList="nodownload" preload="none" poster="<?php echo $unions_news->video_path ?>">
	 					<source id="mp4" src="<?php echo $this->filemanager->getFilePath($unions_news->video_path)  ?>" type="video/mp4">
					</video>
				</div>
    	<?php } ?>

    </div>

    <div class="col-sm-12">
		<h5><b><?php echo $unions_news->news_letter_name ?></b></h5>
	    <?php echo $unions_news->description ?>
	    <br>
     	<br>
      <br>
	    <div class="col-md-8">
	     	<!-- <small style="float: left;margin-bottom: 1rem; width: 50% ">
		     <p>Report</p> -->
		     <?php 
		     	// if ($noticeNews->sender_show_button == 'name') {

	       //      $profileImage = base_url().'assets/img/logged_user.png';
	       //      echo '<img style="border-radius: 50%; width:30%; height: 52px;" src="'.$profileImage.'">';
	       //      echo "<h3>".$noticeNews->display_name."</h3>";

	       //    }else if($noticeNews->sender_show_button == 'name_photo'){

	       //      if (!empty($noticeNews->img) ) {
	       //       echo '<img style="border-radius: 50%; width:30%; height: 36px;" src="'.$noticeNews->img.'">';
	       //      }else{
	       //        $profileImage = base_url().'assets/img/logged_user.png';
	       //        echo '<img style="border-radius: 50%; width:30%; height: 52px;" src="'.$profileImage.'">';
	       //      }
	       //      echo "<h5>".$noticeNews->display_name."</h5>";

	       //    } 
          ?>

		    </small>
		</div>
		<div class="col-md-4" style="text-align: right;">
			<p><?php echo ($unions_news->post_date == '')? '': $unions_news->post_date ?> </p>
		</div>

    </div>
    <div class="col-sm-12" style="margin-bottom: 6rem;" >
      <center>
	    	<div class="sharethis-inline-share-buttons"></div>
	  	</center>
    </div>
  </div>
</div>

<style type="text/css">
	.back_anchor {
		text-decoration: none !important;
		padding: 16px 16px 16px 0px;
	}

</style>