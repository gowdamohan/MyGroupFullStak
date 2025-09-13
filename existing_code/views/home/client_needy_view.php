<style media="screen">
	.profilephoto{
		width:8rem;
		height:8rem;
		border-radius:50%;
		background:url('<?php echo $this->filemanager->getFilePath($client_needy_view->photo) ?>');
		background-size:cover;
		background-repeat:no-repeat;
		background-position:center center;
		border:3px solid #ffffff;
		box-shadow:0 0 10px white;
	}
	.partnername{
		font-size:1.5rem;
		font-weight:bold;
		text-transform:uppercase;
		letter-spacing: 1px;
		margin:0.2rem 0;
		color:#057284;
	}
	.partnername span{
		font-size:0.9rem;
		vertical-align: middle;
	}
	.informationcenter{
		border-radius: 10px;
		box-shadow:0 0 5px grey;
		margin-bottom:1rem;
	}
	.informationcenter .labelprofile{
		text-align:center;
		margin:0;
		padding: 5px 0;
		font-weight:bold;
	}
	.informationcenter .infoprofile{
		text-align:center;
		margin:0;
		padding: 10px 0;
		background:#ebebeb;
		line-height:1.2;
		border-radius:10px;
		padding:0.8rem;
		font-weight:500;
	}
	.informationcenter .infotime{
		margin:0;
		padding: 10px 0;
		background:#ebebeb;
		line-height:1.2;
		border-radius:10px;
		padding:0.8rem;
		font-weight:500;
	}
	.informationcenter .infotime p{
		line-height:2;
	}
	.socialicons a i{
		color:white;
		font-size:1.2rem;
	}
	.socialicons a{
		height:2rem;
		width:2rem;
		display:flex;
		align-items:center;
		justify-content:center;
		border-radius:50%;
	}
	.shareicon{
		position: fixed;
		bottom:10rem;
		right:-24px;
		background:black;
		background: linear-gradient(to right, #2c3e50, #4ca1af);
		color:white;
		padding:3px 10px;
		font-size:1rem;
		transform: rotateZ(270deg)
	}
</style>
<div class="d-flex align-items-center justify-content-center py-3" style="background: linear-gradient(to right, #136a8a, #267871);flex-direction:column;">
	<div class="profilephoto" style="">

	</div>
	<h4 class="text-white mt-2">4.5 <i class="fas fa-star" aria-hidden="true"></i></h4>
</div>
<section class="pt-4 pb-5" style="background:#ebebeb;">
	<div class="col-11 mx-auto">
		<div class="mb-3">
			<h5 class="text-center partnername"><?php echo $client_needy_view->services_name ?></h5>
			<h5 class="text-center partnername" style="font-size:1.2rem;"><?php echo $client_needy_view->name_regional_language ?></h5>
		</div>

		<div class="informationcenter col-12 mx-auto bg-white p-2">
			<p class="labelprofile">Description</p>
			<div class="infoprofile"> <p class="" style="text-align:justify;"><?php echo $client_needy_view->descriptions ?></p> </div>
			<div class="infoprofile mt-2">
				<p style="text-align:left;margin:5px 0;"><b>Area:</b> <?php echo $client_needy_view->area ?></p>
				<p style="text-align:left;margin:5px 0;"><b>Pincode:</b> <?php echo $client_needy_view->pincode ?></p>
				<p style="text-align:left;margin:5px 0;"><b>Fees:</b> <?php echo $client_needy_view->consultancy_charges_from . 'to' .$client_needy_view->consultancy_charges_to  ?></p>
			</div>
		</div>

		<div class="informationcenter col-12 mx-auto bg-white p-2">
			<p class="labelprofile">Timings</p>
			<div class="infotime">
				<?php foreach ($client_needy_view->slave as $key => $val) {
					if ($val->from_time !='') {
						echo '<p><b>'.$val->week_name.' :</b>'.$val->from_time.' to '.$val->to_time.'</p>';
					}
				} ?>
			</div>
		</div>

		<div class="informationcenter socialicons col-12 mx-auto bg-white p-2 d-flex align-items-center" style="justify-content:space-around;">
			<a href="#" style="background: linear-gradient(to right, #1e3c72, #2a5298);">
				<i class="fas fa-globe" aria-hidden="true"></i>
			</a>
			<a href="#" style="background: linear-gradient(to right, #1e3c72, #2a5298);">
				<i class="fab fa-facebook" aria-hidden="true"></i>
			</a>
			<a href="#" style="background: linear-gradient(to right, #e52d27, #b31217);">
				<i class="fab fa-youtube" aria-hidden="true"></i>
			</a>
			<a href="#" style="background: linear-gradient(to right, #833ab4, #fd1d1d, #fcb045);">
				<i class="fab fa-instagram" aria-hidden="true"></i>
			</a>
			<a href="#" style="background: linear-gradient(to right, #00b4db, #0083b0);">
				<i class="fab fa-twitter" aria-hidden="true"></i>
			</a>
			<a href="#" style="background: linear-gradient(to right, #0575e6, #021b79);">
				<i class="fab fa-linkedin" aria-hidden="true"></i>
			</a>
		</div>

		<!-- floating icon -->
		<a href="#" class="shareicon">
			<i class="fas fa-share-alt" aria-hidden="true"></i> Share
		</a>
		<!-- floating icon end -->

		<div class="informationcenter col-12 mx-auto bg-white p-2">
			<p class="labelprofile">Reviews</p>
			<?php foreach ($client_needy_view->review as $key => $val) { ?>
				<div class="infoprofile mb-2">
					<p style="text-align:left;font-weight:bold;margin-bottom:5px;"><?php echo $val->first_name ?></p>
					<p style="text-align:left;font-size:0.7rem;margin:5px 0;color:#057284;">
						<i class="fas fa-star" aria-hidden="true"></i>
						<i class="fas fa-star" aria-hidden="true"></i>
						<i class="fas fa-star" aria-hidden="true"></i>
						<i class="far fa-star" aria-hidden="true"></i>
						<i class="far fa-star" aria-hidden="true"></i>
					</p>
					<p class="" style="text-align:justify;"><?php echo $val->reviews ?></p>
				</div>
			<?php } ?>

			<?php
			for ($i=1; $i < 5; $i++) {
				?>
				
				<?php
			}
			 ?>

		</div>


	</div>
</section>
