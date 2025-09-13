<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>God Timings</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Timings</h3>
   </div>
   <?php 
      $weekdays = array(
         'Sunday' => 'Sunday', 
         'Monday' => 'Monday', 
         'Tuesday' => 'Tuesday', 
         'Wednesday' => 'Wednesday', 
         'Thursday' => 'Thursday', 
         'Friday' => 'Friday', 
         'Saturday' => 'Saturday', 
      );
   ?>
   <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/insert_media_god_timings_morning') ?>"  class="form-horizontal"  data-parsley-validate >
      <div class="panel-body" style="border-bottom: 2px solid #ccc;">
         <center>
            <h1>Morning</h1>
         </center>
         <div class="form-group">
            <div class="col-sm-6 col-md-offset-2">
               <table class="no-bordered">
                  <thead>
                     <tr>
                        <th width="30%">Week</th>
                        <th width="34%">Open Time</th>
                        <th width="2%"></th>
                        <th width="34%">Close Time</th>
                     </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($weekdays as $day => $val) { ?>
                       <tr>
                           <th><?php echo $day ?></th>
                           <th>
                              <div class='input-group date' id='from_time' style="width:150px" >
                                 <input type='text'  name="from_time[<?php echo $day ?>]" value="<?php if (array_key_exists($day, $morning_god_timing)) {
                                   echo ($morning_god_timing[$day]->open_time == '00:00:00') ? '' : $morning_god_timing[$day]->open_time;
                                 } ?>" autocomplete="off" class="form-control from_time"/>
                                 
                              </div>
                           </th>
                           <th></th>
                           <th>
                              <div class='input-group date ' id='to_time' style="width:150px">
                                 <input type='text' name="to_time[<?php echo $day ?>]" value="<?php if (array_key_exists($day, $morning_god_timing)) {
                                    echo ($morning_god_timing[$day]->close_time == '00:00:00') ? '' : $morning_god_timing[$day]->close_time;
                                 } ?>" autocomplete="off" class="form-control to_time" />
                                
                              </div>
                           </th>
                        </tr>
                    <?php } ?> 
                  </tbody>
               </table>
            </div>
         </div>
         <center>
            <button type="submit" style="margin-top:2rem; margin-bottom:2rem;" class="btn btn-info">Save</button>
         </center>

      </div>
      
   </form>
   <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/insert_media_god_timings_evening') ?>"  class="form-horizontal"  data-parsley-validate >
      <div class="panel-body">
         <center>
            <h1>Evening</h1>
         </center>
         <div class="form-group">
            <div class="col-sm-6 col-md-offset-2">
               <table class="no-bordered">
                  <thead>
                     <tr>
                        <th width="30%">Week</th>
                        <th width="34%">Open Time</th>
                        <th width="2%"></th>
                        <th width="34%">Close Time</th>
                     </tr>
                  </thead>
                  <tbody>
                      <?php foreach ($weekdays as $day => $val) { ?>
                       <tr>
                           <th><?php echo $day ?></th>
                           <th>
                              <div class='input-group date ' style="width:150px"  id='from_time'>
                                 <input type='text' name="from_time[<?php echo $day ?>]" value="<?php if (array_key_exists($day, $evening_god_timing)) {
                                   echo ($evening_god_timing[$day]->open_time == '00:00:00') ? '' : $evening_god_timing[$day]->open_time;
                                 } ?>" autocomplete="off" class="form-control from_time" />
                              </div>
                           </th>
                           <th></th>
                           <th>
                              <div class='input-group date' style="width:150px" id='to_time'>
                                 <input type='text' name="to_time[<?php echo $day ?>]" value="<?php if (array_key_exists($day, $evening_god_timing)) {
                                    echo ($evening_god_timing[$day]->close_time == '00:00:00') ? '' : $evening_god_timing[$day]->close_time;
                                 } ?>" autocomplete="off" class="form-control to_time" />
                              </div>
                           </th>
                        </tr>
                    <?php } ?>
                   
                  </tbody>
               </table>
            </div>
         </div>
         <center>
            <button type="submit" style="margin-top:2rem; margin-bottom:2rem;" class="btn btn-info">Save</button>
         </center>
      </div>
   </form>
</div>

<script type="text/javascript">
    $(function () {
      $('.from_time, .to_time').datetimepicker({
         format: 'HH:mm',

         // format: 'LT'
      });
   });
</script>