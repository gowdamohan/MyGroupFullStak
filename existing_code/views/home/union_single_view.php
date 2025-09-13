<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<?php $pic = $this->filemanager->getFilePath($single_union->client_logo); ?>
<style media="screen">
	.profilephoto{
		width:8rem;
		height:8rem;
		border-radius:15px;
		background:url(<?php echo $pic ?>);
		background-size:cover;
		background-repeat:no-repeat;
		background-position:center center;
		border:3px solid #ffffff;
		box-shadow:0 0 10px white;
	}
	.partnername{
		font-size:1.2rem;
		font-weight:bold;
		text-transform:uppercase;
		letter-spacing: 1px;
		margin:0.2rem 0;
		color:white;\
    line-height: 1;
	}
	.partnername span{
		font-size:0.9rem;
		vertical-align: middle;
	}
	.informationcenter{
		border-radius: 0px;
		box-shadow:0 0 5px grey;
		margin-bottom:1rem;
    padding:0;
	}
	.socialicons a i{
		color:white;
		font-size:1.2rem;
	}
	.socialicons a{
		height:2rem;
		width:2rem;
		display:flex;
		align-items:center;
		justify-content:center;
		border-radius:50%;
	}
	.shareicon{
		position: fixed;
		bottom:10rem;
		right:-24px;
		background:black;
		background: linear-gradient(to right, #2c3e50, #4ca1af);
		color:white;
		padding:3px 10px;
		font-size:1rem;
		transform: rotateZ(270deg)
	}
  .card{
    border-radius: 0 !important;
  }
  .maincatheading{
    font-size:1rem;
    font-weight:600;
    letter-spacing: 1px;
  }
  .maincatpara{
    font-size:0.7rem;
    color:#8a8a8a;
  }
  .subcatheading{
    font-size:0.9rem;
    font-weight:500;
  }
  .singleprod{
    font-size:0.8rem;
    color:black;
    text-decoration:none !important;
  }
  .singleprod:hover{

  }
</style>
<div class="d-flex align-items-center justify-content-center py-3" style="background: linear-gradient(to right, #136a8a, #267871);flex-direction:column;">
	<div class="profilephoto" style="">

	</div>
  <h5 class="text-center partnername mt-2"><?php echo $single_union->name_of_the_organization ?></h5>
  <h5 class="text-center partnername"><?php echo $single_union->regional_lang_name ?></h5>
</div>
<section class="pt-4 pb-5" style="background:#ebebeb;">
	<div class="col-12 mx-auto" style="padding: 0;" >
		<div class="informationcenter col-12 mx-auto bg-white p-0">

      <div id="accordionsub">
        <div class="card">
          <div class="card-header p-0" id="subcat1">
            <div class="p-3" style="background: linear-gradient(to right, #4776e6, #8e54e9);color:white;" data-toggle="collapse" data-target="#collapse_subcat1" aria-expanded="true" aria-controls="collapse_subcat1">
              <div class="row mx-0">
                <div class="col-2 px-1  d-flex align-items-center justify-content-start">
                  <i class="fas fa-user"></i>
                </div>
                <div class="col-8 px-1 d-flex align-items-center justify-content-start">
                  <div class="" style="width:100%;">
                    <h5 class="mb-1">Profile</h5>
                  </div>
                </div>
                <div class="col-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-plus"></i>
                </div>
              </div>
            </div>
          </div>

          <div id="collapse_subcat1" class="collapse px-3" aria-labelledby="subcat1" data-parent="#accordionsub">
            <div class="card-body py-2 px-4">
              <a href="<?php echo site_url('myunions/about_union/'.$groupname.'/'.$union_type.'/'.$unionId) ?>" class="singleprod d-block d-flex align-items-center justify-content-between my-2">About Union <i class="fas fa-angle-right float-right"></i></a>
              <a href="<?php echo site_url('myunions/about_objectives/'.$groupname.'/'.$union_type.'/'.$unionId) ?>" class="singleprod d-block d-flex align-items-center justify-content-between my-2">Objectives <i class="fas fa-angle-right float-right"></i></a>
              <a href="<?php echo site_url('myunions/about_news_letter/'.$groupname.'/'.$union_type.'/'.$unionId) ?>" class="singleprod d-block d-flex align-items-center justify-content-between my-2">News Letter <i class="fas fa-angle-right float-right"></i></a>
              <a href="<?php echo site_url('myunions/about_awards/'.$groupname.'/'.$union_type.'/'.$unionId) ?>" class="singleprod d-block d-flex align-items-center justify-content-between my-2">Awards <i class="fas fa-angle-right float-right"></i></a>
            </div>
          </div>
        </div>
      </div>

      <div>
        <div class="card">
          <a href="<?php echo site_url('myunions/union_director_list/'.$groupname.'/'.$union_type.'/'.$unionId) ?>">
            <div class="card-header p-0">
              <div class="p-3" style="background: linear-gradient(to right, #4776e6, #8e54e9);color:white;">
                <div class="row mx-0">
                  <div class="col-2 px-1  d-flex align-items-center justify-content-start">
                    <i class="fas fa-house-user"></i>
                  </div>
                  <div class="col-10 px-1 d-flex align-items-center justify-content-start">
                    <div class="" style="width:100%;">
                      <h5 class="mb-1">Directors</h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </a>

        </div>
      </div>

      <div>
        <div class="card">
          <a href="<?php echo site_url('myunions/union_member_list/'.$groupname.'/'.$union_type.'/'.$unionId) ?>">
          <div class="card-header p-0">
            <div class="p-3" style="background: linear-gradient(to right, #4776e6, #8e54e9);color:white;">
              <div class="row mx-0">
                <div class="col-2 px-1  d-flex align-items-center justify-content-start">
                  <i class="fas fa-users"></i>
                </div>
                <div class="col-10 px-1 d-flex align-items-center justify-content-start">
                  <div class="" style="width:100%;">
                    <h5 class="mb-1">Members</h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </a>
        </div>
      </div>

      <div>
        <div class="card">
          <a href="<?php echo site_url('myunions/union_member_notice/'.$groupname.'/'.$union_type.'/'.$unionId) ?>">
          <div class="card-header p-0">
            <div class="p-3" style="background: linear-gradient(to right, #4776e6, #8e54e9);color:white;">
              <div class="row mx-0">
                <div class="col-2 px-1  d-flex align-items-center justify-content-start">
                  <i class="fas fa-tag"></i>
                </div>
                <div class="col-10 px-1 d-flex align-items-center justify-content-start">
                  <div class="" style="width:100%;">
                    <h5 class="mb-1">Notices</h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </a>
        </div>
      </div>

      <div>
        <div class="card">
          <div class="card-header p-0">
            <div class="p-3" style="background: linear-gradient(to right, #4776e6, #8e54e9);color:white;">
              <div class="row mx-0">
                <div class="col-2 px-1  d-flex align-items-center justify-content-start">
                  <i class="fas fa-photo-video"></i>
                </div>
                <div class="col-10 px-1 d-flex align-items-center justify-content-start">
                  <div class="" style="width:100%;">
                    <h5 class="mb-1">Gallery</h5>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <div>
        <div class="card">
          <div class="card-header p-0">
            <div class="p-3" style="background: linear-gradient(to right, #4776e6, #8e54e9);color:white;">
              <div class="row mx-0">
                <div class="col-2 px-1  d-flex align-items-center justify-content-start">
                  <i class="fas fa-print"></i>
                </div>
                <div class="col-10 px-1 d-flex align-items-center justify-content-start">
                  <div class="" style="width:100%;">
                    <h5 class="mb-1">Union Documents</h5>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <div>
        <div class="card">
          <div class="card-header p-0">
            <div class="p-3" style="background: linear-gradient(to right, #4776e6, #8e54e9);color:white;">
              <div class="row mx-0">
                <div class="col-2 px-1  d-flex align-items-center justify-content-start">
                  <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="col-10 px-1 d-flex align-items-center justify-content-start">
                  <div class="" style="width:100%;">
                    <h5 class="mb-1">Address</h5>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <div id="accordionsub2">
        <div class="card">
          <div class="card-header p-0" id="subcat2">
            <div class="p-3" style="background: linear-gradient(to right, #4776e6, #8e54e9);color:white;" data-toggle="collapse" data-target="#collapse_subcat2" aria-expanded="true" aria-controls="collapse_subcat2">
              <div class="row mx-0">
                <div class="col-2 px-1  d-flex align-items-center justify-content-start">
                  <i class="fas fa-headset"></i>
                </div>
                <div class="col-8 px-1 d-flex align-items-center justify-content-start">
                  <div class="" style="width:100%;">
                    <h5 class="mb-1">Contact Us</h5>
                  </div>
                </div>
                <div class="col-2 d-flex align-items-center justify-content-center">
                  <i class="fas fa-plus"></i>
                </div>
              </div>
            </div>
          </div>

          <div id="collapse_subcat2" class="collapse px-3" aria-labelledby="subcat2" data-parent="#accordionsub2">
            <div class="card-body py-2 px-4">
              <a href="#" class="singleprod d-block d-flex align-items-center justify-content-between my-2">Feedback & Suggestions <i class="fas fa-angle-right float-right"></i></a>
              <a href="#" class="singleprod d-block d-flex align-items-center justify-content-between my-2">Enquiry <i class="fas fa-angle-right float-right"></i></a>
            </div>
          </div>
        </div>
      </div>

      <div>
        <?php if ($single_union->enabled_public_form == 1 && $user_validate != 1) { ?>
          <div class="card">
            <div class="card-header p-0">
              <div class="p-2">
                <div class="row mx-0">
                  <div class="col-8 mx-auto px-1 d-flex align-items-center justify-content-start">
                    <div class="" style="width:100%;">
                      <a href="<?php echo site_url('myunions/invite_membership_form/'.$single_union->user_id.'/'.$groupname.'/'.$union_type) ?>" class="mb-1" style="background: linear-gradient(to right,#66ff00, #66ff00, #66ff00);color:black;padding:0.3rem 0.8rem;font-size:0.8rem;font-weight:bold;border-radius:20px;border:1px solid black;display:block;text-align:center;text-decoration:none !important;">Apply for Membership</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        <?php } ?>
       

      </div>

		</div>

    <!-- floating icon -->
		<a href="#" class="shareicon" style="color:white;text-decoration:none;">
			<i class="fas fa-share-alt" aria-hidden="true"></i> Share
		</a>
		<!-- floating icon end -->

		<div class="informationcenter socialicons col-12 mx-auto bg-white p-2 d-flex align-items-center" style="justify-content:space-around;">
      <a href="#" style="background: linear-gradient(to right, #1e3c72, #2a5298);">
				<i class="fas fa-globe" aria-hidden="true"></i>
			</a>
			<a href="#" style="background: linear-gradient(to right, #1e3c72, #2a5298);">
				<i class="fab fa-facebook" aria-hidden="true"></i>
			</a>
			<a href="#" style="background: linear-gradient(to right, #e52d27, #b31217);">
				<i class="fab fa-youtube" aria-hidden="true"></i>
			</a>
			<a href="#" style="background: linear-gradient(to right, #833ab4, #fd1d1d, #fcb045);">
				<i class="fab fa-instagram" aria-hidden="true"></i>
			</a>
			<a href="#" style="background: linear-gradient(to right, #00b4db, #0083b0);">
				<i class="fab fa-twitter" aria-hidden="true"></i>
			</a>
			<a href="#" style="background: linear-gradient(to right, #0575e6, #021b79);">
				<i class="fab fa-linkedin" aria-hidden="true"></i>
			</a>
		</div>



	</div>
</section>

<!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script> -->
