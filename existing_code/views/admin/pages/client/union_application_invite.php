<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Union Application</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="form-group">
                <button type="button" onclick="onclick_received_application()" class="btn btn-success btn-rounded">Recived Application</button>
                <button type="button" onclick="onclick_invite()" class="btn btn-info btn-rounded">invite</button>
                <a class="btn btn-default btn-rounded" href="<?php echo site_url('admin_controller/member_registration/'.'1') ?>" >Add New Members</a>
            </div>
        </div>
    </div>

    <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/insert_unions_invate_application_data/') ?>" id="unionInvate" action="#" class="form-horizontal" data-parsley-validate > 
        <div class="panel-body">
            <div class="col-md-4">
            <div class="form-group">
                <label class="control-label col-sm-4" for="regional_lang_name">Mobile Number</label>
                <div class="col-sm-8">
                  <input type="text" required name="mobile_number" onkeyup="checkMobileNumberExits()" id="mobile_number" class="form-control" >
                  <div id="exit_error" style="color: red;" ></div>
                </div>
            </div>
            </div>
            <div class="col-md-2">
                <button type="submit" id="btnSubmit" class="btn btn-primary">Send</button>
            </div>

        </div>
    </form>

    <div class="panel-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mobile Number</th>
                    <th>Created Date</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (!empty($invate_data)) {
                   $i=1; foreach ($invate_data as $key => $val) { ?>
                        <tr>
                            <td><?php echo $i++ ?></td>
                            <td><?php echo $val->mobile_number ?></td>
                            <td><?php echo date('d-m-Y', strtotime($val->created_date)) ?></td>
                            <td><a onclick="return confirm('Are you sure do you want delete ?')" href="<?php echo site_url('client_controller/deleted_invate_member/'.$val->id) ?>" class='btn btn-danger btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='Delete'><i class='fa fa-trash-o'></i></a></td>
                        </tr>
                    <?php } 
                } ?>
               
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    function onclick_invite() {
        window.location.href='<?php echo site_url('client_controller/get_invite_data/') ?>';
    }

    function onclick_received_application() {
        window.location.href='<?php echo site_url('client_controller/get_received_data/') ?>';
    }

    function checkMobileNumberExits() {
        var mobile_number = $('#mobile_number').val();
        $.ajax({
            url: '<?php echo site_url('client_controller/check_mobile_number_unique'); ?>',
            type: 'post',
            data: {'mobile_number':mobile_number},
            success: function(data) {
                console.log(data);
                if ($.trim(data) == 'exists') {
                    $('#exit_error').html("Mobile Number already Exists.");
                    $("#btnSubmit").prop('disabled',true);
                } else {
                    $('#exit_error').html("");
                    $("#btnSubmit").prop('disabled',false);
                }
            }
        });
    }
</script>