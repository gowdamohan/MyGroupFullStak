<div class="bg-g1 size1 flex-w flex-col-c-sb p-l-15 p-r-15 p-t-55 p-b-35 respon1">
  <div class="flex-col-c p-t-20 p-b-50" style="text-align:center; padding-bottom: 4px;padding-top: 20px;">
    <?php 

      $groupLogin = $top_icon['myapps'];
      foreach ($groupLogin as $k => $val) { ?>
        <a class="flex-c-m s1-txt2 size3 how-btn" href="<?php echo site_url('group/'.$val->name) ?>" style="color:white; margin-bottom: 1rem; display: flex; ">
          <img style="width: 20px;" src="<?php echo base_url().$val->icon ?>"> &nbsp;&nbsp;<?php echo $val->name ?>
        </a>
      <?php }
    ?>

  </div>
</div>

 
  <div class="row" style="margin:0">
    <?php if (!empty($about_us)) { ?>
      <div class="container">

        <div id="imageCarousel" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
            <?php $b=1; foreach ($about_us as $key => $val) { ?>
              <li data-target="#imageCarousel" data-slide-to="<?php echo $b ?>" class=" <?php if($b == 1) echo 'active' ?>"></li>
            <?php $b++; } ?>
          </ol>
          <a href="<?php echo site_url('home/about_us/'.'Mygroup') ?>">
          <div class="carousel-inner">
            <?php $k=1; foreach ($about_us as $key => $val) { ?>
             <div style="text-align: center;" class="carousel-item <?php if($k == 1) echo 'active' ?>" >
                <img style="width:50%" src="<?php echo $this->filemanager->getFilePath($val->image) ?>" class="img-responsive" />
                <br>
                <div class="text">
                   <?php  echo $val->content;?>
                </div>
             </div>
            <?php $k++; } ?>
            
          </div>
          </a>
          <a class="left carousel-control" href="#imageCarousel" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="right carousel-control" href="#imageCarousel" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    <?php } ?>

      
<script type="text/javascript">
  $('.readmore').on('click',function(){
    $('.showLess').show();
    $('.showMore').hide();
  });

  $('.lessmore').on('click',function(){
    $('.showLess').hide();
    $('.showMore').show();
  });


</script>
<div class="container bg-g1">
  <div class="row" style="padding-bottom: 4px;padding-top: 20px;">
    <?php 
      $groupLogin = $top_icon['myCompany'];
      foreach ($groupLogin as $k => $val) {
        if($val->name == 'Mygroup') {
            unset($groupLogin[$k]);
        }
      }
      foreach ($groupLogin as $k => $val) { ?>
        <div class="col-6">
          <a class="flex-c-m s1-txt2 how-btn" href="<?php echo site_url('group/'.$val->name) ?>" style="color:white; margin-bottom: 1rem;    display: flex; ">
            <img style="width: 20px;" src="<?php echo base_url().$val->icon ?>"> &nbsp;&nbsp;<?php echo $val->name ?>
          </a>
        </div>
      <?php }
    ?>

  </div>
</div>
  
  <?php if (!empty($main_ads)) { ?>
    <div class="container" style="padding:0">

      <div id="imageCarousel4" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#imageCarousel4" data-slide-to="1" class="active"></li>
          <li data-target="#imageCarousel4" data-slide-to="2" class=""></li>
          <li data-target="#imageCarousel4" data-slide-to="3" class=""></li>
        </ol>
        <div class="carousel-inner">
          <?php if (!empty($main_ads->ads1)) { ?>
            
            <div style="text-align: center;" class="carousel-item active">
              <a href="<?php echo $main_ads->ads1_url ?>">
                <img style="width:100%" src="<?php echo base_url().$main_ads->ads1 ?>" class="img-responsive" />
              </a>
            </div>
          
          <?php } ?>

          <?php if (!empty($main_ads->ads2)) { ?>
            <div style="text-align: center;" class="carousel-item " >
              <a href="<?php echo $main_ads->ads2_url ?>">
                <img style="width:100%" src="<?php echo base_url().$main_ads->ads2 ?>" class="img-responsive" />
              </a>
           </div>
          <?php } ?>
          <?php if (!empty($main_ads->ads3)) { ?>
            <div style="text-align: center;" class="carousel-item ">
              <a href="<?php echo $main_ads->ads3_url ?>">
                <img style="width:100%" src="<?php echo base_url().$main_ads->ads3 ?>" class="img-responsive" />
              </a>
            </div>
          <?php } ?>
        </div>
        <a class="left carousel-control" href="#imageCarousel4" role="button" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#imageCarousel4" role="button" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </div>
   
  <?php } ?>
   


