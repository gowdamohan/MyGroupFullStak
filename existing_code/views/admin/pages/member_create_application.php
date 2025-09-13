<ul class="breadcrumb">
  <li><a href="<?php echo site_url('dashboard') ?>">Dashboard</a></li>
  <li class="active">Create Application form fields</li>
</ul>
<div class="panel panel-default">
  <form enctype="multipart/form-data" method="post" action="<?php echo site_url('admin_controller/insert_member_application_config_fields');?>" data-parsley-validate="" class="form-horizontal">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-titile">Create Application form fields</h3>
        </div>
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
              if (!empty($enabled_fields['enabled_fields'])) {
                foreach ($enabled_fields['enabled_fields'] as $key => $val) { ?>
                   <?php if ($val != 'present_area' && $val != 'present_district' && $val != 'present_state' && $val != 'present_country' && $val != 'present_pincode' && $val != 'permanent_area' && $val != 'permanent_district' && $val != 'permanent_state' && $val != 'permanent_country' && $val != 'permanent_pincode') { ?>
                 <tr>
                      <td style="vertical-align: middle; text-align: center;">
                        <input type="checkbox" class="enabledFields" <?php if (in_array($val, $selected_enabled_fields)) echo 'checked' ?> name="show_enabled_fields[]" value="<?php echo $val ?>" onclick="onclick_enabled_fields('<?php echo $val ?>')" style="width: 18px;height: 18px;" id="enabled<?php echo $val ?>" >
                      </td>
                      <?php 
                      $cssPointer ='pointer-events: none';
                      if (in_array($val, $selected_enabled_fields)){
                        $cssPointer = 'pointer-events: all';
                      }
                      ?>
                      <td style="vertical-align: middle; text-align: center;">
                        <input type="checkbox" <?php if (in_array($val, $selected_required_fields)) echo 'checked' ?> name="required_fields[]" style="width: 18px;height: 18px;<?php echo $cssPointer ?>" value="<?php echo $val ?>" onclick="onclick_required_fields('<?php echo $val ?>')" id="required<?php echo $val ?>" >
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
              <?php }
              }
                 ?>
            </tbody>
          </table>
         
        </div>
        <div class="panel-footer">
          <center><button type="submit" class="btn btn-info">Submit</button></center>
        </div>
      </div>
  </form>
</div>

<script type="text/javascript">
  function onclick_enabled_fields(val) {
    if ($('#enabled'+val).is(':checked')) {
      $('#required'+val).css('pointer-events','all');
      $('#label'+val).css('color','#000');
    }else{
      $('#required'+val).css('pointer-events','none');
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