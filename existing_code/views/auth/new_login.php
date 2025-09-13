<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Group - Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/front/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-logo img {
            max-width: 150px;
            height: auto;
        }
        .login-title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-login {
            background-color: #007bff;
            color: #fff;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-login:hover {
            background-color: #0069d9;
        }
        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #6c757d;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <img src="<?php echo base_url(); ?>assets/front/img/logo.png" alt="My Group Logo">
        </div>
        
        <div class="login-title">Welcome to My Group</div>
        
        <?php if($this->session->flashdata('flashError')): ?>
            <div class="alert alert-danger">
                <?php echo $this->session->flashdata('flashError'); ?>
            </div>
        <?php endif; ?>
        
        <?php if($this->session->flashdata('flashSuccess')): ?>
            <div class="alert alert-success">
                <?php echo $this->session->flashdata('flashSuccess'); ?>
            </div>
        <?php endif; ?>
        
        <form action="<?php echo site_url('auth/login'); ?>" method="post">
            <div class="form-group">
                <label for="identity">Email</label>
                <input type="text" class="form-control" id="identity" name="identity" placeholder="Enter your email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
            </div>
            
            <button type="submit" class="btn btn-login">Login</button>
            
            <div class="form-group mt-3 text-center">
                <a href="<?php echo site_url('auth/forgot_password'); ?>">Forgot your password?</a>
            </div>
        </form>
        
        <div class="login-footer">
            &copy; <?php echo date('Y'); ?> My Group. All rights reserved.
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="<?php echo base_url(); ?>assets/front/js/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/front/js/bootstrap.bundle.min.js"></script>
</body>
</html>
