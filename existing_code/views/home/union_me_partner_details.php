<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div style="border: 2px solid green; border-radius: 26px;" >
	<div class="container" style="border-bottom: 1px solid #28a745;">
		<div class="row">
			<div class="col-2" style="margin-top:1rem">
				<?php 
				$srcPath = base_url().'assets/client_bg.png';
				if (!empty($partner_details->client_logo)) {
					$srcPath = $this->filemanager->getFilePath($partner_details->client_logo);
				} ?>

				<?php 
				$memberSrcPath = base_url().'assets/client_bg.png';
				if (!empty($members_data->member_photo)) {
					$memberSrcPath = $this->filemanager->getFilePath($members_data->member_photo);
				} ?>

				<img class="rounded-circle" style="width: 40px; height:40px" src="<?php echo $srcPath ?>">
			</div>
			<div class="col-10">
				<h5 class="text-center mt-2"><?php echo $partner_details->name_of_the_organization ?></h5>
	  		<h5 class="text-center"><?php echo $partner_details->regional_lang_name ?></h5>
			</div>
		</div>
	</div>
 	
 	<div class="container" style="border-bottom: 1px solid #28a745;">
		<div class="row">
			<div class="col-2" style="margin-top:1rem">
				<img class="rounded-circle" style="width: 40px; height:40px" src="<?php echo $memberSrcPath ?>">
			</div>
			<div class="col-10">
				<h5 class="text-center mt-2"><?php echo $members_data->full_name ?></h5>
	  		<h6 class="text-center">ID: <?php echo $members_data->member_id_number ?></h6>
	  		<h6 class="text-center">Mobile: <?php echo $members_data->mobile_number ?></h6>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
	      <div class="col-4 text-center">
	        <a href="#" class="h4 font-bolder mb-0">25</a>
	        <span class="d-block text-sm">Posts</span>
	      </div>
	      <div class="col-4 text-center">
	        <a href="#" class="h4 font-bolder mb-0">350</a>
	        <span class="d-block text-sm">Following</span>
	      </div>
	      <div class="col-4 text-center">
	        <a href="#" class="h4 font-bolder mb-0">1.5K</a>
	        <span class="d-block text-sm">Followers</span>
	      </div>
		</div>
	</div>

</div>

<script type="text/javascript">
	
	function get_button_group_page(val) {
		if (val == 'union') {
			$('#chats').hide();
			$('#posts').hide();
			$('#docs').hide();
			$('#meetings').hide();
		}else if(val == 'chats'){
			$('#chats').show();
			$('#posts').hide();
			$('#docs').hide();
			$('#meetings').hide();
    	$(this).addClass('active');
		}else if(val == 'posts'){
			$('#chats').hide();
			$('#posts').show();
			$('#docs').hide();
			$('#meetings').hide();
		}else if(val == 'docs'){
			$('#chats').hide();
			$('#posts').hide();
			$('#docs').show();
			$('#meetings').hide();
		}else if(val == 'meetings'){
			$('#chats').hide();
			$('#posts').hide();
			$('#docs').hide();
			$('#meetings').show();
		}else{
			$('#chats').hide();
			$('#posts').hide();
			$('#docs').hide();
			$('#meetings').hide();
		}
	}
</script>

<div class="align-items-center justify-content-center py-3" id="chats" style="display: none;">
	<div class="btn-group" role="group" aria-label="Basic example" style="width: 100%;" >
	  <button type="button" class="btn btn-secondary">Admin</button>
	  <button type="button" class="btn btn-secondary">Leaders</button>
	  <button type="button" class="btn btn-secondary">Directors</button>
	  <button type="button" class="btn btn-secondary">Member group</button>
	  <button type="button" class="btn btn-secondary">My Chats</button>
	</div>
</div>

<div class="align-items-center justify-content-center py-3" id="posts">
	<div class="btn-group" role="group" aria-label="Basic example" style="width: 100%;">
	  <button type="button" onclick="get_union_post_data('all')" class="btn btn-secondary active2">All</button>
	  <button type="button" onclick="get_union_post_data('admin')" class="btn btn-secondary">Admin</button>
	  <button type="button" onclick="get_union_post_data('followers')" class="btn btn-secondary">Followers</button>
	  <button type="button" onclick="get_union_post_data('my_post')" class="btn btn-secondary">My Posts</button>
	</div>
