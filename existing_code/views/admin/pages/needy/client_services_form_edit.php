<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li><a href="<?php echo site_url('client_controller/view_listservices/'.$edit_needy_service['service']->group_id);?>">Needy Services</a></li>
   <li><?php echo $edit_needy_service['service']->type ?> Services form edit</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title"><?php echo $edit_needy_service['service']->type ?> Services form edit</h3>
   </div>
   <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/update_needy_client_services_details/'.$edit_needy_service['service']->id) ?>" id="needy_client_service" action="#" class="form-horizontal" data-parsley-validate >
      <input type="hidden" name="type" value="<?php echo $edit_needy_service['service']->type ?>">
      <input type="hidden" name="group_id" value="<?php echo $edit_needy_service['service']->group_id ?>">
      <div class="panel-body">
         <div class="col-md-8 col-md-offset-1">
            <div class="form-group">
               <label class="control-label col-sm-4" for="country">Country</label>
              <div class="col-sm-6">
               <input type="text" readonly class="form-control" value="<?php echo $edit_needy_service['service']->country ?>" >
              </div>
            </div>

            <div class="form-group">
               <label class="control-label col-sm-4" for="state">State</label>
               <div class="col-sm-6">
                  <input type="text" readonly class="form-control" value="<?php echo $edit_needy_service['service']->district ?>" >
               </div>
            </div>

            <div class="form-group">
               <label class="control-label col-sm-4" for="district">Disctrict</label>
              <div class="col-sm-6">
                <input type="text" readonly class="form-control" value="<?php echo $edit_needy_service['service']->state ?>" >
              </div>
            </div>

               <div class="form-group">
                  <label class="control-label col-sm-4" for="no_of_days">Category <font color="red">*</font></label>
                  <div class="col-sm-6">
                     <input type="text" readonly class="form-control" value="<?php echo $edit_needy_service['service']->category ?>" >
                  </div>
               </div>

               <div class="form-group">
                  <label class="control-label col-sm-4" for="no_of_days">Consultancy charges <font color="red">*</font></label>
                  <div class="col-sm-3">
                     <input type="text" class="form-control" name="consultancy_charges_from" value="<?php echo $edit_needy_service['service']->consultancy_charges_from ?>" placeholder="From fees" >
                  </div>
                  <div class="col-sm-3">
                     <input type="text" class="form-control" name="consultancy_charges_to" value="<?php echo $edit_needy_service['service']->consultancy_charges_to ?>" placeholder="To fees" >
                  </div>
               </div>

               <div class="form-group">
                  <label class="control-label col-sm-4" for="no_of_days">Working time <font color="red">*</font></label>
                  <div class="col-sm-6">
                     <table class="no-bordered">
                        <thead>
                           <tr>
                              <th width="30%">Week</th>
                              <th width="34%">From Time</th>
                              <th width="2%"></th>
                              <th width="34%">To Time</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <th>Sunday</th>
                              <th>
                                 <div class='input-group date from_time'  id='from_time'>
                                    <input type='text' name="from_time[Sunday]" value="" class="form-control" />
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                 </div>
                              </th>
                              <th></th>
                              <th>
                                 <div class='input-group date to_time' id='to_time'>
                                    <input type='text' name="to_time[Sunday]"  value="<?php echo $edit_needy_service['time']['Sunday']->to_time ?>"  class="form-control" />
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                 </div>
                              </th>
                           </tr>
                           <tr>
                              <th>Monday</th>
                              <th>
                                 <div class='input-group date from_time'>
                                    <input type='text' name="from_time[Monday]"  value="<?php echo $edit_needy_service['time']['Monday']->from_time ?>"  class="form-control" />
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                 </div>
                              </th>
                              <th></th>
                              <th>
                                 <div class='input-group date to_time' >
                                    <input type='text' name="to_time[Monday]"  value="<?php echo $edit_needy_service['time']['Monday']->to_time ?>"  class="form-control" />
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                 </div>
                              </th>
                           </tr>
                           <tr>
                              <th>Tuesday</th>
                              <th>
                                 <div class='input-group date from_time'>
                                    <input type='text' name="from_time[Tuesday]"  value="<?php echo $edit_needy_service['time']['Tuesday']->from_time ?>"  class="form-control" />
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                 </div>
                              </th>
                              <th></th>
                              <th>
                                 <div class='input-group date to_time' >
                                    <input type='text' name="to_time[Tuesday]"  value="<?php echo $edit_needy_service['time']['Tuesday']->to_time ?>"  class="form-control" />
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                 </div>
                              </th>
                           </tr>
                           <tr>
                              <th>Wednesday</th>
                              <th>
                                 <div class='input-group date from_time'>
                                    <input type='text' name="from_time[Wednesday]"  value="<?php echo $edit_needy_service['time']['Wednesday']->from_time ?>"  class="form-control" />
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                 </div>
                              </th>
                              <th></th>
                              <th>
                                 <div class='input-group date to_time' >
                                    <input type='text' name="to_time[Wednesday]"  value="<?php echo $edit_needy_service['time']['Wednesday']->to_time ?>"  class="form-control" />
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                 </div>
                              </th>
                           </tr>
                           <tr>
                              <th>Thursday</th>
                              <th>
                                 <div class='input-group date from_time'>
                                    <input type='text' name="from_time[Thursday]"  value="<?php echo $edit_needy_service['time']['Thursday']->from_time ?>"  class="form-control" />
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                 </div>
                              </th>
                              <th></th>
                              <th>
                                 <div class='input-group date to_time' >
                                    <input type='text' name="to_time[Thursday]"  value="<?php echo $edit_needy_service['time']['Thursday']->to_time ?>"  class="form-control" />
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                 </div>
                              </th>
                           </tr>
                           <tr>
                              <th>Friday</th>
                              <th>
                                 <div class='input-group date from_time'>
                                    <input type='text' name="from_time[Friday]"  value="<?php echo $edit_needy_service['time']['Friday']->from_time ?>"  class="form-control" />
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                 </div>
                              </th>
                              <th></th>
                              <th>
                                 <div class='input-group date to_time' >
                                    <input type='text' name="to_time[Friday]"  value="<?php echo $edit_needy_service['time']['Friday']->to_time ?>"  class="form-control" />
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                 </div>
                              </th>
                           </tr>
                           <tr>
                              <th>Saturday</th>
                             <th>
                                 <div class='input-group date from_time'>
                                    <input type='text' name="from_time[Saturday]"  value="<?php echo $edit_needy_service['time']['Saturday']->from_time ?>"  class="form-control" />
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                 </div>
                              </th>
                              <th></th>
                              <th>
                                 <div class='input-group date to_time' >
                                    <input type='text' name="to_time[Saturday]"  value="<?php echo $edit_needy_service['time']['Saturday']->to_time ?>"  class="form-control" />
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                 </div>
                              </th>
                           </tr>

                        </tbody>
                        
                     </table>
                  </div>

               </div>

               <div class="form-group">
                  <label class="control-label col-sm-4" for="no_of_days">Address <font color="red">*</font></label>
                  <div class="col-sm-6">
                     <textarea class="form-control" name="address"><?php echo $edit_needy_service['service']->address ?></textarea>
                  </div>
               </div>
               <div class="form-group">
                  <label class="control-label col-sm-4" for="no_of_days">Area <font color="red">*</font></label>
                  <div class="col-sm-6">
                     <input type="text" class="form-control" value="<?php echo $edit_needy_service['service']->area ?>" name="area">
                  </div>
               </div>
               <div class="form-group">
                  <label class="control-label col-sm-4" for="no_of_days">Pincode <font color="red">*</font></label>
                  <div class="col-sm-6">
                     <input type="text" class="form-control" value="<?php echo $edit_needy_service['service']->pincode ?>" name="pincode">
                  </div>
               </div>
               <div class="form-group">
                  <label class="control-label col-sm-4" for="no_of_days">Details/Descriptions <font color="red">*</font></label>
                  <div class="col-sm-6">
                     <textarea class="form-control" name="descriptions"><?php echo $edit_needy_service['service']->descriptions ?></textarea>
                  </div>
               </div>
               <div class="form-group">
                  <label class="control-label col-sm-4" for="no_of_days">Name<font color="red">*</font></label>
                  <div class="col-sm-6">
                     <input type="text" class="form-control" value="<?php echo $edit_needy_service['service']->services_name ?>" name="services_name">
                  </div>
               </div>
               <div class="form-group">
                  <label class="control-label col-sm-4" for="no_of_days">Name (Regional Language)</label>
                  <div class="col-sm-6">
                     <input type="text" class="form-control" value="<?php echo $edit_needy_service['service']->name_regional_language ?>" name="name_regional_language">
                  </div>
               </div>
               <div class="form-group">
                  <label class="control-label col-sm-4" for="no_of_days">Contact Number</label>
                  <div class="col-sm-6">
                     <input type="text" class="form-control" value="<?php echo $edit_needy_service['service']->contact_number ?>" name="contact_number">
                  </div>
               </div>

               <div class="form-group">
                  <label class="control-label col-sm-4" for="no_of_days">Photo/Image <font color="red">*</font></label>
                  <div class="col-sm-6">
                     <div class="text-center">
                        <?php 
                        $picurl = base_url().'assets/back_end/img/icon/profile.png'; ?>
                        <img onclick="$('#fileupload').click();" class="img-responsive img-circle" id="img_photo" style="width:100px;height:100px;display:inline-block" src="<?php echo (empty($edit_needy_service['service']->photo)) ? $picurl : $this->filemanager->getFilePath($edit_needy_service['service']->photo); ?>">
                        <i onclick="$('#fileupload').click();" class="fa fa-pencil"></i>
                        <input hidden="hidden" type="file" id="fileupload" class="file" data-preview-file-type="jpeg" name="photo" accept="image/*">
                      </div>
                  </div>
               </div>  

            </div>
         </div>
         <div class="panel-footer">
            <center>
               <button class="btn btn-info" onclick="update_needy_client_form()" type="button" id="singleClick"> Update</button>
               <a class="btn btn-danger" href="<?php echo site_url('client_controller/create_services/'.$edit_needy_service['service']->id);?>">Cancel</a>
            </center>
         </div>
      </form>
   </div>

