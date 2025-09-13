<?php 
if ($this->mobile_detect->isMobile()) { 
	$this->load->view('front/header_mobile.php');
} else { 
	$this->load->view('front/header.php');
}
?>
<?php $this->load->view($main_content); ?>
<?php $this->load->view('admin/inc/notification'); ?>
<?php $this->load->view('front/footer.php'); ?>