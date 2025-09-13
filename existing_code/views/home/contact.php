
  <?php 
    if (!empty($branch_office)) {
      echo "<div class='container branchAddress'>";
      foreach ($branch_office as $key => $val) { ?>

        <h3 class="text-default">Branch </h3>

        <select class="form-control"  onclick="change_location_selection_address('branch')" >
          <option id="branch_district_name" value=""><?php echo $val->district_name ?></option>
        </select>

        <address id="branch_address" >
          <br>
          <?php echo $val->address ?>
        </address>
        <h5><img width="20px" src="<?php echo base_url().'assets/front/img/social-icon/mail sq.png' ?>"> <small id="branch_email" class="text-muted"><?php echo $val->email ?></small></h5>
        <h5><img width="20px" src="<?php echo base_url().'assets/front/img/social-icon/call sq.png' ?>"> <small id="branch_phone" class="text-muted"><?php echo $val->phone ?></small></h5>
      <?php }
      echo "</div>";
    }
  ?>

  <?php 
    if (!empty($regional_office)) {
      echo "<div class='container regionalAddress'>";
      foreach ($regional_office as $key => $val) { ?>
        <h3 onclick="change_location_selection_address('regional')" class="text-default">Regional Office </h3>

        <select class="form-control" onclick="change_location_selection_address('regional')" >
          <option id="regional_state_name" value=""><?php echo $val->state_name ?></option>
        </select>

        <address id="regional_address">
          <br>
          <?php echo $val->address ?>
        </address>
        <h5><img width="20px" src="<?php echo base_url().'assets/front/img/social-icon/mail sq.png' ?>"> <small id="regional_email" class="text-muted"><?php echo $val->email ?></small></h5>
        <h5><img width="20px" src="<?php echo base_url().'assets/front/img/social-icon/call sq.png' ?>"> <small id="regional_phone" class="text-muted"><?php echo $val->phone ?></small></h5>
      <?php }
      echo "</div>";
    }
  ?>

  <?php 
    if (!empty($head_office)) {
      echo "<div class='container headAddress'>";
      foreach ($head_office as $key => $val) { ?>

        <h3 onclick="change_location_selection_address('headOffice')" class="text-default">Head Office </h3>
        <select class="form-control" onclick="change_location_selection_address('headOffice')" >
          <option id="head_country_name" value=""><?php echo $val->country_name ?></option>
        </select>

        <address id="head_address">
          <br>
          <?php echo $val->address ?>
        </address>
        <h5><img width="20px" src="<?php echo base_url().'assets/front/img/social-icon/mail sq.png' ?>"> <small id="head_email" class="text-muted"><?php echo $val->email ?></small></h5>
        <h5><img width="20px" src="<?php echo base_url().'assets/front/img/social-icon/call sq.png' ?>"> <small id="head_phone" class="text-muted"><?php echo $val->phone ?></small></h5>
      <?php }
      echo "</div>";
    }
  ?>

<style type="text/css">
  .branchAddress{
    border: 2px solid #12b1a2;
    border-radius: 20px;
    margin-bottom: 1rem;
  }
  .branchAddress .text-default{
    background: #030f52;
    border-radius: 10px;
    text-align: center;
    margin-top: 0.5rem;
    color: #ffff;
  }


  .regionalAddress{
    border: 2px solid #12b1a2;
    border-radius: 20px;
    margin-bottom: 1rem;
  }
  .regionalAddress .text-default{
    background: #030f52;
    border-radius: 10px;
    text-align: center;
    margin-top: 0.5rem;
    color: #ffff;
  }
  .headAddress{
    border: 2px solid #12b1a2;
    border-radius: 20px;
    margin-bottom: 1rem;
  }
  .headAddress .text-default{
    background: #030f52;
    border-radius: 10px;
    text-align: center;
    margin-top: 0.5rem;
    color: #ffff;
  }
</style>

