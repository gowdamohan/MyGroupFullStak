<ul class="breadcrumb">
  <li><a href="#">Home</a></li>                    
  <li class="active">Dashboard</li>
</ul>

<div class="page-content-wrap">
    <div class="row">
      <div class="col-md-12">
        <?php if (!empty($check_user_active)) { ?>
          <div class="error-container">
            <div class="error-code"><?php ($check_user_active->status == 1)? 'Active':'Inactive' ?></div>
            <div class="error-text"><?php ($check_user_active->status == 1)? '':'Options Not Allowed' ?> </div>
            <?php if ($check_user_active->status == 0) { ?>
              <div class="error-subtext">Thank you for registration of Mygroup of company . Our team will be verify and activate within 24 hours. In Case facing issue please contact admin</div>
              <div class="error-actions">                                
                <div class="row">
                  <div class="col-md-6">
                    <button class="btn btn-info btn-block btn-lg" onclick="<?php echo site_url('dashboard') ?>">Back to dashboard</button>
                  </div>
                </div>                                
              </div>
            <?php } ?>
            
           
          </div>
        <?php } ?>
       
      </div>
    </div>
    <div class="row" style="height: 600px; overflow: scroll;">
     <?php 
    $userid = $this->ion_auth->user()->row()->id;
    $groups = $this->ion_auth->get_users_groups($userid)->row()->name;
    if ($groups == 'regional') { ?>
      <?php 
      foreach ($franchise_ads as $key => $value) {
        if ($value->ads_name ==$groups) { ?>
          <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">                            
              <div class="panel-body panel-body-image">
                <img style="width:50%" src="<?php echo $this->filemanager->getFilePath($value->imagepath) ?>" alt="">
              </div>
            </div>
          </div>
        <?php }
      }
      ?>
     
    <?php }
  ?>     
  </div>                                        
  </div>