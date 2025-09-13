<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Live Link</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Live Link</h3>
   </div>
   <form enctype="multipart/form-data" method="post" class="form-horizontal" data-parsley-validate> 
      <div class="panel-body">
         <div class="col-md-6 col-md-offset-2">
            <div class="form-group">
               <label class="control-label col-sm-3" for="livelink">Live Link</label>
               <div class="col-sm-8">
                  <?php  if (!empty($live_link)) { ?>
                     <input type="text" class="form-control" value="<?php echo $live_link->url ?>" id="livelink" name="live_link">
                  <?php }else{ ?>
                     <input type="text" class="form-control" id="livelink" name="live_link">
                  <?php } ?>
                  
               </div>
            </div>
         </div>
      </div>
      <div class="panel-footer">
         <center><button type="button" onclick="uploadlive_links()" class="btn btn-primary">Save</button></center>
      </div>
   </form>
</div>

<script type="text/javascript">
function uploadlive_links() {
   $('#up-btn').html('Please wait ...').attr('disabled', 'disabled');
   var livelink = $('#livelink').val();
   $.ajax({
      url: '<?php echo site_url('client_controller/upload_mygodlivelink'); ?>',
      type: 'post',
      data: {'livelink':livelink},
      success: function(data) {
        location.reload();
      }
   });
}
</script>
