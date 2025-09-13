<ul class="breadcrumb">
  <li class="active">Dashboard</li>  
  <li>Continent</li>
</ul>
<div class="page-content-wrap">
  <div class="row">
    <div class="col-md-12">

      <div class="panel panel-default">
        <?php if (!empty($edit_continent)) { ?>
          <form enctype="multipart/form-data" id="demo-form" action="<?php echo site_url('country_controller/update_continent/'.$edit_continent->id) ?>" class="form-horizontal"  data-parsley-validate method="post" >
            <div class="panel-heading">
              <h3 class="panel-title"><strong> Add Continet</strong></h3>
            </div>
            <div class="panel-body">                                            
              <div class="col-md-6 col-offset-4">
                <div class="form-group">
                 <label class="control-label col-sm-4" for="continentId">Continent</label>
                  <div class="col-sm-6">
                    <input type="text" name="continent" value="<?php echo $edit_continent->continent ?>" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                 <label class="control-label col-sm-4" for="code">Code</label>
                  <div class="col-sm-6">
                    <input type="text" name="code" value="<?php echo $edit_continent->code ?>" class="form-control">
                  </div>
                </div>

                <center>                                 
                  <button class="btn btn-primary ">Update</button>
                </center>     
              </div>
            </div>
          </form> 
        <?php }else{ ?>
          <form enctype="multipart/form-data" id="demo-form" action="<?php echo site_url('country_controller/insert_continent') ?>" class="form-horizontal"  data-parsley-validate method="post" >
            <div class="panel-heading">
              <h3 class="panel-title"><strong> Add Continet</strong></h3>
            </div>
            <div class="panel-body">                                            
              <div class="col-md-6 col-offset-4">
                <div class="form-group">
                 <label class="control-label col-sm-4" for="continentId">Continent</label>
                  <div class="col-sm-6">
                    <input type="text" required="" name="continent" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                 <label class="control-label col-sm-4" for="code">Code</label>
                  <div class="col-sm-6">
                    <input type="text" name="code" value="" class="form-control">
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
          <h3>Continent List</h3>
        </div>
        <div class="panel-body">
          <?php if (!empty($continent)) { ?>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Continent Name</th>
                  <th>Continent Code</th>
                  <th>Order</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; 
                foreach ($continent as $key => $val) { ?>
                  <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $val->continent ?></td>
                    <td><?php echo $val->code ?></td>

                    <td>
                      <input type="number" style="width: 80px" name="order_wise" value="<?php echo $val->order ?>" id="order_wise_<?php echo $val->id ?>">
                      <a onclick="update_orderby('<?php echo $val->id ?>','continent_tbl')" class='btn btn-warning btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Order'><i class='fa fa-arrow-circle-up'></i></a>
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
                      <a href="<?= site_url('country_controller/edit_continent/'.$val->id) ?>" class='btn btn-warning btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Edit'><i class='fa fa-edit'></i></a>
                      
                      <a onclick="return confirm('Are you sure delete this item?')" href="<?= site_url('country_controller/delete_continent/'.$val->id) ?>" class='btn btn-info btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><i class='fa fa-trash-o'></i></a>
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
      url: "<?php echo site_url('country_controller/update_order');?>",
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
    var table = 'continent_tbl';
    $.ajax({
      url: "<?php echo site_url('country_controller/update_status');?>",
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