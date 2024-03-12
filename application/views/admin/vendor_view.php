<!-- C3 charts css -->
<link href="<?= base_url() ?>assets/plugins/c3/c3.min.css" rel="stylesheet" type="text/css" />

<div class="page-content-wrapper">

    <div class="container-fluid" id="empvendor--deatils--area">
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
                    <a href="<?php echo base_url(); ?>admin/vendor" class="btn btn-outline-secondary mt-1"><i class="mdi mdi-chevron-double-left"></i> Back</a>
                </div>
            </div>
        </div>

        <section id="empVendorNewArea" style="display: none;">
            <div class="row">
                <div class="col-12">
                    <div class="card m-b-20">
                        <div class="card-body text-center">
                            <h4 class="mt-0 mb-4">Vendor </h4>
                            <a href="<?php echo base_url('admin/vendor/add'); ?>" class="btn btn-sm btn-primary waves-effect waves-light"><i class="fa fa-plus"></i>&nbsp;Add</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="empVendorDetailArea" style="display: none;">
            <div class="row">
                <div class="col-4" data-employee-detail="vendor">
                    <div class="card m-b-20">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <img class="img_cls" src="<?php echo base_url('assets/images/users/avatar.png'); ?>">
                                </div>
                                <div class="col-7 align-self-end">
                                    <b>Vendor User</b><br>
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
                                    <a href="javascript:void(0)" id="btn-edit-employee-vendor" class="btn btn-sm btn-outline-primary waves-effect waves-light"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
                                </div>
                                <div class="col text-right">
                                    <button id="btn-delete-employee-vendor" class="btn btn-sm btn-outline-danger waves-effect waves-light"><i class="fa fa-trash"></i>&nbsp;Remove</button>
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
        </section>

    </div><!-- container -->

</div> <!-- Page content Wrapper -->

<script>
    const vendor_id = "<?php echo $vendor_id ?? 0; ?>";
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
<script type="text/javascript" src="<?php echo base_url('assets/js/include/admin/vendor_view.js'); ?>"></script>

<script>
    $(function() {
        $('[data-toggle="select2"]').select2();

        function loadInitFunctions() {
            loadEmpDetail(formApiUrl('admin/vendor/detail', {
                vendor_id: vendor_id
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