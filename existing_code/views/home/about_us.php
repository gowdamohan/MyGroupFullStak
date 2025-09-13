 
  <section id="sec-features" class="sec-features pt-5 pb-5">
    <div class="container">
      <?php 
      foreach ($about_us as $key => $val) { 

          if ($key % 2 == 0){ ?>
            <div class="row align-items-center">
              <div class="col-md-6">
                <h3 class="h4"><?php echo $val->title ?></h3>
                <hr />

                <div class="showMore">
                  <?php 
                    echo substr($val->content, 0, 600) . '...'.'<a class="readmore">Read more</a>';
                  ?>
                </div>
                <div class="showLess" style="display: none;" >
                  <?php 
                    echo $val->content.'<a class="lessmore">Read less </a>';
                  ?>
                </div>
              </div>

              <div class="col-md-6 text-center">
                <img class="d-block w-100" style="height:401px" src="<?php echo $this->filemanager->getFilePath($val->image) ?>" alt="First slide">
              </div>
            </div>
          <?php }else{ ?>
           <div class="row align-items-center">
              <div class="col-md-6 text-center">
                <img class="d-block w-100" style="height:401px" src="<?php echo $this->filemanager->getFilePath($val->image) ?>" alt="First slide">
              </div>

              <div class="col-md-6">
                <h3 class="h4"><?php echo $val->title ?></h3>
                <hr />

                <div class="showMore1">
                  <?php 
                    echo substr($val->content, 0, 600) . '...'.'<a class="readmore1">Read more</a>';
                  ?>
                </div>
                <div class="showLess1" style="display: none;" >
                  <?php 
                    echo $val->content.'<a class="lessmore1">Read less </a>';
                  ?>
                </div>

              </div>
            </div>
          <?php }
        } 
      ?>
    </div>
  </section>

<script type="text/javascript">
  $('.readmore').on('click',function(){
    $('.showLess').show();
    $('.showMore').hide();
  });

  $('.lessmore').on('click',function(){
    $('.showLess').hide();
    $('.showMore').show();
  });

  $('.readmore1').on('click',function(){
    $('.showLess1').show();
    $('.showMore1').hide();
  });

  $('.lessmore1').on('click',function(){
    $('.showLess1').hide();
    $('.showMore1').show();
  });

</script>