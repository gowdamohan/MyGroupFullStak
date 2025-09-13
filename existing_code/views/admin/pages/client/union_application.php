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
</div>

<script type="text/javascript">
    function onclick_invite() {
        window.location.href='<?php echo site_url('client_controller/get_invite_data/') ?>';
    }

    function onclick_received_application() {
        window.location.href='<?php echo site_url('client_controller/get_received_data/') ?>';
    }
</script>