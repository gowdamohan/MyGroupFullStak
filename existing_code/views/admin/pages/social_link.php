  <ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
    <li>Header Ads</li>
</ul>
<div class="panel panel-default">
 <div class="panel-heading">
    <h3 class="panel-title">Header Ads</h3>
 </div>
 <div class="panel-body">
    <table class="table table-bordered" id="verticalAlgn">
      <thead>
        <tr>
          <th>Social Link Name</th>
          <th>URL</th>
          <th>ACTION</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          $linkId0 = 0;
          $linkUrl0 = '';
          if (!empty($social_link[0])) {
            $linkId0 = $social_link[0]->id;
            $linkUrl0 = $social_link[0]->url;
          }
        ?>
        <?php 
          $linkId1 = 0;
          $linkUrl1 = '';
          if (!empty($social_link[1])) {
            $linkId1 = $social_link[1]->id;
            $linkUrl1 = $social_link[1]->url;
          }
        ?>
        <?php 
          $linkId2 = 0;
          $linkUrl2 = '';
          if (!empty($social_link[2])) {
            $linkId2 = $social_link[2]->id;
            $linkUrl2 = $social_link[2]->url;
          }
        ?>

        <?php 
          $linkId3 = 0;
          $linkUrl3 = '';
          if (!empty($social_link[3])) {
            $linkId3 = $social_link[3]->id;
            $linkUrl3 = $social_link[3]->url;
          }
        ?>

        <?php 
          $linkId4 = 0;
          $linkUrl4 = '';
          if (!empty($social_link[4])) {
            $linkId4 = $social_link[4]->id;
            $linkUrl4 = $social_link[4]->url;
          }
        ?>

        <?php 
          $linkId5 = 0;
          $linkUrl5 = '';
          if (!empty($social_link[5])) {
            $linkId5 = $social_link[5]->id;
            $linkUrl5 = $social_link[5]->url;
          }
        ?>
        <?php 
          $linkId6 = 0;
          $linkUrl6 = '';
          if (!empty($social_link[6])) {
            $linkId6 = $social_link[6]->id;
            $linkUrl6 = $social_link[6]->url;
          }
        ?>
        <tr>

        <tr>
          <td>
           You Tube
          </td>

          <td>
            <input type="text" required="" value="<?php echo $linkUrl0 ?>" class="form-control" id="image-url2" >
          </td>
          <td>
            <input type="button"  value="Save"  onclick="uploadsocial_links(2, <?php echo $linkId0 ?>)" id="up-btn2" class="btn btn-success">
          </td>
        </tr>

          <td>
            Face Book
          </td>
          
          <td>
            <input type="text" required="" value="<?php echo $linkUrl1   ?>" class="form-control" id="image-url1" >
          </td>
          <td>
            <input type="button"  value="Save" onclick="uploadsocial_links(1, <?php echo $linkId1 ?>)" id="up-btn1" class="btn btn-success">
          </td>
          
        </tr>

         
        <tr>
          <td>
          Instagram
          </td>

          <td>
            <input type="text" required="" value="<?php echo $linkUrl2 ?>" class="form-control" id="image-url3" >
          </td>

          <td>
            <input type="button"  value="Save" onclick="uploadsocial_links(3, <?php echo $linkId2 ?>)" id="up-btn3" class="btn btn-success">
          </td>
        </tr>

         <tr>
          <td>
          Twiter
          </td>

          <td>
            <input type="text" required="" value="<?php echo $linkUrl3 ?>" class="form-control" id="image-url4" >
          </td>

          <td>
            <input type="button"  value="Save" onclick="uploadsocial_links(4, <?php echo $linkId3 ?>)" id="up-btn3" class="btn btn-success">
          </td>
        </tr>

        <tr>
          <td>
          Linkedin
          </td>

          <td>
            <input type="text" required="" value="<?php echo $linkUrl4 ?>" class="form-control" id="image-url5" >
          </td>

          <td>
            <input type="button"  value="Save" onclick="uploadsocial_links(5, <?php echo $linkId4 ?>)" id="up-btn3" class="btn btn-success">
          </td>
        </tr>

        <tr>
          <td>
          Website
          </td>

          <td>
            <input type="text" required="" value="<?php echo $linkUrl5 ?>" class="form-control" id="image-url6" >
          </td>

          <td>
            <input type="button"  value="Save" onclick="uploadsocial_links(6, <?php echo $linkId5 ?>)" id="up-btn3" class="btn btn-success">
          </td>
        </tr>
 
        <tr>
          <td>
          Blogger
          </td>

          <td>
            <input type="text" required="" value="<?php echo $linkUrl6 ?>" class="form-control" id="image-url7" >
          </td>

          <td>
            <input type="button"  value="Save" onclick="uploadsocial_links(7, <?php echo $linkId6 ?>)" id="up-btn3" class="btn btn-success">
          </td>
        </tr>
      </tbody>
    </table>
    
 </div>
 
</div>

<style type="text/css">
  #verticalAlgn tr td{
    vertical-align: middle;
  }
  img{
    width: 24%;
  }
</style>

<script type="text/javascript">
  function uploadsocial_links(sl, uploadId) {
    $('#up-btn'+sl).val('Please wait ...').attr('disabled', 'disabled');
    var socialurl = $('#image-url'+sl).val();
     $.ajax({
      url: '<?php echo site_url('admin_controller/upload_social_link'); ?>',
      type: 'post',
      data: {'socialurl':socialurl,'uploadId':uploadId},
      success: function(data) {
        location.reload();
      }
    });
  }
</script>