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
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/sterling-wilson.png">

    <?php require_once APPPATH . 'views/layout/headerStyles.php'; ?>
    <?php require_once APPPATH . 'views/layout/headerScripts.php'; ?>

</head>

<body class="fixed-left" style="height: 100vh;">

    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div>


    <!-- Begin page -->
    <div class="row align-items-center justify-content-center h-100 my-auto">
        <div class="col-md-6">
            <div class="row justify-content-center">
                <div class="card mx-auto col-10 col-md-8">
                    <div class="card-body">

                        <h3 class="text-center m-0">
                            <a href="#" class="logo logo-admin"><img src="<?= base_url() ?>assets/images/sterling-wilson.png" alt="logo"></a>
                        </h3>

                        <div class="pl-3 pr-3">

                            <form class="form-horizontal m-t-30" id="userLoginForm">

                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" name="user_name" id="username" placeholder="Enter email" required />
                                </div>

                                <div class="form-group">
                                    <label for="userpassword">Password</label>
                                    <a href="<?= base_url() ?>forgot_password" class="text-muted float-right" style="font-size:14px;"><i class="mdi mdi-lock"></i> Forgot password?</a>
                                    <!-- <input type="password" class="form-control"   placeholder="Enter password" required /> -->
                                    <div class="input-field">
                                        <input type="password" class="form-control password" id="userpassword" name="user_password" placeholder="Enter password" required>
                                        <i class="mdi mdi-eye-off showHidePw"></i>
                                    </div>
                                </div>

                                <div class="form-group row m-t-40">
                                    <div class="col-sm-6">
                                        <div class="rememberme custom-control custom-checkbox">
                                            <input type="checkbox" name="remember_me" class="custom-control-input" value="0" id="customControlInline">
                                            <label class="custom-control-label" for="customControlInline">Remember me</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Log In</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <div class="m-t-20 text-center">
                <a href="<?php echo base_url('privacy-policy'); ?>">Privacy Policy</a>
                <span class="mx-2">-</span>
                <a href="<?php echo base_url('terms-and-condition'); ?>">Terms &amp; Condition</a>
            </div>
            <div class="m-t-20 px-4 text-center">
                <p class="mb-1"><a target="_blank" href="<?php echo base_url('download/apk'); ?>" class="btn btn-outline-light rounded-circle shadow"><i class="text-danger mdi mdi-android"></i></a></p>
            </div>
            <div class="m-t-20 px-4 text-center">
                <p class="">&copy; 2021 Sterling Wilson Developed by Mentric Technologies</p>
            </div>
        </div>
    </div>

    <?php require_once APPPATH . 'views/layout/footerScripts.php'; ?>

    <!-- js -->
    <script src="<?php echo base_url('assets/js/include/check_login.js'); ?>"></script>
    <script src="<?= base_url('assets/js/include/admin/login.js') ?>"></script>
    <script>
        $(function() {
            const pwShowHide = document.querySelectorAll(".showHidePw");
            const pwFields = document.querySelectorAll(".password");

            pwShowHide.forEach((eyeIcon) => {
                eyeIcon.addEventListener("click", () => {
                    pwFields.forEach((pwField) => {
                        if (pwField.type === "password") {
                            pwField.type = "text";

                            pwShowHide.forEach((icon) => {
                                icon.classList.replace("mdi-eye-off", "mdi-eye");
                            });
                        } else {
                            pwField.type = "password";

                            pwShowHide.forEach((icon) => {
                                icon.classList.replace("mdi-eye", "mdi-eye-off");
                            });
                        }
                    });
                });
            });
            
            // Check Login
            $.ajax({
                url: formApiUrl('admin/checkLoggedin'),
                type: 'post',
                headers: {
                    Authorization: `Bearer ${wapLogin.getToken()}`
                },
                dataType: 'json',
                beforeSend: function() {
                    this.loadSwal = Swal.fire({
                        position: 'bottom',
                        html: '<div class="my-1 text-center d-inline-block"><h6 class="m-0">checking Login...</h6></div>',
                        customClass: {
                            popup: 'col-6 col-sm-5 col-md-4 col-lg-4'
                        },
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false
                    });
                },
                success: function(res) {
                    if (res.status == 'success') {
                        console.log(res.message);
                        appUser = res.user; // Set user infos
                        wapLogin.setStatus(res.login);
                        setTimeout(() => {
                            window.location.href = formUrl('admin/dashboard');
                        }, 1000);
                    } else if (res.status == 'error') {
                        Swal.fire({
                            icon: 'error',
                            title: res.message,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(function() {
                            wapLogin.setStatus(res.login);
                        });
                    } else {
                        wapLogin.setStatus(false);
                    }
                },
                error: function(error) {
                    console.log(error);
                    let errResponse = error.responseJSON;
                    if (error.status == 401) {
                        if (typeof errResponse.login != 'undefined' && errResponse.login == false) {
                            Swal.fire({
                                icon: 'error',
                                title: errResponse.message,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            }).then(function() {
                                wapLogin.setStatus(false);
                            });
                        } else {
                            wapLogin.setStatus(false);
                        }
                    } else {
                        wapLogin.setStatus(false);
                    }
                },
                complete: function() {
                    this.loadSwal.close();
                }
            });
        });
    </script>
</body>

</html>