<div class="page-content-wrapper">

    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card m-b-20" id="product--deatils--area">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-12">
                                <h4 class="mt-0 header-title">
                                    <div class="text-right">
                                        <a href="<?= base_url() ?>admin/store/products/add" class="btn btn-outline-primary waves-effect waves-light">Add</a>
                                    </div>
                                </h4>
                            </div>
                        </div>

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
                            <div class="col-12" data-container="productListArea">
                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered dt-responsive nowrap">
                                        <thead>
                                            <tr>
                                                <th width="15%">Sl.No</th>
                                                <th>Name</th>
                                                <th>Category</th>
                                                <th>Sub Category</th>
                                                <th>Available Stock</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody data-container="productTlistArea">
                                        </tbody>
                                    </table>
                                </div>
                                <div data-pagination="productTlistArea" class="clearfix mb-4">
                                    <div class="list-pagination-label float-left"></div>
                                    <div class="list-pagination navigation float-right"></div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div><!-- container -->

</div> <!-- Page content Wrapper -->

<!-- store_product_quantity Modal -->
<div class="modal fade" id="quantityModal">
    <div class="modal-dialog modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="font-18 modal-title">Spare Parts Quantity</h4>
                <button type="button" data-dismiss="modal" class="close"><i class="mdi mdi-close"></i></button>
            </div>
            <div class="modal-body">
                <form id="quantityForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="control-label">Quantity<span class="text-danger"> *</span></label>
                                <input type="number" name="quantity" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <a href="javascript:void(0)" id="btn-reset-quantity-form" class="btn btn-secondary btn-sm" style="display:none;">
                            <i class="mdi mdi-reload"></i>&nbsp;Reset
                        </a>
                        <button type="submit" class="btn btn-indigo btn-sm">
                            <i class="mdi mdi-content-save"></i>&nbsp;Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Pagination -->
<script src="<?php echo base_url('assets/js/jquery.simplePagination.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js'); ?>"></script>

<!-- Custom Js -->
<script src="<?php echo base_url('assets/js/include/check_login.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/include/admin/store_product_list.js'); ?>"></script>

<script>
    $(function() {
        $('[data-toggle="select2"]').select2();

        function loadInitFunctions() {
            loadDetails(formApiUrl('admin/store/product/list'));
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