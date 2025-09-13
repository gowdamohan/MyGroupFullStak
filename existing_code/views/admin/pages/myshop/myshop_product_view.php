<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Product View</h3>         
    </div>
        <div class="panel-body table-responsive">
            <div class="col-md-6 col-md-offset-2">
                <div class="form-group">
                    <label class="control-label col-sm-4">Category</label>
                    <div class="col-md-8">
                        <p><?php echo $view_product->category_name ?></p>
                      
                    </div>
                </div>
              
                <div class="form-group" id="subCatDisplay" style="display: none;">
                    <label class="control-label col-sm-4">Sub Category</label>
                    <div class="col-md-8">
                        <p><?php echo $view_product->subcatname ?></p>
                    </div>
                </div>
               
                <div class="form-group" id="subSubCatDisplay" style="display: none;">
                    <label class="control-label col-sm-4">Sub Sub Category</label>
                    <div class="col-md-8">
                       <p><?php echo $view_product->subsubcatname ?></p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4">Product Name</label>
                    <div class="col-md-8">
                       <p><?php echo $view_product->product_name ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Product Tag line</label>
                    <div class="col-md-8">
                       <p><?php echo $view_product->product_tag_line ?></p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4">Product MRP</label>
                    <div class="col-md-8">
                        <p><?php echo $view_product->product_mrp ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Product My Price</label>
                    <div class="col-md-8">
                        <p><?php echo $view_product->product_my_price ?></p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4">Details</label>
                    <div class="col-md-8">
                        <p><?php echo $view_product->product_details ?></p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4">Specifications</label>
                    <div class="col-md-8">
                        <p><?php echo $view_product->specifications ?></p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4">Features</label>
                    <div class="col-md-8">
                        <p><?php echo $view_product->features ?></p>
                    </div>
                </div>

                <div class="form-group" id="upload-Img">
                    <label class="control-label col-md-4"></label>
                    <div class="col-sm-6">
                     <div class="form-group mt-4" id="upload-video" style="height: 98px;">
                        <div id="logoDispaly1" style="position: absolute;top: 0px;left: 15px; text-align: center;">
                            <img style="width: 80px;height: 80px;" src="<?php echo $this->filemanager->getFilePath($view_product->image[0]->image) ?>">
                        </div>
                      </div>
                    </div>
                </div>
                
                
                <div class="form-group" id="upload-Img">
                    <label class="control-label col-md-4"></label>
                    <div class="col-sm-8">
                    <?php foreach ($view_product->image as $key => $val) {
                        if ($key > 0) { ?>
                            <div class="form-group col-sm-3" id="upload-video" style="height: 98px; margin-right: 0;">
                                <div id="logoDispaly2" style="position: absolute;top: 0px;left: 26px; text-align: center;">
                                    <img style="width: 80px;height: 80px;" src="<?php echo $this->filemanager->getFilePath($val->image) ?>" id="img_photo_preview2">
                                </div>
                            </div> 
                        <?php }
                    } ?>

                    </div>
                </div>

          </div>
        </div>
        <div class="panel-footer">
            <center>
               <a class="btn btn-danger" href="<?php echo site_url('client_controller/myshop_product/'.$view_product->shop_type) ?>">Cancel / Back</a>
            </center>
        </div>
    </form>
</div>