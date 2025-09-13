<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Media</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">My Channel List</h3>
   </div>
   <div class="panel-body">
      <div class="row">
         <table class="table table-bordered">
            <thead>
               <th>#</th>
               <th>Logo</th>
               <th>Name</th>
               <th>Subscription</th>
               <th>Ratings</th>
               <th>Validate</th>
               <th>Action</th>
            </thead>
            <tbody>
               <?php $i=1; foreach ($channel_list as $key => $val) { ?>
                  <tr>
                     <td><?php echo $i++; ?></td>
                     <td><img width="50px" class="img-responsive" src="<?php echo $this->filemanager->getFilePath($val->img_path) ?>"></td>
                     <td><?php echo $val->media_name ?></td>
                     <td></td>
                     <td></td>
                     <td></td>
                     <td><a class="btn btn-primary" href="<?php echo site_url('Client_controller/create_each_channel_list/'.$val->media_type) ?>">View</a></td>
                  </tr>
               <?php } ?>
               
            </tbody>
         </table>
   
      </div>
   </div>
</div>

<style type="text/css">
   .widget.widget-padding-sm, .widget.widget-item-icon{
      width: 70%;
   }
   .widget.widget-item-icon .widget-item-left, .widget.widget-item-icon .widget-item-right{
      padding: 0px 0px;
   }
   .widget{
       min-height: 60px;
   }
   .widget .widget-item-left .fa, .widget .widget-item-right .fa, .widget .widget-item-left .glyphicon, .widget .widget-item-right .glyphicon{
      font-size: 34px;
   }
   .widget .widget-title{
       font-size: 15px;
   }
</style>