<div class="container bg-g1">
  <h5 style="text-align: center;color: #fff;border-bottom: 2px solid;padding: 10px;">Online Apps</h5>
  <div class="row" style="padding-bottom: 4px;padding-top: 20px;margin:0">
    <?php 
      $groupLogin = $top_icon['online'];
      foreach ($groupLogin as $k => $val) {
        if($val->name == 'Mygroup') {
            unset($groupLogin[$k]);
        }
      }
      foreach ($groupLogin as $k => $val) { ?>
        <div class="col-6 text-center">
           <img style="width: 20px;" src="<?php echo base_url().$val->logo ?>">
          <a class="flex-c-m s1-txt2 how-btn1" href="<?php echo site_url('group/'.$val->name) ?>" style="color:white; margin-bottom: 1rem;display: flex; "><?php echo $val->name ?>
          </a>
         
        </div>
      <?php }
    ?>
  </div>
</div>
<div class="container bg-g1">
  <h5 style="text-align: center;color: #fff;border-bottom: 2px solid;padding: 10px;">Offline Apps</h5>
  <div class="row" style="padding-bottom: 4px;padding-top: 20px;margin:0">
    <?php 
      $groupLogin = $top_icon['offline'];
      foreach ($groupLogin as $k => $val) {
        if($val->name == 'Mygroup') {
            unset($groupLogin[$k]);
        }
      }
      foreach ($groupLogin as $k => $val) { ?>
        <div class="col-6 text-center">
           <img style="width: 20px;" src="<?php echo base_url().$val->logo ?>">
          <a class="flex-c-m s1-txt2 how-btn1" href="<?php echo site_url('group/'.$val->name) ?>" style="color:white; margin-bottom: 1rem;display: flex; "><?php echo $val->name ?>
          </a>
         
        </div>
      <?php }
    ?>
  </div>
</div>

<?php if (!empty($newsroom)) { ?>

    <div class="container" style="padding:0">

      <div id="imageCarousel5" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#imageCarousel5" data-slide-to="1" class="active"></li>
          <li data-target="#imageCarousel5" data-slide-to="2" class=""></li>
          <li data-target="#imageCarousel5" data-slide-to="3" class=""></li>
          <li data-target="#imageCarousel5" data-slide-to="4" class=""></li>
        </ol>
        <div class="carousel-inner">
          <?php if (!empty($newsroom->image)) { ?>
            <div style="text-align: center;" class="carousel-item active">
              <img style="width:100%;height: 250px;" src="<?php echo $this->filemanager->getFilePath($newsroom->image) ?>" class="img-responsive" />
            </div>
          <?php } ?>

          <?php if (!empty($awards->image)) { ?>
            <div style="text-align: center;" class="carousel-item " >
              <img style="width:100%;height: 250px;" src="<?php echo $this->filemanager->getFilePath($awards->image) ?>" class="img-responsive" />
            </div>
          <?php } ?>
          <?php if (!empty($event->image)) { ?>
            <div style="text-align: center;" class="carousel-item ">
              <img style="width:100%;height: 250px;" src="<?php echo $this->filemanager->getFilePath($event->image) ?>" class="img-responsive" />
            </div>
          <?php } ?>
          <?php if (!empty($gallery->image_name)) { ?>
            <div style="text-align: center;" class="carousel-item ">
              <img style="width:100%;height: 250px;" src="<?php echo $this->filemanager->getFilePath($gallery->image_name) ?>" class="img-responsive" />
            </div>
          <?php } ?>
        </div>
        <a class="left carousel-control" href="#imageCarousel5" role="button" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#imageCarousel5" role="button" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </div>
   
  <?php } ?>

