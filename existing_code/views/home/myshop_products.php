
<style media="screen">
  .subcategories .btnscollwala{
    background:white;
    line-height: 1;
    padding:8px 20px;
    color:black;
    font-size:0.9rem;
    text-transform: capitalize;
    border-radius: 10px;
    box-shadow:0 0 10px #e6eced;
  }
  .subcategories .btnscollwalaactive{
    background:#daf1f5;
    border:1px solid #057284;
    color:#057284;
  }
  .products .btnscollwala{
    background:white;
    line-height: 0.5;
    padding:8px 20px;
    color:black;
    font-size:0.7rem;
    text-transform: capitalize;
    border-radius: 5px;
    box-shadow:0 0 10px #e6eced;
  }
  .products .btnscollwalaactive{
    background:#daf1f5;
    border:1px solid #057284;
    color:#057284;
  }
  .pname{
    font-size:1rem;
    color:black;
  }
  .ptag{
    font-size:0.7rem;
    color:#5f6161;
  }
  .pmrp{
    font-size: 0.8rem;
    color:#f52f53;
  }
  .pprice{
    font-size: 1rem;
    color:#1d6b00;
  }
  .buttonthispage{
    font-size:0.7rem;
    padding:0.1rem 0.5rem;
  }
  a{
    text-decoration:none !important;
  }
</style>
<div class="p-2 subcategories" style="background:#f0f0f0;">
  <div class="row mx-0">
    <div class="col-4 p-0">
      <a href="<?php echo site_url('myshop/myshop_category/'.$groupname.'/'.$shop_type) ?>" style="display:block;background:transparent;border-radius:0;" class="btn dropdown-toggle"><?php echo $shop_product->category ?> </a>
    </div>
    <div class="col-8 p-0" style="">
      <div style="display:flex;overflow-x:scroll;align-items:center;height:100%;">
        <?php 
        if (!empty($shop_product->sub_cat)) {
          foreach ($shop_product->sub_cat as $key => $sub) { ?>
            <a href="#" class="btnscollwala <?php if($sub_cat_id == $sub->id) echo 'btnscollwalaactive' ?> mx-1"><?php echo $sub->sub_category ?></a>
          <?php } 
        } ?>
      </div>
    </div>
  </div>
</div>

<div class="p-0 products" style="background:#d4d8d9;">
  <div class="row mx-0">
    <div class="col-12 p-1 d-flex align-items-center justify-content-center">
      <div style="display:flex;overflow-x:scroll;height:100%;padding:0 !important;align-items:center !important;padding-top:0.1rem !important;">
        <?php 
        if (!empty($shop_product->sub_sub_cat)) {
          foreach ($shop_product->sub_sub_cat as $key => $sub_sub) { ?>
            <a href="#" class="btnscollwala btnscollwalaactive mx-1"><?php echo $sub_sub->sub_sub_category ?></a>
          <?php } 
        } ?>
      </div>
    </div>
  </div>
</div>

<div class="">
  <?php 
  if (empty($shop_product_details)) {
    echo "<h3>Product not found</h3>";
  }else{
    foreach ($shop_product_details as $key => $val) { ?>
       <a href="#">
        <div class="col-12 mx-auto p-2 my-1" style="border-bottom:1px solid #057284;overflow:hidden;">
          <div class="row mx-0">
            <div class="col-3 px-1">
              <img src="../../../assets/radio/1.jpg" style="width:100%;" alt="">
            </div>
            <div class="col-9 px-1 d-flex align-items-start justify-content-center">
              <div class="order-details" style="width:100%;">
                <p class="pname"><?php echo $val->product_name ?></p>
                <p class="ptag"><?php echo $val->product_tag_line ?></p>
                <p><span class="pprice">My Price: <?php echo $val->product_my_price ?></span> <span class="pmrp"><del>MRP: <?php echo $val->product_mrp ?></del> </span></p>
                <div class="row mx-0">
                  <div class="col-8 px-0">
                    <button type="button" class="btn btn-sm btn-warning buttonthispage" name="button">Buy Now</button>
                    <button type="button" class="btn btn-sm btn-warning buttonthispage" name="button">Add to Cart</button>
                  </div>
                  <div class="col-4 px-0 d-flex align-items-center justify-content-end">
                    <button href="#" class="btn buttonthispage btn-sm btn-outline-danger btn-sm"><i class="far fa-heart"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    <?php } 
  } ?>

</div>
