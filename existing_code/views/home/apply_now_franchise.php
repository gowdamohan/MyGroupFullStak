<?php 
    if ($this->mobile_detect->isMobile()) { ?>
       <section id="sec-features" class="sec-features pt-5 pb-5" style="margin-top:25%">
    <?php }else{ ?>
       <section id="sec-features" class="sec-features pt-5 pb-5">
    <?php }
?>
    <div class="row">
        <div class="container">
            <div class="panel-body">
                <a href="<?php echo base_url() ?>">
                    <div class="back-button">
                        <div class="arrow-wrap">
                            <span class="arrow-part-1"></span>
                            <span class="arrow-part-2"></span>
                            <span class="arrow-part-3"></span>
                        </div>
                    </div>
                </a>
                <div class="col-md-6 col-md-offset-2">
                  
                    <div class="form-group">
                        <a style="background: #2f409c;" href="<?php echo site_url('home/franchaise_resume_form/'.$groupname.'/'.'National') ?>" class="btn btn-info btn-lg btn-block">
                            <small>Apply for</small>
                             <br> 
                           <h3> National Franchise</h3>
                        </a>
                         <a style="background: #2f409c;" href="<?php echo site_url('home/franchaise_resume_form/'.$groupname.'/'.'Regional') ?>" class="btn btn-info btn-lg btn-block">
                            <small>Apply for</small>
                             <br> 
                           <h3> Regional Office</h3>
                        </a>
                         <a style="background: #2f409c;" href="<?php echo site_url('home/franchaise_resume_form/'.$groupname.'/'.'Branch') ?>" class="btn btn-info btn-lg btn-block">
                            <small>Apply for</small>
                             <br> 
                           <h3> Branch Office</h3>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style type="text/css">
    .back-button{
  width:50px;
  height:50px;
  position: absolute;
  top: 30%;
  left: 10%;
  transform: translate(-50%, -50%);
  border-radius:50%;
  border:#03A9F4 1px solid;
  overflow:hidden;
  transition:background 0.3s ease;
}

.arrow-wrap{
    display:block;
    position:absolute;
    height:70%;
    width:70%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    transition:left 0.3s ease;
}
.arrow-wrap span{
  height:1px;
  left:0;
  top:50%;
  background:#03A9F4;
  position:absolute;
  display:block;
  transition:background 0.3s ease;
}
.arrow-part-1{
  width:100%;
  transform: translate(0, -50%);
}
.arrow-part-2{
  width:60%;
  transform: rotate(-45deg);
  transform-origin: 0 0;
}
.arrow-part-3{
  width:60%;
  transform: rotate(45deg);
  transform-origin: 0 0;
}
</style>