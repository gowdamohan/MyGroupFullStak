<div class="col-md-12 pt-2 pb-5">
    <div class="row" style="margin: 0px;">
      <div class="col-md-6">
        <h3 class="card-title panel_title_new_style_staff">
            <a class="back_anchor" href="<?php echo site_url('home/gallery/'.$groupname) ?>" class="control-primary">
                <span class="fa fa-arrow-left"></span>
                <strong>Back to Gallery</strong>&nbsp;
            </a>
        
        </h3>
      </div>
    </div>
<?php if (!empty($gallery_info)) { ?>
   <div style="margin-top: 8px;border-radius: 8px;">
        <h6>
            <center><?php echo $gallery_info->gallery_name;?> </center>
        </h6>
    
        <div class="gallery" style="padding-left:7px;">
            <div id="images_new">
                <?php foreach($image_info as $row){ ?>
                <a class="gallery-item" href="<?php echo $this->filemanager->getFilePath($row['image_name']) ?>" title="" data-gallery="">
                    <div class="image">
                        <img class="rounded mx-auto" style="width: 100%;"  src="<?php echo $this->filemanager->getFilePath($row['image_name']) ?>">
                    </div>
                </a>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
   

</div>

<style type="text/css">
.gallery .gallery-item {
    height: 320px !important;
        padding: 2px ;
}
.gallery .gallery-item .image {
    border-radius: 4px;
}

.gallery .gallery-item .image:after, .gallery .gallery-item .image:before {

border:none;

}
</style>