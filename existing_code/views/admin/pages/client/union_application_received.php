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


    <div class="panel-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Recevied Date</th>
                    <th>Name</th>
                    <th>Date of Birth</th>
                    <th>Mobile Number</th>
                    <th>Email Id</th>
                    <th>Applied For</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (!empty($application)) {
                   $i=1; foreach ($application as $key => $val) { ?>
                        <tr>
                            <td><?php echo $i++ ?></td>
                            <td><?php echo date('d-m-Y',strtotime($val->created_date))  ?></td>
                            <td><?php echo $val->full_name ?></td>
                            <td><?php echo date('d-m-Y',strtotime($val->date_of_birth))  ?></td>
                            <td><?php echo $val->mobile_number ?></td>
                            <td><?php echo $val->email_id ?></td>
                            <td><?php echo $val->apply_for ?></td>
                            <td><a class="btn btn-info btn-md" href="<?php echo site_url('client_controller/receive_application_view_by_union/'.$val->id) ?>">View Application</a></td>
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
</script>