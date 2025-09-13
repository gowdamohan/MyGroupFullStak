<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title"><?php echo str_replace('_', ' ', $pagename) ?></h3>         
        <ul class="panel-controls">
            <li><a href="<?php echo site_url('admin_controller/add_page/'.$pagename) ?>" class="control-primary"><span class="fa fa-plus"></span></a></li>
        </ul>
    </div>
    <div class="panel-body table-responsive">
    <?php
    switch ($pagename) {
        case 'clients':
            $titileStyle ='style="display:none"';
            $Image ='style=""';
            $Contnent ='style="display:none"';
            $tagline ='style="display:none"';
            break;
        case 'milestones':
            $titileStyle ='style="display:"';
            $Image ='style="display:none"';
            $Contnent ='style="display:"';
            $tagline ='style="display:none"';
            break;
        case 'testimonials':
            $titileStyle ='style="display:"';
            $Image ='style="display:"';
            $Contnent ='style="display:"';
            $tagline ='style="display:"';
            break;
        default:
            $titileStyle ='style="display:"';
            $Image ='style="display:"';
            $Contnent ='style="display:"';
            $tagline ='style="display:none"';
            break;
    }

     ?>                                
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th <?php echo $titileStyle ?> >Title</th>
                    <th <?php echo $tagline ?> >Tag Line</th>
                    <th <?php echo $Image ?>>Image</th>
                    <th <?php echo $Contnent ?>>Content</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; foreach ($data as $key => $val) { ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td <?php echo $titileStyle ?> ><?php echo $val->title ?></td>
                        <td <?php echo $tagline ?> ><?php echo $val->tag_line ?></td>
                        <td <?php echo $Image ?> ><img width="50px" class="img-responsive" src="<?php echo $this->filemanager->getFilePath($val->image) ?>"></td>
                        <td  <?php echo $Contnent ?>  ><?php echo $val->content  ?></td>
                        <th>
                            <a href="<?php echo site_url('admin_controller/edit_page/'.$val->id.'/'.$pagename) ?>" class="btn btn-warning btn-xs mrg" data-placement="top" data-toggle="tooltip"  data-original-title="Edit"><i class='fa fa-edit'></i></a>
                            <a onclick="return confirm('Are you sure do you want delete ?')" href="<?php echo site_url('admin_controller/deleted_page/'.$val->id.'/'.$pagename) ?>" class='btn btn-danger btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><i class='fa fa-trash-o'></i></a>
                        </th>
                    </tr>
                <?php } ?>
            </tbody>
        </table>              
    </div>
</div>