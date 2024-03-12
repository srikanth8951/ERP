<div class="page-content-wrapper">

    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card m-b-20" id="request--deatils--area">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-sm-12 col-md-6">
                                <div class="d-flex align-items-center w-25 d-search">
                                    <label class="mb-0 mr-2">Show </label>
                                    <select data-jy-length="record" name="length" class="custom-select custom-select-sm form-control form-control-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 text-md-right">
                                <div class="w-50 ml-auto">
                                    <div class="d-flex align-items-center d-search">
                                        <label class="mb-0 mr-2">Search:</label>
                                        <form data-jy-search="record">
                                            <div class="input-group">
                                                <input type="text" name="search" class="form-control form-control-sm" placeholder="" />
                                                <button type="submit" class="input-group-append input-group-text btn btn-sm"><i class="fa fa-search"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12" data-container="requestListArea">
                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered dt-responsive nowrap">
                                        <thead>
                                            <tr>
                                                <th>Request Number</th>
                                                <th>Title</th>
                                                <th>Status</th>
                                                <th>Engineer</th>
                                                <th>Created Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody data-container="requestTlistArea">
                                        </tbody>
                                    </table>
                                </div>
                                <div data-pagination="requestTlistArea" class="clearfix mb-4">
                                    <div class="list-pagination-label float-left"></div>
                                    <div class="list-pagination navigation float-right"></div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div><!-- container -->

</div> <!-- Page content Wrapper -->

<!-- Pagination -->
<script src="<?php echo base_url('assets/js/jquery.simplePagination.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js'); ?>"></script>

<!-- Custom Js -->
<script src="<?php echo base_url('assets/js/include/check_login.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/employee/rsd/store_request_list.js'); ?>"></script>

<script>
    $(function() {
        $('[data-toggle="select2"]').select2();

        function loadInitFunctions() {
            loadDetails(formApiUrl('employee/rsd/store_product/request/list'));
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