</div>

<div class="align-items-center justify-content-center py-3" id="docs" style="display: none;" >
	<div class="btn-group" role="group" aria-label="Basic example" style="width: 100%;">
	  <button type="button" class="btn btn-secondary">ID</button>
	  <button type="button" class="btn btn-secondary">Certificate</button>
	  <button type="button" class="btn btn-secondary">Letters</button>
	  <button type="button" class="btn btn-secondary">V Card</button>
	  <button type="button" class="btn btn-secondary">Receipts</button>
	</div>
</div>

<div class="align-items-center justify-content-center py-3" id="meetings" style="display: none;" >
	<div class="btn-group" role="group" aria-label="Basic example" style="width: 100%;">
	  <button type="button" class="btn btn-secondary">Schedule</button>
	  <button type="button" class="btn btn-secondary">Live</button>
	  <button type="button" class="btn btn-secondary">History</button>
	  <button type="button" class="btn btn-secondary">Alert</button>
	</div>
</div>

<div class="container" id="post_view_all" >
	<div class="row">

		<!-- post1-->
	 	<div class="col-sm-12">
      <div class="panel panel-white post">
      	<div class="post-heading">
          <div class="pull-left image">
            <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="img-circle avatar" alt="user profile image">
          </div>
          <div class="pull-left meta">
            <div class="title h5">
              <a href="#"><b>John Doe</b></a>
              made a post.
            </div>
            <h6 class="text-muted time">1 minute ago</h6>
          </div>
      	</div>
  		 	<div class="post-description"> 
          <p>Anyone who is interested in helping out with the Mark &amp; Markson dinner party, please let me know before the week is over.</p>

          <div class="stats">
            <a href="javascript:void(0);" class="btn btn-default stat-item">
              <i class="fa fa-thumbs-up icon"></i>2
            </a>
            <a href="javascript:void(0);" class="btn btn-default stat-item">
              <i class="fa fa-share icon"></i>12
            </a>
          </div>
        </div>
      

        <div class="post-footer">
          <div class="input-group"> 
            <input class="form-control" placeholder="Add a comment" type="text">
            <span class="input-group-addon">
              <a href="javascript:void(0);"><i class="fa fa-edit"></i></a>  
            </span>
          </div>
          <ul class="comments-list">
            <li class="comment">
              <a class="pull-left" href="javascript:void(0);">
                <img class="avatar" src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="avatar">
              </a>
              <div class="comment-body">
                <div class="comment-heading">
                  <h4 class="user">Gavino Free</h4>
                  <h5 class="time">5 minutes ago</h5>
                </div>
                <p>Sure, I'd help out.</p>
              </div>
              <ul class="comments-list">
                <li class="comment">
                  <a class="pull-left" href="javascript:void(0);">
                    <img class="avatar" src="https://bootdey.com/img/Content/avatar/avatar4.png" alt="avatar">
                  </a>
                  <div class="comment-body">
                    <div class="comment-heading">
                      <h4 class="user">John Doe</h4>
                      <h5 class="time">3 minutes ago</h5>
                    </div>
                    <p>I will email you the details.</p>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
    	<!-- post2-->
     <div class="col-sm-12">
        <div class="panel panel-white post">
          <div class="post-heading">
            <div class="pull-left image">
              <img src="https://bootdey.com/img/Content/avatar/avatar5.png" class="img-circle avatar" alt="user profile image">
            </div>
            <div class="pull-left meta">
              <div class="title h5">
                <a href="#"><b>Yanique Robinson</b></a>
                  shared a video.
              </div>
              <h6 class="text-muted time">1 minute ago</h6>
            </div>
          </div> 
          <div class="post-video">
            <div class="fluid-width-video-wrapper" style="padding-top: 56.2%;"><iframe src="https://player.vimeo.com/video/98417189" id="fitvid369523"></iframe></div>
          </div>
          <div class="post-description">  
            <div class="stats">
              <a href="javascript:void(0);" class="btn btn-default stat-item">
                <i class="fa fa-thumbs-up icon"></i>2
              </a>
              <a href="javascript:void(0);" class="btn btn-default stat-item">
                <i class="fa fa-share icon"></i>12
              </a>
            </div>
          </div>
          <div class="post-footer">
            <div class="input-group"> 
              <input class="form-control" placeholder="Add a comment" type="text">
              <span class="input-group-addon">
                <a href="javascript:void(0);"><i class="fa fa-edit"></i></a> 
              </span>
            </div> 
          </div>
        </div>
      </div>

	</div>
