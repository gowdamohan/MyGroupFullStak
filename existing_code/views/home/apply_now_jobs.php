<style media="screen">
a{
	text-decoration: none !important;
}
	.thumbnailimage{
		width:100%;
		height:15vh;
		background-size:100% 100% !important;
		background-position:center center !important;
		background-repeat:no-repeat !important;
		border-bottom-right-radius: 10px;
		border-bottom-left-radius: 10px;
	}
	.categorypara{
		background:#ab411a;
		color:white;
		font-weight:600;
		letter-spacing: 1px;
		text-align:center;
		font-size:0.7rem;
		padding:5px;
		border-top-right-radius:10px;
		border-top-left-radius:10px;
		margin-bottom: 0;
	}
</style>
<div class="row mx-0 pb-5">
	<img class="d-block w-100" style="margin-bottom:1rem" height="150px" src="<?php echo base_url().'assets/front/img/myjobs/carrer.png' ?>" alt="First slide">
	<?php 
		$myJobs = array(
			'Accounts / Database' => ['name'=>'accounting','img'=>base_url().'assets/front/img/myjobs/accounting.png'],
			'Customer Support' => ['name'=>'customer_support','img'=>base_url().'assets/front/img/myjobs/customer_support.png'],
			'Development / IT' => ['name'=>'development','img'=>base_url().'assets/front/img/myjobs/development.png'],
			'Administration / HR' => ['name'=>'hr','img'=>base_url().'assets/front/img/myjobs/hr.png'],
			'Marketing & sales' => ['name'=>'marketing','img'=>base_url().'assets/front/img/myjobs/marketing.png'],
			'Publicity / Events' => ['name'=>'publicity','img'=>base_url().'assets/front/img/myjobs/publicity.png'],
		);
	?>

	<?php foreach ($myJobs as $jobType => $val) {
		?>
			<div class="col-6 px-2 mb-3">
				<p class="categorypara"><?php echo $jobType ?></p>
				<a href="<?php echo site_url('home/apply_for_myjobs/'.$groupname.'/'.$val['name']) ?>">
					<div class="thumbnailimage" style="background:url('<?php echo $val['img'] ?>')">

					</div>
				</a>
			</div>
		
	<?php } ?>
	

</div>
