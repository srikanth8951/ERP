<!-- C3 charts css -->
<link href="<?= base_url() ?>assets/plugins/c3/c3.min.css" rel="stylesheet" type="text/css" />

<div class="page-content-wrapper">

    <div class="container-fluid" id="empcustomer--deatils--area">
        <style>
            .img_cls {
                width: 100px;
                border-radius: 6px;
            }
        </style>

        <div class="card card-body p-2 mb-2">
            <div class="row">
                <div class="col px-4">
                    <h5>View</h5>
                </div>
                <div class="col text-right">
                    <a href="<?php echo base_url(); ?>employee/cam/customer" class="btn btn-outline-secondary mt-1"><i class="mdi mdi-chevron-double-left"></i> Back</a>
                </div>
            </div>
        </div>

        <section id="empCustomerDetailArea" style="display: none;">
            <div class="row">
                <div class="col-4" data-employee-detail="customer">
                    <div class="card m-b-20">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <img class="img_cls" src="<?php echo base_url('assets/images/users/avatar.png'); ?>">
                                </div>
                                <div class="col-7 align-self-end">
                                    <b>Customer User</b><br>
                                    EMP-1001<br>
                                    All India
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-1">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <div class="col-11">
                                    <div class="pl-2">admin@gmail.com</div>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-1">
                                    <i class="fa fa-mobile"></i>
                                </div>
                                <div class="col-11">
                                    <div class="pl-2">+91 98765 43210</div>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-1">
                                    <i class="fa fa-map-marker"></i>
                                </div>
                                <div class="col-11">
                                    <div class="pl-2">Mumbai</div>
                                </div>
                            </div>
                            <hr />
                            <div class="row align-items-center justify-content-between">
                                <div class="col">
                                    <a href="javascript:void(0)" id="btn-edit-employee-customer" class="btn btn-sm btn-outline-primary waves-effect waves-light"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
                                </div>
                                <div class="col text-right">
                                    <button id="btn-delete-employee-customer" class="btn btn-sm btn-outline-danger waves-effect waves-light"><i class="fa fa-trash"></i>&nbsp;Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card m-b-20">
                        <div class="card-body p-2">
                            <h4 class="mt-0 header-title">CHECKLIST</h4>
                            <ul class="list-inline widget-chart text-center">
                                <li class="list-inline-item" style="margin-right: 0rem;">
                                    <h6 class="m-0 small">841</h6>
                                    <p class="text-muted m-0 small">Completed</p>
                                </li>
                                <li class="list-inline-item" style="margin-right: 0rem;">
                                    <h6 class="m-0 small">241</h6>
                                    <p class="text-muted m-0 small">Pending</p>
                                </li>
                                <li class="list-inline-item" style="margin-right: 0rem;">
                                    <h6 class="m-0 small">130</h6>
                                    <p class="text-muted m-0 small">Upcoming</p>
                                </li>
                            </ul>
                            <div id="chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card m-b-20">
                        <div class="card-body p-2" style="height: 310px;">
                            <h4 class="mt-0 header-title">Compliants</h4>
                            <ul class="list-inline widget-chart text-center">
                                <li class="list-inline-item" style="margin-right: 0rem;">
                                    <h6 class="m-0 small">841</h6>
                                    <p class="text-muted m-0 small">Open</p>
                                </li>
                                <li class="list-inline-item" style="margin-right: 0rem;">
                                    <h6 class="m-0 small">241</h6>
                                    <p class="text-muted m-0 small">Pending</p>
                                </li>
                                <li class="list-inline-item" style="margin-right: 0rem;">
                                    <h6 class="m-0 small">130</h6>
                                    <p class="text-muted m-0 small">Completed</p>
                                </li>
                            </ul>

                            <div id="sparkline1" class="text-center"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-2">
                <div class="row">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" style="font-size: 16px;" data-toggle="tab" href="#home" role="tab">
                                <span class="d-none d-md-block">Complaint</span><span class="d-block d-md-none"><i class="mdi mdi-home-variant h5"></i></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" style="font-size: 16px;" data-toggle="tab" href="#profile" role="tab">
                                <span class="d-none d-md-block">Client Report</span><span class="d-block d-md-none"><i class="mdi mdi-account h5"></i></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="home" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card m-b-20 card-body">
                                <h3 class="card-title font-20 mt-0">Complaint-47648
                                    <div class="float-right" style="position: absolute; right: 70px; bottom: -35px;">
                                        <div class="mini-stat widget-chart-sm clearfix border-0">
                                            <span class="peity-donut float-left" data-peity='{ "fill": ["#ea553d", "#f2f2f2"], "innerRadius": 23, "radius": 32 }' data-width="60" data-height="60">520,134</span>
                                            <div class="mini-stat-info text-right">
                                                Exprise in
                                                <span class="counter text-danger" style="font-size: 19px;">15 Days</span>
                                            </div>
                                            <div class="clearfix"></div>
                                            <p class="text-muted mb-0 m-t-5" style="font-size: 13px;">Expiry date: 20-10-2022</p>
                                        </div>
                                    </div>
                                </h3>
                                <div class="row">
                                    <div class="col-md-3">
                                        Nature : Electrical
                                    </div>
                                    <div class="col-md-3">
                                        Type : O & M
                                    </div>
                                    <div class="col-md-3">
                                        PO No. : 86543
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        Region : South
                                    </div>
                                    <div class="col-md-3">
                                        Area : Banglore
                                    </div>
                                    <div class="col-md-3">
                                        Status : <span class="text-success"> In Contract</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card m-b-20 card-body">
                                <h3 class="card-title font-20 mt-0">Complaint-47648
                                    <div class="float-right" style="position: absolute; right: 70px; bottom: -35px;">
                                        <div class="mini-stat widget-chart-sm clearfix border-0">
                                            <span class="peity-donut float-left" data-peity='{ "fill": ["#ea553d", "#f2f2f2"], "innerRadius": 23, "radius": 32 }' data-width="60" data-height="60">520,134</span>
                                            <div class="mini-stat-info text-right">
                                                Exprise in
                                                <span class="counter text-danger" style="font-size: 19px;">15 Days</span>
                                            </div>
                                            <div class="clearfix"></div>
                                            <p class="text-muted mb-0 m-t-5" style="font-size: 13px;">Expiry date: 20-10-2022</p>
                                        </div>
                                    </div>
                                </h3>
                                <div class="row">
                                    <div class="col-md-3">
                                        Nature : Electrical
                                    </div>
                                    <div class="col-md-3">
                                        Type : O & M
                                    </div>
                                    <div class="col-md-3">
                                        PO No. : 86543
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        Region : South
                                    </div>
                                    <div class="col-md-3">
                                        Area : Banglore
                                    </div>
                                    <div class="col-md-3">
                                        Status : <span class="text-success"> In Contract</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card m-b-20 card-body">
                                <h3 class="card-title font-20 mt-0">Complaint-47648
                                    <div class="float-right" style="position: absolute; right: 70px; bottom: -35px;">
                                        <div class="mini-stat widget-chart-sm clearfix border-0">
                                            <span class="peity-donut float-left" data-peity='{ "fill": ["#ea553d", "#f2f2f2"], "innerRadius": 23, "radius": 32 }' data-width="60" data-height="60">520,134</span>
                                            <div class="mini-stat-info text-right">
                                                Exprise in
                                                <span class="counter text-danger" style="font-size: 19px;">15 Days</span>
                                            </div>
                                            <div class="clearfix"></div>
                                            <p class="text-muted mb-0 m-t-5" style="font-size: 13px;">Expiry date: 20-10-2022</p>
                                        </div>
                                    </div>
                                </h3>
                                <div class="row">
                                    <div class="col-md-3">
                                        Nature : Electrical
                                    </div>
                                    <div class="col-md-3">
                                        Type : O & M
                                    </div>
                                    <div class="col-md-3">
                                        PO No. : 86543
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        Region : South
                                    </div>
                                    <div class="col-md-3">
                                        Area : Banglore
                                    </div>
                                    <div class="col-md-3">
                                        Status : <span class="text-success"> In Contract</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="profile" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card m-b-20 card-body">
                                <h3 class="card-title font-20 mt-0">Client-47648
                                    <div class="float-right">
                                    </div>
                                </h3>
                                <div class="row">
                                    <div class="col-md-4">
                                        Nature : Electrical
                                    </div>
                                    <div class="col-md-4">
                                        Type : O & M
                                    </div>
                                    <div class="col-md-4">
                                        PO No. : 86543
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        Region : South
                                    </div>
                                    <div class="col-md-4">
                                        Area : Banglore
                                    </div>
                                    <div class="col-md-4">
                                        Status : <span class="text-success"> In Contract</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="row">
                <div class="col-12">
                    <div class="card m-b-20 border-0">
                        <div class="card-body" style="background-color: #f5f5f5;">


                        </div>
                    </div>
                </div>
            </div> -->
        </section>

    </div><!-- container -->