</div>

<style type="text/css">
  .list-group a{
    line-height: 1.5rem;
    text-align: left;
    margin-bottom: 0.1rem;


  }
  .list-group .active{
    color: #fff;
    background-color: #ffc107;
    border-color: #ffc107;
  }
  .bg-g1{
    background: linear-gradient(-45deg, #ac32e4, #7918f2, #4801ff);
  }
 
@media (max-width: 576px)
.respon1 {
    padding-top: 35px;
}
.how-btn {
    padding: 10px 20px;
    background-color: transparent;
    border-radius: 25px;
    border: 2px solid #fff;
    position: relative;
    z-index: 1;
    overflow: hidden; 
}
.how-btn1{
     padding: 4px 10px;
    background-color: transparent;
    border-radius: 25px;
    border: 2px solid #fff;
    position: relative;
    z-index: 1;
    overflow: hidden; 
}
.size3 {
    min-width: 280px;
    max-width: 75%;
    min-height: 50px;
    margin:auto;
}
.s1-txt2 {
    font-family: Montserrat-Bold;
    font-size: 15px;
    color: #fff;
    line-height: 1.2;
}
.flex-c-m {
    justify-content: center;
    -ms-align-items: center;
    align-items: center;
}
</style>


<div class="list-group" style="margin-bottom: 1rem;">
 <!--  <a href="<?php echo site_url('home/change_nav/'.'my-apps') ?>" class="btn btn-primary btn-rounded   <?php if($navName == 'my-apps') echo 'active' ?> ">My Apps</a>
  <a href="<?php echo site_url('home/change_nav/'.'my-company') ?>" class="btn btn-primary btn-rounded  <?php if($navName == 'my-company') echo 'active' ?>">My Company</a>
  <a href="<?php echo site_url('home/change_nav/'.'my-onine-apps') ?>" class="btn btn-primary btn-rounded   <?php if($navName == 'my-onine-apps') echo 'active' ?>">My Online Apps</a>
  <a href="<?php echo site_url('home/change_nav/'.'my-offline-apps') ?>" class="btn btn-primary btn-rounded  <?php if($navName == 'my-offline-apps') echo 'active' ?>">My Offline Apps</a> -->

  <a href="#" class="btn btn-primary btn-rounded   <?php if($navName == 'my-apps') echo 'active' ?> ">My Apps</a>
  <a href="#" class="btn btn-primary btn-rounded  <?php if($navName == 'my-company') echo 'active' ?>">My Company</a>
  <a href="#" class="btn btn-primary btn-rounded   <?php if($navName == 'my-onine-apps') echo 'active' ?>">My Online Apps</a>
  <a href="#" class="btn btn-primary btn-rounded  <?php if($navName == 'my-offline-apps') echo 'active' ?>">My Offline Apps</a>
</div>


<div class="row" style="margin:0">
  
  <?php if (!empty($testimonials)) { ?>
    <div class="container" style="padding:0">
      <div id="imageCarousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <?php $t=1; foreach ($testimonials as $key => $val) { ?>
            <li data-target="#imageCarousel" data-slide-to="<?php echo $t ?>" class=" <?php if($t == 1) echo 'active' ?>"></li>
          <?php $t++; } ?>
        </ol>
        <div class="carousel-inner">
          <?php $t1=1; foreach ($testimonials as $key => $val) { ?>
             <div style="text-align: center;" class="carousel-item <?php if($t1 == 1) echo 'active' ?>" >
              <section class="t-bq-section" id="jasper">
                <div class="t-bq-wrapper t-bq-wrapper-boxed">
                    <div class="t-bq-quote t-bq-quote-jasper">
                        <div class="t-bq-quote-jasper-pattern">
                            <div class="t-bq-quote-jasper-qmark">
                                &#10077;
                            </div>
                        </div>
                        <div class="t-bq-quote-jasper-userpic">
                            <img style="width: 100%; border-radius: 50%; height: 70px;" id="imageresource" src="<?php echo $this->filemanager->getFilePath($val->image) ?>" alt="...">
                        </div>

                        <div class="t-bq-quote-jasper-base">
                            <blockquote class="t-bq-quote-jasper-text" cite="Strugatsky Brothers">
                                <?php  echo $val->content; ?>
                            </blockquote>
                            <div class="t-bq-quote-jasper-meta">
                                <div class="t-bq-quote-jasper-meta-info">
                                    <div class="t-bq-quote-jasper-author"><cite><?php  echo $val->title; ?></cite></div>
                                    <div class="t-bq-quote-jasper-source"><span> <?php  echo $val->tag_line; ?> </span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
           </div>

          <?php $t1++; } ?>
        </div>
        <a class="left carousel-control" href="#imageCarousel" role="button" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#imageCarousel" role="button" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </div>
  <?php } ?>
