
 <div class="row" style="margin: 0px;">
      <div class="col-md-6">
        <h3 class="card-title panel_title_new_style_staff">
            <a class="back_anchor" href="<?php echo site_url('home/apply_my_jobs/'.$groupname) ?>" class="control-primary">
                <span class="fa fa-arrow-left"></span>
                <strong>Go Back</strong>&nbsp;
            </a>
        
        </h3>
      </div>
    </div>
<?php 
    if ($this->mobile_detect->isMobile()) { ?>
       <section id="sec-features" class="sec-features pb-5">
    <?php }else{ ?>
       <section id="sec-features" class="sec-features  pb-5">
    <?php }
?>
    <div class="row" style="margin: 0px;">
       <div class="container">
            <div class="heading white-heading">
             <?php echo str_replace('_', ' ', strtoupper($jobType)) ?>
            </div>
        </div>
        <div class="container">
            <div class="panel-body">

                <div class="col-md-6 col-md-offset-2">
                    <form enctype="multipart/form-data" method="post" id="job_apply-now" action="<?php echo site_url('home/apply_job_insert/'.$groupname) ?>" class="form-horizontal" data-parsley-validate>
                        <input type="hidden" name="job_user_id" value="<?php echo $user->id ?>">
                        <input type="hidden" name="job_type" value="<?php echo $jobType ?>">
                        <div class="form-group">
                            <p>Country <font color="red"> *</font></p>
                            <select class="form-control" id="franchiseCountry" name="franchise_country">
                                <option value="">Select Country</option>
                                 <?php foreach ($countryList as $key => $val) { ?>
                                    <option value="<?php echo $val->id ?>"><?php echo $val->country ?></option>
                                <?php } ?>
                            </select>
                        </div>


                        <div class="form-group">
                            <p>Name <font color="red"> *</font></p>
                            <input type="text" name="applier_name" required id="applier_name" class="form-control" >
                        </div>
                        <div class="form-group">
                            <p>Mobile Number <font color="red"> *</font></p>
                            <input type="text" name="applyeer_mobile" required id="applyeer_mobile" class="form-control" >
                        </div>
                        <div class="form-group">
                            <p>Email ID <font color="red"> *</font></p>
                            <input type="text" name="applyeer_email_id" required id="applyeer_email_id" class="form-control" >
                        </div>
                    
                        <div class="form-group">
                            <p>Education Qualification <font color="red"> *</font></p>
                            <input type="text" name="applier_education" required id="applier_education" class="form-control" >
                        </div>
                        <div class="form-group">
                            <p>Work Experience <font color="red"> *</font></p>
                            <input type="text" name="applyeer_experience" required id="applyeer_experience" class="form-control" >
                        </div>
                        <div class="form-group">
                            <p>Any Other Details / Skils </p>
                            <input type="text" name="applyeer_any_other_details" id="applyeer_any_other_details" class="form-control" >
                        </div>
                        <div class="form-group">
                            <div class="image-upload">
                                <input type="file" name="franchise_upload_file" id="logo" onchange="fileValue(this)" >
                                <label for="logo" class="upload-field" id="file-label">
                                    <div class="file-thumbnail">
                                        <canvas id="pdfViewer"></canvas>
                                        <br>
                                        <img id="image-preview" src="https://www.btklsby.go.id/images/placeholder/basic.png" alt="">
                                        <h3 id="filename">
                                            Upload Resume
                                        </h3>
                                        <p >Supports PDF, JPG, PNG</p>
                                    </div>
                                </label>
                            </div>
                        </div>


                        <button type="button" onclick="submitMyjobs()" id="btnFranchiseSubmit" class="btn btn-info btn-lg btn-block">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style type="text/css">
    .image-upload{
    position: relative;
    max-width: 360px;
  margin: 0 auto;
  overflow: hidden;
}
.image-upload input {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 100%;
        opacity: 0;
    }
.upload-field {
  display: block;
  background: #F4FAFE;
  padding: 12px;
  border-radius: 11px;

}

