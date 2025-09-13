<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Product Add</h3>         
    </div>

    <form enctype="multipart/form-data" method="post" id="myshop_product_form" action="" class="form-horizontal" data-parsley-validate >
        <div class="panel-body table-responsive">
            <input type="hidden" id="shop_type" name="shop_type" value="<?php echo $shop_type ?>" >
            <input type="hidden" id="category_id" name="category_id" value="<?php echo $category_id ?>" >
            <div class="col-md-6 col-md-offset-2">
                <?php
                $display = '';
                $required = '';
                if ($shop_type == 'local' || $shop_type == 'wholesale' || $shop_type == 'brands') {
                    $display = 'style="display:none"';
                    $required = 'required';
                }elseif($shop_type == 'shop' || $shop_type == 'echoshop'){
                    $required = 'required';

                } 
                ?>
                <?php if ($shop_type == 'shop' || $shop_type == 'echoshop') { ?>
                    <div class="form-group" <?php echo $display ?>>
                        <label class="control-label col-sm-4">Category <font color="red" >*</font></label>
                        <div class="col-md-8">
                           <select class="form-control" required name="category" id="category" >
                            <option value="">Select Category</option>
                            <?php foreach ($shop_category as $key => $val) { ?>
                               <option value="<?php echo $val->id ?>"><?php echo $val->category ?></option>
                            <?php } ?>
                           </select>
                        </div>
                    </div>
                <?php } ?>
               
                <?php if ($shop_type == 'local' || $shop_type == 'wholesale' || $shop_type == 'brands') { ?>
                    <input type="hidden" id="category_id" name="category" value="<?php echo $category_id ?>" >
                <?php } ?>

                <script type="text/javascript">
                    $(document).ready(function(){
                        $('#subCatDisplay').hide();
                        var shop_type = '<?php echo $shop_type ?>';
                        if (shop_type == 'local' || shop_type == 'wholesale' || shop_type == 'brands') {
                            ajax_subcategory_display();
                            $('#subCatDisplay').show();
                        }
                    });

                    $('#category').on('change',function(){
                        var category = $('#category').val();
                        var shop_type = $('#shop_type').val();
                        $('#subCatDisplay').hide();
                          $.ajax({
                          url: '<?php echo site_url('client_controller/get_sub_categorybyid') ?>',
                          type: 'post',
                          data: {'category':category,'shop_type':shop_type},
                          success:function(data){
                            var resData = JSON.parse(data);
                            var suboptions = '';
                            if (resData !='') {
                                suboptions +='<option value="">Select Sub Category</option>';
                                for (var i = 0; i < resData.length; i++) {
                                    suboptions +='<option value="'+resData[i].id+'">'+resData[i].sub_category+'</option>';
                                }
                                $('#sub_cat').html(suboptions);
                                $('#subCatDisplay').show();
                            }
                          }
                        });
                    });

                    function ajax_subcategory_display() {
                        var category = $('#category_id').val();
                        var shop_type = $('#shop_type').val();
                        $.ajax({
                          url: '<?php echo site_url('client_controller/get_sub_categorybyid') ?>',
                          type: 'post',
                          data: {'category':category,'shop_type':shop_type},
                          success:function(data){
                            var resData = JSON.parse(data);
                            var suboptions = '';
                            if (resData !='') {
                                suboptions +='<option value="">Select Sub Category</option>';
                                for (var i = 0; i < resData.length; i++) {
                                    suboptions +='<option value="'+resData[i].id+'">'+resData[i].sub_category+'</option>';
                                }
                                $('#sub_cat').html(suboptions);
                                $('#subCatDisplay').show();
                            }
                          }
                        });
                    }
                   
                </script>
                <div class="form-group" id="subCatDisplay" style="display: none;">
                    <label class="control-label col-sm-4">Sub Category</label>
                    <div class="col-md-8">
                       <select class="form-control" <?php echo $required ?> name="sub_category" id="sub_cat">
                       </select>
                    </div>
                </div>
                <script type="text/javascript">
                    $('#sub_cat').on('change',function(){
                        var sub_cat = $('#sub_cat').val();
                        var category = $('#category').val();
                        var shop_type =$('#shop_type').val();
                         $('#subSubCatDisplay').hide();
                          $.ajax({
                          url: '<?php echo site_url('client_controller/get_sub_sub_categorybyid') ?>',
                          type: 'post',
                          data: {'category':category,'sub_cat':sub_cat,'shop_type':shop_type},
                          success:function(data){
                            var resData = JSON.parse(data);
                            var suboptions = '';
                            if (resData !='') {
                                suboptions +='<option value="">Select Sub Sub Category</option>';
                                for (var i = 0; i < resData.length; i++) {
                                    suboptions +='<option value="'+resData[i].id+'">'+resData[i].sub_sub_category+'</option>';
                                }
                                $('#sub_sub_cat').html(suboptions);
                                $('#subSubCatDisplay').show();
                            }
                          }
                        });
                   });
                </script>
                <div class="form-group" id="subSubCatDisplay" style="display: none;">
                    <label class="control-label col-sm-4">Sub Sub Category</label>
                    <div class="col-md-8">
                       <select class="form-control" name="sub_sub_category" id="sub_sub_cat">
                       </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4">Product Name <font color="red" >*</font></label>
                    <div class="col-md-8">
                        <input type="text" name="product_name" required class="form-control" id="product_name" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Product Tag line</label>
                    <div class="col-md-8">
                        <input type="text" name="product_tag_line" class="form-control" id="product_tag_line" >
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4">Product MRP <font color="red" >*</font></label>
                    <div class="col-md-8">
                        <input type="text" name="product_mrp" required class="form-control" id="product_mrp" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4">Product My Price <font color="red" >*</font></label>
                    <div class="col-md-8">
                        <input type="text" name="product_my_price" required class="form-control" id="product_my_price" >
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4">Details <font color="red" >*</font></label>
                    <div class="col-md-8">
                        <textarea class="form-control" required name="product_details"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4">Specifications</label>
                    <div class="col-md-8">
                         <textarea class="summernote" rows="300" name="specifications" id="specifications"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4">Features</label>
                    <div class="col-md-8">
                         <textarea class="summernote" rows="300" name="features"  id="specifications"></textarea>
                    </div>
                </div>

                <div class="form-group" id="upload-Img">
                    <label class="control-label col-md-4">Product Main Image</label>
                    <div class="col-sm-6">
                     <div class="form-group mt-4" id="upload-video" style="height: 98px;">
                        <div class="col-sm-12">
                          <div class="input--file" style="height: 80px; float: left;">
                            <span id="hidden_fileupaload1">
                                <svg onclick='$("#fileupload1").click()' xmlns="http://www.w3.org/2000/svg"  width="80" height="80" viewBox="0 0 24 24">
                                  <circle cx="12" cy="12" r="3.2"/>
                                  <path d="M9 2l-1.83 2h-3.17c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-12c0-1.1-.9-2-2-2h-3.17l-1.83-2h-6zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                                  <path d="M0 0h24v24h-24z" fill="none"/>
                                </svg>
                            </span>
                            <input style="display:none" required type="file" data-parsley-required-message="Please Upload Logo" id="fileupload1" />
                          </div>
                        </div>
                        <div id="logoDispaly1" style="position: absolute;top: 0px;left: 15px; text-align: center; display: none; ">
                            <img style="width: 80px;height: 80px;" src="" id="img_photo_preview1">
                        </div>
                        <div id="loader-visible1" style="position: relative;top: -80px;right: 0px; text-align: center;display: none; ">
                          <img style="width: 80px;height: 80px" src="<?php echo base_url().'assets/loading-circle-gif.gif' ?>">
                        </div>
                        <input type="hidden" name="img_path[]" id="img_path1">
                      </div>
                    </div>
                </div>
                
                
                <div class="form-group" id="upload-Img">
                    <label class="control-label col-md-4">Product Sub Image</label>
                    <div class="col-sm-8">
                     <div class="form-group col-sm-3" id="upload-video" style="height: 98px; margin-right: 0;">
                          <div class="col-sm-12">
                          <div class="input--file" style="height: 80px; float: left;">
                            <span id="hidden_fileupaload2">
                                <svg xmlns="http://www.w3.org/2000/svg" onclick='$("#fileupload2").click()' style="width: 80px;" viewBox="0 0 24 24">
                                  <circle cx="12" cy="12" r="3.2"/>
                                  <path d="M9 2l-1.83 2h-3.17c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-12c0-1.1-.9-2-2-2h-3.17l-1.83-2h-6zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                                  <path d="M0 0h24v24h-24z" fill="none"/>
                                </svg>
                            </span>
                            <input style="display:none" type="file" data-parsley-required-message="Please Upload Logo" id="fileupload2" />
                          </div>
                        </div>
                        <div id="logoDispaly2" style="position: absolute;top: 0px;left: 26px; text-align: center; display: none; ">
                            <img style="width: 80px;height: 80px;" src="" id="img_photo_preview2">
                        </div>
                        <input type="hidden" name="img_path[]" id="img_path2">
                      </div>

                      <div class="form-group col-sm-3" id="upload-video" style="height: 98px;margin-right: 0;margin-left: 0;">
                          <div class="col-sm-12">
                          <div class="input--file" style="height: 80px; float: left;">
                            <span id="hidden_fileupaload3">
                                <svg xmlns="http://www.w3.org/2000/svg" onclick='$("#fileupload3").click()' style="width: 80px;" viewBox="0 0 24 24">
                                  <circle cx="12" cy="12" r="3.2"/>
                                  <path d="M9 2l-1.83 2h-3.17c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-12c0-1.1-.9-2-2-2h-3.17l-1.83-2h-6zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                                  <path d="M0 0h24v24h-24z" fill="none"/>
                                </svg>
                            </span>
                            <input style="display:none" type="file" data-parsley-required-message="Please Upload Logo" id="fileupload3" />
                          </div>
                        </div>
                        <div id="logoDispaly3" style="position: absolute;top: 0px;left: 26px; text-align: center; display: none; ">
                            <img style="width: 80px;height: 80px;" src="" id="img_photo_preview3">
                        </div>
                        <input type="hidden" name="img_path[]" id="img_path3">
                      </div>

                      <div class="form-group col-sm-3" id="upload-video" style="height: 98px;margin-right: 0;margin-left: 0;">
                          <div class="col-sm-12">
                          <div class="input--file" style="height: 80px; float: left;">
                            <span id="hidden_fileupaload4">
                                <svg xmlns="http://www.w3.org/2000/svg" onclick='$("#fileupload4").click()' style="width: 80px;" viewBox="0 0 24 24">
                                  <circle cx="12" cy="12" r="3.2"/>
                                  <path d="M9 2l-1.83 2h-3.17c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-12c0-1.1-.9-2-2-2h-3.17l-1.83-2h-6zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                                  <path d="M0 0h24v24h-24z" fill="none"/>
                                </svg>
                            </span>
                            <input style="display:none" type="file" data-parsley-required-message="Please Upload Logo" id="fileupload4" />
                          </div>
                        </div>
                        <div id="logoDispaly4" style="position: absolute;top: 0px;left: 26px; text-align: center; display: none; ">
                            <img style="width: 80px;height: 80px;" src="" id="img_photo_preview4">
                        </div>
                        <input type="hidden" name="img_path[]" id="img_path4">
                      </div>

                      <div class="form-group col-sm-3" id="upload-video" style="height: 98px;margin-right: 0;margin-left: 0;">
                          <div class="col-sm-12">
                          <div class="input--file" style="height: 80px; float: left;">
                            <span id="hidden_fileupaload5">
                                <svg  xmlns="http://www.w3.org/2000/svg" onclick='$("#fileupload5").click()' style="width: 80px;" viewBox="0 0 24 24">
                                  <circle cx="12" cy="12" r="3.2"/>
                                  <path d="M9 2l-1.83 2h-3.17c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-12c0-1.1-.9-2-2-2h-3.17l-1.83-2h-6zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                                  <path d="M0 0h24v24h-24z" fill="none"/>
                                </svg>
                            </span>
                            <input style="display:none" type="file" data-parsley-required-message="Please Upload Logo" id="fileupload5" />
                          </div>
                        </div>
                        <div id="logoDispaly5" style="position: absolute;top: 0px;left: 26px; text-align: center; display: none; ">
                            <img style="width: 80px;height: 80px;" src="" id="img_photo_preview5">
                        </div>
                        <input type="hidden" name="img_path[]" id="img_path5">
                      </div>

                    </div>
                </div>

          </div>
        </div>
        <div class="panel-footer">
            <center>
               <input type="button" value="Upload" onclick="insert_myshop_product_data()" id="up-btn" class="btn btn-primary">
               <a class="btn btn-danger" href="<?php echo site_url('client_controller/view_created_client_shop') ?>">Cancel / Back</a>
            </center>
        </div>
    </form>
