<ul class="breadcrumb">
  <li class="active">Dashboard</li>  
  <li>Country</li>
</ul>
<div class="page-content-wrap">
  <div class="row">
    <div class="col-md-12">

      <div class="panel panel-default">
        <?php if (!empty($edit_country)) { ?>
          <form enctype="multipart/form-data" id="demo-form" action="<?php echo site_url('country_controller/update_country/'.$edit_country->id) ?>" class="form-horizontal"  data-parsley-validate method="post" >
            <div class="panel-heading">
              <h3 class="panel-title"><strong> Add Country</strong></h3>
            </div>
            <div class="panel-body">                                            
               <div class="col-md-6 col-offset-4">

                <div class="form-group">
                 <label class="control-label col-sm-4" for="continentId">Continent</label>
                  <div class="col-sm-6">
                    <select name="continent" class="form-control" id="continentId">
                      <option value="">Select Continent</option>
                        <?php foreach ($continent as $key => $val) {?>
                          <option <?php if($edit_country->continent_id == $val->id) echo 'selected' ?> value="<?php echo $val->id ?>"><?php echo $val->continent;?></option>
                        <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                 <label class="control-label col-sm-4" for="continentId">Country</label>
                  <div class="col-sm-6">
                    <input type="text" name="country" value="<?php echo $edit_country->country ?>" class="form-control">
                  </div>
                </div>

                <div class="form-group">
                 <label class="control-label col-sm-4" for="code">Code</label>
                  <div class="col-sm-6">
                    <input type="text" name="code" value="<?php echo $edit_country->code ?>" class="form-control">
                  </div>
                </div>

                <div class="form-group">
                 <label class="control-label col-sm-4" for="code">Currency</label>
                  <div class="col-sm-6">
                    <input type="text" name="currency" value="<?php echo $edit_country->currency ?>" class="form-control">
                  </div>
                </div>

                <div class="form-group">
                 <label class="control-label col-sm-4" for="code">Country Flag</label>
                  <div class="col-sm-6">
                    <input type="hidden" name="country_flag_1" value="<?php echo $edit_country->country_flag ?>" class="form-control">
                    <input type="file" name="country_flag" value="" class="form-control">
                  </div>
                </div>

                <div class="form-group">
                 <label class="control-label col-sm-4" for="code">ISD Code</label>
                  <div class="col-sm-6">
                    <input type="text" name="phone_code" value="<?php echo $edit_country->phone_code ?>" class="form-control">
                  </div>
                </div>

                <div class="form-group">
                 <label class="control-label col-sm-4" for="code">Nationality</label>
                  <div class="col-sm-6">
                    <input type="text" name="nationality" value="<?php echo $edit_country->nationality ?>" class="form-control">
                  </div>
                </div>


                <center>                                 
                  <button class="btn btn-primary ">Update</button>
                </center>

              </div>

            </div>
          </form> 
        <?php }else{ ?>
          <form enctype="multipart/form-data" id="demo-form" action="<?php echo site_url('country_controller/insert_country') ?>" class="form-horizontal"  data-parsley-validate method="post" >
            <div class="panel-heading">
              <h3 class="panel-title"><strong> Add Country</strong></h3>
            </div>
            <div class="panel-body">                                            
              <div class="col-md-6 col-offset-4">

                <div class="form-group">
                 <label class="control-label col-sm-4" for="continentId">Continent</label>
                  <div class="col-sm-6">
                    <select name="continent" required="" class="form-control continent" id="continentId">
                      <option value="">Select Continent</option>
                        <?php foreach ($continent as $key => $val) {?>
                          <option value="<?php echo $val->id;?>"><?php echo $val->continent;?></option>
                        <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                 <label class="control-label col-sm-4" for="continentId">Country</label>
                  <div class="col-sm-6">
                    <input type="text" required="" name="country" class="form-control">
                  </div>
                </div>

                <div class="form-group">
                 <label class="control-label col-sm-4" for="code">Code</label>
                  <div class="col-sm-6">
                    <input type="text" name="code" value="" class="form-control">
                  </div>
                </div>
                
                <div class="form-group">
                 <label class="control-label col-sm-4" for="code">Currency</label>
                  <div class="col-sm-6">
                    <input type="text" name="currency" value="" class="form-control">
                  </div>
                </div>

                <div class="form-group">
                 <label class="control-label col-sm-4" for="code">Country Flag</label>
                  <div class="col-sm-6">
                    <input type="file" name="country_flag" value="" class="form-control">
                  </div>
                </div>

                  <div class="form-group">
                   <label class="control-label col-sm-4" for="code">ISD Code</label>
                    <div class="col-sm-6">
                      <input type="text" name="phone_code" value="" class="form-control">
                    </div>
                  </div>

                  <div class="form-group">
                   <label class="control-label col-sm-4" for="code">Nationality</label>
                    <div class="col-sm-6">
                      <input type="text" name="nationality" value="" class="form-control">
                    </div>
                  </div>


                <center>                                 
                  <button class="btn btn-primary ">Submit</button>
                </center>   
              </div>
                
            </div>
          </form> 
        <?php } ?>
        
      </div>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3>Country List</h3>
        </div>
        <div class="panel-body">
          <?php if (!empty($country)) { ?>
            <table class="table table-bordered datatable" id="datatable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Continent Name</th>
                  <th>Country Name</th>
                  <th>Country Code</th>
                  <th>Currency</th>
                  <th>Country Flag</th>
                  <th>ISD Code</th>
                  <th>Nationality</th>
                  <th>Order</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; 
                foreach ($country as $key => $val) { ?>
                  <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $val->continent ?></td>
                    <td><?php echo $val->country ?></td>
                    <td><?php echo $val->code ?></td>
                    <td><?php echo $val->currency ?></td>
                    <td><img style="width: 40px" class="img-responsive" src="<?php echo base_url().$val->country_flag ?>"></td>
                     <td><?php echo $val->phone_code ?></td>
                    <td><?php echo $val->nationality ?></td>
                    <td>
                      <input type="number" style="width: 80px" name="order_wise" value="<?php echo $val->order ?>" id="order_wise_<?php echo $val->id ?>">
                      <a onclick="update_orderby('<?php echo $val->id ?>','country_tbl')" class='btn btn-warning btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Order'><i class='fa fa-arrow-circle-up'></i></a>
                    </td>

                    <td>
                      <?php if($val->status == 1){ ?>
                        <label class="switch">
                          <input type="checkbox" onclick="country_status('<?php echo $val->id ?>','0')" checked >
                          <span></span>
                        </label>
                      <?php }else{ ?>
                        <label class="switch">
                          <input type="checkbox" onclick="country_status('<?php echo $val->id ?>','1')" >
                          <span></span>
                        </label>
                      <?php } ?>
                    </td>

                    <td>
                      <a href="<?= site_url('country_controller/edit_country/'.$val->id) ?>" class='btn btn-warning btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Edit'><i class='fa fa-edit'></i></a>
                      
                      <a onclick="return confirm('Are you sure delete this item?')" href="<?= site_url('country_controller/delete_country/'.$val->id) ?>" class='btn btn-info btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><i class='fa fa-trash-o'></i></a>
                    </td>

                  </tr>
                <?php } ?>
                
              </tbody>
            </table>
          <?php } ?>
          
        </div>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  function update_orderby(cateId, table) {
    var order_wise = $('#order_wise_'+cateId).val();
    $.ajax({
      url: "<?php echo site_url('country_controller/update_order_country');?>",
      data: {'cateId':cateId, 'order_wise': order_wise, 'table':table},
      type: 'post',
      success: function(data){
        // console.log(data);
        location.reload();
      },
      error: function (err) {
        console.log(err);
      }
    });
  }

  function country_status(cId, mode) {
    var table = 'country_tbl';
    $.ajax({
      url: "<?php echo site_url('country_controller/update_status_country');?>",
      data: {'cId':cId, 'mode': mode, 'table':table},
      type: 'post',
      success: function(data){
        // console.log(data);
        location.reload();
      },
      error: function (err) {
        console.log(err);
      }
    });
  }
</script>