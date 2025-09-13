<ul class="breadcrumb" id="parent_breadcums">
    <li> Feedback and Suggestions</li>
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

<div class="container" id="submitform">
    <div class="messages messages-img">
        <div class="form-group">
            <textarea class="form-control" id="feed_back_suggetions" rows="4" cols="2"  ></textarea>
        </div>
    </div>                        
    <div class="panel panel-default push-up-10">
        <div class="panel-body panel-body-search">
            <div class="input-group-btn">
                <center>
                    <button onclick="send_feedback_suggetionsto_admin()"  class="btn btn-info mb-3">Submit</button>
                </center>
            </div>
        </div>
    </div>
</div>
<div class="container" id="successform">
    <h4 class="text-info"></h4>
</div>


</style>
<script type="text/javascript">
 
    function send_feedback_suggetionsto_admin() {
        var feed_back_suggetions = $('#feed_back_suggetions').val();
        $.ajax({
            url : '<?php echo site_url('home/submit_feeback_suggetion') ?>',
            type:'post',
            data :{'feed_back_suggetions':feed_back_suggetions},
            success:function(data){
                if (data) {
                    $('#submitform').hide();
                    $('#successform').show();
                    $('.text-success').html('<b>Mygroup of company.</b><br>Thank you for your valuable feedback.');
                }
            }
        });
    }

</script>