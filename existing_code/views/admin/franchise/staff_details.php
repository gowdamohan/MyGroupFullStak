<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Franchise Staff Details</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">Franchise Staff Details</h3>
      <ul class="panel-controls" style="float:right">
         <li>
         <a  href="<?php echo site_url('franchise/create_franchise_staff');?>" data-placement="top" data-toggle="tooltip" data-original-title="Create a Staff" class="control-primary"><span class="fa fa-plus"></span></a>&nbsp; &nbsp;
         </li>
      </ul>
   </div>

   <div class="panel-body">
      <?php 
         if (!empty($franchise_staff)) { ?>
            <table class="table table-bordered">
               <thead>
                  <tr>
                     <th>#</th>
                     <th>Name</th>
                     <th>Phone Number</th>
                     <th>Email Id</th>
                     <th>Address</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php 
                  $i = 1;
                     foreach ($franchise_staff as $key => $val) { ?>
                        <tr>
                           <td><?php echo $i++; ?></td>
                           <td><?php echo $val->first_name ?></td>
                           <td><?php echo $val->phone ?></td>
                           <td><?php echo $val->email ?></td>
                           <td><?php echo $val->address ?></td>
                           <td>
                              <a  class="btn btn-info btn-sm" href="<?php echo site_url('franchise/upload_franchise_staff_documents/'.$val->id) ?>">Upload Documents</a>
                              <a onclick="return confirm('Are you sure do you want delete ?')" class="btn btn-danger btn-sm" href="<?php echo site_url('franchise/delete_franchise_staff_by_id/'.$val->id) ?>">Delete</a>
                           </td>
                        </tr>
                     <?php }
                  ?>
               </tbody>
            </table>
         <?php }else{ ?>
               <h2>Result not found</h2>
         <?php }
      ?>
      
   </div>
</div>