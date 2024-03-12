<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>Sterling Wilson</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="Mentric" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App Icons -->
        <link rel="shortcut icon" href="<?= base_url()?>assets/images/sterling-wilson.png">

        <?php require_once APPPATH . 'views/layout/headerStyles.php'; ?>
        <?php require_once APPPATH . 'views/layout/headerScripts.php'; ?>
    
    </head>


    <body class="fixed-left" style="height: 100vh;">

        <!-- Loader -->
        <div id="preloader"><div id="status"><div class="spinner"></div></div></div>


        <!-- Begin page -->
        <div class="wrapper-page">

            <div class="card">
                <div class="card-body">
                    <h3 class="text-center m-0">
                        <a href="#" class="logo logo-admin"><img src="<?= base_url()?>assets/images/sterling-wilson.png" alt="logo"></a>
                    </h3>
                    <div class="p-3">
                        <h4 class="font-18 m-b-5 text-center">Reset Password</h4>
                        <p class="text-muted text-center">Enter your Email and instructions will be sent to you!</p>
                        <form class="form-horizontal m-t-30" id="forgotFormByEmail">
                            <div class="form-group">
                                <label for="useremail">Email</label>
                                <input type="email" class="form-control" id="useremail" name="user_email" placeholder="Enter email">
                            </div>
                            <div class="form-group row m-t-20">
                                <div class="col-12 text-right">
                                    <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Send mail</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="m-t-40 text-center">
                <p class="text-white">© 2021 Sterling Wilson Developed with Mentric Technologies</p>
            </div>
        </div>

        <?php require_once APPPATH . 'views/layout/footerScripts.php'; ?>
           
        <!-- js -->
        <script src="<?php echo base_url('assets/js/include/check_login.js'); ?>"></script>
        <script src="<?php echo base_url('assets/js/include/admin/password_reset.js')?>"></script>
        <script src="<?php echo base_url('assets/js/include/admin/password_reset_otp.js')?>"></script>
        <script src="<?php echo base_url('assets/js/include/admin/forgot_password.js')?>"></script>
        
    </body>
</html>