</div>

<div class="container" id="mypost" style="display: none;">
  <div class="row">
    <div class="col-12" style="padding: 0;">
      <div class="well well-sm well-social-post">
    		<form>
          <!-- <ul class="list-inline" id='list_PostActions'> -->
              <!-- <li class='active'><a href='#'>Update status</a></li> -->
             <!--  <li><a href='#'>Add photos/Video</a></li>
              <li><a href='#'>Create photo album</a></li> -->
          <!-- </ul> -->
          <textarea class="form-control" name="post_content" id="post_content" placeholder="What's in your mind?"></textarea>

          <div id="upload-Preview" >
                
        	</div>

          <ul class='list-inline post-actions'>
	        	<li>
							<div class="image-upload">
						    <label for="file-input">
				         <span class="fa fa-camera"></span>
						    </label>
						    <input multiple="" accept="image/png, image/jpeg, image/jpg" id="file-input" type="file"/>
							</div>
	        	</li>

           <!--  <li><a href="#" class='fa fa-user'></a></li>
            <li><a href="#" class='fa fa-map-marker'></a></li> -->
            <li class='pull-right'>
            	<input type="button" onclick="post_data_by_user_and_union()" id="post-button" class='btn btn-primary btn-sm' value="Post">
          	</li>
          </ul>
    		</form>
      </div>
    </div>

   	<div class="col-12" style="padding: 0;">
   		<div class="unions_my_post_list"></div>
		  <div id="my_post_modal-loader-content" style="display: none; text-align: center;">
		    <img src="<?php echo base_url('assets/loading-circle-gif.gif');?>" style="width:10%;">
		  </div>
   	</div>

  </div>

</div>
<script type="text/javascript">
function get_union_post_data(val) {
		if (val == 'my_post') {
			$('#mypost').show();
			$('#post_view_all').hide();
			get_mypost_data_unionwise();
		}else{
			$('#mypost').hide();
			$('#post_view_all').show();
		}
}
var union_my_post = [];
function get_mypost_data_unionwise() {
	$('.unions_my_post_list').html('');
  $('#my_post_modal-loader-content').show();
  var union_id = '<?php echo $partner_details->id ?>';
  union_member_my_post = [];
  $.ajax({
      url: '<?php echo site_url('myunions/get_mypost_data_unionwise_ids'); ?>',
      type: 'post',
      data: {'union_id':union_id},
      success: function(data) {
          var resData = JSON.parse(data);
          console.log(resData);
          if (resData.length > 0) {
              union_member_my_post = resData;
              callReportGetter(0);
          }else{
              $('.unions_my_post_list').html('<h3 style="text-align: center;margin-top: 3rem;" >No Post found</h3>');
              $('#my_post_modal-loader-content').hide();
          }

          // $('.missing_list').html(construct_missing_data(resData));
      }
  });

}

function callReportGetter(index){
  if(index < union_member_my_post.length) {
    getReport(index);
  } else {
    $('#my_post_modal-loader-content').hide();
  }
}
function getReport(index){
  var unions_post_ids = union_member_my_post[index];
  $.ajax({
    url: '<?php echo site_url('myunions/get_mypost_data_unionwise_data'); ?>',
    type: 'post',
    data:{'unions_post_ids':unions_post_ids},
    success: function(data) {
      var unionMyPostData = JSON.parse(data);

  	 	var sortedData = [];
  	  for (var i in unionMyPostData) 
  	  	sortedData.push(unionMyPostData[i]);
		  	sortedData.sort(function(a, b) {
		    return b.id - a.id ;
		  });
      constructUnionMyPostReport(index, sortedData);
    },
  });
}
function constructUnionMyPostReport(index, unionMyPostData) {
  $('.unions_my_post_list').append(construct_union_my_post_data(index, unionMyPostData));
  index++;
  callReportGetter(index);
}

