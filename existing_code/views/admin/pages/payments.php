<ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
    <li>Payments </li>
</ul>

<div class="panel panel-default">
     <div class="panel-heading">
        <h3 class="panel-title">Add Payments Purpose</h3>
     </div>
    <div class="panel-body">
        <form class="form-inline" method="post" role="form" action="<?php echo site_url('admin_controller/purpose_add') ?>">
            <div class="form-group">
                <label class="sr-only">Purpose</label>
                <input type="text" class="form-control" name="purpose" placeholder="Purpose">
            </div>                                   
            <button type="submit" class="btn btn-danger">Add</button>
        </form>
    </div>

    <div class="panel-body">
        <table class="table table-bordered datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Purpose</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; foreach ($purpose as $key => $val) { ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $val->purpose ?></td>
                        <td><a onclick="return confirm('Are you sure do you want delete ?')" href="<?php echo site_url('admin_controller/delete_payment_purpose/'.$val->id) ?>" class='btn btn-danger btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><i class='fa fa-trash-o'></i></a></td>
                    </tr>
                <?php } ?>
               
            </tbody>
        </table>
    </div>
</div>


<div class="panel panel-default">
     <div class="panel-heading">
        <h3 class="panel-title"> Payments Comments</h3>
     </div>
        <form class="form-inline" role="form" method="post" action="<?php echo site_url('admin_controller/purpose_comments_add') ?>">
            <div class="panel-body">
                <div class="form-group">
                    <label class="sr-only">Message</label>
                    <textarea class="summernote" name="payment_comments"><?php echo (!empty($comment)) ? $comment->payment_comments : ''  ?></textarea>
                </div>                                   
                
            </div>
            <div class="panel-footer">
                <center>
                    <button type="submit" class="btn btn-danger">Add</button>
                </center>
            </div>
        </form>
</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/plugins/summernote/summernote.js"></script>