<style media="screen">
  .tvbanner{
    width:100%;
    height:15vh;
    background-position: center center !important;
    background-repeat: no-repeat !important;
    background-size: cover !important;
  }
  .tvbanner a{
    text-decoration: none !important;
  }
  .tvbanner p{
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
        <a href="#" style="width:100%;height:100%;">
          <div class="" style="width:100%;height:100%;">
            <div class="tvbanner" style="background:url('<?php echo base_url();?>/assets/web/<?php echo $i ?>.jpg')">

            </div>
            <p>Website <?php echo $i ?> </p>
          </div>
        </a>
      </div>
      <?php
    }
     ?>

  </div>
</section>
