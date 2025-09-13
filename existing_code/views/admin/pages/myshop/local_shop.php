<?php if (!empty($local_shop)) { ?>
	
	<div class="panel-body">
		<p>Shop Name : <?php echo $local_shop->name; ?></p>
		<p>Shop Logo :<img class="img-circle" style="height:40px; width: 40px" src="<?php echo $this->filemanager->getFilePath($local_shop->shop_logo) ?>"> </p>
		<p>Shop Address :<?php echo $local_shop->address; ?> </p>
		
		<a class="btn btn-info" href="<?php echo site_url('client_controller/create_shop_categoryby_client/'.$shop_type.'/'.$local_shop->id.'/'.$local_shop->category_id) ?>">Create Shop Category</a>

	<script type="text/javascript">
		function submit_local_shop_category() {
			var local_shop_category = $('#local_shop_category').val();
			$.ajax({
		        url: '<?php echo site_url('client_controller/insert_local_shop_category'); ?>',
		        type: "post",
		        data:{'local_shop_category':local_shop_category},
		        success: function (data) {
		            if (data.length=='1') {
		              location.reload();
		            }else{
		              location.reload();
		            }
		        },
		      	error: function (err) {
		          console.log(err);
				}
			});
		}
	</script>
	<div class="panel-body">
			<ul class="panel-controls">
				<a class="btn btn-info" href="<?php echo site_url('client_controller/add_myshop_product/'.$shop_type.'/'.$local_shop->category_id) ?>">Add Product</a>
			</ul>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>Category</th>
						<th>Product Name</th>
						<th>My Price</th>
						<th>MRP Price</th>
						<th>Status</th>
						<th>View</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php $i=1; foreach ($product_details as $key => $val) { ?>
						<tr>
							<td><?php echo $i++; ?></td>
							<th><?php echo $val->category_name ?></th>
							<th><?php echo $val->product_name ?></th>
							<th><?php echo $val->product_my_price ?></th>
							<th><?php echo $val->product_mrp ?></th>

							<th>
								<span style="float: right;" >
									<?php if($val->status == 1){ ?>
		                      	<label class="switch">
		                        	<input type="checkbox" onclick="my_shop_product_switch_check('<?php echo $val->id ?>','0')" checked >
		                        	<span></span>
		                        </label>
		                      <?php }else{ ?>
		                          <label class="switch">
		                              <input type="checkbox" onclick="my_shop_product_switch_check('<?php echo $val->id ?>','1')" >
		                              <span></span>
		                          </label>
		                    	<?php } ?>
		                	</span>
							</th>
							<th><a href="<?php echo site_url('client_controller/my_shop_product_view/'.$val->id) ?>">View</a> </th>
							<th></th>
						</tr>
					<?php } ?>
					
				</tbody>
			</table>
		</div>
	</div>
<?php }else{

	echo "<div class='panel-body'><h3>Create Shop then add product details.</h3></div>";
} ?>


<script type="text/javascript">
		function my_shop_product_switch_check(stngId, value) {
        	$.ajax({
            url: '<?php echo site_url('client_controller/my_shop_product_status_udpate'); ?>',
            type: "post",
            data:{'stngId':stngId, 'value':value},
            success: function (data) {
                if (data.length=='1') {
                  location.reload();
                }else{
                  location.reload();
                }
            },
          	error: function (err) {
              console.log(err);
        		}
    		});
	  }
</script>

<style type="text/css">
	.widget.widget-item-icon .widget-item-left, .widget.widget-item-icon .widget-item-right{
		padding: 0px 0px;
	}
	.widget{
	    min-height: 0px;
	}
	.widget .widget-item-left .fa, .widget .widget-item-right .fa, .widget .widget-item-left .glyphicon, .widget .widget-item-right .glyphicon{
		font-size: 34px;
	}
	.widget .widget-title{
	    font-size: 15px;
	}
	a[disabled] {
	    pointer-events: none;
	}
	.disabled{
		opacity: 0.6;
	}
</style>