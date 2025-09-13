<ul class="breadcrumb">
  <li class="active">Dashboard</li>  
  <li>State</li>
</ul>
<div class="page-content-wrap">
  <div class="row">
    <div class="col-md-12">

      <div class="panel panel-default">
        <?php if (!empty($edit_state)) { ?>
          <form enctype="multipart/form-data" id="demo-form" action="<?php echo site_url('country_controller/update_state/'.$edit_state->id) ?>" class="form-horizontal"  data-parsley-validate method="post" >
            <div class="panel-heading">
              <h3 class="panel-title"><strong> Add State</strong></h3>
            </div>
            <div class="panel-body">                                            
               <div class="col-md-6 col-offset-4">

                <div class="form-group">
                 <label class="control-label col-sm-4" for="continentId">Continent</label>
                  <div class="col-sm-6">
                   
                    <select name="continent" class="form-control" onchange="content_get_country()" id="continentId">
                      <option value="">Select Continent</option>
                        <?php foreach ($continent as $key => $val) {?>
                          <option <?php if($edit_state->conId == $val->id) echo 'selected' ?> value="<?php echo $val->id ?>"><?php echo $val->continent;?></option>
                        <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                 <label class="control-label col-sm-4" for="country">Country</label>
                  <div class="col-sm-6">
                    <input type="hidden" id="editConid" value="<?php echo $edit_state->countryId ?>">
                    <select name="country" class="form-control" id="country">
                      <option value="">Select Country</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                 <label class="control-label col-sm-4" for="state">State</label>
                  <div class="col-sm-6">
                    <input type="text" name="state" value="<?php echo $edit_state->state ?>" class="form-control">
                  </div>
                </div>

                <div class="form-group">
                 <label class="control-label col-sm-4" for="code">Code</label>
                  <div class="col-sm-6">
                    <input type="text" name="code" value="<?php echo $edit_state->code ?>" class="form-control">
                  </div>
                </div>
                
                
                <center>                                 
                  <button class="btn btn-primary ">Update</button>
                </center>

              </div>

            </div>
          </form> 
        <?php }else{ ?>
          <form enctype="multipart/form-data" id="demo-form" action="<?php echo site_url('country_controller/insert_state') ?>" class="form-horizontal"  data-parsley-validate method="post" >
            <div class="panel-heading">
              <h3 class="panel-title"><strong> Add State</strong></h3>
            </div>
            <div class="panel-body">                                            
              <div class="col-md-6 col-offset-4">

                <div class="form-group">
                 <label class="control-label col-sm-4" for="continentId">Continent</label>
                  <div class="col-sm-6">
                    <select name="continent" required=""  class="form-control continent" onchange="content_get_country()" id="continentId">
                      <option value="">Select Continent</option>
                        <?php foreach ($continent as $key => $val) {?>
                          <option value="<?php echo $val->id;?>"><?php echo $val->continent;?></option>
                        <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                 <label class="control-label col-sm-4" for="country">Country</label>
                  <div class="col-sm-6">
                    <select name="country" required=""  class="form-control" id="country">
                      <option value="">Select Country</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                 <label class="control-label col-sm-4" for="state">State</label>
                  <div class="col-sm-6">
                    <input type="text" required="" name="state" value="" class="form-control">
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
          <h3>State List</h3>
        </div>
        <div class="panel-body">
          <?php if (!empty($state)) { ?>
            <table class="table table-bordered datatable" id="datatable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Continent Name</th>
                  <th>Country Name</th>
                  <th>State Name</th>
                  <th>State Code</th>
                  <th>Order</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; 
                foreach ($state as $key => $val) { ?>
                  <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $val->continent ?></td>
                    <td><?php echo $val->country ?></td>
                    <td><?php echo $val->state ?></td>
                    <td><?php echo $val->code ?></td>
                    <td>
                      <input type="number" style="width: 80px" name="order_wise" value="<?php echo $val->order ?>" id="order_wise_<?php echo $val->id ?>">
                      <a onclick="update_orderby('<?php echo $val->id ?>','state_tbl')" class='btn btn-warning btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Order'><i class='fa fa-arrow-circle-up'></i></a>
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
                      <a href="<?= site_url('country_controller/edit_state/'.$val->id) ?>" class='btn btn-warning btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Edit'><i class='fa fa-edit'></i></a>
                      
                      <a onclick="return confirm('Are you sure delete this item?')" href="<?= site_url('country_controller/delete_state/'.$val->id) ?>" class='btn btn-info btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><i class='fa fa-trash-o'></i></a>
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
$(document).ready(function(){
  content_get_country();
});

function content_get_country() {
   var conId =$('#continentId').val();
   var editconId = $('#editConid').val();
    $.post("<?php echo site_url('country_controller/get_country_by_conId')?>",{conId:conId},function(data){
      var country = jQuery.parseJSON(data);
      console.log(country);
      var output='';
      output+='<option value="">Select Country</option>';
      var len=country.length;
      for (var i=0,j=len; i < j; i++) {
        var selected = '';
        if (editconId == country[i].id) {
          selected ='selected';
        }
        output+='<option '+selected+' value="'+country[i].id+'">'+country[i].country+'</option>'; 
      }
      $('#country').html(output);
   });
}

  function update_orderby(cateId, table) {
    var order_wise = $('#order_wise_'+cateId).val();
    $.ajax({
      url: "<?php echo site_url('country_controller/update_order_state');?>",
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
    var table = 'state_tbl';
    $.ajax({
      url: "<?php echo site_url('country_controller/update_status_state');?>",
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