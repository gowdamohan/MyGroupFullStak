<style media="screen">
  .radioicon{
    position: absolute;
    bottom:0.5rem;
    left:0.5rem;
    color:white;
    border:2px solid white;
    padding:5px;
    border-radius:50%;
    width:2rem;
    height:2rem;
    display: flex;
    align-items:center;
    justify-content: center;
  }
  .radioicon i{
    font-size:0.8rem;
    color:white;
  }
  .radioicon p{
    color:black !important;
    margin-top:0.2rem !important;
    font-size:0.8rem;
    font-weight:600;
    letter-spacing:1px;
    text-align: center;
  }
</style>
<section class="pb-5">
  <div class="row mx-0 mt-4">

    <?php
    for ($i=1; $i < 11; $i++) {
      ?>
      <div class="col-6 mb-3 col-md-3 col-lg-2 px-2 d-flex">
        <div class="" style="width:100%;height:100%;">
          <div class="d-flex align-items-center justify-content-center" style="position:relative;border-radius:20px;overflow:hidden;">
            <img src="<?php echo base_url();?>/assets/radio/<?php echo $i ?>.jpg" style="width:100%;" alt="">
            <div class="radioicon">
              <a href="#"><i class="fa fa-broadcast-tower"></i></a>
            </div>

          </div>
          <p>Radio Name <?php echo $i ?> </p>
        </div>
      </div>
      <?php
    }
     ?>

  </div>
</section>
