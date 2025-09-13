<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Group Account</h3>         
    </div>

    <form enctype="multipart/form-data" method="post" id="students" action="<?php echo site_url('admin_controller/register_group_creation') ?>" class="form-horizontal" data-parsley-validate >
        <div class="panel-body table-responsive">
            <div class="col-md-6 col-md-offset-2">
                <div class="form-group">
                    <label class="control-label col-sm-4">Group Name</label>
                    <div class="col-md-8">
                        <select class="form-control" name="group_name" id="gropuName">
                            <option value="">Select Group</option>
                            <?php foreach ($groups as $key => $val) { ?>
                                <option value="<?php echo $val->id ?>"><?php echo $val->name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4">User Name</label>
                    <div class="col-md-8">
                        <input type="text" name="group_username" class="form-control" id="group_username">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4">Password</label>
                    <div class="col-md-8">
                        <input type="text" name="group_password" class="form-control" id="group_password">
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <center>
               <input type="submit" value="Submit"  id="up-btn" class="btn btn-primary">
               <a class="btn btn-danger" href="<?php echo site_url('dashboard') ?>">Cancel / Back</a>
            </center>
        </div>
    </form>
</div>
