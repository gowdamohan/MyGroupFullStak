<ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
    <li><a href="<?php echo site_url('client_controller/client_god_gallery');?>">Galleries Dashboard</a></li>
    <li>Galleries</li>
</ul>
<hr>
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <form enctype="multipart/form-data" id="gallery-form" action="<?php echo site_url('client_controller/god_gallery_created'); ?>"
                class="form-horizontal" data-parsley-validate method="post">
                <div class="panel panel-default new-panel-style_3">
                    <div class="panel-heading new-panel-heading">
                        <h3 class="panel-title"><strong>Create a New Gallery</strong></h3>
                        <ul class="panel-controls" style="float:right">
                            <li>
                                <a  href="<?php echo site_url('client_controller/client_god_gallery');?>" data-placement="top" data-toggle="tooltip" data-original-title="Back" class="control-primary"><span class="fa fa-mail-reply"></span></a>
                            </li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <p>
                        <strong>Notes: </strong><br>
                        1. To Add New Gallery, please fill the form below.<br>
                        </p><br><br>
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="gallery_title">Title<font color="red">*</font></label>
                                            <div class="col-md-8"> 
                                                <input type='hidden' name='created_by' value='<?php echo $AvatarId; ?>'>
                                                <input placeholder="Enter Gallery Album" name="gallery_name"
                                                    type="text" class="form-control input-md" data-parsley-error-message="Cannot be empty." required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Description</label>
                                            <div class="col-md-10">
                                                <textarea name="gallery_description" type="text" class="form-control input-md"
                                                    placeholder="Enter Description"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <center>
                    <input type="button" onclick="gallery_update()" class="btn btn-success"  value="Create">
                    <?php //if ($traverse_to == '0') { ?>
                    <a class="btn btn-danger" href="<?php echo site_url('client_controller/client_god_gallery'); ?>">Cancel</a>
                    <?php //}else{ ?>
                    <!-- <a class="btn btn-danger" href="<?php //echo site_url('fees/fee_wpl/select_fee/'.$edit_data['student']->id) ?>">Cancel</a> -->
                    <?php //} ?>
                </center>    
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

    function gallery_update(){
        bootbox.dialog({
            title : "Note",  
            message: "This will create a new <b>'Gallery Album'</b>.<br><b>Next steps:</b> <br>1. Add photos from the index page.",
            buttons: {
                ok: {
                    label: "OK",
                    className: 'btn-success',
                    callback: function(){
                        $('#gallery-form').submit();
                    }
                }
            }
        });
    }
    
</script>