function construct_union_my_post_data(index, unionMyPostData) {

	var htmlMypost = '';
	for(var mp in  unionMyPostData){
		htmlMypost += '<div class="panel panel-white post">';
		htmlMypost += '<div class="post-heading">';
		htmlMypost += '<div class="pull-left image">';
    htmlMypost += '<img src="'+unionMyPostData[mp].posted_photo+'" class="img-circle avatar" alt="user profile image">';
    htmlMypost += '</div>';
    htmlMypost += '<div class="pull-left meta">';
    htmlMypost += '<div class="title h5">';
    htmlMypost += '<a href="#"><b>'+unionMyPostData[mp].posted_name+'</b></a>';
    htmlMypost += '</div>';
    htmlMypost += '<h6 style="font-size:12px;" class="text-muted time">'+unionMyPostData[mp].date_time+'</h6>';
    htmlMypost += '</div>';
    htmlMypost += '</div>';
  	htmlMypost += '<div class="post-description">';
    htmlMypost += '<p>'+unionMyPostData[mp].content+'</p>';


   	htmlMypost += '<div id="post_carouselIndicators" class="carousel slide" data-ride="carousel" style="margin-top: 1rem;margin-bottom: 4px; ">';
		htmlMypost += '<ol class="carousel-indicators">';
		var images  = unionMyPostData[mp].images;
		var mg = 1;
		for (var img = 0; img < images.length; img++) {
			var active = '';
			if (mg == 1) {
				active = 'active';
			}
			htmlMypost += '<li  data-target="#post_carouselIndicators" data-slide-to="'+mg+'" class="'+active+'"></li>';
			mg++;
		}
		htmlMypost += ' </ol>';

		htmlMypost += '<div class="carousel-inner">';
		var mg1 = 1;
		for (var img1 = 0; img1 < images.length; img1++) {
			var active = '';
			if (mg1 == 1) {
				active = 'active';
			}
			htmlMypost += '<div class="carousel-item '+active+'">';
			htmlMypost += '<img class="d-block w-100" src="'+images[img1]+'" alt="First slide">';
			htmlMypost += '</div>';
			mg1++;
		}

		htmlMypost += '</div>';
		htmlMypost += '<a class="carousel-control-prev" href="#post_carouselIndicators" role="button" data-slide="prev">';
    htmlMypost += '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
    htmlMypost += '<span class="sr-only">Previous</span>';
    htmlMypost += '</a>';
    htmlMypost += '<a class="carousel-control-next" href="#post_carouselIndicators" role="button" data-slide="next">';
   	htmlMypost += '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
    htmlMypost += '<span class="sr-only">Next</span>';
    htmlMypost += '</a>';
		htmlMypost += '</div>';

    htmlMypost += '<div class="stats">';
    htmlMypost += '<a href="javascript:void(0);" class="btn btn-default stat-item">';

  	var likedUsers = 0;
  	var sharedUsers = 0;
  	var member_comment = [];
    if (typeof(unionMyPostData[mp].comments) !='undefined' && unionMyPostData[mp].comments !== null) {
    	likedUsers = unionMyPostData[mp].comments.like_user_id;
    	sharedUsers = unionMyPostData[mp].comments.shared_user_id;
    	 member_comment = unionMyPostData[mp].comments.comments;
    }
    htmlMypost += '<i class="fa fa-thumbs-up icon"></i>'+likedUsers+'';
    htmlMypost += '</a>';
    htmlMypost += '<a href="javascript:void(0);" class="btn btn-default stat-item">';
    htmlMypost += '<i class="fa fa-share icon"></i>'+sharedUsers+'';
    htmlMypost += '</a>';
    htmlMypost += '</div>';
    htmlMypost += '</div>';
    htmlMypost += '<div class="post-footer">';
    htmlMypost += '<div class="input-group">';
   	htmlMypost += '<input class="form-control" placeholder="Add a comment" type="text">';
		htmlMypost += '<span class="input-group-addon">';
    htmlMypost += '<a href="javascript:void(0);"><i class="fa fa-edit"></i></a>';
    htmlMypost += '</span>';
    htmlMypost += '</div>';
   	for (var mc = 0; mc < member_comment.length; mc++) {
			htmlMypost += '<ul class="comments-list">';
	   	htmlMypost += '<li class="comment">';
	   	htmlMypost += '<a class="pull-left" href="javascript:void(0);">';
	   	htmlMypost += '<img class="avatar" src="'+member_comment[mc].memberCommentsPhoto+'" alt="avatar">';
	   	htmlMypost += '</a>';
	   	htmlMypost += '<div class="comment-body">';
	   	htmlMypost += '<div class="comment-heading">';
	   	htmlMypost += '<h4 class="user">'+member_comment[mc].posted_name+'</h4>';
	   	htmlMypost += '<h5 class="time">'+member_comment[mc].comment_date_time+'</h5>';
	   	htmlMypost += '</div>';
	   	htmlMypost += '<p>'+member_comment[mc].member_comments+'</p>';
	   	htmlMypost += '</div>';
	             
	   	htmlMypost += '</li>';
	   	htmlMypost += '</ul>';
   	}
   

   	htmlMypost += '</div>';

		htmlMypost += '</div>';
	}
 	return htmlMypost;      
}

