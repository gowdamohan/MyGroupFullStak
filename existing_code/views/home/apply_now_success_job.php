<?php 
    if ($this->mobile_detect->isMobile()) { ?>
        <section id="contact" class="parallax-section" style="margin-top:30%">
    <?php }else{ ?>
        <section id="contact" class="parallax-section mt-5">
    <?php }
?>

    <div class="container">
        
         <div class="modal-content">
			<div class="modal-header">
				<div class="icon-box">
					<i style="font-size: 48px; color: #20ba6c;margin-right: 18px;" class="fa fa-check-circle" aria-hidden="true"></i>
				</div>				
				<h4 class="modal-title w-100"> Successfully Submitted!</h4>	
			</div>
			<div class="modal-body">
				<h2 class="text-center">We recevied your details. Our team will contact you soon.</h2>
			</div>
			<div class="modal-footer">
				<a class="btn btn-success btn-block" href="<?php echo site_url('home/apply_my_jobs/'.$groupname) ?>">Back to Home page</a>
			</div>
		</div>

    </div>
</section>

<style type="text/css">
.modal-confirm .icon-box {
    color: #fff;
    position: absolute;
    margin: 0 auto;
    left: 0;
    right: 0;
    top: -70px;
    width: 95px;
    height: 95px;
    border-radius: 50%;
    z-index: 9;
    background: #82ce34;
    padding: 15px;
    text-align: center;
    box-shadow: 0px 2px 2px rgb(0 0 0 / 10%);
}

.modal-confirm .icon-box i {
    font-size: 58px;
    position: relative;
    top: 3px;
}
.material-icons {
    font-family: 'Material Icons';
    font-weight: normal;
    font-style: normal;
    font-size: 24px;
    line-height: 1;
    letter-spacing: normal;
    text-transform: none;
    display: inline-block;
    white-space: nowrap;
    word-wrap: normal;
    direction: ltr;
    -webkit-font-feature-settings: 'liga';
    -webkit-font-smoothing: antialiased;
}
</style>