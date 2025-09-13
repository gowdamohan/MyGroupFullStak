<?php 
    if ($this->mobile_detect->isMobile()) { ?>
       <section id="sec-features" class="sec-features pt-5 pb-5" style="margin-top:25%">
    <?php }else{ ?>
       <section id="sec-features" class="sec-features pt-5 pb-5">
    <?php }
?>
    <div class="container">
        <div class="row">
            <div class="panel-body">
                <div class="col-md-6 col-md-offset-2">
                    <form enctype="multipart/form-data" method="post" id="apply-now" action="<?php echo site_url('home/apply_now_insert') ?>" class="form-horizontal" data-parsley-validate>
                        <input type="hidden" name="group_name" value="<?php echo $groupname ?>">
                        <div class="form-group">
                            <select class="form-control" id="applyNow" name="apply_for">
                                <option value="apply_for_franchise">Apply For Franchise</option>
                                <option value="apply_for_media">Apply For Media</option>
                                <option value="apply_for_developer">Apply For Developer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="file" class="form-control"  required="" name="upload_document" id="upload_document">
                        </div>
                       
                        <button type="submit" class="btn btn-warning btn-lg btn-block">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>