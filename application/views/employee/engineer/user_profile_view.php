<!-- C3 charts css -->
<link href="<?= base_url()?>assets/plugins/c3/c3.min.css" rel="stylesheet" type="text/css" />

<div class="page-content-wrapper">

    <div class="container-fluid" id="engineer--details--area">
        <style>
            .img_cls{
                width: 100px;
                border-radius: 6px;
            }
        </style>


        <section id="engineerDetailArea" style="display: none;">
            <div class="row">
                <div class="col-4" data-employee-detail="engineer">
                    <div class="card m-b-20">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <img class="img_cls" src="<?php echo base_url('assets/images/users/avatar.png'); ?>">
                                </div>
                                <div class="col-7 align-self-end">
                                    <b>National Head User</b><br>
                                    EMP-1001<br>
                                    All India Head
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
                                    <a href="javascript:void(0)" id="btn-edit-employee-engineer" class="btn btn-sm btn-outline-primary waves-effect waves-light"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
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
            <div class="row">
                <div class="col-12">
                    <div class="card m-b-20">
                        <div class="card-body">

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                                        <span class="d-none d-md-block">Compliants</span><span class="d-block d-md-none"><i class="mdi mdi-home-variant h5"></i></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                                        <span class="d-none d-md-block">Tab1</span><span class="d-block d-md-none"><i class="mdi mdi-account h5"></i></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#messages" role="tab">
                                        <span class="d-none d-md-block">Tab2</span><span class="d-block d-md-none"><i class="mdi mdi-email h5"></i></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#settings" role="tab">
                                        <span class="d-none d-md-block">Tab3</span><span class="d-block d-md-none"><i class="mdi mdi-settings h5"></i></span>
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active p-3" id="home" role="tabpanel">
                                    <p class="font-14 mb-0">
                                        Raw denim you probably haven't heard of them jean shorts Austin.
                                        Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache
                                        cliche tempor, williamsburg carles vegan helvetica. Reprehenderit
                                        butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi,
                                        qui irure terry richardson ex squid. Aliquip placeat salvia cillum
                                        iphone. Seitan aliquip quis cardigan american apparel, butcher
                                        voluptate nisi qui.
                                    </p>
                                </div>
                                <div class="tab-pane p-3" id="profile" role="tabpanel">
                                    <p class="font-14 mb-0">
                                        Food truck fixie locavore, accusamus mcsweeney's marfa nulla
                                        single-origin coffee squid. Exercitation +1 labore velit, blog
                                        sartorial PBR leggings next level wes anderson artisan four loko
                                        farm-to-table craft beer twee. Qui photo booth letterpress,
                                        commodo enim craft beer mlkshk aliquip jean shorts ullamco ad
                                        vinyl cillum PBR. Homo nostrud organic, assumenda labore
                                        aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr,
                                        vero magna velit sapiente labore stumptown. Vegan fanny pack
                                        odio cillum wes anderson 8-bit.
                                    </p>
                                </div>
                                <div class="tab-pane p-3" id="messages" role="tabpanel">
                                    <p class="font-14 mb-0">
                                        Etsy mixtape wayfarers, ethical wes anderson tofu before they
                                        sold out mcsweeney's organic lomo retro fanny pack lo-fi
                                        farm-to-table readymade. Messenger bag gentrify pitchfork
                                        tattooed craft beer, iphone skateboard locavore carles etsy
                                        salvia banksy hoodie helvetica. DIY synth PBR banksy irony.
                                        Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh
                                        mi whatever gluten-free, carles pitchfork biodiesel fixie etsy
                                        retro mlkshk vice blog. Scenester cred you probably haven't
                                        heard of them, vinyl craft beer blog stumptown. Pitchfork
                                        sustainable tofu synth chambray yr.
                                    </p>
                                </div>
                                <div class="tab-pane p-3" id="settings" role="tabpanel">
                                    <p class="font-14 mb-0">
                                        Trust fund seitan letterpress, keytar raw denim keffiyeh etsy
                                        art party before they sold out master cleanse gluten-free squid
                                        scenester freegan cosby sweater. Fanny pack portland seitan DIY,
                                        art party locavore wolf cliche high life echo park Austin. Cred
                                        vinyl keffiyeh DIY salvia PBR, banh mi before they sold out
                                        farm-to-table VHS viral locavore cosby sweater. Lomo wolf viral,
                                        mustache readymade thundercats keffiyeh craft beer marfa
                                        ethical. Wolf salvia freegan, sartorial keffiyeh echo park
                                        vegan.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div><!-- container -->

</div> <!-- Page content Wrapper -->


<!--C3 Chart-->
<script src="<?php echo base_url() ?>assets/plugins/d3/d3.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/c3/c3.min.js"></script>
<script src="<?php echo base_url() ?>assets/pages/c3-chart-init.js"></script>

<script src="<?php echo base_url() ?>assets/plugins/sparklines-chart/jquery.sparkline.min.js"></script>
<script src="<?php echo base_url() ?>assets/pages/sparklines.init.js"></script>

<!-- Custom Js -->
<script type="text/javascript" src="<?php echo base_url('assets/js/include/employee/check_login.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/employee/engineer/profile_view.js'); ?>"></script>

<script>
    $(function() {
        $('[data-toggle="select2"]').select2();

        function loadInitFunctions() {
            loadEmpDetail(formApiUrl('employee/engineer/profile/detail'));
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