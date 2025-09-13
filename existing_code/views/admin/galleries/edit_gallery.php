<ul class="breadcrumb">
	<li><a href="#">Dashboard</a></li>
	<li>Edit Gallery</li>
</ul>
<hr>
<div class="page-content-wrap">
	<div class="row">
		<div class="col-md-12">
			<form enctype="multipart/form-data" id="gallery-form" action="<?php echo site_url('galleries/update_gallery_info/'); ?>"
			 class="form-horizontal" data-parsley-validate="" method="post">
			 <input type="hidden" name="publish_status" id="publish_status" value="">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Edit <a href='<?php echo site_url(' galleries/view_gallery/'.$gallery_info->gallery_id);
							 ?>'>
								<?php echo $gallery_info->gallery_name;?></a> Gallery</h3>
								<ul class="panel-controls" style="float:right">
									<li>
										<a  href="<?php echo site_url('galleries');?>" data-placement="top" data-toggle="tooltip" data-original-title="Back" class="control-primary"><span class="fa fa-mail-reply"></span></a>
									</li>
								</ul>
								<?php // echo '<pre>';print_r($gallery_info); die(); ?> 
					</div>
					<div class="panel-body">
						<p>
                        <strong>Notes: </strong><br>
                        1. To Update Gallery, please fill the form below.<br>
                        2. Enter the title, event date and its location. Write a small description of the event so that person seeing it can have more information about it.
                        <br>
                        3. Choose the visibilty preferences based on the requirements. <br>
                        4. Click Create Pass button.<br>
                        </p><br><br>
						<div class="col-md-12">
							<!-- <div class="panel panel-default"> -->
								<!-- <div class="panel-body"> -->
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-md-2 control-label" for="gallery_title">Title<font color="red">*</font></label>
													<div class="col-md-8">
														<input name="gallery_name" type="text" value='<?php echo $gallery_info->gallery_name;?>' class="form-control input-md"
														 data-parsley-error-message="Cannot be empty." required="">
														<input name="gallery_id" type="hidden" value='<?php echo $gallery_info->gallery_id;?>'>
													</div>
												</div>
												<div class="form-group">
		                                            <label class="col-md-2 control-label" for='event_date'>Event Date</label>
		                                            <div class="col-md-8">                                                        
		                                                <div class="input-group date" id="datetimepicker1"> 
		                                                    <input value="<?php echo date('d-m-Y', strtotime($gallery_info->gallery_date)); ?>"  type='text' class="form-control" id="event_date" name="event_date">
		                                                    <span class="input-group-addon">
		                                                        <span class="glyphicon glyphicon-calendar"></span>
		                                                    </span>
		                                                </div>
		                                            </div>
		                                        </div>
												<div class="form-group">
													<label class="col-md-2 control-label" for="event_location">Location</label>
													<div class="col-md-8">
														<input type="text" placeholder='Enter Location.' name="gallery_location" value='<?php echo $gallery_info->gallery_location;?>'
														 class="form-control input-md emailtype">
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-md-2 control-label">Description</label>
													<div class="col-md-10">
														<textarea name="gallery_description" placeholder='Enter Description.' class="form-control input-md"><?php echo $gallery_info->gallery_description;?></textarea>
													</div> 
												</div>
												<div class="form-group">
													<label class="col-md-2 control-label" for="event_visibility">Visibility</label>
													<div class="col-md-8">
                                                        <!-- VALUE 1 FOR ONLY ME, 2 FOR STAFF ONLY, 3 FOR PARENTS AND STAFF AND 4 FOR PUBLIC/WEBSITE -->
                                                        <label class="radio-inline" >
                                                            <input  type="radio" data-parsley-group="block1" <?php if($gallery_info->gallery_visibility == '1') echo 'checked' ?> name="gallery_visibility" value="1" onclick="classandsection(false)">
                                                            Only Me
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" data-parsley-group="block1" <?php if($gallery_info->gallery_visibility == '2') echo 'checked' ?> name="gallery_visibility"  value="2" onclick="classandsection(false)">
                                                            Staff Only
                                                        </label>   
                                                        <label class="radio-inline" >
                                                            <input  type="radio" data-parsley-group="block1" <?php if($gallery_info->gallery_visibility == '3') echo 'checked' ?> name="gallery_visibility" value="3" onclick="classandsection(true);">
                                                            Staff and Parents
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" data-parsley-group="block1"  <?php if($gallery_info->gallery_visibility == '4') echo 'checked' ?> name="gallery_visibility"  value="4" onclick="classandsection(false)">
                                                            Public
                                                        </label>                              
                                                        <!-- <label class="switch">
                                                            <input type="hidden" id="g_visibility" name="gallery_visibility"
                                                                value="0">
                                                            <input class="eventClick att_<?php //echo $student['id']?>"
                                                                data-id="<?php //echo $student['id'] ?>" type="checkbox"
                                                                value="0" name="<?php //echo $student['id'].'_'.$attValue->id.'_n'.'_'.$student['event_id'].'_'.$student['event_type'] ?>" />
                                                            <span></span>
                                                            <h5 id='gallery_visibility'>Gallery is Private</h5>
                                                        </label> -->
                                                    </div>

                                                    							<!-- <div class="col-md-8">
														
															 <?php 
																//if($gallery_info->gallery_visibility == 0){?>
																<label class="switch">
																	<input type="hidden" id="g_visibility" name="gallery_visibility" value="0">
															<input class="eventClick att_<?php ?>" data-id="<?php  ?>"
															 type="checkbox" value="0" name="<?php ?>" />
															<span></span>
															<h5 id='gallery_visibility'>Gallery is Private</h5></label>
																<?php //}
																//else{ ?>
																	<label class="switch">
																	<input type="hidden" id="g_visibility" name="gallery_visibility" value="1">
															<input class="eventClick att_<?php ?>" data-id="<?php  ?>"
															 type="checkbox" checked value="1" name="<?php ?>" />
															<span></span>
															<h5 id='gallery_visibility'>Gallery is Public</h5></label>
															<?php	//}
															?>	  
															
														
													</div> -->
												</div>
											</div>
										</div>
									</div>
								<!-- </div>	 -->
							<!-- </div> -->
						</div>
					</div>
					<div class="panel-body" id="pas" style="display : none;">
			            <div class="form-group">
			                <div class="col-md-12 col-sm-12">
			                    <!-- <div class="row"> -->
			                    	 <div class="col-md-5 col-xs-5">
			                    	 	<label class="control-label">All Available Classes & Sections</label>
			                    	 	<select multiple="multiple" class="form-control users_list" id="multi_d" size="10">
											<?php
												foreach($classSectionList as $key =>$value)
												{
													echo '<option value='.$value->class_name.$value->section_name.'>';
													echo $value->class_name.$value->section_name;
													echo '</option>';
												}
											?>
										</select>
			                    	 </div>
			                    	 <div class="col-md-2 col-xs-2">
				                            <div style="margin:0 0 48px;" />
				                        </div>
				                        <button data-placement='top' data-toggle='tooltip' data-original-title='Select All' type="button"
				                            id="multi_d_rightAll" class="btn btn-secondary argin-bottom: 10px;"><i
				                                style="font-size: 22px;" class="fa fa-angle-double-right"></i>
				                        </button>
				                        <button data-placement='top' data-toggle='tooltip' data-original-title='Select' type="button"
				                            id="multi_d_rightSelected" class="btn btn-secondary argin-bottom: 10px;"><i
				                                style="font-size: 22px;" class="fa fa-angle-right"></i>
				                        </button>
				                        <br>
				                        <button data-placement='top' data-toggle='tooltip' data-original-title='Deselect' type="button"
				                            id="multi_d_leftSelected" style="margin-bottom: 10px;" class="btn btn-secondary 
				                                style="font-size: 22px;" class="fa fa-angle-left"></i>
				                        </button>
				                        <button data-placement='top' data-toggle='tooltip' data-original-title='Remove All' type="button"
				                            id="multi_d_leftAll" class="btn btn-secondary argin-bottom: 10px;"><i
				                                style="font-size: 22px;" class="fa fa-angle-double-left"></i>
				                        </button>
				                    </div>
				                    <div class="col-md-5 col-xs-5">
				                    	<div class="form-group">
				                        <label class="control-label">Enabled Classes & Sections</label>
				                        <select name="classSectionId[]" id="multi_users_to_2" class="form-control" size="10"
				                            multiple="multiple" required="">
				                            <?php  if (!empty($enabledOptions)) { 
				                                //$enabledOptions = json_decode($enabledOptions);
				                                //echo '<pre>'; print(gettype($enabledOptions)); die(); 
				                                foreach($enabledOptions as $value) { ?>
				                                <option value="<?= $value ?>">
				                                    <?= $value ?>
				                                </option>
				                                <?php }
				                        } ?>
				                        </select>
				                    </div>
			                    </div>
			                </div>
			            </div>
			        </div>
					<center>
						<input type="button" onclick="gallery_update()" class="btn btn-success"  value="Create">
						<?php //if ($traverse_to == '0') { ?>
						<a class="btn btn-danger" href="<?php echo site_url('galleries/edit_cancel');?>">Cancel</a>
						<?php //}else{ ?>
						<!-- <a class="btn btn-danger" href="<?php //echo site_url('fees/fee_wpl/select_fee/'.$edit_data['student']->id) ?>">Cancel</a> -->
						<?php //} ?>
					</center> 	
			</div>
		</form>
	</div>
