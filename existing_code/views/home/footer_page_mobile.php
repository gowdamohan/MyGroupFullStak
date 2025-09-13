
  <section id="sec-features" class="sec-features pt-5 pb-5">
    <div class="row" style="margin: 0;">
       <div class="container">
        <div class="heading white-heading">
          <?php echo str_replace('_', ' ', $pagename)  ?>
        </div>
      </div>

      <div class="container align-items-center">
      <?php 
      $sl= 1;
      foreach ($footer_data as $key => $val) { ?>
          <div class="container">
            <figure class="figure" style="width: 100%;text-align: center;">
              <a href="#" onclick="open_imagepreview(<?php echo $sl ?>)" id="pop">
              <?php if ($pagename == 'events' || $pagename == 'advertise_with_us') { ?>
                  <img style="width: 100%;" id="imageresource<?php echo $sl ?>" src="<?php echo $this->filemanager->getFilePath($val->image) ?>" class="figure-img img-fluid rounded" alt="...">
              <?php }else{ ?>
                  <img style="width: 80%;" id="imageresource<?php echo $sl ?>" src="<?php echo $this->filemanager->getFilePath($val->image) ?>" class="figure-img img-fluid rounded" alt="...">
              <?php } ?>
            
              </a>
              <figcaption class="figure-caption"><?php echo $val->title ?></figcaption>
            </figure>
          </div>
          <div class="container">
              <?php 
                echo $val->content;
              ?>
          </div>
        <?php $sl++; } 
      ?>
      </div>
    </div>
  </section>


<script type="text/javascript">
  function open_imagepreview(sl) {
    $('#imagepreview').attr('src', $('#imageresource'+sl).attr('src'));
    $('#imagemodal').modal('show');
  }
 
  $('.readmore').on('click',function(){
    $('.showLess').show();
    $('.showMore').hide();
  });

  $('.lessmore').on('click',function(){
    $('.showLess').hide();
    $('.showMore').show();
  });

  function zoomin() {
  var myImg = document.getElementById("imagepreview");
    var currWidth = myImg.clientWidth;
    if (currWidth == 2500) return false;
    else {
      myImg.style.width = (currWidth + 100) + "px";
    }
  }

  function zoomout() {
    var myImg = document.getElementById("imagepreview");
    var currWidth = myImg.clientWidth;
    if (currWidth == 100) return false;
    else {
      myImg.style.width = (currWidth - 100) + "px";
    }
  }


</script>

<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Preview</h4>
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
      </div>
     
        <div id="navbar" style="margin-bottom:1rem">
          <button type="button" class="btn btn-primary" onclick="zoomin()"> Zoom In</button>
          <button type="button" class="btn btn-info"  onclick="zoomout()"> Zoom Out</button>
        </div>
       <div class="modal-body" style="overflow-x: scroll;">
        <img src="" id="imagepreview" style="width: 100%;" >
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

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