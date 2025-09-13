 
  <section id="sec-features" class="sec-features pt-5 pb-5">
    <div class="container">
      <?php 
      $i=1;
      foreach ($union_details as $key => $val) { ?>
        <div class="row align-items-center">
          <div class="col-md-6 text-center">
            <img class="d-block w-100" style="height:250px" src="<?php echo $this->filemanager->getFilePath($val->objectivies_path) ?>" alt="First slide">
          </div>
          <div class="col-md-6">
            <h3 class="h4"><?php echo $val->objectivies_name ?></h3>
            <hr />

            <div class="showMore<?php echo $i ?>">
              <?php 
                echo substr($val->objectivies_description, 0, 500) . '...'.'<a onclick="readmore('.$i.')" class="readmore">Read more</a>';
              ?>
            </div>
            <div class="showLess<?php echo $i ?>" style="display: none;" >
              <?php 
                echo $val->objectivies_description.'<a  onclick="lessmore('.$i.')" class="lessmore">Read less </a>';
              ?>
            </div>
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