</div>

<style type="text/css">
    blockquote {
        padding: 0;
        margin: 0;
    }

section.t-bq-section {
    padding: 0px;
    margin-bottom: 20px;
}
.heading {
    text-align: center;
    color: #4c372f;
    font-size: 30px;
    font-weight: 700;
    margin-bottom: 20px;
    text-transform: uppercase;
    border: 1px solid #ccc;
    border-radius: 10px;
     background: #4cf8e6;
}
.t-bq-wrapper.t-bq-wrapper-boxed {
    max-width: 576px;
    margin: 0 auto;
}

.t-bq-wrapper.t-bq-wrapper-fullwidth {
    max-width: 100%;
}


.t-bq-quote-jasper {
    position: relative;
    box-shadow: 2px 2px 25px #cecece;
    border-radius: 10px;
}

.t-bq-quote-jasper .t-bq-quote-jasper-pattern {
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    height: 80px;
    align-items: center;
    justify-content: flex-start;
    background: url("https://raw.githubusercontent.com/taviskaron/t-div-blockquotes/main/img/hexabump.png") repeat;
    border-radius: 10px 10px 0 0;
}

.t-bq-quote-jasper .t-bq-quote-jasper-pattern .t-bq-quote-jasper-qmark {
    flex-basis: 100px;
    font-family: Garamond, Georgia, "Times New Roman", serif;
    font-size: 60pt;
    color: #999999;
    text-align: center;
    height: 80px;
    line-height: 90pt;
    -moz-user-select: none;
    -ms-user-select: none;
    -webkit-user-select: none;
    user-select: none;
}

.t-bq-quote-jasper .t-bq-quote-jasper-userpic {
    position: absolute;
    top: 45px;
    left: calc(50% - 35px);
    width: 70px;
    height: 70px;
    background-size: cover;
    border-radius: 50%;
}

.t-bq-quote-jasper .t-bq-quote-jasper-base {
    flex-basis: calc(100% - 80px);
    background: #ded3d3;
    padding: 60px 30px 50px 100px;
    font-size: 11pt;
    line-height: 1.62em;
    border-radius: 0 0 10px 10px;
}

.t-bq-quote-jasper .t-bq-quote-jasper-meta {
    margin-top: 30px;
    padding-top: 10px;
    border-top: 2px dotted #777777;
    text-align: center;
}

.t-bq-quote-jasper .t-bq-quote-jasper-meta .t-bq-quote-jasper-author,
.t-bq-quote-jasper .t-bq-quote-jasper-meta .t-bq-quote-jasper-source {
    color: #777777;
}

.t-bq-quote-jasper .t-bq-quote-jasper-meta .t-bq-quote-jasper-author {
    font-style: normal;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    font-size: 10pt;
    font-weight: bold;
}

.t-bq-quote-jasper .t-bq-quote-jasper-meta .t-bq-quote-jasper-author cite {
    font-style: normal;
}

.t-bq-quote-jasper .t-bq-quote-jasper-meta .t-bq-quote-jasper-source {
    font-size: 9pt;
}

@media screen and (max-width: 768px) {

    .t-bq-quote-jasper .t-bq-quote-jasper-base {
        padding-left: 30px;
    }
}

</style>