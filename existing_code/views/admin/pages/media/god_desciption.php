<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li><?php echo $type ?></li>
</ul>

<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title"><?php echo $type ?></h3>         
    </div>

    <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/insert_client_god_desciption') ?>"  id="students" action="" class="form-horizontal" data-parsley-validate >
      <input type="hidden" value="<?php echo $type ?>" name="page_type">
      <input type="hidden" value="<?php echo $urlType ?>" name="page_type_url">
        <div class="panel-body table-responsive">
          <div class="col-md-6 col-md-offset-2">
            <div class="form-group" id="textSummernote">
              <label class="control-label col-sm-4" for="summernote">Description</label>
              <div class="col-sm-8">
                <textarea class="summernote" required="" name="description" id="description">
                  <?php
                  if (!empty($god_desciption)) {
                    echo $god_desciption->description;
                  } ?>

                </textarea>
              </div>
            </div>
            <?php 
              if ($type == 'Accomadation' || $type == 'Donation Details' || $type == 'Fee Details') { ?>
                <div class="form-group">
                  <label class="control-label col-sm-4">URL</label>
                  <div class="col-md-8">
                     <?php
                     $url = '';
                      if (!empty($god_desciption)) {
                        $url = $god_desciption->url;
                      } ?>
                    <input type="text" name="url" value="<?php echo $url ?>" class="form-control" id="url" >
                  </div>
                </div>
              <?php }
            ?>
          </div>
        </div>
        <div class="panel-footer">
          <center>
            <input type="submit" value="Upload" id="up-btn" class="btn btn-primary">
          </center>
        </div>
    </form>

</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/plugins/summernote/summernote.js"></script>
