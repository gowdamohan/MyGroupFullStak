  <!DOCTYPE html>
<html lang="en">
<head>
  	<title>MY Group</title>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/back_end/css/fontawesome/font-awesome.min.css">

	<title>Myproup of Company</title>


<meta property="og:site_name" content="Myproup">
<meta property="og:image" itemprop="image" content="<?php echo base_url().'assets/front/img/play_store_icon.png' ?>">
<meta property="og:image:width" content="300" />
<meta property="og:image:height" content="300" />
<meta property="og:title" content="My group of apps" />
<meta property="og:type" content="Application" />
<meta property="og:url" content="http://www.gomygroup.com/">
<link rel="icon" type="image/png"  href="<?php echo base_url().'assets/front/img/play_store_icon.png' ?>" />

</head>
<body>
<center>
	<h1 style="margin-bottom:2rem; font-size:28px;" >Download the Apps</h1>
</center>

<div class="accordion">
		
	<!-- use labels for the title as they can be used to toggle radios -->
	<label for="accordion1">  <img class="social-icon-width"  style="border-radius: 20px;width: 32px; height: 32px;" src="<?php echo base_url().'assets/front/img/My app.png' ?>"> &nbsp;Mygroup</label>
	<input type="radio" checked name="accordion1" id="accordion1" hidden>
	<div class="accordion-item" style="margin-bottom:2rem">

      <div class="news-app-promo__section" style="width:50%; float: left;">
        <div class="news-app-promo-subsection">
          <a class="news-app-promo-subsection--link news-app-promo-subsection--playstore" href="https://play.google.com/store/apps/details?id=com.mygroup.apps" target="_parent">
            <img class="news-app-promo__play-store " src="<?php echo base_url().'assets/front/img/play_store.png' ?>" width="130" height="auto" border="0">
          </a>
        </div>
      </div>
      	
      <div class="news-app-promo__section">
        <div class="news-app-promo-subsection">
          <a class="news-app-promo-subsection--link news-app-promo-subsection--playstore" href="https://apps.apple.com/us/developer/apple/id284417353?mt=12" target="_parent">
            <img class="news-app-promo__play-store " src="<?php echo base_url().'assets/front/img/app_store.png' ?>" width="130" height="auto" border="0">
          </a>
        </div>
      </div>

	</div>
	
	<label for="accordion2"><img class="social-icon-width" style="border-radius: 20px;width: 32px; height: 32px;" src="<?php echo base_url().'assets/front/img/my partner.png' ?>"> &nbsp;Mypartner</label>
	<input type="radio" checked name="accordion2" id="accordion2" hidden>
	<div class="accordion-item">
	  <div class="news-app-promo__section" style="width:50%; float: left;">
        <div class="news-app-promo-subsection">
          <a class="news-app-promo-subsection--link news-app-promo-subsection--playstore" href="https://play.google.com/store/apps/details?id=com.mygroup.partner" target="_parent">
            <img class="news-app-promo__play-store " src="<?php echo base_url().'assets/front/img/play_store.png' ?>" width="130" height="auto" border="0">
          </a>
        </div>
      </div>
      	
      <div class="news-app-promo__section">
        <div class="news-app-promo-subsection">
          <a class="news-app-promo-subsection--link news-app-promo-subsection--playstore" href="https://apps.apple.com/us/developer/apple/id284417353?mt=12" target="_parent">
            <img class="news-app-promo__play-store " src="<?php echo base_url().'assets/front/img/app_store.png' ?>" width="130" height="auto" border="0">
          </a>
        </div>
      </div>
	</div>
		
</div>
</body>

<style type="text/css">
	
.accordion {
	background-color: #0a3961;
	color: black;
	border-radius:.5rem;
	overflow:hidden;
	width:100%;
	display:flex;
	flex-direction:column;
	}
	label {
		padding:.75rem 1rem;
		border-bottom:1px solid whitesmoke;
		cursor: pointer;
		font-weight: bold;
		color: #000;
	    background: #fff;
	    border: 2px solid #fff;
	    border-radius: 26px;
	    text-align: center;
	    font-size: 24px;
		}
	label:hover{
		background-color: whitesmoke;
	}
	label:focus {
		background-color: whitesmoke;
	}
	
	.accordion-item {
		max-height:0px;
		overflow:hidden;
		padding:0 1rem;
	}
	
	input:checked + .accordion-item {
		max-height:100vh;
		padding:1.5rem 1rem;
	}

}



// additional styling
// ------------------
@import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap');


* {
	box-sizing: border-box;
}

body {
	--primary: rgb(80,120,200);
	--secondary: rgb(30,60,100);
	height:100vh;
	padding:2rem;
	font-family: 'Quicksand', sans-serif;
	background-color: #0a3961;
	gap:2rem;
	color:white;
	
}

h1 {
	font-weight:800;
	font-size: 150%;
}

h2 {
	font-weight:800;
	// margin-bottom:.5rem;
}


</style>