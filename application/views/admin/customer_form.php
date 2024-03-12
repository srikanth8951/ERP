<?php
$view_type = $view_type ?? '';
$customer_id = $customer_id ?? 0;
?>
<style>
    .pac-container {
        z-index: 10000 !important;
    }
</style>
<div class="page-content-wrapper">

    <div class="container-fluid" id="customer--deatils--area">
        <div class="card card-body p-2 mb-2">
            <div class="row">
                <div class="col px-4">
                    <?php if ($view_type == 'add') { ?>
                        <h5>Add</h5>
                    <?php } else { ?>
                        <h5>Edit</h5>
                    <?php } ?>
                </div>
                <div class="col text-right">
                    <a href="<?php echo base_url(); ?>admin/customer" class="btn btn-outline-secondary mt-1"><i class="mdi mdi-chevron-double-left"></i> Back</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <form id="customerForm" action="#">
                    <!-- customer row -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card m-b-20">
                                <div class="card-body">
                                    <h6>CUSTOMER DETAILS</h6>
                                    <hr />
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Company Name/Customer Name<span class="text-danger"> *</span></label>
                                                <input type="text" name="company_name" class="form-control" placeholder="" />
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Customer Sector</label>
                                                <div class="ele-jqValid">
                                                    <select name="sector" data-toggle="select2" class="select2 form-control"></select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Username<span class="text-danger"> *</span></label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                <input type="text" name="username" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="form-group col-8">
                                                    <label>Password<?php echo $view_type == 'add' ? '<span class="text-danger">&nbsp;*</span>' : ''; ?></label>
                                                    <input type="text" name="password" class="form-control">
                                                </div>
                                                <div class="col-4 pt-4">
                                                    <button type="button" class="btn btn-secondary waves-effect password-generate mt-1 ml-4">Generate</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Billing Address</label>
                                                    <textarea name="billing_address" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label>Contact Person Name<span class="text-danger"> *</span></label>
                                                    <input type="text" name="billing_address_contact_name" required class="form-control" placeholder="" />
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label>Email ID<span class="text-danger"> *</span></label>
                                                    <input class="form-control" required type="text" name="billing_address_email" />
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label>Country<span class="text-danger"> *</span></label>
                                                    <div class="ele-jqValid">
                                                        <select name="billing_address_country_id" data-toggle="select2" class="select2 form-control" required></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="mobile">Mobile<span class="text-danger"> *</span></label>
                                                    <div class="input-group">
                                                        <span id="billing-address-country-dial-code" class="input-group-text input-group-prepend"></span>
                                                        <input class="form-control" type="text" name="billing_address_mobile">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label>State</label>
                                                    <div class="ele-jqValid">
                                                        <select name="billing_address_state_id" data-toggle="select2" class="select2 form-control"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="" class="control-label">City</label>
                                                    <div class="ele-jqValid">
                                                        <select name="billing_address_city_id" data-toggle="select2" class="select2 form-control"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label>Pincode</label>
                                                    <input class="form-control" type="text" name="billing_address_pincode">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 row">
                                            <!-- <div class="col-md-12 pb-3">
                                                
                                            </div> -->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Site Address
                                                        <input type="checkbox" class="ml-2" id="same-address">
                                                        <span>Same as billing address</span>
                                                    </label>
                                                    <textarea name="site_address" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label>Contact Person Name</label>
                                                    <input type="text" name="site_address_contact_name" class="form-control" placeholder="" />
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label>Email ID</label>
                                                    <input class="form-control" type="text" name="site_address_email" />
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label>Country</label>
                                                    <div class="ele-jqValid">
                                                        <select name="site_address_country_id" data-toggle="select2" class="select2 form-control"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="mobile">Mobile</label>
                                                    <div class="input-group">
                                                        <span id="site-address-country-dial-code" class="input-group-text input-group-prepend"></span>
                                                        <input class="form-control" type="text" name="site_address_mobile">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label>State</label>
                                                    <div class="ele-jqValid">
                                                        <select name="site_address_state_id" data-toggle="select2" class="select2 form-control"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="" class="control-label">City</label>
                                                    <div class="ele-jqValid">
                                                        <select name="site_address_city_id" data-toggle="select2" class="select2 form-control"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label>Pincode</label>
                                                    <input class="form-control" type="text" name="site_address_pincode">
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
                                        <div class="col-md-4 col-lg-4">
                                            <div class="row">
                                                <div class="col-10 form-group ele-jqValid">
                                                    <label>Term of Payment</label>
                                                    <select name="payment_term" data-toggle="select2" class="select2 form-control"></select>
                                                </div>
                                                <div class="col-2 form-group h-100 mt-auto pl-1">
                                                    <button type="button" class="btn btn-light waves-effect" id="btn-add-payment-term">+</button>
                                                </div>
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
                            <a href="<?php echo base_url(); ?>admin/customer" class="btn btn-secondary waves-effect">
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
    const customer_id = "<?php echo $customer_id; ?>";
    const emp_form_type = "<?php echo $view_type; ?>";
    var formActionUrl;
</script>

<script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyAEm4NxtW21dNCaPQoP8WmNxmsFEqYmWIo" async defer></script>

<!-- Custom Js -->
<script type="text/javascript" src="<?php echo base_url('assets/js/include/check_login.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/admin/module/payment_term.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/admin/module/map_location.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/admin/customer_form.js'); ?>"></script>

<script>
    $(function() {
        $('[data-toggle="select2"]').select2();

        function generateUsername(){
            let name1First; let name2First;
            let name1 = $('#customerForm [name="company_name"]').val();
            let name2 = $('#customerForm [name="billing_address_contact_name"]').val();

            if(parseValue(name1) != '' && parseValue(name2) != '') {
                name1First = (name1.split(" "))[0];
                name2First = (name2.split(" "))[0];

                $('#customerForm [name="username"]').val((name1First + '_' + name2First + '_' + moment().format('hhmmss')).toLowerCase());
            } else {
                $('#customerForm [name="username"]').val('');
            }
        }

        function loadInitFunctions() {
            if (emp_form_type == 'edit') {
                formActionUrl = formApiUrl('admin/customer/edit', {
                    customer_id: customer_id
                });
                loadCustomerDetail();
            } else {
                formActionUrl = formApiUrl('admin/customer/add');
                // Load autocompletes
                loadAutocompleteBillingAddressCountry({
                    selected: [101]
                });
                loadAutocompleteSiteAddressCountry({
                    selected: [101]
                });
                loadAutocompletePaymentTerms();
                loadAutocompleteCustomerSectores();
            }

            // Generate username
            $('#customerForm [name="company_name"]').keyup(function() {
                generateUsername();
            });

            $('#customerForm [name="billing_address_contact_name"]').keyup(function() {
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