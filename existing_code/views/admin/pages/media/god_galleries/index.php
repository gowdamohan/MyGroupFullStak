<ul class="breadcrumb">
	<li><a href="#">Dashboard</a></li>
</ul>
<hr>
<div class="col-md-12">
	<div class="panel panel-default new-panel-style_3">
		<div class="panel-heading new-panel-heading">
			<h3 class="panel-title"><strong>Galleries</strong></h3>
			<ul class="panel-controls" style="float:right">
				<li>
					<a href="<?php echo site_url('client_controller/god_create_gallery');?>" data-placement="top" data-toggle="tooltip" data-original-title="Create a new Gallery" class="control-primary"><span class="fa fa-plus"></span></a>&nbsp; &nbsp;
				</li>
			</ul>
		</div>
		<div class="panel-body table-responsive" style="padding:8px 8px;">
			<table class='table table-bordered datatable' id='list_tab'>
				<thead>
					<tr>
						<th>#</th>
						<th width="17%">Album</th>
						<th width="24%">Operations</th>
					</tr>
				</thead>
				<tbody>  
					<?php $i=1;
						//echo '<pre>'; print_r($gallery_info)  ;die();	 
						$i = 1;
					foreach ($gallery_info as $row)
					{ ?>
					<tr>
					<td><?= $i++ ?></td>
						<td>
							<a href='<?php echo site_url('client_controller/god_view_gallery/'.$row['gallery_id']); ?>' >
								<?php echo $row['gallery_name']; echo ' -- ('.$row['image_count'].')'; ?>
							</a>
						</td>
						<td><center>
							<a href="<?php echo site_url('client_controller/god_view_gallery/'.$row['gallery_id']); ?>" class="btn  btn-success btn_align"
							data-placement="top" data-toggle="tooltip" data-original-title="View/Edit Gallery Items">
								<i style='margin-right: 5px; margin-left: 5px;' class="fa fa-eye" ></i>
							</a>
							<a class="delete_event btn  btn-danger btn_align" onclick="deleteFunction(<?php echo $row['gallery_id']; ?>, '<?php echo $row['gallery_name']; ?>')" gallery-name="<?php echo $row['gallery_name'];?>"
							data-placement="top" data-toggle="tooltip" data-original-title="Delete Gallery">
								<i style='margin-right: 5px; margin-left: 5px;' class="fa fa-trash-o"></i>
							</a>
						</center></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="visible-sm visible-xs visible-md">
  <a href="<?php echo site_url('dashboard');?>" id="backBtn" onclick="loader()"><span class="fa fa-mail-reply"></span></a>
</div>  
<script>
function deleteFunction(gId, galleryName){
	
	bootbox.confirm({
		title: "Confirm",
    message: "Are you sure about deleting <strong>" + galleryName + "</strong> Gallery?",
    buttons: {
        confirm: {
            label: 'Yes',
            className: 'btn-success'
        },
        cancel: {
            label: 'No',
            className: 'btn-danger'
        }
    },
    callback: function (result) {
      if(result) {        
        $.ajax({
			
          url: '<?php echo site_url('client_controller/god_delete_gallery_by_id'); ?>',
          type: 'post',
          data: {'gId' : gId},
					success: function (data) {
						 if(data == 1) {
						 	//alert("Done");
						 	location.reload();
						 } else {
						 	alert("failed");
						 }
					}
        });
		//alert(gId);
    	}
	}
  });
}

function publishGallery(id, name, status) {
	var state = (status == 1)?'Un-Publish':'Publish';
	var change_to = (status == 1)?0:1;
	var msg = "Are you sure you want to <b>"+state+"</b> the gallery: <b>"+name+"?</b><br>(Clicking yes will send notifications.)";
	bootbox.confirm({
		title: "Confirm",
	    message: msg,
	    buttons: {
	        confirm: {
	            label: 'Yes',
	            className: 'btn-success'
	        },
	        cancel: {
	            label: 'No',
	            className: 'btn-danger'
	        }
	    },
	    callback: function (result) {
	      if(result) {        
	        $.ajax({
	          	url: '<?php echo site_url('galleries/publish_gallery'); ?>',
	          	type: 'post',
	          	data: {'gId' : id, status:change_to},
				success: function (data) {
					 if(data == 1) {
					 	//alert("Done");
					 	location.reload();
					 } else {
					 	alert("failed");
					 }
				}
	        });
			//alert(gId);
	    	}
		}
	});
}

</script>

<style>
.btn_align{
  margin-bottom: 4px;
   
}
.sorting:before{
	content:none;
}
.sorting_asc:before {
    content: none;
}
.table>thead>tr>th{
	vertical-align:top;
}
</style>