<div id="loader" class="loaderclass" style="display:none"></div>

<script type="text/javascript">
   function update_needy_client_form() {
      $('#singleClick').prop('disabled','disabled').html('Please wait..');
      document.getElementById('loader').style.display="block";
      $('#needy_client_service').submit();
   }
   $(function () {
      $('.from_time, .to_time').datetimepicker({
         format: 'LT'
      });
   });
</script>
<style type="text/css">
  
  .fa-pencil {
    padding: 8px;
    background: #6893ca;
    border-radius: 50%;
    margin-left: -10%;
    margin-bottom: 5%;
    vertical-align: bottom;
    color:white;
  }
  
  [hidden], template{
   display: none !important;
  }
</style>

<script type="text/javascript">
   $('#fileupload').change(function(){
    var src = $(this).val();
    // var isFileOk = validatePhoto(this.files[0])
    if(src && validatePhoto(this.files[0], 'fileupload')){
        $("#fileuploadError").html("");
        readURL(this);
    } else{
        this.value = null;
       }
   });

   function validatePhoto(file,errorId){
    if (file.size > 10000000 || file.fileSize > 10000000)
    {
       $("#"+errorId+"Error").html("Allowed file size exceeded. (Max. 10 MB)")
       return false;
    }
    if(file.type != 'image/jpeg' && file.type != 'image/jpg' && file.type != 'image/png') {
        $("#"+errorId+"Error").html("Allowed file types are jpeg, jpg and png");
        return false;
    }
    return true;
}

function readURL(input) {
   if (input.files && input.files[0]) {
     var reader = new FileReader();
     reader.onload = function (e) {
         $('#img_photo').attr('src', e.target.result);
     }

     reader.readAsDataURL(input.files[0]);
   }
}
</script>

<style type="text/css">
.loaderclass {
  border: 8px solid #eee;
  border-top: 8px solid #7193be;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  position: fixed;
  z-index: 1;
  animation: spin 2s linear infinite;
  margin-top: 60%;
  margin-left: 40%;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>