 <div class="panel panel-default">
   <div class="panel-heading">
    <h3 class="panel-title">Media Link</h3>
   </div>

   <?php
   if (!empty($media_link)) {
      $formurl = site_url('Client_controller/update_media_client_live_url_link');
      $link = $media_link->media_link;
   }else{
      $formurl = site_url('Client_controller/insert_media_client_live_url_link');
      $link = '';
   }
    ?>
    <form data-validation="true" action="<?php echo $formurl ?>" method="post" enctype="multipart/form-data">
      <input type="hidden" name="media_channel_id" value="<?php echo $media_chanel_id ?>" >
      <input type="hidden" name="media_type" value="<?php echo $mediaType ?>" >
      <div class="panel-body">
        <div class="col-md-6 col-md-offset-2">
          <div class="form-group">
            <label class="control-label col-sm-4" for="no_of_days">Meida URL Link <font color="red">*</font></label>
            <div class="col-sm-8">
              <input type="text" name="media_link" value="<?php echo $link  ?>" class="form-control" id="media_link">
            </div>
          </div>
        </div>
      </div>
      <div class="panel-footer">
        <center>
          <button type="submit" class="btn btn-primary">Submit</button>
          <a class="btn btn-danger" href="<?php echo site_url('client_controller/media_dashboard') ?>">Back</a>
        </center>
      </div>
    </form>
</div>
