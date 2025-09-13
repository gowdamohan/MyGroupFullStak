 
  <section id="sec-features" class="sec-features pt-5 pb-5">
     <div class="row" style="margin: 0;">
      <div class="container">
        <div class="heading white-heading">
          About us
        </div>
      </div>

        <?php 
        foreach ($about_us as $key => $val) { ?>
            <div class="container align-items-center">
              <figure class="figure" style="width: 100%;text-align: center;">
                <img style="width: 50%;" src="<?php echo $this->filemanager->getFilePath($val->image) ?>" class="figure-img img-fluid rounded" alt="...">
                <figcaption class="figure-caption"><?php echo $val->title ?></figcaption>
              </figure>
            </div>
            <div class="container align-items-center">
                <?php 
                  echo $val->content;
                ?>
            </div>
          <?php } 
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


</script>
<style type="text/css">
  .showMore p{
    word-wrap: break-word;
  }
  .showLess p{
    word-wrap: break-word;
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
</style>