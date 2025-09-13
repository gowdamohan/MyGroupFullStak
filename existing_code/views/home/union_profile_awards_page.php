 
  <section id="sec-features" class="sec-features pt-5 pb-5">
    <div class="container">
      <?php 
      $i=1;
      foreach ($union_details as $key => $val) { ?>
        <div class="row align-items-center">
          <div class="col-md-6">
            <h3 class="h4"><?php echo $val->awards_name ?></h3>
            <hr />
            <img class="d-block w-100" style="height:250px" src="<?php echo $this->filemanager->getFilePath($val->awards_path) ?>" alt="First slide">
          </div>
        </div>
        <?php $i++; }
      ?>
    </div>
  </section>

<script type="text/javascript">
  function readmore(i) {
    $('.showLess'+i).show();
    $('.showMore'+i).hide();
  }

  function lessmore(i) {
    $('.showLess'+i).hide();
    $('.showMore'+i).show();
  }
</script>