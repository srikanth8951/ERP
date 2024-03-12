<?php
$view_type = $view_type ?? '';
$employee_id = $employee_id ?? 0;
?>
<div class="page-content-wrapper">
    <div class="container-fluid" id="empasdhead--deatils--area">
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
                    <a href="<?php echo base_url(); ?>admin/asd_head" class="btn btn-outline-secondary mt-1"><i class="mdi mdi-chevron-double-left"></i> Back</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <form id="employeeASDHeadForm" action="#">

                    <div class="card m-b-20">
                        <div class="card-body">
                            <h6>PERSONAL DETAILS</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>First Name<span class="text-danger"> *</span></label>
                                        <input type="text" name="first_name" class="form-control" placeholder="" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Last Name<span class="text-danger"> *</span></label>
                                        <input type="text" name="last_name" class="form-control" placeholder="" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Email ID<span class="text-danger"> *</span></label>
                                        <input type="email" name="email" class="form-control" placeholder="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card m-b-20">
                                <div class="card-body">
                                    <h6>ADDRESS DETAILS</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Country</label>
                                                <div class="ele-jqValid">
                                                    <select name="country_id" data-toggle="select2" class="select2 form-control"></select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>State</label>
                                                <div class="ele-jqValid">
                                                    <select name="state_id" data-toggle="select2" class="select2 form-control"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Mobile<span class="text-danger"> *</span></label>
                                                <div class="input-group">
                                                    <span id="country-dial-code" class="input-group-text input-group-prepend"></span>
                                                    <input type="text" name="mobile" class="form-control" placeholder="" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Address</label>
                                                <div>
                                                    <textarea name="address" class="form-control" rows="4"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>City</label>
                                                <div class="ele-jqValid">
                                                    <select name="city_id" data-toggle="select2" class="select2 form-control"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Pincode</label>
                                                <div>
                                                    <input type="text" name="pincode" class="form-control" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card m-b-20">
                                <div class="card-body">
                                    <h6>CREDENTIALS</h6>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Username<span class="text-danger"> *</span></label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                <input type="text" name="username" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Password<?php echo $view_type == 'add' ? '<span class="text-danger">&nbsp;*</span>' : ''; ?></label>
                                                <input type="text" name="password" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-secondary waves-effect password-generate">Generate</button>
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
                                    <h6>OFFICIAL DETAILS</h6>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Employee ID</label>
                                            <div class="input-group has-validation">
                                                <input type="text" name="emp_id" class="form-control" id="validationEmployeeID">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
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

                                        <div class="col-md-3">
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
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Date of Joining </label>
                                                <div>
                                                    <input type="text" id="datetimepicker" name="joining_date" class="form-control" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="row">
                                                <div class="col-10 form-group ele-jqValid">
                                                    <label>Department</label>
                                                    <select name="department_id" data-toggle="select2" class="select2 form-control"></select>
                                                </div>
                                                <div class="col-2 form-group h-100 mt-auto pl-1">
                                                    <button type="button" id="btn-add-user-department" class="btn btn-light waves-effect">+</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="row">
                                                <div class="col-10 form-group ele-jqValid">
                                                    <label>Designation</label>
                                                    <select name="designation_id" data-toggle="select2" class="select2 form-control"></select>
                                                </div>
                                                <div class="col-2 form-group h-100 mt-auto pl-1">
                                                    <button type="button" class="btn btn-light waves-effect" id="btn-add-user-designation">+</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="row">
                                                <div class="col-10 form-group ele-jqValid">
                                                    <label>Work Expertise</label>
                                                    <select name="work_expertise" data-toggle="select2" class="select2 form-control"></select>
                                                </div>
                                                <div class="col-2 form-group h-100 mt-auto pl-1">
                                                    <button type="button" class="btn btn-light waves-effect" id="btn-add-user-work-expertise">+</button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Reporting Manager</label>
                                                <select name="reporting_manager" data-toggle="select2" class="select2 form-control"></select>
                                            </div>
                                        </div> -->

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
                            <a href="<?php echo base_url(); ?>admin/asd_head" class="btn btn-secondary waves-effect">
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
    const employee_id = "<?php echo $employee_id; ?>";
    const emp_form_type = "<?php echo $view_type; ?>";
    var formActionUrl;
</script>

<!-- Custom Js -->
<script type="text/javascript" src="<?php echo base_url('assets/js/include/check_login.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/admin/module/region.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/admin/module/branch.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/admin/module/designation.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/admin/module/department.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/admin/module/work_expertise.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/admin/asd_head_form.js'); ?>"></script>

<script>
    $(function() {
        $('[data-toggle="select2"]').select2();
        moment().format();
        $('#datetimepicker').datetimepicker({
            timepicker: false,
            formatDate: 'd/m/Y',
            maxDate: moment()
        });

        function loadInitFunctions() {
            if (emp_form_type == 'edit') {
                formActionUrl = formApiUrl('admin/employee/asd_head/edit', {
                    employee_id: employee_id
                });
                loadEmpDetail();
            } else {
                formActionUrl = formApiUrl('admin/employee/asd_head/add');
                // Load autocompletes
                loadAutocompleteRegions();
                loadAutocompleteDepartments();
                loadAutocompleteDesignations();
                loadAutocompleteCountry({
                    selected: [101]
                });
                loadAutocompleteWorkExpertise();
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