function post_data_by_user_and_union() {
	$('#post-button').val('Please wait ...').attr('disabled', 'disabled');
    var file_data = $('#file-input').prop('files');
    if (file_data.length === 0) { 
        alert('Please choose an image to upload');
        $('#up-btn').val('Upload').prop('disabled', false);
        return false; 
    }
    var images = $('#upload-Preview').find('img').map(function() { return this.src; }).get();
    var union_client_id = '<?php echo $partner_details->id ?>';
    var post_content = $('#post_content').val();
    var blobs = [];
    var form_data = new FormData();
    form_data.append('file[]', file_data);
    form_data.append('union_client_id', union_client_id);
    form_data.append('post_content', post_content);
    var total_images = 0;
    for(var i in images) {
        var image = images[i];
        var base64ImageContent = image.replace(/^data:image\/(png|jpg|jpeg);base64,/, "");
        var blob = base64ToBlob(base64ImageContent, 'image/png');
        form_data.append('file_name[]', blob);
        total_images++;
    }
    // console.log(form_data);
    // return false;
    $.ajax({
      url: '<?php echo site_url('myunions/post_member_data') ?>',
      type: 'post',
      data: form_data,
      cache: false,
      contentType: false,
      processData: false,
      success: function(data) {
          // console.log(data);
          var failed = parseInt(data);
          $("#upload-Preview").html(''); 
          $('#post_content').val('');
          $('#post-button').val('Post').removeAttr('disabled');
          if (failed != 0) {
            $(function(){
              new PNotify({
                title: 'Error',
                text: failed+' / '+total_images + ' failed to upload',
                type: 'error',
              });
            });
          }else {
            $(function(){
              new PNotify({
                title: 'Success',
                text: 'uploaded images successfully',
                type: 'success',
              });
            });
          }
        get_mypost_data_unionwise();
      }
    });
}
$(document).ready(function () {  
    $("#file-input").ImageResize({  
        maxWidth: 500,  
        onImageResized: function (imageData) { 
             $("#upload-Preview").append($("<img/>", { src: imageData }));  
             $("#up-btn").html('Upload').prop('disabled', false);
        }  
    });  
 });
 $.fn.ImageResize = function (options) { 
    
		var defaults = {  
			maxWidth: Number.MAX_VALUE,  
			maxHeigt: Number.MAX_VALUE,  
			onImageResized: null  
			}  
			var settings = $.extend({}, defaults, options);  

			var selector = $(this);
			selector.each(function (index) {  
			var control = selector.get(index);  
			if ($(control).prop("tagName").toLowerCase() == "input" && $(control).attr("type").toLowerCase() == "file") {  
			$(control).attr("accept", "image/*");  
			$(control).attr("multiple", "true");  
			control.addEventListener('change', handleFileSelect, false);  
			}  
			else {  
			cosole.log("Invalid file input field");  
			}  
			});  
			function handleFileSelect(event) {  
			//Check File API support  
			if (window.File && window.FileList && window.FileReader) {  
			var count = 0;  
			var files = event.target.files; 
			for (var i = 0; i < files.length; i++) {  
			var file = files[i];  
			//Only pics  
			if (!file.type.match('image')) continue;  
			var picReader = new FileReader();  
			picReader.addEventListener("load", function (event) {  
			var picFile = event.target;  
			  var imageData = picFile.result;  
			  var img = new Image();  
			  img.src = imageData;  
			  img.onload = function () { 
			      if (img.width > settings.maxWidth || img.height > settings.maxHeigt) {  
			          var width = settings.maxWidth;  
			          var height = settings.maxHeigt;  

			          if (img.width > settings.maxWidth) {  
			              width = settings.maxWidth;  
			              var ration = settings.maxWidth / img.width;  
			              height = Math.round(img.height * ration);  
			          }  

			          if (height > settings.maxHeigt) {  
			              height = settings.maxHeigt;  
			              var ration = settings.maxHeigt / img.height;  
			              width = Math.round(img.width * ration);  
			          }  

			          var canvas = $("<canvas/>").get(0);  
			          canvas.width = width;  
			          canvas.height = height;  
			          var context = canvas.getContext('2d');  
			          context.drawImage(img, 0, 0, width, height);  
			          imageData = canvas.toDataURL();  

			          if (settings.onImageResized != null && typeof (settings.onImageResized) == "function") {  
			              settings.onImageResized(imageData);  
			          }  
			      }  else {
			           var canvas = $("<canvas/>").get(0);  
			          canvas.width = img.width;  
			          canvas.height = img.height;  
			          var context = canvas.getContext('2d');  
			          context.drawImage(img, 0, 0, img.width, img.height);  
			          imageData = canvas.toDataURL();  
			          if (settings.onImageResized != null && typeof (settings.onImageResized) == "function") {  
			              settings.onImageResized(imageData);  
			          } 
			      }

			  }  
			  img.onerror = function () {  

			  }  
			});  
			//Read the image  
			picReader.readAsDataURL(file);  
			}  
			} else {  
			console.log("Your browser does not support File API");  
			}  
  	}
}  
function base64ToBlob(base64, mime) {
    mime = mime || '';
    // var sliceSize = 9000000;
    // var sliceSize = 500000;
    var sliceSize = 1024;
    var byteChars = window.atob(base64);
    // var byteChars = new Blob(base64, mime);
    var byteArrays = [];

    for (var offset = 0, len = byteChars.length; offset < len; offset += sliceSize) {
        var slice = byteChars.slice(offset, offset + sliceSize);

        var byteNumbers = new Array(slice.length);
        for (var i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }

        var byteArray = new Uint8Array(byteNumbers);

        byteArrays.push(byteArray);
    }

    return new Blob(byteArrays, {type: mime});
}
</script>