</div>
 <!-- Javascript -->
<script type="text/javascript">
	function gallery_update(){
        bootbox.dialog({
            title : "Confirm",  
            message: "Do you want to publish the Gallery ",
            buttons: {
                oks: {
                    label: "Un-Publish",
                    className: 'btn-danger',
                    callback: function(){
                        $('#publish_status').val('0');
                        $('#gallery-form').submit();
                    }
                },
                ok: {
                    label: "Publish",
                    className: 'btn-info',
                    callback: function(){
                        $('#publish_status').val('1');
                        $('#gallery-form').submit();
                    }
                },
                cancel: {
                    label: "No",
                    className: 'btn-warning'
                }
            }
        });
    }
	function classandsection(i){
		if(i==true){
			document.getElementById("pas").removeAttribute('style');
		}
		else{
			document.getElementById("pas").setAttribute('style','display:none');	
		}
	}
	$(document).ready(function(){
	    var value=$("input[type=radio][name='gallery_visibility']:checked").val()
	    if(value==3){
	    	document.getElementById("pas").removeAttribute('style');
	    }
	});
	$(document).ready(function () {
		$('#singleclick').on('click', function () {
			$(this).val('Please wait ...')
				.attr('disabled', 'disabled');
			$('#attendance_form').submit();
		});

		$('#attendanceDataTable').DataTable({
			"paging": false,
			"ordering": false,
			"info": false
		});

		$('#datetimepicker1, #event_date').datetimepicker({
		    viewMode: 'days',
		    format: 'DD-MM-YYYY'
		});
	});

	$(".eventClick").click(function () {

		var dataId = $(this).attr("data-id");
		var current_value = $(this).val();

		if (current_value == 1) {
			$('.att_' + dataId).val(0);
			$("#g_visibility").val(0);
			$('.att_' + dataId).prop('checked', false);
			$("#gallery_visibility").html("Gallery is Private");

		} else {
			$('.att_' + dataId).val(1);
			$("#g_visibility").val(1);
			$('.att_' + dataId).prop('checked', true);
			$("#gallery_visibility").html("Gallery is Public");
			// document.GetElementById('gallery_visibility').innerHTML = "gallery is Public.";
		}

	});


	function insRow() {
		var privilegesTable = document.getElementById('privilegesTable');
		var new_row = privilegesTable.rows[0].cloneNode(true);
		privilegesTable.appendChild(new_row);
	}

	function deleteRow(cell) {
		var rowIndex = cell.parentNode.rowIndex;

		if (rowIndex == 0) {
			alert('First Row cannot be deleted');
		} else {
			document.getElementById('privilegesTable').deleteRow(rowIndex);
		}
	}

	$(function () {
        $('#multi_d').multiselect({
            right: '#multi_d_to, #multi_users_to_2',
            rightSelected: '#multi_d_rightSelected',
            leftSelected: '#multi_d_leftSelected',
            rightAll: '#multi_d_rightAll',
            leftAll: '#multi_d_leftAll',
            moveToRight: function (Multiselect, options, event, silent, skipStack) {
                var button = $(event.currentTarget).attr('id');
                if (button == 'multi_d_rightSelected') {
                    var left_options = Multiselect.left.find('option:selected');
                    Multiselect.right.eq(0).append(left_options);
                    if (typeof Multiselect.callbacks.sort == 'function' && !silent) {
                        Multiselect.right.eq(0).find('option').sort(Multiselect.callbacks.sort).appendTo(Multiselect.right.eq(0));
                    }
                } else if (button == 'multi_d_rightAll') {
                    var left_options = Multiselect.left.find('option');
                    Multiselect.right.eq(0).append(left_options);

                    if (typeof Multiselect.callbacks.sort == 'function' && !silent) {
                        Multiselect.right.eq(0).find('option').sort(Multiselect.callbacks.sort).appendTo(Multiselect.right.eq(0));
                    }
                }
            },

            moveToLeft: function (Multiselect, options, event, silent, skipStack) {
                var button = $(event.currentTarget).attr('id');

                if (button == 'multi_d_leftSelected') {
                    var right_options = Multiselect.right.eq(0).find('option:selected');
                    Multiselect.left.append(right_options);

                    if (typeof Multiselect.callbacks.sort == 'function' && !silent) {
                        Multiselect.left.find('option').sort(Multiselect.callbacks.sort).appendTo(Multiselect.left);
                    }
                } else if (button == 'multi_d_leftAll') {
                    var right_options = Multiselect.right.eq(0).find('option');
                    Multiselect.left.append(right_options);

                    if (typeof Multiselect.callbacks.sort == 'function' && !silent) {
                        Multiselect.left.find('option').sort(Multiselect.callbacks.sort).appendTo(Multiselect.left);
                    }
                }
            }
        });
    });
</script>