</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/plugins/summernote/summernote.js"></script>

<script type="text/javascript">
    $("#fileupload1").change(function () {
        $('#hidden_fileupaload1').hide();
        $('#logoDispaly1').css('display','block');
        $('#img_photo_preview1').css('opacity','0.6');
        var src = $(this).val();
        if(src){
            readURL(this,1);
        } else{
            this.value = null;
        }
        var file_data = $('#fileupload1').prop('files')[0];
        var form_data = new FormData();
        form_data.append('product_file', file_data);
        // $('#loader-visible1').css('display','block');
        ajaxupload_productfile(form_data, 1);
    });

    $("#fileupload2").change(function () {
        $('#hidden_fileupaload2').hide();
        $('#logoDispaly2').css('display','block');
        $('#img_photo_preview2').css('opacity','0.6');
        var src = $(this).val();
        if(src){
            readURL(this,2);
        } else{
            this.value = null;
        }
        var file_data = $('#fileupload2').prop('files')[0];
        var form_data = new FormData();
        form_data.append('product_file', file_data);
        // $('#loader-visible1').css('display','block');
        ajaxupload_productfile(form_data, 2);
    });

    $("#fileupload3").change(function () {
        $('#hidden_fileupaload3').hide();
        $('#logoDispaly3').css('display','block');
        $('#img_photo_preview3').css('opacity','0.6');
        var src = $(this).val();
        if(src){
            readURL(this,3);
        } else{
            this.value = null;
        }
        var file_data = $('#fileupload3').prop('files')[0];
        var form_data = new FormData();
        form_data.append('product_file', file_data);
        // $('#loader-visible1').css('display','block');
        ajaxupload_productfile(form_data, 3);
    });

    $("#fileupload4").change(function () {
        $('#hidden_fileupaload4').hide();
        $('#logoDispaly4').css('display','block');
        $('#img_photo_preview4').css('opacity','0.6');
        var src = $(this).val();
        if(src){
            readURL(this,4);
        } else{
            this.value = null;
        }
        var file_data = $('#fileupload4').prop('files')[0];
        var form_data = new FormData();
        form_data.append('product_file', file_data);
        // $('#loader-visible1').css('display','block');
        ajaxupload_productfile(form_data, 4);
    });

    $("#fileupload5").change(function () {
        $('#hidden_fileupaload5').hide();
        $('#logoDispaly5').css('display','block');
        $('#img_photo_preview5').css('opacity','0.6');
        var src = $(this).val();
        if(src){
            readURL(this,5);
        } else{
            this.value = null;
        }
        var file_data = $('#fileupload5').prop('files')[0];
        var form_data = new FormData();
        form_data.append('product_file', file_data);
        // $('#loader-visible1').css('display','block');
        ajaxupload_productfile(form_data, 5);
    });

    function ajaxupload_productfile(form_data, i) {

        $('#up-btn').prop('disabled',true);
        $.ajax({
          url: '<?php echo site_url('mytv/upload_myshop_product') ?>',
          type: 'post',
          data: form_data,
          cache: false,
          contentType: false,
          processData: false,
          success:function(data){
            var resData = JSON.parse(data);
            console.log(resData);
            if (resData.status == 'success') {
                $('#logoDispaly'+i).css('display','block');
                $('#img_path'+i).val(resData.file_name);
                $('#img_photo_preview'+i).css('opacity','1');
                $('#up-btn').prop('disabled',false);
            }
            $('#hidden_fileupaload'+i).show();
            // $('#loader-visible1').css('display','none');
          }
        });
    }
    function readURL(input,i) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#img_photo_preview'+i).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function insert_myshop_product_data() {
        var shopType = $('#shop_type').val();
        var $form = $('#myshop_product_form');
        if ($form.parsley().validate()){
            var form = $('#myshop_product_form')[0];
            var form_data = new FormData(form);
            $('#up-btn').prop('disabled',true).val('Please wait..');
            $.ajax({
              url: '<?php echo site_url('client_controller/insert_myshop_product_form_data') ?>',
              type: 'post',
              data: form_data,
              cache: false,
              contentType: false,
              processData: false,
              success:function(data){
                var resData = JSON.parse(data);
                if (resData) {
                    $('#up-btn').prop('disabled',false).val('Upload');
                }else{
                    console.log(resData);
                }
                window.location.href='<?php echo site_url('client_controller/myshop_product/') ?>'+shopType;
                
                
              }
            });
        }
    }
</script>
