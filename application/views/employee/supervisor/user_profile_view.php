<style>
    .pac-container {
        z-index: 10000 !important;
    }
</style>
<div class="page-content-wrapper">

    <div class="container-fluid" id="user--deatils--area">
        <div class="card card-body p-2 mb-2">
            <div class="row">
                <div class="col px-4">
                    <h5>Profile</h5>
                </div>
                <div class="col text-right">
                    <a href="<?php echo base_url(); ?>employee/supervisor/dashboard" class="btn btn-outline-secondary mt-1"><i class="mdi mdi-chevron-double-left"></i> Back</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <form id="userProfileForm" action="#">
                    <!-- customer row -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card m-b-20">
                                <div class="card-body">
                                    <h6>USER DETAILS</h6>
                                    <hr />
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>First Name<span class="text-danger"> *</span></label>
                                                <input type="text" name="first_name" class="form-control" placeholder="" />
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Last Name<span class="text-danger"> *</span></label>
                                                <input type="text" name="last_name" class="form-control" placeholder="" />
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Email<span class="text-danger"> *</span></label>
                                                <input type="text" name="email" class="form-control" placeholder="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Username<span class="text-danger"> *</span></label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                <input type="text" name="username" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <div>
                                                    <textarea name="address" class="form-control" rows="4"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Country</label>
                                                <div class="ele-jqValid">
                                                    <select name="country_id" data-toggle="select2" class="select2 form-control"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>State</label>
                                                <div class="ele-jqValid">
                                                    <select name="state_id" data-toggle="select2" class="select2 form-control"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Mobile<span class="text-danger"> *</span></label>
                                                <div class="input-group">
                                                    <span id="country-dial-code" class="input-group-text input-group-prepend"></span>
                                                    <input type="text" name="mobile" class="form-control" placeholder="" />
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>City</label>
                                                <div class="ele-jqValid">
                                                    <select name="city_id" data-toggle="select2" class="select2 form-control"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Pincode</label>
                                                <div>
                                                    <input type="text" name="pincode" class="form-control" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 row">
                                            <div class="col-2">
                                                <img src="" id="user-img-profile" height="50px" width="50px" alt="user" class="rounded-circle mt-4">
                                            </div>
                                            <div class="form-group col-10">
                                                <label>Profile Image</label>
                                                <input type="file" class="filestyle" data-buttonname="btn-secondary" id="file" placeholder="Enter file" name="file">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end customer row -->

                    <div class="form-group">
                        <div>
                            <button type="submit" class="btn m-r-5 btn-sw">
                                Submit
                            </button>
                            <a href="<?php echo base_url(); ?>employee/supervisor/dashboard" class="btn btn-secondary waves-effect">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var formActionUrl;
</script>

<!-- Custom Js -->
<script type="text/javascript" src="<?php echo base_url('assets/js/include/employee/check_login.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/employee/supervisor/profile_form.js'); ?>"></script>

<script>
    $(function() {
        $('[data-toggle="select2"]').select2();

        function loadInitFunctions() {
            formActionUrl = formApiUrl('employee/supervisor/profile/edit');
            loadUserDetail();
        }

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