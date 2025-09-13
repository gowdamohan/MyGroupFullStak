<?php
$active = "0";
if ($active == 1) {
  $btn1 = "btn";
}else{
  $btn1 = "btn-outline";
}

$active2 = "1";
if ($active2 == 1) {
  $btn2 = "btn";
}else{
  $btn2 = "btn-outline";
}

$active3 = "0";
if ($active3 == 1) {
  $btn3 = "btn";
}else{
  $btn3 = "btn-outline";
}


 ?>
<style media="screen">
  .btn{
    border-radius: 0;
  }
  .maincat{
    background:#057284;
    margin:0;
    padding:0;
  }
  .maincat p{
    font-size:0.9rem;
    text-transform:uppercase;
    color:white;
    padding:10px 15px;
    line-height: 1;
  }
  .subcat p{
    font-size:0.9rem;
    text-transform:uppercase;
    padding:10px 15px;
    line-height: 1;
    border-bottom:1px solid #057284;
  }
  .cat{
    font-size:0.9rem;
    text-transform:uppercase;
    padding:10px 15px;
    line-height: 1;
    border-bottom:1px solid #057284;
  }
  .order-details h5{
    font-size:1.1rem;
    font-weight:600;
    line-height:1;
    margin-bottom:5px;
  }
  .order-details p{
    font-size:0.7rem;
  }
  a{
    color:black !important;
    text-decoration:none !important;
  }
  .profilephoto{
		width:8rem;
		height:8rem;
		border-radius:50%;
		background:url('');
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
  .ratingwala{
    display:flex;
    align-items:center;
    justify-content:space-between;
    width:100%;
    margin-bottom:0.5rem;
    margin-top:0.5rem;
    padding:0.5rem 2rem;
  }
  .ratingwala i{
    font-size:2.4rem;
  }
</style>

<section class="pb-5">
  <div class="col-12">
    <h4 class="mb-1">Order Details</h4>
  </div>
  <div class="col-12 mx-auto p-2 my-1">
    <div class="row mx-0 p-2" style="border:1px solid #057284;overflow:hidden;border-radius:10px;">
      <div class="col-4 px-1">
        <p class="mb-0">Order Date</p>
        <p class="mb-0">Order Id</p>
        <p class="mb-0">Order Total</p>
      </div>
      <div class="col-8 px-1">
        <p class="mb-0">28-05-2022</p>
        <p class="mb-0">#123</p>
        <p class="mb-0">99.00</p>
      </div>
    </div>
  </div>

  <div class="col-12">
    <h4 class="mb-1">Order Status</h4>
  </div>
  <div class="col-12 my-1">
    <div class="progress">
      <div class="progress-bar bg-info" role="progressbar" style="width: 33%" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100">Booked</div>
      <div class="progress-bar bg-warning" role="progressbar" style="width: 33%" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100">Accepted</div>
      <div class="progress-bar bg-success" role="progressbar" style="width: 34%" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100">Completed</div>
    </div>

    <br>
    <div class="progress">
      <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">Booked</div>
      <div class="progress-bar bg-danger" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">Cancelled</div>
    </div>

  </div>


  <div class="informationcenter p-0 col-11 mx-auto bg-white mt-3" style="overflow:hidden">

    <div class="col-12" style="background: linear-gradient(to right, #0575e6, #021b79);">
      <h4 class="mb-1 py-1 text-center text-white">Rate Now</h4>
    </div>
    <div class="p-2">
      <p class="ratingwala" style="">
        <i style="color:#0575e6;" class="fas fa-star"></i>
        <i style="color:#0575e6;" class="far fa-star"></i>
        <i style="color:#0575e6;" class="far fa-star"></i>
        <i style="color:#0575e6;" class="far fa-star"></i>
        <i style="color:#0575e6;" class="far fa-star"></i>
      </p>
      <div class="infoprofile">
        <textarea name="name" class="form-control" rows="3" placeholder="Your feedback is important to us"></textarea>
        <input type="submit" class="btn btn-sm mt-2 form-control" style="background: linear-gradient(to right, #000000, #0f9b0f);color:white;border-radius:0.25rem;" name="" value="Submit">
      </div>
    </div>

  </div>

  <div class="col-12">
    <h4 class="mb-1">Service Details</h4>
  </div>


  <div class="col-12">
    <div class="d-flex align-items-center justify-content-center py-3" style="background: linear-gradient(to right, #136a8a, #267871);flex-direction:column;">
    	<div class="profilephoto" style="">

    	</div>
    	<h4 class="text-white mt-2">4.5 <i class="fas fa-star" aria-hidden="true"></i></h4>
    </div>
  </div>
  <div class="col-12">
		<div class="mb-3">
			<h5 class="text-center partnername">Name in english</h5>
			<h5 class="text-center partnername" style="font-size:1.2rem;">name in local language</h5>
		</div>

    <center>
      <a href="#" class="btn btn-primary btn-sm" style="color:white !important;" href="#">View Profile</a>
    </center>







	</div>

</section>