</div> <!-- Page content Wrapper -->

<script>
    const customer_id = "<?php echo $customer_id ?? 0; ?>";
</script>

<!-- Peity chart JS -->
<script src="<?php echo base_url() ?>assets/plugins/peity-chart/jquery.peity.min.js"></script>
<!--C3 Chart-->
<script src="<?php echo base_url() ?>assets/plugins/d3/d3.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/c3/c3.min.js"></script>
<script src="<?php echo base_url() ?>assets/pages/c3-chart-init.js"></script>

<script src="<?php echo base_url() ?>assets/plugins/sparklines-chart/jquery.sparkline.min.js"></script>
<script src="<?php echo base_url() ?>assets/pages/sparklines.init.js"></script>

<!-- KNOB JS -->
<script src="<?php echo base_url() ?>assets/plugins/jquery-knob/excanvas.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/jquery-knob/jquery.knob.js"></script>

<!-- Widget init JS -->
<script src="<?php echo base_url() ?>assets/pages/widget-init.js"></script>

<!-- Custom Js -->
<script type="text/javascript" src="<?php echo base_url('assets/js/include/check_login.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/employee/cam/customer_view.js'); ?>"></script>

<script>
    $(function() {
        $('[data-toggle="select2"]').select2();

        function loadInitFunctions() {
            loadEmpDetail(formApiUrl('employee/cam/customer/detail', {
                customer_id: customer_id
            }));
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