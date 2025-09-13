<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">About</h3>         
        <ul class="panel-controls">
            <li><a href="<?php echo site_url('myads/add_about') ?>" class="control-primary"><span class="fa fa-plus"></span></a></li>
        </ul>
    </div>
    <div class="panel-body table-responsive">                                
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Content</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; foreach ($about as $key => $val) { ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $val->title ?></td>
                        <td><img width="50px" class="img-responsive" src="<?php echo $this->filemanager->getFilePath($val->image) ?>"></td>
                        <td><?php echo substr($val->content, 0,200)  ?></td>
                        <th>
                            <a href="<?php echo site_url('myads/edit_about_us/'.$val->id) ?>" class="btn btn-warning btn-xs mrg" data-placement="top" data-toggle="tooltip"  data-original-title="Edit"><i class='fa fa-edit'></i></a>
                            <a onclick="return confirm('Are you sure do you want delete ?')" href="<?php echo site_url('myads/deleted_about_us/'.$val->id) ?>" class='btn btn-danger btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><i class='fa fa-trash-o'></i></a>
                        </th>
                    </tr>
                <?php } ?>
            </tbody>
        </table>              
    </div>
</div>