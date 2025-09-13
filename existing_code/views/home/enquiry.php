<div class="container mb-4">
    <div class="row">
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
</script>