.heading {
    text-align: center;
    color: #4c372f;
    font-size: 30px;
    font-weight: 700;
    margin-bottom: 20px;
    text-transform: uppercase;
    border: 1px solid #ccc;
    border-radius: 10px;
     background: #4cf8e6;
}

.upload-field .file-thumbnail {
  cursor: pointer;
  border: 1px dashed #BBD9EC;
  border-radius: 11px;
  text-align: center;
  padding: 20px;
}

.upload-field .file-thumbnail img {
  width: 50px;
}

.upload-field .file-thumbnail h3 {
  font-size: 12px;
  color: #000000;
  font-weight: 600;
  margin-bottom: 4px;
}

.upload-field .file-thumbnail p {
  font-size: 12px;
  color: #9ABCD1;
  margin-bottom: 0;
}

.back-button{
  width:50px;
  height:50px;
  position: absolute;
  top: 45%;
  left: 10%;
  transform: translate(-50%, -50%);
  border-radius:50%;
  border:#03A9F4 1px solid;
  overflow:hidden;
  transition:background 0.3s ease;
}

.arrow-wrap{
    display:block;
    position:absolute;
    height:70%;
    width:70%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    transition:left 0.3s ease;
}
.arrow-wrap span{
  height:1px;
  left:0;
  top:50%;
  background:#03A9F4;
  position:absolute;
  display:block;
  transition:background 0.3s ease;
}
.arrow-part-1{
  width:100%;
  transform: translate(0, -50%);
}
.arrow-part-2{
  width:60%;
  transform: rotate(-45deg);
  transform-origin: 0 0;
}
.arrow-part-3{
  width:60%;
  transform: rotate(45deg);
  transform-origin: 0 0;
}
 


</style>
<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
<script type="text/javascript">

function submitMyjobs() {

    var $form = $('#job_apply-now');
    if ($form.parsley().validate()){
      //console.log ( 'valid' );
        $('#btnFranchiseSubmit').prop('disabled',true).html('Please wait...');
        $('#job_apply-now').submit();
    }
}
    // Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

    function fileValue(value) {
        
        var path = value.value;
        var extenstion = path.split('.').pop();
        var file = event.target.files[0];
        if (extenstion == "pdf") {
            $('#image-preview').hide();
            $('#pdfViewer').show();
            var fileReader = new FileReader();  
            fileReader.onload = function() {
                var pdfData = new Uint8Array(this.result);
                // Using DocumentInitParameters object to load binary data.
                var loadingTask = pdfjsLib.getDocument({data: pdfData});
                loadingTask.promise.then(function(pdf) {
                  // Fetch the first page
                  var pageNumber = 1;
                  pdf.getPage(pageNumber).then(function(page) {
                    var scale = 0.1;
                    var viewport = page.getViewport({scale: scale});
                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#pdfViewer")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                      canvasContext: context,
                      viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function () {
                    });
                  });
                }, function (reason) {
                  // PDF loading error
                  console.error(reason);
                });
            };
            fileReader.readAsArrayBuffer(file);

        }else{
            $('#image-preview').show();
            $('#pdfViewer').hide();
            if(extenstion == "jpg" || extenstion == "jpeg" || extenstion == "png"){
                document.getElementById('image-preview').src = window.URL.createObjectURL(value.files[0]);
                var filename = path.replace(/^.*[\\\/]/, '').split('.').slice(0, -1).join('.');
                document.getElementById("filename").innerHTML = filename;
            }else{
                alert("File not supported. Kindly Upload the Image of below given extension ")
            }
        }
    }
    var user_id = '<?php echo (!empty($user)) ? $user->id : ''  ?>';
    $.ajax({
        url: '<?php echo site_url('home/edit_profile_mobile'); ?>',
        data: {'user_id': user_id},
        type: 'post',
        success: function(data) {
          var profile = JSON.parse(data);
          $('#applier_name').val(profile.profile.first_name);
          $('#applyeer_mobile').val(profile.profile.phone);
          $('#applyeer_email_id').val(profile.profile.email);
        }
    });
</script>