<div class="container mb-4">
    <div class="row">
      <div  class="col-md-6 pb-4">
      <?php if (!empty($contact)) { ?>
          <br>
          <br>
          <h3 class="div-heading" style="border-bottom: 4px solid #ff0404;width: 105px;">Contact</h3>
          <?php echo $contact->address ?>
      <h5><img width="40px" src="<?php echo base_url().'assets/front/img/social-icon/mail sq.png' ?>"> <small class="text-muted"><?php echo $contact->email ?></small></h5>
      <h5><img width="40px" src="<?php echo base_url().'assets/front/img/social-icon/call sq.png' ?>"> <small class="text-muted"><?php echo $contact->contact_number ?></small></h5>
      <?php } ?>
    </div>
    <div class="col-md-6">
      <div class="col-sm text-center">
        <h3 class="div-heading" style="border-bottom: 4px solid #ff0404;width: 105px;">Enquiry</h3>
      </div>
      <form enctype="multipart/form-data" method="post" id="students" action="<?php echo site_url('home/contact_enquiry/'.$groupname) ?>" class="form-horizontal" data-parsley-validate >
        <div class="form-group">
            <input type="name" class="form-control" required="" name="first_name" id="exampleInputName" placeholder="Your Full Name...">
        </div>
        <div class="form-group">
          <input type="email" class="form-control"  required="" name="email_id" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Your Email Address">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" required="" name="mobile_number"  id="exampleInputMobile" aria-describedby="emailHelp" placeholder="Your Phone Number">
        </div>
        <div class="form-group">
          <textarea class="form-control" name="comments" placeholder="Comments" aria-label="With textarea"></textarea>
        </div>
        <button type="submit" class="btn btn-warning btn-lg btn-block">Submit</button>
      </form>
    </div>
  </div>
