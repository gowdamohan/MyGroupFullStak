<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Enabled Application for Public</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Enabled Application for Public</h3>
   </div>
	<div class="panel-body">
     <span style="text-align:center;" >
      <?php if($client_name->enabled_public_form == 1){ ?>
         <label class="switch">
            <input type="checkbox" onclick="partner_enabled_for_application_for_public('<?php echo $client_name->id ?>','0')" checked >
            <span></span>
         </label>
       <?php }else{ ?>
           <label class="switch">
               <input type="checkbox" onclick="partner_enabled_for_application_for_public('<?php echo $client_name->id ?>','1')" >
               <span></span>
           </label>
      <?php } ?>
   </div>
</div>

<script type="text/javascript">
      function partner_enabled_for_application_for_public(stngId, value) {
         $.ajax({
            url: '<?php echo site_url('client_controller/partner_enabled_for_application_for_public_update'); ?>',
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