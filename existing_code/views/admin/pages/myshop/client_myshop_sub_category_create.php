 <ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Create Sub Category</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title"><?php echo strtoupper($client_cat->category) ?></h3>
   </div>
   <div class="panel-body">
      <div class="row">
       <form enctype="multipart/form-data" method="post" id="category-form" action="<?php echo site_url('client_controller/insert_sub_cateogyr_by_clienty/'.'Myshop') ?>" class="form-horizontal" data-parsley-validate > 
         <input type="hidden" name="shop_type" value="<?php echo $shop_type ?>">
         <input type="hidden" name="client_shop_id" value="<?php echo $client_shop_id ?>">
         <input type="hidden" name="client_category_id" value="<?php echo $client_category_id ?>">
            <div class="panel-body">
             <div class="form-group">
               <div class="col-md-8">
                  <div class="col-md-5">
                     <input type="text" id="sub_category" placeholder="Enter Sub Category"  class="form-control" name="sub_category" >
                  </div>
                  <div class="col-md-2">
                     <button type="submit" class="btn btn-primary" >Submit</button>
                  </div>
               </div>
             </div>

            </div>
         </form>
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th>#</th>
                  <th>Sub Category Name</th>
               </tr>
            </thead>
          
            <tbody>
               <?php 
               $i=1;
                  foreach ($client_sub_cat as $key => $val) { ?>
                  <tr>
                     <td><?php echo $i++; ?></td>
                     <td><?php echo $val->sub_category ?></td>
                  </tr>
               <?php } ?>
            </tbody>
         </table>
      </div>
   </div>
</div>