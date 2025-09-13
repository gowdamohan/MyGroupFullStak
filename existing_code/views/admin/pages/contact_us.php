<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Contact Address Add</h3>         
    </div>

    <?php if (!empty($contact)) { ?>
       <form enctype="multipart/form-data" method="post" id="students" action="<?php echo site_url('admin_controller/update_contact_by_id/'.$contact->id) ?>" class="form-horizontal" data-parsley-validate >
        <div class="panel-body table-responsive">
           <div class="col-md-6 col-md-offset-2">

               <div class="form-group" id="textSummernote">
                  <label class="control-label col-sm-4" for="summernote">Address</label>
                  <div class="col-sm-8">
                    <textarea class="summernote" name="address" id="summernote"><?php echo $contact->address ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Email ID </label>
                    <div class="col-md-8">
                        <input type="text" name="emaiil_id" class="form-control" value="<?php echo $contact->email ?>" id="emaiil_id" >
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4">Contact Number </label>
                    <div class="col-md-8">
                        <input type="text" name="contact_number"  value="<?php echo $contact->contact_number ?>" class="form-control" id="contact_number" >
                    </div>
                </div>

          </div>
        </div>
        <div class="panel-footer">
            <center>
               <input type="submit" value="Update"  id="up-btn" class="btn btn-primary">
               <a class="btn btn-danger" href="<?php echo site_url('admin_controller/contact_us') ?>">Cancel / Back</a>
            </center>
        </div>
        </form>
    <?php }else{ ?>
        <form enctype="multipart/form-data" method="post" id="students" action="<?php echo site_url('admin_controller/update_contact') ?>" class="form-horizontal" data-parsley-validate >
        <div class="panel-body table-responsive">
           <div class="col-md-6 col-md-offset-2">

               <div class="form-group" id="textSummernote">
                  <label class="control-label col-sm-4" for="summernote">Address</label>
                  <div class="col-sm-8">
                    <textarea class="summernote"  name="address" id="summernote"></textarea>
                  </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Email ID </label>
                    <div class="col-md-8">
                        <input type="text" name="emaiil_id" class="form-control" id="emaiil_id" >
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4">Contact Number </label>
                    <div class="col-md-8">
                        <input type="text" name="contact_number" class="form-control" id="contact_number" >
                    </div>
                </div>

          </div>
        </div>
        <div class="panel-footer">
            <center>
               <input type="submit" value="Submit"  id="up-btn" class="btn btn-primary">
               <a class="btn btn-danger" href="<?php echo site_url('admin_controller/contact_us') ?>">Cancel / Back</a>
            </center>
        </div>
    </form>
    <?php } ?>
    
</div>


<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/plugins/summernote/summernote.js"></script>

