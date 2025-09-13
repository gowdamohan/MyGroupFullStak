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
  .order-details a{
    color:black !important;
    text-decoration:none !important;
  }
</style>

<section class="pb-5">
  <div class="row mx-0">
    <a class="btn btn-sm <?php echo $btn1 ?>-info col-4">Recent</a>
    <a class="btn btn-sm <?php echo $btn2 ?>-success col-4">Completed</a>
    <a class="btn btn-sm <?php echo $btn3 ?>-danger col-4">Cancel / Reject</a>
  </div>

    <?php
      for ($i=0; $i < 5; $i++) {
        ?>
        <a href="#">
          <div class="col-12 mx-auto p-2 my-1" style="border-bottom:1px solid #057284;overflow:hidden;">
            <div class="row mx-0">
              <div class="col-3 px-1">
                <img src="../../../assets/radio/1.jpg" style="width:100%;" alt="">
              </div>
              <div class="col-8 px-1 d-flex align-items-center justify-content-center">
                <div class="order-details" style="width:100%;">
                  <h5>Order name or service</h5>
                  <p>Main Category / Sub Category</p>
                  <p>Booked on 28-05-2022</p>
                  <p>
                    <span class="badge badge-pill badge-info">Booked</span>
                    <span class="badge badge-pill badge-warning">Accepted</span>
                    <span class="badge badge-pill badge-danger">Canceled</span>
                    <span class="badge badge-pill badge-primary">Rejected</span>
                  </p>
                </div>
              </div>
              <div class="col-1 px-1 d-flex align-items-center justify-content-center">
                <i class="fas fa-angle-right"></i>
              </div>
            </div>
          </div>
        </a>
        <?php
      }
     ?>
</section>
