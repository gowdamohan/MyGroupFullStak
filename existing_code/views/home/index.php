<div class="container-fluid text-center">    
  <div class="row content">
    <div class="col-sm-2 sidenav" style="height: auto; overflow: hidden;">
       <?php 
        $count = 0; 
        foreach ($body_content as $key => $val) {
          if($count % 2 == 0) 

          echo '<p style="margin-bottom: 3rem; margin-top:1rem"><img style="width:20px; margin-right: 10px;" src="'.base_url(). $val->icon.'"><a href="'.$val->url.'"> '.$val->name.'</a></p>';

          if ($count % 2 != 0) {
            echo '<p style="margin-bottom: 2rem;"><img style="width:20px; margin-right: 10px;" src="'.base_url(). $val->icon.'"><a href="'.$val->url.'">'.$val->name.'</a></p><hr>';
          }
        $count ++;

      } ?>
    </div>
    <div class="col-sm-8 text-left" style="height: auto; overflow: hidden;">
      <?php if (empty($navName)) { ?>

         <?php if (!empty($header_sliders->main_ads)) { ?>
            <div class="col-md-12" style="margin-top: 1rem;">
              <img style="width: 100%;" src="<?php echo base_url().$header_sliders->main_ads ?>">
            </div>
          <?php }else{ ?>

            <?php foreach ($body_content as $key => $val) { ?>
              <div class="col-md-6" style="margin-top: 1rem;">
                <img style="width: 100%; height: 100px;"  src="<?php echo base_url().$val->banner ?>">
              </div>
            <?php } ?> 
          <?php } ?>

    <?php  }else{ ?>
       <?php foreach ($body_content as $key => $val) { ?>
              <div class="col-md-6" style="margin-top: 1rem;">
                <img style="width: 100%; height: 100px;"  src="<?php echo base_url().$val->banner ?>">
              </div>
            <?php } ?> 
    <?php } ?>

    </div>
    <div class="col-sm-2 sidenav" style="overflow: scroll;height: auto; border: 1px solid #ccc; ">
      <?php if (!empty($header_sliders->side_ads)) {

        $filetype = pathinfo($header_sliders->side_ads, PATHINFO_EXTENSION);
        if ($filetype == 'mp4') { ?>
          <video width="100%" height="240" autoplay="" loop="" muted="" controls="">
            <source src="<?php echo $header_sliders->side_ads ?>" type="video/mp4">
          </video>
        <?php }
        ?>

        <div class="main-content">
          <div class="item button-hand" style="--bg-color: #3498db;">
            <button>Click Me!
              <div class="hands"></div>
            </button>
          </div>
        </div>

      <?php } ?>
     

    </div>
  </div>
</div>

<style type="text/css">
  hr{
    border-top: 2px solid #ff04bb;
  }

.main-content {
  width: 100%;
}
.item {
 
  background: var(--bg-color);
}
.item&:not(.footer) {
  padding-top: 1rem;
}

button {
  background: transparent;
  color: #fff;
  border: 3px solid #fff;
  border-radius: 50px;
  padding: 0.8rem 2rem;
  font: 24px "Margarine", sans-serif;
  outline: none;
  cursor: pointer;
  position: relative;
  transition: 0.2s ease-in-out;
  letter-spacing: 2px;
}

.name {
  width: 100%;
  text-align: center;
  padding: 0 0 3rem;
  font: 500 14px 'Rubik', sans-serif;
  letter-spacing: .5px;
  text-transform: uppercase;
  text-shadow: 0 1px 1px rgba(0,0,0,0.4);
}


</style>