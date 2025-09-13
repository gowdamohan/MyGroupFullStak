<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Client Document</h3>         
    </div>

    <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/upload_client_document') ?>"  id="students" action="" class="form-horizontal" data-parsley-validate >
        <div class="panel-body table-responsive">
          <div class="col-md-6 col-md-offset-2">

            <div class="form-group">
              <label class="control-label col-sm-4">Document Name</label>
              <div class="col-md-8">
                <input type="text" name="document_name" class="form-control" id="document_name" >
              </div>
            </div>

              <div class="form-group">
                <label class="control-label col-sm-4" for="no_of_days">Upload Document</label>
                <div class="col-sm-8">
                   <input type="file" required="" name="client_document" class="form-control" id="image-upload">
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
            <th>Document Name</th>
            <th>Download Attached Document</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($client_document)) { ?>
            <?php $i=1; foreach ($client_document as $key => $doc) { ?>
              <tr>
                <td><?php echo $i++ ?></td>
                <td><?php echo $doc->document_name ?></td>
                <td><a href="<?php echo site_url('client_controller/download_client_doc/'.$doc->id) ?>">Download</a></td>
                <td><a onclick="return confirm('Are you going to  delete this document. Are you sure? ')" class="btn btn-danger" href="<?php echo site_url('client_controller/delete_doc/'.$doc->id) ?>">Delete</a></td>
              </tr>
            <?php } ?>
          <?php }else{
            echo " <tr><th colspan='4' class='text-center'><h3>Attached Document Not Found</h3></th></tr>";
          } ?>
        </tbody>
      </table>
    </div>

</div>