<html lang="en" class="body-full-height"><head>        
        <!-- META SECTION -->
        <title>My Media</title>            
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="favicon.ico" type="image/x-icon">
        <!-- END META SECTION -->
        <!-- CSS INCLUDE -->        
        <link rel="stylesheet" type="text/css" id="theme" href="<?php echo base_url()?>assets/back_end/css/theme-default.css">
        <!-- EOF CSS INCLUDE -->
    </head>
    <body>
        
        <div class="login-container">
        
            <div class="login-box animated fadeInDown">
                <div class="login-logo"></div>
                <div class="login-body">
                    <div class="login-title"><strong>Welcome</strong>, Please login</div>
                    <form action="<?php echo site_url().'login';?>" class="form-horizontal" method="post">
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="identity" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <p>
                                <a href="<?php echo site_url().'forgot' ?>" class="btn btn-link btn-block">Forgot your password?</a>
                            </p>
                            <!-- <p>
                                <a href="<?php // echo site_url('register') ?>" class="btn btn-link btn-block">New User</a>
                            </p> -->
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-info btn-block">Log In</button>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="login-footer">
                    <div class="pull-left">
                        © 2021 My Group
                    </div>
                </div>
            </div>
        </div>
<style type="text/css">
    .logo{
        height: 80px;
    }
</style>
</body>
</html>