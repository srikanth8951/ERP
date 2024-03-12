<?php
$view_type = $view_type ?? '';
$contract_job_id = $contract_job_id ?? 0;
$back_contract_job_id = $back_contract_job_id ?? 0;
?>
<style>
    .pac-container {
        z-index: 10000 !important;
    }

    .customer-map-location {
        height: 6.5rem;
        padding: 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        overflow-y: auto;
    }
</style>

<div class="page-content-wrapper">

    <div class="container-fluid">
        <div class="card card-body p-2 mb-2">
            <div class="row">
                <div class="col px-4">
                    <h5 class="mb-2">View</h5>
                </div>
                <div class="col text-right">
                    <a href="<?php echo base_url('employee/regionHead/contract_job_log/' . $back_contract_job_id); ?>" class="btn btn-outline-secondary waves-effect waves-light"><i class="mdi mdi-chevron-double-left"></i> Back</a>
                </div>
            </div>
        </div>

        <div class="job_cls">
            <div class="" action="#" id="contractjob--deatils--area">

                <!-- customer row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card m-b-20">
                            <div class="card-body customer-detail-area">
                                <div class="row mb-2">
                                    <div class="col">
                                        <h6>CUSTOMER DETAILS</h6>
                                    </div>
                                    <!-- <div class="col text-right">
                                        <div class="btn-group" id="select-customer-type">
                                            <button class="ctype btn btn-outline-purple waves-effect waves-light active" type="button" data-customer-type="new">New Customer</button>
                                            <button class="ctype btn btn-outline-purple waves-effect waves-light" type="button" data-customer-type="exist">Existing Customer</button>
                                            <input type="hidden" name="customer_type" value="new" />
                                            <input type="hidden" name="customer_id" value="0" />
                                        </div>
                                    </div> -->
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Company Name</label>
                                            <label id="customer-company-name" class="form-control"></label>
                                        </div>
                                    </div>
                                    <!-- <div class="col-4">
                                        <div class="form-group">
                                            <label>Customer Job No.</label>
                                            <label id="customer-job-number" class="form-control"></label>
                                        </div>
                                    </div> -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Customer Sector</label>
                                            <div class="ele-jqValid">
                                                <label id="customer-sector" class="form-control"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text">@</span>
                                            <label id="customer-username" class="form-control"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Password</label>
                                        <div class="input-group">
                                            <label id="customer-password" class="form-control">******</label>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="mt-4">BILLING ADDRESS DETAILS</h6>
                                <hr />
                                <div class="row">
                                    <div class="col-12 row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Billing address</label>
                                                <textarea id="customer-billing-address" class="form-control bg-transparent" readonly></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-6">
                                            <div class="form-group">
                                                <label>Contact Person Name</label>
                                                <label id="customer-billing-address-contact-name" class="form-control"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-6">
                                            <div class="form-group">
                                                <label>Email ID</label>
                                                <label class="form-control" id="customer-billing-address-email"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label>Country</label>
                                                <label id="customer-billing-address-country" class="form-control"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="mobile">Mobile</label>
                                                <label id="customer-billing-address-mobile" class="form-control"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label>State</label>
                                                <label id="customer-billing-address-state" class="form-control"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="city" class="control-label">City</label>
                                                <label id="customer-billing-address-city" class="form-control"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="pincode">Pincode</label>
                                                <label id="customer-billing-address-pincode" class="form-control"></label>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <h6>SITE ADDRESS DETAILS</h6>
                                <hr />
                                <div class="row">
                                    <div class="col-12 row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Site address</label>
                                                <textarea id="customer-site-address" class="form-control bg-transparent" readonly></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-6">
                                            <div class="form-group">
                                                <label>Contact Person Name</label>
                                                <label id="customer-site-address-contact-name" class="form-control"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-6">
                                            <div class="form-group">
                                                <label>Email ID</label>
                                                <label class="form-control" id="customer-site-address-email"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label>Country</label>
                                                <label id="customer-site-address-country" class="form-control"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="mobile">Mobile</label>
                                                <label id="customer-site-address-mobile" class="form-control"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label>State</label>
                                                <label id="customer-site-address-state" class="form-control"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="city" class="control-label">City</label>
                                                <label id="customer-site-address-city" class="form-control"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="pincode">Pincode</label>
                                                <label id="customer-site-address-pincode" class="form-control"></label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Website</label>
                                            <label id="customer-website" class="form-control"></label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>GST No</label>
                                            <label id="customer-gst-number" class="form-control"></label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>PAN No</label>
                                            <label id="customer-pan-number" class="form-control"></label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Term of Payment</label>
                                            <label id="customer-payment-term" class="form-control"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end customer row -->

                <!-- job row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card m-b-20">
                            <div class="card-body">
                                <h6>JOB / CONTRACT DETAILS (<span id="job-number"></span>)</h6>
                                <hr />
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Job Title</label>
                                            <label id="job-title" class="form-control"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>SAP Ref Job No</label>
                                            <label id="sap-job-number" class="form-control"></label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>PO No.</label>
                                            <label id="po-number" class="form-control"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nature of Contract</label>
                                            <label id="contract-nature" class="form-control"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Type of Contract</label>
                                            <label id="contract-type" class="form-control"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="waranty">No of People Deployed</label>
                                            <label id="deployed-people-number" class="form-control"></label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="waranty">PPM Frequency</label>
                                            <label id="ppm-frequency" class="form-control"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>CAM</label>
                                            <label id="customer-account-manager" class="form-control"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Engineer</label>
                                            <label id="engineer" class="form-control"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Contract Currency</label>
                                            <label id="contract-currency" class="form-control"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Contract value(basic)</label>
                                            <label id="contract-value" class="form-control"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>GST value</label>
                                            <label id="contract-gst-value" class="form-control"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Total contract value</label>
                                            <label id="total-contract-value" class="form-control"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>EGM</label>
                                            <label id="expected-gross-margin" class="form-control"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Contract Status</label>
                                            <label id="contract-status" class="form-control"></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="contract_period" style="display: none;">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="contract-period-from">Start date</label>
                                                <label id="contract-period-fromdate" class="form-control"></label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="contract-period-to">End date</label>
                                                <label id="contract-period-todate" class="form-control"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <label>Location</label>
                                            <div class="form-group job-map-location">
                                                <!-- <p class="mb-1"><label class="mb-0">Address:</label></p> -->
                                                <p class="mb-0"><label class="mb-0">Lattitude: <span id="job-location-latitude"></span></label></p>
                                                <p class="mb-0"><label class="mb-0">Longitude: <span id="job-location-longitude"></span></label></p>
                                                <p class="mb-0"><label class="mb-0">Range: <span id="job-location-range"></span></label></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="div_detail"></div>
                    </div>
                </div>
                <!-- end job row -->

                <!-- Asset row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card m-b-20">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <h6>ASSET DETAILS</h6>
                                <!-- <div>
                                    <button type="button" class="btn btn-outline-purple btn-sm" id="btn_add_asset"><i class="mdi mdi-plus"></i>&nbsp;Add</button>
                                    <button type="button" class="btn btn-outline-purple btn-sm" id="btn_exist_asset"><i class="mdi mdi-view-list"></i>&nbsp;Exist</button>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div id="asset_div">
                    <div class="row asset_row" id="asset-row-empty">
                        <div class="col-lg-12">
                            <div class="card m-b-20">
                                <div class="card-body">
                                    <p class="mb-0">No asset available</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End asset row -->
            </div>

        </div>


    </div>
</div>

<script>
    const contract_job_id = "<?php echo $contract_job_id; ?>";
    const emp_form_type = "<?php echo $view_type; ?>";
    var formActionUrl;
    $(function() {
        window.contractJobDetailArea = $('#contractjob--deatils--area');
    });
</script>

<script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyAEm4NxtW21dNCaPQoP8WmNxmsFEqYmWIo" async defer></script>

<!-- Custom Js -->
<script src="<?php echo base_url('assets/js/include/check_login.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/admin/contract_job_autocomplete.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/admin/module/map_location.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/employee/regionHead/contract_job_view.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/admin/contract_job_asset.js'); ?>"></script>
<script>
    $(function() {
        $('[data-toggle="select2"]').select2();
        moment().format();

        function loadInitFunctions() {
            loadContractJobDetail();
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