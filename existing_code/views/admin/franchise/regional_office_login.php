<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Regional Office Login</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Regional Office Login</h3>
   </div>
   <div class="panel-body">
      <table class="table table-bordered">
         <thead>
            <tr>
               <th>#</th>
               <th>State Name</th>
               <th>Username</th>
               <th>Name</th>
               <th>Mobile</th>
               <th>Email Id</th>
               <th>Status</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php $i=1; foreach ($franchise_holder as $key => $val) { ?>
               
               <?php 
                  $profile_url = '';
                  if (!empty($val->user_id)) {
                     $profile_url = site_url('franchise/create_franchise_staff/'.$val->user_id.'/regional');
                  } ?>
                  <?php 
                     $disabled = 'readonly';
                     if (!empty($val->user_id)) {
                        $disabled = '';
                     } ?>
                     <?php 
                     $disabled1 = 'readonly';
                     if (empty($val->user_id)) {
                        $disabled1 = '';
                     } ?>

                 <tr>
                  <th><?php echo $i ?></th>
                  <th>
                     <a href="<?php echo $profile_url ?>"><?php echo $val->state ?></a>   
                  </th>
                  <th><input type="text" <?php echo $disabled1 ?>  class="form-control" value="<?php echo $val->username ?>" name="username" id="username<?php echo $i ?>"></th>
                  <input type="hidden" class="form-control" value="<?php echo $val->country_id ?>" name="franchise_country" id="franchise_country<?php echo $i ?>">
                  <input type="hidden" class="form-control" value="<?php echo $val->id ?>" name="franchise_state" id="franchise_state<?php echo $i ?>">
                  <input type="hidden" class="form-control" value="" name="franchise_district" id="franchise_district<?php echo $i ?>">

                  <th><input type="text"  class="form-control" value="<?php echo $val->first_name ?>" name="franchise_name" id="franchise_name<?php echo $i ?>"></th>
                  <th><input type="text" class="form-control" value="<?php echo $val->phone ?>" name="franchise_mobile_number" id="franchise_mobile_number<?php echo $i ?>"></th>
                  <th><input type="email" class="form-control" value="<?php echo $val->email ?>" name="franchise_email_id" id="franchise_email_id<?php echo $i ?>"></th>
                  
                  <th>
                     <?php if($val->active == 1){ ?>
                     <label class="switch">
                        <input type="checkbox" onclick="user_active_inactive('<?php echo $val->user_id ?>','0')" checked >
                        <span></span>
                     </label>
                      <?php }else{ ?>
                        <label class="switch">
                           <input type="checkbox" onclick="user_active_inactive('<?php echo $val->user_id ?>','1')" >
                           <span></span>
                        </label>
                      <?php } ?>
                  </th>
                  <th>
                     
                     <a class="btn btn-info" onclick="save_franchise_login_details(<?php echo $i ?>)" href="javascript:void(0)">Save</a>
                     <a <?php echo $disabled ?> class="btn btn-danger" href="<?php echo site_url('franchise/franchise_reset_password/'.$val->user_id.'/'.'create_regional_office_login') ?>">Reset password</a></th>
               </tr>
            <?php $i++; } ?>
         </tbody>
      </table>
   </div>
</div>

<script type="text/javascript">
   function save_franchise_login_details(i) {
      var franchise_country = $('#franchise_country'+i).val();
      var franchise_state = $('#franchise_state'+i).val();
      var franchise_district = $('#franchise_district'+i).val();
      var franchise_name = $('#franchise_name'+i).val();
      var franchise_mobile_number = $('#franchise_mobile_number'+i).val();
      var franchise_email_id = $('#franchise_email_id'+i).val();
      var username = $('#username'+i).val();
      var group_id = '7';
      var redirect_url = 'franchise/create_regional_office_login';
      $.ajax({
         url :'<?php echo site_url('franchise/save_franchise_login') ?>',
         type:'post',
         data:{'franchise_country':franchise_country,'franchise_state':franchise_state,'franchise_district':franchise_district,'franchise_name':franchise_name,'franchise_mobile_number':franchise_mobile_number,'franchise_email_id':franchise_email_id,'username':username,'group_id':group_id,'redirect_url':redirect_url},
         success:function(data){
            location.reload();
         }
      });
   }
   function user_active_inactive(stngId, value) {
      $.ajax({
          url: '<?php echo site_url('franchise/update_user_active_status'); ?>',
          type: "post",
          data:{'stngId':stngId, 'value':value},
          success: function (data) {
              if (data.length=='1') {
                  location.reload();
              }else{
                  location.reload();
              }
          },
          error: function (err) {
              console.log(err);
          }
      });
   }

</script>