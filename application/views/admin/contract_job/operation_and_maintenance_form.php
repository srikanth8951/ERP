<?php
$view_type = $view_type ?? '';
$contract_job_id = $contract_job_id ?? 0;
$ppm_frequencies = getPPMFrequencies();
?>
<style>
    .pac-container {
        z-index: 10000 !important;
    }

    .form-control:disabled {
        background-color: transparent !important;
    }

    .card .card-body.asset-checklist-body {
        overflow: auto;
    }
</style>

<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="card card-body p-2 mb-2">
            <div class="row">
                <div class="col px-4">
                    <?php
                    switch ($view_type) {
                        case 'renew':
                            $view_title = 'Renew';
                            break;
                        case 'update':
                            $view_title = 'Update';
                            break;
                        default:
                            $view_title = 'Add';
                    }
                    ?>
                    <h5><?php echo $view_title; ?></h5>
                </div>
                <div class="col text-right">
                    <a href="<?php echo base_url('admin/contract_job/operation_and_maintenance'); ?>" class="btn btn-outline-secondary waves-effect waves-light"><i class="mdi mdi-chevron-double-left"></i> Back</a>
                </div>
            </div>
        </div>
        <div class="job_cls">
            <form class="" action="#" id="contract-job-form">
                <!-- customer row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card m-b-20">
                            <div class="card-body customer-detail-area">
                                <div class="row mb-2">
                                    <div class="col">
                                        <h6>CUSTOMER DETAILS</h6>
                                    </div>
                                    <?php if ($view_type == 'add') { ?>
                                        <div class="col text-right">
                                            <div class="btn-group" id="select-customer-type">
                                                <button class="ctype btn btn-outline-purple waves-effect waves-light active" type="button" data-customer-type="new">New Customer</button>
                                                <button class="ctype btn btn-outline-purple waves-effect waves-light" type="button" data-customer-type="exist">Existing Customer</button>
                                                <input type="hidden" name="customer_type" value="new" />
                                                <input type="hidden" name="customer_id" value="0" />
                                            </div>
                                        </div>
                                    <?php } elseif ($view_type == 'update' || $view_type == 'renew') { ?>
                                        <div class="col text-right">
                                            <div class="btn-group" id="select-customer-type">
                                                <button class="ctype btn btn-outline-purple waves-effect waves-light" type="button" data-customer-type="new">New Customer</button>
                                                <button class="ctype btn btn-outline-purple waves-effect waves-light active" type="button" data-customer-type="exist">Existing Customer</button>
                                                <input type="hidden" name="customer_type" value="exist" />
                                                <input type="hidden" name="customer_id" value="0" />
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Company Name/Customer Name<span class="text-danger"> *</span></label>
                                            <input type="text" name="customer_company_name" class="form-control" placeholder="" />
                                        </div>
                                    </div>
                                    <!-- <div class="col-4">
                                        <div class="form-group">
                                            <label>Customer Job No.</label>
                                            <input type="text" name="customer_job_number" class="form-control" placeholder="" />
                                        </div>
                                    </div> -->
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Customer Sector</label>
                                            <div class="ele-jqValid">
                                                <select name="customer_sector" data-toggle="select2" class="select2 form-control"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Username<span class="text-danger"> *</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">@</span>
                                            <input type="text" name="customer_username" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group col-8">
                                                <label>Password<?php echo $view_type == 'add' ? '<span class="text-danger">&nbsp;*</span>' : ''; ?></label>
                                                <input type="text" name="customer_password" class="form-control">
                                            </div>
                                            <div class="col-4 pt-4">
                                                <button type="button" class="btn btn-secondary waves-effect password-generate mt-1 ml-4">Generate</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h6>BILLING ADDRESS DETAILS</h6>
                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Billing address</label>
                                            <textarea name="customer_billing_address" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Contact Person Name<span class="text-danger"> *</span></label>
                                            <input type="text" name="customer_billing_address_contact_name" required class="form-control" placeholder="" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Email ID<span class="text-danger"> *</span></label>
                                            <input class="form-control" required type="text" name="customer_billing_address_email" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Country<span class="text-danger"> *</span></label>
                                            <div class="ele-jqValid">
                                                <select name="customer_billing_address_country" data-toggle="select2" class="select2 form-control" required></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="mobile">Mobile<span class="text-danger"> *</span></label>
                                            <div class="input-group">
                                                <span id="billing_address_country-dial-code" class="input-group-text input-group-prepend"></span>
                                                <input id="customer_billing_address_mobile" class="form-control" type="text" name="customer_billing_address_mobile">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>State</label>
                                            <div class="ele-jqValid">
                                                <select name="customer_billing_address_state" data-toggle="select2" class="select2 form-control"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="city" class="control-label">City</label>
                                            <div class="ele-jqValid">
                                                <select name="customer_billing_address_city" data-toggle="select2" class="select2 form-control"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Pincode</label>
                                            <input id="" class="form-control" type="text" name="customer_billing_address_pincode">
                                        </div>
                                    </div>
                                </div>

                                <h6>SITE ADDRESS DETAILS</h6>
                                <hr />

                                <div class="row">
                                    <div class="col-md-12 pb-3">
                                        <input type="checkbox" id="same-address">
                                        <span>Same as billing address</span>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Site address</label>
                                            <textarea name="customer_site_address" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Contact Person Name</label>
                                            <input type="text" name="customer_site_address_contact_name" class="form-control" placeholder="" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Email ID</label>
                                            <input class="form-control" type="text" name="customer_site_address_email" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Country</label>
                                            <div class="ele-jqValid">
                                                <select name="customer_site_address_country" data-toggle="select2" class="select2 form-control"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Mobile</label>
                                            <div class="input-group">
                                                <span id="site_address_country-dial-code" class="input-group-text input-group-prepend"></span>
                                                <input id="customer_site_address_mobile" class="form-control" type="text" name="customer_site_address_mobile">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>State</label>
                                            <div class="ele-jqValid">
                                                <select name="customer_site_address_state" data-toggle="select2" class="select2 form-control"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label class="control-label">City</label>
                                            <div class="ele-jqValid">
                                                <select name="customer_site_address_city" data-toggle="select2" class="select2 form-control"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Pincode</label>
                                            <input class="form-control" type="text" name="customer_site_address_pincode">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Website</label>
                                            <input class="form-control" type="text" name="customer_website" />
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>GST No</label>
                                            <input type="text" class="form-control" name="customer_gst_number" id="" />
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>PAN No/SSN No</label>
                                            <input type="text" class="form-control" name="customer_pan_number" id="" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="col-10 form-group ele-jqValid">
                                                <label>Term of Payment</label>
                                                <select name="customer_payment_term" data-toggle="select2" class="select2 form-control"></select>
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

                <!-- job row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card m-b-20">
                            <div class="card-body contractjob-detail-area">
                                <h6>JOB / CONTRACT DETAILS</h6>
                                <hr />
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Job Title<span class="text-danger"> *</span></label>
                                            <select name="job_title" class="form-control select2" data-toggle="select2">
                                                <option value="">Select</option>
                                                <option value="Maintenance Contract">Maintenance Contract</option>
                                                <option value="Operation and Routine Maintenance Contract">Operation and Routine Maintenance Contract</option>
                                                <option value="Maintenance + Operation Contract">Maintenance + Operation Contract</option>
                                            </select>
                                            <!-- <input type="text" name="job_title" class="form-control" required placeholder="" /> -->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>SAP Job No<span class="text-danger"> *</span></label>
                                            <input type="text" name="sap_job_number" class="form-control" required placeholder="" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>PO No.<span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" name="po_number" id="po-number" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nature of Contract</label>
                                            <select name="contract_nature" class="form-control select2" data-toggle="select2">
                                                <option value="" disabled>Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Type of Contract</label>
                                            <select name="contract_type" class="form-control select2" data-toggle="select2">
                                                <option value="" disabled>Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="waranty">No of People Deployed<span class="text-danger"> *</span></label>
                                            <input type="number" class="form-control" name="deployed_people_number" required id="deployed-people-no">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="waranty">PPM Frequency</label>
                                            <!-- <input type="text" class="form-control" name="ppm_frequency" id="ppm-frequency">-->

                                            <select name="ppm_frequency" class="form-control select2" data-toggle="select2" id="ppm-frequency">
                                                <option value="">Select</option>
                                                <?php if ($ppm_frequencies) { ?>
                                                    <?php foreach ($ppm_frequencies as $ppm_frequency) { ?>
                                                        <option value="<?php echo $ppm_frequency['code']; ?>"><?php echo $ppm_frequency['name']; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Site In Charge</label>
                                            <select name="managers" class="form-control select2" data-toggle="select2" multiple>
                                                <option value="" disabled>Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Supervisor</label>
                                            <select name="supervisors" class="form-control select2" data-toggle="select2" multiple>
                                                <option value="" disabled>Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Technicians</label>
                                            <select name="technicians" class="form-control select2" data-toggle="select2" multiple>
                                                <option value="" disabled>Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Contract Currency</label>
                                            <select class="form-control select2" data-toggle="select2" name="contract_currency" id="contract-currency">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Contract value<small>(Basic)</small></label>
                                            <input type="number" class="form-control" name="contract_value" id="contract-value">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>GST percentage(%)</label>
                                            <input type="number" class="form-control" name="contract_gst_value" id="gst-value">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Total contract value</label>
                                            <input type="number" class="form-control" name="contract_value_total" id="contract-value" hidden>
                                            <input type="number" class="form-control" name="total_contact_value" id="contract-value" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>EGM percentage(%)</label>
                                            <input type="number" class="form-control" name="expected_gross_margin" id="expected-gross-margin">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Contract Status</label>
                                            <select name="contract_status" class="form-control select2" data-toggle="select2" id="status-select">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="contract_period" style="display: none;">
                                    <div class="row">
                                        <div class="col-4" id="status1">
                                            <div class="form-group">
                                                <label for="contract-period">Contract Period</label>
                                                <input type="text" class="form-control" name="period" id="contract-period" />
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="contract-period-from">Start date</label>
                                                <div class="input-group">
                                                    <input type="text" data-toggle="datepicker" name="period_fromdate" id="contract-period-from" class="form-control" />
                                                    <span class="input-group-text input-group-append"><i class="mdi mdi-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="contract-period-to">End date</label>
                                                <div class="input-group">
                                                    <input type="text" data-toggle="datepicker" name="period_todate" id="contract-period-to" class="form-control" />
                                                    <span class="input-group-text input-group-append"><i class="mdi mdi-calendar"></i></span>
                                                </div>
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

                <!-- engineer row -->
                <div class="row" id="engineer-details" style="display:none;">
                    <div class="col-lg-12">
                        <div class="card m-b-20">
                            <div class="card-body engineer-detail-area">
                                <div class="row mb-2">
                                    <div class="col">
                                        <h6>ENGINEER DETAILS</h6>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>National Head</label>
                                            <input type="text" name="engineer_nh" class="form-control" placeholder="" />
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>AISD Head</label>
                                            <input type="text" name="engineer_aisd" class="form-control" placeholder="" />
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>Regional Head</label>
                                            <input class="form-control" type="text" name="engineer_rh" />
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>RSD Head</label>
                                            <input type="text" name="engineer_rsd" class="form-control" placeholder="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>ASD Head</label>
                                            <input type="text" name="engineer_asd" class="form-control" placeholder="" />
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>Region</label>
                                            <input class="form-control" type="text" name="engineer_region" />
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="">Area</label>
                                            <input class="form-control" type="text" name="engineer_area" />
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="">Mobile number</label>
                                            <input class="form-control" type="text" name="engineer_contact" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end engineer row -->

                <!-- Google address row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card m-b-20">
                            <div class="card-body job-address-detail-area">
                                <h6>Location<span class="text-danger"> *</span></h6>
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div id="place-search-area">
                                                    <div class="alert alert-info alert-colored"><i class="mdi mdi-alert-circle"></i> Google places api required to enable Place Search</div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 customer-map-location"></div>
                                        </div>

                                        <div class="text-center mb-3">OR</div>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <input type="text" name="job_location_lattitude" class="form-control" placeholder="Lattitude" required />
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" name="job_location_longitude" class="form-control" placeholder="Longitude" required />
                                            </div>
                                            <div class="col-md-4">
                                                <input type="number" name="job_location_range" class="form-control" placeholder="Range (meters)" required />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Google address row end -->

                <!-- Asset row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card m-b-20">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <h6>ASSET DETAILS</h6>
                                <div>
                                    <a href="#" id="btn-asset-upload" class="btn btn-outline-purple btn-sm">Upload</a>
                                    <button type="button" class="btn btn-outline-purple btn-sm" id="btn_add_asset"><i class="mdi mdi-plus"></i>&nbsp;Add</button>
                                </div>
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

                <div class="form-group">
                    <div>
                        <button type="submit" form="contract-job-form" class="btn btn-indigo m-r-5 waves-effect waves-light">Submit</button>

                        <a href="<?php echo base_url(); ?>admin/contract_job/operation_and_maintenance" class="btn btn-secondary waves-effect">
                            Cancel
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Customer modal content -->
<div id="customerModal" class="modal fade" role="dialog" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="customerModalLabel">Customers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Select existing customer</label>
                            <select class="form-control select2" id="customer-select" data-toggle="select2-customer" name="customer_id">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Asset modal content -->
<div id="assetModal" class="modal fade" role="dialog" aria-labelledby="assetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="assetModalLabel">Assets</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Select existing asset</label>
                            <select class="form-control select2" id="asset-select" data-toggle="select2-asset" name="asset_id">
                                <option value="" disabled>Select</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Upload Modal -->
<div class="modal fade" id="assetUploadModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Asset Upload</h5>
                <button data-dismiss="modal" class="close">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" id="assetUploadForm">
                    <div class="form-group row">
                        <div class="col">
                            <input type="file" class="filestyle upload-file" accept=".xlsx,.xls,.csv" data-buttonname="btn-secondary" required id="file" placeholder="Enter file" name="file">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <a class="btn btn-link btn-sm" id="btn-download-upload-sample" href="javascript:void(0);"><i class="fa fa-file-excel-o"></i>&nbsp;Download sample</a>
                <button form="assetUploadForm" type="submit" class="btn btn-primary btn-add-assets"><i class="fa fa-plus"></i> Add</button>
                <span class="btn-loading" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading</span>
            </div>
        </div>
    </div>
</div>

<div id="assetListModal" class="modal fade add-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Assets List</h4>
            </div>
            <span id="duplicate-msg" style="text-align: center;color:#f02121;margin: 10px;"></span>

            <form id="uploadForm" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/formdata">
                <div class="modal-body" style="overflow: scroll;max-height: 400px;">
                    <div class="table-responsive-sm mt-6">
                        <table id="assetTableId" class="table table-striped table-centered mb-0">
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="asset-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end upload -->

<script>
    const contract_job_id = "<?php echo $contract_job_id; ?>";
    const emp_form_type = "<?php echo $view_type; ?>";
    var formActionUrl;
    window.contractJobDetailArea = '';
    window.contractJobForm = '';
    window.customerModal = '';

    $(function() {
        contractJobDetailArea = $('#contractjob--deatils--area');
        contractJobForm = $('#contract-job-form');
        customerModal = $('#customerModal');
    });
</script>

<!-- <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyAEm4NxtW21dNCaPQoP8WmNxmsFEqYmWIo" async defer></script> -->

<script src="<?php echo base_url('assets/plugins/SheetJS/xlsx.full.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js'); ?>"></script>

<!-- Custom Js -->
<script src="<?php echo base_url('assets/js/include/check_login.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/admin/contract_job/operation_and_maintenance_autocomplete.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/admin/module/payment_term.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/admin/module/map_location.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/admin/contract_job/operation_and_maintenance_asset.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/admin/contract_job/operation_and_maintenance_asset_upload.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/admin/contract_job/operation_and_maintenance_form.js'); ?>"></script>

<script>
    $(function() {
        $('[data-toggle="select2"]').select2();

        function generateUsername(){
            let name1First; let name2First;
            let name1 = contractJobForm.find('[name="customer_company_name"]').val();
            let name2 = contractJobForm.find('[name="customer_billing_address_contact_name"]').val();

            if(parseValue(name1) != '' && parseValue(name2) != '') {
                name1First = (name1.split(" "))[0];
                name2First = (name2.split(" "))[0];

                contractJobForm.find('[name="customer_username"]').val((name1First + '_' + name2First + '_' + moment().format('hhmmss')).toLowerCase());
            } else {
                contractJobForm.find('[name="customer_username"]').val('');
            }
        }

        async function loadInitFunctions() {
            if (emp_form_type == 'update') {
                formActionUrl = formApiUrl('admin/contract_job/update', {
                    contract_job_id: contract_job_id
                });
                loadContractJobDetail();
            } else if (emp_form_type == 'renew') {
                formActionUrl = formApiUrl('admin/contract_job/renew', {
                    contract_job_id: contract_job_id
                });
                loadContractJobDetail();
            } else {
                formActionUrl = formApiUrl('admin/contract_job/add');

                // Load autocompletes
                // Customer Billing Address
                await loadAutocompleteAddressCountries({
                    element: $('#contract-job-form [name="customer_billing_address_country"]'),
                    params: {
                        selected: [101]
                    }
                });
                await loadAutocompleteAddressStates({
                    element: $('#contract-job-form [name="customer_billing_address_state"]'),
                    params: {
                        country_id: 101
                    }
                });

                // Customer Site Address
                await loadAutocompleteAddressCountries({
                    element: $('#contract-job-form [name="customer_site_address_country"]'),
                    params: {
                        selected: [101]
                    }
                });
                await loadAutocompleteAddressStates({
                    element: $('#contract-job-form [name="customer_site_address_state"]'),
                    params: {
                        country_id: 101
                    }
                });

                loadAutocompletePaymentTerms();
                loadAutocompleteCustomerSectores();
                loadAutocompleteContractNature();
                loadAutocompleteCurrency({
                    selected: [103]
                });
                loadAutocompleteCAM();
                loadAutocompleteContractType();
                loadAutocompleteContractStatus();

                // Load asset view
                // loadAssetView({
                //     type: 'new',
                //     remove: true
                // });
            }

            // Load & Init Google place search
            // getPlaceSearchDetail({
            //     'type': 'inline',
            //     'element': '#place-search-area'
            // }).then(function(data) {
            //     contractJobForm.find('[name="job_location_lattitude"]').val(data.geometry.location.lat());
            //     contractJobForm.find('[name="job_location_longitude"]').val(data.geometry.location.lng());
            //     contractJobForm.find(".customer-map-location").html(`<p class="mb-1">
            //         <label class="mb-0">Address:</label> ${data.formatted_address}</p>`);
            // });

            $('[data-toggle="datepicker"]').datetimepicker({
                format: 'd/m/Y',
                timepicker: false,
                mask: true
            });

            // Generate username
            contractJobForm.find('[name="customer_company_name"]').keyup(function() {
                generateUsername();
            });

            contractJobForm.find('[name="customer_billing_address_contact_name"]').keyup(function() {
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