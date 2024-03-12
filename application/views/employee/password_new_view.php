<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>SW-Application</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="Themesbrand" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App Icons -->
        <link rel="shortcut icon" href="<?= base_url() ?>assets/images/sterling-wilson.png">

        <!-- Basic Css files -->
        <link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="<?= base_url() ?>assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="<?= base_url() ?>assets/css/style.css" rel="stylesheet" type="text/css">

    </head>


    <body class="fixed-left">

        <!-- Loader -->
        <div id="preloader"><div id="status"><div class="spinner"></div></div></div>


        <!-- Begin page -->
        <div class="accountbg"></div>
        <div class="wrapper-page">

            <div class="card">
                <div class="card-body">

                    <h3 class="text-center m-0">
                        <a href="index.html" class="logo logo-admin"><img src="<?= base_url()?>assets/images/sterling-wilson.png" alt="logo"></a>
                    </h3>

                    <div class="p-3">
                        <h4 class="font-18 m-b-5 text-center">Reset Your Password</h4>

                        <form class="form-horizontal m-t-30" action="index.html">

                            <div class="form-group">
                                <label for="useremail">New password</label>
                                <input type="email" class="form-control" id="" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label for="useremail">Confirm password</label>
                                <input type="email" class="form-control" id="" placeholder="Password">
                            </div>

                            <div class="form-group row m-t-20">
                                <div class="col-12 text-right">
                                    <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Reset</button>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

            <div class="m-t-40 text-center">
                <p>© 2021 Sterling Wilson Developed with Mentric Technologies</p>
            </div>

        </div>


        <!-- jQuery  -->
        <script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
        <script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?= base_url() ?>assets/js/modernizr.min.js"></script>
        <script src="<?= base_url() ?>assets/js/jquery.slimscroll.js"></script>
        <script src="<?= base_url() ?>assets/js/waves.js"></script>
        <script src="<?= base_url() ?>assets/js/jquery.nicescroll.js"></script>
        <script src="<?= base_url() ?>assets/js/jquery.scrollTo.min.js"></script>

        <!-- App js -->
        <script src="<?= base_url() ?>assets/js/app.js"></script>

    </body>
</html>