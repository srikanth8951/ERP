<div class="page-content-wrapper">

    <div class="container-fluid">

        <!-- --------------------------------------------------- -->
        <div class="wrapper-page" style="margin-top: 5px;">
            <div class="card">
                <div class="card-body">
                    <div class="p-3">
                        <h4 class="font-18 m-b-5 text-center">Change Password</h4>
                        <form class="form-horizontal m-t-30" id="employeePasswordChangeForm" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Current Password<span class="text-danger"> *</span></label>
                                <input type="password" class="form-control" name="user_current_password" id="userpassword" placeholder="Enter current password">
                            </div>
                            <div class="form-group">
                                <label>New Password<span class="text-danger"> *</span></label>
                                <input type="password" class="form-control" name="user_new_password" id="newpassword" placeholder="Enter new password">
                            </div>
                            <div class="form-group">
                                <label>Confirm Password<span class="text-danger"> *</span></label>
                                <input type="password" class="form-control" name="confirm_password" id="confirmpassword" placeholder="Enter confirm password">
                            </div>
                            <div class="form-group row m-t-20">
                                <div class="col-12 text-center">
                                    <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- --------------------------------------------------- -->
    </div><!-- container -->

</div> <!-- Page content Wrapper -->

<script src="<?php echo base_url('assets/js/include/customer/check_login.js'); ?>"></script>
<script src="<?= base_url() ?>assets/js/include/customer/password.js"></script>


<script>
    $(function() {

        // Check Login
        $.when(wapLogin.check()).done(function(res) {
            if (res.status == 'success') {
                console.log(res.message);
                appUser = res.user; // Set user infos
                wapLogin.setStatus(res.login);
                loadInitFunctions();
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
                wapLogin.showDialog(res.message);
            }
        }).fail(function(jqXHR, textStatus) {
            wapLogin.setStatus(false);
            Swal.fire({
                icon: 'error',
                title: 'Something went wrong! Contact support',
                allowOutsideClick: false,
                allowEscapeKey: false,
                // timer: 3000,
                // timerProgressBar: true
            });
        });
    });
</script>