</div>

  <script type="text/javascript">
   var user_id = '<?php echo (!empty($user)) ? $user->id : ''  ?>';
    $.ajax({
        url: '<?php echo site_url('home/edit_profile_mobile'); ?>',
        data: {'user_id': user_id},
        type: 'post',
        success: function(data) {
          var profile = JSON.parse(data);
          $('#exampleInputName').val(profile.profile.first_name);
          $('#exampleInputMobile').val(profile.profile.phone);
          $('#exampleInputEmail1').val(profile.profile.email);
        }
    });

    function change_location_selection_address(location_type) {
      $('#addrss_location_type').val(location_type);
      $('#location_address_modal').modal('show');
      $('#districtAddress').hide();
      $('#stateAddress').hide();
      $('#countryAddress').hide();
      $('#current_address_country').val('')
      $('#current_address_state').val('')
      $('#current_adress_district').val('')
      if (location_type == 'branch') {
        $('#districtAddress').show();
        $('#stateAddress').show();
        $('#countryAddress').show();
      }else if(location_type == 'regional'){
        $('#districtAddress').hide();
        $('#stateAddress').show();
        $('#countryAddress').show();
      }else if(location_type == 'headOffice'){
        $('#districtAddress').hide();
        $('#stateAddress').hide();
        $('#countryAddress').show();
      }else{
        $('#districtAddress').hide();
        $('#stateAddress').hide();
        $('#countryAddress').hide();
      }
    }
    function address_get_state_local_current() {
        var countryId = $('#current_address_country').val();
        $.post("<?php echo site_url('country_controller/get_state_by_countryId')?>",{countryId:countryId},function(data){
            var state = jQuery.parseJSON(data);
            var output='';
            output+='<option value="">Select State</option>';
            var len=state.length;
            for (var i=0,j=len; i < j; i++) {
              output+='<option  value="'+state[i].id+'">'+state[i].state+'</option>'; 
            }
            $('#current_address_state').html(output);
        });
    }

    function address_get_district_by_state_current(value) {
        var state = value;
        $.post("<?php echo site_url('country_controller/get_state_by_district')?>",{state:state},function(data){
          var district = jQuery.parseJSON(data);
          var output='';
          output+='<option value="">Select District</option>';
          var len=district.length;
          for (var i=0,j=len; i < j; i++) {
            output+='<option value="'+district[i].id+'">'+district[i].district+'</option>'; 
          }
          $('#current_adress_district').html(output);
       });
    }

    function change_address_location() {
      var addLocationType = $('#addrss_location_type').val();

      if (addLocationType == 'branch') {
        var country= $('#current_address_country').val();
        var state = $('#current_address_state').val();
        var district = $('#current_adress_district').val();
        onchange_branch_address(country,state,district);
      }else if(addLocationType == 'regional'){
        var country= $('#current_address_country').val();
        var state = $('#current_address_state').val();
        onchange_regional_address(country,state);
      }else if(addLocationType == 'headOffice'){
        var country= $('#current_address_country').val();
        onchange_head_office_address(country);
      }

    }

    function onchange_branch_address(country,state,district) {
      $.ajax({
        url:'<?php echo site_url('home/get_branch_address') ?>',
        type:'post',
        data:{'country':country,'state':state,'district':district},
        success:function(result){
          var data = $.parseJSON(result); 
          console.log(data);
          $('#location_address_modal').modal('hide');
          if (data.length > 0) {
            $('#branch_district_name').html(data[0].district_name);
            $('#branch_address').html(data[0].address);
            $('#branch_email').html(data[0].email);
            $('#branch_phone').html(data[0].phone);
          }
         
        }
      });
    }
    function onchange_regional_address(country,state) {
      $.ajax({
        url:'<?php echo site_url('home/get_regional_address') ?>',
        type:'post',
        data:{'country':country,'state':state},
        success:function(result){
          var data = $.parseJSON(result);
          $('#location_address_modal').modal('hide');
          if (data.length > 0) {
            $('#regional_state_name').html(data[0].state_name);
            $('#regional_address').html(data[0].address);
            $('#regional_email').html(data[0].email);
            $('#regional_phone').html(data[0].phone);
          }
        }
      });
    }
    function onchange_head_office_address(country) {
      $.ajax({
        url:'<?php echo site_url('home/get_head_office_address') ?>',
        type:'post',
        data:{'country':country},
        success:function(result){
          var data = $.parseJSON(result);
          console.log(data);
          $('#location_address_modal').modal('hide');
          if (data.length > 0) {
            $('#head_country_name').html(data[0].country_name);
            $('#head_address').html(data[0].address);
            $('#head_email').html(data[0].email);
            $('#head_phone').html(data[0].phone);
          }
        }
      });
    }
</script>

<div class="modal fade" id="location_address_modal" tabindex="-1" aria-labelledby="locationChangeModalLabel" aria-hidden="true">
    <div class="modal-dialog m-0" style="height:auto;">
        <div class="modal-content" style="height:auto;">
            <div class="modal-header" style="padding: 15px 30px; border: none; background: #17a2b8; color: #fff; ">
                <h3>Change Location</h3>
            </div>
            <input type="hidden" id="addrss_location_type">
            <div class="modal-body">
                <div class="form-group" id="countryAddress" >
                  <?php 
                   $country = $this->db->get('country_tbl')->result();
                  ?>
                 
                  <p>Country <font color="red">*</font></p>
                    <select class="form-control" name="country" required="" onchange="address_get_state_local_current()"  id="current_address_country">
                        <option value="">Select Country</option>
                        <?php foreach ($country as $key => $val) { ?>
                            <option value="<?php echo $val->id ?>"><?php echo $val->country ?></option>
                        <?php } ?>
                   </select>
                </div>

                <div class="form-group" id="stateAddress">
                  <p>State / Province <font color="red">*</font></p>
                  <select class="form-control" name="state" onchange="address_get_district_by_state_current(this.value)" required="" id="current_address_state">
                    <option value="">Select State / Province</option>
                  </select>
                </div>

                <div class="form-group" id="districtAddress">
                  <p>District / City <font color="red">*</font></p>
                  <select class="form-control" name="district" required="" id="current_adress_district">
                    <option value="">Select District</option>
                  </select>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" onclick="change_address_location()" class="btn btn-primary">Okay</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>