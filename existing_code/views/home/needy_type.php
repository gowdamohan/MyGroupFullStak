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
		border-top-right-radius: 10px;
		border-top-left-radius: 10px;
	}
	.categorypara{
		background:#057284;
		color:white;
		font-weight:600;
		letter-spacing: 1px;
		text-align:center;
		font-size:0.7rem;
		padding:5px;
		border-bottom-right-radius:10px;
		border-bottom-left-radius:10px;
	}
</style>
<div class="row mx-0 pb-5">
	<?php if (!empty($needy_type)) { ?>
		<?php foreach ($needy_type as $key => $val) {
			$catString = str_replace('/', '-', $val->category);
			?>
			<div class="col-6 px-2 mb-3">
				<a href="<?php echo site_url('needy/client_needy_list/'.$groupname.'/'.$val->needy_type.'/'.$val->id) ?>">
					<div class="thumbnailimage" style="background:url('<?php echo base_url().$val->category_img  ?>')">

					</div>
					<p class="categorypara"><?php echo $val->category ?></p>
				</a>
			</div>
		<?php } ?>
	<?php } ?>
	

</div>
