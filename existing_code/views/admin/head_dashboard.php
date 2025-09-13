<ul class="breadcrumb">
  <li class="active">Dashboard</li>
</ul>
<?php 
  $users = $this->ion_auth->user()->row();
  $userid = $this->ion_auth->user()->row()->id;
  $groups = $this->ion_auth->get_users_groups($userid)->row()->name;
?>
<div class="page-content-wrap">


    <div class="row">

        <div class="col-md-3">
            <div class="widget widget-info widget-padding-sm" style="min-height: 90px;">
                <div class="widget-big-int plugin-clock">08<span>:</span>17</div>                            
                <div class="widget-subtitle plugin-date">Wednesday, October 12, 2022</div>           
            </div>
        </div>

        <div class="col-md-3">                            
            <div class="widget widget-default widget-item-icon" style="min-height: 90px;">
                <div class="widget-item-left" style="padding: 10px;margin-left: 0;margin-right: 0;" >
                    <span class="fa fa-user" style="font-size:45px" ></span>
                </div>
                <div class="widget-data">
                    <div class="widget-int num-count">375</div>
                    <div class="widget-title">Regional Office users</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">                            
            <div class="widget widget-default widget-item-icon" style="min-height: 90px;">
                <div class="widget-item-left" style="padding: 10px;margin-left: 0;margin-right: 0;" >
                    <span class="fa fa-user" style="font-size:45px"></span>
                </div>
                <div class="widget-data">
                    <div class="widget-int num-count">375</div>
                    <div class="widget-title">Branch Office users</div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <?php if ($groups == 'head_office') { ?>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading ui-draggable-handle">
                    <div class="panel-title-box">
                        <h3>Regional Office Ads </h3>
                        <span>Total Ads</span>
                    </div>
                </div>
                <div class="panel-body panel-body-table">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="50%">Name</th>
                                    <th width="20%">Status</th>
                                    <th width="30%">Activity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Karnataka</strong></td>
                                    <td><span class="label label-success">Full</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">100%</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Andhra Pradesh</strong></td>
                                    <td><span class="label label-warning">Not-Started</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">40%</div>
                                        </div>
                                    </td>
                                </tr>                                                
                                <tr>
                                    <td><strong>Delhi</strong></td>
                                    <td><span class="label label-danger">Partial</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 72%;">72%</div>
                                        </div>
                                    </td>
                                </tr>
                                 <tr>
                                     <td><strong>Kerala</strong></td>
                                    <td><span class="label label-success">Full</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">100%</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Bihar</strong></td>
                                    <td><span class="label label-warning">Not-Started</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">40%</div>
                                        </div>
                                    </td>
                                </tr>                                                
                                <tr>
                                    <td><strong>Madhya Pradesh</strong></td>
                                    <td><span class="label label-danger">Partial</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 72%;">72%</div>
                                        </div>
                                    </td>
                                </tr>
                                 <tr>
                                    <td><strong>Delhi</strong></td>
                                    <td><span class="label label-danger">Partial</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 72%;">72%</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                     <td><strong>Manipur</strong></td>
                                    <td><span class="label label-success">Full</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">100%</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Maharashtra</strong></td>
                                    <td><span class="label label-warning">Not-Started</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">40%</div>
                                        </div>
                                    </td>
                                </tr>                                                
                                <tr>
                                    <td><strong>Himachal Pradesh</strong></td>
                                    <td><span class="label label-danger">Partial</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 72%;">72%</div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>

         <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading ui-draggable-handle">
                    <div class="panel-title-box">
                        <h3>Branch Office Ads </h3>
                        <span>Total Ads</span>
                    </div>
                </div>
                <div class="panel-body panel-body-table">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="50%">Name</th>
                                    <th width="20%">Status</th>
                                    <th width="30%">Activity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>New Delhi</strong></td>
                                    <td><span class="label label-success">Full</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">100%</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Central Delhi</strong></td>
                                    <td><span class="label label-warning">Not-Started</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">40%</div>
                                        </div>
                                    </td>
                                </tr>                                                
                                <tr>
                                    <td><strong>North East Delhi</strong></td>
                                    <td><span class="label label-danger">Partial</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 72%;">72%</div>
                                        </div>
                                    </td>
                                </tr>
                                 <tr>
                                     <td><strong>North West Delhi</strong></td>
                                    <td><span class="label label-success">Full</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">100%</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Anantapur</strong></td>
                                    <td><span class="label label-warning">Not-Started</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">40%</div>
                                        </div>
                                    </td>
                                </tr>                                                
                                <tr>
                                    <td><strong>West Delhi</strong></td>
                                    <td><span class="label label-danger">Partial</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 72%;">72%</div>
                                        </div>
                                    </td>
                                </tr>
                                 <tr>
                                    <td><strong>Guntur</strong></td>
                                    <td><span class="label label-danger">Partial</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 72%;">72%</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                     <td><strong>Kadapa</strong></td>
                                    <td><span class="label label-success">Full</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">100%</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Kurnool</strong></td>
                                    <td><span class="label label-warning">Not-Started</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">40%</div>
                                        </div>
                                    </td>
                                </tr>                                                
                                <tr>
                                    <td><strong>Srikakulam</strong></td>
                                    <td><span class="label label-danger">Partial</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 72%;">72%</div>
                                        </div>
                                    </td>
                                </tr>
                                 <tr>
                                    <td><strong>Guntur</strong></td>
                                    <td><span class="label label-danger">Partial</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 72%;">72%</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                     <td><strong>Kadapa</strong></td>
                                    <td><span class="label label-success">Full</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">100%</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Kurnool</strong></td>
                                    <td><span class="label label-warning">Not-Started</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">40%</div>
                                        </div>
                                    </td>
                                </tr>                                                
                                <tr>
                                    <td><strong>Srikakulam</strong></td>
                                    <td><span class="label label-danger">Partial</span></td>
                                    <td>
                                        <div class="progress progress-small progress-striped active">
                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 72%;">72%</div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
      <?php } ?>

       <?php 
        
        if ($groups == 'regional' || $groups == 'branch') { ?>
        <div class="row" style="height: 450px; overflow: scroll;" >
          <?php 
          foreach ($franchise_ads as $key => $value) {
            if ($value->ads_name ==$groups) { ?>
             
                  <div class="col-md-8 col-md-offset-1">
                    <div class="panel panel-default">                            
                      <div class="panel-body panel-body-image">
                        <img style="width:50%" src="<?php echo $this->filemanager->getFilePath($value->imagepath) ?>" alt="">
                      </div>
                    </div>
                  </div>
            <?php }
          }
          ?>
        </div>
        <?php }
      ?> 

    </div>
</div>
<style type="text/css">
    .widget.widget-item-icon .widget-data{
            padding-left: 75px;
    }
</style>