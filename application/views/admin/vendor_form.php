<?php
$view_type = $view_type ?? '';
$vendor_id = $vendor_id ?? 0;
?>
<div class="page-content-wrapper" id="vendor--deatils--area">

    <div class="container-fluid">
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
                    <a href="<?php echo base_url(); ?>admin/vendor" class="btn btn-outline-secondary mt-1"><i class="mdi mdi-chevron-double-left"></i> Back</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <form id="vendorForm" action="#" enctype="multipart/form-data">
                    <div class="card m-b-20">
                        <div class="card-body">
                            <h6>VENDOR DETAILS</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Vendor Organization Name<span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control" name="organization_name" placeholder="" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Vendor Code</label>
                                        <input type="text" class="form-control" name="code" placeholder="" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Vendor Contact Person Name<span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control" name="contact_name" placeholder="" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Vendor Group</label>
                                        <input type="text" class="form-control" name="vendor-group" placeholder="" />
                                        <!-- <select name="" id="input" class="form-control" required="required">
                                            <option value="">HVAC</option>
                                            <option value="">Electrical</option>
                                            <option value="">ibms</option>
                                            <option value="">FF</option>
                                            <option value="">FAS</option>
                                            <option value="">Plumbing</option>
                                            <option value="">Others</option>
                                        </select> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Address1</label>
                                        <textarea name="address1" id="input" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Address2</label>
                                        <textarea name="address2" id="input" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Country</label>
                                        <div class="ele-jqValid">
                                            <select name="country_id" data-toggle="select2" class="select2 form-control"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>State</label>
                                        <div class="ele-jqValid">
                                            <select name="state_id" data-toggle="select2" class="select2 form-control"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="">City</label>
                                        <div class="ele-jqValid">
                                            <select name="city_id" data-toggle="select2" class="select2 form-control"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Mobile No<span class="text-danger"> *</span></label>
                                        <div class="input-group">
                                            <span id="country-dial-code" class="input-group-text input-group-prepend"></span>
                                            <input id="mobile" class="form-control" type="text" name="mobile">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Phone No</label>
                                        <div class="input-group">
                                            <input id="phone" class="form-control" type="phone" name="phone">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Pincode</label>
                                        <input class="form-control" type="text" name="pincode">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Email ID<span class="text-danger"> *</span></label>
                                        <input class="form-control" type="text" name="email">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>GST No</label>
                                        <input class="form-control" type="text" name="gst_number">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>PAN/SSN No</label>
                                        <input class="form-control" type="text" name="pan_number">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Website</label>
                                        <input class="form-control" type="text" name="website">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-10 form-group ele-jqValid">
                                            <label>Region<span class="text-danger"> *</span></label>
                                            <select name="region_id" data-toggle="select2" class="select2 form-control"></select>
                                        </div>
                                        <div class="col-2 form-group h-100 mt-auto pl-1">
                                            <button type="button" id="btn-add-user-region" class="btn btn-light waves-effect">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-10 form-group ele-jqValid">
                                            <label>Branch<span class="text-danger"> *</span></label>
                                            <select name="branch_id" data-toggle="select2" class="select2 form-control"></select>
                                        </div>
                                        <div class="col-2 form-group h-100 mt-auto pl-1">
                                            <button type="button" id="btn-add-user-branch" class="btn btn-light waves-effect">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-10 form-group ele-jqValid">
                                            <label>City<span class="text-danger"> *</span></label>
                                            <select name="area_id" data-toggle="select2" class="select2 form-control"></select>
                                        </div>
                                        <div class="col-2 form-group h-100 mt-auto pl-1">
                                            <button type="button" id="btn-add-user-area" class="btn btn-light waves-effect">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card m-b-20">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Vendor Evaluation</label>
                                                <div class="form-group mb-0">
                                                    <input type="file" name="vendor_evaluation[]" class="multi" />
                                                    <input type="hidden" name="existingAttachments" class="hiddenAttach">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card m-b-20">
                                        <div class="card-body">
                                            <h6>BANK DETAILS</h6>
                                            <div class="row">
                                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="">Bank name</label>
                                                        <input id="" class="form-control" type="text" name="bank_name">
                                                    </div>
                                                </div>

                                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="">Name As per Bank </label>
                                                        <input id="" class="form-control" type="text" name="bank_account_person_name">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label>Account No.</label>
                                                        <input id="m-input" class="form-control" type="text" name="bank_account_number">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label>Branch Name</label>
                                                        <input id="my-inwut" class="form-control" type="text" name="bank_branch_name">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label>IFSC Code</label>
                                                        <input id="my-indput" class="form-control" type="text" name="bank_ifsc_code">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn m-r-5 btn-sw">
                                    Submit
                                </button>
                                <a href="<?php echo base_url(); ?>admin/vendor" class="btn btn-secondary waves-effect">
                                    Cancel
                                </a>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>

</div>
</div>
</div>

<script>
    const vendor_id = "<?php echo $vendor_id; ?>";
    const emp_form_type = "<?php echo $view_type; ?>";
    var formActionUrl;
</script>

<script src="<?php echo base_url('assets/plugins/multifile-master/jquery.MultiFile.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/check_login.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/admin/module/region.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/admin/module/branch.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/admin/module/area.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/admin/vendor_form.js'); ?>"></script>

<script>
    $(function() {

        $('[data-toggle="select2"]').select2();
        // $('#files-multi-upload').MultiFile({});

        function loadInitFunctions() {
            if (emp_form_type == 'edit') {
                formActionUrl = formApiUrl('admin/vendor/edit', {
                    vendor_id: vendor_id
                });
                loadVendorDetail();
            } else {
                formActionUrl = formApiUrl('admin/vendor/add');
                // Load autocompletes
                loadAutocompleteRegions();
                loadAutocompleteCountry({
                    selected: [101]
                });

            }
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