<style media="screen">
  .radioicon{
    position: absolute;
    bottom:0.5rem;
    left:0.5rem;
    color:white;
    background: red;
    border:2px solid white;
    padding:5px;
    border-radius:50%;
    width:2.5rem;
    height:2.5rem;
    display: flex;
    align-items:center;
    justify-content: center;
  }
  .radioicon i{
    font-size:1.2rem;
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
      <div class="col-6 mb-3 col-md-3 col-lg-3 px-2 d-flex">
        <a href="#">
          <div class="" style="width:100%;height:100%;">
            <div class="d-flex align-items-center justify-content-center" style="position:relative;overflow:hidden;">
              <img src="<?php echo base_url();?>/assets/youtube/<?php echo $i ?>.jpg" style="width:100%;" alt="">
              <div class="radioicon">
                <a href="#" style="line-height:0;"><i class="fa fa-youtube"></i></a>
              </div>

            </div>
            <p>Youtube Channel <?php echo $i ?> </p>
          </div>
        </a>
      </div>
      <?php
    }
     ?>

  </div>
</section>
