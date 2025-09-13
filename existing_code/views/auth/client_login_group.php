<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="<?php echo base_url() ?>assets/commingsoon/images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/commingsoon/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/commingsoon/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/commingsoon/fonts/iconic/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/commingsoon/vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/commingsoon/vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/commingsoon/css/util.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/commingsoon/css/main.css">
</head>
<body>


    <div class="bg-g1 size1 flex-w flex-col-c-sb p-l-15 p-r-15 p-t-55 p-b-35 respon1">
        <span></span>
        <div class="flex-col-c p-t-20 p-b-50">
            <h3 class="txt-center p-b-10" style="font-family: Montserrat-Black;color: #fff;line-height: 1.2;text-transform: uppercase;">
              Click to Login
            </h3>

            <?php 
            $groupLogin = $top_icon['myapps'];

            foreach ($groupLogin as $k => $val) {
                if($val->name == 'Mychat' || $val->name == 'Mydairy' || $val->name == 'Myfriend' || $val->name == 'Mybank' || $val->name == 'Mybank') {
                    unset($groupLogin[$k]);
                }
            }
            foreach ($groupLogin as $k => $val) { ?>
                <?php $uri = $this->uri->segment(2); ?>
                <?php 
                    if ($val->name=='Mymedia') { ?>
                        <a class="flex-c-m s1-txt2 size3 how-btn" href="<?php echo site_url('media-login/'.$val->name) ?>" style="color:white; margin-bottom: 1rem; ">
                            <img  style="width: 20px;" src="<?php echo base_url().$val->icon ?>"> &nbsp;&nbsp; <?php echo $val->name ?>
                        </a>
                    <?php }else{ ?>
                        <a class="flex-c-m s1-txt2 size3 how-btn" href="<?php echo site_url('client-login/'.$val->name) ?>" style="color:white; margin-bottom: 1rem; ">
                            <img  style="width: 20px;" src="<?php echo base_url().$val->icon ?>"> &nbsp;&nbsp; <?php echo $val->name ?>
                        </a>
                   <?php }
                ?>
               
            <?php }
            ?>
          
        </div>

        <span class="s1-txt3 txt-center">
            @ 2021 Mygroup
        </span>

    </div>


    <script src="<?php echo base_url() ?>assets/commingsoon/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="<?php echo base_url() ?>assets/commingsoon/vendor/bootstrap/js/popper.js"></script>
    <script src="<?php echo base_url() ?>assets/commingsoon/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>assets/commingsoon/vendor/select2/select2.min.js"></script>
    <script src="<?php echo base_url() ?>assets/commingsoon/vendor/countdowntime/moment.min.js"></script>
    <script src="<?php echo base_url() ?>assets/commingsoon/vendor/countdowntime/moment-timezone.min.js"></script>
    <script src="<?php echo base_url() ?>assets/commingsoon/vendor/countdowntime/moment-timezone-with-data.min.js"></script>
    <script src="<?php echo base_url() ?>assets/commingsoon/vendor/countdowntime/countdowntime.js"></script>
    <script>
        $('.cd100').countdown100({
            // Set Endtime here
            // Endtime must be > current time
            endtimeYear: 0,
            endtimeMonth: 0,
            endtimeDate: 35,
            endtimeHours: 18,
            endtimeMinutes: 0,
            endtimeSeconds: 0,
            timeZone: ""
            // ex:  timeZone: "America/New_York", can be empty
            // go to " http://momentjs.com/timezone/ " to get timezone
        });
    </script>
<!--===============================================================================================-->
    <script src="vendor/tilt/tilt.jquery.min.js"></script>
    <script >
        $('.js-tilt').tilt({
            scale: 1.1
        })
    </script>
<!--===============================================================================================-->
    <script src="js/main.js"></script>

</body>
</html>