<style type="text/css">
	#upload-Preview img{
		width: 15%;
	}
	.file_post_upload > input{
		display: none;
	}

.image-upload > input
{
    display: none;
}

.image-upload img
{
    width: 80px;
    cursor: pointer;
}

	.list-inline>li {
    display: inline-block;
    padding-right: 5px;
    padding-left: 5px;
	}
	/* Component: Panel */
.panel {
  border-radius: 0;
  margin-bottom: 30px;
}
.panel.solid-color {
  color: white;
}
.panel .panel-heading {
  border-radius: 0;
  position: relative;
}
.panel .panel-heading > .controls {
  position: absolute;
  right: 10px;
  top: 12px;
}
.panel .panel-heading > .controls .nav.nav-pills {
  margin: -8px 0 0 0;
}
.panel .panel-heading > .controls .nav.nav-pills li a {
  padding: 5px 8px;
}
.panel .panel-heading .clickable {
  margin-top: 0px;
  font-size: 12px;
  cursor: pointer;
}
.panel .panel-heading.no-heading-border {
  border-bottom-color: transparent;
}
.panel .panel-heading .left {
  float: left;
}
.panel .panel-heading .right {
  float: right;
}
.panel .panel-title {
  font-size: 16px;
  line-height: 20px;
}
.panel .panel-title.panel-title-sm {
  font-size: 18px;
  line-height: 28px;
}
.panel .panel-title.panel-title-lg {
  font-size: 24px;
  line-height: 34px;
}
.panel .panel-body {
  font-size: 13px;
}
.panel .panel-body > .body-section {
  margin: 0px 0px 20px;
}
.panel .panel-body > .body-section > .section-heading {
  margin: 0px 0px 5px;
  font-weight: bold;
}
.panel .panel-body > .body-section > .section-content {
  margin: 0px 0px 10px;
}
.panel-white {
  border: 1px solid #dddddd;
}
.panel-white > .panel-heading {
  color: #333;
  background-color: #fff;
  border-color: #ddd;
}
.panel-white > .panel-footer {
  background-color: #fff;
  border-color: #ddd;
}
.panel-primary {
  border: 1px solid #dddddd;
}
.panel-purple {
  border: 1px solid #dddddd;
}
.panel-purple > .panel-heading {
  color: #fff;
  background-color: #8e44ad;
  border: none;
}
.panel-purple > .panel-heading .panel-title a:hover {
  color: #f0f0f0;
}
.panel-light-purple {
  border: 1px solid #dddddd;
}
.panel-light-purple > .panel-heading {
  color: #fff;
  background-color: #9b59b6;
  border: none;
}
.panel-light-purple > .panel-heading .panel-title a:hover {
  color: #f0f0f0;
}
.panel-blue,
.panel-info {
  border: 1px solid #dddddd;
}
.panel-blue > .panel-heading,
.panel-info > .panel-heading {
  color: #fff;
  background-color: #2980b9;
  border: none;
}
.panel-blue > .panel-heading .panel-title a:hover,
.panel-info > .panel-heading .panel-title a:hover {
  color: #f0f0f0;
}
.panel-light-blue {
  border: 1px solid #dddddd;
}
.panel-light-blue > .panel-heading {
  color: #fff;
  background-color: #3498db;
  border: none;
}
.panel-light-blue > .panel-heading .panel-title a:hover {
  color: #f0f0f0;
}
.panel-green,
.panel-success {
  border: 1px solid #dddddd;
}
.panel-green > .panel-heading,
.panel-success > .panel-heading {
  color: #fff;
  background-color: #27ae60;
  border: none;
}
.panel-green > .panel-heading .panel-title a:hover,
.panel-success > .panel-heading .panel-title a:hover {
  color: #f0f0f0;
}
.panel-light-green {
  border: 1px solid #dddddd;
}
.panel-light-green > .panel-heading {
  color: #fff;
  background-color: #2ecc71;
  border: none;
}
.panel-light-green > .panel-heading .panel-title a:hover {
  color: #f0f0f0;
}
.panel-orange,
.panel-warning {
  border: 1px solid #dddddd;
}
.panel-orange > .panel-heading,
.panel-warning > .panel-heading {
  color: #fff;
  background-color: #e82c0c;
  border: none;
}
.panel-orange > .panel-heading .panel-title a:hover,
.panel-warning > .panel-heading .panel-title a:hover {
  color: #f0f0f0;
}
.panel-light-orange {
  border: 1px solid #dddddd;
}
.panel-light-orange > .panel-heading {
  color: #fff;
  background-color: #ff530d;
  border: none;
}
.panel-light-orange > .panel-heading .panel-title a:hover {
  color: #f0f0f0;
}
.panel-red,
.panel-danger {
  border: 1px solid #dddddd;
}
.panel-red > .panel-heading,
.panel-danger > .panel-heading {
  color: #fff;
  background-color: #d40d12;
  border: none;
}
.panel-red > .panel-heading .panel-title a:hover,
.panel-danger > .panel-heading .panel-title a:hover {
  color: #f0f0f0;
}
.panel-light-red {
  border: 1px solid #dddddd;
}
.panel-light-red > .panel-heading {
  color: #fff;
  background-color: #ff1d23;
  border: none;
}
.panel-light-red > .panel-heading .panel-title a:hover {
  color: #f0f0f0;
}
.panel-pink {
  border: 1px solid #dddddd;
}
.panel-pink > .panel-heading {
  color: #fff;
  background-color: #fe31ab;
  border: none;
}
.panel-pink > .panel-heading .panel-title a:hover {
  color: #f0f0f0;
}
.panel-light-pink {
  border: 1px solid #dddddd;
}
.panel-light-pink > .panel-heading {
  color: #fff;
  background-color: #fd32c0;
  border: none;
}
.panel-light-pink > .panel-heading .panel-title a:hover {
  color: #f0f0f0;
}
.panel-group .panel {
  border-radius: 0;
}
.panel-group .panel + .panel {
  margin-top: 0;
  border-top: 0;
}

