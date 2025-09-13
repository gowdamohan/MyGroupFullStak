<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">About us</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Gallery</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Contact Us</a>
      </li>
    </ul>
  </div>
</nav>


<style media="screen">
a{
  text-decoration: none !important;
}
  .thumbnailimage{
    width:100%;
    height:15vh;
    background-size:cover !important;
    background-position:center center !important;
    background-repeat:no-repeat !important;
    border-top-right-radius: 10px;
    border-top-left-radius: 10px;
  }
  .categorypara{
    background:#057284;
    color:white;
    font-weight:600;
    letter-spacing: 1px;
    text-align:center;
    font-size:0.7rem;
    padding:5px;
    border-bottom-right-radius:10px;
    border-bottom-left-radius:10px;
  }
</style>
<div class="row mx-0 pb-5">
  <?php for ($i=0; $i < 6; $i++) {  ?>
    <div class="col-6 px-2 mb-3">
      <a href="#">
        <div class="thumbnailimage" style="background:url('assets/client_bg.png')">
        </div>
        <p class="categorypara">Test</p>
      </a>
    </div>
  <?php } ?>
  
</div>
