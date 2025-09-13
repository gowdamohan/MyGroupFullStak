<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">News Letter</h3>         
    </div>

    <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/upload_client_news_letter') ?>"  id="students" action="" class="form-horizontal" data-parsley-validate >
        <div class="panel-body table-responsive">
          <div class="col-md-6 col-md-offset-2">

            <div class="form-group">
              <label class="control-label col-sm-4">Title</label>
              <div class="col-md-8">
                <input type="text" name="news_letter_name" class="form-control" id="news_letter_name" >
              </div>
            </div>

              <div class="form-group">
                <label class="control-label col-sm-4" for="awards_path">Upload file</label>
                <div class="col-sm-8">
                   <input type="file" required="" name="news_letter_path" class="form-control" id="image-upload">
                </div>
                <div id="upload-Preview" style="text-align: center;"></div>
              </div>
          </div>
        </div>
        <div class="panel-footer">
          <center>
            <input type="submit" value="Upload" id="up-btn" class="btn btn-primary">
          </center>
        </div>
    </form>
    <div class="panel-body table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Download Attached</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($client_news_letter)) { ?>
            <?php $i=1; foreach ($client_news_letter as $key => $aw) { ?>
              <tr>
                <td><?php echo $i++ ?></td>
                <td><?php echo $aw->news_letter_name ?></td>
                <td><a href="<?php echo site_url('client_controller/download_client_news_letter/'.$aw->id) ?>">Download</a></td>
                <td><a onclick="return confirm('Are you going to  delete this document. Are you sure? ')" class="btn btn-danger" href="<?php echo site_url('client_controller/delete_news_letter/'.$aw->id) ?>">Delete</a></td>
              </tr>
            <?php } ?>
          <?php }else{
            echo " <tr><th colspan='4' class='text-center'><h3>No News Letter Found</h3></th></tr>";
          } ?>
        </tbody>
      </table>
    </div>

</div>