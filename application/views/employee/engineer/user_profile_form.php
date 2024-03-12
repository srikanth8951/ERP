<?php 
    $view_type = $view_type ?? ''; 
    $employee_id = $employee_id ?? 0; 
?>
<div class="page-content-wrapper">
    <div class="container-fluid" id="engineer--details--area">
        <div class="row">
            <div class="col-lg-12">
                <form id="employeeForm" action="#">
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
                                                <div>
                                                    <input type="text" name="city" class="form-control" placeholder="" />
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
                  

                    <div class="form-group">
                        <div>
                            <button type="submit" class="btn m-r-5 btn-sw">
                                Submit
                            </button>
                            <button type="reset" class="btn btn-secondary waves-effect">
                                Cancel
                            </button>
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
<script type="text/javascript" src="<?php echo base_url('assets/js/include/employee/check_login.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/employee/engineer/profile_form.js'); ?>"></script>

<script>
    $(function() {
        $('[data-toggle="select2"]').select2();

        function loadInitFunctions() {
            if (emp_form_type == 'edit') {
                formActionUrl = formApiUrl('employee/engineer/profile/edit', {
                    employee_id: employee_id
                });
                loadEmpDetail();
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