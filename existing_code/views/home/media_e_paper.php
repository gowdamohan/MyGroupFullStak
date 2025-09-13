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
  .radioicon a{
    text-decoration:none !important;
  }
  .radioicon p{
    color:black !important;
    margin-top:0.2rem !important;
    font-size:0.8rem;
    font-weight:600;
    letter-spacing:1px;
    text-align: center;
  }
  .magazinesec{
    width:100%;
    height:35vh;
    position:relative;
    background-position: center center !important;
    background-size: cover !important;
    background-position: no-repeat;
    border:5px solid #057284;
  }
  .labelimage{
    position:absolute;
    top:0;
    right:0;
    background:url('<?php echo base_url();?>/assets/paper/paper_label.png');
    background-size:cover;
    height:2rem;
    width:6rem;
    z-index:999;
    display:flex;
    align-items:center;
    justify-content:end;
  }
  .labelimage p{
    color:white !important;
    font-size:0.7rem;
    line-height:1;
    font-weight:bold;
  }
</style>
<section class="pb-5">
  <div class="row mx-0 mt-4">

    <?php
    for ($i=1; $i < 11; $i++) {
      ?>
      <!-- single magazine start -->
      <div class="col-6 mb-3 col-md-4 col-lg-3 d-flex px-2">
        <a href="#" style="width:100%;height:100%;">
          <div class="magazinesec" style="background:url('<?php echo base_url();?>/assets/paper/1.jpg');">
            <div class="labelimage" style="">
              <p class="mb-1 text-center" style="">27-05-2022</p>
            </div>
          </div>
          <p class="my-1">Paper Name <?php echo $i ?></p>
        </a>
      </div>
      <!-- single magazine end -->
      <?php
    }
     ?>


  </div>
</section>
