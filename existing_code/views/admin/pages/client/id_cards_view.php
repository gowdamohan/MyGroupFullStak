<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">ID Cards List</h3>         
    </div>
    <div class="panel-body">
       <div class="view_id_card">
       </div>
    </div>
    <div class="panel-body table-responsive">  
        <div id="printArea">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Full Name</th>
                        <th>Mobile Number</th>
                        <th>ID Number</th>
                        <th>Validity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1; foreach ($member_view as $key => $val) { ?>
                    <tr>
                        <td><a onclick="view_id_card_by_id('<?php echo $val->id ?>')" href="javascript:void(0)">View</a></td>
                        <td><?php echo $i++ ?></td>
                        <td>
                            <?php if ($val->member_photo !='') { ?>
                                <img class="img-responsive" style="width: 40px;border-radius: 50%;height: 40px;" src="<?php echo $this->filemanager->getFilePath($val->member_photo) ?>">
                            <?php }else{
                                echo "No Photo";
                            } ?>
                           
                        </td>
                        <td><?php echo $val->full_name ?></td>
                        <td><?php echo $val->mobile_number ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>  
        </div>
                    
    </div>
</div>

<script type="text/javascript">
    function view_id_card_by_id(id) {
        $.ajax({
            url: '<?php echo site_url('client_controller/view_id_card_by_id'); ?>',
            type: 'post',
            data: {'id':id},
            success: function(data) {
              console.log(data);
              var card = JSON.parse(data);
              $('.view_id_card').html(card);
            }
        });
    }
</script>

<style type="text/css">
    .view_id_card img{
        height: 100px;
    }
    .memberPhoto{
       position: absolute;
       width: 40px;
       top: 67px;
       left: 57px;
       border-radius: 3px;
       height: 46px !important;
    }
    #fullname{
        font-size: 8px;
        padding: 0;
        margin: 0;
        height: 0px;
    }
    #designation{
        font-size: 6px;
        padding: 0;
        margin: 0;
        height: 0px;
    }
    #medianame{
        font-size: 6px;
        padding: 0;
        margin: 0;
        height: 0px;
    }
    #place{
        font-size: 5px;
        padding: 0;
        margin: 0;
        height: 0px;
    }
    #idcarNumber{
        font-size: 5px;
        padding: 0;
        margin: 0;
        height: 0px;
    }
    #validatity{
        font-size: 5px;
        padding: 0;
        margin: 0;
        height: 0px;
    }
</style>