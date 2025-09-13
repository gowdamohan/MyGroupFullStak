<style media="screen">
  .card{
    border-radius: 0 !important;
  }
  .maincatheading{
    font-size:1rem;
    font-weight:600;
    letter-spacing: 1px;
  }
  .maincatpara{
    font-size:0.7rem;
    color:#8a8a8a;
  }
  .subcatheading{
    font-size:0.9rem;
    font-weight:500;
  }
  .singleprod{
    font-size:0.8rem;
    color:black;
    text-decoration:none !important;
  }
  .singleprod:hover{

  }
</style>
<section class="pb-5">
  <div class="col-12">
    <div id="accordion">
      <div class="card">
      	<?php foreach ($shop_details as $key => $cat) { ?>
      		<div class="card-header p-0" id="headingOne">
      			<?php
      			$onclickRedirct = 'onclick="redirect_my_shop_product('.$cat->id.')"';
      			if (!empty($cat->subcat)) {
    						$onclickRedirct = '';
      			} ?>
	          <div class="bg-white p-2" <?php echo $onclickRedirct ?> data-toggle="collapse" data-target="#collapse<?php echo $cat->id ?>" aria-expanded="true" aria-controls="collapse<?php echo $cat->id ?>">
	            <div class="row mx-0">
	              <div class="col-2 px-1">
	              	<?php 
	              	$catImg = base_url().'assets/front/logo.png';
	              	if (!empty($cat->category_img)) {
	              		$catImg = $this->filemanager->getFilePath($val->image);
	              	} ?>
	                <img src="<?php echo $catImg ?>" style="width:100%;" alt="">
	              </div>
	              <div class="col-8 px-1 d-flex align-items-center justify-content-start">
	                <div class="" style="width:100%;">
	                  <h6 class="mb-1 maincatheading"><?php echo $cat->category ?></h6>
	                  <?php 
	                  $subCatString = '';
	                  foreach ($cat->subcat as $key => $subCat) {
                  		if (!empty($subCatString)) 
                  			$subCatString .= ',';
                  			$subCatString .= $subCat->sub_category;
	                  } ?>
	                  <p class="mb-0 maincatpara"><?php echo $subCatString ?></p>
	                </div>
	              </div>
	             <?php
	             	$arrow = '<i class="fas fa-angle-right float-right"></i>';
	             	if (!empty($cat->subcat)) {
	              	$arrow = '<i class="fas fa-angle-down"></i>';
	             	} ?>
	              <div class="col-2 d-flex align-items-center justify-content-center">
	               	<?php echo $arrow; ?>
	              </div>
	            </div>
	          </div>

	        </div>
      	<?php } ?>
        
      	<?php foreach ($shop_details as $key => $cat) { ?>
      		<div id="collapse<?php echo $cat->id ?>" class="collapse" aria-labelledby="heading<?php echo $cat->id ?>" data-parent="#accordion">
	          <div class="card-body p-0">
	            <div id="accordionsub">
	              <div class="card">
	              	<?php foreach ($cat->subcat as $key => $subcat) { ?>
	              		
	              		<?php if (!empty($subcat->subSubcat)) { ?>
	              			<div class="card-header p-0" id="subcat<?php echo $subcat->id ?>">
			                  <div class="p-3" style="background:#f7f7f7;" data-toggle="collapse" data-target="#collapse_subcat<?php echo $subcat->id ?>" aria-expanded="true" aria-controls="collapse_subcat<?php echo $subcat->id ?>">

			                    <div class="row mx-0">
			                      <div class="col-2 px-1">
			                        <img src="../../../assets/radio/1.jpg" style="width:100%;border-radius:50%;" alt="">
			                      </div>
			                      <div class="col-8 px-1 d-flex align-items-center justify-content-start">
			                        <div class="" style="width:100%;">
			                          <h6 class="mb-1 subcatheading"><?php echo $subcat->sub_category ?></h6>
			                        </div>
			                      </div>
			                      <div class="col-2 d-flex align-items-center justify-content-center">
			                       	<i class="fas fa-angle-down"></i>
			                      </div>
			                    </div>
			                  </div>
			                </div>
			                <?php
			                	foreach ($subcat->subSubcat as $key => $subSubCat) { ?>
			                	 	<div id="collapse_subcat<?php echo $subSubCat->sub_category_id ?>" class="collapse px-3" aria-labelledby="subcat" data-parent="#accordionsub">
					                  <div class="card-body py-2 px-4">
					                    <a href="#" class="singleprod d-block d-flex align-items-center justify-content-between my-2"><?php echo $subSubCat->sub_sub_category ?> <i class="fas fa-angle-right float-right"></i></a>
					                  </div>
					                </div>
				                <?php } ?>

	              		<?php }else{ ?>
											<div class="card-header p-0" id="subcat<?php echo $subcat->id ?>">
	              				<a class="p-3" style="background:#f7f7f7;" href="<?php echo site_url('myshop/myshop_category_sub_products/'.$groupname.'/'.$shop_type.'/'.$subSubCat->sub_category_id.'/'.$subcat->id) ?>">
	          					  	<div class="row mx-0">
			                      <div class="col-2 px-1">
			                        <img src="../../../assets/radio/1.jpg" style="width:100%;border-radius:50%;" alt="">
			                      </div>
			                      <div class="col-8 px-1 d-flex align-items-center justify-content-start">
			                        <div class="" style="width:100%;">
			                          <h6 class="mb-1 subcatheading"><?php echo $subcat->sub_category ?></h6>
			                        </div>
			                      </div>
			                      <div class="col-2 d-flex align-items-center justify-content-center">
			                       	<i class="fas fa-angle-right float-right"></i>
			                      </div>
			                    </div>
	              				</a>
			                </div>
	              		<?php } ?>
	             	<?php } ?>
	              </div>
	            </div>
	          </div>
	        </div>
      	<?php } ?>
        
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
	function redirect_my_shop_product(cat_id) {
		var groupname = '<?php echo $groupname ?>';
		var shop_type = '<?php echo $shop_type ?>';
		window.location.href='<?php echo site_url('myshop/myshop_category_products/') ?>'+groupname+'/'+shop_type+'/'+cat_id;
	}
</script>