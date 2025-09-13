<ul class="breadcrumb">
    <li><a href="#">Dashboard</a></li>
    <li>Feedback and Suggestions</li>
</ul>

<style type="text/css">
    .list-group-contacts .list-group-item .letter{
        /* border: 2px solid #F5F5F5; */
        -moz-border-radius: 20%;
        -webkit-border-radius: 20%;
        border-radius: 50%;
        width: 40px;
        margin-right: 10px;
        border: 1px solid #ccc;
        height: 40px;
        text-align: center;
        background: #038506;
        color: #fff;
        padding: 9px;
    }
    .list-group-contacts .list-group-item .letter span{
       font-size: 25px;
    }

.list-group-contacts .list-group-item.active{
    background: #95b75d;
}
.messages-img p{
  /* border: 2px solid #F5F5F5; */
    -moz-border-radius: 20%;
    -webkit-border-radius: 20%;
    border-radius: 50%;
    width: 40px;
    margin-right: 10px;
    border: 1px solid #ccc;
    height: 40px;
    text-align: center;
    background: #038506;
    color: #fff;
}
.messages-img p span{
    font-size: 25px;
}

</style>
<div class="content-frame">                                    
    <!-- START CONTENT FRAME TOP -->
    <div class="content-frame-top">                        
        <div class="page-title">                    
            <h2><span class="fa fa-comments"></span> Feedback and Suggestions</h2>
        </div>                                                                           
    </div>
    <div class="content-frame-right" style="display: block;">
        
        <div class="list-group list-group-contacts border-bottom push-down-10">
            <?php  foreach ($feebackGroup as $key => $val) { ?>
                <input type="hidden" name="current_user_id" id="current_userId" value="<?php echo $user_id ?>" >
                <a href="javascript:void(0)" onclick="onlick_get_user_chat(<?php echo $val->userId ?>)" class="list-group-item <?php if($val->userId ==$user_id) echo 'active' ?> ">                                 
                    <div class="list-group-status"></div>
                     <?php 
                        $firstLtter = substr($val->display_name, 0, 1);
                        $img ='';
                        if (!empty($val->profile_img)) {
                            if ($val->profile_img !='') {
                              $img = $this->filemanager->getFilePath($val->profile_img);
                            }
                        } ?>
                       
                  
                    <span class="contacts-title"><?php echo $val->display_name ?> <span class="label label-danger"><?php echo $val->countMessage ?></span></span>
                    <p><?php echo substr($val->message, 0,15).'...' ?></p>
                </a>   
            <?php } ?>  
        </div>
    </div>
   <form enctype="multipart/form-data" method="post" id="chat-userid"  class="form-horizontal">
    <input type="hidden" name="user_id" id="userId">
   </form>
<script type="text/javascript">
    function onlick_get_user_chat(user_id) {
        $('#userId').val(user_id);
        var url = '<?php echo site_url('admin_controller/feed_back_users') ?>';
        $('#chat-userid').attr('action',url);
        $('#chat-userid').submit();
    }
</script>
    <div class="content-frame-body content-frame-body-left" style="height: 1330px;">
        
        <div class="messages messages-img">
            <?php foreach ($user_feedback as $key => $val) {
                $firstLtter = substr($val->display_name, 0, 1);
                $img ='';
                if (!empty($val->profile_img)) {
                    if ($val->profile_img !='') {
                      $img = $this->filemanager->getFilePath($val->profile_img);
                    }
                } 
            ?>
                <div class="item <?php echo ($val->in_out =='out')? 'in' : '' ?> item-visible">
                                                  
                    <div class="text">
                        <div class="heading">
                            <!-- <a href="#"><?php // echo $val->display_name ?></a> -->
                            <span class="date"><?php echo datE('Y-m-d h:i',strtotime($val->date)) ?></span>
                        </div> 
                        <?php echo $val->message ?>
                    </div>
                </div>
            <?php  } ?>
        </div>                        
        
        <div class="panel panel-default push-up-10">
            <div class="panel-body panel-body-search">
                <div class="input-group">
                    <!-- <div class="input-group-btn">
                        <button class="btn btn-default"><span class="fa fa-camera"></span></button>
                        <button class="btn btn-default"><span class="fa fa-chain"></span></button>
                    </div> -->
                    <input type="text" class="form-control" id="admin_message" placeholder="Your message...">
                    <div class="input-group-btn">
                        <button onclick="send_message_to_user()" id="enter_send" class="btn btn-default">Send</button>

                    </div>
                </div>
            </div>
        </div>
        
    </div>

</div>

<style type="text/css">
.messages.messages-img .item.in .text:nth-child(2n){
    background: #F6F6F6;
}

.messages.messages-img .item .text:nth-child(2n){
    background: #fff;
}
 
</style>
<script type="text/javascript">
    $('#admin_message').keypress(function(e){
      if(e.which == 13){
        send_message_to_user();
      }
    });

    function send_message_to_user() {
        var message = $('#admin_message').val();
        var userId = $('#current_userId').val();
        $.ajax({
            url : '<?php echo site_url('admin_controller/send_user_message') ?>',
            type:'post',
            data :{'message':message,'userId':userId},
            success:function(data){
                location.reload();
            }
        });
    }

</script>