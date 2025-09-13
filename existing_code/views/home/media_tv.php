<style media="screen">
  .tablediv{
    padding:1rem;
  }
  table td{
    padding:5px 10px;
    white-space: nowrap;
    background:#48767d;
    color:white;
    border:5px solid #057284;
  }
  .daystable td a{
    text-decoration:none;
    color:white;
    text-align:center;
    display: block;
  }
</style>
<section class="pb-5">
  <img src="images/tv.jpg" style="width:100%;" alt="">
  <div class="">
    <!-- filter buttons -->
    <div class="row mx-0 mt-3 mb-0">
      <div class="col-3 px-0">
        <div class="btn-group" style="width:100%;" role="group">
            <button style="border-radius:0;width:100%;border:1px solid black;" id="btnGroupDrop1" type="button" class="btn btn-sm btn-warning dropdown-toggle" style="width:100%;" data-bs-toggle="dropdown" aria-expanded="false">
              Type
            </button>
            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
              <li><a class="dropdown-item" href="#">Type 1</a></li>
              <li><a class="dropdown-item" href="#">Type 2</a></li>
            </ul>
          </div>
      </div>
      <div class="col-3 px-0">
        <button style="border-radius:0;width:100%;border:1px solid black;" type="button" class="btn btn-sm btn-warning" style="width:100%;">Location</button>
      </div>
      <div class="col-3 px-0">
        <div class="btn-group" style="width:100%;" role="group">
            <button style="border-radius:0;width:100%;border:1px solid black;" id="btnGroupDrop1" type="button" class="btn btn-sm btn-warning dropdown-toggle" style="width:100%;" data-bs-toggle="dropdown" aria-expanded="false">
              Category
            </button>
            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
              <li><a class="dropdown-item" href="#">Category 1</a></li>
              <li><a class="dropdown-item" href="#">Category 2</a></li>
            </ul>
          </div>
      </div>
      <div class="col-3 px-0">
        <div class="btn-group" style="width:100%;" role="group">
            <button style="border-radius:0;width:100%;border:1px solid black;" id="btnGroupDrop1" type="button" class="btn btn-sm btn-warning dropdown-toggle" style="width:100%;" data-bs-toggle="dropdown" aria-expanded="false">
              Language
            </button>
            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
              <li><a class="dropdown-item" href="#">Language 1</a></li>
              <li><a class="dropdown-item" href="#">Language 2</a></li>
            </ul>
          </div>
      </div>
    </div>
    <!-- filter buttons end -->

    <!-- filter days -->
    <div class="table-responsive">
      <table class="daystable">
        <tr>
          <td style="width:15rem;">
            <a href="#">Sunday</a>
          </td>
          <td style="width:15rem;">
            <a href="#">Monday</a>
          </td>
          <td style="width:15rem;">
            <a href="#">Tuesday</a>
          </td>
          <td style="width:15rem;">
            <a href="#">Wednesday</a>
          </td>
          <td style="width:15rem;">
            <a href="#">Thursday</a>
          </td>
          <td style="width:15rem;">
            <a href="#">Friday</a>
          </td>
          <td style="width:15rem;">
            <a href="#">Saturday</a>
          </td>
        </tr>
      </table>
    </div>
    <!-- filter days end -->

    <!-- tv shows according to time -->
    <div class="table-responsive">
      <table cellspacing="2" class="" style="table-layout: auto !important;width: auto !important;">
        <!-- tv show timings -->
        <thead>
          <tr>
            <td>Channel</td>
            <td>00:00</td>
            <td>00:30</td>
            <td>01:00</td>
            <td>01:30</td>
            <td>02:00</td>
            <td>02:30</td>
            <td>03:00</td>
            <td>03:30</td>
            <td>04:00</td>
            <td>04:30</td>
            <td>05:00</td>
            <td>05:30</td>
            <td>06:00</td>
            <td>06:30</td>
            <td>07:00</td>
            <td>07:30</td>
            <td>08:00</td>
            <td>08:30</td>
            <td>09:00</td>
            <td>09:30</td>
            <td>10:00</td>
            <td>10:30</td>
            <td>11:00</td>
            <td>11:30</td>
            <td>12:00</td>
            <td>12:30</td>
            <td>13:00</td>
            <td>13:30</td>
            <td>14:00</td>
            <td>14:30</td>
            <td>15:00</td>
            <td>15:30</td>
            <td>16:00</td>
            <td>16:30</td>
            <td>17:00</td>
            <td>17:30</td>
            <td>18:00</td>
            <td>18:30</td>
            <td>19:00</td>
            <td>19:30</td>
            <td>20:00</td>
            <td>20:30</td>
            <td>21:00</td>
            <td>21:30</td>
            <td>22:00</td>
            <td>22:30</td>
            <td>23:00</td>
            <td>23:30</td>
          </tr>
        </thead>
        <!-- tv show end -->

        <!-- tv shows start -->
        <tbody>
          <!-- single channel shows start -->
          <tr>
            <td style="background:white;">
              <div style="min-width:5rem;display:flex;align-items:center;justify-content:center">
                <img src="<?php echo base_url();?>/assets/tv/1.png" alt="">
              </div>
            </td>
            <td colspan="3">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
          </tr>
          <!-- single channel shows end -->

          <!-- single channel shows start -->
          <tr>
            <td style="background:white;">
              <div style="min-width:5rem;display:flex;align-items:center;justify-content:center">
                <img src="<?php echo base_url();?>/assets/tv/2.png" alt="">
              </div>
            </td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="3">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
          </tr>
          <!-- single channel shows end -->

          <!-- single channel shows start -->
          <tr>
            <td style="background:white;">
              <div style="min-width:5rem;display:flex;align-items:center;justify-content:center">
                <img src="<?php echo base_url();?>/assets/tv/3.png" alt="">
              </div>
            </td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="3">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
            <td colspan="1">show name</td>
          </tr>
          <!-- single channel shows end -->


        </tbody>
        <!-- tv shows end -->

      </table>
    </div>
    <!-- tv shows according to time end -->
  </div>
</section>
