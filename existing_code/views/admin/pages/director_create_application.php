<ul class="breadcrumb">
  <li><a href="<?php echo site_url('dashboard') ?>">Dashboard</a></li>
  <li class="active">Application form fields</li>
</ul>
<div class="panel panel-default">
  <form enctype="multipart/form-data" method="post" id="demo-form" action="<?php echo site_url('admin_controller/director_application_configure_fields');?>" data-parsley-validate="" class="form-horizontal">
    <div class="col-md-8">
      <div class="panel panel-default">
        <div class="panel-body">
          <h3 class="panel-titile">Select field to enabled for Director Application</h3>
        
          <?php 
          foreach ($directfields as $key => $val) { ?>
            <div class="checkbox">
              <label>
                <input type="checkbox" class="requiredFields" <?php if (in_array($val, $selected_direct_enabled_fields)) echo 'checked' ?>  name="director_application_show_enabled_fields[]" value="<?php echo $val ?>"><?php echo strtoupper(str_replace('_',' ',$val)) ?>
              </label>
               <label>
                <input type="text" readonly="" class="form-control" >
              </label>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>

    <div class="panel-footer">
      <center>
        <button type="submit" class="btn btn-primary">Submit</button>
      </center>
    </div>
  </form>
</div>
<script type="text/javascript">
  function check_all_required(check){
    if($(check).is(':checked')) {
      $(".requiredFields").prop('checked', true);
    }
    else {
      $(".requiredFields").prop('checked', false);
    }
  }

  function check_all_enabled(check){
    if($(check).is(':checked')) {
      $(".enabledFields").prop('checked', true);
    }
    else {
      $(".enabledFields").prop('checked', false);
    }
  }
</script>