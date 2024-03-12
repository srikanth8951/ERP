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
                    <a href="<?php echo base_url(); ?>customer/dashboard" class="btn btn-outline-secondary mt-1"><i class="mdi mdi-chevron-double-left"></i> Back</a>
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
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Company Name/Customer Name<span class="text-danger"> *</span></label>
                                                <input type="text" name="company_name" class="form-control" placeholder="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Customer Sector</label>
                                                <div class="ele-jqValid">
                                                    <select name="sector" data-toggle="select2" class="select2 form-control"></select>
                                                </div>
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
                                        <div class="col-md-12 row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Billing Address</label>
                                                    <textarea name="billing_address" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Contact Person Name<span class="text-danger"> *</span></label>
                                                    <input type="text" name="billing_address_contact_name" required class="form-control" placeholder="" />
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Email ID<span class="text-danger"> *</span></label>
                                                    <input class="form-control" required type="text" name="billing_address_email" />
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Country<span class="text-danger"> *</span></label>
                                                    <div class="ele-jqValid">
                                                        <select name="billing_address_country_id" data-toggle="select2" class="select2 form-control" required></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label for="mobile">Mobile<span class="text-danger"> *</span></label>
                                                    <div class="input-group">
                                                        <span id="billing-address-country-dial-code" class="input-group-text input-group-prepend"></span>
                                                        <input class="form-control" type="text" name="billing_address_mobile">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>State</label>
                                                    <div class="ele-jqValid">
                                                        <select name="billing_address_state_id" data-toggle="select2" class="select2 form-control"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label for="" class="control-label">City</label>
                                                    <div class="ele-jqValid">
                                                        <select name="billing_address_city_id" data-toggle="select2" class="select2 form-control"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Pincode</label>
                                                    <input class="form-control" type="text" name="billing_address_pincode">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label>Website</label>
                                                <input class="form-control" type="text" name="website">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label>GST No</label>
                                                <input type="text" class="form-control" name="gst_number" id="">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label>PAN/SSN No</label>
                                                <input type="text" class="form-control" name="pan_number" id="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 row">
                                            <div class="col-2">
                                                <img src="" id="user-img-profile" height="50px" width="50px" alt="user" class="rounded-circle ">
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
                            <a href="<?php echo base_url(); ?>customer/dashboard" class="btn btn-secondary waves-effect">
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


<!--C3 Chart-->
<script src="<?php echo base_url() ?>assets/plugins/d3/d3.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/c3/c3.min.js"></script>
<script src="<?php echo base_url() ?>assets/pages/c3-chart-init.js"></script>

<script src="<?php echo base_url() ?>assets/plugins/sparklines-chart/jquery.sparkline.min.js"></script>
<script src="<?php echo base_url() ?>assets/pages/sparklines.init.js"></script>

<!-- Custom Js -->
<script type="text/javascript" src="<?php echo base_url('assets/js/include/customer/check_login.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/customer/profile_form.js'); ?>"></script>

<script>
    $(function() {
        $('[data-toggle="select2"]').select2();

        function generateUsername() {
            let name1First;
            let name2First;
            let name1 = $('#userProfileForm [name="company_name"]').val();
            let name2 = $('#userProfileForm [name="billing_address_contact_name"]').val();

            if (parseValue(name1) != '' && parseValue(name2) != '') {
                name1First = (name1.split(" "))[0];
                name2First = (name2.split(" "))[0];

                $('#userProfileForm [name="username"]').val((name1First + '_' + name2First + '_' + moment().format('hhmmss')).toLowerCase());
            } else {
                $('#userProfileForm [name="username"]').val('');
            }
        }

        function loadInitFunctions() {
            loadUserDetail(formApiUrl('customer/profile/detail'));

            // Generate username
            $('#userProfileForm [name="company_name"]').keyup(function() {
                generateUsername();
            });

            $('#userProfileForm [name="billing_address_contact_name"]').keyup(function() {
                generateUsername();
            });
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