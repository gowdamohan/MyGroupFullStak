<ul class="breadcrumb">
  <li><a href="<?php echo site_url('dashboard') ?>">Dashboard</a></li>
  <li class="active">Create Union Staff Application form fields</li>
</ul>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-titile">Create Union Staff Application form fields</h3>
  </div>
  <div class="panel-body">
    <form enctype="multipart/form-data" method="post" action="<?php echo site_url('admin_controller/staff_applcation_form') ?>" class="form-horizontal" data-parsley-validate >
      <div class="form-group">
        <label class="control-label col-md-3" for="unions_category">Union Category</label>
        <div class="col-md-6">
          <select class="form-control"  onchange="this.form.submit()" name="unions_category">
            <option value="">Select Union Category</option>
            <?php foreach ($myunions_category as $key => $val) { ?>
              <option <?php if($unionCat == $val->id) echo 'selected' ?> value="<?php echo $val->id ?>"><?php echo $val->category ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </form>
  </div>

  <form enctype="multipart/form-data" method="post" action="<?php echo site_url('admin_controller/insert_staff_application_config_fields_admin/'.$unionCat);?>" data-parsley-validate="" class="form-horizontal">
        <div class="panel-body">
          <table class="table no-border">
            <thead>
              <tr>
                <td width="8%;" style="vertical-align: middle; text-align: center;" >Enabled Field</td>
                <td width="10%" style="vertical-align: middle; text-align: center;" >Mandatory Field</td>
                <td style="vertical-align: middle; text-align: center;" >Forms</td>
              </tr>
            </thead>
            <tbody>
              <?php 
                foreach ($enabled_fields as $key => $val) { ?>
                    <tr>
                      <td style="vertical-align: middle; text-align: center;">
                        <input type="checkbox" class="enabledFields" <?php if (in_array($val, $selected_enabled_fields)) echo 'checked' ?> name="show_enabled_fields_staff[]" value="<?php echo $val ?>" onclick="onclick_enabled_fields('<?php echo $val ?>')" style="width: 18px;height: 18px;" id="enabled<?php echo $val ?>" >
                      </td>
                      <td style="vertical-align: middle; text-align: center;">
                        <input type="checkbox" <?php if (in_array($val, $selected_required_fields)) echo 'checked' ?> name="required_fields_staff[]" style="width: 18px;height: 18px;" value="<?php echo $val ?>" readonly="true" onclick="onclick_required_fields('<?php echo $val ?>')" id="required<?php echo $val ?>" >
                      </td>
                      <td>
                        <div class="form-group">
                          <label style="color: #ccc;" id="label<?php echo $val ?>" class="control-label col-md-3"><?php echo strtoupper(str_replace('_',' ', $val)) ?><font id="lablefont<?php echo $val ?>"></font></label>
                          <div class="col-md-8">
                            <input type="text" autocomplete="off" id="input<?php echo $val ?>" class="form-control" value="" placeholder="<?php echo strtoupper(str_replace('_',' ', $val)) ?>">
                          </div>
                        </div>
                      </td>
                    </tr>
                <?php } ?>
            </tbody>
          </table>
         
        </div>
        <div class="panel-footer">
          <center><button type="submit" class="btn btn-info">Submit</button></center>
        </div>
  </form>
</div>

<script type="text/javascript">
  function onclick_enabled_fields(val) {
    if ($('#enabled'+val).is(':checked')) {
      $('#required'+val).prop('readonly',false);
      $('#label'+val).css('color','#000');
    }else{
      $('#required'+val).prop('readonly',true);
      $('#required'+val).prop('checked',false);
      $('#label'+val).css('color','#ccc');
    }
  }
  function onclick_required_fields(val) {
    if ($('#required'+val).is(':checked')) {
      $('#lablefont'+val).css('color','red').html(' *');
    }else{
      $('#lablefont'+val).css('color','').html('');
    }
  }
</script>