<section class="pt-4 pb-5" style="background:#ebebeb;">
  <div class="row">
    <div class="col-2">
      <a class="btn btn-info btn-sm" style="font-style:italic;" href="<?php echo site_url('myunions/unions_single/'.$groupname.'/'.$union_type.'/'.$unionId) ?>">Back</a>
    </div>
    <div class="col-10">
      <center>
        <h3>Direcotr List</h3>
      </center>
    </div>
  </div>
  <?php foreach ($union_director as $key => $val) { ?>
    <div class="col-12 mx-auto" style="padding: 0; background: #fff;">
      <a href="#">
        <div class="col-12 mx-auto p-2 my-1" style="border-bottom:1px solid #057284;overflow:hidden;">
          <div class="row mx-0">
            <div class="col-3 px-1">
              <img src="<?php echo (!empty($val->photo) == '') ? base_url().'assets/front/logo.png' : $this->filemanager->getFilePath($val->photo); ?>" style="width:100%;" alt="">
            </div>
            <div class="col-9 px-1 d-flex align-items-start justify-content-center">
              <div class="order-details" style="width:100%;">
                <p class="pname"><?php echo $val->name ?></p>
                <p class="ptag"><?php echo $val->designation ?></p>
                <p><?php echo $val->description ?></p>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
  <?php } ?>
</section>


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