/* Component: Posts */
.post .post-heading {
  height: 95px;
  padding: 20px 15px;
}
.post .post-heading .avatar {
  width: 60px;
  height: 60px;
  display: block;
  margin-right: 15px;
}
.post .post-heading .meta .title {
  margin-bottom: 0;
}
.post .post-heading .meta .title a {
  color: black;
}
.post .post-heading .meta .title a:hover {
  color: #aaaaaa;
}
.post .post-heading .meta .time {
  margin-top: 8px;
  color: #999;
}
.post .post-image .image {
  width: 100%;
  height: auto;
}
.post .post-description {
  padding: 15px;
}
.post .post-description p {
  font-size: 14px;
}
.post .post-description .stats {
  margin-top: 20px;
}
.post .post-description .stats .stat-item {
  display: inline-block;
  margin-right: 15px;
}
.post .post-description .stats .stat-item .icon {
  margin-right: 8px;
}
.post .post-footer {
  border-top: 1px solid #ddd;
  padding: 15px;
}
.post .post-footer .input-group-addon a {
  color: #454545;
}
.post .post-footer .comments-list {
  padding: 0;
  margin-top: 20px;
  list-style-type: none;
}
.post .post-footer .comments-list .comment {
  display: block;
  width: 100%;
  margin: 20px 0;
}
.post .post-footer .comments-list .comment .avatar {
  width: 35px;
  height: 35px;
}
.post .post-footer .comments-list .comment .comment-heading {
  display: block;
  width: 100%;
}
.post .post-footer .comments-list .comment .comment-heading .user {
  font-size: 14px;
  font-weight: bold;
  display: inline;
  margin-top: 0;
  margin-right: 10px;
}
.post .post-footer .comments-list .comment .comment-heading .time {
  font-size: 12px;
  color: #aaa;
  margin-top: 0;
  display: inline;
}
.post .post-footer .comments-list .comment .comment-body {
  margin-left: 50px;
}
.post .post-footer .comments-list .comment > .comments-list {
  margin-left: 50px;
}

