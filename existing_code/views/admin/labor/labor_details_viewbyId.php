<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Profile Details</h3>         
    </div>
   <?php 
      $users = $this->ion_auth->user()->row();
      
      $this->db->where('labor_mobile_number',$users->username);
      $query = $this->db->get('labor_account')->row();
      // $accountDetails = ['Add Category','Add Category1','Add Category2','Add Labors','Labors Details','Create Login','Attendance'];
      $accountDetails = [];
      if (!empty($query)) {
        $accountDetails = json_decode($query->account_details);
      }else{
        $accountDetails = ['Labors profile','Labors details'];
      }
      
    ?>
 
      <div class="panel-body table-responsive">
        <div class="col-md-6 col-md-offset-2">

           <div class="form-group">
            <label class="control-label col-sm-4">Labor ID Number <font color="red" >*</font></label>
            <div class="col-md-8">
              <input type="text" readonly  value="<?php echo $laborView->labor_id_number ?>" class="form-control" id="labor_id_number" >
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-4">Category <font color="red">*</font></label>
            <div class="col-md-8">
               <input type="text" readonly  value="<?php echo $laborView->category ?>" class="form-control" id="labor_name" >
            </div>
          </div>


          <div class="form-group">
            <label class="control-label col-sm-4">Labor Name <font color="red">*</font></label>
            <div class="col-md-8">
              <input type="text" readonly value="<?php echo $laborView->labor_name ?>" class="form-control" id="labor_name" >
            </div>
          </div>
          <?php if (in_array('Labors profile', $accountDetails)) { ?>
          <div class="form-group">
            <label class="control-label col-sm-4">Father/Husband Name</label>
            <div class="col-md-8">
              <input type="text" readonly value="<?php echo $laborView->father_husband_name ?>" class="form-control" id="father_husband_name" >
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-4">Date of Birth</label>
            <div class="col-md-8">
              <input type="text" readonly value="<?php echo $laborView->date_of_birth ?>" class="form-control" id="mobile_number" >
            </div>
          </div>

         

          <div class="form-group">
            <label class="control-label col-md-4">Blood Group </label>
            <div class="col-md-8">
               <input type="text" readonly value="<?php echo $laborView->blood_group ?>" required class="form-control" id="labor_name" >
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-4">Aadhar Number</label>
            <div class="col-md-8">
              <input type="text" readonly value="<?php echo $laborView->aadhar_number ?>" class="form-control" id="aadhar_number" >
            </div>
          </div>
         
          <div class="row">
             <label class="control-label col-sm-4">Aadhar</label>
              <div class="form-group imgUp col-md-4">
                <label class="control-label">Front Photo</label>
                  <div class="imagePreview" id="front_photo" style="margin-bottom: 0.5rem;">
                       <img style="width: 100%;height: 100%;" src="<?php echo $this->filemanager->getFilePath($laborView->aadhar_front_photo) ?>">
                  </div>
              </div>
            <div class="form-group imgUp col-md-4">
               <label class="control-label">Back Photo</label>
                <div class="imagePreview" id="back_photo" style="margin-bottom: 0.5rem;">
                   <img style="width: 100%;height: 100%;" src="<?php echo $this->filemanager->getFilePath($laborView->aadhar_back_photo) ?>">
                </div>
               
            </div>
          </div>
           <?php } ?>

          <div class="form-group">
            <label class="control-label col-sm-4">Photo</label>
            <div class="col-md-8 imgUp">
              <div class="imagePreview" id="labor_photo" style="margin-bottom: 0.5rem;">
                <img style="width: 100%;height: 100%;" src="<?php echo $this->filemanager->getFilePath($laborView->labor_photo) ?>">

              </div>
            </div>
          </div>
          <?php if (in_array('Labors profile', $accountDetails)) { ?>
          <div class="form-group">
            <label class="control-label col-sm-4">Address</label>
            <div class="col-md-8">
              <p style="margin-top: 2rem;font-size: 24px;"><?php echo $laborView->address ?></p>
            </div>
          </div>
          <?php } ?>

        </div>
      </div>
     
</div>