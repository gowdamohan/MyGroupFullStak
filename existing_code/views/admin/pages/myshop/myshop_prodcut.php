<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>My Shop</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">My Shop</h3>
   </div>
	<div class="panel-body">
   	<div class="row">
			<div class="col-md-2">
				<a href="<?php echo site_url('client_controller/myshop_product/'.'shop') ?>">
			    	<div class="widget widget-warning widget-item-icon">                            
			        <div class="widget-data-left">
			            <div class="widget-title">Shop</div>
			        </div>                                     
			    	</div>
		    	</a>
			</div>
			<div class="col-md-2">
				<a href="<?php echo site_url('client_controller/myshop_product/'.'local') ?>">
			    <div class="widget widget-warning widget-item-icon">                           
			        <div class="widget-data-left">
			            <div class="widget-title">Local</div>
			        </div>                                     
			    </div>
				</a>
			</div>

			<div class="col-md-2">
				<a href="<?php echo site_url('client_controller/myshop_product/'.'wholesale') ?>">
			    <div class="widget widget-warning widget-item-icon">                            
			        <div class="widget-data-left">
			            <div class="widget-title">Wholesale</div>
			        </div>                                     
			    </div>
				</a>
			</div>
			<div class="col-md-2">
				<a href="<?php echo site_url('client_controller/myshop_product/'.'brands') ?>">
			    <div class="widget widget-warning widget-item-icon ">                       
			        <div class="widget-data-left">
			            <div class="widget-title">Brands</div>
			        </div>                                     
			    </div>
				</a>
			</div>
			<div class="col-md-2">
				<a href="<?php echo site_url('client_controller/myshop_product/'.'echoshop') ?>">
				    <div class="widget widget-warning widget-item-icon">                          
				        <div class="widget-data-left">
				            <div class="widget-title">Echoshop</div>
				        </div>                                     
				    </div>
				</a>
			</div>
		</div>
   </div>

   	<?php
   	if ($shop_type == 'local' || $shop_type == 'wholesale' || $shop_type == 'brands') {
   		$this->load->view('admin/pages/myshop/local_shop');
   	}else{ ?>
   		<div class="panel-body">
			<ul class="panel-controls">
				<a class="btn btn-info" href="<?php echo site_url('client_controller/add_myshop_product/'.$shop_type) ?>">Add Product</a>
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
   	<?php }
    ?>
	
</div>

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