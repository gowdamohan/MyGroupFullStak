<ul class="breadcrumb" id="parent_breadcums" style="background:#2d9bd1">
    <li style="color:#fff"><span class="fa fa-comments"></span>  Chat with us</li>
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
.content-frame{
    border: 2px solid #ccc;
    padding: 12px;
}
.page-container .page-content .content-frame {
    float: left;
    width: 100%;
    position: relative;
    background: #f5f5f5 url(../img/bg.png) left top repeat;
}

.page-container .page-content .content-frame .content-frame-top {
    float: left;
    width: 100%;
    line-height: 30px;
    padding: 13px 15px 15px;
    background: rgba(0, 0, 0, .02);
    border-bottom: 1px solid #DDD;
    border-top: 1px solid #DDD;
}
.page-container .page-content .content-frame .content-frame-body.content-frame-body-left {
    margin-left: 0;
    margin-right: 300px;
}
.page-container .page-content .content-frame .content-frame-body {
    padding: 70px 10px 10px 10px;
    margin-left: 300px;
}

.messages {
    width: 100%;
    float: left;
}
.panel.panel-default {
    border-top-color: #F5F5F5;
    border-top-width: 1px;
}
.messages .item.item-visible {
    opacity: 1;
    filter: alpha(opacity=100);
}
.messages .item {
    width: 100%;
    float: left;
    margin-bottom: 10px;
    opacity: 0;
    filter: alpha(opacity=0);
    -webkit-transition: all 200ms ease;
    -moz-transition: all 200ms ease;
    -ms-transition: all 200ms ease;
    -o-transition: all 200ms ease;
    transition: all 200ms ease;
}
.messages.messages-img .item .image {
    float: left;
    width: 40px;
}

.messages.messages-img .item .text {
    margin-left: 50px;
    position: relative;
}

.messages .item .text {
    background: #FFF;
    padding: 10px;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    border: 1px solid #D5D5D5;
}

.messages .item .text .heading {
    width: 100%;
    margin-bottom: 5px;
}

.messages.messages-img .item .text:after {
    border-color: rgba(255, 255, 255, 0);
    border-right-color: #FFF;
    /* border-width: 5px; */
    margin-top: -5px;
}
.messages.messages-img .item .text:after, .messages.messages-img .item .text:before {
    right: 100%;
    top: 20px;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
}

.messages.messages-img .item.in .image {
    float: right;
}

.messages.messages-img .item.in .text {
    margin-left: 0;
    margin-left: 50px;
}
.messages.messages-img .item.out .text {
    margin-left: 0;
    margin-right: 50px;
}
.messages .item .text .heading a {
    text-decoration: none;
    font-size: 12px;
    color: #1b1e24;
    font-weight: 600;
    line-height: 20px;
}
.messages .item .text .heading .date {
    float: right;
    line-height: 20px;
    font-size: 11px;
    color: #CCC;
    font-weight: 600;
}
.messages.messages-img .item .image img {
    border: 2px solid #F5F5F5;
    border-radius: 20%;
    width: 40px;
}
</style>

<div class="row" style="margin:0">
    <div class="container">
        <div class="content-frame mb-5" style="border-radius:11px" >                                    
            <div class="content-frame-body content-frame-body-left" style="height: auto">
                <div class="messages messages-img   mb-3">
                    <?php 
                      $user = $this->ion_auth->user()->row();
                    ?>
                    <input type="hidden" id="userIdUrl" value="<?php echo  (!empty($user)) ? $user->id : ''  ?>">
                    <?php foreach ($user_feedback as $key => $val) {
                        $firstLtter = substr($val->display_name, 0, 1);
                        $img ='';
                        if (!empty($val->profile_img)) {
                            if ($val->profile_img !='') {
                              $img = $this->filemanager->getFilePath($val->profile_img);
                            }
                        } 
                    ?>
                        <div class="item <?php echo ($val->in_out =='in')? 'in' : 'out' ?> item-visible">                              
                            <div class="text">
                                <?php echo $val->message ?>
                                 <div class="heading">
                                    <span class="date"><?php echo datE('Y-m-d h:i',strtotime($val->date)) ?></span>
                                </div>
                            </div>

                        </div>
                    <?php  } ?>
                </div>                        
                
                <div class="panel panel-default push-up-10">
                    <div class="panel-body panel-body-search">
                        <div class="input-group">
                            <input type="text" class="form-control" id="user_message" placeholder="Your message...">
                            <div class="input-group-btn">
                                <button onclick="send_message_to_admin()" id="enter_send" class="btn btn-default">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<style type="text/css">
.btn{
    /*width: 10%;*/
}
.push-up-10 {
    margin-top: 10px!important;
}

.panel.panel-default {
    border-top-color: #F5F5F5;
    border-top-width: 1px;
}
.messages.messages-img .item.in .text:nth-child(2n){
    background: #F6F6F6;
}

.messages.messages-img .item .text:nth-child(2n){
    background: #fff;
}


.input-group-addon, .input-group-btn {
    vertical-align: bottom;
}
 .btn-default {
    border-color: #DDD;
}
 panel {
    float: left;
    width: 100%;
    -moz-border-radius: 0;
    -webkit-border-radius: 0;
    border-radius: 0;
    border: 0;
    border-top: 2px solid #E5E5E5;
    margin-bottom: 20px;
    position: relative;
    -moz-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .2);
    -webkit-box-shadow: 0 1px 1px 0 rgb(0 0 0 / 20%);
    box-shadow: 0 1px 1px 0 rgb(0 0 0 / 20%);
}

</style>
<script type="text/javascript">
    $('#user_message').keypress(function(e){
      if(e.which == 13){
        send_message_to_admin();
      }
    });

    function send_message_to_admin() {
        var message = $('#user_message').val();
        $.ajax({
            url : '<?php echo site_url('home/send_user_message') ?>',
            type:'post',
            data :{'message':message},
            success:function(data){
                location.reload();
            }
        });
    }

</script>