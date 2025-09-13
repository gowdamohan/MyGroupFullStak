<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Objectivies</h3>         
    </div>

    <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/upload_client_objectivies') ?>"  id="students" action="" class="form-horizontal" data-parsley-validate >
        <div class="panel-body table-responsive">
          <div class="col-md-6 col-md-offset-2">

            <div class="form-group">
              <label class="control-label col-sm-4">Title</label>
              <div class="col-md-8">
                <input type="text" name="objectivies_name" class="form-control" id="objectivies_name" >
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-4" for="awards_path">Upload file</label>
              <div class="col-sm-8">
                 <input type="file" required="" name="objectivies_path" class="form-control" id="image-upload">
              </div>
              <div id="upload-Preview" style="text-align: center;"></div>
            </div>

            <div class="form-group" id="textSummernote">
              <label class="control-label col-sm-4" for="summernote">Description</label>
              <div class="col-sm-8">
                <textarea class="summernote" required="" name="description" id="description"></textarea>
              </div>
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
            <th>Description</th>
            <th>Download Attached</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($client_objectivies)) { ?>
            <?php $i=1; foreach ($client_objectivies as $key => $aw) { ?>
              <tr>
                <td><?php echo $i++ ?></td>
                <td><?php echo $aw->objectivies_name ?></td>
                <td><?php echo $aw->objectivies_description ?></td>
                <td><a href="<?php echo site_url('client_controller/download_client_objectivies/'.$aw->id) ?>">Download</a></td>
                <td><a onclick="return confirm('Are you going to  delete this document. Are you sure? ')" class="btn btn-danger" href="<?php echo site_url('client_controller/delete_objectivies/'.$aw->id) ?>">Delete</a></td>
              </tr>
            <?php } ?>
          <?php }else{
            echo " <tr><th colspan='4' class='text-center'><h3>No Objectivies Found</h3></th></tr>";
          } ?>
        </tbody>
      </table>
    </div>

</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/plugins/summernote/summernote.js"></script>