.fluid-width-video-wrapper {
    width: 100%;
    position: relative;
    padding: 0;
}

.fluid-width-video-wrapper iframe, .fluid-width-video-wrapper object, .fluid-width-video-wrapper embed {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}
.btn-default{
	color: #333;
  background-color: #fff;
  border-color: #ccc;
}
.well-social-post {
    border-radius: 0;
    background-color: #ffffff;
    border: 1px solid #ddd;
    padding:0;
}

.well-social-post .glyphicon,
.well-social-post .fa,
.well-social-post [class^='icon-'],
.well-social-post [class*='icon-'] {
    font-weight: bold;
    color: #999999;
}

.well-social-post a,
.well-social-post a:hover,
.well-social-post a:active,
.well-social-post a:focus {
    text-decoration: none;
}

.well-social-post .list-inline {
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
}

.well-social-post .list-inline li {
    position: relative;
}

.well-social-post .list-inline li.active::after {
    position: absolute;
    display: block;
    width: 0;
    height: 0;
    content: "";
    top: 30px;
    left: 50%;
    left: -webkit-calc(50% - 5px);
    left: -moz-calc(50%-5px);
    left: calc(50% - 5px);
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 5px solid #dddddd;
}

.well-social-post .list-inline li.active a {
    color: #222222;
    font-weight: bold;
}

.well-social-post .form-control {
    width: 100%;
    min-height: 100px;
    border: none;
    border-radius: 0;
    box-shadow: none;
}

.well-social-post .list-inline {
    padding: 10px;
}

.well-social-post .list-inline li + li {
    margin-left: 10px;
}

.well-social-post .post-actions {
    margin: 0;
    background-color: #f6f7f8;
    border-top-color: #e9eaed;
}    
</style>

<style type="text/css">
	.active1{
		background-color: green !important;
	}
	.active2{
		background-color: green !important;
	}
</style>

<script type="text/javascript">
  $(".btn-secondary").click(function(){
    $(this).addClass("active2").siblings().removeClass("active2");
  });
</script>

