<div class="page-content-wrapper">
    
    <div class="container-fluid" id="vendor--deatils--area">
    <div class="card card-body p-2">
            <div class="row">
                <div class="col">
                    <form id="searchForm" class="mt-1">
                        <div class="row height d-flex justify-content-center align-items-center">
                            <div class="col-md-12">
                                <div class="form">
                                    <i class="mdi mdi-magnify">
                                    </i> <input type="text" class="form-control form-input" name="search" placeholder="Search here..."> <span class="left-pan"></span>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search" />
                            <button type="submit" class="input-group-append input-group-text btn btn-sm waves-effect waves-light">Search</button>
                        </div> -->
                    </form>
                </div>

                <div class="col text-right">
                    <a data-toggle="modal" href="#vendorUploadModal" data-backdrop="static" data-keyboard="false" class="btn btn-outline-info mt-1"><i class=" mdi mdi-folder-upload"></i> Upload</a>
                    <a href="<?php echo base_url('admin/vendor/add') ?>" class="btn btn-outline-primary mt-1"><i class="mdi mdi-account-plus"></i> Add</a>
                    <a href="<?php echo base_url(); ?>admin/dashboard" class="btn btn-outline-secondary mt-1"><i class="mdi mdi-chevron-double-left"></i> Back</a>
                </div>
            </div>
        </div>
        <br>
        <div class="row" data-container="VendorArea"></div>

        <div class="row align-items-center justify-content-between" data-pagination="VendorArea">
            <div class="col">
                <div class="list-pagination-label float-left"></div>
                <div class="list-pagination navigation float-right"></div>
            </div>
        </div>
        <!-- end row -->

    </div><!-- container -->

</div> <!-- Page content Wrapper -->

<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.simplePagination.js'); ?>"></script>

<!-- Custom Js -->
<script type="text/javascript" src="<?php echo base_url('assets/js/include/check_login.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/include/admin/vendor_list.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js'); ?>"></script>
<!-- <script src="<?php echo base_url('assets/js/include/admin/upload_engineer.js'); ?>"></script> -->
<script>
    $(function() {
        $('[data-toggle="select2"]').select2();

        function loadInitFunctions() {
            loadVendorDetails(formApiUrl('admin/vendor/list'));
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